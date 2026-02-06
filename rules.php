<?php
include_once __DIR__ . '/include/auth.php';
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>درآمد یار - قوانین و منابع</title>
    
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="fonts/Vazirmatn-font-face.css">
    <link rel="stylesheet" href="style/rules.css?v=<?php echo filemtime(__DIR__ . '/style/rules.css'); ?>">
    <?php include 'include/analytics.php'; ?>
</head>
<body>

    <button class="mobile-menu-btn" onclick="toggleMenu()"><i class="fa-solid fa-bars"></i></button>
    <div class="overlay" id="mobileOverlay" onclick="closeMenu()"></div>

    <?php include __DIR__ . '/include/sidebar.php'; ?>

    <main class="main-content">
        
        <div class="page-header">
            <div class="header-title">قوانین و منابع</div>
        </div>

        <div class="law-grid">
            
            <a href="circulars" class="law-card blue">
                <div class="card-header">
                    <div class="cat-icon-box icon-blue"><i class="fa-solid fa-file-contract"></i></div>
                    <div class="arrow-circle"><i class="fa-solid fa-arrow-left"></i></div>
                </div>
                <h4 class="cat-title">بخش‌نامه‌ها و ابلاغیه‌ها</h4>
                <p class="cat-desc">دسترسی به آخرین بخشنامه‌های وزارت بهداشت و سازمان‌های بیمه‌گر</p>
                <div class="cat-footer">
                    <i class="fa-solid fa-folder-open" style="color: #cbd5e1;"></i>
                </div>
            </a>

            <a href="standards" class="law-card green">
                <div class="card-header">
                    <div class="cat-icon-box icon-green"><i class="fa-solid fa-list-check"></i></div>
                    <div class="arrow-circle"><i class="fa-solid fa-arrow-left"></i></div>
                </div>
                <h4 class="cat-title">استاندارد خدمات درمانی</h4>
                <p class="cat-desc">استانداردهای اعتباربخشی و راهنماهای بالینی بیمارستانی</p>
                <div class="cat-footer">
                    <i class="fa-solid fa-folder-open" style="color: #cbd5e1;"></i>
                </div>
            </a>

            <!-- pharmacopoeia.html -->
            <a href="#" class="law-card purple">
                <div class="card-header">
                    <div class="cat-icon-box icon-purple"><i class="fa-solid fa-book-medical"></i></div>
                    <div class="arrow-circle"><i class="fa-solid fa-arrow-left"></i></div>
                </div>
                <h4 class="cat-title">راهنمای تجویز دارو (فارماکوپه)</h4>
                <p class="cat-desc">لیست دارویی رسمی و تعهدات بیمه‌ها (تامین، سلامت، نیرو مسلح)</p>
                <div class="cat-footer">
                    <i class="fa-solid fa-folder-open" style="color: #cbd5e1;"></i>
                </div>
            </a>
<!-- council_approvals.html -->
            <a href="#" class="law-card orange">
                <div class="card-header">
                    <div class="cat-icon-box icon-orange"><i class="fa-solid fa-gavel"></i></div>
                    <div class="arrow-circle"><i class="fa-solid fa-arrow-left"></i></div>
                </div>
                <h4 class="cat-title">مصوبات شورای عالی بیمه</h4>
                <p class="cat-desc">آخرین تغییرات تعرفه‌ها و مصوبات قانونی هیئت وزیران</p>
                <div class="cat-footer">
                    <i class="fa-solid fa-folder-open" style="color: #cbd5e1;"></i>
                </div>
            </a>

        </div>
    </main>

    <script>
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');
        
        function toggleMenu() { sidebar.classList.toggle('active'); mobileOverlay.classList.toggle('active'); }
        function closeMenu() { sidebar.classList.remove('active'); mobileOverlay.classList.remove('active'); }
    </script>

</body>
</html>