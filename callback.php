<?php
// callback.php
include_once __DIR__ . '/include/config.php';
include_once __DIR__ . '/include/db.php';
include_once __DIR__ . '/include/zarinpal.php';

$order_id  = (int)($_GET['order'] ?? 0);
$authority = $_GET['Authority'] ?? '';
$status    = $_GET['Status'] ?? '';

// دریافت اطلاعات سفارش
$stmt = $db->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

// اگر سفارش پیدا نشد
if (!$order) {
    header("Location: payment-result?status=error");
    exit;
}

// اگر پرداخت توسط کاربر لغو شده یا ناموفق بوده
if ($status !== 'OK') {
    $db->prepare("UPDATE orders SET status='failed' WHERE id=?")->execute([$order_id]);
    header("Location: payment-result?status=failed&msg=پرداخت لغو شد");
    exit;
}

// تأیید پرداخت با زرین‌پال
$verify = zarinpal_verify($order['final_price'], $authority);
$code = $verify['data']['code'] ?? null;

// کدهای ۱۰۰ و ۱۰۱ به معنی پرداخت موفق هستند
if (in_array($code, [100, 101])) {

    // ۱. تغییر وضعیت سفارش به پرداخت شده
    $db->prepare("UPDATE orders SET status='paid' WHERE id=?")->execute([$order_id]);

    // ۲. سوزاندن کد تخفیف (اگر استفاده شده باشد)
    if (!empty($order['coupon_id'])) {
        $stmtCoupon = $db->prepare("INSERT IGNORE INTO coupon_usage (user_id, coupon_id, order_id) VALUES (?,?,?)");
        $stmtCoupon->execute([$order['user_id'], $order['coupon_id'], $order['id']]);
    }

    // ۳. تحویل محصول یا اشتراک
    if ($order['item_type'] === 'subscription') {

        // الف) دریافت مدت زمان اشتراک خریداری شده
        $stmtSub = $db->prepare("SELECT duration_days FROM subscriptions WHERE id = ?");
        $stmtSub->execute([$order['item_id']]);
        $sub = $stmtSub->fetch();

        // ب) دریافت اطلاعات فعلی کاربر از دیتابیس (چون متغیر $user اینجا وجود ندارد)
        $stmtUser = $db->prepare("SELECT subscription_expire FROM users WHERE id = ?");
        $stmtUser->execute([$order['user_id']]);
        $currentUser = $stmtUser->fetch();

        // ج) محاسبه تاریخ انقضای جدید
        // اگر اشتراک فعال دارد، به تاریخ قبلی اضافه کن. اگر ندارد، از همین لحظه شروع کن.
        $baseDate = ($currentUser['subscription_expire'] && strtotime($currentUser['subscription_expire']) > time()) 
                    ? new DateTime($currentUser['subscription_expire']) 
                    : new DateTime();
        
        $baseDate->modify("+{$sub['duration_days']} days");

        // د) آپدیت کاربر
        $db->prepare("UPDATE users SET subscription_expire=? WHERE id=?")
           ->execute([$baseDate->format('Y-m-d H:i:s'), $order['user_id']]);

        $msg = "اشتراک شما با موفقیت فعال شد";

    } else {
        // اگر محصول تکی بود
        $db->prepare("INSERT IGNORE INTO user_products (user_id, product_id) VALUES (?,?)")
           ->execute([$order['user_id'], $order['item_id']]);

        $msg = "محصول با موفقیت فعال شد";
    }

    // ۴. هدایت به صفحه نتیجه (لینک تمیز)
    header("Location: payment-result?status=success&msg=" . urlencode($msg));
    exit;

} else {
    // پرداخت تایید نشد
    $db->prepare("UPDATE orders SET status='failed' WHERE id=?")->execute([$order_id]);
    header("Location: payment-result?status=failed&msg=پرداخت تأیید نشد");
    exit;
}
?>