@extends('layouts.app_wep')

@section('title', 'CourseBook · Learn and Book Courses')



@section('content')
    <main id="content" class="flex-grow-1">
        <section class="hero-section overflow-hidden position-relative">
            <div class="decor decor-top" aria-hidden="true">
                <svg viewBox="0 0 600 600">
                    <defs>
                        <linearGradient id="g1" x1="0" x2="1">
                            <stop offset="0" stop-color="#4f46e5" />
                            <stop offset="1" stop-color="#06b6d4" />
                        </linearGradient>
                    </defs>
                    <path d="M0,300 C150,200 450,400 600,300 L600,0 L0,0 Z" fill="url(#g1)" opacity=".12" />
                </svg>
            </div>
            <div class="decor decor-bottom" aria-hidden="true">
                <svg viewBox="0 0 600 600">
                    <path d="M0,300 C150,400 450,200 600,300 L600,600 L0,600 Z" fill="currentColor" class="decor-fill"
                        opacity=".06" />
                </svg>
            </div>
            <div class="container">
                <div class="row align-items-center g-5">
                    <div class="col-lg-6">
                        <div class="hero-badge mb-3 fade-up"><i class="bi bi-stars me-1"></i> {{ __('home.hero_badge') }}</div>
                        <h1 class="display-5 fw-bold lh-tight fade-up delay-1">{{ __('home.hero_title') }}</h1>
                        <p class="lead text-muted mb-4 fade-up delay-2">{{ __('home.hero_description') }}</p>
                        <div class="d-flex flex-wrap gap-2 fade-up delay-3">
                            <a class="btn btn-primary btn-lg btn-lift btn-grad btn-pill" href="courses.html"><i
                                    class="bi bi-rocket-takeoff me-2"></i>{{ __('home.browse_courses') }}</a>
                        </div>
                    </div>
                    <div class="col-lg-6 text-center">
                        <div class="hero-illustration fade-up delay-2">
                            <img class="img-fluid rounded-4 shadow-hero"
                                src="https://images.unsplash.com/photo-1513258496099-48168024aec0?q=80&w=1200&auto=format&fit=crop"
                                alt="Students learning online">
                            <div class="floating-card shadow-sm">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar bg-primary text-white fw-bold"><i class="bi bi-check2-circle"></i>
                                    </div>
                                    <div>
                                        <div class="small fw-semibold">{{ __('home.hero_instant_confirm_title') }}</div>
                                        <div class="text-muted small">{{ __('home.hero_instant_confirm_desc') }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="glow"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section class="py-5 section-gradient">
            <div class="container">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h2 class="h3 mb-0">{{ __('home.featured_courses') }}</h2>
                    <a href="courses.html"
                        class="link-primary link-underline-opacity-0 link-underline-opacity-75-hover">{{ __('home.view_all') }}</a>
                </div>
                <div id="featuredCourses" class="row g-4" aria-live="polite">
                    @forelse ($courses as $course)
                        <x-card_courses_component :course="$course" />
                    @empty
                        <div class="col-12 text-center py-5">
                            <p class="text-muted">{{ __('home.no_courses') }}</p>
                            <p class="small">{{ __('home.total_courses') }} {{ \App\Models\Course::count() }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="py-5 bg-surface">
            <div class="container">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card card-lift h-100 fade-up">
                            <div class="card-body">
                                <div class="icon-badge bg-primary-subtle text-primary mb-3"><i
                                        class="bi bi-calendar2-check"></i></div>
                                <h3 class="h5">{{ __('home.flexible_booking_title') }}</h3>
                                <p class="text-muted mb-0">{{ __('home.flexible_booking_desc') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-lift h-100 fade-up delay-1">
                            <div class="card-body">
                                <div class="icon-badge bg-success-subtle text-success mb-3"><i class="bi bi-award"></i>
                                </div>
                                <h3 class="h5">{{ __('home.top_instructors_title') }}</h3>
                                <p class="text-muted mb-0">{{ __('home.top_instructors_desc') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-lift h-100 fade-up delay-2">
                            <div class="card-body">
                                <div class="icon-badge bg-warning-subtle text-warning mb-3"><i
                                        class="bi bi-shield-lock"></i>
                                </div>
                                <h3 class="h5">{{ __('home.secure_payments_title') }}</h3>
                                <p class="text-muted mb-0">{{ __('home.secure_payments_desc') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
