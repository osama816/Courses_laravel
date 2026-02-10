@extends('layouts.app_wep')

@section('title', '404 - ' . __('home.page_not_found') ?? 'Page Not Found')

@section('content')
<main class="min-h-[calc(100vh-80px)] flex items-center justify-center p-4 bg-slate-50/50 dark:bg-slate-950/50 transition-colors duration-500 overflow-hidden relative">
    
    <!-- Background Decor -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 opacity-30">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-primary/20 blur-[120px] rounded-full animate-pulse-slow"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-rose-500/10 blur-[120px] rounded-full animate-pulse-slow delay-700"></div>
    </div>

    <div class="max-w-3xl w-full text-center relative z-10">
        <div class="space-y-8 animate-fade-in-up">
            <!-- Illustration / Large Number -->
            <div class="relative group">
                <div class="absolute inset-0 bg-primary/20 blur-3xl rounded-full scale-75 group-hover:scale-100 transition-transform duration-700 opacity-50"></div>
                
                @isset($illustration_url)
                    <img src="{{ $illustration_url }}" alt="404 Illustration" class="w-64 md:w-80 mx-auto transform transition-transform duration-700 hover:scale-105 active:scale-95 drop-shadow-2xl">
                @else
                    <h1 class="text-[10rem] md:text-[15rem] font-black leading-none tracking-tighter bg-linear-to-b from-primary to-primary-hover bg-clip-text text-transparent drop-shadow-2xl animate-bounce-slow">
                        404
                    </h1>
                @endisset
            </div>

            <!-- Content -->
            <div class="space-y-4">
                <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                    {{ app()->getLocale() == 'ar' ? 'أوبس! الصفحة غير موجودة' : 'Oops! Page Not Found' }}
                </h2>
                <p class="text-lg text-slate-500 dark:text-slate-400 max-w-lg mx-auto leading-relaxed">
                    {{ app()->getLocale() == 'ar' 
                        ? 'يبدو أن الصفحة التي تبحث عنها قد تم نقلها أو أنها لم تعد موجودة. لا تقلق، يمكنك العودة دائماً للبداية.' 
                        : "The page you are looking for might have been removed, had its name changed, or is temporarily unavailable." }}
                </p>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                <a href="{{ route('home') }}" class="btn-premium px-8 py-4 text-lg shadow-xl shadow-primary/25 min-w-[200px]">
                    <i class="fa-solid fa-house me-2"></i>
                    {{ app()->getLocale() == 'ar' ? 'العودة للرئيسية' : 'Back to Home' }}
                </a>
                <a href="{{ route('courses.index') }}" class="w-full sm:w-auto px-8 py-4 rounded-2xl border-2 border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 font-bold hover:border-primary hover:text-primary transition-all duration-300 min-w-[200px] bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm">
                    <i class="fa-solid fa-magnifying-glass me-2"></i>
                    {{ app()->getLocale() == 'ar' ? 'تصفح الدورات' : 'Browse Courses' }}
                </a>
            </div>
        </div>

        <!-- Fun Little Message -->
        <div class="mt-16 pt-8 border-t border-slate-200 dark:border-slate-800 animate-fade-in delay-500">
            <p class="text-sm font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">
                {{ app()->getLocale() == 'ar' ? 'كود الخطأ: ٤٠٤' : 'Error Code: 404' }}
            </p>
        </div>
    </div>
</main>

<style>
    @keyframes bounce-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }
    .animate-bounce-slow {
        animation: bounce-slow 4s ease-in-out infinite;
    }
    .animate-pulse-slow {
        animation: pulse 6s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 0.3; }
        50% { opacity: 0.15; }
    }
</style>
@endsection
