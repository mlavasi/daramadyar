<?php
include_once __DIR__ . '/include/config.php';
include_once __DIR__ . '/include/db.php';
include_once __DIR__ . '/include/auth.php';

if ($is_premium) {
    $query = "SELECT * FROM bed_hotelling";
} else {
    $query = "SELECT * FROM bed_hotelling LIMIT 5";
}
$items = $db->query($query);
?>




<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>درآمد یار - تخت روز (هوشمند)</title>
    
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <!-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">  -->

    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="fonts/vazirmatn.css">
    <link rel="stylesheet" href="style/bed_day.css?v=<?php echo filemtime(__DIR__ . '/style/bed_day.css'); ?>">
<?php include 'include/analytics.php'; ?>
  
</head>
<body>

    <button class="mobile-menu-btn" onclick="toggleMenu()"><i class="fa-solid fa-bars"></i></button>
    <div class="overlay" id="mobileOverlay" onclick="closeMenu()"></div>

    <?php include __DIR__ . '/include/sidebar.php'; ?>

    <main class="main-content">
        <div class="page-header">
            <a href="index" class="back-btn"><i class="fa-solid fa-arrow-right"></i></a>
            <h1 class="page-title">کدینگ تخت روز ۱۴۰۴</h1>
        </div>

        <div class="filter-wrapper">
            <div class="search-box">
                <i class="fa-solid fa-search"></i>
                <input type="text" id="searchInput" placeholder="جستجو در عنوان یا کد خدمت...">
            </div>
            
            <div class="grade-filters">
                <div class="filter-chip active" onclick="setGrade(1)">درجه ۱</div>
                <div class="filter-chip" onclick="setGrade(2)">درجه ۲</div>
                <div class="filter-chip" onclick="setGrade(3)">درجه ۳</div>
                <div class="filter-chip" onclick="setGrade(4)">درجه ۴</div>
            </div>
        </div>

        <div class="bed-grid" id="bedContainer">
            <div style="text-align:center; padding:40px; color:#64748b; width:100%; grid-column:1/-1;">
                <i class="fa-solid fa-circle-notch fa-spin fa-2x"></i><br><br>
                در حال دریافت اطلاعات فایل...
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
        let currentGrade = 1;

        async function loadData() {
            try {
                allData = parseData();
                renderData();
            } catch (error) {
                console.error(error);
                document.getElementById('bedContainer').innerHTML = 
                    `<div style="text-align:center; color:red; padding:20px;">
                        خطا در بارگذاری ... <br>
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
                    dolati: "<?php echo $item['dolati'] ?>",
                    privat: "<?php echo $item['privat'] ?>",
                    general: "<?php echo $item['general'] ?>",
                    charity: "<?php echo $item['charity'] ?>",
                    graid: "<?php echo $item['graid'] ?>"
                });
                         
                     
                         
                     <?php
                                }
                            }
                            ?>
            

            return result;
        }

        function renderData() {
            

            const container = document.getElementById('bedContainer');
            const searchVal = document.getElementById('searchInput').value.toLowerCase().trim();
            
            const filtered = allData.filter(row => {
             
                // ستون 6: درجه بیمارستان
                const grade = parseInt(row.graid);
                
                if (grade !== currentGrade) return false;

                // جستجو در کد (0) و عنوان (1)
                const code = (row.code || '').toLowerCase();
                const title = (row.title || '').toLowerCase();
                if (searchVal && !title.includes(searchVal) && !code.includes(searchVal)) return false;

                return true;
            });

            container.innerHTML = '';
           
            if (filtered.length === 0) {
                container.innerHTML = `<div style="text-align:center; padding:40px; color:#64748b; width:100%; grid-column:1/-1;">موردی یافت نشد.</div>`;
                return;
            }

            filtered.forEach(row => {
                const code = row.code;
                const title = row.title;
                
                // فرمت قیمت‌ها: اگر _ بود خط تیره، وگرنه نمایش عادی
                //const fmt = (val) => (!val || val === '_') ? '<span class="empty-price">-</span>' : val;
                
                const p1 = row.dolati; // دولتی
                const p2 = row.privat; // خصوصی
                const p3 = row.general; // عمومی
                const p4 = row.charity; // خیریه

                const cardHtml = `
                    <div class="bed-card">
                        <div class="card-header">
                            <div class="card-title">${title}</div>
                            <div class="card-code">کد: ${code}</div>
                        </div>
                        <div class="price-table-wrapper">
                            <table class="price-table">
                                <thead>
                                    <tr>
                                        <th>دولتی</th>
                                        <th>خصوصی</th>
                                        <th>عمومی</th>
                                        <th>خیریه</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>${p1}</td>
                                        <td>${p2}</td>
                                        <td>${p3}</td>
                                        <td>${p4}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                `;
                container.innerHTML += cardHtml;
            });
        }

        function setGrade(grade) {
            currentGrade = grade;
            
            // آپدیت دکمه‌ها
            document.querySelectorAll('.filter-chip').forEach((el, index) => {
                if (index + 1 === grade) el.classList.add('active');
                else el.classList.remove('active');
            });

            renderData();
        }

        document.getElementById('searchInput').addEventListener('input', renderData);

        // شروع برنامه
        document.addEventListener('DOMContentLoaded', loadData);

    </script>
</body>
</html>