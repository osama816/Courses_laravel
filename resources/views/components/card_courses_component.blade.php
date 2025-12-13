@props(['course'])

<div class="col-md-4 mb-4">

    <div class="card card-lift h-100 course-card fade-up" aria-label="{{ $course->title }}">
        <a href="{{ route('courses.show', $course->id) }}">
            @if($course->image_url)
            <img src="{{ asset('storage/'.$course->image_url) }}" class="card-img-top" alt="{{ $course->title }}">
            @else
            <img src="{{ $course->image }}" class="card-img-top" alt="{{ $course->title }}">
            @endif
        </a>
        <div class="card-body d-flex flex-column">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h3 class="h5 mb-0">{{ $course->getTranslation('title', app()->getLocale()) }}</h3>
                <span class="badge bg-secondary badge-level">{{ $course->level }}</span>
            </div>

            <div class="small text-muted mb-2">
                {{ __('nav.by')}} {{ $course->instructor->user->name ?? 'Unknown Instructor' }}
            </div>

            <div class="d-flex align-items-center gap-2 small mb-2">
                @php
                $rating = $course->rating ?? 0;
                $fullStars = floor($rating);
                $hasHalfStar = ($rating - $fullStars) >= 0.5;
                @endphp

                @for($i = 0; $i < $fullStars; $i++)
                    <i class="bi bi-star-fill text-warning"></i>
                    @endfor

                    @if($hasHalfStar)
                    <i class="bi bi-star-half text-warning"></i>
                    @endif

                    @for($i = 0; $i < (5 - $fullStars - ($hasHalfStar ? 1 : 0)); $i++)
                        <i class="bi bi-star text-warning"></i>
                        @endfor

                        <span class="text-muted">({{ number_format($rating, 1) }})</span>
            </div>

            <p class="text-truncate-2 mb-3">{{ Str::limit($course->description, 100) }}</p>

            <div class="d-flex gap-2 mb-3">
                <span class="badge duration-badge">
                    <i class="bi bi-clock-history me-1"></i>{{ __('nav.hours')}} {{ $course->duration ?? '10' }}
                </span>
                <span class="badge price-badge">
                    <i class="bi bi-tag me-1"></i>${{ number_format($course->price, 2) }}
                </span>
            </div>

            <div class="mt-auto d-flex justify-content-between align-items-center course-actions">
                <a href="{{ route('courses.show', $course->id) }}" class="btn btn-sm btn-outline-secondary btn-lift btn-pill">
                    <i class="bi bi-eye me-1"></i>{{ __('nav.View Details') }}
                </a>

            </div>
        </div>
    </div>
</div>
