@extends('layouts.app_wep')

@section('title', __('booking.book_course') . ' - CourseBook')

@section('content')
<main class="min-h-screen bg-surface dark:bg-slate-900/50 py-12 sm:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12 animate-fade-in">
            <div>
                <h1 class="text-3xl lg:text-4xl font-bold text-text-base mb-2 italic border-l-8 border-primary ps-6">
                    {{ __('booking.book_course') }}
                </h1>
                <p class="text-text-muted ps-6">{{ __('booking.live_seats_updating') }}</p>
            </div>
            
            <div class="flex items-center gap-3 px-6 py-3 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                </span>
                <span class="text-emerald-600 dark:text-emerald-400 font-bold text-sm tracking-tight">
                    @livewire('available-seats', ['courseId' => $course->id]) {{ __('booking.seats_available') }}
                </span>
            </div>
        </div>

        @if ($errors->any() || session('success'))
            <div class="mb-8 space-y-4 animate-fade-in-up">
                @if ($errors->any())
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-600 dark:text-rose-400">
                        <i class="fa-solid fa-circle-exclamation text-xl mt-0.5"></i>
                        <div class="flex-1">
                            <strong class="font-bold block mb-1">{{ __('booking.error_occurred') }}</strong>
                            <ul class="text-sm space-y-1 opacity-90">
                                @foreach ($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @if (session('success'))
                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400">
                        <i class="fa-solid fa-circle-check text-xl"></i>
                        <p class="font-bold">{{ session('success') }}</p>
                    </div>
                @endif
            </div>
        @endif

        <div class="grid lg:grid-cols-12 gap-12 items-start">
            <!-- Left Column: Booking Form -->
            <div class="lg:col-span-7 space-y-8 animate-fade-in-up delay-100">
                <form id="bookingForm" 
                      method="POST" 
                      action="{{ route('bookings.store') }}"
                      class="premium-card p-8 sm:p-10">
                    @csrf
                    
                    {{-- Hidden Fields --}}
                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                    <input type="hidden" name="amount" value="{{ $course->price }}">

                    <div class="space-y-10">
                        <!-- Course Info Summary (Form Header) -->
                        <div class="pb-8 border-b border-slate-100 dark:border-slate-800">
                            <h2 class="text-2xl font-bold text-text-base mb-4">
                                {{ __('booking.course') }}: <span class="text-primary">{{ $course->title }}</span>
                            </h2>
                            <div class="flex flex-wrap gap-4 text-sm font-bold text-text-muted">
                                <span class="flex items-center gap-2 px-3 py-1 bg-surface dark:bg-slate-800 rounded-lg border border-slate-100 dark:border-slate-800">
                                    <i class="fa-solid fa-tag text-primary/60"></i>
                                    {{ number_format($course->price, 2) }} SAR
                                </span>
                                <span class="flex items-center gap-2 px-3 py-1 bg-surface dark:bg-slate-800 rounded-lg border border-slate-100 dark:border-slate-800">
                                    <i class="fa-solid fa-clock text-primary/60"></i>
                                    {{ $course->duration }} {{ __('booking.hours') }}
                                </span>
                            </div>
                        </div>

                        @guest
                        <div class="p-6 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center gap-4">
                            <i class="fa-solid fa-circle-info text-2xl text-amber-600 dark:text-amber-400"></i>
                            <p class="text-sm font-bold text-amber-700 dark:text-amber-300">
                                {{ __('booking.login_required') }} 
                                <a href="{{ route('login') }}" class="underline decoration-2 underline-offset-4 hover:text-primary transition-colors">{{ __('booking.login_here') }}</a>
                            </p>
                        </div>
                        @endguest

                        <!-- Payment Method Selection -->
                        <div class="space-y-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                                    <i class="fa-solid fa-credit-card"></i>
                                </div>
                                <h3 class="text-xl font-bold text-text-base">{{ __('booking.payment_method') }}</h3>
                            </div>

                            <div class="grid sm:grid-cols-3 gap-4">
                                <!-- Paymob Option -->
                                <label class="relative group cursor-pointer">
                                    <input type="radio" name="payment_method" value="paymob" class="peer hidden" required>
                                    <div class="p-6 rounded-[2rem] bg-surface dark:bg-slate-800 border-2 border-slate-100 dark:border-slate-700 peer-checked:border-primary peer-checked:bg-primary/5 transition-all duration-300">
                                        <div class="flex flex-col items-center text-center gap-4">
                                            <div class="w-16 h-16 rounded-2xl bg-white dark:bg-slate-700 shadow-sm flex items-center justify-center group-hover:scale-110 transition-transform">
                                                <i class="fa-solid fa-globe text-3xl text-primary"></i>
                                            </div>
                                            <div>
                                                <span class="block font-bold text-text-base text-lg mb-1">Paymob</span>
                                                <span class="text-xs text-text-muted uppercase tracking-widest font-bold">{{ __('booking.pay_online') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Checked Icon Overlay -->
                                    <div class="absolute top-4 right-4 text-primary opacity-0 peer-checked:opacity-100 transition-opacity">
                                        <i class="fa-solid fa-circle-check text-xl"></i>
                                    </div>
                                </label>

                                <!-- MyFatoorah Option -->
                                <label class="relative group cursor-pointer">
                                    <input type="radio" name="payment_method" value="myfatoorah" class="peer hidden" required>
                                    <div class="p-6 rounded-[2rem] bg-surface dark:bg-slate-800 border-2 border-slate-100 dark:border-slate-700 peer-checked:border-primary peer-checked:bg-primary/5 transition-all duration-300">
                                        <div class="flex flex-col items-center text-center gap-4">
                                            <div class="w-16 h-16 rounded-2xl bg-white text-primary dark:bg-slate-700 shadow-sm flex items-center justify-center group-hover:scale-110 transition-transform">
                                                <i class="fa-solid fa-receipt text-3xl"></i>
                                            </div>
                                            <div>
                                                <span class="block font-bold text-text-base text-lg mb-1">MyFatoorah</span>
                                                <span class="text-xs text-text-muted uppercase tracking-widest font-bold">{{ __('booking.pay_online') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="absolute top-4 right-4 text-primary opacity-0 peer-checked:opacity-100 transition-opacity">
                                        <i class="fa-solid fa-circle-check text-xl"></i>
                                    </div>
                                </label>

                                <!-- Cash Option -->
                                <label class="relative group cursor-pointer text-center">
                                    <input type="radio" name="payment_method" value="cash" class="peer hidden">
                                    <div class="p-6 rounded-[2rem] bg-surface dark:bg-slate-800 border-2 border-slate-100 dark:border-slate-700 peer-checked:border-primary peer-checked:bg-primary/5 transition-all duration-300">
                                        <div class="flex flex-col items-center gap-4">
                                            <div class="w-16 h-16 rounded-2xl bg-white dark:bg-slate-700 shadow-sm flex items-center justify-center group-hover:scale-110 transition-transform">
                                                <i class="fa-solid fa-money-bill-transfer text-3xl text-primary"></i>
                                            </div>
                                            <div>
                                                <span class="block font-bold text-text-base text-lg mb-1">{{ __('booking.cash') }}</span>
                                                <span class="text-xs text-text-muted uppercase tracking-widest font-bold">{{ __('booking.pay_later') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="absolute top-4 right-4 text-primary opacity-0 peer-checked:opacity-100 transition-opacity">
                                        <i class="fa-solid fa-circle-check text-xl"></i>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Terms -->
                        <div class="pt-6">
                            <label class="flex items-start gap-4 group cursor-pointer select-none">
                                <div class="relative flex items-center mt-1">
                                    <input type="checkbox" id="agreeTerms" required class="peer appearance-none w-6 h-6 border-2 border-slate-200 dark:border-slate-700 rounded-lg checked:bg-primary checked:border-primary transition-all">
                                    <i class="fa-solid fa-check absolute inset-0 m-auto text-white text-xs opacity-0 peer-checked:opacity-100 pointer-events-none"></i>
                                </div>
                                <span class="text-sm font-medium text-text-muted group-hover:text-text-base transition-colors leading-relaxed">
                                    {{ __('booking.agree_terms') }} 
                                    <a href="#" class="text-primary font-bold hover:underline underline-offset-4">{{ __('booking.terms_conditions') }}</a>
                                </span>
                            </label>
                        </div>

                        <!-- Submit Section -->
                        <div class="pt-8 flex flex-col sm:flex-row items-center gap-4">
                            @auth
                                <button type="submit" class="btn-premium w-full sm:w-auto py-4 px-12 text-lg shadow-xl shadow-primary/20">
                                    <i class="fa-solid fa-shield-check me-2"></i>
                                    {{ __('booking.confirm_booking') }}
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="btn-premium w-full sm:w-auto py-4 px-12 text-lg">
                                    <i class="fa-solid fa-right-to-bracket me-2"></i>
                                    {{ __('booking.login_to_book') }}
                                </a>
                            @endauth
                            
                            <a href="{{ route('courses.show', $course->id) }}" class="flex items-center gap-2 text-sm font-bold text-text-muted hover:text-text-base transition-colors group">
                                <i class="fa-solid fa-arrow-left me-1 transition-transform group-hover:-translate-x-1"></i>
                                {{ __('booking.back_to_course') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Right Column: Summary & Features -->
            <div class="lg:col-span-5 space-y-8 sticky top-24 animate-fade-in-up delay-200">
                <!-- Summary Card -->
                <div class="premium-card overflow-hidden">
                    <!-- Header with Gradient -->
                    <div class="bg-gradient-to-r from-primary/90 to-primary p-8 text-white relative overflow-hidden">
                        <div class="absolute -top-12 -right-12 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
                        <div class="relative flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-receipt text-2xl opacity-80"></i>
                                <h4 class="text-xl font-bold">{{ __('booking.summary') }}</h4>
                            </div>
                            <div class="text-right">
                                <span class="block text-[10px] uppercase font-bold opacity-60 tracking-widest mb-1">{{ __('booking.total') }}</span>
                                <span class="text-2xl font-bold">{{ number_format($course->price, 2) }} SAR</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 space-y-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-start gap-4">
                                <div class="flex items-center gap-3 text-text-muted italic">
                                    <i class="fa-solid fa-book-open w-5 text-primary/60"></i>
                                    <span class="text-sm font-bold">{{ __('booking.course') }}</span>
                                </div>
                                <span class="text-sm font-bold text-text-base text-right max-w-[200px]">{{ $course->title }}</span>
                            </div>

                            <div class="flex justify-between items-center gap-4">
                                <div class="flex items-center gap-3 text-text-muted italic">
                                    <i class="fa-solid fa-calendar-day w-5 text-primary/60"></i>
                                    <span class="text-sm font-bold">{{ __('booking.start_date') }}</span>
                                </div>
                                <span class="text-sm font-bold text-text-base">{{ $course->created_at->format('d M Y') }}</span>
                            </div>

                            <div class="flex justify-between items-center gap-4">
                                <div class="flex items-center gap-3 text-text-muted italic">
                                    <i class="fa-solid fa-user-tie w-5 text-primary/60"></i>
                                    <span class="text-sm font-bold">{{ __('booking.instructor') }}</span>
                                </div>
                                <span class="text-sm font-bold text-text-base">{{ $course->instructor->user->name }}</span>
                            </div>

                            <div class="flex justify-between items-center gap-4">
                                <div class="flex items-center gap-3 text-text-muted italic">
                                    <i class="fa-solid fa-layer-group w-5 text-primary/60"></i>
                                    <span class="text-sm font-bold">{{ __('booking.level') }}</span>
                                </div>
                                <span class="px-2 py-0.5 rounded-md bg-primary/5 text-primary text-[10px] uppercase font-bold border border-primary/10">
                                    {{ $course->level }}
                                </span>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-100 dark:border-slate-800">
                            <div class="flex items-center justify-center gap-4 text-xs font-bold text-emerald-500 uppercase tracking-widest bg-emerald-500/5 py-4 rounded-2xl border border-emerald-500/10">
                                <i class="fa-solid fa-shield-check text-base"></i>
                                {{ __('booking.secure_payment') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Features Card -->
                <div class="premium-card p-8 group">
                    <h6 class="text-sm font-bold text-text-base mb-6 flex items-center gap-3 italic">
                        <i class="fa-solid fa-wand-magic-sparkles text-amber-500"></i>
                        {{ __('booking.course_includes') }}
                    </h6>
                    <ul class="space-y-4">
                        <li class="flex items-center gap-4 text-sm font-medium text-text-muted group-hover:text-text-base transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                            {{ __('booking.lifetime_access') }}
                        </li>
                        <li class="flex items-center gap-4 text-sm font-medium text-text-muted group-hover:text-text-base transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                                <i class="fa-solid fa-certificate"></i>
                            </div>
                            {{ __('booking.certificate') }}
                        </li>
                        <li class="flex items-center gap-4 text-sm font-medium text-text-muted group-hover:text-text-base transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                                <i class="fa-solid fa-headset"></i>
                            </div>
                            {{ __('booking.support') }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

