<?php
include_once __DIR__ . '/include/auth.php';
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>درآمد یار - کدهای حذف شده</title>
    
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">

    <!-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> -->

    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="fonts/vazirmatn.css">
    <link rel="stylesheet" href="style/deductions.css?v=<?php echo filemtime(__DIR__ . '/style/deductions.css'); ?>">
<?php include 'include/analytics.php'; ?>
    <style>
        :root {
            --primary-bg: #f3f4f6;
            --sidebar-bg: #ffffff;
            --text-main: #1e293b;
            --text-light: #64748b;
            --accent-teal: #0ea5e9;
            --accent-red: #e11d48; /* رنگ قرمز مخصوص حذفیات */
            --bg-red-light: #ffe4e6;
            --border-color: #e2e8f0;
            --transition-speed: 0.3s;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Vazirmatn', sans-serif; text-decoration: none; outline: none; -webkit-tap-highlight-color: transparent; }

        body { background-color: var(--primary-bg); color: var(--text-main); display: flex; height: 100vh; overflow: hidden; }

        /* --- Mobile Menu --- */
        .mobile-menu-btn { display: none; position: fixed; top: 20px; right: 20px; z-index: 1001; background: white; border: none; width: 45px; height: 45px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); color: var(--accent-red); font-size: 20px; cursor: pointer; align-items: center; justify-content: center; }
        .overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.4); backdrop-filter: blur(2px); z-index: 1002; }
        .overlay.active { display: block; }

        /* --- Sidebar --- */
        .sidebar { width: 260px; background-color: var(--sidebar-bg); border-left: 1px solid var(--border-color); display: flex; flex-direction: column; padding: 20px; z-index: 1000; transition: transform var(--transition-speed); flex-shrink: 0; height: 100vh; }
        .sidebar.active { transform: translateX(0); }
        .brand-logo { font-size: 22px; font-weight: 800; color: var(--accent-teal); display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 40px; padding-bottom: 20px; border-bottom: 1px solid #f1f5f9; }
        .nav-menu { display: flex; flex-direction: column; gap: 10px; flex: 1; overflow-y: auto; }
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 12px 15px; border-radius: 12px; color: var(--text-light); font-weight: 500; font-size: 14px; transition: 0.2s; white-space: nowrap; }
        .nav-item:hover { background-color: #f1f5f9; color: var(--accent-teal); transform: translateX(-5px); }
        .nav-item i { width: 20px; text-align: center; font-size: 18px; }
        .sidebar-footer { margin-top: auto; border-top: 1px solid #f1f5f9; padding-top: 20px; display: flex; align-items: center; gap: 10px; }
        .user-avatar { width: 40px; height: 40px; background: #e0f2fe; color: #0284c7; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0; }

        /* --- Main Content --- */
        .main-content { flex: 1; padding: 30px; overflow-y: auto; display: flex; flex-direction: column; gap: 20px; scroll-behavior: smooth; }

        .page-header { display: flex; align-items: center; gap: 15px; margin-bottom: 10px; }
        .back-btn { width: 40px; height: 40px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; color: var(--text-main); box-shadow: 0 2px 5px rgba(0,0,0,0.1); transition: 0.2s; cursor: pointer; flex-shrink: 0; }
        .back-btn:hover { background-color: var(--accent-red); color: white; transform: translateX(3px); }
        .page-title { font-size: 24px; font-weight: 800; color: var(--text-main); }

        /* --- Search --- */
        .search-container { background: white; padding: 15px; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .search-box { position: relative; width: 100%; }
        .search-box input { width: 100%; padding: 12px 40px 12px 15px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 14px; transition: 0.3s; }
        .search-box input:focus { border-color: var(--accent-red); }
        .search-box i { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8; }

        /* --- Deduction Cards --- */
        .deduction-list { display: flex; flex-direction: column; gap: 15px; }

        .deduction-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            border: 1px solid var(--border-color);
            border-right: 5px solid var(--accent-red); /* نوار قرمز سمت راست */
            display: flex;
            align-items: center;
            gap: 20px;
            transition: 0.3s;
            animation: fadeIn 0.4s ease-out;
        }
        .deduction-card:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(225, 29, 72, 0.1); }

        .card-code-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-width: 90px;
            gap: 8px;
        }
        
        .code-text { font-weight: 800; font-size: 18px; color: var(--text-main); font-family: 'Segoe UI', sans-serif; letter-spacing: 1px; }
        
        .feature-badge { 
            background: var(--bg-red-light); 
            color: var(--accent-red); 
            font-size: 13px; 
            padding: 3px 10px; 
            border-radius: 8px; 
            font-weight: 700; 
            font-family: 'Segoe UI', sans-serif;
        }

        .card-desc { font-size: 14px; line-height: 1.8; color: #334155; text-align: justify; border-right: 1px solid #f1f5f9; padding-right: 20px; flex: 1; }

        /* --- Responsive --- */
        @media (max-width: 768px) {
            body { display: block; overflow: auto; }
            .mobile-menu-btn { display: flex; }
            .sidebar { position: fixed; top: 0; right: 0; height: 100vh; width: 280px; transform: translateX(100%); box-shadow: -5px 0 20px rgba(0,0,0,0.1); z-index: 1003; }
            .sidebar.active { transform: translateX(0); }
            .main-content { padding: 80px 20px 20px 20px; height: auto; overflow: visible; }
            
            .deduction-card { flex-direction: column; align-items: flex-start; gap: 10px; padding: 15px; border-right-width: 1px; border-top: 4px solid var(--accent-red); }
            .card-code-section { flex-direction: row; width: 100%; justify-content: space-between; min-width: auto; }
            .card-desc { border-right: none; padding-right: 0; border-top: 1px dashed #f1f5f9; padding-top: 10px; width: 100%; }
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

    <button class="mobile-menu-btn" onclick="toggleMenu()"><i class="fa-solid fa-bars"></i></button>
    <div class="overlay" id="mobileOverlay" onclick="closeMenu()"></div>

    <?php include __DIR__ . '/include/sidebar.php'; ?>

    <main class="main-content">
        <div class="page-header">
            <a href="index" class="back-btn"><i class="fa-solid fa-arrow-right"></i></a>
            <h1 class="page-title">کدهای حذف شده ۱۴۰۴</h1>
        </div>

        <div class="search-container">
            <div class="search-box">
                <i class="fa-solid fa-search"></i>
                <input type="text" id="searchInput" placeholder="جستجو در شرح کد یا شماره کد...">
            </div>
        </div>

        <div class="deduction-list" id="deductionContainer">
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

        // --- CSV Logic ---
        let allData = [];

        async function loadCSV() {
            try {
                // نام فایل deductions.csv باشد
                const response = await fetch('deductions.csv?v=' + new Date().getTime());
                if (!response.ok) throw new Error("فایل CSV یافت نشد");
                const text = await response.text();
                allData = parseCSV(text);
                renderData();
            } catch (error) {
                console.error(error);
                document.getElementById('deductionContainer').innerHTML = 
                    `<div style="text-align:center; color:red; padding:20px;">
                        خطا در بارگذاری فایل CSV.<br>
                        لطفاً نام فایل خود را به <b>deductions.csv</b> تغییر دهید و در کنار فایل HTML بگذارید.
                    </div>`;
            }
        }

        function parseCSV(text) {
            const rows = [];
            let currentRow = [];
            let currentCell = '';
            let inQuote = false;
            
            const cleanText = text.replace(/\r\n/g, '\n').replace(/\r/g, '\n');

            for (let i = 0; i < cleanText.length; i++) {
                const char = cleanText[i];
                if (char === '"') { inQuote = !inQuote; continue; }
                if (char === ',' && !inQuote) {
                    currentRow.push(currentCell.trim()); currentCell = '';
                } else if (char === '\n' && !inQuote) {
                    currentRow.push(currentCell.trim());
                    // حداقل 3 ستون (کد، ویژگی، شرح)
                    if (currentRow.length >= 3) rows.push(currentRow);
                    currentRow = []; currentCell = '';
                } else {
                    currentCell += char;
                }
            }
            if (currentRow.length >= 3) {
                currentRow.push(currentCell.trim());
                rows.push(currentRow);
            }
            return rows.slice(1); // حذف هدر
        }

        function renderData() {
            const container = document.getElementById('deductionContainer');
            const searchVal = document.getElementById('searchInput').value.toLowerCase().trim();
            
            const filtered = allData.filter(row => {
                const code = (row[0] || '').toLowerCase();
                const desc = (row[2] || '').toLowerCase(); // شرح در ستون سوم (ایندکس 2)
                
                if (searchVal && !desc.includes(searchVal) && !code.includes(searchVal)) return false;
                return true;
            });

            container.innerHTML = '';
            
            if (filtered.length === 0) {
                container.innerHTML = `<div style="text-align:center; padding:40px; color:#64748b;">موردی یافت نشد.</div>`;
                return;
            }

            filtered.forEach(row => {
                const code = row[0];
                const feature = row[1];
                const desc = row[2];

                const card = document.createElement('div');
                card.className = 'deduction-card';
                card.innerHTML = `
                    <div class="card-code-section">
                        <span class="code-text">${code}</span>
                        ${feature ? `<span class="feature-badge">${feature}</span>` : ''}
                    </div>
                    <div class="card-desc">
                        ${desc}
                    </div>
                `;
                container.appendChild(card);
            });
        }

        document.getElementById('searchInput').addEventListener('input', renderData);
        document.addEventListener('DOMContentLoaded', loadCSV);

    </script>
</body>
</html>