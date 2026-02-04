<?php
include_once __DIR__ . '/include/config.php';
include_once __DIR__ . '/include/db.php';
include_once __DIR__ . '/include/auth.php';

if ($is_premium) {
    $query = "SELECT * FROM dental";
} else {
    $query = "SELECT * FROM dental LIMIT 50";
}
$items = $db->query($query);
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>درآمد یار - دندانپزشکی</title>
    
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">


    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="fonts/vazirmatn.css">
    <link rel="stylesheet" href="style/dental.css?v=<?php echo filemtime(__DIR__ . '/style/dental.css'); ?>">

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
            <h1 class="page-title">خدمات دندانپزشکی ۱۴۰۴</h1>
        </div>

        <div class="search-container">
            <div class="search-box">
                <i class="fa-solid fa-search"></i>
                <input type="text" id="searchInput" placeholder="جستجو در شرح خدمت یا کد (D...) ...">
            </div>
        </div>

        <div class="dental-list" id="dentalContainer">
            <div style="text-align:center; padding:40px; color:#64748b; grid-column: 1/-1;">
                <i class="fa-solid fa-circle-notch fa-spin fa-2x"></i><br><br>
                در حال دریافت اطلاعات فایل 
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

        // ---  Logic ---
        let allData = [];

        async function loadData() {
            try {
                allData = parseData();
                renderData();
            } catch (error) {
                console.error(error);
                document.getElementById('dentalContainer').innerHTML = 
                    `<div style="text-align:center; color:red; padding:20px; grid-column: 1/-1;">
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
                    desc: "<?php echo str_replace(array("\r", "\n"),' ',$item['title']) ?>",
                    category: "<?php echo str_replace(array("\r", "\n"),' ',$item['tabaghe']) ?>",
                    pf: "<?php echo $item['prof'] ?>",
                    fani: "<?php echo $item['fani'] ?>",
                    eqp: "<?php echo $item['lavazem'] ?>",
                    fulltime: "<?php echo $item['gov-full-time'] ?>",
                    parttime: "<?php echo $item['gob-part-time'] ?>",
                    omomi: "<?php echo $item['omomi'] ?>",
                    charity: "<?php echo $item['charity'] ?>",
                    pr: "<?php echo $item['privat'] ?>",
                    bime: "<?php echo $item['bime'] ?>",
                                 
                });
                <?php
                }
            }
            ?>

            return result;
        }

        function renderData() {
            const container = document.getElementById('dentalContainer');
            const searchVal = document.getElementById('searchInput').value.toLowerCase().trim();
            
            const filtered = allData.filter(row => {
                // row[0]: کد, row[1]: شرح
                const code = (row.code || '').toLowerCase();
                const desc = (row.desc || '').toLowerCase();
                if (searchVal && !desc.includes(searchVal) && !code.includes(searchVal)) return false;
                return true;
            });

            container.innerHTML = '';
            
            if (filtered.length === 0) {
                container.innerHTML = `<div style="text-align:center; padding:40px; color:#64748b; grid-column: 1/-1;">موردی یافت نشد.</div>`;
                return;
            }

            // نمایش ۵۰ تای اول
            filtered.slice(0, 50).forEach(row => {
                const card = document.createElement('div');
                card.className = 'dental-card';
                
                const idx = allData.indexOf(row);
                const code = row.code;
                const desc = row.desc;
                const category = row.category;

                card.onclick = () => openModal(idx);

                card.innerHTML = `
                    <div class="card-header">
                        <span class="code-badge"><i class="fa-solid fa-tooth"></i> کد: ${code}</span>
                    </div>
                    <div class="card-body">
                        <p class="service-desc">${desc}</p>
                        ${category ? `<div class="category-tag"><i class="fa-solid fa-folder-tree"></i> ${category}</div>` : ''}
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

            let html = `<div class="modal-title">جزئیات خدمت: ${row.code}</div>`;
            
            html += secHtml("اطلاعات پایه", "fa-circle-info");
            html += rowHtml("شرح خدمت", row.desc);
            html += rowHtml("طبقه‌بندی", row.category);
            html += rowHtml("تحت پوشش", row.bime || 'خیر');

            html += secHtml("اجزاء ارزش نسبی", "fa-chart-pie");
            html += rowHtml("جزء حرفه‌ای", row.pf);
            html += rowHtml("جزء فنی", row.fani);
            html += rowHtml("جزء مواد و لوازم", row.eqp);

            html += secHtml("تعرفه‌ها (ریال)", "fa-coins");
            html += rowHtml("دولتی تمام وقت", fmt(row.fulltime));
            html += rowHtml("دولتی غیرتمام وقت", fmt(row.parttime));
            html += rowHtml("عمومی غیردولتی", fmt(row.omomi));
            html += rowHtml("خیریه", fmt(row.charity));
            html += rowHtml("خصوصی", fmt(row.pr));

            modalBody.innerHTML = html;
            detailModal.classList.add('open');
        }

        document.getElementById('searchInput').addEventListener('input', renderData);
        document.addEventListener('DOMContentLoaded', loadData);

    </script>
</body>
</html>