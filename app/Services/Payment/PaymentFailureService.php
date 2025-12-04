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
            $this->createSupportTicket($bookingId, $paymentData);
        }
    }

    /**
     * Create support ticket for failed payment
     */
    private function createSupportTicket(int $bookingId, array $paymentData): void
    {
        try {
            $booking = Booking::with('user')->find($bookingId);

            if ($booking && $booking->user) {
                SupportTicket::create([
                    'ticket_number' => SupportTicket::generateTicketNumber(),
                    'user_id' => $booking->user_id,
                    'subject' => 'Payment failed - Transaction number:' . ($paymentData['TransactionId'] ?? 'unknown'),
                    'message' => 'Payment verification failed. Please review your payment details and contact support if necessary.',
                    'priority' => 'high',
                    'status' => 'open',
                ]);

                Log::channel('payment')->info('Support ticket created for failed payment', [
                    'booking_id' => $bookingId,
                    'user_id' => $booking->user_id,
                ]);
            }
        } catch (\Exception $e) {
            Log::channel('payment')->error('Failed to create support ticket: ' . $e->getMessage());
        }
    }
}
