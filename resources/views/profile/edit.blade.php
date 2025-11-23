

@extends('layouts.app_wep')

@section('title', 'CourseBook · profile')


@section('content')
  <main class="py-4 flex-grow-1 section-gradient">
    <div class="container">
      <div class="row g-4">
        <div class="col-lg-4">
          <div class="card card-lift h-100 fade-up">
            <div class="card-body">
              <h1 class="h5"><i class="bi bi-person-lines-fill me-1"></i>Profile</h1>
 
                   <form method="post" action="{{ route('profile.update') }}" class="needs-validation"  >
        @csrf
        @method('patch')
                <div class="form-floating mb-3">
                  <input id="pName" class="form-control form-modern" name="name" value="{{ $user->name }}" type="text"  required>
                  <label for="pName">Full name</label>
                  <div class="invalid-feedback">Enter your name.</div>
                </div>
                <div class="form-floating mb-3">
                  <input id="pEmail" class="form-control form-modern" name="email" type="email" value="{{ $user->email }}" required>
                  <label for="pEmail">Email</label>
                  <div class="invalid-feedback">Enter a valid email.</div>
                </div>
                <p class="small text-muted mb-3"><!-- TODO: POST to /api/users/me --></p>
                <button class="btn btn-primary btn-lift btn-pill" type="submit"><i class="bi bi-save2 me-1"></i>Save changes</button>
              </form>
            </div>
          </div>
        </div>
        <div class="col-lg-8">
          <div class="card card-lift h-100 fade-up delay-1">
            <div class="card-body">
              <div class="d-flex align-items-center justify-content-between">
                <h2 class="h5 mb-0"><i class="bi bi-journal-check me-1"></i>My Bookings</h2>
                <a class="btn btn-sm btn-outline-primary btn-lift btn-pill" href=""><i class="bi bi-plus-circle me-1"></i>New booking</a>
              </div>
              <div class="table-responsive mt-3">
                <table class="table align-middle table-hover table-modern">
                  <thead>
                    <tr>
                      <th>Course</th>
                      <th>Date</th>>
                      <th>Status</th>
                    </tr>
                    @foreach ($user->bookings as $booking )
                        <tr>
                      <th>{{ $booking->course->title }}</th>
                      <th>{{ $booking->created_at }}</th>
                      @if ( $booking->status=='confirmed')
                      <th class="badge bg-success text-white">{{ $booking->status }}</th>
                      
                      @elseif( $booking->status=='pending')
                      <th class="badge bg-success text-white">{{ $booking->status }}</th>
                      @elseif( $booking->status=='cancelled')
                      <th class="badge bg-danger text-white">{{ $booking->status }}</th>
                       @endif
                    </tr>
                    @endforeach
                  </thead>
                  <tbody id="myBookingsBody">
                    <!-- Populated by main.js -->
                  </tbody>
                </table>
              </div>
              <div id="noBookingsNote" class="text-muted small" aria-live="polite"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection

