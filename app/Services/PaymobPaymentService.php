<?php

namespace App\Services;

use App\Helper\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\PaymentGatewayInterface;
use Illuminate\Support\Facades\Log;

class PaymobPaymentService extends BasePaymentService implements PaymentGatewayInterface
{
    /**
     * Create a new class instance.
     */
    protected $api_key;
    protected $integrations_id;

    public function __construct()
    {
        $this->base_url = env("PAYMOD_BASE_URL");
        $this->api_key = env("PAYMOD_API_KEY");
        $this->header = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $this->integrations_id = [5403743,5403761,5403762];
    }

//first generate token to access api
    protected function generateToken()
    {
        $response = $this->buildRequest('POST', '/api/auth/tokens', ['api_key' => $this->api_key]);
        return $response->getData(true)['data']['token'];
    }



    public function sendPayment(Request $request)
    {
        $this->header['Authorization'] = 'Bearer ' . $this->generateToken();
        //validate data before sending it
       $data = $this->formatData($request);

        $response = $this->buildRequest('POST', '/api/ecommerce/orders', $data);
        //handel payment response data and return it
        if ($response->getData(true)['success']) {
            //dd($response->getData(true));
            return $response->getData(true);
        }


      return ApiResponse::error('Payment initiation failed', ['url' => route('payment.failed')], 500);

    }

    public function callBack(Request $request): bool
    {
        $response = $request->all();
        Storage::put('paymob_response.json', json_encode($request->all()));
        Log::channel('payment')->info('Paymob callback response', $response);

        if (isset($response['success']) && $response['success'] === 'true') {

            return true;
        }
        return false;

    }


    protected function formatData(Request $request): array
    {
        return [
            "amount_cents" => $request->get('amount_cents') * 100,
            "currency" => $request->get('currency'),
            "api_source" => "INVOICE",
            "integrations" => $this->integrations_id,
            "shipping_data" => [
                "booking_id" => $request->get('booking_id','test'),

            ],
        ];
    }

}

// [▼ // app\Services\PaymobPaymentService.php:49
//   "amount_cents" => 0
//   "currency" => "EGP"
//   "api_source" => "INVOICE"
//   "integrations" => array:3 [▶]
//   "shipping_data" => array:1 [▼
//     "booking_id" => "test"
//   ]
// ]

// array:5 [▼ // app\Services\PaymobPaymentService.php:49
//   "amount_cents" => "17133"
//   "currency" => "EGP"
//   "shipping_data" => array:2 [▶]
//   "api_source" => "INVOICE"
//   "integrations" => array:3 [▶]
// ]
