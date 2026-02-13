<?php

namespace App\Providers;

use App\Services\PaymobPaymentService;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\PaymentGatewayInterface;
use App\Services\MyFatoorahPaymentService;
use App\Services\Payment\PaymentVerificationService;
use App\Services\Payment\PaymentProcessingService;
use App\Services\Payment\PaymentFailureService;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(PaymentGatewayInterface::class, function ($app) {
            $gatewayType = request('gateway_type', config('payment.default_gateway'));

            return match ($gatewayType) {
                'myfatoorah' => $app->make(MyFatoorahPaymentService::class),
                'paymob' => $app->make(PaymobPaymentService::class),
                default => $app->make(MyFatoorahPaymentService::class),
            };
        });

        // Register Payment Services
        $this->app->singleton(PaymentVerificationService::class);
        $this->app->singleton(PaymentProcessingService::class);
        $this->app->singleton(PaymentFailureService::class);
    }
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
