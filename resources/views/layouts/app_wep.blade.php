<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
      class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Online Course Booking Platform">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CourseBook')</title>
    <link rel="icon" href="{{ asset('assets/favicon.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Scripts (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
    <script>
        (function() {
            try {
                var t = localStorage.getItem('cb_theme') || 'light';
                if (t === 'dark') document.documentElement.classList.add('dark');
                document.documentElement.setAttribute('data-theme', t);
            } catch (e) {}
        })();

        window.toggleTheme = function() {
            console.log("[Theme] Toggle Triggered");
            const html = document.documentElement;
            const isDark = html.classList.contains('dark');
            const next = isDark ? 'light' : 'dark';
            
            if (next === 'dark') {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }
            html.setAttribute('data-theme', next);
            localStorage.setItem('cb_theme', next);
            
            // Update icons
            document.querySelectorAll("#themeToggle i").forEach(icon => {
                icon.className = next === 'dark' ? 'fa-solid fa-sun text-lg text-amber-400' : 'fa-solid fa-moon text-lg';
            });

            // Update debug info if exists
            const debug = document.getElementById('theme-debug');
            if (debug) {
                const surfaceColor = getComputedStyle(document.body).getPropertyValue('--color-surface');
                debug.innerText = `Theme: ${next} | Surface: ${surfaceColor}`;
            }
            
            console.log("[Theme] New state:", next, "Classes:", html.classList.toString());
        };
    </script>
</head>

