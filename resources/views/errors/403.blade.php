@extends('layouts.app_wep')

@section('title', '403 - ' . (__('auth.unauthorized') ?? 'Access Denied'))

@section('content')
<main class="min-h-[calc(100vh-80px)] flex items-center justify-center p-4 bg-slate-50/50 dark:bg-slate-950/50 transition-colors duration-500 overflow-hidden relative">
    
    <!-- Background Decor -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 opacity-30">
        <div class="absolute top-[-10%] right-[-10%] w-[40%] h-[40%] bg-amber-500/20 blur-[120px] rounded-full animate-pulse-slow"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[40%] h-[40%] bg-red-500/10 blur-[120px] rounded-full animate-pulse-slow delay-700"></div>
    </div>

    <div class="max-w-3xl w-full text-center relative z-10">
        <div class="space-y-8 animate-fade-in-up">
            <!-- Icon / Number -->
            <div class="relative group inline-block">
                <div class="absolute inset-0 bg-amber-500/20 blur-3xl rounded-full scale-75 group-hover:scale-100 transition-transform duration-700 opacity-50"></div>
                
                <div class="relative z-10">
                    <i class="fa-solid fa-lock text-[8rem] md:text-[10rem] text-transparent bg-clip-text bg-gradient-to-br from-amber-400 to-red-500 drop-shadow-2xl animate-bounce-slow"></i>
                </div>
            </div>

            <!-- Content -->
            <div class="space-y-4">
                <h1 class="text-3xl md:text-5xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                    {{ app()->getLocale() == 'ar' ? 'عذراً! ليس لديك صلاحية' : 'Access Denied' }}
                </h1>
                <p class="text-lg text-slate-500 dark:text-slate-400 max-w-lg mx-auto leading-relaxed">
                    {{ app()->getLocale() == 'ar' 
                        ? 'لا تملك الصلاحيات اللازمة للوصول إلى هذه الصفحة. إذا كنت تعتقد أن هذا خطأ، يرجى التواصل مع الإدارة.' 
                        : "You don't have permission to access this resource. If you believe this is an error, please contact support." }}
                </p>
                <div class="text-sm font-bold text-slate-400 uppercase tracking-widest mt-2">
                    {{ app()->getLocale() == 'ar' ? 'خطأ ٤٠٣' : 'Error 403' }}
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-8">
                <a href="{{ route('home') }}" class="btn-premium px-8 py-3.5 text-lg shadow-xl shadow-amber-500/20 min-w-[200px] flex items-center justify-center gap-2">
                    <i class="fa-solid fa-house"></i>
                    {{ app()->getLocale() == 'ar' ? 'العودة للرئيسية' : 'Back to Home' }}
                </a>
                
                @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full sm:w-auto px-8 py-3.5 rounded-2xl border-2 border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 font-bold hover:border-red-500 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-300 min-w-[200px] bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm flex items-center justify-center gap-2">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        {{ app()->getLocale() == 'ar' ? 'تسجيل الخروج' : 'Logout' }}
                    </button>
                </form>
                @endauth
            </div>
        </div>
    </div>
</main>

<style>
    @keyframes bounce-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-15px); }
    }
    .animate-bounce-slow {
        animation: bounce-slow 4s ease-in-out infinite;
    }
    .animate-pulse-slow {
        animation: pulse 6s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>
@endsection
