<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Download invoice as PDF
     */
    public function download($id)
    {
        $invoice = Invoice::with(['payment.booking.course', 'payment.booking.user', 'user'])
            ->findOrFail($id);

        // Check if user owns this invoice
        if (Auth::check() && $invoice->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to invoice');
        }

        // If PDF already exists, return it
        if ($invoice->invoice_path && Storage::exists($invoice->invoice_path)) {
            return Storage::download($invoice->invoice_path, $invoice->invoice_number . '.pdf');
        }

        // Generate PDF
        $pdf = Pdf::loadView('invoices.pdf', [
            'invoice' => $invoice,
            'payment' => $invoice->payment,
            'booking' => $invoice->booking,
            'course' => $invoice->booking->course,
            'user' => $invoice->user,
        ]);

        // Store PDF
        $filename = 'invoices/' . $invoice->invoice_number . '.pdf';
        Storage::put($filename, $pdf->output());

        // Update invoice path
        $invoice->update(['invoice_path' => $filename]);

        return $pdf->download($invoice->invoice_number . '.pdf');
    }

    /**
     * Show invoice details
     */
    public function show($id)
    {
        $invoice = Invoice::with(['payment.booking.course', 'payment.booking.user', 'user'])
            ->findOrFail($id);

        // Check if user owns this invoice
        if (Auth::check() && $invoice->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to invoice');
        }

        return view('invoices.show', compact('invoice'));
    }
}

