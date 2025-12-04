<?php

namespace App\Services;

use App\Helper\ApiResponse;
use Illuminate\Http\Request;
use App\Interfaces\PaymentGatewayInterface;
use Illuminate\Support\Facades\Log;

class PaymobPaymentService extends BasePaymentService implements PaymentGatewayInterface
{
    protected $api_key;
    protected $integrations_id;

    public function __construct()
    {
        $this->base_url = config('payment.paymob.base_url');
        $this->api_key = config('payment.paymob.api_key');
        $this->integrations_id = config('payment.paymob.integration_ids');
        $this->header = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Send payment request
     */
    public function sendPayment(Request $request)
    {
        $token = $this->generateToken();
        $this->header['Authorization'] = 'Bearer ' . $token;

        $data = $this->formatPaymentData($request);
        $response = $this->buildRequest('POST', '/api/ecommerce/orders', $data);

        if ($this->isResponseSuccessful($response)) {
            return $response->getData(true);
        }

        return ApiResponse::error('فشل بدء الدفع', [
            'url' => route('payment.failed')
        ], 500);
    }

    /**
     * Handle payment callback
     */
    public function callBack(Request $request): bool
    {
        $response = $request->all();

        $this->logCallback($response);

        return $this->isCallbackSuccessful($response);
    }

    /**
     * Generate authentication token
     */
    protected function generateToken(): string
    {
        $response = $this->buildRequest('POST', '/api/auth/tokens', [
            'api_key' => $this->api_key
        ]);

        return $response->getData(true)['data']['token'];
    }

    /**
     * Format payment data
     */
    protected function formatPaymentData(Request $request): array
    {
        return [
            'amount_cents' => $request->get('amount_cents') * 100,
            'currency' => $request->get('currency', 'EGP'),
            'api_source' => 'INVOICE',
            'integrations' => $this->integrations_id,
            'shipping_data' => [
                'booking_id' => $request->get('booking_id'),
            ],
        ];
    }

    /**
     * Check if response is successful
     */
    private function isResponseSuccessful($response): bool
    {
        $data = $response->getData(true);
        return isset($data['success']) && $data['success'];
    }

    /**
     * Check if callback is successful
     */
    private function isCallbackSuccessful(array $response): bool
    {
        return isset($response['success']) && $response['success'] === 'true';
    }

    /**
     * Log callback data
     */
    private function logCallback(array $response): void
    {
        Log::channel('payment')->info('Paymob callback response', $response);
    }
}
