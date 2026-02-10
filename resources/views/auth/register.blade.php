@extends('layouts.app_wep')

@section('title', __('auth.register_title') . ' - CourseBook')

@section('content')
<main class="min-h-[calc(100vh-80px)] flex items-center justify-center p-4 bg-slate-50/50 dark:bg-slate-950/50">
    <!-- Main Auth Container -->
    <div class="max-w-5xl w-full bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-3xl overflow-hidden relative min-h-[650px] flex flex-col lg:flex-row group" id="authContainer" 
         x-data="{ isRegister: {{ request()->routeIs('register') ? 'true' : 'false' }} }">
        
        <!-- Forms Section -->
        <div class="w-full lg:w-1/2 flex relative min-h-[500px] lg:min-h-full transition-all duration-700 ease-in-out"
             :class="isRegister ? 'ltr:lg:translate-x-full rtl:lg:-translate-x-full' : ''">
            <!-- Login Form -->
            <div class="w-full p-8 md:p-12 lg:p-16 transition-all duration-700 ease-in-out" 
                 :class="isRegister ? 'opacity-0 ltr:translate-x-full rtl:-translate-x-full pointer-events-none' : 'opacity-100 translate-x-0 z-10'">
                <div class="mb-10">
                    <h2 class="text-4xl font-bold text-slate-900 dark:text-white mb-3">{{ __('auth.sign_in') }}</h2>
                    <p class="text-slate-500 font-medium">{{ __('auth.welcome_back') }}</p>
                </div>
                <livewire:login/>
            </div>

            <!-- Register Form -->
            <div class="w-full p-8 md:p-12 lg:p-16 absolute inset-0 transition-all duration-700 ease-in-out"
                 :class="isRegister ? 'opacity-100 translate-x-0 z-10' : 'opacity-0 ltr:-translate-x-full rtl:translate-x-full pointer-events-none'">
                <div class="mb-10">
                    <h2 class="text-4xl font-bold text-slate-900 dark:text-white mb-3">{{ __('auth.sign_up') }}</h2>
                    <p class="text-slate-500 font-medium">{{ __('auth.hello_friend') }}</p>
                </div>
                <livewire:form-register/>
            </div>
        </div>

        <!-- Overlay Section (Desktop only) -->
        <div class="hidden lg:block lg:w-1/2 bg-primary relative overflow-hidden transition-all duration-700 ease-in-out z-20" 
             :class="isRegister ? 'ltr:-translate-x-full rtl:translate-x-full rounded-r-[2.5rem]' : 'rounded-l-[2.5rem] lg:rounded-l-none'">
            <div class="absolute inset-0 bg-gradient-to-br from-primary via-primary/95 to-indigo-800"></div>
            
            <!-- Animated Decorative Elements -->
            <div class="absolute -top-12 -right-12 w-64 h-64 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-12 -left-12 w-80 h-80 bg-accent/20 rounded-full blur-3xl animate-pulse delay-700"></div>

            <div class="relative h-full flex flex-col items-center justify-center p-12 text-white text-center">
                <!-- Content for Login State (Shows switch to Register) -->
                <div class="space-y-6 transition-all duration-700 delay-100" 
                     :class="isRegister ? 'opacity-0 scale-90 pointer-events-none absolute' : 'opacity-100 scale-100'">
                    <i class="fa-solid fa-user-plus text-6xl opacity-20 mb-4 block"></i>
                    <h2 class="text-4xl font-bold leading-tight">{{ __('auth.hello_friend') }}</h2>
                    <p class="text-indigo-100/80 leading-relaxed max-w-sm mx-auto text-lg">{{ __('auth.hello_desc') }}</p>
                    <button @click="isRegister = true" class="group mt-8 px-12 py-4 bg-white text-primary rounded-2xl font-bold shadow-2xl hover:bg-slate-50 hover:scale-105 active:scale-95 transition-all flex items-center gap-2 mx-auto">
                        {{ __('auth.sign_up') }}
                        <i class="fa-solid fa-arrow-right transition-transform group-hover:translate-x-1"></i>
                    </button>
                </div>

                <!-- Content for Register State (Shows switch to Login) -->
                <div class="space-y-6 transition-all duration-700 delay-100" 
                     :class="!isRegister ? 'opacity-0 scale-90 pointer-events-none absolute' : 'opacity-100 scale-100'">
                    <i class="fa-solid fa-shield-halved text-6xl opacity-20 mb-4 block"></i>
                    <h2 class="text-4xl font-bold leading-tight">{{ __('auth.welcome_back') }}</h2>
                    <p class="text-indigo-100/80 leading-relaxed max-w-sm mx-auto text-lg">{{ __('auth.welcome_desc') }}</p>
                    <button @click="isRegister = false" class="group mt-8 px-12 py-4 bg-white/10 backdrop-blur-md border border-white/30 text-white rounded-2xl font-bold shadow-2xl hover:bg-white/20 hover:scale-105 active:scale-95 transition-all flex items-center gap-2 mx-auto">
                        <i class="fa-solid fa-arrow-left transition-transform group-hover:-translate-x-1"></i>
                        {{ __('auth.sign_in') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Switcher -->
        <div class="lg:hidden p-6 bg-slate-50 dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700 text-center">
             <p class="text-slate-500 mb-2" x-text="isRegister ? '{{ __('auth.welcome_back') }}' : '{{ __('auth.hello_friend') }}'"></p>
             <button @click="isRegister = !isRegister" class="text-primary font-bold decoration-primary/30 underline decoration-2 underline-offset-4" x-text="isRegister ? '{{ __('auth.sign_in') }}' : '{{ __('auth.sign_up') }}'"></button>
        </div>
    </div>
</main>
@endsection
