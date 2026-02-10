

@extends('layouts.app_wep')

@section('title', 'CourseBook · verify-email')


@section('content')
<main class="min-h-[calc(100vh-80px)] flex items-center justify-center p-4 bg-slate-50/50 dark:bg-slate-950/50">
    <div class="max-w-md w-full bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-3xl p-8 md:p-12 overflow-hidden relative">
        <!-- Decoration -->
        <div class="absolute -top-12 -right-12 w-32 h-32 bg-primary/10 rounded-full blur-3xl"></div>
        
        <div class="relative">
            <div class="mb-10 text-center">
                <div class="w-20 h-20 bg-primary/10 rounded-3xl flex items-center justify-center text-primary mx-auto mb-8 relative group">
                    <div class="absolute inset-0 bg-primary/20 rounded-3xl blur-xl group-hover:blur-2xl transition-all duration-500"></div>
                    <i class="fa-solid fa-envelope-circle-check text-4xl relative z-10 transition-transform duration-500 group-hover:scale-110"></i>
                </div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-white mb-3">{{ __('Verify Email') }}</h2>
                <p class="text-slate-500 font-medium text-sm leading-relaxed">
                    {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="p-4 mb-6 text-sm text-green-800 rounded-xl bg-green-50 dark:bg-green-900/30 dark:text-green-400 font-bold text-center" role="alert">
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </div>
            @endif

            <div class="space-y-6">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" 
                        class="w-full py-4 px-6 rounded-xl bg-primary hover:bg-primary-hover text-white font-bold shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 flex items-center justify-center gap-2 group">
                        {{ __('Resend Verification Email') }} 
                        <i class="fa-solid fa-paper-plane group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="text-center">
                    @csrf
                    <button type="submit" class="text-sm font-bold text-slate-500 hover:text-rose-500 transition-colors flex items-center justify-center gap-2 mx-auto">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection

