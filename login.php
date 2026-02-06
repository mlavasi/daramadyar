<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود به حساب کاربری</title>
    
    <!-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> -->


    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="fonts/vazirmatn.css">
    <link rel="stylesheet" href="style/auth.css?v=<?php echo filemtime(__DIR__ . '/style/auth.css'); ?>">
    <?php include 'include/analytics.php'; ?>
</head>
<body>

<div class="auth-container">
    <div class="auth-icon">
        <i class="fa-solid fa-user-lock"></i>
    </div>
    <h2>خوش آمدید!</h2>
    <p class="subtitle">برای ورود یا ثبت‌نام شماره موبایل خود را وارد کنید</p>

    <form method="post" action="send_code">
        <div class="input-group">
            <input type="tel" name="mobile" placeholder="شماره موبایل (مثلا: 09123456789)" required autofocus pattern="09[0-9]{9}" maxlength="11">
            <i class="fa-solid fa-mobile-screen"></i>
        </div>
        <button type="submit">
            دریافت کد ورود <i class="fa-solid fa-arrow-left" style="margin-right: 5px;"></i>
        </button>
    </form>
</div>

</body>
</html>