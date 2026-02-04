<?php
include_once __DIR__ . '/include/config.php';
include_once __DIR__ . '/include/db.php';
include_once __DIR__ . '/include/auth.php';

if ($is_premium) {
    $query = "SELECT * FROM nurs_tariff";
} else {
    $query = "SELECT * FROM nurs_tariff LIMIT 5";
}
$items = $db->query($query);
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>درآمد یار - خدمات پرستاری</title>
    
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="fonts/vazirmatn.css">
    <link rel="stylesheet" href="style/nursing.css?v=<?php echo filemtime(__DIR__ . '/style/nursing.css'); ?>">
</head>
<body>

    <button class="mobile-menu-btn" onclick="toggleMenu()"><i class="fa-solid fa-bars"></i></button>
    <div class="overlay" id="mobileOverlay" onclick="closeMenu()"></div>

    <?php include __DIR__ . '/include/sidebar.php'; ?>

    <main class="main-content">
        <div class="page-header">
            <a href="index" class="back-btn"><i class="fa-solid fa-arrow-right"></i></a>
            <h1 class="page-title">تعرفه‌های پرستاری ۱۴۰۴</h1>
        </div>

        <div class="search-container">
            <div class="search-box">
                <i class="fa-solid fa-search"></i>
                <input type="text" id="searchInput" placeholder="جستجو در شرح خدمت یا کد (مثال: جراحی، 903500)...">
            </div>
        </div>

        <div class="nursing-grid" id="nursingContainer">
            <div style="text-align:center; padding:40px; color:#64748b;">
                <i class="fa-solid fa-circle-notch fa-spin fa-2x"></i><br><br>
                در حال دریافت اطلاعات فایل CSV...
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
        let allData = [];

        async function loadData() {
            try {
          
                allData = parseData();
                renderData();
            } catch (error) {
                console.error(error);
                document.getElementById('nursingContainer').innerHTML = 
                    `<div style="text-align:center; color:red; padding:20px;">
                        خطا در بارگذاری فایل .<br>
                 
                    </div>`;
            }
        }

        function parseData() {
             const result = [];
                    <?php
                        if ($items->rowCount() > 0) {
                            foreach ($items as $item) {
                                ?>
                          result.push({
                    code: "<?php echo $item['code'] ?>",
                    title1: "<?php echo str_replace(array("\r", "\n"),' ',$item['title_part1']) ?>",
                    title2: "<?php echo str_replace(array("\r", "\n"),' ',$item['title_part2']) ?>",
                    rv: "<?php echo $item['relative_value'] ?>",
                    fee: "<?php echo $item['fee'] ?>"
                  
                   
                });    
                     <?php
                                }
                            }
                            ?>
            return result;
        }

        function renderData() {
            const container = document.getElementById('nursingContainer');
            const searchVal = document.getElementById('searchInput').value.toLowerCase().trim();
            
            const filtered = allData.filter(row => {
                // ستون 0: کد، ستون 1: شرح
                const code = (row.code || '').toLowerCase();
                const title1 = (row.title1 || '').toLowerCase();
                const title2 = (row.title2 || '').toLowerCase();
                if (searchVal && !title1.includes(searchVal) && !title2.includes(searchVal) && !code.includes(searchVal)) return false;
                return true;
            });

            container.innerHTML = '';
            
            if (filtered.length === 0) {
                container.innerHTML = `<div style="text-align:center; padding:40px; color:#64748b;">موردی یافت نشد.</div>`;
                return;
            }

            filtered.forEach(row => {
                const code = row.code;
                const title1 = row.title1;
                const title2 = row.title2;
                const rv = row.rv; // ارزش نسبی
                const tariff = row.fee; // تعرفه

                const cardHtml = `
                    <div class="nurse-card">
                        <div class="card-stats">
                            <div class="stat-item">
                                <span class="stat-label">کد خدمت</span>
                                <span class="stat-value">${code}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">ارزش نسبی</span>
                                <span class="stat-value">${rv}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">تعرفه (ریال)</span>
                                <span class="stat-value price">${tariff}</span>
                            </div>
                        </div>
                        <div class="card-desc">
                            ${title1}

                            <div class="stat-label">
                            ${title2}
                        </div>
                        </div>
                        
                    </div>
                `;
                container.innerHTML += cardHtml;
            });
        }

        document.getElementById('searchInput').addEventListener('input', renderData);

        document.addEventListener('DOMContentLoaded', loadData);

    </script>
</body>
</html>