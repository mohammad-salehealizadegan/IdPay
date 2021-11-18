<?php

namespace Mdafzaran\Idpay;

use Illuminate\Support\Facades\Http;
    
class IdPayRequest
{
    public function sendRequest($url, $options = [], $headers =[], $verify = true)
    {
        $response = Http::withHeaders([
            'Content-Type' =>$headers['type'],
            'X-API-KEY' => $headers['apiKey'],
            'X-SANDBOX' =>  $headers['sandBox'],
        ])
        ->withOptions(["verify"=>$verify])
        ->post($url, $options);
        
        return $response;
    }
}