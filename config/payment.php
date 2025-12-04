<?php

return [
    'myfatoorah' => [
        'base_url' => env('MYFATOORAH_BASE_URL'),
        'api_key' => env('MYFATOORAH_API_KEY'),
    ],

    'paymob' => [
        'base_url' => env('PAYMOD_BASE_URL'),
        'api_key' => env('PAYMOD_API_KEY'),
        'integration_ids' => [5403743, 5403761, 5403762],
    ],

    'default_gateway' => env('PAYMENT_GATEWAY', 'paymob'),
];
