



@extends('layouts.app_wep')

@section('title', 'CourseBook · Email Password Reset')



@section('content')
<div class="flex items-center justify-center mt-5">
    
   <x-auth-session-status class="mb-4 text-center " :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <input id="email" type="email" name="email" class="form-control"   autocomplete="email">
           
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</div>
@endsection
