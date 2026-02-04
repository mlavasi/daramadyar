<?php
// نام فایل: proxy_standard.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// جلوگیری از نمایش خطاهای PHP در خروجی
error_reporting(0);
ini_set('display_errors', 0);

$url = 'https://lavasi.ir/rules/get_standard.php';
$password = '7B01jdsf#5';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
$fields = ['password' => $password];
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');

$result = curl_exec($ch);

if ($result === false) {
    echo json_encode(["error" => "خطای اتصال CURL: " . curl_error($ch)]);
    exit;
}

curl_close($ch);

// --- پاکسازی هوشمند خروجی ---
// تلاش برای پیدا کردن JSON بین آکولادها {}
$firstBracket = strpos($result, '{');
$lastBracket = strrpos($result, '}');

if ($firstBracket !== false && $lastBracket !== false) {
    // استخراج فقط قسمت JSON
    $cleanJson = substr($result, $firstBracket, ($lastBracket - $firstBracket) + 1);
    echo $cleanJson;
} else {
    // اگر اصلا شبیه JSON نبود
    echo json_encode(["error" => "خروجی نامعتبر از سرور", "raw" => substr($result, 0, 100)]);
}
?>