<?php include_once __DIR__ . '/include/auth.php'; ?>
<?php if (!$user) die("ابتدا وارد شوید"); ?>

<link rel="stylesheet" href="style/shop.css?v=<?php echo filemtime(__DIR__ . '/style/shop.css'); ?>">

<h2 class="page-title">فیلم آموزش PHP</h2>

<div class="product-box">
    <p>آموزش کامل PHP از صفر</p>
    <strong>299,000 تومان</strong>

    <a href="pay.php?type=product&id=5" class="buy-btn">
        خرید و دانلود
    </a>
</div>
