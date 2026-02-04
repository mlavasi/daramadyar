<?php
include_once __DIR__ . '/include/auth.php';
include_once __DIR__ . '/include/db.php';

if (!$user) { header("Location: login"); exit; }

$type = $_GET['type'] ?? '';
$id   = (int)($_GET['id'] ?? 0);

// دریافت اطلاعات محصول/اشتراک
if ($type === 'subscription') {
    $item = $db->prepare("SELECT * FROM subscriptions WHERE id=?");
} else {
    // فرض بر این است که جدول products دارید
    $item = $db->prepare("SELECT * FROM products WHERE id=?");
}
$item->execute([$id]);
$product = $item->fetch();

if (!$product) die("محصول یافت نشد");

$price = (int)$product['price'];
$final_price = $price;
$discount_amount = 0;
$coupon_msg = "";
$coupon_code = "";

// --- بررسی کد تخفیف ---
if (isset($_POST['check_coupon'])) {
    $coupon_code = trim($_POST['coupon_code']);
    
    // ۱. بررسی وجود کد
    $stmt = $db->prepare("SELECT * FROM coupons WHERE code=? AND is_active=1 AND expire_date > NOW()");
    $stmt->execute([$coupon_code]);
    $coupon = $stmt->fetch();

    if ($coupon) {
        // ۲. بررسی استفاده قبلی کاربر
        $checkUsage = $db->prepare("SELECT id FROM coupon_usage WHERE user_id=? AND coupon_id=?");
        $checkUsage->execute([$user['id'], $coupon['id']]);
        
        if ($checkUsage->rowCount() > 0) {
            $coupon_msg = "<span style='color:red'>شما قبلاً از این کد استفاده کرده‌اید.</span>";
        } else {
            // اعمال تخفیف
            $discount_amount = ($price * $coupon['percent']) / 100;
            $final_price = $price - $discount_amount;
            $coupon_msg = "<span style='color:green'>کد تخفیف اعمال شد ({$coupon['percent']}٪)</span>";
        }
    } else {
        $coupon_msg = "<span style='color:red'>کد تخفیف نامعتبر یا منقضی شده است.</span>";
    }
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>فاکتور پرداخت</title>
    <link rel="stylesheet" href="fonts/vazirmatn.css">
    <link rel="stylesheet" href="style/dashboard.css">
    <style>
        .invoice-card { background: white; max-width: 500px; margin: 50px auto; padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .row { display: flex; justify-content: space-between; margin-bottom: 15px; border-bottom: 1px dashed #eee; padding-bottom: 15px; }
        .total { font-weight: 900; color: #0ea5e9; font-size: 18px; border-top: 2px solid #eee; padding-top: 15px; border-bottom: none; }
        .coupon-box { display: flex; gap: 10px; margin: 20px 0; }
        .coupon-input { flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 8px; }
        .btn-apply { background: #64748b; color: white; border: none; padding: 0 15px; border-radius: 8px; cursor: pointer; }
        .btn-pay { width: 100%; background: #0ea5e9; color: white; padding: 15px; border: none; border-radius: 12px; font-size: 16px; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>

<div class="invoice-card">
    <h2 style="text-align:center; margin-bottom:30px;">پیش‌فاکتور خرید</h2>
    
    <div class="row">
        <span>محصول:</span>
        <b><?= htmlspecialchars($product['title'] ?? 'محصول') ?></b>
    </div>
    <div class="row">
        <span>قیمت اصلی:</span>
        <span><?= number_format($price/10) ?> تومان</span>
    </div>
    
    <?php if($discount_amount > 0): ?>
    <div class="row" style="color: #ef4444;">
        <span>تخفیف:</span>
        <span><?= number_format($discount_amount/10) ?>- تومان</span>
    </div>
    <?php endif; ?>

    <div class="row total">
        <span>مبلغ قابل پرداخت:</span>
        <span><?= number_format($final_price/10) ?> تومان</span>
    </div>

    <form method="post">
        <div class="coupon-box">
            <input type="text" name="coupon_code" class="coupon-input" placeholder="کد تخفیف دارید؟" value="<?= htmlspecialchars($coupon_code) ?>">
            <button type="submit" name="check_coupon" class="btn-apply">بررسی</button>
        </div>
        <div style="font-size: 13px; margin-bottom: 20px; text-align: center;"><?= $coupon_msg ?></div>
    </form>

    <form action="pay" method="POST">
        <input type="hidden" name="type" value="<?= $type ?>">
        <input type="hidden" name="id" value="<?= $id ?>">
        <?php if($discount_amount > 0): ?>
            <input type="hidden" name="valid_coupon" value="<?= htmlspecialchars($coupon_code) ?>">
        <?php endif; ?>
        
        <button type="submit" class="btn-pay">پرداخت آنلاین</button>
    </form>
</div>

</body>
</html>