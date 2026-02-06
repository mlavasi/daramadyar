<?php
include_once __DIR__ . '/include/config.php';
include_once __DIR__ . '/include/auth.php';

?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>درآمد یار -ارزش نسبی خدمات سلامت</title>
    <meta http-equiv="Cache-Control" content="max-age=3600">
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="fonts/vazirmatn.css">
    <link rel="stylesheet" href="style/relative_value.css?v=<?php echo filemtime(__DIR__ . '/style/relative_value.css'); ?>">

    <style>
    /* کلاس برای غیرفعال کردن تب‌ها */
    .disabled-area {
        opacity: 0.5;
        pointer-events: none; /* جلوگیری از کلیک */
        filter: grayscale(100%);
    }
    /* استایل اینپوت قفل شده */
    input:disabled {
        background-color: #f1f5f9;
        cursor: not-allowed;
    }
</style>

</head>
<body>

    <button class="mobile-menu-btn" onclick="toggleMenu()"><i class="fa-solid fa-bars"></i></button>
    <div class="overlay" id="mobileOverlay" onclick="closeMenu()"></div>

    <?php include __DIR__ . '/include/sidebar.php'; ?>

    <div class="modal-overlay" id="detailModal" onclick="closeDetail(event)">
        <div class="modal-content" onclick="event.stopPropagation()">
            <div class="modal-header">
                <div class="modal-title" id="modalTitle">جزئیات خدمت</div>
                <button class="close-btn" onclick="closeDetail()">&times;</button>
            </div>
            <div class="modal-body" id="modalBody"></div>
        </div>
    </div>

    <main class="main-content">
        <div class="page-header">
            <a href="index" class="back-btn"><i class="fa-solid fa-arrow-right"></i></a>
            <h1 class="page-title">جدول ارزش نسبی خدمات سلامت ۱۴۰۴</h1>
        </div>

        <div class="filter-section">
            <div class="input-wrapper">
                <i class="fa-solid fa-microphone" style="position: absolute; right: 15px; color: #64748b;"></i>
                <input type="text" id="searchInput" 
                    placeholder="<?php echo $is_premium ? 'جستجو در نام یا کد خدمت...' : 'برای جستجو اشتراک تهیه کنید'; ?>" 
                    <?php echo !$is_premium ? 'disabled' : ''; ?> 
                    autocomplete="off">
            </div>

            <div class="chips-row <?php echo !$is_premium ? 'disabled-area' : ''; ?>">
                <div class="filter-chip active" onclick="applyFilter('all', this)">همگی</div>
                <div class="filter-chip" onclick="applyFilter('global', this)">گلوبال</div>
                <div class="filter-chip" onclick="applyFilter('star', this)">ستاره دار</div>
                <div class="filter-chip" onclick="applyFilter('mark', this)">نشان‌دار</div>
                <div class="filter-chip" onclick="applyFilter('c_code', this)">کد C</div>
                <div class="filter-chip" onclick="applyFilter('anesthesia', this)">بیهوشی</div>
                <span class="result-count" id="resultCount">...</span>
            </div>


        </div>

        <div class="rv-list" id="resultsContainer">
            </div>
        
        <div id="loadingIndicator" style="text-align:center; padding: 20px; display: none;">
            <i class="fa-solid fa-circle-notch fa-spin fa-2x" style="color: #64748b;"></i>
        </div>
        <div id="endOfResults" style="text-align:center; padding: 20px; color: #64748b; display: none;">
            پایان نتایج
        </div>

    </main>

    <script>

        // انتقال وضعیت دسترسی از PHP به JS
        const hasFullAccess = <?php echo $is_premium ? 'true' : 'false'; ?>;

        // --- تنظیمات اولیه ---
        let currentPage = 1;
        let currentSearch = '';
        let currentFilter = 'all';
        let isLoading = false;
        let hasMoreData = true;
        let allLoadedData = []; // برای ذخیره دیتا جهت نمایش در مودال

        // --- UI Logic (Sidebar & Modal) ---
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');
        const detailModal = document.getElementById('detailModal');
        const modalBody = document.getElementById('modalBody');
        const modalTitle = document.getElementById('modalTitle');

        function toggleMenu() { sidebar.classList.toggle('active'); mobileOverlay.classList.toggle('active'); }
        function closeMenu() { sidebar.classList.remove('active'); mobileOverlay.classList.remove('active'); }
        
        function closeDetail(event) {
            if(event && event.target !== detailModal) return; 
            detailModal.classList.remove('open');
        }

        // --- Modal Logic ---
        function openDetail(indexInArray) {
            const item = allLoadedData[indexInArray];
            if(!item) return;

            const row = (label, val) => `
                <div class="detail-row">
                    <span class="detail-label">${label}</span>
                    <span class="detail-value">${val || '-'}</span>
                </div>`;
            const section = (title) => `<div class="detail-group-title">${title}</div>`;

            let content = '';
            
            // 1. اطلاعات پایه
            content += section('مشخصات پایه');
            content += row('کد ملی', item.code);
            content += row('ویژگی کد', item.attr);
            content += row('شرح خدمت', item.desc);
            content += row('توضیحات تکمیلی', item.details);
            content += row('نوع کد', item.type);
            
            // 2. اجزاء ارزش نسبی
            content += `<div class="detail-group">`;
            content += section('اجزاء ارزش نسبی');
            content += row('جزء کل', item.total_part);
            content += row('جزء حرفه‌ای', item.pro_part);
            content += row('جزء فنی', item.tech_part);
            content += row('جزء بیهوشی', item.anesthesia);
            content += `</div>`;

            // 3. تعرفه ها
            content += `<div class="detail-group">`;
            content += section('تعرفه‌ها (ریال)');
            content += row('دولتی سرپایی', item.gov_out);
            content += row('دولتی بستری', item.gov_in);
            content += row('دولتی غیرتمام وقت', item.gov_part_time);
            content += row('خصوصی', item.private);
            content += row('عمومی غیردولتی', item.public_non_gov);
            content += row('خیریه', item.charity);
            content += `</div>`;

            // 4. وضعیت ها
            content += `<div class="detail-group">`;
            content += section('سایر اطلاعات');
            content += row('گلوبال', item.global === '1' ? 'بله' : 'خیر');
            content += row('نیاز به پرونده', item.file === '1' ? 'بله' : 'خیر');
            content += row('فاقد پوشش', item.no_cover === '1' ? 'بله' : 'خیر');
            content += row('قیمت داده خام آزمایشگاه', item.lab_price);
            content += `</div>`;

            modalTitle.textContent = `جزئیات کد: ${item.code}`;
            modalBody.innerHTML = content;
            detailModal.classList.add('open');
        }

        // --- Core Data Logic ---
        
        // تابع اصلی دریافت اطلاعات
        async function fetchData(reset = false) {
            if (isLoading || (!hasMoreData && !reset)) return;
            
            isLoading = true;
            document.getElementById('loadingIndicator').style.display = 'block';

            if (reset) {
                currentPage = 1;
                allLoadedData = [];
                document.getElementById('resultsContainer').innerHTML = '';
                document.getElementById('endOfResults').style.display = 'none';
                hasMoreData = true;
                window.scrollTo(0, 0);
            }

            try {
                // ارسال درخواست به سرور
                const url = `fetch_data.php?page=${currentPage}&search=${encodeURIComponent(currentSearch)}&filter=${currentFilter}`;
                const response = await fetch(url);
                const json = await response.json();

                if (json.status === 'success') {
                    const newData = json.data;
                    
                    if (newData.length < 50) {
                        hasMoreData = false;
                        document.getElementById('endOfResults').style.display = 'block';
                    }

                    if (newData.length > 0) {
                        renderItems(newData);
                        currentPage++;
                    } else if (reset) {
                        document.getElementById('resultsContainer').innerHTML = '<div style="text-align:center; padding:30px; color:#64748b;">موردی یافت نشد.</div>';
                    }
                }
            } catch (error) {
                console.error("Error fetching data:", error);
            } finally {
                isLoading = false;
                document.getElementById('loadingIndicator').style.display = 'none';
            }
        }

        // رندر کردن کارت‌ها
        function renderItems(items) {
            const container = document.getElementById('resultsContainer');
            const startIndex = allLoadedData.length;
            
            // اضافه کردن دیتای جدید به آرایه کلی
            allLoadedData = [...allLoadedData, ...items];
            document.getElementById('resultCount').textContent = `نمایش ${allLoadedData.length.toLocaleString('fa-IR')} مورد`;

            items.forEach((item, index) => {
                const globalIndex = startIndex + index;
                const card = document.createElement('div');
                card.className = 'rv-card';
                card.onclick = () => openDetail(globalIndex);

                // لاجیک Badges
                let badgesHtml = '';
                if (item.file === '1') badgesHtml += `<span class="tag-blue">پرونده</span>`;
                if (item.global === '1') badgesHtml += `<span class="tag-blue">گلوبال</span>`;
                if (item.type && item.type !== '0' && item.type !== 'nan') badgesHtml += `<span class="tag-gray">${item.type.toUpperCase()}</span>`;

                let attrHtml = '';
                if (item.attr && item.attr.trim() !== '' && item.attr !== 'nan') {
                    attrHtml = `<span class="attr-badge">${item.attr}</span>`;
                }

                // آماده سازی خلاصه توضیحات (اگر باشد)
                let detailText = item.details ? item.details.replace(/^"|"$/g, '').trim() : '';
                let hasDetails = (detailText && detailText.toLowerCase() !== 'nan' && detailText.length > 0);

                // ابتدا مطمئن می‌شویم که کد به صورت رشته است
                // اگر null بود رشته خالی می گذارد، در غیر این صورت به رشته تبدیل می‌کند
                const codeStr = String(item.code || '');

                //حالا عملیات replace را روی codeStr انجام دهید
                // تبدیل اعداد به فارسی برای نمایش
                const persianCode = codeStr.replace(/\d/g, d => '۰１２３４５６７８９'[d]);

                card.innerHTML = `
                    <div class="card-header-row">
                        <div class="code-badge">
                            کد: ${persianCode}
                            ${attrHtml}
                            <i class="fa-regular fa-copy" onclick="copyCode(event, '${item.code}')" title="کپی" style="margin-right:5px; color:#cbd5e1;"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <p>${item.desc}</p>
                        ${hasDetails ? `<p style="font-size:13px; color:#64748b; margin-top:8px; background:#f8fafc; padding:10px; border-radius:8px; border:1px dashed #e2e8f0;">${detailText}</p>` : ''}
                    </div>
                    <div style="display:flex; gap:5px; flex-wrap:wrap; margin-top:10px;">
                        ${badgesHtml}
                    </div>
                `;
                container.appendChild(card);
            });
        }

        // --- Search & Filter Handlers ---
        
        let debounceTimer;
        function handleSearch() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                currentSearch = document.getElementById('searchInput').value;
                fetchData(true); // ریست کردن و جستجوی جدید
            }, 600); // 600ms تاخیر برای جلوگیری از درخواست‌های رگباری
        }

        function applyFilter(filterType, element) {
            // آپدیت UI
            document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
            element.classList.add('active');
            
            // آپدیت لاجیک
            currentFilter = filterType;
            fetchData(true);
        }

        function copyCode(event, code) {
            event.stopPropagation();
            navigator.clipboard.writeText(code);
        }

        // --- Infinite Scroll Implementation ---
        
        // استفاده از IntersectionObserver برای تشخیص رسیدن به پایین صفحه
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting && hasMoreData && !isLoading) {
                fetchData(false); // لود کردن صفحه بعدی
            }
        }, { threshold: 1.0 });


        // --- Infinite Scroll Logic ---
        window.addEventListener('scroll', () => {
            // شرط مهم: اگر کاربر دسترسی کامل ندارد، اصلا تابع لود بعدی را صدا نزن
            if (!hasFullAccess) return;

            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 300) {
                fetchData(false); 
            }
        });

        window.addEventListener('DOMContentLoaded', () => {
            fetchData(true); // لود اولیه
            // نمایش پیام هشدار برای کاربران محدود (اختیاری)
        if (!hasFullAccess) {
            const container = document.getElementById('resultsContainer');
            // یک پیام زیر لیست اضافه میکنیم که بدونه فقط 50 تا رو میبینه
            const msg = document.createElement('div');
            msg.style.cssText = "text-align:center; padding:20px; color:#ef4444; background:#fef2f2; margin-top:20px; border-radius:8px;";
            msg.innerHTML = "کاربر گرامی، شما در حال مشاهده نسخه نمایشی (۵۰ رکورد اول) هستید.<br>برای مشاهده تمام لیست و جستجو، لطفا اشتراک تهیه کنید.";
            // چون fetchData غیرهمگام است، بهتر است این پیام را در خود تابع fetchData بعد از رندر اضافه کنید یا اینجا به صورت استاتیک زیر لیست باشد.
            document.querySelector('main').appendChild(msg);
        }
        });

    </script>
</body>
</html>