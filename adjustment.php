<?php

include_once __DIR__ . '/include/config.php';
include_once __DIR__ . '/include/db.php';
include_once __DIR__ . '/include/auth.php';

if ($is_premium) {
    $query = "SELECT * FROM tadili";
} else {
    $query = "SELECT * FROM tadili LIMIT 5";
}
$items = $db->query($query);
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>درآمد یار - کدهای تعدیلی</title>
    
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">

    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="fonts/Vazirmatn-font-face.css">
    <link rel="stylesheet" href="style/adjustment.css?v=<?php echo filemtime(__DIR__ . '/style/adjustment.css'); ?>">
    <?php include '/include/analytics.php'; ?>
</head>
<body>

    <button class="mobile-menu-btn" onclick="toggleMenu()"><i class="fa-solid fa-bars"></i></button>
    <div class="overlay" id="mobileOverlay" onclick="closeMenu()"></div>

    <?php include __DIR__ . '/include/sidebar.php'; ?>

    <main class="main-content">
        <div class="page-header">
            <a href="index" class="back-btn"><i class="fa-solid fa-arrow-right"></i></a>
            <h1 class="page-title">کدهای تعدیلی ۱۴۰۴</h1>
            
            <div class="search-container">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" id="searchInput" class="search-input" placeholder="جستجو در کد، عنوان یا توضیحات...">
            </div>
        </div>

        <div class="cards-grid" id="cardsContainer">
            <div style="text-align:center; padding:40px; color:#64748b; grid-column:1/-1;">
                <i class="fa-solid fa-circle-notch fa-spin fa-2x"></i><br><br>
                در حال بارگذاری اطلاعات از فایل...
            </div>
        </div>
    </main>

    <script>
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');
        function toggleMenu() { sidebar.classList.toggle('active'); mobileOverlay.classList.toggle('active'); }
        function closeMenu() { sidebar.classList.remove('active'); mobileOverlay.classList.remove('active'); }

      
        let allAdjustments = [];
        
         async function loadData() {
            const container = document.getElementById('cardsContainer');
            try {
             
                allAdjustments = parseData();
                renderCards(allAdjustments);

            } catch (error) {
                console.error(error);
                container.innerHTML = `
                    <div style="text-align:center; color:#ef4444; grid-column:1/-1; padding:30px;">
                        <i class="fa-solid fa-triangle-exclamation fa-2x"></i><br><br>
                        خطا در خواندن فایل CSV.<br>
                        <span style="font-size:12px; opacity:0.8">مطمئن شوید فایل <b>tadili.csv</b> کنار فایل HTML است.</span>
                    </div>`;
            }
        }
       
        function parseData() {
            const result = [];
                    <?php
                        if ($items->rowCount() > 0) {
                            foreach ($items as $item) {
                                ?>
                         var code = "<?php echo $item['code'] ?>"
                         var title = "<?php echo str_replace(array("\r", "\n"),' ',$item['codetitle']) ?>"
                         var desc = "<?php echo str_replace(array("\r", "\n"),' ',$item['title']) ?>"
                         
                        result.push({ code, title, desc });
                         
                     <?php
                                }
                            }
                            ?>
            

            return result;
        }

        function renderCards(data) {
            const container = document.getElementById('cardsContainer');
            container.innerHTML = '';

            if (data.length === 0) {
                container.innerHTML = '<div style="grid-column: 1/-1; text-align: center; color: var(--text-light); padding: 40px;">موردی یافت نشد.</div>';
                return;
            }

            data.forEach((item, index) => {
                const card = document.createElement('div');
                const colorClass = `card-color-${(index % 4) + 1}`;
                card.className = `info-card ${colorClass}`;

                card.innerHTML = `
                    <div class="card-top-accent"></div>
                    <div class="card-header">
                        <span class="header-title">
                            <i class="fa-solid fa-file-contract"></i>
                            ${item.title}
                        </span>
                        <div class="code-badge">
                            <span class="code-label">کد</span>
                            <span class="code-value">${item.code}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        ${item.desc}
                    </div>
                `;
                container.appendChild(card);
            });
        }

         document.getElementById('searchInput').addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase().trim();
            const filtered = allAdjustments.filter(item => {
                return item.title.toLowerCase().includes(term) || 
                       item.code.toString().includes(term) || 
                       item.desc.toLowerCase().includes(term);
            });
            renderCards(filtered);
        });

        document.addEventListener('DOMContentLoaded', loadData);


    </script>
</body>
</html>