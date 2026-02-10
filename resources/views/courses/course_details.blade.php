@extends('layouts.app_wep')

@section('title', 'CourseBook · ' . $course->title)

@section('content')
<main class="min-h-screen bg-surface dark:bg-slate-900/50 py-12 sm:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb / Back Navigation -->
        <nav class="flex mb-8 animate-fade-in" aria-label="Breadcrumb">
            <a href="{{ route('courses.index') }}" class="inline-flex items-center text-sm font-medium text-text-muted hover:text-primary transition-colors group">
                <i class="fa-solid fa-arrow-left-long me-2 transition-transform group-hover:-translate-x-1"></i>
                {{ __('nav.courses') }}
            </a>
        </nav>

        <div class="grid lg:grid-cols-12 gap-12 lg:gap-16 items-start">
            <!-- Left Column: Course Media -->
            <div class="lg:col-span-7 space-y-8 animate-fade-in-up">
                <div class="relative group">
                    <!-- Decorative Background -->
                    <div class="absolute -inset-4 bg-primary/5 rounded-[3rem] blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    
                    <div class="relative aspect-video overflow-hidden rounded-[2.5rem] shadow-2xl border border-white/20 dark:border-slate-800">
                        <img src="{{ asset('storage/'.$course->image_url) }}" 
                             class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-105" 
                             alt="{{ $course->title }}">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                        
                        <!-- Floating Badge -->
                        <div class="absolute top-6 left-6 flex gap-2">
                            <span class="px-4 py-1.5 rounded-full bg-white/90 dark:bg-slate-900/90 backdrop-blur-md text-primary text-xs font-bold shadow-lg border border-primary/20">
                                {{ $course->level }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Course Description -->
                <div class="premium-card p-8 sm:p-10">
                    <h2 class="text-2xl font-bold text-text-base mb-6 italic border-l-4 border-primary ps-4">
                        {{ __('nav.About') ?? 'About this Course' }}
                    </h2>
                    <div class="prose prose-slate dark:prose-invert max-w-none">
                        <p class="text-text-muted leading-relaxed text-lg">
                            {{ $course->description }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Column: Course Info & Action -->
            <div class="lg:col-span-5 space-y-8 animate-fade-in-up delay-150">
                <div class="space-y-6">
                    <h1 class="text-4xl lg:text-5xl font-bold text-text-base leading-tight">
                        {{ $course->title }}
                    </h1>
                    
                    <div class="flex flex-wrap items-center gap-6">
                        <!-- Instructor Info -->
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-slate-200 dark:bg-slate-800 border-2 border-white dark:border-slate-700 overflow-hidden">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($course->instructor?->user?->name) }}&background=6366f1&color=fff" alt="Instructor">
                            </div>
                            <div class="text-sm">
                                <span class="block text-text-muted text-xs uppercase tracking-widest font-bold">{{ __('auth.name') ?? 'Instructor' }}</span>
                                <span class="font-bold text-text-base text-lg">{{ $course->instructor?->user?->name }}</span>
                            </div>
                        </div>

                        <!-- Rating -->
                        <div class="flex flex-col">
                            <span class="text-text-muted text-xs uppercase tracking-widest font-bold mb-1">Rating</span>
                            <div class="flex items-center gap-1.5">
                                <div class="flex text-amber-400">
                                    @php $rating = round($course->rating); @endphp
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fa-{{ $i <= $rating ? 'solid' : 'regular' }} fa-star"></i>
                                    @endfor
                                </div>
                                <span class="text-text-base font-bold ml-1">{{ number_format($course->rating, 1) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Course Details Card -->
                <div class="premium-card overflow-hidden">
                    <div class="p-8 space-y-6">
                        <!-- Price Tag -->
                        <div class="flex items-center justify-between p-6 bg-primary/5 rounded-3xl border border-primary/10">
                            <span class="text-text-muted font-bold tracking-tight">{{ __('nav.Price') }}</span>
                            <div class="text-right">
                                <span class="text-3xl font-bold text-primary">{{ number_format($course->price, 2) }}</span>
                                <span class="text-sm font-bold text-primary/60 ml-1">SAR</span>
                            </div>
                        </div>

                        <!-- Info Grid -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 rounded-2xl bg-surface dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                                <i class="fa-solid fa-calendar-alt text-primary mb-2 block"></i>
                                <span class="block text-[10px] uppercase font-bold text-text-muted tracking-widest">{{ __('nav.Start date') }}</span>
                                <span class="font-bold text-text-base">{{ $course->created_at->format('Y-m-d') }}</span>
                            </div>
                            <div class="p-4 rounded-2xl bg-surface dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                                <i class="fa-solid fa-clock text-primary mb-2 block"></i>
                                <span class="block text-[10px] uppercase font-bold text-text-muted tracking-widest">{{ __('nav.Duration') }}</span>
                                <span class="font-bold text-text-base">{{ $course->duration }}</span>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="pt-4">
                            <a href="{{ route('bookings.create', $course->id) }}" class="btn-premium w-full py-5 text-lg shadow-xl shadow-primary/25">
                                <i class="fa-solid fa-cart-plus me-2"></i>
                                {{ __('nav.book') }}
                            </a>
                        </div>
                        
                        <div class="flex items-center justify-center gap-4 text-xs font-bold text-text-muted uppercase tracking-widest">
                            <span class="flex items-center gap-1.5"><i class="fa-solid fa-shield-halved text-emerald-500"></i> Secure</span>
                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                            <span class="flex items-center gap-1.5"><i class="fa-solid fa-bolt text-amber-500"></i> Instant</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