<body class="flex flex-col min-h-screen bg-surface transition-colors duration-300">
    <header class="glass-nav" x-data="{ mobileMenuOpen: false }" @resize.window="if (window.innerWidth >= 1024) mobileMenuOpen = false">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" aria-label="Main navigation">
            <div class="flex justify-between h-20 items-center">
                <!-- Logo -->
                <div class="flex shrink-0 items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center shadow-lg group-hover:rotate-12 transition-transform">
                            <svg class="text-white w-6 h-6" viewBox="0 0 64 64" fill="currentColor">
                                <path d="M18 21h28a4 4 0 0 1 4 4v18a4 4 0 0 1-4 4H18a4 4 0 0 1-4-4V25a4 4 0 0 1 4-4Zm3 5v16h22a3 3 0 1 0 0-6H25v-4h18a3 3 0 1 0 0-6H21Z" />
                            </svg>
                        </div>
                        <span class="text-2xl font-bold tracking-tight text-text-base">Course<span class="text-primary">Book</span></span>
                    </a>
                </div>

                <!-- Desktop Nav -->
                <div class="hidden lg:flex items-center space-x-10">
                    <a href="{{ route('home') }}" class="nav-link">
                        <i class="fa-solid fa-grip mr-1.5"></i>{{ __('nav.courses') }}
                    </a>
                    <a href="{{ route('bookings.index') }}" class="nav-link">
                        <i class="fa-solid fa-calendar-check mr-1.5"></i>{{ __('nav.book') }}
                    </a>
                    <a href="{{ route('profile.edit') }}" class="nav-link">
                        <i class="fa-solid fa-circle-user mr-1.5 "></i>{{ __('nav.profile') }}
                    </a>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3 sm:gap-6">
                    <!-- Lang Toggle (Desktop) -->
                    <div class="hidden sm:flex items-center bg-surface rounded-2xl p-1 gap-1 border border-slate-200/50">
                        <a href="{{ url('lang/ar') }}" class="px-4 py-1.5 rounded-xl text-xs font-bold transition-all {{ app()->getLocale() == 'ar' ? 'bg-panel shadow-sm text-primary' : 'text-text-muted hover:text-text-base' }}">AR</a>
                        <a href="{{ url('lang/en') }}" class="px-4 py-1.5 rounded-xl text-xs font-bold transition-all {{ app()->getLocale() == 'en' ? 'bg-panel shadow-sm text-primary' : 'text-text-muted hover:text-text-base' }}">EN</a>
                    </div>

                    <!-- Theme Toggle (Desktop) -->
                    <button id="themeToggle" onclick="toggleTheme()" class="hidden sm:flex w-11 h-11 items-center justify-center rounded-2xl bg-panel text-text-muted shadow-sm border border-slate-200/50 hover:scale-105 transition-all">
                        <i class="fa-solid fa-moon text-lg"></i>
                    </button>

                    @auth
                        <div class="hidden md:block">
                            <form method="POST" action="{{ route('logout') }}" id="logout_form" class="m-0">
                                @csrf
                                <button type="submit" class="flex items-center justify-center gap-2 px-5 py-2.5 rounded-full bg-rose-500 hover:bg-rose-600 text-white font-semibold transition-all shadow-lg hover:shadow-rose-500/30">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                    <span>{{ __('nav.logout') }}</span>
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="hidden md:block">
                            <a href="{{ route('login') }}" class="btn-premium">
                                {{ __('nav.login') }}
                            </a>
                        </div>
                    @endauth

                    <!-- Mobile Menu Btn -->
                    <button @click="mobileMenuOpen = true" class="lg:hidden w-11 h-11 flex items-center justify-center rounded-2xl bg-panel text-text-muted shadow-sm border border-slate-200/50 hover:scale-105 transition-all" aria-label="Toggle menu">
                        <i class="fa-solid fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </nav>

        <!-- Mobile Menu Drawer -->
        <div x-show="mobileMenuOpen" 
             x-cloak
             class="fixed inset-0 z-[100] lg:hidden" 
             role="dialog" aria-modal="true">
            <!-- Overlay -->
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="mobileMenuOpen = false" 
                 class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            
            <!-- Menu Content -->
            <div x-show="mobileMenuOpen"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="ltr:translate-x-full rtl:-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="ltr:translate-x-full rtl:-translate-x-full"
                 class="absolute ltr:right-0 rtl:left-0 top-0 bottom-0 w-80 bg-surface shadow-2xl p-6 flex flex-col border-l border-slate-200 dark:border-slate-800">
                
                <div class="flex items-center justify-between mb-8">
                    <span class="text-xl font-bold text-text-base">Menu</span>
                    <button @click="mobileMenuOpen = false" class="w-10 h-10 flex items-center justify-center rounded-xl bg-panel text-text-muted border border-slate-200/50 shadow-sm">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <div class="space-y-4 mb-8">
                    <a href="{{ route('courses.index') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-panel hover:bg-primary/5 hover:text-primary transition-all font-bold text-text-base border border-slate-200/50">
                        <i class="fa-solid fa-grip text-primary"></i> {{ __('nav.courses') }}
                    </a>
                    <a href="{{ route('bookings.index') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-panel hover:bg-primary/5 hover:text-primary transition-all font-bold text-text-base border border-slate-200/50">
                        <i class="fa-solid fa-calendar-check text-primary"></i> {{ __('nav.book') }}
                    </a>
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-panel hover:bg-primary/5 hover:text-primary transition-all font-bold text-text-base border border-slate-200/50">
                        <i class="fa-solid fa-circle-user text-primary"></i> {{ __('nav.profile') }}
                    </a>
                </div>

                <div class="mt-auto space-y-6">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex items-center bg-panel rounded-2xl p-1 gap-1 border border-slate-200/50">
                            <a href="{{ url('lang/ar') }}" class="flex-1 text-center py-2.5 rounded-xl text-xs font-bold transition-all {{ app()->getLocale() == 'ar' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-text-muted' }}">AR</a>
                            <a href="{{ url('lang/en') }}" class="flex-1 text-center py-2.5 rounded-xl text-xs font-bold transition-all {{ app()->getLocale() == 'en' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-text-muted' }}">EN</a>
                        </div>
                        <button onclick="toggleTheme()" class="flex items-center justify-center gap-2 py-2.5 rounded-2xl bg-panel text-text-muted border border-slate-200/50 shadow-sm font-bold text-xs uppercase transition-all">
                            <i class="fa-solid fa-moon"></i> Theme
                        </button>
                    </div>

                    @auth
                        <form method="POST" action="{{ route('logout') }}" id="logout_form_mobile" class="m-0">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center gap-2 py-4 rounded-2xl bg-rose-500 hover:bg-rose-600 text-white font-bold transition-all shadow-lg shadow-rose-500/30">
                                <i class="fa-solid fa-right-from-bracket"></i>
                                {{ __('nav.logout') }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="w-full flex items-center justify-center gap-2 py-4 rounded-2xl bg-primary hover:bg-primary-hover text-white font-bold transition-all shadow-lg shadow-primary/30">
                            <i class="fa-solid fa-right-to-bracket"></i>
                            {{ __('nav.login') }}
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <x-flash_messages data-auto-dismiss="true" />

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-panel border-t border-slate-200 dark:border-slate-800 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                <div class="space-y-6">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                         <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                            <svg class="text-white w-5 h-5" viewBox="0 0 64 64" fill="currentColor">
                                <path d="M18 21h28a4 4 0 0 1 4 4v18a4 4 0 0 1-4 4H18a4 4 0 0 1-4-4V25a4 4 0 0 1 4-4Zm3 5v16h22a3 3 0 1 0 0-6H25v-4h18a3 3 0 1 0 0-6H21Z" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-text-base">CourseBook</span>
                    </a>
                    <p class="text-text-muted text-sm leading-relaxed">{{ __('footer.description') }}</p>
                </div>

                <div>
                    <h3 class="text-text-base font-bold mb-6 italic border-l-4 border-primary pl-3">{{ __('footer.links_title') }}</h3>
                    <ul class="space-y-4 text-sm font-medium">
                        <li><a href="{{ route('courses.index') }}" class="text-text-muted hover:text-primary transition-colors">{{ __('footer.courses') }}</a></li>
                        <li><a href="{{ route('bookings.index') }}" class="text-text-muted hover:text-primary transition-colors">{{ __('footer.book') }}</a></li>
                        <li><a href="{{ route('profile.edit') }}" class="text-text-muted hover:text-primary transition-colors">{{ __('footer.profile') }}</a></li>
                        <li><a href="{{ route('dashboard') }}" class="text-text-muted hover:text-primary transition-colors">{{ __('footer.admin') }}</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-text-base font-bold mb-6 italic border-l-4 border-primary pl-3">{{ __('footer.contact_title') }}</h3>
                    <ul class="space-y-4 text-sm text-text-muted">
                        <li class="flex items-center gap-3"><i class="fa-solid fa-envelope text-primary"></i> support@coursebook.test</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-phone text-primary"></i> +1 555-0100</li>
                    </ul>
                </div>

                <div class="space-y-6">
                    <h3 class="text-text-base font-bold mb-6 italic border-l-4 border-primary pl-3">{{ __('footer.social_title') }}</h3>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-400 hover:bg-primary hover:text-white transition-all shadow-sm">
                            <i class="fa-brands fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-400 hover:bg-primary hover:text-white transition-all shadow-sm">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-400 hover:bg-primary hover:text-white transition-all shadow-sm">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-200 dark:border-slate-800 pt-8 flex flex-col md:flex-row justify-center items-center gap-4">
                <p class="text-text-muted text-sm">© {{ date('Y') }} CourseBook. Made with  for learning.</p>
                <div class="flex gap-8 text-sm font-medium">
                    <a href="#" class="text-text-muted hover:text-primary transition-colors">{{ __('footer.privacy') }}</a>
                    <a href="#" class="text-text-muted hover:text-primary transition-colors">{{ __('footer.terms') }}</a>
                </div>
            </div>
        </div>
    </footer>

    <div id="toastContainer" class="fixed bottom-4 right-4 z-[9999]"></div>

    <!-- Scripts -->
    <!-- <script src="{{ asset('js/main.js') }}"></script> -->
    @livewireScripts

    @auth
        <x-ai_support />
    @endauth

    <!-- Theme Debug Indicator (Temporary) -->
    <div id="theme-debug" class="fixed bottom-4 left-4 z-[9999] bg-black/80 text-white text-[10px] px-2 py-1 rounded pointer-events-none opacity-50">
        Theme: Loading...
    </div>
    <script>
        document.getElementById('theme-debug').innerText = "Theme: " + (document.documentElement.classList.contains('dark') ? 'dark' : 'light');
    </script>
</body>
</html>
