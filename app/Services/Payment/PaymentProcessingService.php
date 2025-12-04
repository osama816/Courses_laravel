<?php

namespace App\Services\Payment;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentProcessingService
{
    /**
     * Process successful payment
     */
    public function processSuccessfulPayment(array $paymentData): array
    {
        DB::beginTransaction();

        try {
            // Get booking
            $booking = Booking::with('course')->findOrFail($paymentData['booking_id']);

            // Update booking
            $this->updateBookingStatus($booking);

            // Create payment record
            $payment = $this->createPaymentRecord($booking, $paymentData);

            // Create invoice
            $invoice = $this->createInvoice($booking, $payment, $paymentData);

            DB::commit();

            Log::channel('payment')->info('Payment processed successfully', [
                'booking_id' => $booking->id,
                'payment_id' => $payment->id,
                'invoice_id' => $invoice->id,
            ]);

            return [
                'success' => true,
                'booking' => $booking,
                'payment' => $payment,
                'invoice' => $invoice,
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('payment')->error('Payment processing failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update booking status and course seats
     */
    private function updateBookingStatus(Booking $booking): void
    {
        $booking->update(['status' => 'confirmed']);
        $booking->course->decrement('available_seats');
    }

    /**
     * Create payment record
     */
    private function createPaymentRecord(Booking $booking, array $paymentData): Payment
    {
        return Payment::create([
            'booking_id' => $booking->id,
            'payment_method' => $paymentData['payment_method'],
            'amount' => $paymentData['amount'],
            'status' => $paymentData['status'],
            'transaction_id' => $paymentData['TransactionId'],
            'paid_at' => now(),
        ]);
    }

    /**
     * Create invoice
     */
    private function createInvoice(Booking $booking, Payment $payment, array $paymentData): Invoice
    {
        return Invoice::create([
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'user_id' => $booking->user_id,
            'payment_id' => $payment->id,
            'booking_id' => $booking->id,
            'amount' => $paymentData['amount'],
            'status' => 'paid',
            'issued_at' => now(),
        ]);
    }
}
