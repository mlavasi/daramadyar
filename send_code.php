<?php
include_once __DIR__ . '/include/config.php';
include_once __DIR__ . '/include/db.php';
include "UltraFastSend.php";

date_default_timezone_set('Asia/Tehran');

$mobile = $_POST['mobile'];

if (!preg_match('/^09\d{9}$/', $mobile)) {
    die("شماره موبایل نامعتبر است");
}

$code = rand(10000,99999);
$expire = date("Y-m-d H:i:s", strtotime("+2 minutes"));

$stmt = $db->prepare("INSERT INTO users (mobile, otp_code, otp_expire)
VALUES (?, ?, ?)
ON DUPLICATE KEY UPDATE otp_code=?, otp_expire=?");

$stmt->execute([$mobile,$code,$expire,$code,$expire]);

// ارسال پیامک
$SmsIR_UltraFastSend = new SmsIR_UltraFastSend();
$UltraFastSend = $SmsIR_UltraFastSend->UltraFastSend($mobile,$code);


header("Location: verify?mobile=".$mobile);
