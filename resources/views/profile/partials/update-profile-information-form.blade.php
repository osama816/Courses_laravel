<section>
    <header class="mb-8">
        <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
            <i class="fa-solid fa-address-card text-primary text-xl"></i>
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-2 text-sm font-medium text-slate-500 dark:text-slate-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div class="space-y-2">
            <x-input-label for="name" :value="__('Name')" class="text-xs font-bold uppercase tracking-widest text-slate-400 ms-1" />
            <x-text-input id="name" name="name" type="text" class="block w-full h-14" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="space-y-2">
            <x-input-label for="email" :value="__('Email')" class="text-xs font-bold uppercase tracking-widest text-slate-400 ms-1" />
            <x-text-input id="email" name="email" type="email" class="block w-full h-14" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="p-4 rounded-2xl bg-amber-50 dark:bg-amber-950/20 border border-amber-200/50 dark:border-amber-900/30">
                    <p class="text-sm font-medium text-amber-800 dark:text-amber-300 flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="ms-auto underline text-xs font-bold hover:text-amber-900 dark:hover:text-amber-100 transition-colors">
                            {{ __('Resend Verification') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-xs font-bold text-emerald-600 dark:text-emerald-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-slate-50 dark:border-slate-800">
            <button type="submit" class="btn-premium px-8 py-3.5 shadow-lg shadow-primary/20">
                <i class="fa-solid fa-cloud-arrow-up me-2"></i>
                {{ __('Save Changes') }}
            </button>

            @if (session('status') === 'profile-updated')
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
