<?php

namespace App\Providers;

use App\Services\PaymobPaymentService;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\PaymentGatewayInterface;
use App\Services\MyFatoorahPaymentService;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
              $this->app->singleton(PaymentGatewayInterface::class, function ($app) {
            $gatewayType = request('gateway_type', 'paymob');
            return match ($gatewayType) {
                'myfatoorah' => $app->make(MyFatoorahPaymentService::class),
                'paymob' => $app->make(PaymobPaymentService::class),
                default => $app->make(PaymobPaymentService::class),
            };
        });

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
