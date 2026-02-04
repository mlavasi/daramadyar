<?php
// payment-result.php
$status  = $_GET['status'] ?? 'error'; // success | failed | error
$message = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>نتیجه پرداخت</title>
    <link rel="stylesheet" href="../fonts/vazirmatn.css">
    <link rel="stylesheet" href="style/payment.css?v=<?php echo filemtime(__DIR__ . '/style/payment.css'); ?>">
    <link rel="stylesheet" href="../fontawesome/css/all.min.css">
</head>
<body>

<div class="result-wrapper">

    <?php if ($status === 'success'): ?>
        <div class="result-card success">
            <div class="icon">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <h1>پرداخت با موفقیت انجام شد 🎉</h1>
            <p><?= htmlspecialchars($message) ?></p>

            <a href="../index" class="btn primary">بازگشت به سایت</a>
        </div>

    <?php elseif ($status === 'failed'): ?>
        <div class="result-card failed">
            <div class="icon">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
            <h1>پرداخت ناموفق بود</h1>
            <p><?= htmlspecialchars($message) ?></p>

            <a href="../index" class="btn">بازگشت</a>
        </div>

    <?php else: ?>
        <div class="result-card error">
            <div class="icon">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h1>خطای سیستمی</h1>
            <p>لطفاً در صورت کسر وجه با پشتیبانی تماس بگیرید.</p>

            <a href="../index" class="btn">بازگشت</a>
        </div>
    <?php endif; ?>

</div>

</body>
</html>
