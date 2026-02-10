@extends('layouts.app_wep')

@section('title', (string) __('Invoice') . ' #' . $invoice->invoice_number . ' - CourseBook')

@section('content')
<main class="min-h-[calc(100vh-80px)] bg-slate-50/50 dark:bg-slate-950/50 py-12 px-4 transition-colors duration-300">
    <div class="max-w-4xl mx-auto">
        
        <!-- Header Actions -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
            <div class="flex items-center gap-3">
                <a href="{{ route('bookings.show', $invoice->booking_id) }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:text-primary transition-all shadow-sm">
                    <i class="fa-solid fa-arrow-left rtl:rotate-180"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ (string) __('Invoice Details') }}</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider">{{ (string) __('booking.booking') }} <span class="text-primary">#{{ $invoice->booking_id }}</span></p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('invoice.download', $invoice->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary hover:bg-primary-hover text-white font-bold rounded-xl shadow-lg shadow-primary/20 transition-all hover:scale-105 active:scale-95">
                    <i class="fa-solid fa-download"></i>
                    {{ __('Download PDF') }}
                </a>
            </div>
        </div>

        <!-- Invoice Receipt Card -->
        <div class="bg-white   dark:bg-slate-900 rounded-[2.5rem] shadow-3xl overflow-hidden transition-all duration-300 border border-slate-200/50 dark:border-slate-800/50">
            
            <!-- Invoice Header -->
            <div class="bg-linear-to-r from-primary to-primary-hover p-8 md:p-12 text-white">
                <div class="flex flex-col md:flex-row justify-between gap-8">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/20 backdrop-blur-md rounded-lg text-sm font-medium mb-4 text-slate-900 dark:text-white">
                            <i class="fa-solid fa-receipt"></i>
                            {{ (string) __('Invoice') }} #{{ $invoice->invoice_number }}
                        </div>
                        <h2 class="text-4xl text-slate-900 dark:text-white font-extrabold mb-2">{{ (string) __('CourseBook') }}</h2>
                        <p class="text-slate-900 dark:text-white/80 max-w-xs text-sm leading-relaxed">
                            {{ (string) __('Premium online learning platform. Thank you for choosing us for your education.') }}
                        </p>
                    </div>
                    
                    <div class="text-left md:text-right flex flex-col justify-end">
                        <div class="space-y-1">
                            <p class="text-slate-900 dark:text-white/70 text-sm uppercase tracking-wider font-bold">{{ (string) __('Issued Date') }}</p>
                            <p class="text-xl font-bold text-slate-900 dark:text-white">{{ $invoice->issued_at->format('F d, Y') }}</p>
                        </div>
                        <div class="mt-6">
                             <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full {{ $invoice->status === 'paid' ? 'bg-emerald-400/20 text-emerald-100 border border-emerald-400/30' : 'bg-amber-400/20 text-amber-100 border border-amber-400/30' }} text-sm font-bold capitalize">
                                <span class="w-2 h-2 rounded-full {{ $invoice->status === 'paid' ? 'bg-emerald-400 animate-pulse' : 'bg-amber-400 animate-pulse' }}"></span>
                                {{ $invoice->status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoice Content -->
            <div class="p-8 md:p-12">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-12">
                    <!-- Billing Details -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 text-slate-400 uppercase text-xs font-black tracking-widest">
                            <i class="fa-solid fa-user-tag"></i>
                            {{ (string) __('Bill To') }}
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-2xl p-6 border border-slate-100 dark:border-slate-800">
                            <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-1">{{ $invoice->user->name }}</h4>
                            <p class="text-slate-500 dark:text-slate-400 flex items-center gap-2">
                                <i class="fa-solid fa-envelope text-sm"></i>
                                {{ $invoice->user->email }}
                            </p>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 text-slate-400 uppercase text-xs font-black tracking-widest">
                            <i class="fa-solid fa-credit-card"></i>
                            {{ (string) __('Payment Info') }}
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-2xl p-6 border border-slate-100 dark:border-slate-800">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-slate-500 dark:text-slate-400 text-sm">{{ (string) __('Method') }}</span>
                                <span class="text-slate-900 dark:text-white font-bold capitalize">{{ $invoice->payment->payment_method }}</span>
                            </div>
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-slate-500 dark:text-slate-400 text-sm">{{ (string) __('Transaction ID') }}</span>
                                <span class="text-slate-900 dark:text-white font-mono text-sm">{{ $invoice->payment->transaction_id }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-500 dark:text-slate-400 text-sm">{{ (string) __('Paid At') }}</span>
                                <span class="text-slate-900 dark:text-white font-bold text-sm">
                                    {{ $invoice->payment->paid_at ? $invoice->payment->paid_at->format('F d, Y H:i') : 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Course Table -->
                <div class="mb-12">
                    <div class="flex items-center gap-2 text-slate-400 uppercase text-xs font-black tracking-widest mb-4">
                        <i class="fa-solid fa-graduation-cap"></i>
                        {{ (string) __('Purchased Items') }}
                    </div>
                    <div class="overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800">
                        <table class="w-full text-left rtl:text-right">
                            <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-600 dark:text-slate-400 text-xs font-bold uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-4">{{ (string) __('Course') }}</th>
                                    <th class="px-6 py-4 text-center">{{ (string) __('Qty') }}</th>
                                    <th class="px-6 py-4 text-right rtl:text-left">{{ (string) __('Amount') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                <tr class="bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="px-6 py-6">
                                        <div class="font-bold text-slate-900 dark:text-white mb-1">
                                            {{ (string) $invoice->booking->course->title }}
                                        </div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1 max-w-md">
                                            {{ (string) $invoice->booking->course->description }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-6 text-center text-slate-600 dark:text-slate-400 font-medium">1</td>
                                    <td class="px-6 py-6 text-right rtl:text-left font-bold text-slate-900 dark:text-white truncate">
                                        {{ number_format((float) $invoice->amount, 2) }} {{ (string) __('EGP') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Summary -->
                <div class="flex flex-col items-end">
                    <div class="w-full md:w-80 space-y-3">
                        <div class="flex justify-between text-slate-500 dark:text-slate-400">
                            <span>{{ (string) __('Subtotal') }}</span>
                            <span>{{ number_format((float) $invoice->amount, 2) }} {{ (string) __('EGP') }}</span>
                        </div>
                        <div class="flex justify-between text-slate-500 dark:text-slate-400">
                            <span>{{ (string) __('Tax (0%)') }}</span>
                            <span>0.00 {{ (string) __('EGP') }}</span>
                        </div>
                        <div class="pt-3 border-t border-slate-200 dark:border-slate-800 flex justify-between items-center">
                            <span class="text-lg font-bold text-slate-900 dark:text-white">{{ (string) __('Total Amount') }}</span>
                            <span class="text-3xl font-black text-primary">
                                {{ number_format((float) $invoice->amount, 2) }} <span class="text-sm font-bold uppercase">{{ (string) __('EGP') }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="bg-slate-50 dark:bg-slate-800/30 p-8 text-center border-t border-slate-200/50 dark:border-slate-800/50">
                <p class="text-slate-500 dark:text-slate-400 text-sm">
                    {{ __('If you have any questions about this invoice, please contact our support team.') }}
                </p>
                <div class="flex items-center justify-center gap-6 mt-4">
                    <a href="#" class="text-slate-400 hover:text-primary transition-colors"><i class="fa-solid fa-earth-americas text-xl"></i></a>
                    <a href="#" class="text-slate-400 hover:text-primary transition-colors"><i class="fa-solid fa-headset text-xl"></i></a>
                    <a href="#" class="text-slate-400 hover:text-primary transition-colors"><i class="fa-solid fa-circle-question text-xl"></i></a>
                </div>
            </div>
        </div>
        
        <!-- Bottom Print Button (Mobile visibility only or secondary) -->
        <div class="mt-8 text-center md:hidden">
            <a href="{{ route('invoice.download', $invoice->id) }}" class="inline-flex items-center gap-2 px-8 py-3 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20 w-full justify-center">
                <i class="fa-solid fa-download"></i>
                {{ __('Download Invoice') }}
            </a>
        </div>
    </div>
</main>
@endsection


