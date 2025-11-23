@extends('layouts.app_wep')

@section('title', 'CourseBook · Details')



@section('content')
<div class="row g-4 fade-up">
    <div class="col-lg-6">
        <img src="{{ asset('storage/'.$course->image_url) }}" class="img-fluid rounded-4 shadow-hero" alt="${course.title}">
    </div>
    <div class="col-lg-6">
        <h1 class="h3">{{ $course->title }}</h1>
        <div class="mb-2 d-flex align-items-center gap-2">
            <span class="badge bg-secondary badge-level">{{ $course->level }}</span>
            <span class="text-muted small">by {{ $course->instructor->user->name }}</span>
        </div>
        <div class="mb-2 d-flex align-items-center">
            @php
            $rating = round($course->rating);
            @endphp


            @for ($i = 1; $i <= $rating; $i++)
                <i class="bi bi-star-fill text-warning"></i>
                @endfor


                @for ($i = $rating + 1; $i <= 5; $i++)
                    <i class="bi bi-star text-warning"></i>
                    @endfor

                    <span class="ms-2 text-muted small">{{ number_format($course->rating, 1) }}</span>
        </div>

        <p>{{$course->description}}</p>
        <dl class="row">
            <dt class="col-sm-4">{{__('nav.Start date')}}</dt>
            <dd class="col-sm-8">{{ $course->created_at->format('Y-m-d') }}</dd>
            <dt class="col-sm-4">{{__('nav.Duration')}}</dt>
            <dd class="col-sm-8">{{$course->duration}}</dd>
            <dt class="col-sm-4">{{__('nav.Price')}}</dt>
            <dd class="col-sm-8">{{$course->price}}</dd>
        </dl>
        <div class="d-flex gap-2">
           <a href="{{ route('bookings.create',$course->id) }}">
    <button class="btn btn-primary btn-lift btn-pill mb-2">
        <i class="bi bi-cart-check me-1"></i> {{ __('nav.book') }}
    </button>
</a>

        </div>
    </div>
</div>
@endsection
