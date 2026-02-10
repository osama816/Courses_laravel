@extends('layouts.app_wep')

@section('title', __('booking.booking_details') . ' #' . $booking->id . ' - CourseBook')

@section('content')
<main class="min-h-screen bg-surface dark:bg-slate-900/50 py-12 sm:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Success Message --}}
        @if (session('success'))
            <div class="mb-8 animate-fade-in-up">
                <div class="flex items-center gap-4 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                    <p class="font-bold">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12 animate-fade-in">
            <div>
                <h1 class="text-3xl lg:text-4xl font-bold text-text-base mb-2 italic border-l-8 border-primary ps-6">
                    {{ __('booking.booking_details') }}
                </h1>
                <p class="text-text-muted ps-6">{{ __('booking.booking_number') }}: <strong class="text-primary">#{{ $booking->id }}</strong></p>
            </div>

            <div class="ps-6 md:ps-0 flex items-center">
                @if($booking->status === 'confirmed')
                    <span class="flex items-center gap-2 px-6 py-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 font-bold rounded-2xl tracking-tight shadow-sm">
                        <i class="fa-solid fa-circle-check"></i>
                        {{ __('booking.confirmed') }}
                    </span>
                @elseif($booking->status === 'pending')
                    <span class="flex items-center gap-2 px-6 py-3 bg-amber-500/10 border border-amber-500/20 text-amber-600 dark:text-amber-400 font-bold rounded-2xl tracking-tight shadow-sm">
                        <i class="fa-solid fa-clock"></i>
                        {{ __('booking.pending') }}
                    </span>
                @else
                    <span class="flex items-center gap-2 px-6 py-3 bg-rose-500/10 border border-rose-500/20 text-rose-600 dark:text-rose-400 font-bold rounded-2xl tracking-tight shadow-sm">
                        <i class="fa-solid fa-circle-xmark"></i>
                        {{ __('booking.cancelled') }}
                    </span>
                @endif
            </div>
        </div>

        <div class="grid lg:grid-cols-12 gap-12 items-start">
            {{-- Main Content --}}
            <div class="lg:col-span-8 space-y-8 animate-fade-in-up delay-100">
                
                {{-- Booking Status & Timeline --}}
                <div class="premium-card p-8">
                    <div class="flex items-center gap-3 mb-10 pb-6 border-b border-slate-100 dark:border-slate-800">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                            <i class="fa-solid fa-route"></i>
                        </div>
                        <h5 class="text-xl font-bold text-text-base">{{ __('booking.booking_status') }}</h5>
                    </div>

                    <div class="relative space-y-12">
                        <!-- Vertical Line -->
                        <div class="absolute left-6 top-2 bottom-2 w-0.5 bg-slate-100 dark:bg-slate-800"></div>

                        <!-- Step 1: Created -->
                        <div class="relative flex gap-8 pl-14">
                            <div class="absolute left-3 -translate-x-1/2 w-6 h-6 rounded-full bg-primary flex items-center justify-center border-4 border-white dark:border-slate-900 z-10 shadow-sm shadow-primary/20">
                                <i class="fa-solid fa-calendar-check text-[10px] text-white"></i>
                            </div>
                            <div class="flex-1">
                                <h6 class="font-bold text-text-base text-lg">{{ __('booking.booking_created') }}</h6>
                                <p class="text-sm font-bold text-text-muted italic">{{ $booking->created_at->format('d M Y, h:i A') }}</p>
                            </div>
                        </div>

                        <!-- Step 2: Payment -->
                        @if($booking->payment)
                        <div class="relative flex gap-8 pl-14">
                            <div class="absolute left-3 -translate-x-1/2 w-6 h-6 rounded-full {{ $booking->payment->status === 'paid' ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-700' }} flex items-center justify-center border-4 border-white dark:border-slate-900 z-10">
                                <i class="fa-solid fa-credit-card text-[10px] text-white"></i>
                            </div>
                            <div class="flex-1">
                                <h6 class="font-bold text-text-base text-lg">{{ __('booking.payment_received') }}</h6>
                                <p class="text-sm font-bold text-text-muted italic">
                                    @if($booking->payment->status === 'paid')
                                        {{ $booking->payment->paid_at->format('d M Y, h:i A') }}
                                    @else
                                        {{ __('booking.pending_payment') }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        @endif

                        <!-- Step 3: Confirmation -->
                        <div class="relative flex gap-8 pl-14">
                            <div class="absolute left-3 -translate-x-1/2 w-6 h-6 rounded-full {{ $booking->status === 'confirmed' ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-700' }} flex items-center justify-center border-4 border-white dark:border-slate-900 z-10">
                                <i class="fa-solid fa-check-double text-[10px] text-white"></i>
                            </div>
                            <div class="flex-1">
                                <h6 class="font-bold text-text-base text-lg">{{ __('booking.booking_confirmed') }}</h6>
                                <p class="text-sm font-bold text-text-muted italic">
                                    @if($booking->status === 'confirmed')
                                        {{ $booking->updated_at->format('d M Y, h:i A') }}
                                    @else
                                        {{ __('booking.awaiting_confirmation') }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Course Details Card --}}
                <div class="premium-card p-8 group">
                    <div class="flex items-center gap-3 mb-8 pb-6 border-b border-slate-100 dark:border-slate-800">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                            <i class="fa-solid fa-book-bookmark"></i>
                        </div>
                        <h5 class="text-xl font-bold text-text-base">{{ __('booking.course_details') }}</h5>
                    </div>

                    <div class="flex flex-col md:flex-row gap-8 items-start">
                        <div class="w-full md:w-48 aspect-video md:aspect-square overflow-hidden rounded-[2rem] shadow-lg group-hover:shadow-primary/20 transition-all duration-500">
                            <img src="{{ asset('storage/' . $booking->course->image_url) }}"
                                 alt="{{ $booking->course->title }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        </div>
                        
                        <div class="flex-1 space-y-6">
                            <div class="flex items-start justify-between gap-4">
                                <h4 class="text-2xl font-bold text-text-base leading-tight">{{ $booking->course->title }}</h4>
                                <div class="flex items-center gap-1.5 px-3 py-1 bg-amber-500/10 text-amber-500 text-xs font-bold rounded-lg border border-amber-500/20">
                                    <i class="fa-solid fa-star"></i>
                                    {{ number_format($booking->course->rating, 1) }}
                                </div>
                            </div>

                            <div class="grid sm:grid-cols-2 gap-4">
                                <div class="flex items-center gap-3 p-4 rounded-2xl bg-surface dark:bg-slate-800 border border-slate-100 dark:border-slate-700">
                                    <i class="fa-solid fa-user-instructor text-primary/60"></i>
                                    <div>
                                        <span class="block text-[10px] uppercase font-bold text-text-muted tracking-widest">{{ __('booking.instructor') }}</span>
                                        <span class="text-sm font-bold text-text-base">{{ $booking->course->instructor->user->name }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 p-4 rounded-2xl bg-surface dark:bg-slate-800 border border-slate-100 dark:border-slate-700">
                                    <i class="fa-solid fa-signal text-primary/60"></i>
                                    <div>
                                        <span class="block text-[10px] uppercase font-bold text-text-muted tracking-widest">{{ __('booking.level') }}</span>
                                        <span class="text-sm font-bold text-text-base">{{ $booking->course->level }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 p-4 rounded-2xl bg-surface dark:bg-slate-800 border border-slate-100 dark:border-slate-700">
                                    <i class="fa-solid fa-clock-rotate-left text-primary/60"></i>
                                    <div>
                                        <span class="block text-[10px] uppercase font-bold text-text-muted tracking-widest">{{ __('booking.duration') }}</span>
                                        <span class="text-sm font-bold text-text-base">{{ $booking->course->duration }} {{ __('booking.hours') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4">
                                <a href="{{ route('courses.show', $booking->course->id) }}"
                                   class="inline-flex items-center gap-2 group/btn text-sm font-bold text-primary hover:text-primary-dark transition-colors italic">
                                   {{ __('booking.view_course') }}
                                   <i class="fa-solid fa-circle-chevron-right transition-transform group-hover/btn:translate-x-1 rtl:group-hover/btn:-translate-x-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Payment Information --}}
                @if($booking->payment)
                <div class="premium-card p-8">
                    <div class="flex items-center gap-3 mb-8 pb-6 border-b border-slate-100 dark:border-slate-800">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                        </div>
                        <h5 class="text-xl font-bold text-text-base">{{ __('booking.payment_information') }}</h5>
                    </div>

                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div>
                            <span class="block text-[10px] uppercase font-bold text-text-muted tracking-widest mb-2 italic">{{ __('booking.payment_method') }}</span>
                            <span class="px-4 py-2 bg-primary/5 text-primary font-bold rounded-xl border border-primary/10 text-sm uppercase">
                                {{ $booking->payment->payment_method }}
                            </span>
                        </div>
                        <div>
                            <span class="block text-[10px] uppercase font-bold text-text-muted tracking-widest mb-2 italic">{{ __('booking.transaction_id') }}</span>
                            <code class="text-xs font-mono bg-slate-50 dark:bg-slate-800 p-2 rounded-lg text-slate-500 dark:text-slate-400 block break-all">
                                {{ $booking->payment->transaction_id }}
                            </code>
                        </div>
                        <div>
                            <span class="block text-[10px] uppercase font-bold text-text-muted tracking-widest mb-2 italic">{{ __('booking.amount_paid') }}</span>
                            <span class="text-xl font-bold text-emerald-500 italic">{{ number_format($booking->payment->amount, 2) }} SAR</span>
                        </div>
                        <div>
                            <span class="block text-[10px] uppercase font-bold text-text-muted tracking-widest mb-2 italic">{{ __('booking.payment_status') }}</span>
                            @if($booking->payment->status === 'paid')
                                <span class="text-xs font-bold text-emerald-600 flex items-center gap-1.5">
                                    <i class="fa-solid fa-badge-check"></i>
                                    {{ __('booking.paid') }}
                                </span>
                            @else
                                <span class="text-xs font-bold text-amber-500 flex items-center gap-1.5">
                                    <i class="fa-solid fa-hourglass-start"></i>
                                    {{ __('booking.pending') }}
                                </span>
                            @endif
                        </div>
                        <div>
                            <span class="block text-[10px] uppercase font-bold text-text-muted tracking-widest mb-2 italic">{{ __('booking.payment_date') }}</span>
                            <span class="text-sm font-bold text-text-base italic">
                                {{ $booking->payment->paid_at ? $booking->payment->paid_at->format('d M Y, h:i A') : '-' }}
                            </span>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-4 space-y-8 sticky top-24 animate-fade-in-up delay-200">
                {{-- Order Summary --}}
                <div class="premium-card overflow-hidden">
                    <div class="bg-gradient-to-r from-primary/90 to-primary p-6 text-white text-center">
                        <h5 class="text-lg font-bold flex items-center justify-center gap-3">
                            <i class="fa-solid fa-receipt opacity-80"></i>
                            {{ __('booking.summary') }}
                        </h5>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center italic">
                                <span class="text-sm font-bold text-text-muted">{{ __('booking.booking_date') }}</span>
                                <span class="text-sm font-bold text-text-base">{{ $booking->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center italic">
                                <span class="text-sm font-bold text-text-muted">{{ __('booking.course_price') }}</span>
                                <span class="text-sm font-bold text-text-base italic">{{ number_format($booking->course->price, 2) }} SAR</span>
                            </div>
                        </div>
                        <div class="pt-6 border-t border-slate-100 dark:border-slate-800">
                            <div class="flex justify-between items-center italic">
                                <span class="text-lg font-bold text-text-base">{{ __('booking.total') }}</span>
                                <span class="text-2xl font-bold text-emerald-500">{{ number_format($booking->course->price, 2) }} SAR</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="premium-card p-8">
                    <h6 class="text-xs font-bold text-text-muted uppercase tracking-widest mb-6 italic">{{ __('booking.actions') }}</h6>
                    <div class="flex flex-col gap-4">
                        @if($booking->status === 'confirmed')
                            <a href="{{ route('courses.show', $booking->course->id) }}"
                               class="btn-premium w-full flex items-center justify-center gap-3 italic">
                                <i class="fa-solid fa-circle-play"></i>
                                {{ __('booking.start_course') }}
                            </a>
                        @endif

                        @if($booking->payment && $booking->payment->status === 'paid' && $booking->invoice)
                            <a href="{{ route('invoice.show', $booking->invoice->id) }}" class="w-full py-4 text-sm font-bold text-text-base bg-surface dark:bg-slate-800 border-2 border-slate-100 dark:border-slate-700 rounded-2xl flex items-center justify-center gap-3 hover:border-primary transition-all duration-300">
                                <i class="fa-solid fa-file-invoice text-rose-500"></i>
                                {{ __('booking.invoice') }}
                            </a>
                        @endif

                        @if($booking->status === 'pending')
                            <form action="{{ route('bookings.destroy', $booking->id) }}"
                                  method="POST" 
                                  class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full py-4 text-sm font-bold text-rose-500 bg-rose-500/5 border-2 border-rose-500/10 rounded-2xl flex items-center justify-center gap-3 hover:bg-rose-500 hover:text-white transition-all duration-300">
                                    <i class="fa-solid fa-ban"></i>
                                    {{ __('booking.cancel_booking') }}
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('bookings.index') }}"
                           class="w-full py-4 text-sm font-bold text-text-muted text-center hover:text-primary transition-colors flex items-center justify-center gap-2 group">
                            <i class="fa-solid fa-arrow-left transition-transform group-hover:-translate-x-1 rtl:group-hover:translate-x-1"></i>
                            {{ __('booking.back_to_bookings') }}
                        </a>
                    </div>
                </div>

                {{-- Help Support --}}
                <div class="premium-card p-8 text-center space-y-6 bg-gradient-to-b from-primary/5 to-transparent">
                    <div class="w-16 h-16 rounded-[1.5rem] bg-white dark:bg-slate-800 shadow-xl shadow-primary/10 flex items-center justify-center text-primary mx-auto">
                        <i class="fa-solid fa-headset text-2xl animate-bounce-slow"></i>
                    </div>
                    <div>
                        <h6 class="text-lg font-bold text-text-base mb-2 italic text-center">{{ __('booking.need_help') }}</h6>
                        <p class="text-xs font-medium text-text-muted leading-relaxed italic text-center">{{ __('booking.contact_support_text') }}</p>
                    </div>
                    <a href="#" class="inline-flex py-3 px-8 text-sm font-bold text-white bg-slate-900 dark:bg-slate-700 rounded-xl hover:scale-105 transition-transform">
                        {{ __('booking.contact_support') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
