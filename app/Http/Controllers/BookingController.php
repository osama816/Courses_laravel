<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;

class BookingController extends Controller
{

    public function index()
    {
        $bookings = Booking::with(['payment', 'invoice'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('bookings.show_All', compact('bookings'));
    }


    public function create($course)
    {
        $course = Course::findOrFail($course);

        $existingBooking = Booking::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->first();

        if ($existingBooking) {
            return redirect()->route('bookings.show', $existingBooking->id)
                ->with('info', __('booking.already_booked'));
        }

        return view('bookings.create', ['course' => $course]);
    }



    public function store(StoreBookingRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            $course = Course::findOrFail($validated['course_id']);
            $amount = $course->price;
            $user = Auth::user();

            if ($course->available_seats <= 0) {
                return redirect()->back()
                    ->with('error', __('booking.no_seats_available'))
                    ->withInput();
            }

            if (in_array($validated['payment_method'], ['paymob', 'myfatoorah'])) {
                $intentToken = 'booking_intent_' . uniqid() . '_' . time();

                \Illuminate\Support\Facades\Cache::put($intentToken, [
                    'user_id' => $user->id,
                    'course_id' => $validated['course_id'],
                    'payment_method' => $validated['payment_method'],
                    'amount' => $amount,
                ], now()->addHour());

                DB::commit();

                if ($validated['payment_method'] === 'paymob') {
                    $paymentData = [
                        "amount_cents" => $amount * 100,
                        "currency" => "EGP",
                        "intent_token" => $intentToken,
                    ];
                    return redirect()->route('payment.booking', ['gateway_type' => 'paymob'] + $paymentData);
                }

                if ($validated['payment_method'] === 'myfatoorah') {
                    $paymentData = [
                        "InvoiceValue" => $amount,
                        "currency" => "EGP",
                        "CustomerName" => $user->name,
                        "CustomerEmail" => !empty($user->email) ? $user->email : "guest@example.com",
                        "intent_token" => $intentToken,
                    ];
                    return redirect()->route('payment.booking', ['gateway_type' => 'myfatoorah'] + $paymentData);
                }
            }

            if ($validated['payment_method'] === 'cash') {
                $booking = Booking::create([
                    'user_id' => $user->id,
                    'course_id' => $validated['course_id'],
                    'status' => 'pending',
                ]);

                Payment::create([
                    'booking_id'      => $booking->id,
                    'payment_method'  => 'cash',
                    'amount'          => "$amount",
                    'status'          => 'pending',
                    'transaction_id'  => 'CASH-PENDING-' . uniqid(),
                    'paid_at'         => null,
                ]);

                DB::commit();

                return redirect()->route('bookings.show', $booking->id);
            }

            DB::commit();
            return redirect()->route('bookings.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', __('booking.error_occurred') . ': ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Booking $booking)
    {
        $booking->load(['course', 'payment', 'invoice']);
        return view('bookings.show', ['booking' => $booking, 'course' => $booking->course, 'invoice' => $booking->invoice]);
    }

    public function edit(Booking $booking)
    {
        //
    }

    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        //
    }


    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('bookings.index')
            ->with('success', __('booking.booking_cancelled'));
    }

    public function undoDelete(Request $request, Booking $booking)
    {
        if ($request->user()->hasRole('admin')) {
            $booking->restore();
            return redirect()->route('bookings.index')
                ->with('success', 'booking.booking_restored');
        } else {
            return redirect()->route('bookings.index')
                ->with('error', 'booking.unauthorized');
        }
    }
}
