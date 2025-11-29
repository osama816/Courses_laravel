<div class="flex justify-center text-center">
{{-- Success Message --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4 d-flex align-items-center" role="alert">
        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
        <div class="flex-grow-1">{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Info Message --}}
@if (session('info'))
    <div class="alert alert-info alert-dismissible fade show mb-4 d-flex align-items-center" role="alert">
        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
        <div class="flex-grow-1">{{ session('info') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Warning Message --}}
@if (session('warning'))
    <div class="alert alert-warning alert-dismissible fade show mb-4 d-flex align-items-center" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
        <div class="flex-grow-1">{{ session('warning') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Error Message --}}
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4 d-flex align-items-center" role="alert">
        <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
        <div class="flex-grow-1">{{ session('error') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Validation Errors --}}
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex align-items-start">
            <i class="bi bi-exclamation-circle-fill me-2 fs-5 mt-1"></i>
            <div class="flex-grow-1">
                <strong class="d-block mb-2">{{ __('messages.validation_errors') }}</strong>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<style>
    .alert {
        border-left: 4px solid;
        animation: slideInRight 0.3s ease-out;
    }

    .alert-success {
        border-left-color: #198754;
    }

    .alert-info {
        border-left-color: #0dcaf0;
    }

    .alert-warning {
        border-left-color: #ffc107;
    }

    .alert-danger {
        border-left-color: #dc3545;
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Auto dismiss after 5 seconds */
    .alert {
        transition: opacity 0.3s ease-out;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto dismiss alerts after 5 seconds
        const alerts = document.querySelectorAll('.alert[data-auto-dismiss="true"]');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    });
</script>
</div>
