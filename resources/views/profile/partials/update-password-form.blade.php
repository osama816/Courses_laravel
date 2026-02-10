<section>
    <header class="mb-8">
        <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
            <i class="fa-solid fa-shield-halved text-primary text-xl"></i>
            {{ __('Update Password') }}
        </h2>

        <p class="mt-2 text-sm font-medium text-slate-500 dark:text-slate-400">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div class="space-y-2">
            <x-input-label for="update_password_current_password" :value="__('Current Password')" class="text-xs font-bold uppercase tracking-widest text-slate-400 ms-1" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="block w-full h-14" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="space-y-2">
            <x-input-label for="update_password_password" :value="__('New Password')" class="text-xs font-bold uppercase tracking-widest text-slate-400 ms-1" />
            <x-text-input id="update_password_password" name="password" type="password" class="block w-full h-14" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div class="space-y-2">
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="text-xs font-bold uppercase tracking-widest text-slate-400 ms-1" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="block w-full h-14" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-slate-50 dark:border-slate-800">
            <button type="submit" class="btn-premium px-8 py-3.5 shadow-lg shadow-primary/20">
                <i class="fa-solid fa-key me-2"></i>
                {{ __('Update Password') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-2"
                >
                    <i class="fa-solid fa-check-circle"></i>
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>
