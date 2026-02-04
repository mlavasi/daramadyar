<?php
include_once __DIR__ . '/include/auth.php';
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>درآمد یار - آکادمی</title>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" /> -->

    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="fonts/Vazirmatn-font-face.css">
   <link rel="stylesheet" href="style/academy.css?v=<?php echo filemtime(__DIR__ . '/style/academy.css'); ?>">
</head>
<body>

    <?php include __DIR__ . '/include/sidebar.php'; ?>

    <main class="main-content">
        
        <div class="page-header">
            <div class="header-title">فروشگاه وبینارها</div>
            <div class="header-actions">
                <div class="icon-btn">
                    <i class="fa-regular fa-bell"></i>
                    <div class="badge">3</div>
                </div>
                <div class="icon-btn">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <div class="badge">1</div>
                </div>
            </div>
        </div>

        <div class="toolbar">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="جستجو در دوره‌ها و وبینارها...">
            </div>
            
            <div class="categories">
                <div class="chip active">همه</div>
                <div class="chip">درآمد</div>
                <div class="chip">بیمه</div>
                <div class="chip">تجهیزات</div>
            </div>

            <button class="filter-btn" onclick="toggleModal()">
                <i class="fa-solid fa-sliders"></i>
                فیلتر پیشرفته
            </button>
        </div>

        <div class="hero-card">
            <div class="hero-content">
                <span class="hero-tag">پیشنهاد ویژه</span>
                <h3 class="hero-title">محاسبه تخفیفات کاهش فرانشیز و اسناد پزشکی</h3>
                <button class="hero-btn">مشاهده دوره</button>
            </div>
            <img src="https://ui-avatars.com/api/?name=A+B&background=random&size=200" class="hero-image">
        </div>

        <div class="section-header">
            <div class="section-title">جدیدترین وبینارها</div>
            <div class="view-all">مشاهده همه <i class="fa-solid fa-angle-left"></i></div>
        </div>

        <div class="product-grid">

            <div class="product-card">
                <div class="product-thumb-area">
                    <img src="https://ui-avatars.com/api/?name=Ali+B&background=e2e8f0&color=64748b&size=400" class="product-thumb">
                    <div class="play-overlay"><i class="fa-solid fa-play"></i></div>
                    <div class="bookmark-btn"><i class="fa-regular fa-bookmark"></i></div>
                </div>
                <div class="product-info">
                    <h4 class="product-title">کتاب ارزش نسبی (بخش دوم) - نکات کلیدی</h4>
                    <div class="guest-info">
                        <div class="guest-icon"><i class="fa-solid fa-user-doctor"></i></div>
                        <div class="guest-details">
                            <span class="guest-name">دکتر علی بهاری</span>
                            <span class="guest-role">متخصص ارتوپدی</span>
                        </div>
                    </div>
                    <div class="product-footer">
                        <div class="rating-box"><i class="fa-solid fa-star star"></i> 4.9</div>
                        <div class="product-price">250,000</div>
                        <button class="action-btn"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-thumb-area">
                    <img src="https://ui-avatars.com/api/?name=S+M&background=e2e8f0&color=64748b&size=400" class="product-thumb">
                    <div class="bookmark-btn saved"><i class="fa-solid fa-bookmark"></i></div>
                </div>
                <div class="product-info">
                    <h4 class="product-title">دستورالعمل ترخیص بر بالین و مدیریت تخت</h4>
                    <div class="guest-info">
                        <div class="guest-icon"><i class="fa-solid fa-user-tie"></i></div>
                        <div class="guest-details">
                            <span class="guest-name">سارا مهرآرا</span>
                            <span class="guest-role">مدیر مالی بیمارستان</span>
                        </div>
                    </div>
                    <div class="product-footer">
                        <div class="rating-box"><i class="fa-solid fa-star star"></i> 4.5</div>
                        <div class="price-purchased"><i class="fa-solid fa-check-circle"></i> خریداری شده</div>
                    </div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-thumb-area">
                    <img src="https://ui-avatars.com/api/?name=Group&background=e2e8f0&color=64748b&size=400" class="product-thumb">
                    <div class="play-overlay"><i class="fa-solid fa-play"></i></div>
                    <div class="bookmark-btn"><i class="fa-regular fa-bookmark"></i></div>
                </div>
                <div class="product-info">
                    <h4 class="product-title">شیوه‌نامه تخفیفات بیماران صعب‌العلاج</h4>
                    <div class="guest-info">
                        <div class="guest-icon"><i class="fa-solid fa-users"></i></div>
                        <div class="guest-details">
                            <span class="guest-name">تیم کارشناسان درآمد</span>
                            <span class="guest-role">پنل تخصصی</span>
                        </div>
                    </div>
                    <div class="product-footer">
                        <div class="rating-box"><i class="fa-solid fa-star star"></i> 4.8</div>
                        <div class="product-price" style="color:var(--success)">رایگان</div>
                        <button class="action-btn"><i class="fa-solid fa-download"></i></button>
                    </div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-thumb-area">
                    <img src="https://ui-avatars.com/api/?name=Reza+K&background=e2e8f0&color=64748b&size=400" class="product-thumb">
                    <div class="play-overlay"><i class="fa-solid fa-play"></i></div>
                    <div class="bookmark-btn"><i class="fa-regular fa-bookmark"></i></div>
                </div>
                <div class="product-info">
                    <h4 class="product-title">مدیریت کسورات بیمه سلامت (نسخه جدید)</h4>
                    <div class="guest-info">
                        <div class="guest-icon"><i class="fa-solid fa-clipboard-check"></i></div>
                        <div class="guest-details">
                            <span class="guest-name">رضا کاظمی</span>
                            <span class="guest-role">کارشناس ارشد بیمه</span>
                        </div>
                    </div>
                    <div class="product-footer">
                        <div class="rating-box"><i class="fa-solid fa-star star"></i> 5.0</div>
                        <div class="product-price">180,000</div>
                        <button class="action-btn"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            </div>

        </div>

    </main>

    <div class="modal-overlay" id="modalOverlay" onclick="toggleModal()"></div>
    <div class="filter-modal" id="filterModal">
        <div class="modal-header">
            <span class="modal-title">فیلتر کردن نتایج</span>
            <i class="fa-solid fa-xmark close-modal" onclick="toggleModal()"></i>
        </div>
        
        <div class="filter-group">
            <label class="filter-label">وضعیت قیمت</label>
            <div class="filter-options">
                <div class="modal-filter-chip selected">همه</div>
                <div class="modal-filter-chip">رایگان</div>
                <div class="modal-filter-chip">نقدی</div>
                <div class="modal-filter-chip">خریداری شده</div>
            </div>
        </div>

        <div class="filter-group">
            <label class="filter-label">مرتب‌سازی بر اساس</label>
            <div class="filter-options">
                <div class="modal-filter-chip">جدیدترین</div>
                <div class="modal-filter-chip selected">محبوب‌ترین</div>
                <div class="modal-filter-chip">ارزان‌ترین</div>
            </div>
        </div>

        <button class="apply-btn" onclick="toggleModal()">اعمال فیلترها</button>
    </div>

    <script>
        function toggleModal() {
            document.getElementById('filterModal').classList.toggle('open');
            document.getElementById('modalOverlay').classList.toggle('open');
        }
    </script>

</body>
</html>