<?php
include_once __DIR__ . '/include/auth.php';
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لیست جامع هتلینگ و خدمات پزشکی</title>
    

    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="fonts/vazirmatn.css">
    <link rel="stylesheet" href="style/hoteling.css?v=<?php echo filemtime(__DIR__ . '/style/hoteling.css'); ?>">

    
</head>
<body>

    <button class="mobile-menu-btn" onclick="toggleMenu()"><i class="fa-solid fa-bars"></i></button>
    <div class="overlay" id="mobileOverlay" onclick="closeMenu()"></div>

    <?php include __DIR__ . '/include/sidebar.php'; ?>

    <main class="main-content">
        <header class="page-header">
            <h1 class="page-title">اقلام فاقد تعهد بیمه و یارانه سلامت</h1>
        </header>

        <section class="notice-card">
            <h4><i class="fa-solid fa-circle-exclamation"></i> نکات حیاتی و استثنائات تعهد:</h4>
            <ul>
                <li>• اقلام مشمول ۶٪ خدمات پرستاری در صورت <b>بستری موقت (تحت نظر)</b> در تعهد بیمه پایه می‌باشد.</li>
                <li>• لوله‌های خرطومی ونتیلاتور در بخش‌ها دارای تعهد بیمه بوده و قابل پرداخت است.</li>
                <li>• موارد ۱ تا ۷ و ۱۹ بند الف در صورت استفاده در <b>اتاق عمل</b>، در تعهد بیمه می‌باشد.</li>
                <li>• رابط لوله تراشه، ماسک بیهوشی، نوار کیت گلوکومتر و اسپکولوم زنان مورد تعهد می‌باشند.</li>
            </ul>
        </section>

        <div class="cards-grid">
            
            <div class="info-card color-a">
                <div class="card-top-accent"></div>
                <div class="card-header">
                    <i class="fa-solid fa-user-nurse"></i>
                    <h3>الف) مشمول ۶٪ خدمات پرستاری (هتلینگ)</h3>
                </div>
                <div class="item-list">
                    <div class="item-row"><div class="item-dot"></div>انواع سرنگ (در بخش بستری)</div>
                    <div class="item-row"><div class="item-dot"></div>انواع سرسوزن (در بخش بستری)</div>
                    <div class="item-row"><div class="item-dot"></div>انواع گاز ساده و خط‌دار استریل و غیراستریل</div>
                    <div class="item-row"><div class="item-dot"></div>انواع گاز طبی O2 و ...</div>
                    <div class="item-row"><div class="item-dot"></div>انواع باند (در بخش بستری)</div>
                    <div class="item-row"><div class="item-dot"></div>پنبه (در بخش بستری)</div>
                    <div class="item-row"><div class="item-dot"></div>انواع چسب (در بخش بستری)</div>
                    <div class="item-row"><div class="item-dot"></div>ملحفه یکبار مصرف و پارچه‌ای</div>
                    <div class="item-row"><div class="item-dot"></div>دستکش لاتکس، وینیل و کم‌پودر</div>
                    <div class="item-row"><div class="item-dot"></div>پلاستیک سفره‌ای (روکش تخت)</div>
                    <div class="item-row"><div class="item-dot"></div>دروشیت</div>
                    <div class="item-row"><div class="item-dot"></div>شیلد چشمی</div>
                    <div class="item-row"><div class="item-dot"></div>سرجی فیکس</div>
                    <div class="item-row"><div class="item-dot"></div>دستکش نایلونی</div>
                    <div class="item-row"><div class="item-dot"></div>آستین</div>
                    <div class="item-row"><div class="item-dot"></div>نخ وزنه</div>
                    <div class="item-row"><div class="item-dot"></div>روتختی (کاور تخت) یکبار مصرف و پارچه‌ای</div>
                    <div class="item-row"><div class="item-dot"></div>مچ‌بند شناسایی (نوزاد و بزرگسال)</div>
                    <div class="item-row"><div class="item-dot"></div>انواع محلول ضدعفونی (الکل، بتادین، ساولن و...)</div>
                    <div class="item-row"><div class="item-dot"></div>رکاب</div>
                    <div class="item-row"><div class="item-dot"></div>اریگیتور (وسیله شستشوی زخم)</div>
                    <div class="item-row"><div class="item-dot"></div>روبالشتی پارچه‌ای و یکبار مصرف</div>
                    <div class="item-row"><div class="item-dot"></div>چست لید (ICU و CCU)</div>
                </div>
            </div>

            <div class="info-card color-b">
                <div class="card-top-accent"></div>
                <div class="card-header">
                    <i class="fa-solid fa-scissors"></i>
                    <h3>ب) مشمول جزء فنی اتاق عمل</h3>
                </div>
                <div class="item-list">
                    <div class="item-row"><div class="item-dot"></div>آستین</div>
                    <div class="item-row"><div class="item-dot"></div>پیش‌بند</div>
                    <div class="item-row"><div class="item-dot"></div>شان پارچه‌ای و یکبار مصرف (استریل و غیر استریل)</div>
                    <div class="item-row"><div class="item-dot"></div>گان بیمار</div>
                    <div class="item-row"><div class="item-dot"></div>کلاه بیمار</div>
                    <div class="item-row"><div class="item-dot"></div>کلاه، ماسک و گان جراحی</div>
                    <div class="item-row"><div class="item-dot"></div>استریل درپ جراحی</div>
                    <div class="item-row"><div class="item-dot"></div>کاور کفش (پاپوش)</div>
                    <div class="item-row"><div class="item-dot"></div>لوله‌های خرطومی و رابط ونتیلاتور (در اتاق عمل)</div>
                    <div class="item-row"><div class="item-dot"></div>کاور شیلد اسکوپی</div>
                    <div class="item-row"><div class="item-dot"></div>کاور میکروسکوپ</div>
                    <div class="item-row"><div class="item-dot"></div>کاور دوربین</div>
                    <div class="item-row"><div class="item-dot"></div>لوله‌ها و رابط‌های دستگاه ساکشن (در اتاق عمل)</div>
                    <div class="item-row"><div class="item-dot"></div>مارکر جراحی</div>
                    <div class="item-row"><div class="item-dot"></div>پگ اعمال جراحی</div>
                    <div class="item-row"><div class="item-dot"></div>عینک محافظ</div>
                    <div class="item-row"><div class="item-dot"></div>قلم کوتر</div>
                    <div class="item-row"><div class="item-dot"></div>پلیت کوتر</div>
                    <div class="item-row"><div class="item-dot"></div>انواع ست‌های جراحی (ارتوپدی، جنرال، لامینکتومی و...)</div>
                    <div class="item-row"><div class="item-dot"></div>متعلقات دستگاه TUR (پروپ و لوپ)</div>
                    <div class="item-row"><div class="item-dot"></div>گاید لوله تراشه</div>
                    <div class="item-row"><div class="item-dot"></div>گاید دهانی</div>
                    <div class="item-row"><div class="item-dot"></div>گازهای طبی (O2, N2O و...)</div>
                    <div class="item-row"><div class="item-dot"></div>تیپیس ونچوری</div>
                    <div class="item-row"><div class="item-dot"></div>رابط پلیت کوتر</div>
                    <div class="item-row"><div class="item-dot"></div>دستگاه مانیتور با متعلقات (بجز چست لید)</div>
                    <div class="item-row"><div class="item-dot"></div>دستگاه پالس اکسیمتری با متعلقات</div>
                    <div class="item-row"><div class="item-dot"></div>دستگاه استرایکر</div>
                    <div class="item-row"><div class="item-dot"></div>دستگاه اشعه (تلویزیون)</div>
                    <div class="item-row"><div class="item-dot"></div>چراغ اشعه ماوراء بنفش</div>
                    <div class="item-row"><div class="item-dot"></div>انواع محلول‌های شوینده و استریل‌کننده</div>
                </div>
            </div>

            <div class="info-card color-c">
                <div class="card-top-accent"></div>
                <div class="card-header">
                    <i class="fa-solid fa-receipt"></i>
                    <h3>ج) لحاظ شده در تعرفه سرجمع خدمت</h3>
                </div>
                <div class="item-list">
                    <div class="item-row"><div class="item-dot"></div>کاغذ دستگاه‌ها (EEG، سونوگرافی، EKG و...)</div>
                    <div class="item-row"><div class="item-dot"></div>انواع ژل هادی</div>
                    <div class="item-row"><div class="item-dot"></div>لانست (در سرجمع نمونه‌برداری)</div>
                    <div class="item-row"><div class="item-dot"></div>ظرف بیوپسی (در سرجمع نمونه‌برداری)</div>
                    <div class="item-row"><div class="item-dot"></div>لوله آزمایش</div>
                    <div class="item-row"><div class="item-dot"></div>لام آزمایش</div>
                    <div class="item-row"><div class="item-dot"></div>کاست بلاد گاز</div>
                    <div class="item-row"><div class="item-dot"></div>پوشه دکمه‌دار برای گرافی و نتایج</div>
                </div>
            </div>

            <div class="info-card color-d">
                <div class="card-top-accent"></div>
                <div class="card-header">
                    <i class="fa-solid fa-box-tissue"></i>
                    <h3>د) اقلام کیف بهداشتی بیمار</h3>
                </div>
                <div class="item-list">
                    <div class="item-row"><div class="item-dot"></div>انواع لباس بیمار</div>
                    <div class="item-row"><div class="item-dot"></div>لیوان یکبار مصرف</div>
                    <div class="item-row"><div class="item-dot"></div>انواع درجه حرارت (ترمومتر)</div>
                </div>
            </div>

            <div class="info-card color-e">
                <div class="card-top-accent"></div>
                <div class="card-header">
                    <i class="fa-solid fa-kit-medical"></i>
                    <h3>هـ) اقلام ضروری بیمارستان</h3>
                </div>
                <div class="item-list">
                    <div class="item-row"><div class="item-dot"></div>آمبوبگ</div>
                    <div class="item-row"><div class="item-dot"></div>تیغه لارنگوسکوپ یکبار مصرف</div>
                    <div class="item-row"><div class="item-dot"></div>پروپ پالس اکسیمتر (اطفال و بزرگسال)</div>
                    <div class="item-row"><div class="item-dot"></div>آتل ارتوپدی فلزی و چوبی</div>
                    <div class="item-row"><div class="item-dot"></div>رسیور یکبار مصرف</div>
                    <div class="item-row"><div class="item-dot"></div>ظرف جمع‌آوری ادرار و مدفوع</div>
                    <div class="item-row"><div class="item-dot"></div>مژر داروی بیمار</div>
                </div>
            </div>

        </div>
    </main>

     <script>

        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');
        
        function toggleMenu() { sidebar.classList.toggle('active'); mobileOverlay.classList.toggle('active'); }
        function closeMenu() { sidebar.classList.remove('active'); mobileOverlay.classList.remove('active'); }
</script>

</body>
</html>