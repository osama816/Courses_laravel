<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Interfaces\PaymentGatewayInterface;
use App\Services\Payment\PaymentVerificationService;
use App\Services\Payment\PaymentProcessingService;
use App\Services\Payment\PaymentFailureService;

class PaymentController extends Controller
{
    protected PaymentGatewayInterface $paymentGateway;
    protected PaymentVerificationService $verificationService;
    protected PaymentProcessingService $processingService;
    protected PaymentFailureService $failureService;

    public function __construct(
        PaymentGatewayInterface $paymentGateway,
        PaymentVerificationService $verificationService,
        PaymentProcessingService $processingService,
        PaymentFailureService $failureService
    ) {
        $this->paymentGateway = $paymentGateway;
        $this->verificationService = $verificationService;
        $this->processingService = $processingService;
        $this->failureService = $failureService;
    }

    /**
     * Initiate payment process
     */
    public function paymentProcess(Request $request)
    {
        $response = $this->paymentGateway->sendPayment($request);

        if ($request->is('api/*')) {
            return response()->json($response, 200);
        }

        return redirect($response['data']['url']);
    }

    /**
     * Handle payment callback
     */
    public function callBack(Request $request)
    {
        try {
            // Verify payment
            $verificationResult = $this->verificationService->verifyPayment($request);

            if (!$verificationResult['success']) {
                $this->failureService->handlePaymentFailure($verificationResult['data'] ?? []);
                return redirect()->route('payment.failed');
            }

            // Process successful payment
            $result = $this->processingService->processSuccessfulPayment($verificationResult['data']);
            Log::channel('payment')->error('Request date: ', [$result['booking']->id, $result['invoice']->id]);
            return redirect()
                ->route('payment.success',  [
                    'booking_id' => $result['booking']->id,
                    'invoice_id' => $result['invoice']->id,
                ]);
        } catch (\Exception $e) {
            Log::channel('payment')->error('Payment callback error: ' . $e->getMessage());
            return redirect()->route('payment.failed');
        }
    }

    /**
     * Show success page
     */
    public function success(Request $request)
    {

    $booking_id = $request->booking_id;
    $invoice_id = $request->invoice_id;
     $invoice = \App\Models\Invoice::with(['payment.booking.course'])->find($invoice_id);
        return view('payment.payment-success', compact('booking_id', 'invoice_id', 'invoice'));
    }

    /**
     * Show failure page
     */
    public function failed()
    {
        return view('payment.payment-failed');
    }
}
