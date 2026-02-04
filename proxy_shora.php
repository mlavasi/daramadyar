<?php
// نام فایل: proxy_shora.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// آدرس جدید API که فرستادید
$url = 'https://lavasi.ir/rules/get_shora_rvu.php';
$password = '7B01jdsf#5';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
// ارسال پسورد به صورت Multipart Form Data
$fields = ['password' => $password];
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');

$result = curl_exec($ch);

if ($result === false) {
    echo json_encode(["error" => "خطای CURL: " . curl_error($ch)]);
} else {
    echo $result;
}
curl_close($ch);
?>