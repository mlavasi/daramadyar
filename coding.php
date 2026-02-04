<?php
include_once __DIR__ . '/include/config.php';
include_once __DIR__ . '/include/db.php';
include_once __DIR__ . '/include/auth.php';

if ($is_premium) {
    $query = "SELECT * FROM general_code";
} else {
    $query = "SELECT * FROM general_code LIMIT 10";
}
$items = $db->query($query);
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>درآمد یار - کدینگ جامع</title>
    
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">

    <!-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> -->

    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="fonts/vazirmatn.css">
    <link rel="stylesheet" href="style/coding.css?v=<?php echo filemtime(__DIR__ . '/style/coding.css'); ?>">

    <style>
        :root {
            --primary-bg: #f3f4f6;
            --sidebar-bg: #ffffff;
            --text-main: #1e293b;
            --text-light: #64748b;
            --accent-teal: #0ea5e9;
            --accent-slate: #475569; /* رنگ سربی مخصوص این صفحه */
            --border-color: #e2e8f0;
            --transition-speed: 0.3s;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Vazirmatn', sans-serif; text-decoration: none; outline: none; -webkit-tap-highlight-color: transparent; }

        body { background-color: var(--primary-bg); color: var(--text-main); display: flex; height: 100vh; overflow: hidden; }

        /* --- Mobile Menu --- */
        .mobile-menu-btn { display: none; position: fixed; top: 20px; right: 20px; z-index: 1001; background: white; border: none; width: 45px; height: 45px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); color: var(--accent-slate); font-size: 20px; cursor: pointer; align-items: center; justify-content: center; }
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
        .back-btn:hover { background-color: var(--accent-slate); color: white; transform: translateX(3px); }
        .page-title { font-size: 24px; font-weight: 800; color: var(--text-main); }

        /* --- Search --- */
        .search-container { background: white; padding: 15px; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .search-box { position: relative; width: 100%; }
        .search-box input { width: 100%; padding: 12px 40px 12px 15px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 14px; transition: 0.3s; }
        .search-box input:focus { border-color: var(--accent-slate); }
        .search-box i { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8; }

        /* --- Coding Grid --- */
        .coding-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px; }

        .code-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            border: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            transition: 0.3s;
            animation: fadeIn 0.4s ease-out;
            cursor: pointer; /* برای حس تعاملی */
        }
        .code-card:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(71, 85, 105, 0.1); border-color: var(--accent-slate); }

        .card-text-section { flex: 1; }
        .card-desc { font-size: 14px; line-height: 1.8; color: var(--text-main); font-weight: 500; margin: 0; text-align: justify; }

        .card-code-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #f1f5f9;
            padding: 10px 15px;
            border-radius: 12px;
            min-width: 100px;
            flex-shrink: 0;
        }
        
        .code-icon { font-size: 20px; color: var(--accent-slate); margin-bottom: 5px; }
        .code-number { font-weight: 800; font-size: 18px; color: var(--text-main); font-family: 'Segoe UI', sans-serif; letter-spacing: 1px; }

        /* --- Responsive --- */
        @media (max-width: 768px) {
            body { display: block; overflow: auto; }
            .mobile-menu-btn { display: flex; }
            .sidebar { position: fixed; top: 0; right: 0; height: 100vh; width: 280px; transform: translateX(100%); box-shadow: -5px 0 20px rgba(0,0,0,0.1); z-index: 1003; }
            .sidebar.active { transform: translateX(0); }
            .main-content { padding: 80px 20px 20px 20px; height: auto; overflow: visible; }
            .coding-grid { grid-template-columns: 1fr; }
            .code-card { flex-direction: column-reverse; align-items: stretch; text-align: center; gap: 15px; }
            .card-code-section { flex-direction: row; justify-content: space-between; min-width: auto; }
            .code-icon { margin-bottom: 0; }
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
            <h1 class="page-title">کدینگ جامع ۱۴۰۴</h1>
        </div>

        <div class="search-container">
            <div class="search-box">
                <i class="fa-solid fa-search"></i>
                <input type="text" id="searchInput" placeholder="جستجو در شرح کد یا شماره کد...">
            </div>
        </div>

        <div class="coding-grid" id="codingContainer">
            <div style="text-align:center; padding:40px; color:#64748b; grid-column:1/-1;">
                <i class="fa-solid fa-circle-notch fa-spin fa-2x"></i><br><br>
                در حال دریافت اطلاعات فایل ...
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
                document.getElementById('codingContainer').innerHTML = 
                    `<div style="text-align:center; color:red; padding:20px; grid-column:1/-1;">
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
                    desc: "<?php echo str_replace(array("\r", "\n"),' ',$item['title']) ?>",              
                });
                <?php
                }
            }
            ?>

            return result;
        }

        function renderData() {
            const container = document.getElementById('codingContainer');
            const searchVal = document.getElementById('searchInput').value.toLowerCase().trim();
            
            const filtered = allData.filter(row => {
                // row[0]: کدملی, row[1]: شرح
                const code = (row.code || '').toLowerCase();
                const desc = (row.desc || '').toLowerCase();
                
                if (searchVal && !desc.includes(searchVal) && !code.includes(searchVal)) return false;
                return true;
            });

            container.innerHTML = '';
            
            if (filtered.length === 0) {
                container.innerHTML = `<div style="text-align:center; padding:40px; color:#64748b; grid-column:1/-1;">موردی یافت نشد.</div>`;
                return;
            }

            // نمایش ۵۰ تای اول
            filtered.slice(0, 50).forEach(row => {
                const card = document.createElement('div');
                card.className = 'code-card';
                
                const code = row.code;
                const desc = row.desc;

                // کپی کردن کد با کلیک روی کارت
                card.onclick = () => {
                    navigator.clipboard.writeText(code);
                    // (اختیاری) می‌توان اینجا پیغام کپی شدن داد
                };

                card.innerHTML = `
                    <div class="card-text-section">
                        <p class="card-desc">${desc}</p>
                    </div>
                    <div class="card-code-section">
                        <i class="fa-solid fa-file-invoice code-icon"></i>
                        <span class="code-number">${code}</span>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        document.getElementById('searchInput').addEventListener('input', renderData);
        document.addEventListener('DOMContentLoaded', loadData);

    </script>
</body>
</html>