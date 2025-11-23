<?php

namespace App\Services;

use App\Helper\ApiResponse;
use App\Interfaces\PaymentGatewayInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
class MyFatoorahPaymentService extends BasePaymentService implements PaymentGatewayInterface
{
    /**
     * Create a new class instance.
     */
    protected $api_key;
    public function __construct()
    {
        $this->base_url = env("MYFATOORAH_BASE_URL");
        $this->api_key = env("MYFATOORAH_API_KEY");
        $this->header = [
            'accept' => 'application/json',
            "Content-Type" => "application/json",
            "Authorization" => "Bearer " . $this->api_key,
        ];
    }

    public function sendPayment(Request $request)
    {

        $data = $this->formatData($request);

        $response = $this->buildRequest('POST', '/v2/SendPayment', $data);
        //handel payment response data and return it
         if($response->getData(true)['success']){
         $url=$response->getData(true)['data']['Data']['InvoiceURL'];
            $booking_id=$response->getData(true)['data']['Data']['CustomerReference'];
          // dd(['url' => $url,'booking_id'=>$booking_id]);
            return ApiResponse::success(['url' => $url,'booking_id'=>$booking_id],'send payment successful', 200)->getData(true);
        }
         return ['success' => false,'url'=>route('payment.failed')];
    }

    public function callBack(Request $request)
    {
        $data=[
            'KeyType' => 'paymentId',
            'Key' => $request->input('paymentId'),
        ];
        $response=$this->buildRequest('POST', '/v2/getPaymentStatus', $data);
        $response_data=$response->getData(true);
       // dd($response_data['data']['Data']['InvoiceValue']);
        $payment_method='myfatoorah';
        $amount=$response_data['data']['Data']['InvoiceValue'];
        $booking_id=$response_data['data']['Data']['CustomerReference'];
        $status=$response_data['data']['Data']['InvoiceStatus'];
        $TransactionId=$response_data['data']['Data']['InvoiceTransactions'][0]['TransactionId'];
        Log::channel('payment')->info('myfatoorah_response.json',[
            'myfatoorah_callback_response'=>$request->all(),
            'myfatoorah_response_status'=>$response_data
        ]);

        if($response_data['data']['Data']['InvoiceStatus']==='Paid'){
            $data= [
                'amount'=>$amount,
                'payment_method'=>$payment_method,
                'booking_id'=>$booking_id,
                'status'=>$status,
                'TransactionId'=>$TransactionId,
            ];

            return $data;
        }

        return false ;
    }


        protected function formatData(Request $request): array
    {
        return [
            "InvoiceValue" => $request->get('InvoiceValue'),
            "DisplayCurrencyIso" => $request->get('currency'),
            "NotificationOption" => 'LNK',
            "Language" => 'en',
            "CallBackUrl" => $request->getSchemeAndHttpHost().'/api/payment/callback?gateway_type=myfatoorah',
            "CustomerName" => $request->get('CustomerName'),
            "CustomerEmail" => $request->get('CustomerEmail'),
            "CustomerReference" => $request->get('booking_id'),
        ];
    }

}
