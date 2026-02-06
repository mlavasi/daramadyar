<?php
include_once __DIR__ . '/include/config.php';
include_once __DIR__ . '/include/db.php';
include_once __DIR__ . '/include/auth.php';

// دریافت کل اطلاعات (فیلد type_code حذف شد)
$query = "SELECT id, rvu_code, loinc_cod, fa_name, en_name, fee FROM loinc ORDER BY id ASC";
$items = $db->query($query);
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>کدینگ LOINC و RVU</title>
    
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="fonts/vazirmatn.css">
    <link rel="stylesheet" href="style/loinc.css?v=<?php echo filemtime(__DIR__ . '/style/loinc.css'); ?>">
    <?php include 'include/analytics.php'; ?>
</head>
<body>

    <button class="mobile-menu-btn" onclick="toggleMenu()"><i class="fa-solid fa-bars"></i></button>
    <div class="overlay" id="mobileOverlay" onclick="closeMenu()"></div>

    <?php include __DIR__ . '/include/sidebar.php'; ?>

    <main class="main-content">
        <div class="page-header">
            <a href="index" class="back-btn"><i class="fa-solid fa-arrow-right"></i></a>
            <h1 class="page-title">بانک اطلاعات LOINC</h1>
        </div>

        <div class="filter-wrapper">
            <div class="search-box">
                <i class="fa-solid fa-search"></i>
                <input type="text" id="searchInput" placeholder="جستجو (نام فارسی، انگلیسی، کد RVU یا LOINC)...">
            </div>
        </div>

        <div class="loinc-grid" id="dataContainer">
            <div style="text-align:center; padding:40px; color:#64748b; width:100%; grid-column:1/-1;">
                <i class="fa-solid fa-circle-notch fa-spin fa-2x"></i><br><br>
                در حال دریافت اطلاعات...
            </div>
        </div>
        
        <div id="countDisplay" style="text-align:left; color:#94a3b8; font-size:12px; margin-top:10px;"></div>
    </main>

    <script>
        // --- UI Logic ---
        const sidebar = document.querySelector('.sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');
        function toggleMenu() { sidebar.classList.toggle('active'); mobileOverlay.classList.toggle('active'); }
        function closeMenu() { sidebar.classList.remove('active'); mobileOverlay.classList.remove('active'); }

        // --- Data Logic ---
        let allData = [];
        const MAX_DISPLAY = 50; // همیشه حداکثر 50 تا نمایش داده شود

        async function loadData() {
            try {
                // تمام دیتا در حافظه لود می‌شود تا جستجو سریع باشد
                allData = [
                    <?php
                    if ($items->rowCount() > 0) {
                        foreach ($items as $item) {
                            $fa = str_replace(["\r", "\n", "'", '"'], [' ', ' ', '', ''], $item['fa_name']);
                            $en = str_replace(["\r", "\n", "'", '"'], [' ', ' ', '', ''], $item['en_name']);
                            echo "{
                                id: '{$item['id']}',
                                rvu: '{$item['rvu_code']}',
                                loinc: '{$item['loinc_cod']}',
                                fa: '$fa',
                                en: '$en',
                                fee: '{$item['fee']}'
                            },";
                        }
                    }
                    ?>
                ];

                renderData();
            } catch (error) {
                console.error(error);
                document.getElementById('dataContainer').innerHTML = 
                    `<div style="text-align:center; color:red; padding:20px;">خطا در بارگذاری داده‌ها</div>`;
            }
        }

        function renderData() {
            const container = document.getElementById('dataContainer');
            const countDisplay = document.getElementById('countDisplay');
            const searchVal = document.getElementById('searchInput').value.toLowerCase().trim();
            
            // 1. فیلتر کردن (جستجو در کل ۳۰۰۰ رکورد)
            let filtered = allData;
            if (searchVal !== "") {
                filtered = allData.filter(row => {
                    const searchStr = `${row.fa} ${row.en} ${row.loinc} ${row.rvu}`.toLowerCase();
                    return searchStr.includes(searchVal);
                });
            }

            // 2. برش آرایه (فقط ۵۰ تای اول از نتایج فیلتر شده)
            const slicedData = filtered.slice(0, MAX_DISPLAY);

            // نمایش پیام تعداد
            if (filtered.length > MAX_DISPLAY) {
                countDisplay.innerHTML = `نمایش ${slicedData.length} مورد از ${filtered.length} نتیجه (برای دیدن موارد دقیق‌تر جستجو کنید)`;
            } else {
                countDisplay.innerHTML = `تعداد نتایج: ${filtered.length}`;
            }

            // اگر موردی یافت نشد
            if (slicedData.length === 0) {
                container.innerHTML = `<div style="text-align:center; padding:40px; color:#64748b; width:100%; grid-column:1/-1;">موردی یافت نشد.</div>`;
                return;
            }

            // 3. رندر کردن HTML
            const htmlString = slicedData.map(row => {
                let formattedFee = row.fee;
                if (!isNaN(row.fee) && row.fee && row.fee.length > 0) {
                     formattedFee = Number(row.fee).toLocaleString('fa-IR');
                } else {
                     formattedFee = row.fee || '-';
                }

                return `
                    <div class="loinc-card">
                        <div class="card-header">
                            <div class="card-title">${row.fa || 'بدون نام فارسی'}</div>
                            <div class="loinc-badge">${row.loinc || 'No LOINC'}</div>
                        </div>
                        <div class="card-body">
                            <div class="en-name">${row.en || '-'}</div>
                            
                            <div class="info-row">
                                <div class="info-item">
                                    <span class="info-label">کد RVU</span>
                                    <span class="info-value">${row.rvu || '-'}</span>
                                </div>
                                <div class="info-item" style="align-items: flex-end;">
                                    <span class="info-label">تعرفه (ریال)</span>
                                    <span class="info-value price-value">${formattedFee}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            container.innerHTML = htmlString;
        }

        document.getElementById('searchInput').addEventListener('input', renderData);
        document.addEventListener('DOMContentLoaded', loadData);

    </script>
</body>
</html>