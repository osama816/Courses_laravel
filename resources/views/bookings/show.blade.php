

@extends('layouts.app_wep')

@section('title', __('booking.booking_details') . ' #' . $booking->id . ' - CourseBook')

@section('content')

<main class="py-5 flex-grow-1">
    <div class="container">
        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Page Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 fw-bold mb-2">{{ __('booking.booking_details') }}</h1>
                <p class="text-muted mb-0">{{ __('booking.booking_number') }}: <strong>#{{ $booking->id }}</strong></p>
            </div>

            {{-- Status Badge --}}
            <div>
                @if($booking->status === 'confirmed')
                    <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fs-6">
                        <i class="bi bi-check-circle me-1"></i>{{ __('booking.confirmed') }}
                    </span>
                @elseif($booking->status === 'pending')
                    <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill fs-6">
                        <i class="bi bi-clock me-1"></i>{{ __('booking.pending') }}
                    </span>
                @else
                    <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill fs-6">
                        <i class="bi bi-x-circle me-1"></i>{{ __('booking.cancelled') }}
                    </span>
                @endif
            </div>
        </div>

        <div class="row g-4">
            {{-- Main Content --}}
            <div class="col-lg-8">
                {{-- Booking Status Card --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-info-circle me-2 text-primary"></i>{{ __('booking.booking_status') }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        {{-- Timeline --}}
                        <div class="timeline">
                            <div class="timeline-item {{ $booking->status === 'pending' || $booking->status === 'confirmed' || $booking->status === 'cancelled' ? 'active' : '' }}">
                                <div class="timeline-icon bg-primary">
                                    <i class="bi bi-calendar-check text-white"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold mb-1">{{ __('booking.booking_created') }}</h6>
                                    <p class="text-muted mb-0 small">{{ $booking->created_at->format('d M Y, h:i A') }}</p>
                                </div>
                            </div>

                            @if($booking->payment)
                            <div class="timeline-item {{ $booking->payment->isPaid() ? 'active' : '' }}">
                                <div class="timeline-icon {{ $booking->payment->isPaid() ? 'bg-success' : 'bg-secondary' }}">
                                    <i class="bi bi-credit-card text-white"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold mb-1">{{ __('booking.payment_received') }}</h6>
                                    <p class="text-muted mb-0 small">
                                        @if($booking->payment->isPaid())
                                            {{ $booking->payment->paid_at->format('d M Y, h:i A') }}
                                        @else
                                            {{ __('booking.pending_payment') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @endif

                            <div class="timeline-item {{ $booking->status === 'confirmed' ? 'active' : '' }}">
                                <div class="timeline-icon {{ $booking->status === 'confirmed' ? 'bg-success' : 'bg-secondary' }}">
                                    <i class="bi bi-check-circle text-white"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold mb-1">{{ __('booking.booking_confirmed') }}</h6>
                                    <p class="text-muted mb-0 small">
                                        @if($booking->status === 'confirmed')
                                            {{ $booking->updated_at->format('d M Y, h:i A') }}
                                        @else
                                            {{ __('booking.awaiting_confirmation') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Course Details Card --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-book me-2 text-primary"></i>{{ __('booking.course_details') }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <img src="{{ asset('storage/' . $booking->course->image_url) }}"
                                     alt="{{ $booking->course->title }}"
                                     class="img-fluid rounded-3 shadow-sm"
                                     style="width: 100%; height: 150px; object-fit: cover;">
                            </div>
                            <div class="col-md-9 mt-3 mt-md-0">
                                <h4 class="fw-bold mb-3">{{ $booking->course->title }}</h4>
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-center text-muted">
                                            <i class="bi bi-person me-2"></i>
                                            <div>
                                                <small class="d-block">{{ __('booking.instructor') }}</small>
                                                <strong class="text-dark">{{ $booking->course->instructor->user->name }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-center text-muted">
                                            <i class="bi bi-speedometer2 me-2"></i>
                                            <div>
                                                <small class="d-block">{{ __('booking.level') }}</small>
                                                <span class="badge bg-info-subtle text-info">{{ $booking->course->level }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-center text-muted">
                                            <i class="bi bi-clock me-2"></i>
                                            <div>
                                                <small class="d-block">{{ __('booking.duration') }}</small>
                                                <strong class="text-dark">{{ $booking->course->duration }} {{ __('booking.hours') }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-center text-muted">
                                            <i class="bi bi-star-fill text-warning me-2"></i>
                                            <div>
                                                <small class="d-block">{{ __('booking.rating') }}</small>
                                                <strong class="text-dark">{{ number_format($booking->course->rating, 1) }}/5</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('courses.show', $booking->course->id) }}"
                                       class="btn btn-outline-primary btn-sm rounded-pill">
                                        <i class="bi bi-eye me-1"></i>{{ __('booking.view_course') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Payment Information (if exists) --}}
                @if($booking->payment)
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-credit-card me-2 text-primary"></i>{{ __('booking.payment_information') }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <dl class="row mb-0">
                            <dt class="col-sm-4 text-muted mb-2">{{ __('booking.payment_method') }}</dt>
                            <dd class="col-sm-8 mb-2">
                                <span class="badge bg-primary-subtle text-primary text-capitalize">
                                    {{ $booking->payment->payment_method }}
                                </span>
                            </dd>

                            <dt class="col-sm-4 text-muted mb-2">{{ __('booking.transaction_id') }}</dt>
                            <dd class="col-sm-8 mb-2">
                                <code class="text-muted">{{ $booking->payment->transaction_id }}</code>
                            </dd>

                            <dt class="col-sm-4 text-muted mb-2">{{ __('booking.amount_paid') }}</dt>
                            <dd class="col-sm-8 mb-2">
                                <strong class="text-success fs-5">${{ number_format($booking->payment->amount, 2) }}</strong>
                            </dd>

                            <dt class="col-sm-4 text-muted mb-2">{{ __('booking.payment_status') }}</dt>
                            <dd class="col-sm-8 mb-2">
                                @if($booking->payment->isPaid())
                                    <span class="badge bg-success-subtle text-success">
                                        <i class="bi bi-check-circle me-1"></i>{{ __('booking.paid') }}
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning">
                                        <i class="bi bi-clock me-1"></i>{{ __('booking.pending') }}
                                    </span>
                                @endif
                            </dd>

                            <dt class="col-sm-4 text-muted mb-0">{{ __('booking.payment_date') }}</dt>
                            <dd class="col-sm-8 mb-0">
                                {{ $booking->payment->paid_at ? $booking->payment->paid_at->format('d M Y, h:i A') : '-' }}
                            </dd>
                        </dl>
                    </div>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                {{-- Summary Card --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-gradient text-white py-3"
                         style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-receipt me-2"></i>{{ __('booking.summary') }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <dl class="row mb-0">
                            <dt class="col-6 text-muted mb-3">{{ __('booking.booking_date') }}</dt>
                            <dd class="col-6 text-end mb-3">{{ $booking->created_at->format('d M Y') }}</dd>

                            <dt class="col-6 text-muted mb-3">{{ __('booking.course_price') }}</dt>
                            <dd class="col-6 text-end mb-3">${{ number_format($booking->course->price, 2) }}</dd>

                            <hr class="my-2">

                            <dt class="col-6 fw-bold fs-5 mb-0">{{ __('booking.total') }}</dt>
                            <dd class="col-6 text-end fw-bold text-success fs-4 mb-0">
                                ${{ number_format($booking->course->price, 2) }}
                            </dd>
                        </dl>
                    </div>
                </div>

                {{-- Actions Card --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-gear me-2"></i>{{ __('booking.actions') }}
                        </h6>

                        <div class="d-grid gap-2">
                            {{-- Start Course (if confirmed) --}}
                            @if($booking->status === 'confirmed')
                                <a href="{{ route('courses.show', $booking->course->id) }}"
                                   class="btn btn-primary rounded-pill">
                                    <i class="bi bi-play-circle me-2"></i>{{ __('booking.start_course') }}
                                </a>
                            @endif

                            {{-- Download Invoice (if paid) --}}
                            @if($booking->payment && $booking->payment->isPaid())
                                <a href="#" class="btn btn-outline-secondary rounded-pill">
                                    <i class="bi bi-download me-2"></i>{{ __('booking.download_invoice') }}
                                </a>
                            @endif

                            {{-- Cancel Booking (if pending) --}}
                            @if($booking->status === 'pending')
                                <form action="{{ route('bookings.destroy', $booking->id) }}"
                                      method="POST"
                                      >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger rounded-pill w-100">
                                        <i class="bi bi-x-circle me-2"></i>{{ __('booking.cancel_booking') }}
                                    </button>
                                </form>
                            @endif

                            {{-- Back to Bookings --}}
                            <a href="{{ route('bookings.index') }}"
                               class="btn btn-outline-secondary rounded-pill">
                                <i class="bi bi-arrow-left me-2"></i>{{ __('booking.back_to_bookings') }}
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Help Card --}}
                <div class="card border-0 bg-light">
                    <div class="card-body p-4 text-center">
                        <i class="bi bi-question-circle text-primary fs-1 mb-3"></i>
                        <h6 class="fw-bold mb-2">{{ __('booking.need_help') }}</h6>
                        <p class="text-muted small mb-3">{{ __('booking.contact_support_text') }}</p>
                        <a href="#" class="btn btn-sm btn-primary rounded-pill">
                            <i class="bi bi-headset me-2"></i>{{ __('booking.contact_support') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection

@push('styles')
<style>
    /* Timeline Styles */
    .timeline {
        position: relative;
        padding-left: 40px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 17px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e5e7eb;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 2rem;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-icon {
        position: absolute;
        left: -40px;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        z-index: 1;
    }

    .timeline-item.active .timeline-icon {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        50% {
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.4), 0 0 0 8px rgba(102, 126, 234, 0.1);
        }
    }

    .timeline-content {
        padding-left: 0;
    }

    /* Breadcrumb RTL Support */
    [dir="rtl"] .breadcrumb-item + .breadcrumb-item::before {
        float: right;
        padding-right: 0;
        padding-left: 0.5rem;
    }

    /* Card hover effect */
    .card {
        transition: transform 0.2s ease;
    }

    .card:hover {
        transform: translateY(-2px);
    }
</style>
@endpush
