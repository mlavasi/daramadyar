<?php
include_once __DIR__ . '/include/auth.php';
include_once __DIR__ . '/include/db.php';

$msg_success = "";
$msg_error = "";

// ثبت پیام
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contact'])) {
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if (empty($subject) || empty($message)) {
        $msg_error = "لطفا تمام فیلدها را پر کنید.";
    } else {
        try {
            $stmt = $db->prepare("INSERT INTO contact_messages (user_id, subject, message) VALUES (?, ?, ?)");
            $stmt->execute([$user['id'], $subject, $message]);
            $msg_success = "پیام شما با موفقیت ثبت شد. کارشناسان ما به زودی بررسی خواهند کرد.";
        } catch (PDOException $e) {
            $msg_error = "خطا در ثبت پیام: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ارتباط با ما</title>
    
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="fonts/vazirmatn.css">
    <link rel="stylesheet" href="style/dashboard.css?v=<?= filemtime(__DIR__.'/style/dashboard.css') ?>">
    
    <style>
        .contact-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            max-width: 800px;
            margin: 0 auto;
        }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; margin-bottom: 8px; color: var(--text-main); font-weight: 700; }
        .form-input { 
            width: 100%; padding: 12px 15px; border: 2px solid var(--border-color); 
            border-radius: 12px; font-family: 'Vazirmatn'; font-size: 14px; transition: 0.3s; 
        }
        .form-input:focus { border-color: var(--accent-teal); }
        textarea.form-input { min-height: 150px; resize: vertical; }
        
        .btn-send {
            background: var(--accent-teal); color: white; border: none; padding: 12px 30px;
            border-radius: 12px; font-weight: 700; cursor: pointer; transition: 0.3s;
            display: flex; align-items: center; gap: 10px; font-size: 15px;
        }
        .btn-send:hover { background: #0284c7; transform: translateY(-2px); }
        
        .alert { padding: 15px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    </style>
</head>
<body>

    <button class="mobile-menu-btn" onclick="toggleMenu()"><i class="fa-solid fa-bars"></i></button>
    <div class="overlay" id="mobileOverlay" onclick="closeMenu()"></div>

    <?php include __DIR__ . '/include/sidebar.php'; ?>

    <main class="main-content">
        
        <div class="page-header">
            <a href="dashboard" class="back-btn"><i class="fa-solid fa-arrow-right"></i></a>
            <h1 class="page-title">ارتباط با پشتیبانی</h1>
        </div>

        <div class="contact-card">
            <div style="text-align: center; margin-bottom: 30px;">
                <i class="fa-solid fa-headset" style="font-size: 50px; color: var(--accent-teal); margin-bottom: 15px;"></i>
                <p style="color: var(--text-light);">نظرات، پیشنهادات و مشکلات خود را با ما در میان بگذارید.</p>
            </div>

            <?php if($msg_success): ?>
                <div class="alert alert-success"><i class="fa-solid fa-check-circle"></i> <?= $msg_success ?></div>
            <?php endif; ?>

            <?php if($msg_error): ?>
                <div class="alert alert-error"><i class="fa-solid fa-triangle-exclamation"></i> <?= $msg_error ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="form-group">
                    <label class="form-label">موضوع پیام</label>
                    <input type="text" name="subject" class="form-input" placeholder="مثلا: مشکل در پرداخت..." required>
                </div>

                <div class="form-group">
                    <label class="form-label">متن پیام</label>
                    <textarea name="message" class="form-input" placeholder="توضیحات کامل خود را بنویسید..." required></textarea>
                </div>

                <button type="submit" name="submit_contact" class="btn-send">
                    <i class="fa-regular fa-paper-plane"></i> ارسال پیام
                </button>
            </form>
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