<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CourseBook') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
        
        <!-- Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Scripts & Styles -->
        <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
        <style type="text/tailwindcss">
            @theme {
                --color-primary: oklch(0.6 0.25 260);
                --color-accent: oklch(0.75 0.2 180);
                --color-surface: oklch(0.98 0.01 240);
                --color-panel: oklch(1 0 0);
                --font-sans: "Outfit", "Inter", ui-sans-serif, system-ui, sans-serif;
                --radius-premium: 1.25rem;
            }
            @layer base {
                body {
                    @apply bg-surface text-slate-900 font-sans selection:bg-primary/20 antialiased;
                }
            }
        </style>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen flex flex-col items-center justify-center p-6 bg-slate-50/50 dark:bg-slate-950/50">
        <div class="w-full sm:max-w-md">
            <div class="flex justify-center mb-8">
                <a href="/" class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-primary rounded-2xl flex items-center justify-center shadow-2xl shadow-primary/20">
                         <svg class="text-white w-7 h-7" viewBox="0 0 64 64" fill="currentColor">
                            <path d="M18 21h28a4 4 0 0 1 4 4v18a4 4 0 0 1-4 4H18a4 4 0 0 1-4-4V25a4 4 0 0 1 4-4Zm3 5v16h22a3 3 0 1 0 0-6H25v-4h18a3 3 0 1 0 0-6H21Z" />
                        </svg>
                    </div>
                </a>
            </div>

            <div class="w-full bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-3xl p-8 md:p-10 border border-slate-200/50">
                {{ $slot }}
            </div>

            <div class="mt-8 text-center">
                 <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">© {{ date('Y') }} CourseBook AI</p>
            </div>
        </div>
    </body>
</html>
