@extends('layouts.app_wep')

@section('title', __('booking.book_course') . ' - CourseBook')

@section('content')

<main class="py-5 flex-grow-1">
    <div class="container">
        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 fw-bold mb-0">{{ __('booking.book_course') }}</h1>

            <div class="badge bg-success-subtle text-success d-flex align-items-center gap-2 px-3 py-2 rounded-pill shadow-sm">
                <span class="realtime-dot blink"></span>
                {{ __('booking.live_seats_updating') }}
            </div>
        </div>

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="bi bi-exclamation-triangle me-2"></i>{{ __('booking.error_occurred') }}</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Booking Form -->
            <div class="col-lg-7">
                <form id="bookingForm"
                      class="needs-validation p-4 rounded-4 shadow-sm border bg-white"
                      novalidate
                      method="POST"
                      action="{{ route('bookings.store') }}">
                     @csrf

                    <div class="mb-4">
                        <h2 class="h4 fw-bold mb-2">{{ __('booking.course') }}: {{ $course->title }}</h2>
                        <div class="d-flex align-items-center gap-3 text-muted">
                            <small class="d-flex align-items-center gap-1">
                                <i class="bi bi-people"></i>
                                {{ __('booking.seats_available') }}:
                                <strong class="text-primary" id="seatsAvailable">
                                    @livewire('available-seats', ['courseId' => $course->id])
                                </strong>
                            </small>
                            <small class="d-flex align-items-center gap-1">
                                <i class="bi bi-tag"></i>
                                {{ __('booking.price') }}:
                                <strong class="text-success">${{ number_format($course->price, 2) }}</strong>
                            </small>
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- Hidden Fields --}}
                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                    <input type="hidden" name="amount" value="{{ $course->price }}">

                    {{-- User Information (if not logged in) --}}
                    @guest
                    <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                        <i class="bi bi-info-circle me-2"></i>
                        <div>
                            {{ __('booking.login_required') }}
                            <a href="{{ route('login') }}" class="alert-link">{{ __('booking.login_here') }}</a>
                        </div>
                    </div>
                    @endguest

                    {{-- Payment Method Selection --}}
                    <fieldset class="mb-4">
                        <legend class="h5 fw-bold mb-3">
                            <i class="bi bi-credit-card me-2"></i>{{ __('booking.payment_method') }}
                        </legend>

                        <div class="row g-3">
                            {{-- Paymob Payment --}}
                            <div class="col-md-6">
                                <input type="radio"
                                       class="btn-check payment-radio"
                                       name="payment_method"
                                       id="paymob"
                                       value="paymob"

                                       autocomplete="off">
                                <label class="btn btn-outline-primary w-100 h-100 p-4 payment-option"
                                       for="paymob"
                                       style="cursor: pointer; transition: all 0.3s ease;">
                                    <div class="d-flex flex-column align-items-center gap-2">
                                        <i class="bi bi-credit-card-2-front fs-1"></i>
                                        <div class="fw-bold">Paymob</div>
                                        <small class="text-muted">{{ __('booking.pay_online') }}</small>
                                    </div>
                                </label>
                            </div>
                            {{-- myfatoorah Payment --}}
                            <div class="col-md-6">
                                <input type="radio"
                                       class="btn-check payment-radio"
                                       name="payment_method"
                                       id="myfatoorah"
                                       value="myfatoorah"

                                       autocomplete="off">
                                <label class="btn btn-outline-primary w-100 h-100 p-4 payment-option"
                                       for="myfatoorah"
                                       style="cursor: pointer; transition: all 0.3s ease;">
                                    <div class="d-flex flex-column align-items-center gap-2">
                                        <i class="bi bi-credit-card-2-front fs-1"></i>
                                        <div class="fw-bold">Myfatoorah</div>
                                        <small class="text-muted">{{ __('booking.pay_online') }}</small>
                                    </div>
                                </label>
                            </div>

                            {{-- Cash Payment --}}
                            <div class="col-md-6">
                                <input type="radio"
                                       class="btn-check payment-radio"
                                       name="payment_method"
                                       id="cash"
                                       value="cash"
                                       autocomplete="off">
                                <label class="btn btn-outline-secondary w-100 h-100 p-4 payment-option"
                                       for="cash"
                                       style="cursor: pointer; transition: all 0.3s ease;">
                                    <div class="d-flex flex-column align-items-center gap-2">
                                        <i class="bi bi-cash-coin fs-1"></i>
                                        <div class="fw-bold">{{ __('booking.cash') }}</div>
                                        <small class="text-muted">{{ __('booking.pay_later') }}</small>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </fieldset>

                    {{-- Terms and Conditions --}}
                    <div class="form-check mb-4">
                        <input class="form-check-input"
                               type="checkbox"
                               id="agreeTerms"
                               required>
                        <label class="form-check-label" for="agreeTerms">
                            {{ __('booking.agree_terms') }}
                            <a href="#" class="text-primary">{{ __('booking.terms_conditions') }}</a>
                        </label>
                        <div class="invalid-feedback">
                            {{ __('booking.must_agree_terms') }}
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex gap-3 flex-wrap">
                        @auth
                            <button class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm"
                                    type="submit"
                                    id="submitBtn">
                                <span class="spinner-border spinner-border-sm me-2 d-none" role="status" id="btnSpinner"></span>
                                <i class="bi bi-check-circle me-2" id="btnIcon"></i>
                                <span id="btnText">{{ __('booking.confirm_booking') }}</span>
                            </button>
                        @else
                            <a href="{{ route('login') }}"
                               class="btn btn-primary  px-3 rounded-pill shadow-sm">
                                <i class="bi bi-box-arrow-in-right me-1"></i>
                                {{ __('booking.login_to_book') }}
                            </a>
                        @endauth

                        <a href="{{ route('courses.show', $course->id) }}"
                           class="btn btn-outline-secondary btn-lg px-2 rounded-pill">
                            <i class="bi bi-arrow-left me-2"></i>
                            {{ __('booking.back_to_course') }}
                        </a>
                    </div>
                </form>
            </div>

            <!-- Summary Card -->
            <div class="col-lg-5">
                <div class="position-sticky" style="top: 100px;">
                    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                        <div class="card-header bg-gradient text-white py-3"
                             style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="fw-bold">
                                    <i class="bi bi-receipt me-2"></i>{{ __('booking.summary') }}
                                </span>
                                <span class="badge bg-white text-primary fw-bold px-3 py-2 rounded-pill">
                                    $<span id="totalPrice">{{ number_format($course->price, 2) }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <dl class="row mb-0">
                                <dt class="col-5 text-muted mb-2">
                                    <i class="bi bi-book me-1"></i>{{ __('booking.course') }}
                                </dt>
                                <dd class="col-7 fw-semibold mb-2">{{ $course->title }}</dd>

                                <dt class="col-5 text-muted mb-2">
                                    <i class="bi bi-calendar me-1"></i>{{ __('booking.start_date') }}
                                </dt>
                                <dd class="col-7 mb-2">{{ $course->created_at->format('d M Y') }}</dd>

                                <dt class="col-5 text-muted mb-2">
                                    <i class="bi bi-person me-1"></i>{{ __('booking.instructor') }}
                                </dt>
                                <dd class="col-7 mb-2">{{ $course->instructor->user->name }}</dd>

                                <dt class="col-5 text-muted mb-2">
                                    <i class="bi bi-speedometer2 me-1"></i>{{ __('booking.level') }}
                                </dt>
                                <dd class="col-7 mb-2">
                                    <span class="badge bg-info-subtle text-info">{{ $course->level }}</span>
                                </dd>

                                <dt class="col-5 text-muted mb-2">
                                    <i class="bi bi-clock me-1"></i>{{ __('booking.duration') }}
                                </dt>
                                <dd class="col-7 mb-2">{{ $course->duration }} {{ __('booking.hours') }}</dd>

                                <hr class="my-3">

                                <dt class="col-5 text-muted mb-2">
                                    <i class="bi bi-wallet2 me-1"></i>{{ __('booking.payment') }}
                                </dt>
                                <dd class="col-7 mb-2">
                                    <span class="badge bg-primary-subtle text-primary" id="paymentMethodBadge">
                                        Paymob
                                    </span>
                                </dd>

                                <dt class="col-5 text-muted fw-bold fs-5">
                                    {{ __('booking.total') }}
                                </dt>
                                <dd class="col-7 fw-bold text-success fs-4">
                                    $<span id="finalPrice">{{ number_format($course->price, 2) }}</span>
                                </dd>
                            </dl>

                            {{-- Security Badge --}}
                            <div class="alert alert-light border d-flex align-items-center mt-3 mb-0" role="alert">
                                <i class="bi bi-shield-check text-success me-2 fs-5"></i>
                                <small class="mb-0">{{ __('booking.secure_payment') }}</small>
                            </div>
                        </div>
                    </div>

                    {{-- Course Features --}}
                    <div class="card border-0 shadow-sm rounded-4 mt-3">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-star me-2 text-warning"></i>{{ __('booking.course_includes') }}
                            </h6>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    {{ __('booking.lifetime_access') }}
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    {{ __('booking.certificate') }}
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    {{ __('booking.support') }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

