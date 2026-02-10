@extends('layouts.app_wep')

@section('title', __('payment.success_title') . ' - CourseBook')

@section('content')
<main class="min-h-[calc(100vh-80px)] flex items-center justify-center p-4 bg-slate-50/50 dark:bg-slate-950/50">
    <div class="max-w-2xl w-full">
        <!-- Success Card -->
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-3xl overflow-hidden p-8 md:p-12 text-center">
            <!-- Success Icon -->
            <div class="w-24 h-24 mx-auto mb-6 bg-linear-to-br from-emerald-400 to-emerald-600 rounded-full flex items-center justify-center shadow-lg shadow-emerald-500/30 animate-bounce">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <!-- Title -->
            <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-3">
                {{ __('payment.success_title') }}
            </h1>
            
            <!-- Message -->
            <p class="text-slate-600 dark:text-slate-400 text-lg mb-8">
                {{ __('payment.success_message') }}
            </p>

            <!-- Invoice Details -->
            @if(isset($invoice_id) && $invoice)
                <div class="bg-slate-50 dark:bg-slate-800 rounded-2xl p-6 mb-6 border border-slate-200/50 dark:border-slate-700/50">
                    <div class="flex items-center justify-center gap-2 mb-3">
                        <i class="fa-solid fa-file-invoice text-primary text-xl"></i>
                        <p class="text-lg font-bold text-slate-900 dark:text-white">
                            {{ __('Invoice') }} #{{ $invoice->invoice_number }}
                        </p>
                    </div>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">
                        <i class="fa-solid fa-graduation-cap text-primary me-2"></i>
                        {{ __('Course') }}: <span class="font-semibold">{{ $invoice->payment->booking->course->title }}</span>
                    </p>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-3 justify-center">
                        <a href="{{ route('invoice.download', $invoice->id) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary hover:bg-primary-hover text-white font-bold rounded-xl shadow-lg shadow-primary/20 transition-all hover:scale-105 active:scale-95">
                            <i class="fa-solid fa-download"></i>
                            {{ __('Download Invoice') }}
                        </a>
                        <a href="{{ route('invoice.show', $invoice->id) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-panel hover:bg-slate-100 dark:hover:bg-slate-700 text-text-base font-bold rounded-xl border border-slate-200 dark:border-slate-700 transition-all hover:scale-105 active:scale-95">
                            <i class="fa-solid fa-eye"></i>
                            {{ __('View Invoice') }}
                        </a>
                    </div>
                </div>
            @endif

            <!-- Navigation -->
            <div class="flex flex-wrap gap-3 justify-center">
                @if(isset($booking_id))
                    <a href="{{ route('bookings.show', $booking_id) }}" class="inline-flex items-center gap-2 px-8 py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-text-base font-bold rounded-xl transition-all hover:scale-105 active:scale-95">
                        <i class="fa-solid fa-calendar-check"></i>
                        {{ __('View Booking') }}
                    </a>
                @endif
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-text-base font-bold rounded-xl transition-all hover:scale-105 active:scale-95">
                    <i class="fa-solid fa-home"></i>
                    {{ __('Back to Home') }}
                </a>
            </div>
        </div>

        <!-- Decorative Elements -->
        <div class="absolute top-10 ltr:left-10 rtl:right-10 w-32 h-32 bg-emerald-500/10 rounded-full blur-3xl -z-10"></div>
        <div class="absolute bottom-10 ltr:right-10 rtl:left-10 w-40 h-40 bg-primary/10 rounded-full blur-3xl -z-10"></div>
    </div>
</main>
@endsection
