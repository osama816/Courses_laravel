<?php

namespace App\Mcp\Tools;

use App\Models\Booking;
use App\Models\Payment;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;
use Illuminate\Support\Facades\Auth;
use Illuminate\JsonSchema\JsonSchema;

class GetPaymentStatusTool extends Tool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        Get the payment status for a booking or payment. Returns payment details including amount, status, transaction ID, and payment method.
        Use this to help students check their payment status and resolve payment-related issues.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
  public function handle(Request $request)
{
    // Validate user_id
        $validated = $request->validate([
            'user_id' => ['sometimes', 'exists:users,id'],
        ]);

    $userId = $validated['user_id']?? Auth::id();

    $bookings = \App\Models\Booking::with(['payment', 'course', 'user', 'payment.invoice'])
        ->where('user_id', $userId)
        ->get();

    if ($bookings->isEmpty()) {
        return Response::json([
            'success' => false,
            'message' => 'No bookings found for this user.'
        ]);
    }


    $payments = [];

    foreach ($bookings as $booking) {
        if ($booking->payment) {
            $payments[] = [
                'booking_id' => $booking->id,
                'payment_id' => $booking->payment->id,
                'amount' => $booking->payment->amount,
                'status' => $booking->payment->status,
                'payment_method' => $booking->payment->payment_method,
                'transaction_id' => $booking->payment->transaction_id,
                'paid_at' => optional($booking->payment->paid_at)->format('Y-m-d H:i:s'),
                'course_title' => $booking->course->title ?? 'N/A',
                'user_name' => $booking->user->name ?? 'N/A',
                'has_invoice' => $booking->payment->invoice ? true : false,
                'invoice_number' => $booking->payment->invoice->invoice_number ?? null,
                'invoice_url' => $booking->payment->invoice->invoice_path ?? null,
                'invoice_id' => $booking->payment->invoice->id ?? null,
            ];
        }
    }

    return [
        'success' => true,
        'payments' => $payments
    ];
}


    /**
     * Get the tool's input schema.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'user_id' => $schema->integer()
                ->title('User ID')
                ->description('The ID of the user to get courses for')
                ->required(),
        ];
    }
}
