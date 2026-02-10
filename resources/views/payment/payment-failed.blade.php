@extends('layouts.app_wep')

@section('title', __('payment.failed_title') . ' - CourseBook')

@section('content')
<main class="min-h-[calc(100vh-80px)] flex items-center justify-center p-4 bg-slate-50/50 dark:bg-slate-950/50">
    <div class="max-w-2xl w-full">
        <!-- Failed Card -->
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-3xl overflow-hidden p-8 md:p-12 text-center">
            <!-- Error Icon -->
            <div class="w-24 h-24 mx-auto mb-6 bg-linear-to-br from-rose-400 to-rose-600 rounded-full flex items-center justify-center shadow-lg shadow-rose-500/30 animate-pulse">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>

            <!-- Title -->
            <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-3">
                {{ __('payment.failed_title') }}
            </h1>
            
            <!-- Message -->
            <p class="text-slate-600 dark:text-slate-400 text-lg mb-8">
                {{ __('payment.failed_message') }}
            </p>

            <!-- Info Box -->
            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-2xl p-6 mb-8">
                <div class="flex items-start gap-3 text-left">
                    <i class="fa-solid fa-circle-info text-amber-600 dark:text-amber-400 text-xl mt-1"></i>
                    <div>
                        <p class="text-sm text-amber-900 dark:text-amber-200 font-semibold mb-1">{{ __('What happened?') }}</p>
                        <p class="text-sm text-amber-800 dark:text-amber-300">
                            {{ __('Your payment was not completed. This could be due to insufficient funds, incorrect payment details, or a network issue.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3 justify-center">
                <a href="{{ route('courses.index') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-primary hover:bg-primary-hover text-white font-bold rounded-xl shadow-lg shadow-primary/20 transition-all hover:scale-105 active:scale-95">
                    <i class="fa-solid fa-rotate-left"></i>
                    {{ __('Try Again') }}
                </a>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-text-base font-bold rounded-xl transition-all hover:scale-105 active:scale-95">
                    <i class="fa-solid fa-home"></i>
                    {{ __('Back to Home') }}
                </a>
            </div>

            <!-- Support Link -->
            <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-800">
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    {{ __('Need help?') }}
                    <a href="#" class="text-primary hover:underline font-semibold">{{ __('Contact Support') }}</a>
                </p>
            </div>
        </div>

        <!-- Decorative Elements -->
        <div class="absolute top-10 ltr:left-10 rtl:right-10 w-32 h-32 bg-rose-500/10 rounded-full blur-3xl -z-10"></div>
        <div class="absolute bottom-10 ltr:right-10 rtl:left-10 w-40 h-40 bg-amber-500/10 rounded-full blur-3xl -z-10"></div>
    </div>
</main>
@endsection
