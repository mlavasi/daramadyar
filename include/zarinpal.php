<?php
// include/zarinpal.php

define('ZARINPAL_MERCHANT_ID', 'dd9f67cb-74be-4c5f-8718-4d5227f7c28c');
define('ZARINPAL_API_REQUEST', 'https://payment.zarinpal.com/pg/v4/payment/request.json');
define('ZARINPAL_API_VERIFY',  'https://payment.zarinpal.com/pg/v4/payment/verify.json');
define('ZARINPAL_STARTPAY',    'https://payment.zarinpal.com/pg/StartPay/');

function zarinpal_request($amount, $description, $callback)
{
    $data = [
        "merchant_id"  => ZARINPAL_MERCHANT_ID,
        "amount"       => (int)$amount,
        "currency"     => "IRR",
        "callback_url" => $callback,
        "description"  => $description,
    ];

    $ch = curl_init(ZARINPAL_API_REQUEST);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json'
        ],
        CURLOPT_POSTFIELDS => json_encode($data),
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    if (isset($result['data']) && $result['data']['code'] == 100) {
        return [
            'authority' => $result['data']['authority']
        ];
    }

    return false;
}

function zarinpal_verify($amount, $authority)
{
    $data = [
        "merchant_id" => ZARINPAL_MERCHANT_ID,
        "amount"      => (int)$amount,
        "authority"   => $authority,
    ];

    $ch = curl_init(ZARINPAL_API_VERIFY);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json'
        ],
        CURLOPT_POSTFIELDS => json_encode($data),
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

