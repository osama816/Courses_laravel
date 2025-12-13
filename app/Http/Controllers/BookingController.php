<?php

namespace App\Http\Controllers;

use App\Models\course;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())->get();
        return view('bookings.show_All', ['bookings' => $bookings]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($course)
    {
        $course = Course::findOrFail($course);
        // if ($course->available_seats <= 0) {
        //     return redirect()->back()->with('error', __('booking.no_seats_available'));
        // }

        // Check if user already booked this course
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingRequest $request)
    {

        $validated = $request->validated();
        try {
            DB::beginTransaction();

            // Get the course
            $course = Course::findOrFail($validated['course_id']);
            $amount = $course->price;
            $user = Auth::user();

            // Check available seats
            if ($course->available_seats <= 0) {
                return redirect()->back()
                    ->with('error', __('booking.no_seats_available'))
                    ->withInput();
            }

            // Create booking
            $booking = Booking::create([
                'user_id' => $user->id,
                'course_id' => $validated['course_id'],
                'status' => 'pending',
            ]);

            // If payment method is online (Paymob), redirect to payment
            if ($validated['payment_method'] === 'paymob') {
                // Prepare payment data

                $paymentData = [
                    "amount_cents" => $amount * 100,
                    "currency" => "EGP",
                    "shipping_data" => [
                        'booking_id' => $booking->id,
                        'api_source' => 'INVOICE',
                    ],
                ];

                DB::commit();

                // Redirect to payment API
                return redirect()->route('payment.booking', ['gateway_type' => 'paymob'] + $paymentData);
            }

            if ($validated['payment_method'] === 'myfatoorah') {
                // Prepare payment data
                $paymentData = [
                    "InvoiceValue" => $amount,
                    "currency" => "EGP",
                    "CustomerName" => $user->name,
                    "CustomerEmail" => !empty($user->email) ? $user->email : "guest@example.com",
                    'booking_id' => $booking->id,
                ];
                DB::commit();

                // Redirect to payment API
                return redirect()->route('payment.booking', ['gateway_type' => 'myfatoorah'] + $paymentData);
            }
            if ($validated['payment_method'] === 'cash') {
                // Create pending payment record for cash
                Payment::create([
                    'booking_id'      => $booking->id,
                    'payment_method'  => 'cash',
                    'amount'          => "$amount",
                    'status'          => 'pending',
                    'transaction_id'  => 'CASH-PENDING-' . uniqid(), // optional unique ref
                    'paid_at'         => null,
                ]);

                DB::commit();

                return redirect()->route('bookings.show', $booking->id);
            }


            // For cash payment, just confirm the booking
            DB::commit();

            return redirect()->route('bookings.show', $booking->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', __('booking.error_occurred') . ': ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        $course = Course::findOrFail($booking->course_id);
        return view('bookings.show', ['booking' => $booking, 'course' => $course]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
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
