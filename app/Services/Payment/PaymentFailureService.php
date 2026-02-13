<?php

namespace App\Services\Payment;

use App\Models\Booking;
use App\Models\SupportTicket;
use Illuminate\Support\Facades\Log;

class PaymentFailureService
{
    /**
     * Handle payment failure
     */
    public function handlePaymentFailure(array $paymentData): void
    {
        Log::channel('payment')->warning('Payment failed', $paymentData);

        $bookingId = $paymentData['booking_id'] ?? null;
        if ($bookingId) {
            $booking = Booking::find($bookingId);
            if ($booking) {
                $booking->status = 'failed';
                $booking->save();

                Log::channel('payment')->info("Booking ID {$bookingId} marked as failed.");
            } else {
                Log::channel('payment')->error("Booking ID {$bookingId} not found for payment failure.");
            }
        } else {
            Log::channel('payment')->error('No booking ID provided in payment failure data.');
        }
    }


}
