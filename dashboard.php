<?php
include_once __DIR__ . '/include/auth.php'; // فرض بر این است که $user و $is_premium در اینجا ست می‌شوند

// --- محاسبه روزهای باقی‌مانده ---
$daysLeft = 0;
$statusText = "فاقد اشتراک";
$statusColor = "#ef4444"; // قرمز

if ($user && !empty($user['subscription_expire'])) {
    $expireDate = new DateTime($user['subscription_expire']);
    $now = new DateTime();
    
    if ($expireDate > $now) {
        $daysLeft = $now->diff($expireDate)->days;
        $statusText = "فعال";
        $statusColor = "#10b981"; // سبز
    } else {
        $statusText = "منقضی شده";
    }
}
// اگر کاربر جدید است و هنوز در دوره ۳۰ روزه رایگان است (اختیاری)
elseif ($is_premium && $daysLeft == 0) {
    // منطق محاسبه روزهای باقی‌مانده از ۳۰ روز هدیه
    $regDate = new DateTime($user['created_at']);
    $now = new DateTime();
    $diff = $regDate->diff($now)->days;
    $remainingFree = 30 - $diff;
    if ($remainingFree > 0) {
        $daysLeft = $remainingFree;
        $statusText = "هدیه ثبت‌نام";
        $statusColor = "#f59e0b"; // نارنجی
    }
}

$displayName = 'کاربر مهمان';

if (!empty($user['full_name'])) {
    $displayName = $user['full_name'];
} elseif (!empty($user['mobile'])) {
    $displayName = $user['mobile'];
}

?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>درآمد یار - داشبورد</title>
    
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="fonts/vazirmatn.css">
    <link rel="stylesheet" href="style/dashboard.css?v=<?php echo filemtime(__DIR__.'/style/dashboard.css'); ?>">
    <?php include 'include/analytics.php'; ?>
