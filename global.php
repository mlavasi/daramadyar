<?php
include_once __DIR__ . '/include/config.php';
include_once __DIR__ . '/include/db.php';
include_once __DIR__ . '/include/auth.php';

if ($is_premium) {
    $query = "SELECT * FROM tbl_global";
} else {
    $query = "SELECT * FROM tbl_global LIMIT 10";
}
$items = $db->query($query);
?>



<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>درآمد یار - کدهای گلوبال کامل</title>
    
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">

    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="fonts/vazirmatn.css">
    <link rel="stylesheet" href="style/global.css?v=<?php echo filemtime(__DIR__ . '/style/global.css'); ?>">
    <?php include 'include/analytics.php'; ?>
</head>
<body>

    <button class="mobile-menu-btn" onclick="toggleMenu()"><i class="fa-solid fa-bars"></i></button>
    <div class="overlay" id="mobileOverlay" onclick="closeMenu()"></div>

    <div class="modal-overlay" id="detailModal" onclick="closeDetail(event)">
        <div class="modal-content" onclick="event.stopPropagation()">
            <span class="close-modal" onclick="closeDetail()">&times;</span>
            <div id="modalBody"></div>
        </div>
    </div>

    <?php include __DIR__ . '/include/sidebar.php'; ?>

    <main class="main-content">
        <div class="page-header">
            <a href="index" class="back-btn"><i class="fa-solid fa-arrow-right"></i></a>
            <h1 class="page-title">کدهای گلوبال ۱۴۰۴</h1>
        </div>

        <div class="search-container">
            <div class="search-box">
                <i class="fa-solid fa-search"></i>
                <input type="text" id="searchInput" placeholder="جستجو در شرح، کد ملی (۹۹...) یا کد گلوبال...">
            </div>
        </div>

        <div class="global-list" id="globalContainer">
            <div style="text-align:center; padding:40px; color:#64748b;">
                <i class="fa-solid fa-circle-notch fa-spin fa-2x"></i><br><br>
                در حال دریافت اطلاعات کامل فایل 
            </div>
        </div>
    </main>

    <script>
        // --- UI Logic ---
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');
        const detailModal = document.getElementById('detailModal');
        
        function toggleMenu() { sidebar.classList.toggle('active'); mobileOverlay.classList.toggle('active'); }
        function closeMenu() { sidebar.classList.remove('active'); mobileOverlay.classList.remove('active'); }
        
        function closeDetail(event) { 
            if (event && event.target !== detailModal && !event.target.classList.contains('close-modal')) return;
            detailModal.classList.remove('open'); 
        }

        // --- CSV Logic ---
        let allData = [];

        async function loadData() {
            try {
                allData = parseData();
                renderData();
            } catch (error) {
                console.error(error);
                document.getElementById('globalContainer').innerHTML = 
                    `<div style="text-align:center; color:red; padding:20px;">
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
                    gcode: "<?php echo str_replace(array("\r", "\n"),' ',$item['gcode']) ?>",
                    title: "<?php echo str_replace(array("\r", "\n"),' ',$item['title']) ?>",
                    prof: "<?php echo $item['prof'] ?>",
                    total_value_anesthesia: "<?php echo $item['total_value_anesthesia'] ?>",
                    staying_time: "<?php echo $item['staying_time'] ?>",
                    nursing_ward: "<?php echo $item['nursing_ward'] ?>",
                    nursing_op_room: "<?php echo $item['nursing_op_room'] ?>",
                    global_drug: "<?php echo $item['global_drug'] ?>",
                    global_equipment: "<?php echo $item['global_equipment'] ?>",
                    first_class_hp_tariff: "<?php echo $item['first_class_hp_tariff'] ?>",
                    tow_class_hp_tariff: "<?php echo $item['tow_class_hp_tariff'] ?>",
                    three_class_hp_tariff: "<?php echo $item['three_class_hp_tariff'] ?>",
                    four_class_hp_tariff: "<?php echo $item['four_class_hp_tariff'] ?>",
                    ful_tim_surgical_fee_kol: "<?php echo $item['ful_tim_surgical_fee_kol'] ?>",
                    ful_tim_surgery_fee_bime: "<?php echo $item['ful_tim_surgery_fee_bime'] ?>",
                    ful_tim_bihoshi_fee_kol: "<?php echo $item['ful_tim_bihoshi_fee_kol'] ?>",
                    ful_tim_bihoshi_fee_bime: "<?php echo $item['ful_tim_bihoshi_fee_bime'] ?>",
                    nful_tim_surgical_fee_kol: "<?php echo $item['nful_tim_surgical_fee_kol'] ?>",
                    nful_tim_surgical_fee_bime: "<?php echo $item['nful_tim_surgical_fee_bime'] ?>",
                    nful_timbihoshi_fee_kol: "<?php echo $item['nful_timbihoshi_fee_kol'] ?>",
                    nful_tim_bihoshi_fee_bime: "<?php echo $item['nful_tim_bihoshi_fee_bime'] ?>",
                    first_hp_franchise: "<?php echo $item['first_hp_franchise'] ?>",
                    two_hp_franchise: "<?php echo $item['two_hp_franchise'] ?>",
                    three_hp_franchise: "<?php echo $item['three_hp_franchise'] ?>",
                    four_hp_franchise: "<?php echo $item['four_hp_franchise'] ?>",
                    update_check: "<?php echo $item['update_check'] ?>"
                });
                <?php
            }
        }
    ?>

    return result;
}



        function renderData() {
            const container = document.getElementById('globalContainer');
            const searchVal = document.getElementById('searchInput').value.toLowerCase().trim();
            
            const filtered = allData.filter(row => {
                // row[1]: کد ملی (9900..), row[2]: کد گلوبال, row[4]: شرح
                const codeMain = (row.code || '').toLowerCase();
                const codeGlobal = (row.gcode || '').toLowerCase();
                const desc = (row.title || '').toLowerCase();
                
                if (searchVal && !desc.includes(searchVal) && !codeMain.includes(searchVal) && !codeGlobal.includes(searchVal)) return false;
                return true;
            });

            container.innerHTML = '';
            
            if (filtered.length === 0) {
                container.innerHTML = `<div style="text-align:center; padding:40px; color:#64748b;">موردی یافت نشد.</div>`;
                return;
            }

            // نمایش ۵۰ تای اول برای سرعت
            filtered.slice(0 , 50).forEach(row => {
                const card = document.createElement('div');
                card.className = 'global-card';
                
                const idx = allData.indexOf(row);
                const codeMain = row.code;
                const codeGlobal = row.gcode;
                const desc = row.title;

                card.innerHTML = `
                    <div class="card-header">
                        <div class="header-info">
                            <span class="code-main">کد ملی: ${codeMain}</span>
                            <span class="code-global">کد گلوبال: ${codeGlobal}</span>
                        </div>
                        <button class="details-btn" onclick="openModal(${idx})">جزئیات کامل</button>
                    </div>
                    <div class="card-body">
                        ${desc}
                    </div>
                `;
                container.appendChild(card);
            });
        }

        function openModal(index) {
            const row = allData[index];
            const modalBody = document.getElementById('modalBody');
            
            const fmt = (val) => val ? val.replace(/\B(?=(\d{3})+(?!\d))/g, ",") : '-';
            const rowHtml = (lbl, val) => `<div class="detail-row"><span class="detail-label">${lbl}</span><span class="detail-value">${val}</span></div>`;
            const secHtml = (title, icon) => `<div class="section-header"><i class="fa-solid ${icon}"></i>${title}</div>`;

            let html = `<div class="modal-title">جزئیات کد گلوبال: ${row.code}</div>`;
            
            // 1. اطلاعات پایه
            html += secHtml("#", "fa-circle-info");
            html += rowHtml("کدهای گلوبال", row.gcode);
            html += rowHtml("مدت اقامت (روز)", row.staying_time);
            html += rowHtml("جزء حرفه‌ای", row.prof);
            html += rowHtml("ارزش تام بیهوشی", row.total_value_anesthesia);

             // 4. ریز هزینه‌ها و پرستاری (ستون‌های 8 تا 11)
            html += secHtml("ریز هزینه‌های لحاظ شده", "fa-list-check");
            html += rowHtml("پرستاری بخش", fmt(row.nursing_ward));
            html += rowHtml("پرستاری اتاق عمل/ریکاوری", fmt(row.nursing_op_room));
            html += rowHtml("هزینه دارو", fmt(row.global_drug));
            html += rowHtml("هزینه لوازم مصرفی", fmt(row.global_equipment));

            // 2. تعرفه‌های گلوبال (ستون‌های 12 تا 15)
            html += secHtml("تعرفه گلوبال (ریال)", "fa-sack-dollar");
            html += rowHtml("بیمارستان درجه ۱", fmt(row.first_class_hp_tariff));
            html += rowHtml("بیمارستان درجه ۲", fmt(row.tow_class_hp_tariff));
            html += rowHtml("بیمارستان درجه ۳", fmt(row.three_class_hp_tariff));
            html += rowHtml("بیمارستان درجه ۴", fmt(row.four_class_hp_tariff));

            // 5. حق الزحمه پزشک و بیهوشی (ستون‌های 16 تا 23)
            html += secHtml("حق‌الزحمه پزشک و بیهوشی (تمام وقت)", "fa-user-doctor");
            html += rowHtml("جراحی (کل)", fmt(row.ful_tim_surgical_fee_kol));
            html += rowHtml("جراحی (سهم بیمه)", fmt(row.ful_tim_surgery_fee_bime));
            html += rowHtml("بیهوشی (تام بیهوشی+ریکاوری)(کل)", fmt(row.ful_tim_bihoshi_fee_kol));
            html += rowHtml("بیهوشی (تام بیهوشی+ریکاوری)(سهم بیمه)", fmt(row.ful_tim_bihoshi_fee_bime));

            
            html += secHtml("حق‌الزحمه پزشک و بیهوشی (غیر تمام وقت)", "fa-user-clock");
            html += rowHtml("جراحی (کل)", fmt(row.nful_tim_surgical_fee_kol));
            html += rowHtml("جراحی (سهم بیمه)", fmt(row.nful_tim_surgical_fee_bime));
            html += rowHtml("بیهوشی (تام بیهوشی+ریکاوری)(کل)", fmt(row.nful_timbihoshi_fee_kol));
            html += rowHtml("بیهوشی (تام بیهوشی+ریکاوری)(سهم بیمه)", fmt(row.nful_tim_bihoshi_fee_bime));

             // 3. سهم پرداختی بیمار (ستون‌های 24 تا 27)
            html += secHtml("سهم پرداختی بیمار (ریال)", "fa-user-injured");
            html += rowHtml("بیمارستان درجه ۱", fmt(row.first_hp_franchise));
            html += rowHtml("بیمارستان درجه ۲", fmt(row.two_hp_franchise));
            html += rowHtml("بیمارستان درجه ۳", fmt(row.three_hp_franchise));
            html += rowHtml("بیمارستان درجه ۴", fmt(row.four_hp_franchise));


            modalBody.innerHTML = html;
            detailModal.classList.add('open');
        }

        document.getElementById('searchInput').addEventListener('input', renderData);
        document.addEventListener('DOMContentLoaded', loadData);

    </script>
</body>
</html>