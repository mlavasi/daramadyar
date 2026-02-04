<?php

// به مرورگر می‌گوید: فایل را ذخیره کن، اما هر بار که خواستی نمایش دهی
// حتماً اول از سرور بپرس که آیا تغییر کرده یا نه.
header("Cache-Control: no-cache, must-revalidate");

// تاریخ انقضا را در گذشته می‌گذاریم تا مرورگر بداند این فایل "تازه" نیست
// و باید حتما چک شود.
header("Expires: 0");


session_start();
// استفاده از مسیرهای ثابت یا چک کردن وجود فایل برای جلوگیری از خطا
include_once __DIR__ . "/config.php";
include_once __DIR__ . "/db.php";

date_default_timezone_set('Asia/Tehran');

$user = null;
$is_premium = false; // متغیر نهایی برای دسترسی کل سایت

/* ۱. احراز هویت */
if (isset($_SESSION['user_id'])) {
    $stmt = $db->prepare("SELECT * FROM users WHERE id=?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
} elseif (isset($_COOKIE['remember_token'])) {
    $stmt = $db->prepare("SELECT * FROM users WHERE remember_token=?");
    $stmt->execute([$_COOKIE['remember_token']]);
    $user = $stmt->fetch();
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
    }
}

/* ۲. بررسی سطح دسترسی (هدیه ۳۰ روزه یا اشتراک پولی) */
if ($user) {
    // الف) بررسی هدیه ۳۰ روزه
    $registerDate = new DateTime($user['created_at']);
    $now = new DateTime();
    $daysPassed = $registerDate->diff($now)->days;

    if ($daysPassed <= 30) {
        $is_premium = true;
    }

    // ب) بررسی اشتراک خریداری شده (اگر هدیه تمام شده بود)
    if (!$is_premium && $user['subscription_expire']) {
        if (strtotime($user['subscription_expire']) > time()) {
            $is_premium = true;
        }
    }
}