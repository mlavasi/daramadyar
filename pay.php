<?php
include_once __DIR__ . '/include/auth.php';
include_once __DIR__ . '/include/db.php';
include_once __DIR__ . '/include/zarinpal.php';

if (!$user) die("ابتدا وارد شوید");

// دریافت اطلاعات از متد POST
$type = $_POST['type'] ?? '';
$id   = (int)($_POST['id'] ?? 0);
$coupon_code = $_POST['valid_coupon'] ?? '';

// دریافت قیمت پایه
if ($type === 'subscription') {
    $item = $db->query("SELECT * FROM subscriptions WHERE id=$id")->fetch();
} else {
    // فرض بر محصول
    $item = $db->query("SELECT * FROM products WHERE id=$id")->fetch();
}

if (!$item) die("آیتم نامعتبر");

$price = (int)$item['price'];
$discount = 0;
$coupon_id = null;
$final_price = $price;

// --- اعتبارسنجی مجدد کد تخفیف ---
if (!empty($coupon_code)) {
    $stmt = $db->prepare("SELECT * FROM coupons WHERE code=? AND is_active=1 AND expire_date > NOW()");
    $stmt->execute([$coupon_code]);
    $coupon = $stmt->fetch();

    if ($coupon) {
        // چک کردن استفاده قبلی
        $checkUsage = $db->prepare("SELECT id FROM coupon_usage WHERE user_id=? AND coupon_id=?");
        $checkUsage->execute([$user['id'], $coupon['id']]);

        if ($checkUsage->rowCount() == 0) {
            $discount = ($price * $coupon['percent']) / 100;
            $coupon_id = $coupon['id'];
            $final_price = $price - $discount;
        }
    }
}

// جلوگیری از منفی شدن
if ($final_price < 0) $final_price = 0;


// ======================================================
//  حالت اول: پرداخت رایگان (تخفیف ۱۰۰٪)
// ======================================================
if ($final_price == 0) {

    // ۱. ثبت سفارش با وضعیت پرداخت شده (paid)
    $stmt = $db->prepare("
        INSERT INTO orders (user_id, item_type, item_id, price, discount, final_price, coupon_id, status)
        VALUES (?,?,?,?,?,?,?,?)
    ");
    $stmt->execute([
        $user['id'], $type, $id, $price, $discount, 0, $coupon_id, 'paid'
    ]);
    
    $order_id = $db->lastInsertId();

    // ۲. ثبت استفاده از کد تخفیف (سوزاندن کد)
    if ($coupon_id) {
        $db->prepare("INSERT INTO coupon_usage (user_id, coupon_id, order_id) VALUES (?,?,?)")
           ->execute([$user['id'], $coupon_id, $order_id]);
    }

    // ۳. فعال‌سازی آنی محصول یا اشتراک
    if ($type === 'subscription') {
        // دریافت روزهای اشتراک
        $stmtSub = $db->prepare("SELECT duration_days FROM subscriptions WHERE id = ?");
        $stmtSub->execute([$id]);
        $sub = $stmtSub->fetch();

        // محاسبه تاریخ انقضا (دقیقاً مثل callback)
        $currentExpire = ($user['subscription_expire'] && strtotime($user['subscription_expire']) > time()) 
                         ? new DateTime($user['subscription_expire']) 
                         : new DateTime();
        
        $currentExpire->modify("+{$sub['duration_days']} days");

        $db->prepare("UPDATE users SET subscription_expire=? WHERE id=?")
           ->execute([$currentExpire->format('Y-m-d H:i:s'), $user['id']]);

        $msg = "کد تخفیف ۱۰۰٪ اعمال شد و اشتراک شما فعال گردید.";

    } else {
        // فعال‌سازی محصول
        $db->prepare("INSERT IGNORE INTO user_products (user_id, product_id) VALUES (?,?)")
           ->execute([$user['id'], $id]);

        $msg = "محصول با موفقیت فعال شد.";
    }

    // ۴. هدایت به صفحه نتیجه موفقیت
    header("Location: payment-result?status=success&msg=" . urlencode($msg));
    exit;
}


// ======================================================
//  حالت دوم: پرداخت مبلغ‌دار (اتصال به بانک)
// ======================================================

// اگر مبلغ خیلی کم شد (مثلاً ۵۰ تومن)، بانک قبول نمی‌کند. حداقل ۱۰۰ تومن.
//if ($final_price < 100) $final_price = 100;

$stmt = $db->prepare("
    INSERT INTO orders (user_id, item_type, item_id, price, discount, final_price, coupon_id, status)
    VALUES (?,?,?,?,?,?,?,?)
");
$stmt->execute([
    $user['id'], $type, $id, $price, $discount, $final_price, $coupon_id, 'pending'
]);

$order_id = $db->lastInsertId();

// درخواست به زرین‌پال
$callback = "https://daramadyar.com/callback?order=".$order_id;
$result = zarinpal_request($final_price, "خرید سفارش $order_id", $callback);

if (!$result) {
    die("خطا در اتصال به درگاه پرداخت");
}

$db->prepare("UPDATE orders SET authority=? WHERE id=?")
   ->execute([$result['authority'], $order_id]);

header("Location: " . ZARINPAL_STARTPAY . $result['authority']);
exit;
?>