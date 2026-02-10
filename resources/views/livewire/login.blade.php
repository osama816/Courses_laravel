<div>
    <form wire:submit.prevent="login" class="space-y-5">
        
        @if($errorMessage)
            <div class="p-4 mb-4 text-sm text-red-800 rounded-xl bg-red-50 dark:bg-red-900/30 dark:text-red-400" role="alert">
                {{ $errorMessage }}
            </div>
        @endif

        <!-- Email -->
        <div class="space-y-2">
            <label for="email" class="text-sm font-bold text-slate-700 dark:text-slate-300 ms-1">
                {{ __('auth.email') }}
            </label>
            <div class="flex px-4 items-center bg-slate-50 dark:bg-slate-800 border {{ $errors->has('email') ? 'border-red-500 ring-4 ring-red-500/10' : 'border-slate-200 dark:border-slate-700 focus-within:border-primary focus-within:ring-4 focus-within:ring-primary/10' }} rounded-xl transition-all overflow-hidden group/input">
                <input id="email" type="email" name="email" wire:model="email"
                    class="w-full px-4 py-3.5 bg-transparent border-none focus:ring-0 text-slate-900 dark:text-white placeholder-slate-400 font-medium outline-none" 
                    placeholder="name@example.com" autocomplete="email">
                <div class="ps-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-envelope text-slate-400 group-focus-within/input:text-primary transition-colors"></i>
                </div>
            </div>
            @error('email')
                <p class="text-red-500 text-xs mt-1 font-bold ms-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <label for="password" class="text-sm font-bold text-slate-700 dark:text-slate-300 ms-1">
                {{ __('auth.password') }}
            </label>
            <div class="flex px-4 items-center bg-slate-50 dark:bg-slate-800 border {{ $errors->has('password') ? 'border-red-500 ring-4 ring-red-500/10' : 'border-slate-200 dark:border-slate-700 focus-within:border-primary focus-within:ring-4 focus-within:ring-primary/10' }} rounded-xl transition-all overflow-hidden group/input">
                <input id="password" type="password" name="password" wire:model="password"
                    class="w-full px-4 py-3.5 bg-transparent border-none focus:ring-0 text-slate-900 dark:text-white placeholder-slate-400 font-medium outline-none" 
                    placeholder="••••••••" autocomplete="current-password">
                <div class="ps-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-lock text-slate-400 group-focus-within/input:text-primary transition-colors"></i>
                </div>
            </div>
            @error('password')
                <p class="text-red-500 text-xs mt-1 font-bold ms-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center px-1 cursor-pointer">
                <input id="remember_me" type="checkbox" wire:model="remember" 
                    class="rounded-lg border-slate-300 text-primary focus:ring-primary bg-slate-50">
                <span class="ml-2 text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('auth.remember_me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-bold text-primary hover:text-primary-hover underline decoration-primary/30 underline-offset-4 transition-all" 
                   href="{{ route('password.request') }}">
                   {{ __('auth.forgot_password') }}
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <button type="submit" wire:loading.attr="disabled"
            class="w-full py-3.5 px-6 rounded-xl bg-primary hover:bg-primary-hover text-white font-bold shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-70 disabled:cursor-not-allowed transition-all duration-300 flex items-center justify-center gap-2 group relative overflow-hidden">
            <span wire:loading.remove class="flex items-center gap-2">
                {{ __('auth.login_button') }} 
                <i class="fa-solid fa-arrow-right-to-bracket group-hover:translate-x-1 transition-transform"></i>
            </span>
            <span wire:loading class="flex items-center gap-2">
                <i class="fa-solid fa-circle-notch fa-spin"></i> {{ __('auth.sending') }}
            </span>
        </button>

    </form>
</div>

