<?php
include_once __DIR__ . '/include/auth.php';
include_once __DIR__ . '/include/db.php';

// اگر کاربر لاگین نکرده، هدایت شود به لاگین
if (!$user) {
    header("Location: login");
    exit;
}

// --- لیست شغل‌ها (منبع داده) ---
$job_options = [
    "پزشک", "پرستار", "ماما", "هوشبری", "اتاق عمل", 
    "مدارک پزشکی", "ترخیص", "درآمد", "رسیدگی به اسناد", 
    "حسابداری", "فناوری اطلاعات (IT)", "پذیرش", 
    "رادیولوژی", "آزمایشگاه", "مدیریت","کارشناس بیمه", "سایر"
];

// --- لیست محل کار (منبع داده) ---
$workplace_options = [
    "بیمارستان دولتی (دانشگاهی)", "بیمارستان خصوصی", 
    "بیمارستان تامین اجتماعی", "بیمارستان نیروهای مسلح", "بیمارستان خیریه",
    "درمانگاه / کلینیک", "دی‌کلینیک / جراحی محدود", 
    "مطب شخصی", "داروخانه", "بیمه های خصوصی","بیمه های دولتی","دانشگاه علوم پزشکی","سایر"
];

$msg_success = "";
$msg_error = "";

// --- پردازش فرم در زمان ارسال ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $job = $_POST['job'] ?? '';
    $workplace = $_POST['workplace'] ?? '';

    // اعتبارسنجی ساده
    if (mb_strlen($full_name) > 100) {
        $msg_error = "نام و نام خانوادگی نمی‌تواند بیشتر از ۱۰۰ کاراکتر باشد.";
    } else {
        try {
            $stmt = $db->prepare("UPDATE users SET full_name=?, job=?, workplace=? WHERE id=?");
            $stmt->execute([$full_name, $job, $workplace, $user['id']]);
            
            // آپدیت متغیر یوزر برای نمایش آنی تغییرات
            $user['full_name'] = $full_name;
            $user['job'] = $job;
            $user['workplace'] = $workplace;
            
            $msg_success = "اطلاعات پروفایل با موفقیت بروزرسانی شد.";
        } catch (Exception $e) {
            $msg_error = "خطا در ثبت اطلاعات: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پروفایل کاربری</title>
    
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="fonts/vazirmatn.css">
    <link rel="stylesheet" href="style/dashboard.css"> 
    <link rel="stylesheet" href="style/profile.css?v=<?php echo filemtime(__DIR__.'/style/profile.css'); ?>">
    <?php include 'include/analytics.php'; ?>
</head>
<body>

    <button class="mobile-menu-btn" onclick="toggleMenu()"><i class="fa-solid fa-bars"></i></button>
    <div class="overlay" id="mobileOverlay" onclick="closeMenu()"></div>

    <?php include __DIR__ . '/include/sidebar.php'; ?>

    <main class="main-content">
        
        <div class="page-header">
            <a href="dashboard" class="back-btn"><i class="fa-solid fa-arrow-right"></i></a>
            <h1 class="page-title">پروفایل کاربری</h1>
        </div>

        <div class="profile-container">
            
            <div class="profile-card info-summary">
                <div class="avatar-circle">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div class="user-details-top">
                    <h2 class="display-name"><?= htmlspecialchars($user['full_name'] ?: 'کاربر ناشناس') ?></h2>
                    <span class="display-phone"><?= $user['mobile'] ?></span>
                </div>
            </div>

            <div class="profile-card form-section">
                <h3 class="card-title"><i class="fa-solid fa-pen-to-square"></i> ویرایش مشخصات</h3>

                <?php if($msg_success): ?>
                    <div class="alert success"><i class="fa-solid fa-check"></i> <?= $msg_success ?></div>
                <?php endif; ?>
                
                <?php if($msg_error): ?>
                    <div class="alert error"><i class="fa-solid fa-triangle-exclamation"></i> <?= $msg_error ?></div>
                <?php endif; ?>

                <form method="post" action="">
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label>نام و نام خانوادگی</label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-user-tag"></i>
                                <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name'] ?? '') ?>" placeholder="مثال: محمد محمدی">
                            </div>
                        </div>

                        <div class="form-group disabled">
                            <label>شماره موبایل</label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-mobile-screen"></i>
                                <input type="text" value="<?= $user['mobile'] ?>" disabled>
                            </div>
                            <span class="hint">شماره موبایل قابل تغییر نیست.</span>
                        </div>

                        <div class="form-group">
                            <label>شغل / سمت</label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-briefcase"></i>
                                <select name="job">
                                    <option value="">انتخاب کنید...</option>
                                    <?php foreach ($job_options as $option): ?>
                                        <option value="<?= $option ?>" <?= ($user['job'] === $option) ? 'selected' : '' ?>>
                                            <?= $option ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <i class="fa-solid fa-chevron-down select-arrow"></i>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>محل کار</label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-hospital-user"></i>
                                <select name="workplace">
                                    <option value="">انتخاب کنید...</option>
                                    <?php foreach ($workplace_options as $option): ?>
                                        <option value="<?= $option ?>" <?= ($user['workplace'] === $option) ? 'selected' : '' ?>>
                                            <?= $option ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <i class="fa-solid fa-chevron-down select-arrow"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="save-btn">
                            <i class="fa-solid fa-floppy-disk"></i> ثبت تغییرات
                        </button>
                    </div>

                </form>
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