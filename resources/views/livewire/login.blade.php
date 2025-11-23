<div>
    <form wire:submit.prevent="login">
        <h1>{{ __('auth.login_title') }}</h1>
               @if($errorMessage)
            <div class="alert alert-danger mt-3">{{ $errorMessage }}</div>
        @endif
        <!-- Email -->
        <div class="form-floating mb-3">
            <input id="email" type="email" wire:model.defer="email"
                   class="form-control @error('email') is-invalid @enderror"  autocomplete="email">
            <label for="email"><i class="bi bi-envelope me-1"></i>{{ __('auth.email') }}</label>
            @error('email')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-floating mb-3">
            <input id="password" type="password" wire:model.defer="password"
                   class="form-control @error('password') is-invalid @enderror"  autocomplete="new-password">
            <label for="password"><i class="bi bi-shield-lock me-1"></i>{{ __('auth.password') }}</label>
            @error('password')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="mb-3 form-check d-flex align-items-center">
            <input id="remember_me" type="checkbox" wire:model="remember" class="form-check-input">
            <label for="remember_me" class="form-check-label ms-2">{{ __('auth.remember_me') }}</label>
        </div>
        <div wire:loading.delay>
            <span class="text-success ">{{ __('auth.sending') }}</span>
        </div>
        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                   {{ __('auth.forgot_password') }}
                </a>
            @endif
        </div>
        <button wire:loading.remove type="submit" class="btn btn-primary">{{ __('auth.login_button') }}</button>



    </form>
</div>

