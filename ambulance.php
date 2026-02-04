<?php
include_once __DIR__ . '/include/auth.php';
// دیتای جیسون که فرستادید را اینجا در متغیر PHP قرار می‌دهیم تا به JS پاس بدهیم
$jsonData = '[{"id":1,"code":"988845","amb_type":"b","city":"tehran","dolati":10050000,"charity":15190000,"privat":22400000},{"id":2,"code":"988850","amb_type":"b","city":"bigcity","dolati":8550000,"charity":13175000,"privat":17600000},{"id":3,"code":"_","amb_type":"b","city":"ostan","dolati":5700000,"charity":8215000,"privat":12800000},{"id":4,"code":"988855","amb_type":"b","city":"shahrestan","dolati":4725000,"charity":7052500,"privat":10720000},{"id":5,"code":"distance","amb_type":"b","city":"_","dolati":108000,"charity":158100,"privat":200000},{"id":6,"code":"stop","amb_type":"b","city":"_","dolati":1845000,"charity":2480000,"privat":2880000},{"id":7,"code":"988800","amb_type":"a","city":"tehran","dolati":9450000,"charity":13175000,"privat":20800000},{"id":8,"code":"988805","amb_type":"a","city":"bigcity","dolati":7125000,"charity":11780000,"privat":16000000},{"id":9,"code":"_","amb_type":"a","city":"ostan","dolati":4500000,"charity":7750000,"privat":11200000},{"id":10,"code":"988810","amb_type":"a","city":"shahrestan","dolati":4200000,"charity":6510000,"privat":10080000},{"id":11,"code":"distance","amb_type":"a","city":"_","dolati":82500,"charity":124000,"privat":147200},{"id":12,"code":"stop","amb_type":"a","city":"_","dolati":1845000,"charity":2480000,"privat":2880000}]';
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>درآمد یار - تعرفه آمبولانس</title>
    
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">

    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="fonts/vazirmatn.css">
    <link rel="stylesheet" href="style/ambulance.css?v=<?php echo filemtime(__DIR__ . '/style/ambulance.css'); ?>">
    <style>
        /* استایل‌های اضافه شده برای اینپوت‌های جدید */
        .input-row {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        .input-group {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .input-group label {
            font-size: 0.85rem;
            margin-bottom: 5px;
            color: #555;
        }
        .custom-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: inherit;
            font-size: 1rem;
        }
        /* مخفی کردن پیش‌فرض پارامترهای برون شهری */
        #outcity_parameter {
            display: none;
            border-top: 1px dashed #ccc;
            padding-top: 15px;
            margin-top: 15px;
        }
    </style>
