 
 <style>
.trust-logos {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 12px;
    margin: 10px 0;
}

#zarinpal img {
    width: 80px;
}

.enamad img {
    width: 80px;
}
</style>

 <?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
 <nav class="sidebar" id="sidebar">
        <div class="brand-logo"><i class="fa-solid fa-chart-line"></i>درآمد یار</div>
            <div class="nav-menu">
                <a href="index"
                class="nav-item <?= ($currentPage == 'index.php') ? 'active' : '' ?>">
                <i class="fa-solid fa-house"></i>
                <span>خانه</span>
                </a>

                <a href="magazine"
                class="nav-item <?= ($currentPage == 'magazine.php') ? 'active' : '' ?>">
                <i class="fa-solid fa-book-open"></i>
                <span>مجله درآمد</span>
                </a>

                <a href="academy"
                class="nav-item <?= ($currentPage == 'academy.php') ? 'active' : '' ?>">
                <i class="fa-solid fa-graduation-cap"></i>
                <span>آکادمی</span>
                </a>

                <a href="rules"
                class="nav-item <?= ($currentPage == 'rules.php') ? 'active' : '' ?>">
                <i class="fa-solid fa-scale-balanced"></i>
                <span>قوانین</span>
                </a>

                <a href="dashboard"
                class="nav-item <?= ($currentPage == 'dashboard.php') ? 'active' : '' ?>">
                <i class="fa-solid fa-gauge-high"></i>
                <span>داشبورد</span>
                </a>

            </div>

            <div class="trust-logos">
    <div id="zarinpal">
        <script src="https://www.zarinpal.com/webservice/TrustCode" type="text/javascript"></script>
    </div>

    <a referrerpolicy="origin" target="_blank"
       href="https://trustseal.enamad.ir/?id=699290&Code=1ZIc9aHcbXSgrRCz8apxXn6AchuDGMpI"
       class="enamad">
        <img referrerpolicy="origin"
             src="https://trustseal.enamad.ir/logo.aspx?id=699290&Code=1ZIc9aHcbXSgrRCz8apxXn6AchuDGMpI"
             alt="اینماد">
    </a>
</div>
        </div>
        <div class="sidebar-footer">
            <?php if ($user): ?>
                <div class="user-avatar"><?= substr($user['mobile'], -2) ?></div>
                <div>
                    <div style="font-weight:bold;font-size:13px;">
                        <?= $user['mobile'] ?>
                    </div>
                    <a href="logout" style="font-size:12px;color:#ef4444;">خروج</a>
                </div>
            <?php else: ?>
                <div style="font-size:13px;color:#64748b;">مهمان</div>
            <?php endif; ?>

         


        </div>
    </nav>