<?php
if (!isset($_GET['mobile'])) {
    header("Location: login");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تأیید شماره موبایل</title>
<!--     
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> -->

    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="fonts/vazirmatn.css">
    <link rel="stylesheet" href="style/auth.css?v=<?php echo filemtime(__DIR__ . '/style/auth.css'); ?>">
</head>
<body>

<div class="auth-container">
    <div class="auth-icon" style="background: #e0f2fe; color: #0ea5e9;">
        <i class="fa-solid fa-envelope-open-text"></i>
    </div>
    <h2>تایید حساب</h2>
    <p class="subtitle">کد ارسال شده به <?= htmlspecialchars($_GET['mobile']) ?> را وارد کنید</p>

    <form method="post" action="check_code">
        <input type="hidden" name="mobile" value="<?= htmlspecialchars($_GET['mobile']) ?>">
        
        <div class="input-group">
            <input type="text" name="code" placeholder="کد تایید (مثلا: 12345)" required autocomplete="off" style="text-align: center; letter-spacing: 5px; font-weight: bold;">
            <i class="fa-solid fa-key"></i>
        </div>
        
        <button type="submit">ورود به پنل</button>
    </form>
</div>

</body>
</html>