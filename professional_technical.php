<?php
include_once __DIR__ . '/include/auth.php';
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>درآمد یار - فنی و حرفه‌ای</title>
    
    <meta http-equiv="Cache-Control" content="max-age=3600">

    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="fonts/vazirmatn.css">
    <link rel="stylesheet" href="style/professional_technical.css?v=<?php echo filemtime(__DIR__ . '/style/professional_technical.css'); ?>">
</head>
<body>

    <button class="mobile-menu-btn" onclick="toggleMenu()"><i class="fa-solid fa-bars"></i></button>
    <div class="overlay" id="mobileOverlay" onclick="closeMenu()"></div>

    <?php include __DIR__ . '/include/sidebar.php'; ?>

    <main class="main-content">
        <div class="page-header">
            <a href="index" class="back-btn"><i class="fa-solid fa-arrow-right"></i></a>
            <div class="header-text">
                <h1 class="page-title">جزء حرفه‌ای و فنی ۱۴۰۴</h1>
                <div class="page-subtitle">مقادیر به ریال می‌باشد</div>
            </div>
        </div>

        <div class="tariffs-grid" id="tariffsContainer">
            <div style="text-align:center; padding: 40px; color: #64748b; width:100%; grid-column: 1 / -1;">
                <i class="fa-solid fa-circle-notch fa-spin fa-2x"></i><br><br>در حال پردازش داده‌ها...
            </div>
        </div>
    </main>

    <script>
        // --- UI Logic ---
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');
        function toggleMenu() { sidebar.classList.toggle('active'); mobileOverlay.classList.toggle('active'); }
        function closeMenu() { sidebar.classList.remove('active'); mobileOverlay.classList.remove('active'); }

        // --- Embedded Data (Single File Mode) ---
        const rawCsvData = `بخش دولتی,
                    جزء حرفه ای سرپایی تمام وقت,770000
                    جزء حرفه ای بستری تمام وقت,1370000
                    جزء حرفه ای غیرتمام وقت,410000
                    جزء فنی بدون #,670000
                    جزء فنی با #,670000

                     بخش خصوصی,
                    جزء حرفه ای بدون #,1370000
                    جزء حرفه ای با # و (ویزیت سرپایی),770000
                    جزء فنی بدون # ,4350000
                    جزء فنی با # (کدهای 7),2750000
                    جزء فنی با # (کدهای 8 تا 9),2600000

                    ,

                      بخش عمومی غیر دولتی,
                    جزء حرفه ای بدون #,1370000
                    جزء حرفه ای با # و (ویزیت سرپایی),770000
                    جزء فنی بدون # ,1750000
                    جزء فنی با # (کدهای 7),1112000
                    جزء فنی با # (کدهای 8 تا 9),1050000
                   
  بخش خیریه و موقوفه,
                    جزء حرفه ای بدون #,1370000
                    جزء حرفه ای با #,770000
                    جزء فنی بدون # ,3700000
                    جزء فنی با # (کدهای 1 تا 7),2340000
                    جزء فنی با # (کدهای 8 تا 9),2210000
                
                    ,
                   دندانپزشکی در بخش دولتی,
                    جزء حرفه ای دندانپزشکی (تمام وقت),850000
                    جزء حرفه ای دندانپزشکی (غیرتمام وقت),425000
                    جزء فنی دندانپزشکی (تمام وقت و غیرتمام وقت),730000
                    جزء مواد و لوازم مصرفی دندانپزشکی (تمام وقت و غیرتمام وقت),1000000

                    دندانپزشکی در بخش عمومی غیر دولتی,
                    جزء حرفه ای دندانپزشکی,850000
                    جزء فنی دندانپزشکی,1360000
                    جزء مواد و لوازم مصرفی دندانپزشکی,1000000
                    ,
                  
                    دندانپزشکی در بخش خصوصی,
                    جزء حرفه ای دندانپزشکی,850000
                    جزء فنی دندانپزشکی,1900000
                    جزء مواد و لوازم مصرفی دندانپزشکی,1000000

                    دندانپزشکی در بخش خیریه و موقوفه,
                    جزء حرفه ای دندانپزشکی,850000
                    جزء فنی دندانپزشکی,1620000
                    جزء مواد و لوازم مصرفی دندانپزشکی,1000000`;

        // آیکون‌های متناظر برای هر بخش
        const iconsMap = {
            'دولتی': 'fa-building-columns',
            'دندانپزشکی': 'fa-tooth',
            'خصوصی': 'fa-hospital-user',
            'خیریه': 'fa-hand-holding-heart',
            'عمومی': 'fa-people-group'
        };

        function parseAndRender() {
            const container = document.getElementById('tariffsContainer');
            container.innerHTML = '';
            
            const lines = rawCsvData.split('\n');
            let currentCategory = null;
            let currentItems = [];

            const renderCard = (title, items) => {
                if (!title) return;
                
                let iconClass = 'fa-file-invoice-dollar'; 
                for (const [key, value] of Object.entries(iconsMap)) {
                    if (title.includes(key)) {
                        iconClass = value;
                        break;
                    }
                }

                let rowsHtml = '';
                items.forEach(item => {
                    const price = item.val ? parseInt(item.val).toLocaleString('fa-IR') : '---';
                    rowsHtml += `
                        <tr>
                            <td class="title-col">${item.name}</td>
                            <td class="price-col"><span class="price-badge">${price}</span></td>
                        </tr>`;
                });

                const cardHtml = `
                    <div class="tariff-card">
                        <div class="tariff-header">
                            <i class="fa-solid ${iconClass}"></i>
                            ${title}
                        </div>
                        <table><tbody>${rowsHtml}</tbody></table>
                    </div>`;
                
                container.innerHTML += cardHtml;
            };

            lines.forEach(line => {
                const parts = line.split(',');
                const p0 = parts[0] ? parts[0].trim() : '';
                const p1 = parts[1] ? parts[1].trim() : '';

                if (p0 && (!p1 || isNaN(parseInt(p1)))) {
                    if (currentCategory) {
                        renderCard(currentCategory, currentItems);
                    }
                    currentCategory = p0;
                    currentItems = [];
                } else if (p0 && p1) {
                    currentItems.push({ name: p0, val: p1 });
                }
            });

            if (currentCategory) {
                renderCard(currentCategory, currentItems);
            }
        }

        document.addEventListener('DOMContentLoaded', parseAndRender);
    </script>
</body>
</html>