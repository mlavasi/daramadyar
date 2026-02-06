<?php
include_once __DIR__ . '/include/auth.php';
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>درآمد یار - مجله درآمد</title>
    
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">

    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="fonts/vazirmatn.css">
    <link rel="stylesheet" href="style/magazine.css?v=<?php echo filemtime(__DIR__ . '/style/magazine.css'); ?>">
    <?php include 'include/analytics.php'; ?>
</head>
<body>

    <button class="mobile-menu-btn" onclick="toggleMenu()"><i class="fa-solid fa-bars"></i></button>
    <div class="overlay" id="mobileOverlay" onclick="closeMenu()"></div>

    <?php include __DIR__ . '/include/sidebar.php'; ?>

    <main class="main-content">
        <div class="page-header">
            <a href="index" class="back-btn"><i class="fa-solid fa-arrow-right"></i></a>
            <h1 class="page-title">مجله درآمد (آنلاین)</h1>
        </div>

        <div class="search-container">
            <i class="fa-solid fa-microphone search-icon"></i>
            <input type="text" id="searchInput" class="search-input" placeholder="جستجو در مقالات دریافت شده...">
        </div>

        <div id="articlesContainer" class="articles-grid">
            </div>

        <div id="sentinel" class="loading-sentinel">
            <div class="spinner"></div>
        </div>
    </main>

    <script>
        // UI Logic
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');
        function toggleMenu() { sidebar.classList.toggle('active'); mobileOverlay.classList.toggle('active'); }
        function closeMenu() { sidebar.classList.remove('active'); mobileOverlay.classList.remove('active'); }

        // --- WordPress Blog Logic ---
        const WP_API_URL = 'https://ihee.ir/wp-json/wp/v2/posts';
        const container = document.getElementById('articlesContainer');
        const sentinel = document.getElementById('sentinel');
        const FALLBACK_IMAGE = 'https://via.placeholder.com/400x250/f3f4f6/94a3b8?text=ihee.ir'; 

        let currentPage = 1;
        let perPage = 15;
        let isLoading = false;
        let hasMorePosts = true;

        // تابع اصلی دریافت اطلاعات از وردپرس
        async function fetchPosts() {
            if (isLoading || !hasMorePosts) return;
            
            isLoading = true;
            sentinel.style.display = 'flex'; // نمایش لودینگ

            try {
                // درخواست به API وردپرس با پارامتر _embed برای دریافت عکس‌ها
                const response = await fetch(`${WP_API_URL}?_embed&per_page=${perPage}&page=${currentPage}`);
                
                if (!response.ok) {
                    if(response.status === 400) {
                        // معمولا وقتی صفحه تمام می‌شود وردپرس ارور 400 میدهد
                        hasMorePosts = false;
                        sentinel.innerHTML = 'پایان مقالات';
                        return;
                    }
                    throw new Error('Network response was not ok');
                }

                const posts = await response.json();

                if (posts.length === 0) {
                    hasMorePosts = false;
                    sentinel.innerHTML = 'پایان مقالات';
                } else {
                    renderPosts(posts);
                    currentPage++; // افزایش شماره صفحه برای دفعه بعد
                }

            } catch (error) {
                console.error('Error fetching posts:', error);
                sentinel.innerHTML = 'خطا در برقراری ارتباط با سرور';
            } finally {
                isLoading = false;
                if (!hasMorePosts) sentinel.style.display = 'none'; // مخفی کردن لودینگ اگر تمام شد
            }
        }

        // تابع نمایش کارت‌ها
        function renderPosts(posts) {
            posts.forEach(post => {
                // استخراج عکس شاخص
                let imageUrl = FALLBACK_IMAGE;
                if (post._embedded && post._embedded['wp:featuredmedia'] && post._embedded['wp:featuredmedia'][0]) {
                    imageUrl = post._embedded['wp:featuredmedia'][0].source_url;
                }

                const date = new Date(post.date).toLocaleDateString('fa-IR');
                const title = post.title.rendered;
                const link = post.link;

                const cardHTML = `
                    <a href="${link}" target="_blank" class="article-card">
                        <div class="card-image-wrapper">
                            <img src="${imageUrl}" class="card-image" alt="${title}" onerror="this.src='${FALLBACK_IMAGE}'">
                        </div>
                        
                        <div class="article-title">${title}</div>
                        
                        <div class="card-footer">
                            <div class="article-date">
                                <i class="fa-regular fa-calendar"></i>
                                ${date}
                            </div>
                            <div class="share-btn">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </div>
                        </div>
                    </a>
                `;
                container.insertAdjacentHTML('beforeend', cardHTML);
            });
        }

        // --- Infinite Scroll Setup ---
        // استفاده از IntersectionObserver برای تشخیص رسیدن به انتهای صفحه
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                fetchPosts();
            }
        }, {
            rootMargin: '100px', // 100 پیکسل مانده به انتها شروع به لود کند
        });

        observer.observe(sentinel);

        // --- Search Logic (Client Side Filter) ---
        // توجه: این جستجو فقط روی آیتم‌های لود شده انجام می‌شود
        document.getElementById('searchInput').addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('.article-card');
            
            cards.forEach(card => {
                const title = card.querySelector('.article-title').innerText.toLowerCase();
                if (title.includes(term)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });

    </script>
</body>
</html>