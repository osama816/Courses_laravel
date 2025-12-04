@extends('layouts.app_wep')

@section('title', __('booking.my_bookings') . ' - CourseBook')

@section('content')

<main class="py-5 flex-grow-1">
    <div class="container">
        {{-- Page Header --}}
        <div class="row align-items-center mb-4">
            <div class="col-md-8">
                <h1 class="h3 fw-bold mb-2">
                    <i class="bi bi-calendar-check me-2 text-primary"></i>{{ __('booking.my_bookings') }}
                </h1>
                <p class="text-muted mb-0">{{ __('booking.manage_bookings') }}</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="{{ route('courses.index') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                    <i class="bi bi-plus-circle me-2"></i>{{ __('booking.book_new_course') }}
                </a>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="row g-3 mb-4" >
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 bg-success-subtle">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-success  rounded-circle p-3" style="width: 56px; height: 56px; display: flex; align-items: center; justify-content: center; color: var(--text);">
                                    <i class="bi bi-check-circle fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1" style="color: var(--text);">{{ __('booking.confirmed') }}</h6>
                                <h3 class="mb-0 fw-bold">{{ $bookings->where('status', 'confirmed')->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 bg-warning-subtle">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-warning rounded-circle p-3" style="width: 56px; height: 56px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-clock-history fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">{{ __('booking.pending') }}</h6>
                                <h3 class="mb-0 fw-bold">{{ $bookings->where('status', 'pending')->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 bg-primary-subtle">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-primary  rounded-circle p-3" style="width: 56px; height: 56px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-book fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">{{ __('booking.total_bookings') }}</h6>
                                <h3 class="mb-0 fw-bold">{{ $bookings->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        @if($bookings->isEmpty())
            {{-- Empty State --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5 text-center">
                    <div class="empty-state-icon mb-4">
                        <i class="bi bi-inbox text-muted" style="font-size: 5rem; opacity: 0.3;"></i>
                    </div>
                    <h4 class="fw-bold mb-2">{{ __('booking.no_bookings_yet') }}</h4>
                    <p class="text-muted mb-4">{{ __('booking.start_booking_text') }}</p>
                    <a href="{{ route('courses.index') }}" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm">
                        <i class="bi bi-search me-2"></i>{{ __('booking.browse_courses') }}
                    </a>
                </div>
            </div>
        @else
            {{-- Bookings List --}}
            <div class="row g-4" id="bookingsList">
                @foreach($bookings as $booking)
                <div class="col-12 booking-item"
                     data-status="{{ $booking->status }}"
                     data-title="{{ strtolower($booking->course->title) }}"
                     data-date="{{ $booking->created_at->timestamp }}">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden hover-lift">
                        <div class="row g-0">
                            {{-- Course Image --}}
                            <div class="col-md-3 position-relative">
                                <img src="{{ asset('storage/' . $booking->course->image_url) }}"
                                     alt="{{ $booking->course->title }}"
                                     class="img-fluid w-100 h-100"
                                     style="object-fit: cover; min-height: 200px;">
                                {{-- Status Badge on Image --}}
                                <div class="position-absolute top-0 start-0 m-3">
                                    @if($booking->status === 'confirmed')
                                        <span class="badge bg-success px-3 py-2 rounded-pill shadow">
                                            <i class="bi bi-check-circle me-1"></i>{{ __('booking.confirmed') }}
                                        </span>
                                    @elseif($booking->status === 'pending')
                                        <span class="badge bg-warning px-3 py-2 rounded-pill shadow">
                                            <i class="bi bi-clock me-1"></i>{{ __('booking.pending') }}
                                        </span>
                                    @else
                                        <span class="badge bg-danger px-3 py-2 rounded-pill shadow">
                                            <i class="bi bi-x-circle me-1"></i>{{ __('booking.cancelled') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Booking Details --}}
                            <div class="col-md-9">
                                <div class="card-body p-4 h-100 d-flex flex-column">
                                    {{-- Header --}}
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <span class="badge bg-secondary-subtle text-secondary">
                                                    #{{ $booking->id }}
                                                </span>
                                                @if($booking->payment && $booking->payment->status === 'paid')
                                                    <span class="badge bg-success-subtle text-success">
                                                        <i class="bi bi-credit-card me-1"></i>{{ __('booking.paid') }}
                                                    </span>
                                                @endif
                                            </div>
                                            <h5 class="fw-bold mb-2">{{ $booking->course->title }}</h5>
                                            <p class="text-muted mb-0 small">
                                                <i class="bi bi-person me-1"></i>{{ $booking->course->instructor->user->name }}
                                                <span class="mx-2">•</span>
                                                <i class="bi bi-star-fill text-warning me-1"></i>{{ number_format($booking->course->rating, 1) }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Info Grid --}}
                                    <div class="row g-3 mb-4">
                                        <div class="col-sm-3">
                                            <div class="d-flex align-items-center">
                                                <div class="icon-wrapper bg-primary-subtle text-primary rounded p-2 me-2">
                                                    <i class="bi bi-calendar3"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">{{ __('booking.booked_on') }}</small>
                                                    <strong class="small">{{ $booking->created_at->format('d M Y') }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="d-flex align-items-center">
                                                <div class="icon-wrapper bg-success-subtle text-success rounded p-2 me-2">
                                                    <i class="bi bi-cash"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">{{ __('booking.price') }}</small>
                                                    <strong class="small text-success">${{ number_format($booking->course->price, 2) }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="d-flex align-items-center">
                                                <div class="icon-wrapper bg-info-subtle text-info rounded p-2 me-2">
                                                    <i class="bi bi-speedometer2"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">{{ __('booking.level') }}</small>
                                                    <span class="badge bg-info-subtle text-info small">{{ $booking->course->level }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="d-flex align-items-center">
                                                <div class="icon-wrapper bg-warning-subtle text-warning rounded p-2 me-2">
                                                    <i class="bi bi-clock"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">{{ __('booking.duration') }}</small>
                                                    <strong class="small">{{ $booking->course->duration }}h</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="mt-auto">
                                        <div class="d-flex gap-2 flex-wrap">
                                            <a href="{{ route('bookings.show', $booking->id) }}"
                                               class="btn btn-primary btn-sm rounded-pill px-3">
                                                <i class="bi bi-eye me-1"></i>{{ __('booking.view_details') }}
                                            </a>

                                            @if($booking->status === 'confirmed')
                                                <a href="{{ route('courses.show', $booking->course->id) }}"
                                                   class="btn btn-success btn-sm rounded-pill px-3">
                                                    <i class="bi bi-play-circle me-1"></i>{{ __('booking.start_course') }}
                                                </a>
                                            @endif

                                            @if($booking->payment && $booking->payment->status === 'paid')
                                                <a href="#" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                                    <i class="bi bi-download me-1"></i>{{ __('booking.invoice') }}
                                                </a>
                                            @endif

                                            @if($booking->status === 'pending')
                                                <form action="{{ route('bookings.destroy', $booking->id) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      >
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                                        <i class="bi bi-x-circle me-1"></i>{{ __('booking.cancel') }}
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- No Results Message (Hidden by default) --}}
            <div id="noResults" class="text-center py-5 d-none">
                <i class="bi bi-search text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                <p class="text-muted mt-3">{{ __('booking.no_results_found') }}</p>
            </div>
        @endif
    </div>
</main>

@endsection

@push('styles')
<style>
    .hover-lift {
        transition: all 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.15) !important;
    }

    .booking-item {
        transition: all 0.3s ease;
    }

    .booking-item.hidden {
        display: none !important;
    }

    .icon-wrapper {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-state-icon {
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-20px);
        }
    }

    /* Card status colors */
    .card[data-status="confirmed"] {
        border-left: 4px solid #198754;
    }

    .card[data-status="pending"] {
        border-left: 4px solid #ffc107;
        
    }

    .card[data-status="cancelled"] {
        border-left: 4px solid #dc3545;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusFilter = document.getElementById('statusFilter');
        const searchInput = document.getElementById('searchInput');
        const sortBy = document.getElementById('sortBy');
        const bookingsList = document.getElementById('bookingsList');
        const bookingItems = document.querySelectorAll('.booking-item');
        const noResults = document.getElementById('noResults');

        // Filter by status
        if (statusFilter) {
            statusFilter.addEventListener('change', filterBookings);
        }

        // Search functionality
        if (searchInput) {
            searchInput.addEventListener('input', filterBookings);
        }

        // Sort functionality
        if (sortBy) {
            sortBy.addEventListener('change', sortBookings);
        }

        function filterBookings() {
            const statusValue = statusFilter ? statusFilter.value : '';
            const searchValue = searchInput ? searchInput.value.toLowerCase() : '';
            let visibleCount = 0;

            bookingItems.forEach(item => {
                const itemStatus = item.dataset.status;
                const itemTitle = item.dataset.title;

                const matchesStatus = !statusValue || itemStatus === statusValue;
                const matchesSearch = !searchValue || itemTitle.includes(searchValue);

                if (matchesStatus && matchesSearch) {
                    item.classList.remove('hidden');
                    visibleCount++;
                } else {
                    item.classList.add('hidden');
                }
            });

            // Show/hide no results message
            if (noResults) {
                if (visibleCount === 0 && bookingItems.length > 0) {
                    noResults.classList.remove('d-none');
                } else {
                    noResults.classList.add('d-none');
                }
            }
        }

        function sortBookings() {
            if (!sortBy || !bookingsList) return;

            const items = Array.from(bookingItems);
            const sortValue = sortBy.value;

            items.sort((a, b) => {
                const dateA = parseInt(a.dataset.date);
                const dateB = parseInt(b.dataset.date);

                return sortValue === 'newest' ? dateB - dateA : dateA - dateB;
            });

            items.forEach(item => bookingsList.appendChild(item));
        }
    });
</script>
@endpush
