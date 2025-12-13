<div>
    <form wire:submit.prevent="register">
        <h1></h1>

        <!-- Name -->
        <div class="form-floating mb-3">
            <input id="name" type="text" wire:model.defer="name"
                class="form-control @error('name') is-invalid @enderror" autocomplete="name" autofocus>
            <label for="name"><i class="bi bi-person me-1"></i>{{ __('auth.full_name') }}</label>
            @error('name')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email -->
        <div class="form-floating mb-3">
            <input id="email" type="email" wire:model.defer="email"
                class="form-control @error('email') is-invalid @enderror" autocomplete="email">
            <label for="email"><i class="bi bi-envelope me-1"></i>{{ __('auth.email') }}</label>
            @error('email')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-floating mb-3">
            <input id="password" type="password" wire:model.defer="password"
                class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
            <label for="password"><i class="bi bi-shield-lock me-1"></i>{{ __('auth.password') }}</label>
            @error('password')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="form-floating mb-3">
            <input id="password_confirmation" type="password" wire:model.defer="password_confirmation"
                class="form-control @error('password_confirmation') is-invalid @enderror" autocomplete="new-password">
            <label for="password_confirmation"><i class="bi bi-check2-square me-1"></i>{{ __('auth.confirm_password') }}</label>
            @error('password')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div wire:loading.delay>
            <span class="text-success ">{{ __('auth.sending') }}</span>
        </div>

        <button wire:loading.remove type="submit" class="btn btn-primary">{{ __('auth.sign_in') }}</button>
    </form>
</div>
