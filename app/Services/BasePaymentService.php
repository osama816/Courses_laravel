<?php

namespace App\Services;

use App\Helper\ApiResponse;
use Exception;
use Illuminate\Support\Facades\Http;

class BasePaymentService
{
    /**
     * Create a new class instance.
     */
    protected  $base_url;
    protected array $header;
    protected function buildRequest($method, $url, $data = null,$type='json'): \Illuminate\Http\JsonResponse
    {
        try {
            //type ? json || form_params
            $response = Http::withHeaders($this->header)->send($method, $this->base_url . $url, [
                $type => $data
            ]);
            return ApiResponse::success($response->json(), 'Request successful', $response->status());

        } catch (Exception $e) {
            return ApiResponse::error('Request failed', ['message' => $e->getMessage()], 500);
        }
    }
}
