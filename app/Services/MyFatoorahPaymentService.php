<?php

namespace App\Services;

use App\Helper\ApiResponse;
use App\Interfaces\PaymentGatewayInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\BasePaymentService;

class MyFatoorahPaymentService extends BasePaymentService implements PaymentGatewayInterface
{
    protected $api_key;

    public function __construct()
    {
        $this->base_url = config('payment.myfatoorah.base_url');
        $this->api_key = config('payment.myfatoorah.api_key');
        $this->header = [
            'accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->api_key,
        ];
    }

    /**
     * Send payment request
     */
    public function sendPayment(Request $request)
    {
        $data = $this->formatPaymentData($request);
        $response = $this->buildRequest('POST', '/v2/SendPayment', $data);

        if ($this->isResponseSuccessful($response)) {
            return $this->extractPaymentUrl($response);
        }

        return $this->getFailedResponse();
    }

    /**
     * Handle payment callback
     */
    public function callBack(Request $request)
    {
        $statusData = $this->getPaymentStatus($request->input('paymentId'));

        $this->logCallback($request, $statusData);

        if ($this->isPaymentPaid($statusData)) {
            return $this->formatSuccessResponse($statusData, $request);
        }

        return false;
    }

    /**
     * Get payment status from gateway
     */
    private function getPaymentStatus(string $paymentId): array
    {
        $data = [
            'KeyType' => 'paymentId',
            'Key' => $paymentId,
        ];

        $response = $this->buildRequest('POST', '/v2/getPaymentStatus', $data);
        return $response->getData(true);
    }

    /**
     * Format payment data
     */
    protected function formatPaymentData(Request $request): array
    {
        $intentToken = $request->get('intent_token');

        return [
            'InvoiceValue' => $request->get('InvoiceValue'),
            'DisplayCurrencyIso' => $request->get('currency'),
            'NotificationOption' => 'LNK',
            'Language' => 'ar',
            'CallBackUrl' => route('payment.callback', ['gateway_type' => 'myfatoorah', 'intent_token' => $intentToken]),
            'CustomerName' => $request->get('CustomerName'),
            'CustomerEmail' => $request->get('CustomerEmail'),
            'CustomerReference' => $request->get('booking_id'),
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
     * Extract payment URL from response
     */
    private function extractPaymentUrl($response): array
    {
        $data = $response->getData(true);
        return [
            'success' => true,
            'data' => [
                'url' => $data['data']['Data']['InvoiceURL'],
                'booking_id' => $data['data']['Data']['CustomerReference'],
            ],
        ];
    }

    /**
     * Get failed response
     */
    private function getFailedResponse(): array
    {
        return [
            'success' => false,
            'url' => route('payment.failed'),
        ];
    }

    /**
     * Check if payment is paid
     */
    private function isPaymentPaid(array $statusData): bool
    {
        return isset($statusData['data']['Data']['InvoiceStatus'])
            && $statusData['data']['Data']['InvoiceStatus'] === 'Paid';
    }

    /**
     * Format success response
     */
    private function formatSuccessResponse(array $statusData, Request $request): array
    {
        $data = $statusData['data']['Data'];

        return [
            'amount' => $data['InvoiceValue'],
            'payment_method' => 'myfatoorah',
            'booking_id' => $data['CustomerReference'],
            'status' => 'paid',
            'TransactionId' => $data['InvoiceTransactions'][0]['TransactionId'],
            'intent_token' => $request->input('intent_token'),
        ];
    }

    /**
     * Log callback data
     */
    private function logCallback(Request $request, array $statusData): void
    {
        Log::channel('payment')->info('MyFatoorah callback', [
            'request' => $request->all(),
            'status' => $statusData,
        ]);
    }
}
