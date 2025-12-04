<?php

namespace App\Mcp\Tools;

use Illuminate\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;
use App\Models\Invoice;
use App\Models\Payment;

class GenerateInvoiceDataTool extends Tool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        Generate invoice data for a payment. Returns invoice details including invoice number, amount, payment details, and course information.
        Use this to provide students with their invoice information or to resend invoice details.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
                $validated = $request->validate([
              'payment_id'=>['required','exists:payments,id'],
                'invoice_id'=>['required','exists:invoices,id'],
        ]);
        $paymentId = $validated['payment_id'] ?? null;
        $invoiceId = $validated['invoice_id'] ?? null;

        if (!$paymentId && !$invoiceId) {
            return Response::text('Error: Either payment_id or invoice_id is required');
        }

        $invoice = null;
        if ($invoiceId) {
            $invoice = Invoice::with(['payment.booking.course', 'payment.booking.user'])->find($invoiceId);
        } elseif ($paymentId) {
            $invoice = Invoice::where('payment_id', $paymentId)
                ->with(['payment.booking.course', 'payment.booking.user'])
                ->first();
        }

        if (!$invoice) {
            return Response::text('Error: Invoice not found. The payment may not have an invoice yet.');
        }

        $data = [
            'invoice_id' => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
            'amount' => $invoice->amount,
            'status' => $invoice->status,
            'issued_at' => $invoice->issued_at->format('Y-m-d H:i:s'),
            'payment' => [
                'payment_id' => $invoice->payment->id,
                'payment_method' => $invoice->payment->payment_method,
                'transaction_id' => $invoice->payment->transaction_id,
                'paid_at' => $invoice->payment->paid_at ? $invoice->payment->paid_at->format('Y-m-d H:i:s') : null,
            ],
            'course' => [
                'course_id' => $invoice->booking->course->id ?? null,
                'course_title' => $invoice->booking->course->title ?? 'N/A',
            ],
            'user' => [
                'user_id' => $invoice->user->id,
                'user_name' => $invoice->user->name,
                'user_email' => $invoice->user->email,
            ],
            'has_pdf' => !empty($invoice->invoice_path),
            'download_url' => $invoice->invoice_path ? route('invoice.download', $invoice->id) : null,
        ];

        return Response::text(json_encode($data, JSON_PRETTY_PRINT));
    }

    /**
     * Get the tool's input schema.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'payment_id' => $schema->integer()
                ->title('Payment ID')
                ->description('The ID of the payment to get invoice for')
                ,
            'invoice_id' => $schema->integer()
                ->title('Invoice ID')
                ->description('The ID of the invoice to get data for')
                ,
        ];
    }
}

