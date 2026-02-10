<div>
    <form wire:submit.prevent="register" class="space-y-5">
        
        <!-- Name -->
        <div class="space-y-2">
            <label for="name" class="text-sm font-bold text-slate-700 dark:text-slate-300 ms-1">
                {{ __('auth.full_name') }}
            </label>
            <div class="flex px-4 items-center bg-slate-50 dark:bg-slate-800 border {{ $errors->has('name') ? 'border-red-500 ring-4 ring-red-500/10' : 'border-slate-200 dark:border-slate-700 focus-within:border-primary focus-within:ring-4 focus-within:ring-primary/10' }} rounded-xl transition-all overflow-hidden group/input">
                <input id="name" type="text" name="name" wire:model="name"
                    class="w-full px-4 py-3.5 bg-transparent border-none focus:ring-0 text-slate-900 dark:text-white placeholder-slate-400 font-medium outline-none" 
                    placeholder="John Doe" autocomplete="name" autofocus>
                <div class="ps-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-user text-slate-400 group-focus-within/input:text-primary transition-colors"></i>
                </div>
            </div>
            @error('name')
                <p class="text-red-500 text-xs mt-1 font-bold ms-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div class="space-y-2">
            <label for="email" class="text-sm font-bold text-slate-700 dark:text-slate-300 ms-1">
                {{ __('auth.email') }}
            </label>
            <div class="flex items-center bg-slate-50 dark:bg-slate-800 border px-4 {{ $errors->has('email') ? 'border-red-500 ring-4 ring-red-500/10' : 'border-slate-200 dark:border-slate-700 focus-within:border-primary focus-within:ring-4 focus-within:ring-primary/10' }} rounded-xl transition-all overflow-hidden group/input">
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
        <div class="space-y-2" x-data="{ showPassword: false }">
            <label for="password" class="text-sm font-bold text-slate-700 dark:text-slate-300 ms-1">
                {{ __('auth.password') }}
            </label>
            <div class="relative flex items-center bg-slate-50 dark:bg-slate-800 border {{ $errors->has('password') ? 'border-red-500 ring-4 ring-red-500/10' : 'border-slate-200 dark:border-slate-700 focus-within:border-primary focus-within:ring-4 focus-within:ring-primary/10' }} rounded-xl transition-all overflow-hidden group/input">
                <input id="password" :type="showPassword ? 'text' : 'password'" name="password" wire:model="password"
                    class="w-full px-4 py-3.5 bg-transparent border-none focus:ring-0 text-slate-900 dark:text-white placeholder-slate-400 font-medium outline-none" 
                    placeholder="••••••••" autocomplete="new-password">
                <button type="button" @click="showPassword = !showPassword"
                    class="absolute end-4 flex items-center justify-center text-slate-400 hover:text-primary transition-colors p-2">
                    <i :class="showPassword ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                </button>
            </div>
            @error('password')
                <p class="text-red-500 text-xs mt-1 font-bold ms-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="space-y-2">
            <label for="password_confirmation" class="text-sm font-bold text-slate-700 dark:text-slate-300 ms-1">
                {{ __('auth.confirm_password') }}
            </label>
            <div class="flex px-4 items-center bg-slate-50 dark:bg-slate-800 border {{ $errors->has('password_confirmation') ? 'border-red-500 ring-4 ring-red-500/10' : 'border-slate-200 dark:border-slate-700 focus-within:border-primary focus-within:ring-4 focus-within:ring-primary/10' }} rounded-xl transition-all overflow-hidden group/input">
                <input id="password_confirmation" type="password" name="password_confirmation" wire:model="password_confirmation"
                    class="w-full px-4 py-3.5 bg-transparent border-none focus:ring-0 text-slate-900 dark:text-white placeholder-slate-400 font-medium outline-none" 
                    placeholder="••••••••" autocomplete="new-password">
                <div class="ps-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-circle-check text-slate-400 group-focus-within/input:text-primary transition-colors"></i>
                </div>
            </div>
            @error('password_confirmation')
                <p class="text-red-500 text-xs mt-1 font-bold ms-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" wire:loading.attr="disabled"
            class="w-full py-3.5 px-6 rounded-xl bg-primary hover:bg-primary-hover text-white font-bold shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-70 disabled:cursor-not-allowed transition-all duration-300 flex items-center justify-center gap-2 group relative overflow-hidden mt-2">
            <span wire:loading.remove class="flex items-center gap-2">
                {{ __('auth.sign_up') }} 
                <i class="fa-solid fa-user-plus group-hover:translate-x-1 transition-transform"></i>
            </span>
            <span wire:loading class="flex items-center gap-2">
                <i class="fa-solid fa-circle-notch fa-spin"></i> {{ __('auth.sending') }}
            </span>
        </button>

    </form>
</div>
