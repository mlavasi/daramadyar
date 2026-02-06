<?php
include_once __DIR__ . '/include/auth.php';
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>درآمد یار - سامانه‌ها</title>
    
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="fonts/Vazirmatn-font-face.css">
    <link rel="stylesheet" href="style/systems.css">
    <?php include 'include/analytics.php'; ?>
</head>
<body>

    <button class="mobile-menu-btn" onclick="toggleMenu()"><i class="fa-solid fa-bars"></i></button>
    <div class="overlay" id="mobileOverlay" onclick="closeMenu()"></div>

    <?php include __DIR__ . '/include/sidebar.php'; ?>

    <main class="main-content">
        <div class="page-header">
            <a href="index" class="back-btn"><i class="fa-solid fa-arrow-right"></i></a>
            <h1 class="page-title">سامانه‌ها</h1>
        </div>

        <div class="systems-grid">
            
            <a href="https://ep.tamin.ir" class="system-card" target="_blank">
                <div class="card-icon"><i class="fa-solid fa-file-prescription"></i></div>
                <div class="card-info">
                    <div class="card-title">نسخه نویسی بیمه تامین اجتماعی</div>
                    <div class="card-subtitle">پرتال الکترونیک پزشکان (EP)</div>
                </div>
                <i class="fa-solid fa-arrow-up-right-from-square external-link-icon"></i>
            </a>

            <a href="https://eservices.ihio.gov.ir/ihioerx/" class="system-card" target="_blank">
                <div class="card-icon"><i class="fa-solid fa-heart-pulse"></i></div>
                <div class="card-info">
                    <div class="card-title">نسخه نویسی بیمه سلامت</div>
                    <div class="card-subtitle">سامانه نسخه الکترونیک سلامت</div>
                </div>
                <i class="fa-solid fa-arrow-up-right-from-square external-link-icon"></i>
            </a>

            <a href="https://esakhad.esata.ir:9092/authentication/login" class="system-card" target="_blank">
                <div class="card-icon"><i class="fa-solid fa-shield-halved"></i></div>
                <div class="card-info">
                    <div class="card-title">نسخه نویسی بیمه نیروهای مسلح</div>
                    <div class="card-subtitle">خدمات درمانی نیروهای مسلح (ساخد)</div>
                </div>
                <i class="fa-solid fa-arrow-up-right-from-square external-link-icon"></i>
            </a>

            <a href="https://darman.tamin.ir/Forms/Public/Druglist.aspx?pagename=hdpDrugList" class="system-card" target="_blank">
                <div class="card-icon"><i class="fa-solid fa-pills"></i></div>
                <div class="card-info">
                    <div class="card-title">فارماکوپه دارویی تامین</div>
                    <div class="card-subtitle">فهرست تعهدات دارویی تامین اجتماعی</div>
                </div>
                <i class="fa-solid fa-arrow-up-right-from-square external-link-icon"></i>
            </a>

            <a href="https://mdp.ihio.gov.ir/" class="system-card" target="_blank">
                <div class="card-icon"><i class="fa-solid fa-tablets"></i></div>
                <div class="card-info">
                    <div class="card-title">فارماکوپه دارویی سلامت</div>
                    <div class="card-subtitle">تعهدات دارویی بیمه سلامت ایران</div>
                </div>
                <i class="fa-solid fa-arrow-up-right-from-square external-link-icon"></i>
            </a>

            <a href="https://esata.ir/node/206929" class="system-card" target="_blank">
                <div class="card-icon"><i class="fa-solid fa-prescription-bottle-medical"></i></div>
                <div class="card-info">
                    <div class="card-title">فارماکوپه دارویی نیرومسلح</div>
                    <div class="card-subtitle">لیست دارویی نیروهای مسلح</div>
                </div>
                <i class="fa-solid fa-arrow-up-right-from-square external-link-icon"></i>
            </a>

            <a href="https://iehr.ihio.gov.ir/rme/unsecured/equipmentForm" class="system-card" target="_blank">
                <div class="card-icon"><i class="fa-solid fa-stethoscope"></i></div>
                <div class="card-info">
                    <div class="card-title">تجهیزات پزشکی بیمه سلامت</div>
                    <div class="card-subtitle">استعلام قیمت و تعهدات تجهیزات</div>
                </div>
                <i class="fa-solid fa-arrow-up-right-from-square external-link-icon"></i>
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