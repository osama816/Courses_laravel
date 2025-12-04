@extends('layouts.app_wep')

@section('title', __('Invoice') . ' #' . $invoice->invoice_number)

@section('content')
<main class="py-5 flex-grow-1">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                <i class="bi bi-receipt me-2"></i>{{ __('Invoice') }} #{{ $invoice->invoice_number }}
                            </h4>
                            <a href="{{ route('invoice.download', $invoice->id) }}" class="btn btn-light btn-sm">
                                <i class="bi bi-download me-1"></i>{{ __('Download PDF') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-3">{{ __('Bill To') }}</h6>
                                <p class="mb-1"><strong>{{ $invoice->user->name }}</strong></p>
                                <p class="mb-0 text-muted">{{ $invoice->user->email }}</p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <h6 class="text-muted mb-3">{{ __('Invoice Details') }}</h6>
                                <p class="mb-1"><strong>{{ __('Date') }}:</strong> {{ $invoice->issued_at->format('F d, Y') }}</p>
                                <p class="mb-1"><strong>{{ __('Status') }}:</strong>
                                    <span class="badge bg-{{ $invoice->status === 'paid' ? 'success' : 'warning' }}">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <hr>

                        <div class="mb-4">
                            <h6 class="text-muted mb-3">{{ __('Payment Information') }}</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>{{ __('Payment Method') }}:</strong> {{ ucfirst($invoice->payment->payment_method) }}</p>
                                    <p class="mb-1"><strong>{{ __('Transaction ID') }}:</strong> {{ $invoice->payment->transaction_id }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>{{ __('Paid At') }}:</strong>
                                        {{ $invoice->payment->paid_at ? $invoice->payment->paid_at->format('F d, Y H:i') : 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="mb-4">
                            <h6 class="text-muted mb-3">{{ __('Course Information') }}</h6>
                            <div class="table-responsive ">
                                <table  style="background-color:var(--bg);">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Course') }}</th>
                                            <th class="text-end">{{ __('Amount') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <strong>{{ $invoice->booking->course->title }}</strong><br>
                                                <small class="text-muted">{{ $invoice->booking->course->description }}</small>
                                            </td>
                                            <td class="text-end">${{ number_format($invoice->amount, 2) }}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class="text-end">{{ __('Total') }}:</th>
                                            <th class="text-end">${{ number_format($invoice->amount, 2) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <a href="{{ route('invoice.download', $invoice->id) }}" class="btn btn-primary">
                                <i class="bi bi-download me-2"></i>{{ __('Download Invoice PDF') }}
                            </a>
                            <a href="{{ route('bookings.show', $invoice->booking_id) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>{{ __('Back to Booking') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

