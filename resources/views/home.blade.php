@extends('layouts.app_wep')

@section('title', 'CourseBook · Learn and Book Courses')

@section('content')
    <main id="content" class="min-h-screen">
        <!-- Hero Section -->
        <section class="relative pt-10 pb-32 overflow-hidden bg-surface dark:bg-slate-900/50">
            <!-- Decorative Elements -->
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none" aria-hidden="true">
                <div class="absolute -top-24 -left-24 w-96 h-96 bg-primary/10 rounded-full blur-3xl"></div>
                <div class="absolute top-1/2 -right-24 w-64 h-64 bg-accent/10 rounded-full blur-3xl"></div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <div class="animate-fade-in-up">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary text-sm font-bold mb-6">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                            {{ __('home.hero_badge') }}
                        </div>
                        <h1 class="text-5xl lg:text-7xl font-bold text-text-base leading-[1.1] mb-8">
                            {{ __('home.hero_title') }}
                        </h1>
                        <p class="text-xl text-text-muted leading-relaxed mb-10 max-w-lg">
                            {{ __('home.hero_description') }}
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <a href="{{ route('courses.index') }}" class="btn-premium py-4 px-8 text-lg">
                                <i class="fa-solid fa-rocket mr-2"></i>
                                {{ __('home.browse_courses') }}
                            </a>
                        </div>

                        <div class="mt-12 flex items-center gap-6">
                            <div class="flex -space-x-4">
                                @for($i = 1; $i <= 4; $i++)
                                    <div class="w-12 h-12 rounded-full border-4 border-white dark:border-slate-900 overflow-hidden bg-slate-200">
                                        <img src="https://i.pravatar.cc/150?u={{ $i }}" alt="User">
                                    </div>
                                @endfor
                            </div>
                            <div class="text-sm">
                                <span class="block font-bold text-text-base">10k+ Students</span>
                                <span class="text-text-muted">Joined our community</span>
                            </div>
                        </div>
                    </div>

                    <div class="relative group animate-fade-in-up delay-200">
                        <div class="relative bg-panel p-4 rounded-premium shadow-2xl overflow-hidden">
                            <img class="w-full h-auto rounded-xl object-cover"
                                src="https://images.unsplash.com/photo-1513258496099-48168024aec0?q=80&w=1200&auto=format&fit=crop"
                                alt="Students learning online">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                        </div>
                        
                        <!-- Floating Stats Card -->
                        <div class="absolute -bottom-8 -left-8 bg-panel p-5 rounded-2xl shadow-xl flex items-center gap-4 border border-slate-100 dark:border-slate-700 animate-bounce-slow">
                            <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                                <i class="fa-solid fa-circle-check text-2xl"></i>
                            </div>
                            <div>
                                <span class="block font-bold text-text-base capitalize">{{ __('home.hero_instant_confirm_title') }}</span>
                                <span class="text-xs text-text-muted">{{ __('home.hero_instant_confirm_desc') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Courses Section -->
        <section class="py-24 bg-surface ">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
                    <div class="max-w-2xl">
                        <h2 class="text-3xl lg:text-4xl font-bold text-text-base mb-4 italic border-l-8 border-primary pl-6">
                            {{ __('home.featured_courses') }}
                        </h2>
                        <p class="text-text-muted">Explore our most popular and highly rated courses.</p>
                    </div>
                    <a href="{{ route('courses.index') }}" class="inline-flex items-center text-primary font-bold group">
                        {{ __('home.view_all') }}
                        <i class="fa-solid fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse ($courses as $course)
                        <x-card_courses_component :course="$course" />
                    @empty
                        <div class="col-span-full py-20 text-center bg-slate-50 dark:bg-slate-900/40 rounded-3xl border-2 border-dashed border-slate-200 dark:border-slate-800">
                             <div class="w-16 h-16 bg-slate-200 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid fa-box-open text-2xl text-slate-400"></i>
                             </div>
                            <p class="text-slate-500 dark:text-slate-400 mb-2">{{ __('home.no_courses') }}</p>
                            <p class="text-sm font-medium text-primary">{{ __('home.total_courses') }} {{ \App\Models\Course::count() }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-24 bg-surface">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="premium-card p-8 group">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 mb-6 group-hover:bg-primary group-hover:text-white transition-all duration-500">
                            <i class="fa-solid fa-calendar-check text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-text-base mb-4">{{ __('home.flexible_booking_title') }}</h3>
                        <p class="text-text-muted leading-relaxed">{{ __('home.flexible_booking_desc') }}</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="premium-card p-8 group">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400 mb-6 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500">
                            <i class="fa-solid fa-award text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-text-base mb-4">{{ __('home.top_instructors_title') }}</h3>
                        <p class="text-text-muted leading-relaxed">{{ __('home.top_instructors_desc') }}</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="premium-card p-8 group">
                        <div class="w-14 h-14 rounded-2xl bg-amber-50 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 dark:text-amber-400 mb-6 group-hover:bg-amber-500 group-hover:text-white transition-all duration-500">
                            <i class="fa-solid fa-shield-halved text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-text-base mb-4">{{ __('home.secure_payments_title') }}</h3>
                        <p class="text-text-muted leading-relaxed">{{ __('home.secure_payments_desc') }}</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
