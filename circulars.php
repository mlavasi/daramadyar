<?php
include_once __DIR__ . '/include/auth.php';
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>درآمد یار - مصوبات شورای عالی بیمه</title>
    
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">

    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="fonts/Vazirmatn-font-face.css">
     <link rel="stylesheet" href="style/circulars.css?v=<?php echo filemtime(__DIR__ . '/style/circulars.css'); ?>">

  <?php include 'include/analytics.php'; ?>
</head>
<body>

    <button class="mobile-menu-btn" onclick="toggleMenu()"><i class="fa-solid fa-bars"></i></button>
    <div class="overlay" id="mobileOverlay" onclick="closeMenu()"></div>

    <?php include __DIR__ . '/include/sidebar.php'; ?>

    <main class="main-content">
        <div class="page-header">
            <a href="rules" class="back-btn"><i class="fa-solid fa-arrow-right"></i></a>
            <div class="header-title">مصوبات شورای عالی بیمه</div>
        </div>

        <div class="search-container">
            <div class="input-wrapper">
                <i class="fa-solid fa-magnifying-glass search-icon-inside"></i>
                <input type="text" id="searchInput" class="search-input" placeholder="جستجو در مصوبات...">
            </div>
            <button class="search-btn-action" onclick="performSearch()">
                جستجو
            </button>
        </div>

        <div class="docs-grid" id="docsContainer">
            <div style="text-align:center; padding:40px; color:#64748b; grid-column:1/-1;">
                <i class="fa-solid fa-circle-notch fa-spin fa-2x"></i><br><br>
                در حال دریافت اطلاعات مصوبات...
            </div>
        </div>
    </main>

    <script>
        // --- UI Logic ---
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');
        function toggleMenu() { sidebar.classList.toggle('active'); mobileOverlay.classList.toggle('active'); }
        function closeMenu() { sidebar.classList.remove('active'); mobileOverlay.classList.remove('active'); }

        // --- Data Logic ---
        const API_URL = 'proxy_shora.php'; // فایل واسط جدید
        let allData = [];

        async function fetchShoraData() {
            const container = document.getElementById('docsContainer');
            try {
                const response = await fetch(API_URL);
                const rawText = await response.text();
                
                let result;
                try {
                    result = JSON.parse(rawText);
                } catch (e) {
                    const jsonMatch = rawText.match(/\{.*\}/s);
                    if(jsonMatch) result = JSON.parse(jsonMatch[0]);
                    else throw new Error("خروجی معتبر نیست");
                }

                if (result.success && Array.isArray(result.data)) {
                    allData = result.data;
                    renderData(allData);
                } else if (result.error) {
                    throw new Error(result.error);
                } else {
                    throw new Error("فرمت داده صحیح نیست");
                }

            } catch (error) {
                console.error(error);
                container.innerHTML = `
                    <div style="text-align:center; color:#ef4444; grid-column:1/-1; padding:30px;">
                        <i class="fa-solid fa-triangle-exclamation fa-2x"></i><br><br>
                        خطا در دریافت اطلاعات.<br>
                        <span style="font-size:12px;">${error.message}</span>
                    </div>`;
            }
        }

        function renderData(data) {
            const container = document.getElementById('docsContainer');
            container.innerHTML = '';

            if (data.length === 0) {
                container.innerHTML = '<div style="text-align:center; padding:40px; color:#64748b; grid-column:1/-1;">موردی یافت نشد.</div>';
                return;
            }

            data.forEach(item => {
                // ساخت دکمه‌های دانلود با نام‌گذاری اصلاح شده
                let downloadsHtml = '';
                if (item.addressA && item.addressA.length > 5) {
                    downloadsHtml += `<a href="${item.addressA}" class="download-btn" target="_blank"><i class="fa-solid fa-file-arrow-down"></i> دانلود فایل اصلی</a>`;
                }
                if (item.addressB && item.addressB.length > 5) {
                    downloadsHtml += `<a href="${item.addressB}" class="download-btn" target="_blank"><i class="fa-solid fa-file-image"></i> دانلود فایل ۱</a>`;
                }
                if (item.addressC && item.addressC.length > 5) {
                    downloadsHtml += `<a href="${item.addressC}" class="download-btn" target="_blank"><i class="fa-solid fa-file-pdf"></i> دانلود فایل ۲</a>`;
                }

                const card = document.createElement('div');
                card.className = 'shora-card';
                card.innerHTML = `
                    <div class="card-header">
                        <span class="shora-meta">سال ${item.year}</span>
                        <div class="shora-date"><i class="fa-regular fa-calendar"></i> ${item.date}</div>
                    </div>
                    <div>
                        <div class="shora-title">${item.title}</div>
                        <div style="font-size:12px; color:#94a3b8; margin-top:5px;">شماره مصوبه: ${item.num}</div>
                    </div>
                    <div class="card-footer">
                        ${downloadsHtml || '<span style="font-size:12px; color:#94a3b8; text-align:center;">فایلی موجود نیست</span>'}
                    </div>
                `;
                container.appendChild(card);
            });
        }

        function performSearch() {
            const term = document.getElementById('searchInput').value.toLowerCase().trim();
            const filtered = allData.filter(item => item.title.includes(term) || item.num.includes(term));
            renderData(filtered);
        }

        document.getElementById('searchInput').addEventListener('input', performSearch);
        document.addEventListener('DOMContentLoaded', fetchShoraData);

    </script>
</body>
</html>