</head>
<body>

    <button class="mobile-menu-btn" onclick="toggleMenu()"><i class="fa-solid fa-bars"></i></button>
    <div class="overlay" id="mobileOverlay" onclick="closeMenu()"></div>

    <?php include __DIR__ . '/include/sidebar.php'; ?>

    <main class="main-content">
        
        <div class="profile-header">
            <div class="user-info">
                <div class="large-avatar"><i class="fa-regular fa-user"></i></div>
                <div class="user-text">
                    <h2><?= htmlspecialchars($user['full_name'] ?? $user['mobile'] ?? 'کاربر مهمان') ?> <i class="fa-solid fa-crown" style="color: #facc15; font-size: 20px;"></i></h2>
                    <div class="user-status">وضعیت: <span style="color: <?= $statusColor ?>; font-weight:bold"><?= $statusText ?></span></div>
                </div>
            </div>
            <div class="subscription-info">
                <div class="days-left"><?= $daysLeft ?></div>
                <div class="days-label">روز باقی‌مانده اشتراک</div>
            </div>
        </div>

        <?php if ($daysLeft < 10): ?>
        <div class="renew-banner">
            <div class="renew-content">
                <i class="fa-regular fa-calendar-check renew-icon"></i>
                <div>
                    <div class="renew-title">اشتراک شما رو به اتمام است</div>
                    <div class="renew-subtitle">برای دسترسی بدون وقفه همین حالا تمدید کنید</div>
                </div>
            </div>
            <a href="subscription" class="renew-btn">تمدید کنید</a>
        </div>
        <?php endif; ?>

        <div>
            <div class="section-header">ابزارهای من</div>
            <div class="tools-grid">
                <a href="profile" class="tool-card-link">
                    <div class="tool-card">
                        <div class="tool-icon-circle bg-blue-soft"><i class="fa-solid fa-user-gear"></i></div>
                        <div class="tool-name">پروفایل کاربری</div>
                    </div>
                </a>

                <a href="subscription" class="tool-card-link">
                    <div class="tool-card">
                        <div class="tool-icon-circle bg-orange-soft"><i class="fa-solid fa-bag-shopping"></i></div>
                        <div class="tool-name">خرید اشتراک</div>
                    </div>
                </a>
            </div>
        </div>

        <div>
            <div class="section-header">پشتیبانی و آموزش</div>
            <div class="support-grid">
                
                <div class="support-item" onclick="openModal('group')">
                    <i class="fa-brands fa-telegram support-icon text-telegram"></i>
                    <span class="support-text">گروه رایگان آموزشی</span>
                    <i class="fa-solid fa-chevron-left arrow-icon"></i>
                </div>

                <a href="https://ihee.ir/webinar" class="system-card" target="_blank">
                    <div class="support-item">
                        <i class="fa-solid fa-chalkboard-user support-icon text-webinar"></i>
                        <span class="support-text">وبینار شب‌های درآمد</span>
                        <i class="fa-solid fa-chevron-left arrow-icon"></i>
                    </div>
                </a>

                <a href="contact" class="support-item">
                    <i class="fa-solid fa-headset support-icon text-whatsapp"></i>
                    <span class="support-text">ارتباط با ما</span>
                    <i class="fa-solid fa-chevron-left arrow-icon"></i>
                </a>

            </div>
        </div>
    </main>

    <div class="modal-overlay" id="infoModal" onclick="closeModal(event)">
        <div class="info-modal" onclick="event.stopPropagation()">
            <div class="close-modal-btn" onclick="closeModal()">&times;</div>
            <i class="fa-solid fa-bell modal-icon" id="mIcon"></i>
            <div class="modal-title" id="mTitle">عنوان پیام</div>
            <div class="modal-desc" id="mDesc">متن پیام اینجا قرار می‌گیرد...</div>
            <a href="#" class="modal-btn" id="mLink" target="_blank">مشاهده لینک</a>
        </div>
    </div>

    <script>

        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');
        
        function toggleMenu() { sidebar.classList.toggle('active'); mobileOverlay.classList.toggle('active'); }
        function closeMenu() { sidebar.classList.remove('active'); mobileOverlay.classList.remove('active'); }

        // دیتای مربوط به هر مودال
        const modalData = {
            'webinar': {
                title: 'وبینار شب‌های درآمد',
                desc: 'در این وبینارها ما به بررسی جدیدترین متدهای درآمدزایی از طریق کدهای تعدیلی و گلوبال می‌پردازیم. این جلسات هر هفته برگزار می‌شود و شما می‌توانید سوالات خود را مطرح کنید.',
                link: 'https://skyroom.online/ch/example/webinar', // لینک وبینار خود را اینجا بگذارید
                btnText: 'ورود به وبینار',
                icon: 'fa-chalkboard-user'
            },
            'group': {
                title: 'گروه آموزشی تلگرام',
                desc: 'با عضویت در گروه تلگرامی ما، به جمع هزاران متخصص بپیوندید. در این گروه فایل‌های آموزشی رایگان و پاسخ به سوالات متداول قرار داده می‌شود.',
                link: 'https://t.me/hospital_incomegroup', // لینک تلگرام خود را اینجا بگذارید
                btnText: 'عضویت در گروه',
                icon: 'fa-telegram'
            }
        };

        const modal = document.getElementById('infoModal');
        const mTitle = document.getElementById('mTitle');
        const mDesc = document.getElementById('mDesc');
        const mLink = document.getElementById('mLink');
        const mIcon = document.getElementById('mIcon');

        function openModal(type) {
            const data = modalData[type];
            if(data) {
                mTitle.innerText = data.title;
                mDesc.innerText = data.desc;
                mLink.href = data.link;
                mLink.innerText = data.btnText;
                
                // تغییر آیکون
                mIcon.className = 'modal-icon fa-brands ' + data.icon;
                if(type === 'webinar') mIcon.className = 'modal-icon fa-solid ' + data.icon;

                modal.classList.add('open');
            }
        }

        function closeModal(event) {
            if (!event || event.target === modal || event.target.classList.contains('close-modal-btn')) {
                modal.classList.remove('open');
            }
        }
    </script>
</body>
</html>