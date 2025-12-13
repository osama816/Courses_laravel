@extends('layouts.app_wep')

@section('title', __('auth.register_title') . ' - CourseBook')

@section('content')
<main class="flex-grow-1 d-flex align-items-center">
    <div class="container-fluid px-0">
        <div class="container2" id="container2">
            {{-- Sign Up Container --}}
            <div class="form-container2 sign-up-container2">
                <livewire:form-register/>
            </div>

            {{-- Sign In Container --}}
            <div class="form-container2 sign-in-container2">
                <livewire:login/>
            </div>

            {{-- Overlay Container --}}
            <div class="overlay-container2">
                <div class="overlay">
                    {{-- Left Panel (Welcome Back) --}}
                    <div class="overlay-panel overlay-left">
                        <h1>{{ __('auth.welcome_back') }}</h1>
                        <p>{{ __('auth.welcome_desc') }}</p>
                        <button id="signIn">{{ __('auth.sign_in') }}</button>
                    </div>

                    {{-- Right Panel (Hello Friend) --}}
                    <div class="overlay-panel overlay-right">
                        <h1>{{ __('auth.hello_friend') }}</h1>
                        <p>{{ __('auth.hello_desc') }}</p>
                        <button id="signUp">{{ __('auth.sign_up') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    const container2 = document.getElementById('container2');
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');

    signUpButton.addEventListener('click', () => {
        container2.classList.add('active');
    });

    signInButton.addEventListener('click', () => {
        container2.classList.remove('active');
    });

    // ------------------------------
    // RTL Support: Adjust overlay position based on language direction
    // ------------------------------
    document.addEventListener('DOMContentLoaded', function() {
        const isRTL = document.documentElement.dir === 'rtl' ||
                      document.body.dir === 'rtl' ||
                      getComputedStyle(document.documentElement).direction === 'rtl';

        if (isRTL) {
            // Add RTL class to container for CSS adjustments
            container2.classList.add('rtl-mode');
        }
    });
</script>

<style>
    /* RTL Adjustments */
    .rtl-mode.container2 {
        direction: rtl;
    }

    /* Flip overlay animation for RTL */
    .rtl-mode .overlay-container2 {
        left: auto;
        right: 0;
    }

    .rtl-mode.container2.active .overlay-container2 {
        transform: translateX(100%);
    }

    .rtl-mode .overlay-panel.overlay-left {
        left: auto;
        right: 0;
    }

    .rtl-mode .overlay-panel.overlay-right {
        right: auto;
        left: 0;
    }

    /* Flip form containers for RTL */
    .rtl-mode .form-container2.sign-in-container2 {
        left: auto;
        right: 0;
    }

    .rtl-mode .form-container2.sign-up-container2 {
        left: auto;
        right: 0;
        opacity: 0;
        z-index: 1;
    }

    .rtl-mode.container2.active .form-container2.sign-in-container2 {
        transform: translateX(100%);
    }

    .rtl-mode.container2.active .form-container2.sign-up-container2 {
        transform: translateX(100%);
        opacity: 1;
        z-index: 5;
    }
</style>
@endsection
