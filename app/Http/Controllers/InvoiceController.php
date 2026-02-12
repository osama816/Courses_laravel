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
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 300);

        $invoice = Invoice::with(['payment.booking.course', 'payment.booking.user', 'user'])
            ->findOrFail($id);

        if (Auth::check() && $invoice->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to invoice');
        }

        if ($invoice->invoice_path && Storage::exists($invoice->invoice_path)) {
            return Storage::download($invoice->invoice_path, $invoice->invoice_number . '.pdf');
        }

        $pdf = Pdf::loadView('invoices.pdf', [
            'invoice' => $invoice,
            'payment' => $invoice->payment,
            'booking' => $invoice->booking,
            'course' => $invoice->booking->course,
            'user' => $invoice->user,
        ]);

        $filename = 'invoices/' . $invoice->invoice_number . '.pdf';
        Storage::put($filename, $pdf->output());

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

        if (Auth::check() && $invoice->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to invoice');
        }

        return view('invoices.show', compact('invoice'));
    }
}

