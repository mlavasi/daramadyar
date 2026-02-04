<?php
session_start();
include_once __DIR__ . '/include/config.php';
include_once __DIR__ . '/include/db.php';

date_default_timezone_set('Asia/Tehran');

$mobile = $_POST['mobile'];
$code   = $_POST['code'];

$stmt = $db->prepare("SELECT id FROM users WHERE mobile=? AND otp_code=? AND otp_expire > NOW()");
$stmt->execute([$mobile,$code]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    // ساخت توکن
    $token = bin2hex(random_bytes(32));

    // ذخیره در دیتابیس
    $db->prepare("UPDATE users SET remember_token=?, last_login=NOW() WHERE id=?")
       ->execute([$token, $user['id']]);

       // ذخیره در کوکی (۳۰ روز)
    setcookie("remember_token", $token, time()+60*60*24*30, "/", "", false, true);

    $_SESSION['user_id'] = $user['id'];
    session_regenerate_id(true);
    header("Location: index");
    exit;
} else {
    echo "کد نامعتبر یا منقضی شده";
}
