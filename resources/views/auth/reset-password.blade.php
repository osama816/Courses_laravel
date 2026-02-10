@extends('layouts.app_wep')

@section('title', 'CourseBook · Reset Password')

@section('content')
<main class="min-h-[calc(100vh-80px)] flex items-center justify-center p-4 bg-slate-50/50 dark:bg-slate-950/50">
    <div class="max-w-md w-full bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-3xl p-8 md:p-12 overflow-hidden relative">
        <!-- Decoration -->
        <div class="absolute -top-12 -right-12 w-32 h-32 bg-primary/10 rounded-full blur-3xl"></div>
        
        <div class="relative">
            <div class="mb-10 text-center">
                <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center text-primary mx-auto mb-6">
                    <i class="fa-solid fa-lock-open text-2xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-white mb-3">{{ __('Reset Password') }}</h2>
                <p class="text-slate-500 font-medium text-sm">{{ __('Set your new password below.') }}</p>
            </div>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div class="space-y-2">
                    <label for="email" class="text-sm font-bold text-slate-700 dark:text-slate-300 ms-1">
                        {{ __('Email') }}
                    </label>
                    <div class="flex items-center bg-slate-50 dark:bg-slate-800 border {{ $errors->has('email') ? 'border-red-500 ring-4 ring-red-500/10' : 'border-slate-200 dark:border-slate-700 focus-within:border-primary focus-within:ring-4 focus-within:ring-primary/10' }} rounded-xl transition-all overflow-hidden group/input">
                        <div class="ps-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-envelope text-slate-400 group-focus-within/input:text-primary transition-colors"></i>
                        </div>
                        <input id="email" type="email" name="email" :value="old('email', $request->email)" required
                            class="w-full px-4 py-3.5 bg-transparent border-none focus:ring-0 text-slate-900 dark:text-white placeholder-slate-400 font-medium outline-none" 
                            placeholder="name@example.com">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <label for="password" class="text-sm font-bold text-slate-700 dark:text-slate-300 ms-1">
                        {{ __('Password') }}
                    </label>
                    <div class="flex items-center bg-slate-50 dark:bg-slate-800 border {{ $errors->has('password') ? 'border-red-500 ring-4 ring-red-500/10' : 'border-slate-200 dark:border-slate-700 focus-within:border-primary focus-within:ring-4 focus-within:ring-primary/10' }} rounded-xl transition-all overflow-hidden group/input">
                        <div class="ps-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-slate-400 group-focus-within/input:text-primary transition-colors"></i>
                        </div>
                        <input id="password" type="password" name="password" required
                            class="w-full px-4 py-3.5 bg-transparent border-none focus:ring-0 text-slate-900 dark:text-white placeholder-slate-400 font-medium outline-none" 
                            placeholder="••••••••">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="space-y-2">
                    <label for="password_confirmation" class="text-sm font-bold text-slate-700 dark:text-slate-300 ms-1">
                        {{ __('Confirm Password') }}
                    </label>
                    <div class="flex items-center bg-slate-50 dark:bg-slate-800 border {{ $errors->has('password_confirmation') ? 'border-red-500 ring-4 ring-red-500/10' : 'border-slate-200 dark:border-slate-700 focus-within:border-primary focus-within:ring-4 focus-within:ring-primary/10' }} rounded-xl transition-all overflow-hidden group/input">
                        <div class="ps-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-circle-check text-slate-400 group-focus-within/input:text-primary transition-colors"></i>
                        </div>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            class="w-full px-4 py-3.5 bg-transparent border-none focus:ring-0 text-slate-900 dark:text-white placeholder-slate-400 font-medium outline-none" 
                            placeholder="••••••••">
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <button type="submit" 
                    class="w-full py-4 px-6 rounded-xl bg-primary hover:bg-primary-hover text-white font-bold shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 flex items-center justify-center gap-2 group">
                    {{ __('Reset Password') }} 
                    <i class="fa-solid fa-key group-hover:rotate-12 transition-transform"></i>
                </button>
            </form>
        </div>
    </div>
</main>
@endsection
