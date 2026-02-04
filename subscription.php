<?php
include_once __DIR__ . '/include/auth.php';
include_once __DIR__ . '/include/db.php';



$stmt = $db->prepare("SELECT * FROM subscriptions WHERE is_active = 1 ORDER BY duration_days ASC");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC); // استفاده از fetchAll برای گرفتن همه ردیف‌ها

$subscriptions = [];
if ($result) {
    foreach ($result as $row) {
    $subscriptions[] = $row;
}
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>درآمد یار - خرید اشتراک</title>
    
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="fonts/vazirmatn.css">
    
    <link rel="stylesheet" href="style/dashboard.css"> 
    <link rel="stylesheet" href="style/shop.css"> </head>
<body>

    <button class="mobile-menu-btn" onclick="toggleMenu()"><i class="fa-solid fa-bars"></i></button>
    <div class="overlay" id="mobileOverlay" onclick="closeMenu()"></div>

    <?php include __DIR__ . '/include/sidebar.php'; ?>

    <main class="main-content">
        
        <div class="page-header" style="display:flex; align-items:center; gap:15px; margin-bottom:10px;">
            <a href="dashboard" class="back-btn" style="width:45px; height:45px; border-radius:14px; background:white; display:flex; align-items:center; justify-content:center; color:#1e293b; border:1px solid #e2e8f0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.05);">
                <i class="fa-solid fa-arrow-right"></i>
            </a>
            <h1 class="page-title" style="font-size:24px; font-weight:800; color:#1e293b;">انتخاب طرح اشتراک</h1>
        </div>

        <div class="shop-container">

<?php if (empty($subscriptions)): ?>
    <p style="padding:20px; color:#64748b;">در حال حاضر اشتراکی برای فروش وجود ندارد.</p>
<?php else: ?>

<?php foreach ($subscriptions as $sub): ?>
    
    <?php
        // فرمت قیمت
        $price = number_format($sub['price']/10);

        // تعیین آیکن بر اساس مدت
        if ($sub['duration_days'] <= 30) {
            $icon = 'fa-regular fa-clock';
        } elseif ($sub['duration_days'] <= 90) {
            $icon = 'fa-solid fa-gem';
        } else {
            $icon = 'fa-solid fa-crown';
        }
    ?>

    <div class="plan-card">
        <div class="plan-icon-box">
            <i class="<?= $icon ?>"></i>
        </div>

        <h3 class="plan-title">
            <?= htmlspecialchars($sub['title']) ?>
        </h3>

        <div class="plan-price">
            <?= $price ?> <small>تومان</small>
        </div>

        <ul class="features-list">
            <li><i class="fa-solid fa-check"></i> دسترسی کامل به سیستم</li>
            <li><i class="fa-solid fa-check"></i> مدت اعتبار: <?= $sub['duration_days'] ?> روز</li>
        </ul>

        <a href="invoice?type=subscription&id=<?= $sub['id'] ?>" class="btn-select">
            خرید طرح
        </a>
    </div>

<?php endforeach; ?>

<?php endif; ?>

</div>


    </main>

    <script>
        // اسکریپت ساده برای موبایل منو (کپی شده از فایل‌های خودتان)
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');
        function toggleMenu() { sidebar.classList.toggle('active'); mobileOverlay.classList.toggle('active'); }
        function closeMenu() { sidebar.classList.remove('active'); mobileOverlay.classList.remove('active'); }
    </script>

</body>
</html>