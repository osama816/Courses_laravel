@extends('layouts.app_wep')

@section('title', __('payment.failed_title'))

@section('content')

<main class="py-5 flex-grow-1">
<div class="contant">
    <h1> {{ __('payment.failed_title') }}</h1>
    <p>{{ __('payment.failed_message') }}</p>
</div>
</main>

<style>
        h1 {
            color: #dc3545;
            font-size: 2em;
            margin-bottom: 10px;
        }
        p {
            color: #555;
            font-size: 1.2em;
        }
                .contant{

            text-align: center;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
            max-width: 600px;
            margin: 0 auto;

        }
    </style>
@endsection



