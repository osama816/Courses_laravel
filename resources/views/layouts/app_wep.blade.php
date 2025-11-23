<!doctype html>
<html lang="en" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Online Course Booking Platform">
    <title>@yield('title,coursebook')</title>
    <link rel="icon" href="{{ asset('assets/favicon.ico') }}">
    <!-- Apply theme ASAP to prevent flash -->
    <script>
        (function() {
            try {
                var t = localStorage.getItem('cb_theme') || 'light';
                document.documentElement.setAttribute('data-theme', t);
            } catch (e) {}
        })();
    </script>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    {{-- <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> --}}

    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>

<body data-page="home" class="d-flex flex-column min-vh-100">
    <header class="shadow-sm">
        <nav class="navbar navbar-expand-lg navbar-glass sticky-top py-2" role="navigation" aria-label="Main navigation"
            style="position:sticky;top:0;z-index:1040;">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center gap-2 fw-bold logo" href="{{ route('home') }}"
                    aria-label="Go to homepage">
                    <svg class="logo-svg" width="28" height="28" viewBox="0 0 64 64" aria-hidden="true">
                        <rect rx="14" width="64" height="64" class="logo-rect" />
                        <path
                            d="M18 21h28a4 4 0 0 1 4 4v18a4 4 0 0 1-4 4H18a4 4 0 0 1-4-4V25a4 4 0 0 1 4-4Zm3 5v16h22a3 3 0 1 0 0-6H25v-4h18a3 3 0 1 0 0-6H21Z"
                            class="logo-glyph" />
                    </svg>
                    <span style="color: var(--text)">CourseBook</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                    aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="btn-group shadow-sm" role="group" aria-label="Language selector">
                    <a href="{{ url('lang/ar') }}"
                        class="btn btn-sm btn-outline-primary {{ app()->getLocale() == 'ar' ? 'active' : '' }}"
                        aria-label="Switch to Arabic">
                        <i class="bi bi-globe me-1"></i>العربية
                    </a>
                    <a href="{{ url('lang/en') }}"
                        class="btn btn-sm btn-outline-primary {{ app()->getLocale() == 'en' ? 'active' : '' }}"
                        aria-label="Switch to English">
                        <i class="bi bi-translate me-1"></i>English
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="mainNav">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center nav-links">
                        <li class="nav-item"><a class="nav-link" href="courses.html"><i
                                    class="bi bi-grid-3x3-gap me-1"></i>{{ __('nav.courses') }}</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('bookings.index') }}"><i
                                    class="bi bi-calendar2-check me-1"></i>{{ __('nav.book') }}</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('profile.edit') }}"><i
                                    class="bi bi-person-circle me-1"></i>{{ __('nav.profile') }}</a></li>
                        <li class="nav-item d-none d-lg-block mx-2"><a class="nav-link nav-icon" href="courses.html"
                                aria-label="Search"><i class="bi bi-search"></i></a></li>
                        <li class="nav-item ms-lg-2 mb-2">
                            <div id="themeToggle"
                                class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-2 btn-pill"
                                type="button" aria-label="Toggle theme">
                                <i class="bi bi-moon-stars theme-icon" aria-hidden="true"></i><span
                                    class="theme-label">dark</span>
                            </div>
                        </li>
                        @if (Route::has('login'))
                        @auth
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" id="logout_form">
                                @csrf
                                <button type="submit" data-dark="{{ __('nav.dark') }}"
                                    data-light="{{ __('nav.light') }}"
                                    class="btn btn-danger px-1 mb-2 ms-3 py-1 rounded-pill fw-semibold shadow-sm logout-btn">
                                    <i class="bi bi-box-arrow-right me-1"></i> <span> {{ __('nav.logout') }}</span>
                                </button>
                            </form>
                        </li>
                        @else
                        @if (Route::has('register'))
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-primary btn-pill btn-grad"
                                href="{{ route('register') }}">{{ __('nav.login') }}</a>

                        </li>
                        @endif
                        @endauth
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <!-- Page Header-->

<x-flash-messages data-auto-dismiss="true" />

    <!-- Main Content-->
    @yield('content')


    <footer class="mt-auto border-top py-5 bg-footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-12 col-md-4">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <svg class="logo-svg" width="22" height="22" viewBox="0 0 64 64" aria-hidden="true">
                            <rect rx="14" width="64" height="64" class="logo-rect" />
                            <path
                                d="M18 21h28a4 4 0 0 1 4 4v18a4 4 0 0 1-4 4H18a4 4 0 0 1-4-4V25a4 4 0 0 1 4-4Zm3 5v16h22a3 3 0 1 0 0-6H25v-4h18a3 3 0 1 0 0-6H21Z"
                                class="logo-glyph" />
                        </svg>
                        <strong>CourseBook</strong>
                    </div>
                    <p class="text-muted small mb-0">{{ __('footer.description') }}</p>
                </div>
                <div class="col-6 col-md-2">
                    <h6 class="fw-semibold mb-2">{{ __('footer.links_title') }}</h6>
                    <ul class="list-unstyled small">
                        <li><a class="footer-link" href="courses.html">{{ __('footer.courses') }}</a></li>
                        <li><a class="footer-link" href="booking.html">{{ __('footer.book') }}</a></li>
                        <li><a class="footer-link" href="profile.html">{{ __('footer.profile') }}</a></li>
                        <li><a class="footer-link" href="admin/dashboard.html">{{ __('footer.admin') }}</a></li>
                    </ul>
                </div>
                <div class="col-6 col-md-3">
                    <h6 class="fw-semibold mb-2">{{ __('footer.contact_title') }}</h6>
                    <ul class="list-unstyled small">
                        <li>{{ __('footer.email') }}: support@coursebook.test</li>
                        <li>{{ __('footer.phone') }}: +1 555-0100</li>
                    </ul>
                </div>
                <div class="col-12 col-md-3">
                    <h6 class="fw-semibold mb-2">{{ __('footer.social_title') }}</h6>
                    <div class="d-flex gap-2">
                        <a class="btn btn-outline-secondary btn-sm btn-pill nav-icon" href="#"
                            aria-label="Twitter"><i class="bi bi-twitter"></i></a>
                        <a class="btn btn-outline-secondary btn-sm btn-pill nav-icon" href="#"
                            aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                        <a class="btn btn-outline-secondary btn-sm btn-pill nav-icon" href="#"
                            aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div
                class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-2 pt-4 mt-4 border-top">
                <span class="text-muted small">© <span id="year"></span> CourseBook</span>
                <div class="d-flex gap-3 small">
                    <a href="#" class="footer-link">{{ __('footer.privacy') }}</a>
                    <a href="#" class="footer-link">{{ __('footer.terms') }}</a>
                </div>
            </div>
        </div>
    </footer>

    <div id="toastContainer" class="toast-container position-fixed bottom-0 end-0 p-3"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="{{ asset('js/main.js') }}"></script>

    <script>
        // Clean old localStorage data on first load
        // if (localStorage.getItem('cb_seeded_v4')) {
        //   localStorage.removeItem('cb_courses');
        //   localStorage.removeItem('cb_users');
        //   localStorage.removeItem('cb_bookings');
        //   localStorage.removeItem('cb_settings');
        //   localStorage.removeItem('cb_seeded_v4');
        //   console.log('✅ Cleaned old localStorage data');
        // }
    </script>
</body>

</html>
