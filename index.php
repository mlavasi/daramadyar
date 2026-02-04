<?php
include_once __DIR__ . '/include/auth.php';
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="داشبورد درآمد یار - سامانه مدیریت درآمد سلامت">
    <title>درآمد یار - نسخه تحت وب</title>
	<link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="fonts/vazirmatn.css">
    <link rel="stylesheet" href="style/index.css?v=<?php echo filemtime(__DIR__ . '/style/index.css'); ?>">

   
</head>
<body>

    <button class="mobile-menu-btn" onclick="toggleMenu()"><i class="fa-solid fa-bars"></i></button>
    <div class="overlay" onclick="closeMenu()"></div>

   <?php include __DIR__ . '/include/sidebar.php'; ?>

    <main class="main-content">
        <?php

        if (!$user): ?>
        <div style="
        background:#fff7ed;
        border:1px solid #fed7aa;
        padding:12px;
        border-radius:10px;
        color:#9a3412;
        margin-bottom:8px;
        font-size:14px;
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:10px;
        ">

    <div style="display:flex;align-items:center;gap:8px;">
        <i class="fa-solid fa-circle-info"></i>
        <span>برای دسترسی کامل به امکانات سایت، لطفاً ثبت‌نام یا وارد شوید.</span>
    </div>

    <div class="auth-btn-area">
        <a href="login" class="auth-btn login-btn">
            <i class="fa-solid fa-right-to-bracket"></i>
            ورود / ثبت‌نام
        </a>
    </div>

</div>


<?php endif; ?>

            <a href="https://ihee.ir/webinar/" target="_blank" class="hero-banner">
            <div class="hero-text">
                <h2 id="heroTitle">در حال بارگذاری اخبار...</h2>
            </div>
            
            <div class="hero-image-box">
                <img id="heroImg" src="" alt="تصویر خبر">
                <i id="heroIcon" class="fa-solid fa-newspaper"></i>
            </div>
        </a>

        <div class="top-cards-grid">
            <a href="systems" class="feature-card">
                <div class="icon-circle bg-blue-light"><i class="fa-solid fa-globe"></i></div>
                <div class="card-title">سامانه ها</div>
            </a>
            </div>

        <div>
            <div class="section-title">تعرفه و خدمات ۱۴۰۴</div>
            <div class="services-grid">
                <a href="relative_value" class="service-item"><div class="service-icon"><i class="fa-solid fa-sack-dollar" style="color: #d97706;"></i></div><div class="service-name">ارزش نسبی</div></a>
                <a href="professional_technical" class="service-item"><div class="service-icon"><i class="fa-solid fa-user-doctor" style="color: #475569;"></i></div><div class="service-name">فنی و حرفه‌ای</div></a>
                <a href="adjustment" class="service-item"><div class="service-icon"><i class="fa-solid fa-sliders" style="color: #3b82f6;"></i></div><div class="service-name">تعدیلی</div></a>
                <a href="bed_day" class="service-item"><div class="service-icon"><i class="fa-solid fa-bed-pulse" style="color: #6366f1;"></i></div><div class="service-name">تخت روز</div></a>
                <a href="nursing" class="service-item"><div class="service-icon"><i class="fa-solid fa-user-nurse" style="color: #0d9488;"></i></div><div class="service-name">پرستاری</div></a>
                <a href="global" class="service-item"><div class="service-icon"><i class="fa-solid fa-hands-holding-circle" style="color: #f59e0b;"></i></div><div class="service-name">گلوبال</div></a>
                <a href="dental" class="service-item"><div class="service-icon"><i class="fa-solid fa-tooth" style="color: #60a5fa;"></i></div><div class="service-name">دندانپزشکی</div></a>
                <a href="coding" class="service-item"><div class="service-icon"><i class="fa-solid fa-file-invoice" style="color: #64748b;"></i></div><div class="service-name">کدینگ جامع</div></a>
                <a href="ambulance" class="service-item"><div class="service-icon"><i class="fa-solid fa-truck-medical" style="color: #ef4444;"></i></div><div class="service-name">آمبولانس</div></a>
                <a href="home_care" class="service-item"><div class="service-icon"><i class="fa-solid fa-house-medical" style="color: #10b981;"></i></div><div class="service-name">پرستاری در منزل</div></a>
                <a href="deductions" class="service-item"><div class="service-icon"><i class="fa-solid fa-file-circle-xmark" style="color: #f43f5e;"></i></div><div class="service-name">حذفیات</div></a>
                <a href="franchise" class="service-item"><div class="service-icon"><i class="fa-solid fa-percent" style="color: #8b5cf6;"></i></div><div class="service-name">کاهش فرانشیز</div></a>
            </div>
        </div>

        <div>
            <div class="section-title">سایر خدمات</div>
            <div class="services-grid">
                <a href="#" class="service-item"><div class="service-icon"><i class="fa-solid fa-dna" style="color: #0ea5e9;"></i></div><div class="service-name">ICD-10</div></a>
                <a href="#" class="service-item"><div class="service-icon"><span class="text-icon">CPT</span></div><div class="service-name">CPT</div></a>
                <a href="loinc" class="service-item"><div class="service-icon"><span class="text-icon">LOINC</span></div><div class="service-name">لویینک</div></a>
                <a href="hoteling" class="service-item"><div class="service-icon"><i class="fa-solid fa-hotel" style="color: #6366f1;"></i></div><div class="service-name">اقلام هتلینگ</div></a>
            </div>
        </div>
        
    </main>

    <script>
        // --- Sidebar Logic ---
        const sidebar = document.getElementById('sidebar');
        const overlay = document.querySelector('.overlay');
        function toggleMenu() { sidebar.classList.toggle('active'); overlay.classList.toggle('active'); }
        function closeMenu() { sidebar.classList.remove('active'); overlay.classList.remove('active'); }

        // --- Dynamic Content Logic ---
        async function fetchLatestNews() {
            const heroTitle = document.getElementById('heroTitle');
            const heroImg = document.getElementById('heroImg');
            const heroIcon = document.getElementById('heroIcon');
            const categorySlug = 'شب-های-درآمد'; 
            
            try {
                const catResponse = await fetch(`https://ihee.ir/wp-json/wp/v2/categories?slug=${categorySlug}`);
                const categories = await catResponse.json();
                
                if (categories.length > 0) {
                    const catId = categories[0].id;
                    const postResponse = await fetch(`https://ihee.ir/wp-json/wp/v2/posts?categories=${catId}&per_page=1&_embed`);
                    const posts = await postResponse.json();

                    if (posts.length > 0) {
                        const post = posts[0];
                        heroTitle.innerHTML = post.title.rendered;

                        if (post._embedded && post._embedded['wp:featuredmedia'] && post._embedded['wp:featuredmedia'][0]) {
                            const imageUrl = post._embedded['wp:featuredmedia'][0].source_url;
                            heroImg.src = imageUrl;
                            heroImg.style.display = 'block'; 
                            heroIcon.style.display = 'none'; 
                        }
                    } else {
                        heroTitle.textContent = "خبری در این بخش یافت نشد.";
                    }
                }
            } catch (error) {
                console.error("خطا:", error);
                heroTitle.innerHTML = "جدیدترین وبینارهای شب‌های درآمد (کلیک کنید)";
            }
        }

        document.addEventListener('DOMContentLoaded', fetchLatestNews);
    </script>
</body>
</html>