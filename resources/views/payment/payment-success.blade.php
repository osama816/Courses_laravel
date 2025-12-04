@extends('layouts.app_wep')

@section('title', __('payment.success_title'))

@section('content')

<main class="py-5 flex-grow-1">
<div class="contant">
    <div class="text-center mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#28a745" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mb-3">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>
    </div>
    <h1>{{ __('payment.success_title') }}</h1>
    <p>{{ __('payment.success_message') }}</p>

    @if(isset($invoice_id))
        @if($invoice)
            <div class="mt-4 p-3 bg-light rounded">
                <p class="mb-2"><strong>{{ __('Invoice') }} #{{ $invoice->invoice_number }}</strong></p>
                <p class="mb-3 text-muted small">{{ __('Course') }}: {{ $invoice->booking->course->title }}</p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('invoice.download', $invoice->id) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-download me-1"></i>{{ __('Download Invoice') }}
                    </a>
                    <a href="{{ route('invoice.show', $invoice->id) }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-eye me-1"></i>{{ __('View Invoice') }}
                    </a>
                </div>
            </div>
        @endif
    @endif

    @if(isset($booking_id))
        <div class="mt-3">
            <a href="{{ route('bookings.show', $booking_id) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>{{ __('View Booking') }}
            </a>
        </div>
    @endif
</div>
</main>
  <style>
        h1 {
            color: #28a745;
            font-size: 2em;
            margin-bottom: 10px;
        }
        p {
            color: #555;
            font-size: 1.2em;
        }
        .contant{

            text-align: center;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
            max-width: 600px;
            margin: 0 auto;

        }
    </style>
@endsection



