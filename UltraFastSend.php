<?php

class SmsIR_UltraFastSend
{

public function UltraFastSend($mobile,$code){

    $apiKey = "TDqJS563mQU86GePfECgPygKD7TqarpeLG5JWbgaZjVYPtBG2YIKtC9VDMikkWKK";
    $url = "https://api.sms.ir/v1/send/verify";

    $data = [
        "mobile" => $mobile,
        "templateId" => 445026,
        "parameters" => [
            ["name"=>"VERIFICATIONCODE","value"=>$code]
        ]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Accept: application/json",
        "x-api-key: $apiKey"
    ]);
    curl_setopt($ch, CURLOPT_POST,true);
    curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_exec($ch);
    curl_close($ch);
}

}
?>
