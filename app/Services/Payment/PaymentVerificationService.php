<?php

namespace App\Services\Payment;

use App\Interfaces\PaymentGatewayInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentVerificationService
{
    protected PaymentGatewayInterface $paymentGateway;

    public function __construct(PaymentGatewayInterface $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    /**
     * Verify payment from gateway callback
     */
    public function verifyPayment(Request $request): array
    {
        Log::channel('payment')->info('Payment callback received', $request->all());

        $paymentData = $this->paymentGateway->callBack($request);

        if (!$paymentData) {
            return [
                'success' => false,
                'message' => 'Payment verification failed',
            ];
        }

        if ($this->isPaymentSuccessful($paymentData)) {
            return [
                'success' => true,
                'data' => $paymentData,
            ];
        }

        return [
            'success' => false,
            'data' => $paymentData,
        ];
    }

    /**
     * Check if payment is successful
     */
    private function isPaymentSuccessful(array $paymentData): bool
    {
        return isset($paymentData['status'])
            && strtolower($paymentData['status']) === 'paid';
    }
}
