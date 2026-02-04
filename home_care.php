<?php
include_once __DIR__ . '/include/config.php';
include_once __DIR__ . '/include/db.php';
include_once __DIR__ . '/include/auth.php';

$query = "SELECT * FROM home_nursing";
$items = $db->query($query);

?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>درآمد یار - پرستاری در منزل</title>
    
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">

    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="fonts/vazirmatn.css">
   <link rel="stylesheet" href="style/home_care.css?v=<?php echo filemtime(__DIR__ . '/style/home_care.css'); ?>">
</head>
<body>

    <button class="mobile-menu-btn" onclick="toggleMenu()"><i class="fa-solid fa-bars"></i></button>
    <div class="overlay" id="mobileOverlay" onclick="closeMenu()"></div>

    <?php include __DIR__ . '/include/sidebar.php'; ?>

    <main class="main-content">
        <div class="page-header">
            <a href="index" class="back-btn"><i class="fa-solid fa-arrow-right"></i></a>
            <h1 class="page-title">تعرفه‌های پرستاری در منزل ۱۴۰۴</h1>
        </div>

        <div class="search-container">
            <div class="search-box">
                <i class="fa-solid fa-search"></i>
                <input type="text" id="searchInput" placeholder="جستجو در شرح خدمت یا کد (مثال: ۹۶۰۰۱۰)...">
            </div>
        </div>

        <div class="care-list" id="careContainer">
            <div style="text-align:center; padding:40px; color:#64748b; grid-column:1/-1;">
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

        // ---  Logic ---
        let allData = [];

        async function loadData() {
            try {
             
                allData = parseData();
                renderData();
            } catch (error) {
                console.error(error);
                document.getElementById('careContainer').innerHTML = 
                    `<div style="text-align:center; color:red; padding:20px; grid-column:1/-1;">
                        خطا در بارگذاری فایل <br>
                 
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
                    title: "<?php echo str_replace(array("\r", "\n"),' ',$item['title']) ?>",
                    private: "<?php echo $item['private'] ?>",
                    other: "<?php echo $item['other'] ?>"
                  
                   
                });
                         
                     
                         
                     <?php
                                }
                            }
                            ?>
            

            return result;
        }

        function renderData() {
            const container = document.getElementById('careContainer');
            const searchVal = document.getElementById('searchInput').value.toLowerCase().trim();
            
            const filtered = allData.filter(row => {
                const code = (row.code || '').toLowerCase();
                const desc = (row.title || '').toLowerCase();
                
                if (searchVal && !desc.includes(searchVal) && !code.includes(searchVal)) return false;
                return true;
            });

            container.innerHTML = ''; // پاک کردن محتوای قبلی
            
            if (filtered.length === 0) {
                container.innerHTML = `<div style="text-align:center; padding:40px; color:#64748b; grid-column:1/-1;">موردی یافت نشد.</div>`;
                return;
            }

            filtered.forEach(row => {
                const code = row.code;
                const desc = row.title;
                const pricePrivate = row.private;
                const priceOther = row.other;

                // ایجاد المنت div (نه استرینگ)
                const card = document.createElement('div');
                card.className = 'care-card';
                card.innerHTML = `
                    <div class="card-header">
                        <div class="code-box">
                            <i class="fa-solid fa-user-nurse"></i>
                            کد: ${code}
                        </div>
                    </div>
                    <div class="card-body">
                        ${desc}
                    </div>
                    <div class="card-footer">
                        <div class="price-badge">
                            <span class="price-title">مراکز خصوصی</span>
                            <span class="price-val">${pricePrivate || '-'}</span>
                        </div>
                        <div class="price-badge secondary">
                            <span class="price-title">سایر مراکز</span>
                            <span class="price-val">${priceOther || '-'}</span>
                        </div>
                    </div>
                `;
                // اضافه کردن المنت به کانتینر
                container.appendChild(card);
            });
        }

        document.getElementById('searchInput').addEventListener('input', renderData);
        document.addEventListener('DOMContentLoaded', loadData);

    </script>
</body>
</html>