</head>
<body>

    <button class="mobile-menu-btn" onclick="toggleMenu()"><i class="fa-solid fa-bars"></i></button>
    <div class="overlay" id="mobileOverlay" onclick="closeMenu()"></div>

    <?php include __DIR__ . '/include/sidebar.php'; ?>

    <main class="main-content">
        <div class="page-header">
            <a href="index" class="back-btn"><i class="fa-solid fa-arrow-right"></i></a>
            <h1 class="page-title">تعرفه آمبولانس ۱۴۰۴</h1>
        </div>

        <div class="calculator-layout">
            
            <div class="config-card">
                
                <div class="config-group">
                    <div class="group-label"><i class="fa-solid fa-truck-medical"></i> نوع آمبولانس</div>
                    <div class="options-container">
                        <label>
                            <input type="radio" name="type" value="1" class="option-radio calc-trigger">
                            <div class="option-label">تیپ A</div>
                        </label>
                        <label>
                            <input type="radio" name="type" value="0" class="option-radio calc-trigger" checked>
                            <div class="option-label">تیپ B</div>
                        </label>
                    </div>
                </div>

                <div class="config-group">
                    <div class="group-label"><i class="fa-solid fa-route"></i> محدوده تردد</div>
                    <div class="options-container">
                        <label>
                            <input type="radio" name="scope" value="0" class="option-radio calc-trigger" checked>
                            <div class="option-label">درون شهری</div>
                        </label>
                        <label>
                            <input type="radio" name="scope" value="1" class="option-radio calc-trigger">
                            <div class="option-label">برون شهری</div>
                        </label>
                    </div>

                    <div id="outcity_parameter">
                        <div class="input-row">
                            <div class="input-group">
                                <label>مسافت (کیلومتر)</label>
                                <input type="number" id="edt_kilameter" class="custom-input calc-trigger" placeholder="مثلا 100">
                            </div>
                            <div class="input-group">
                                <label>توقف (ساعت)</label>
                                <input type="number" id="edt_stop" class="custom-input calc-trigger" placeholder="مثلا 2">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="config-group">
                    <div class="group-label"><i class="fa-solid fa-map-location-dot"></i> موقعیت جغرافیایی</div>
                    <div class="options-container">
                        <label>
                            <input type="radio" name="location" value="0" class="option-radio calc-trigger">
                            <div class="option-label">تهران</div>
                        </label>
                        <label>
                            <input type="radio" name="location" value="1" class="option-radio calc-trigger">
                            <div class="option-label">کلان شهر</div>
                        </label>
                        <label>
                            <input type="radio" name="location" value="2" class="option-radio calc-trigger" checked>
                            <div class="option-label">مرکز استان</div>
                        </label>
                        <label>
                            <input type="radio" name="location" value="3" class="option-radio calc-trigger">
                            <div class="option-label">شهرستان</div>
                        </label>
                    </div>
                </div>

                <div class="config-group">
                    <div class="group-label"><i class="fa-solid fa-building-columns"></i> نوع مرکز</div>
                    <div class="options-container">
                        <label>
                            <input type="radio" name="sector" value="0" class="option-radio calc-trigger" checked>
                            <div class="option-label">دولتی</div>
                        </label>
                        <label>
                            <input type="radio" name="sector" value="1" class="option-radio calc-trigger">
                            <div class="option-label">خیریه/عمومی</div>
                        </label>
                        <label>
                            <input type="radio" name="sector" value="2" class="option-radio calc-trigger">
                            <div class="option-label">خصوصی</div>
                        </label>
                    </div>
                </div>

            </div>

            <div class="result-card">
                <div class="price-display">
                    <div class="price-label">مبلغ قابل پرداخت</div>
                    <div class="price-amount" id="final_price">0</div>
                    <div class="price-unit">ریال</div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 0;">
                    <div class="info-row">
                        <span class="info-label">تعرفه پایه:</span>
                        <span class="info-value" id="base_price_display">0</span>
                    </div>
                    <div class="info-row" id="detail_dist_row" style="display:none">
                        <span class="info-label">هزینه مسافت:</span>
                        <span class="info-value" id="dist_price_display">0</span>
                    </div>
                </div>

                <button class="full-table-btn">
                    <i class="fa-solid fa-table-list"></i>
                    مشاهده جدول کامل (بزودی)
                </button>
            </div>

        </div>
    </main>

    <script>
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');
        function toggleMenu() { sidebar.classList.toggle('active'); mobileOverlay.classList.toggle('active'); }
        function closeMenu() { sidebar.classList.remove('active'); mobileOverlay.classList.remove('active'); }

        // --- بخش منطق محاسبات ---

        // 1. دریافت دیتای دیتابیس از PHP
        const rawData = <?php echo $jsonData; ?>;

        // آرایه‌ها برای نگهداری قیمت‌ها (دقیقا مثل جاوا)
        let matrixA = Array(4).fill().map(() => Array(3).fill(0));
        let matrixB = Array(4).fill().map(() => Array(3).fill(0));
        let outA = [0, 0, 0];
        let stopA = [0, 0, 0];
        let outB = [0, 0, 0];
        let stopB = [0, 0, 0];

        // 2. پردازش دیتا و پر کردن ماتریس‌ها (Load Data)
        function initData() {
            if (!rawData || rawData.length === 0) return;

            // پر کردن ماتریس تیپ B (ایندکس 0 تا 3 در JSON)
            for (let i = 0; i < 4; i++) {
                matrixB[i][0] = parseInt(rawData[i].dolati);
                matrixB[i][1] = parseInt(rawData[i].charity);
                matrixB[i][2] = parseInt(rawData[i].privat);
            }
            // Out B (ایندکس 4)
            outB[0] = parseInt(rawData[4].dolati);
            outB[1] = parseInt(rawData[4].charity);
            outB[2] = parseInt(rawData[4].privat);
            // Stop B (ایندکس 5)
            stopB[0] = parseInt(rawData[5].dolati);
            stopB[1] = parseInt(rawData[5].charity);
            stopB[2] = parseInt(rawData[5].privat);

            // پر کردن ماتریس تیپ A (ایندکس 6 تا 9 در JSON)
            for (let i = 0; i < 4; i++) {
                // i+6 چون دیتاهای تیپ A از ایندکس 6 شروع می‌شوند
                matrixA[i][0] = parseInt(rawData[i + 6].dolati);
                matrixA[i][1] = parseInt(rawData[i + 6].charity);
                matrixA[i][2] = parseInt(rawData[i + 6].privat);
            }
            // Out A (ایندکس 10)
            outA[0] = parseInt(rawData[10].dolati);
            outA[1] = parseInt(rawData[10].charity);
            outA[2] = parseInt(rawData[10].privat);
            // Stop A (ایندکس 11)
            stopA[0] = parseInt(rawData[11].dolati);
            stopA[1] = parseInt(rawData[11].charity);
            stopA[2] = parseInt(rawData[11].privat);
        }

        // 3. تابع اصلی محاسبه (معادل calculatePrice در جاوا)
        function calculatePrice() {
            // دریافت مقادیر انتخاب شده از اینپوت‌ها
            const selectedTypeIndex = parseInt(document.querySelector('input[name="type"]:checked').value); // 0=B, 1=A
            const selectedScopeIndex = parseInt(document.querySelector('input[name="scope"]:checked').value); // 0=Inner, 1=Outer
            const selectedLocationIndex = parseInt(document.querySelector('input[name="location"]:checked').value);
            const selectedOwnershipIndex = parseInt(document.querySelector('input[name="sector"]:checked').value);

            // مدیریت نمایش فیلد برون شهری
            const outCityParamDiv = document.getElementById('outcity_parameter');
            const distDetailRow = document.getElementById('detail_dist_row');
            
            if (selectedScopeIndex === 1) {
                outCityParamDiv.style.display = 'block';
                distDetailRow.style.display = 'flex';
            } else {
                outCityParamDiv.style.display = 'none';
                distDetailRow.style.display = 'none';
                // پاک کردن مقادیر در صورت مخفی شدن (اختیاری)
                // document.getElementById('edt_kilameter').value = '';
                // document.getElementById('edt_stop').value = '';
            }

            // انتخاب ماتریس صحیح
            let currentMatrix = (selectedTypeIndex === 0) ? matrixB : matrixA;
            let currentOut = (selectedTypeIndex === 0) ? outB : outA;
            let currentStop = (selectedTypeIndex === 0) ? stopB : stopA;

            // محاسبه قیمت پایه
            let basePrice = currentMatrix[selectedLocationIndex][selectedOwnershipIndex];
            let finalPrice = 0;

            if (selectedScopeIndex === 0) {
                // درون شهری
                finalPrice = basePrice;
            } else {
                // برون شهری
                let distance = 0;
                let stop = 0;
                
                const distVal = document.getElementById('edt_kilameter').value;
                const stopVal = document.getElementById('edt_stop').value;

                if (distVal) distance = parseInt(distVal);
                if (stopVal) stop = parseInt(stopVal);

                let factor = 0.75;
                let pricePerKm = currentOut[selectedOwnershipIndex];
                let pricePerStop = currentStop[selectedOwnershipIndex];

                finalPrice = (basePrice * factor) + (distance * pricePerKm) + (stop * pricePerStop);
                
                // بروزرسانی جزئیات (نمایشی)
                document.getElementById('dist_price_display').innerText = formatMoney((distance * pricePerKm) + (stop * pricePerStop));
            }

            // نمایش در UI
            document.getElementById('final_price').innerText = formatMoney(finalPrice);
            document.getElementById('base_price_display').innerText = formatMoney(basePrice);
        }

        // تابع کمکی برای فرمت سه رقم سه رقم پول
        function formatMoney(amount) {
            return Math.round(amount).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        // 4. راه‌اندازی و اتصال Listener ها
        document.addEventListener('DOMContentLoaded', () => {
            initData(); // بارگذاری ماتریس‌ها

            // اتصال رویداد change به تمام دکمه‌های رادیویی و اینپوت‌ها
            const inputs = document.querySelectorAll('.calc-trigger');
            inputs.forEach(input => {
                input.addEventListener('change', calculatePrice);
                input.addEventListener('input', calculatePrice); // برای تایپ کردن در تکست باکس
            });

            // محاسبه اولیه
            calculatePrice();
        });

    </script>

</body>
</html>