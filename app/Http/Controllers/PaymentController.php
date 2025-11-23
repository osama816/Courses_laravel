<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Interfaces\PaymentGatewayInterface;

class PaymentController extends Controller
{
    protected PaymentGatewayInterface $paymentGateway;

    public function __construct(PaymentGatewayInterface $paymentGateway)
    {

        $this->paymentGateway = $paymentGateway;
    }


    public function paymentProcess(Request $request)
    {
//dd($request);
        $response = $this->paymentGateway->sendPayment($request);
        if($request->is('api/*')){

            return response()->json($response, 200);
        }
     //dd($response);
     //return redirect($response->getData(true)['data']['url']);

        return redirect($response['data']['url']);

    }


    public function callBack(Request $request)
    {
       // dd($request->all());
        try {
            // Log callback for debugging
            Log::channel('payment')->info('Payment callback received', $request->all());

            // Verify payment through gateway
            $paymentSuccess = $this->paymentGateway->callBack($request);
           // dd($paymentSuccess);
            if ($paymentSuccess['status'] === 'Paid') {
                DB::beginTransaction();

                // Extract booking_id from callback (adjust based on your payment gateway response)
                $bookingId = $paymentSuccess['booking_id'];
                $payment_method = $paymentSuccess['payment_method'];

                if (!$bookingId) {
                    Log::channel('payment')->warning('Booking ID not found in callback');
                    DB::rollBack();
                    return redirect()->route('payment.failed');
                }

                // Get booking
                $booking = Booking::with('course')->findOrFail($bookingId);

                // Update booking status
                $booking->update([
                    'status' => 'confirmed',
                ]);

                // Decrease available seats
                $booking->course->decrement('available_seats');

                // Create payment record
                $payment=Payment::create([
                    'booking_id' => $booking->id,
                    'payment_method' => $payment_method,
                    'amount' => $paymentSuccess['amount'], // Convert from cents
                    'status' => $paymentSuccess['status'],
                    'transaction_id' => $paymentSuccess['TransactionId'],  //$request->input('id') ?? $request->input('transaction_id'),
                    'paid_at' => now(),
                ]);

                DB::commit();

                return redirect()->route('payment.success')
                    ->with('booking_id', $booking->id);
            }

            // Payment failed
            Log::channel('payment')->warning('Payment verification failed', $request->all());

            return redirect()->route('payment.failed');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('payment')->error('Payment callback error: ' . $e->getMessage());

            return redirect()->route('payment.failed');
        }
    }


    public function success()
    {

        return view('payment/payment-success');
    }
    public function failed()
    {

        return view('payment/payment-failed');
    }
}
