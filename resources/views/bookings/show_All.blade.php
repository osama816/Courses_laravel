@extends('layouts.app_wep')

@section('title', __('booking.my_bookings') . ' - CourseBook')

@section('content')
<main class="min-h-screen bg-surface dark:bg-slate-900/50 py-12 sm:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12 animate-fade-in text-center md:text-start">
            <div>
                <h1 class="text-3xl lg:text-4xl font-bold text-text-base mb-2 italic border-l-8 border-primary ps-6 inline-block md:block">
                    {{ __('booking.my_bookings') }}
                </h1>
                <p class="text-text-muted ps-6">{{ __('booking.manage_bookings') }}</p>
            </div>
            
            <a href="{{ route('courses.index') }}" class="btn-premium px-8 py-4 shadow-xl shadow-primary/20 flex items-center justify-center gap-3 mx-auto md:mx-0">
                <i class="fa-solid fa-circle-plus"></i>
                {{ __('booking.book_new_course') }}
            </a>
        </div>

        {{-- Statistics Grid --}}
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12 animate-fade-in-up delay-100">
            <!-- Confirmed Stats -->
            <div class="premium-card p-6 flex items-center gap-6 overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-500/5 rounded-full blur-2xl group-hover:bg-emerald-500/10 transition-colors"></div>
                <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                    <i class="fa-solid fa-circle-check text-2xl group-hover:scale-110 transition-transform"></i>
                </div>
                <div>
                    <span class="block text-xs font-bold text-text-muted uppercase tracking-widest mb-1 italic">{{ __('booking.confirmed') }}</span>
                    <span class="text-3xl font-bold text-text-base tracking-tight font-outfit">{{ $bookings->where('status', 'confirmed')->count() }}</span>
                </div>
            </div>

            <!-- Pending Stats -->
            <div class="premium-card p-6 flex items-center gap-6 overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-500/5 rounded-full blur-2xl group-hover:bg-amber-500/10 transition-colors"></div>
                <div class="w-16 h-16 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-500">
                    <i class="fa-solid fa-clock-rotate-left text-2xl group-hover:scale-110 transition-transform"></i>
                </div>
                <div>
                    <span class="block text-xs font-bold text-text-muted uppercase tracking-widest mb-1 italic">{{ __('booking.pending') }}</span>
                    <span class="text-3xl font-bold text-text-base tracking-tight font-outfit">{{ $bookings->where('status', 'pending')->count() }}</span>
                </div>
            </div>

            <!-- Total Stats -->
            <div class="premium-card p-6 flex items-center gap-6 overflow-hidden group sm:col-span-2 lg:col-span-1">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-primary/5 rounded-full blur-2xl group-hover:bg-primary/10 transition-colors"></div>
                <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center text-primary">
                    <i class="fa-solid fa-book-bookmark text-2xl group-hover:scale-110 transition-transform"></i>
                </div>
                <div>
                    <span class="block text-xs font-bold text-text-muted uppercase tracking-widest mb-1 italic">{{ __('booking.total_bookings') }}</span>
                    <span class="text-3xl font-bold text-text-base tracking-tight font-outfit">{{ $bookings->count() }}</span>
                </div>
            </div>
        </div>

        @if($bookings->isEmpty())
            {{-- Empty State --}}
            <div class="premium-card p-16 text-center animate-fade-in-up delay-200">
                <div class="w-24 h-24 rounded-[2rem] bg-slate-50 dark:bg-slate-800 shadow-inner flex items-center justify-center mx-auto mb-8 text-slate-300 dark:text-slate-600">
                    <i class="fa-solid fa-folder-open text-4xl animate-bounce-slow"></i>
                </div>
                <h4 class="text-2xl font-bold text-text-base mb-3 italic">{{ __('booking.no_bookings_yet') }}</h4>
                <p class="text-text-muted max-w-md mx-auto mb-10 italic leading-relaxed">{{ __('booking.start_booking_text') }}</p>
                <a href="{{ route('courses.index') }}" class="btn-premium px-10 py-4 shadow-xl shadow-primary/10 italic">
                    <i class="fa-solid fa-magnifying-glass me-2"></i>
                    {{ __('booking.browse_courses') }}
                </a>
            </div>
        @else
            {{-- Bookings List --}}
            <div class="space-y-6 animate-fade-in-up delay-200" id="bookingsList">
                @foreach($bookings as $booking)
                <div class="booking-item group" 
                     data-status="{{ $booking->status }}"
                     data-title="{{ strtolower($booking->course->title) }}"
                     data-date="{{ $booking->created_at->timestamp }}">
                    
                    <div class="premium-card overflow-hidden transition-all duration-500 hover:shadow-2xl hover:shadow-primary/10 hover:-translate-y-1 relative">
                        <!-- Left Status Ribbon (Logical RTL support) -->
                        <div class="absolute top-0 start-0 bottom-0 w-2 
                            @if($booking->status === 'confirmed') bg-emerald-500 @elseif($booking->status === 'pending') bg-amber-500 @else bg-rose-500 @endif">
                        </div>

                        <div class="flex flex-col lg:flex-row">
                            {{-- Course Image --}}
                            <div class="lg:w-72 relative shrink-0 overflow-hidden">
                                <img src="{{ asset('storage/' . $booking->course->image_url) }}"
                                     alt="{{ $booking->course->title }}"
                                     class="w-full h-full object-cover min-h-[200px] group-hover:scale-110 transition-transform duration-700">
                                
                                {{-- Floating Badge --}}
                                <div class="absolute top-4 start-4">
                                    @if($booking->status === 'confirmed')
                                        <span class="flex items-center gap-1.5 px-3 py-1.5 bg-emerald-500 text-white text-[10px] font-bold rounded-lg shadow-lg">
                                            <i class="fa-solid fa-circle-check"></i>
                                            {{ __('booking.confirmed') }}
                                        </span>
                                    @elseif($booking->status === 'pending')
                                        <span class="flex items-center gap-1.5 px-3 py-1.5 bg-amber-500 text-white text-[10px] font-bold rounded-lg shadow-lg">
                                            <i class="fa-solid fa-clock"></i>
                                            {{ __('booking.pending') }}
                                        </span>
                                    @else
                                        <span class="flex items-center gap-1.5 px-3 py-1.5 bg-rose-500 text-white text-[10px] font-bold rounded-lg shadow-lg">
                                            <i class="fa-solid fa-circle-xmark"></i>
                                            {{ __('booking.cancelled') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Booking Content --}}
                            <div class="flex-1 p-8 sm:p-10">
                                <div class="flex flex-col h-full">
                                    {{-- Header info --}}
                                    <div class="flex flex-wrap items-center gap-3 mb-4">
                                        <span class="text-[10px] font-bold py-1 px-3 bg-slate-100 dark:bg-slate-800 text-text-muted rounded-full">
                                            #{{ $booking->id }}
                                        </span>
                                        @if($booking->payment && $booking->payment->status === 'paid')
                                            <span class="text-[10px] font-bold py-1 px-3 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-full flex items-center gap-1.5">
                                                <i class="fa-solid fa-badge-check"></i>
                                                {{ __('booking.paid') }}
                                            </span>
                                        @endif
                                    </div>

                                    <h5 class="text-2xl font-bold text-text-base mb-3 leading-tight italic">{{ $booking->course->title }}</h5>
                                    
                                    <div class="flex items-center gap-4 text-xs font-bold text-text-muted mb-8 italic">
                                        <div class="flex items-center gap-2">
                                            <i class="fa-solid fa-user-instructor text-primary/60"></i>
                                            {{ $booking->course->instructor->user->name }}
                                        </div>
                                        <div class="flex items-center gap-1.5 text-amber-500">
                                            <i class="fa-solid fa-star"></i>
                                            {{ number_format($booking->course->rating, 1) }}
                                        </div>
                                    </div>

                                    {{-- Stats Grid --}}
                                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-8 pt-8 border-t border-slate-50 dark:border-slate-800">
                                        <div class="space-y-1">
                                            <span class="block text-[10px] uppercase font-bold text-text-muted tracking-widest">{{ __('booking.booked_on') }}</span>
                                            <span class="block text-sm font-bold text-text-base italic">{{ $booking->created_at->format('d M Y') }}</span>
                                        </div>
                                        <div class="space-y-1 text-center sm:text-start">
                                            <span class="block text-[10px] uppercase font-bold text-text-muted tracking-widest">{{ __('booking.price') }}</span>
                                            <span class="block text-sm font-bold text-emerald-500 italic">{{ number_format($booking->course->price, 2) }} SAR</span>
                                        </div>
                                        <div class="space-y-1">
                                            <span class="block text-[10px] uppercase font-bold text-text-muted tracking-widest">{{ __('booking.level') }}</span>
                                            <span class="block text-sm font-bold text-text-base italic">{{ $booking->course->level }}</span>
                                        </div>
                                        <div class="space-y-1 text-center sm:text-start">
                                            <span class="block text-[10px] uppercase font-bold text-text-muted tracking-widest">{{ __('booking.duration') }}</span>
                                            <span class="block text-sm font-bold text-text-base italic">{{ $booking->course->duration }}h</span>
                                        </div>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="mt-auto pt-6 flex flex-wrap gap-4 border-t border-slate-50 dark:border-slate-800">
                                        <a href="{{ route('bookings.show', $booking->id) }}"
                                           class="btn-premium px-6 py-3 text-xs italic">
                                            <i class="fa-solid fa-eye me-2"></i>
                                            {{ __('booking.view_details') }}
                                        </a>

                                        @if($booking->status === 'confirmed')
                                            <a href="{{ route('courses.show', $booking->course->id) }}"
                                               class="px-6 py-3 bg-emerald-500 text-white text-xs font-bold rounded-xl shadow-lg shadow-emerald-500/20 hover:scale-105 transition-transform italic flex items-center justify-center gap-2">
                                                <i class="fa-solid fa-circle-play"></i>
                                                {{ __('booking.start_course') }}
                                            </a>
                                        @endif
                                        @if($booking->payment && $booking->payment->status === 'paid' && $booking->invoice)
                                            <a href="{{ route('invoice.show', $booking->invoice->id) }}" class="px-6 py-3 bg-slate-100 dark:bg-slate-800 text-text-base text-xs font-bold rounded-xl border border-slate-200 dark:border-slate-700 hover:border-primary transition-all italic flex items-center justify-center gap-2">
                                                <i class="fa-solid fa-file-invoice text-rose-500"></i>
                                                {{ __('booking.invoice') }}
                                            </a>
                                        @endif

                                        @if($booking->status === 'pending')
                                            <form action="{{ route('bookings.destroy', $booking->id) }}"
                                                  method="POST"
                                                  class="ms-auto">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-6 py-3 text-rose-500 text-xs font-bold rounded-xl hover:bg-rose-500/10 transition-colors italic flex items-center gap-2">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                    {{ __('booking.cancel') }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- No Results Message --}}
            <div id="noResults" class="hidden text-center py-20 animate-fade-in">
                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-2xl flex items-center justify-center mx-auto mb-6 text-slate-300 dark:text-slate-600">
                    <i class="fa-solid fa-magnifying-glass text-xl"></i>
                </div>
                <p class="text-text-muted font-bold italic">{{ __('booking.no_results_found') }}</p>
            </div>
        @endif
    </div>
</main>
@endsection
