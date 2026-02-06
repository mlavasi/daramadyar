<?php
include_once __DIR__ . '/include/auth.php';
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>درآمد یار - جدول کامل تخفیفات</title>
    
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="fonts/vazirmatn.css">
    <link rel="stylesheet" href="style/franchise.css?v=<?php echo filemtime(__DIR__ . '/style/franchise.css'); ?>">
<?php include 'include/analytics.php'; ?>
    <style>
        /* --- تنظیمات حیاتی برای اسکرول صحیح --- */
        
        /* 1. جلوگیری از اسکرول اضافه در کل صفحه */
        body {
            height: 100vh; /* ارتفاع دقیقاً اندازه صفحه */
            overflow: hidden; 
        }

        /* 2. تبدیل کانتینر اصلی به ستون فلکس */
        .main-content {
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            padding-bottom: 5px; /* فاصله کم از پایین */
        }

        /* 3. جلوگیری از جمع شدن هدر و باکس جستجو */
        .page-header, .search-container {
            flex-shrink: 0; 
        }

        /* 4. تنظیم کانتینر جدول برای پر کردن فضای خالی */
        .table-container {
            flex-grow: 1; /* تمام فضای خالی باقی‌مانده را می‌گیرد */
            overflow: auto; /* اسکرول داخلی فعال می‌شود */
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            margin-bottom: 5px;
            position: relative; /* برای پوزیشن استیکی */
        }

        /* --- استایل‌های جدول --- */
        table {
            border-collapse: separate; 
            border-spacing: 0;
            width: 100%;
            min-width: max-content; 
        }

        th, td {
            padding: 10px 8px;
            text-align: center;
            border-bottom: 1px solid #eee;
            border-right: 1px solid #eee;
            font-size: 0.85rem;
            vertical-align: middle;
        }

        /* --- تنظیمات هدر چسبان (Sticky) --- */
        thead {
            position: sticky;
            top: 0;
            z-index: 10;
        }

        thead th {
            position: sticky;
            top: 0;
            z-index: 20;
            border-bottom: 2px solid #ddd;
            font-weight: bold;
        }
        
        /* تنظیم برای ردیف دوم هدر */
        thead tr:nth-child(2) th {
            top: 41px; /* ارتفاع ردیف اول */
            font-size: 0.8rem;
            z-index: 15;
        }

        /* --- رنگ‌بندی --- */
        .bg-head { background-color: #D9D9D9; color: #000; }
        .bg-green { background-color: #E2EFDA; color: #000; }
        .bg-orange { background-color: #FCE4D6; color: #000; }
        .bg-yellow { background-color: #FFF2CC; color: #000; }
        .bg-gray { background-color: #D6DCE4; color: #000; }

        /* --- هاور و زبرا --- */
        tbody tr:nth-child(odd) { background-color: #fcfcfc; }
        tbody tr:hover { background-color: #e3f2fd; transition: background 0.2s; cursor: pointer; }
        
        th:first-child, td:first-child { border-right: none; }
        .type-cell { font-weight: bold; color: #334155; }
        
    </style>
</head>
<body>

    <button class="mobile-menu-btn" onclick="toggleMenu()"><i class="fa-solid fa-bars"></i></button>
    <div class="overlay" id="mobileOverlay" onclick="closeMenu()"></div>

    <?php include __DIR__ . '/include/sidebar.php'; ?>

    <main class="main-content">
        <div class="page-header">
            <a href="index" class="back-btn"><i class="fa-solid fa-arrow-right"></i></a>
            <h1 class="page-title">جدول جامع تخفیفات و کسورات</h1>
        </div>

        <div class="search-container">
            <i class="fa-solid fa-microphone search-icon"></i>
            <input type="text" id="searchInput" class="search-input" onkeyup="filterTable()" placeholder="جستجو در ستون پوشش حمایتی...">
        </div>

        <div class="table-container">
            <table id="discountTable">
                <thead>
                    <tr>
                        <th rowspan="2" class="bg-head">نوع پرونده</th>
                        <th rowspan="2" class="bg-head">پوشش حمایتی</th>
                        <th rowspan="2" class="bg-head">پارامتر</th>
                        <th rowspan="2" class="bg-head">خروجی</th>
                        <th colspan="4" class="bg-green">سهم سازمان</th>
                        <th colspan="4" class="bg-orange">سهم صندوق 22.2</th>
                        <th colspan="4" class="bg-yellow">یارانه</th>
                        <th colspan="4" class="bg-gray">سهم بیمار</th>
                    </tr>
                    <tr>
                        <th class="bg-green">تعهد بیمه</th>
                        <th class="bg-green">تفاوت بیمه</th>
                        <th class="bg-green">ستاره دار*</th>
                        <th class="bg-green">خارج تعهد</th>
                        
                        <th class="bg-orange">تعهد بیمه</th>
                        <th class="bg-orange">تفاوت بیمه</th>
                        <th class="bg-orange">c و if</th>
                        <th class="bg-orange">خارج تعهد</th>
                        
                        <th class="bg-yellow">تعهد بیمه</th>
                        <th class="bg-yellow">تفاوت بیمه</th>
                        <th class="bg-yellow">c و if</th>
                        <th class="bg-yellow">خارج تعهد</th>
                        
                        <th class="bg-gray">تعهد بیمه</th>
                        <th class="bg-gray">تفاوت بیمه</th>
                        <th class="bg-gray">خارج تعهد</th>
                        <th class="bg-gray">خارج تعهد</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="type-cell">بستری/ اورژانس دارای پرونده</td>
                        <td>دهک 6 تا 10</td>
                        <td></td>
                        <td></td>
                        <td>90</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>90</td><td>90</td><td>90</td>
                        <td>10</td><td>10</td><td>10</td><td>10</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری / اورژانس دارای پرونده</td>
                        <td>دهک 1 تا 3</td>
                        <td>Decile 8</td>
                        <td>98</td>
                        <td>98</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>98</td><td>98</td><td>98</td>
                        <td>2</td><td>2</td><td>2</td><td>2</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری / اورژانس دارای پرونده</td>
                        <td>دهک 4 تا 5</td>
                        <td>Decile 2</td>
                        <td>8</td>
                        <td>98</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>98</td><td>98</td><td>98</td>
                        <td>2</td><td>2</td><td>2</td><td>2</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری / اورژانس دارای پرونده</td>
                        <td>مددجو کمیته امداد و بهزیستی</td>
                        <td>welfare</td>
                        <td>10</td>
                        <td>100</td><td>100</td><td>100</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری / اورژانس دارای پرونده</td>
                        <td>دهک و مددجو</td>
                        <td>Decile/decile 2</td>
                        <td>8</td>
                        <td>100</td><td>100</td><td>100</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری / اورژانس دارای پرونده</td>
                        <td>دهک و مددجو</td>
                        <td>welfare</td>
                        <td>10</td>
                        <td>100</td><td>100</td><td>100</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری / اورژانس دارای پرونده</td>
                        <td>حمایت داخلی</td>
                        <td>Markorg</td>
                        <td>10</td>
                        <td>100</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>100</td><td>100</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری / اورژانس دارای پرونده</td>
                        <td>دهک و حمایت داخلی</td>
                        <td>Decile/decile 2</td>
                        <td>8</td>
                        <td>100</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>100</td><td>100</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری / اورژانس دارای پرونده</td>
                        <td>دهک و حمایت داخلی</td>
                        <td>Markorg</td>
                        <td>10</td>
                        <td>100</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>100</td><td>100</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری / اورژانس دارای پرونده</td>
                        <td>مددجو و حمایت داخلی</td>
                        <td>welfare</td>
                        <td>10</td>
                        <td>100</td><td>100</td><td>100</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری / اورژانس دارای پرونده</td>
                        <td>مددجو و حمایت داخلی</td>
                        <td>Markorg</td>
                        <td>10</td>
                        <td>100</td><td>100</td><td>100</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری / اورژانس دارای پرونده</td>
                        <td>دهک و مددجو و حمایت داخلی</td>
                        <td>Decile/decile 2</td>
                        <td>8</td>
                        <td>100</td><td>100</td><td>100</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری / اورژانس دارای پرونده</td>
                        <td>دهک و مددجو و حمایت داخلی</td>
                        <td>welfare</td>
                        <td>10</td>
                        <td>100</td><td>100</td><td>100</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری / اورژانس دارای پرونده</td>
                        <td>دهک و مددجو و حمایت داخلی</td>
                        <td>Markorg</td>
                        <td>10</td>
                        <td>100</td><td>100</td><td>100</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری/اورژانس دارای پرونده</td>
                        <td>صعب العلاج</td>
                        <td>Mark</td>
                        <td>10</td>
                        <td>90</td><td>0</td><td>0</td><td>0</td>
                        <td>10</td><td>100</td><td>100</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری / اورژانس دارای پرونده</td>
                        <td>مددجو و صعب العلاج</td>
                        <td>welfare</td>
                        <td>10</td>
                        <td>100</td><td>100</td><td>100</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری / اورژانس دارای پرونده</td>
                        <td>مددجو و صعب العلاج</td>
                        <td>Mark</td>
                        <td>10</td>
                        <td>100</td><td>100</td><td>100</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری / اورژانس دارای پرونده</td>
                        <td>دهک و صعب العلاج</td>
                        <td>Decile/decile 2</td>
                        <td>8</td>
                        <td>98</td><td>0</td><td>0</td><td>0</td>
                        <td>2</td><td>100</td><td>100</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری / اورژانس دارای پرونده</td>
                        <td>دهک و صعب العلاج</td>
                        <td>Mark</td>
                        <td>10</td>
                        <td>98</td><td>0</td><td>0</td><td>0</td>
                        <td>2</td><td>100</td><td>100</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری / اورژانس دارای پرونده</td>
                        <td>دهک و مددجو و صعب العلاج</td>
                        <td>Decile/decile 2</td>
                        <td>8</td>
                        <td>100</td><td>100</td><td>100</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری / اورژانس دارای پرونده</td>
                        <td>دهک و مددجو و صعب العلاج</td>
                        <td>welfare</td>
                        <td>10</td>
                        <td>100</td><td>100</td><td>100</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری / اورژانس دارای پرونده</td>
                        <td>دهک و مددجو و صعب العلاج</td>
                        <td>Mark</td>
                        <td>10</td>
                        <td>100</td><td>100</td><td>100</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری/اورژانس دارای پرونده/ بستری زیر 6 ساعت</td>
                        <td>بیمار مبتلا به سرطان**</td>
                        <td>Mark2</td>
                        <td>10</td>
                        <td>100</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>100</td><td>100</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری/ اورژانس دارای پرونده/ بستری زیر 6 ساعت</td>
                        <td>دهک و بیمار مبتلا به سرطان</td>
                        <td>Decile/decile 2</td>
                        <td>8</td>
                        <td>100</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>100</td><td>100</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری/ اورژانس دارای پرونده/ بستری زیر 6 ساعت</td>
                        <td>دهک و بیمار مبتلا به سرطان</td>
                        <td>Mark2</td>
                        <td>10</td>
                        <td>100</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>100</td><td>100</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری/ اورژانس دارای پرونده/ بستری زیر 6 ساعت (به استثنای دارو)</td>
                        <td>مددجو و بیمار مبتلا به سرطان</td>
                        <td>welfare</td>
                        <td>10</td>
                        <td>100</td><td>100</td><td>100</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری/ اورژانس دارای پرونده/ بستری زیر 6 ساعت (به استثنای دارو)</td>
                        <td>مددجو و بیمار مبتلا به سرطان</td>
                        <td>Mark2</td>
                        <td>10</td>
                        <td>100</td><td>100</td><td>100</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری/ اورژانس دارای پرونده/ بستری زیر 6 ساعت</td>
                        <td>دهک و مددجو و بیمار مبتلا به سرطان</td>
                        <td>Decile/decile 2</td>
                        <td>8</td>
                        <td>100</td><td>100</td><td>100</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری/ اورژانس دارای پرونده/ بستری زیر 6 ساعت</td>
                        <td>دهک و مددجو و بیمار مبتلا به سرطان</td>
                        <td>welfare</td>
                        <td>10</td>
                        <td>100</td><td>100</td><td>100</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری/ اورژانس دارای پرونده/ بستری زیر 6 ساعت</td>
                        <td>دهک و مددجو و بیمار مبتلا به سرطان</td>
                        <td>Mark2</td>
                        <td>10</td>
                        <td>100</td><td>100</td><td>100</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری/ اورژانس دارای پرونده/ بستری زیر 6 ساعت</td>
                        <td>صعب العلاج و بیمار مبتلا به سرطان</td>
                        <td>Mark</td>
                        <td>10</td>
                        <td>100</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>100</td><td>100</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری/ اورژانس دارای پرونده/ بستری زیر 6 ساعت</td>
                        <td>صعب العلاج و بیمار مبتلا به سرطان</td>
                        <td>Mark2</td>
                        <td>10</td>
                        <td>100</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>100</td><td>100</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری/ اورژانس دارای پرونده/ بستری زیر 6 ساعت</td>
                        <td>مددجو و بیمار مبتلا به سرطان و صعب العلاج</td>
                        <td>welfare</td>
                        <td>10</td>
                        <td>100</td><td>100</td><td>100</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری/ اورژانس دارای پرونده/ بستری زیر 6 ساعت</td>
                        <td>مددجو و بیمار مبتلا به سرطان و صعب العلاج</td>
                        <td>Mark</td>
                        <td>10</td>
                        <td>100</td><td>100</td><td>100</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری/ اورژانس دارای پرونده/ بستری زیر 6 ساعت</td>
                        <td>مددجو و بیمار مبتلا به سرطان و صعب العلاج</td>
                        <td>Mark2</td>
                        <td>10</td>
                        <td>100</td><td>100</td><td>100</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری/ اورژانس دارای پرونده/ بستری زیر 6 ساعت</td>
                        <td>صعب العلاج و حمایت داخلی و بیمار مبتلا به سرطان</td>
                        <td>Mark</td>
                        <td>10</td>
                        <td>100</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>100</td><td>100</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری/ اورژانس دارای پرونده/ بستری زیر 6 ساعت</td>
                        <td>صعب العلاج و حمایت داخلی و بیمار مبتلا به سرطان</td>
                        <td>Mark2</td>
                        <td>10</td>
                        <td>100</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>100</td><td>100</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری/ اورژانس دارای پرونده/ بستری زیر 6 ساعت</td>
                        <td>حمایت داخلی و بیمار مبتلا به سرطان</td>
                        <td>Markorg</td>
                        <td>10</td>
                        <td>100</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>100</td><td>100</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری/ اورژانس دارای پرونده/ بستری زیر 6 ساعت</td>
                        <td>حمایت داخلی و بیمار مبتلا به سرطان</td>
                        <td>Mark2</td>
                        <td>10</td>
                        <td>100</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>100</td><td>100</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری / اورژانس دارای پرونده</td>
                        <td>مددجو و صعب العلاج و حمایت داخلی</td>
                        <td>welfare</td>
                        <td>10</td>
                        <td>100</td><td>100</td><td>100</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری / اورژانس دارای پرونده</td>
                        <td>مددجو و صعب العلاج و حمایت داخلی</td>
                        <td>Mark</td>
                        <td>10</td>
                        <td>100</td><td>100</td><td>100</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری/ اورژانس دارای پرونده/ بستری زیر 6 ساعت</td>
                        <td>صعب العلاج و دهک و بیمار مبتلا به سرطان</td>
                        <td>Decile/decile 2</td>
                        <td>8</td>
                        <td>100</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>100</td><td>100</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری/ اورژانس دارای پرونده/ بستری زیر 6 ساعت</td>
                        <td>صعب العلاج و دهک و بیمار مبتلا به سرطان</td>
                        <td>Mark</td>
                        <td>10</td>
                        <td>100</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>100</td><td>100</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری/ اورژانس دارای پرونده/ بستری زیر 6 ساعت</td>
                        <td>صعب العلاج و دهک و بیمار مبتلا به سرطان</td>
                        <td>Mark2</td>
                        <td>10</td>
                        <td>100</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>100</td><td>100</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری / اورژانس دارای پرونده</td>
                        <td>مددجو و صعب العلاج و حمایت داخلی و دهک</td>
                        <td>Markorg</td>
                        <td>10</td>
                        <td>100</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>100</td><td>100</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                    <tr>
                        <td class="type-cell">بستری / اورژانس دارای پرونده</td>
                        <td>مددجو و صعب العلاج و حمایت داخلی و دهک</td>
                        <td>Mark</td>
                        <td>10</td>
                        <td>100</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>100</td><td>100</td><td>100</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                        <td>0</td><td>0</td><td>0</td><td>0</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </main>

<script>
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');
        function toggleMenu() { sidebar.classList.toggle('active'); mobileOverlay.classList.toggle('active'); }
        function closeMenu() { sidebar.classList.remove('active'); mobileOverlay.classList.remove('active'); }
    function filterTable() {
        var input = document.getElementById("searchInput");
        var filter = input.value.trim().toLowerCase();
        var table = document.getElementById("discountTable");
        var tbody = table.getElementsByTagName("tbody")[0];
        var tr = tbody.getElementsByTagName("tr");

        for (var i = 0; i < tr.length; i++) {
            var td = tr[i].getElementsByTagName("td")[1];
            if (td) {
                var txtValue = td.textContent || td.innerText;
                if (txtValue.toLowerCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }       
        }
    }
</script>

</body>
</html>