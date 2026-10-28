<?php include_once __DIR__ . "/includes/files_includes.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!--Php File Include For Head Links & Scripts-->
    <?php include_once __DIR__ . "/includes/head_links.php"; ?>
    <style>
        /* Custom smooth transitions and fixes */
        .mobile-menu-enter {
            transform: translateX(100%);
            opacity: 0;
            pointer-events: none;
        }

        .mobile-menu-enter-active {
            transform: translateX(0);
            opacity: 1;
            pointer-events: auto;
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        /* nav dropdown open from right to left effect */
        .dropdown-panel {
            transform: translateX(20px);
            opacity: 0;
            visibility: hidden;
            transition: transform 0.25s ease, opacity 0.2s ease, visibility 0s linear 0.2s;
        }

        .group:hover .dropdown-panel {
            transform: translateX(0);
            opacity: 1;
            visibility: visible;
            transition: transform 0.25s ease, opacity 0.2s ease, visibility 0s linear 0s;
        }

        /* product carousel slider */
        .product-slide {
            transition: transform 0.4s ease;
        }

        /* ticker animation */
        @keyframes ticker {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(-50%);
            }
        }

        .animate-ticker {
            animation: ticker 35s linear infinite;
        }

        .animate-ticker:hover {
            animation-play-state: paused;
        }

        /* glass reflection keyframes */
        @keyframes glassSweep {
            0% {
                left: -45%;
                opacity: 0;
            }

            20% {
                left: 0%;
                opacity: 1;
            }

            80% {
                left: 120%;
                opacity: 0;
            }

            100% {
                left: 120%;
                opacity: 0;
            }
        }

        .hero-glass-reflection {
            animation: glassSweep 2.2s ease-in-out infinite;
        }

        /* respect reduced motion */
        @media (prefers-reduced-motion: reduce) {

            .animate-ticker,
            .hero-glass-reflection {
                animation: none !important;
            }

            .group:hover .dropdown-panel {
                transition: none;
            }
        }

        /* scrollbar for mobile menu */
        .mobile-nav-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .mobile-nav-scroll::-webkit-scrollbar-thumb {
            background: #ed143b;
            border-radius: 4px;
        }
    </style>
</head>

<body class="m-0 bg-white font-sans text-gray-900 antialiased">

    <?php
    // ==================== TOP BAR DATA (DB) ====================
    $storeEmail    = getSettings("email")        ?: '';
    $storePhone    = getSettings("phone")        ?: '';
    $storeWhatsApp = getSettings("whatsapp_number") ?: $storePhone;
    $whatsAppClean = preg_replace('/[^0-9]/', '', $storeWhatsApp);
    $whatsAppLink  = $whatsAppClean ? 'https://wa.me/' . $whatsAppClean : '#';
    $topBarBg      = '#252a35';   // keep original dark navy
    ?>
    <!-- ================================================== -->
    <!-- TOP BAR                                             -->
    <!-- ================================================== -->
    <div class="text-white" style="background: <?= htmlspecialchars($topBarBg) ?>;">
        <div class="mx-auto flex h-[34px] max-w-[1360px] items-center justify-between px-4 text-[10px] font-semibold sm:px-8">

            <!-- Left Side: Contact Info -->
            <div class="flex items-center gap-2 min-w-0">
                <?php if (!empty($storeEmail)): ?>
                    <a href="mailto:<?= htmlspecialchars($storeEmail) ?>" class="hover:text-gray-300 truncate transition-colors">
                        <?= htmlspecialchars(strtoupper($storeEmail)) ?>
                    </a>
                <?php endif; ?>

                <?php if (!empty($storeEmail) && !empty($storeWhatsApp)): ?>
                    <span class="opacity-40">/</span>
                <?php endif; ?>

                <?php if (!empty($storeWhatsApp)): ?>
                    <a href="<?= htmlspecialchars($whatsAppLink) ?>" target="_blank" rel="noopener" class="hover:text-gray-300 truncate transition-colors">
                        WHATSAPP: <?= htmlspecialchars($storeWhatsApp) ?>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Right Side: Social Media Icons -->
            <div class="flex items-center gap-4">
                <?php if (getSettings("facebook")): ?>
                    <a href="<?= getSettings("facebook") ?>" target="_blank" rel="noopener" aria-label="Facebook" class="hover:text-gray-300 transition-colors">
                        <i class="fab fa-facebook-f text-[12px]"></i>
                    </a>
                <?php endif; ?>

                <?php if (getSettings("instagram")): ?>
                    <a href="<?= getSettings("instagram") ?>" target="_blank" rel="noopener" aria-label="Instagram" class="hover:text-gray-300 transition-colors">
                        <i class="fab fa-instagram text-[12px]"></i>
                    </a>
                <?php endif; ?>

                <?php if (getSettings("youtube")): ?>
                    <a href="<?= getSettings("youtube") ?>" target="_blank" rel="noopener" aria-label="YouTube" class="hover:text-gray-300 transition-colors">
                        <i class="fab fa-youtube text-[12px]"></i>
                    </a>
                <?php endif; ?>

                <?php if (getSettings("twitter")): ?>
                    <a href="<?= getSettings("twitter") ?>" target="_blank" rel="noopener" aria-label="Twitter" class="hover:text-gray-300 transition-colors">
                        <i class="fab fa-twitter text-[12px]"></i>
                    </a>
                <?php endif; ?>

                <?php if (getSettings("linkedin")): ?>
                    <a href="<?= getSettings("linkedin") ?>" target="_blank" rel="noopener" aria-label="LinkedIn" class="hover:text-gray-300 transition-colors">
                        <i class="fab fa-linkedin-in text-[12px]"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include_once __DIR__ . "/includes/nav-bar.php"; ?>

    <?php include_once __DIR__ . "/includes/highlights.php"; ?>

    <!-- ================================================== -->
    <!-- Hero Banner                                           -->
    <!-- ================================================== -->
    <?php
    // ==================================================
    // GLASS MIRROR HERO BANNER — backend wired (Theme 9 columns)
    // No bg color, object-contain image, reduced height
    // Bottom shadow + dark gradient overlay removed → clean white
    // ==================================================
    $heroBanners = [];
    for ($i = 1; $i <= 5; $i++) {
        $col      = "homepage_banner" . ($i > 1 ? "_$i" : "");
        $linkCol  = "homepage_banner_link" . ($i > 1 ? "_$i" : "");

        $img  = getData($col,     "seller_banners", "(seller_id = '$sellerId' AND store_id = '$storeId')");
        $link = getData($linkCol, "seller_banners", "(seller_id = '$sellerId' AND store_id = '$storeId')");

        if (!empty($img)) {
            $heroBanners[] = ['image' => $img, 'link' => $link];
        }
    }
    $heroCount = count($heroBanners);
    ?>
    <?php if ($heroCount > 0): ?>
        <section id="heroBanner"
            class="relative isolate w-full overflow-hidden bg-white h-[220px] sm:h-[300px] lg:h-[380px]">

            <!-- Dynamic banner slides (cross-fade) -->
            <?php foreach ($heroBanners as $index => $banner):
                $opacityCls = $index === 0 ? 'opacity-100 z-0' : 'opacity-0 z-0';
                $imgSrc     = UPLOADS_URL . $banner['image'];
            ?>
                <div class="hero-slide absolute inset-0 transition-opacity duration-1000 <?= $opacityCls ?>">
                    <?php if (!empty($banner['link'])): ?>
                        <a href="<?= htmlspecialchars($banner['link']) ?>" target="_blank" class="block h-full w-full">
                            <img src="<?= htmlspecialchars($imgSrc) ?>"
                                alt="Hero Banner <?= $index + 1 ?>" <?= $index === 0 ? 'fetchpriority="high"' : 'loading="lazy"' ?>
                                class="absolute inset-0 h-full w-full object-contain object-center">
                        </a>
                    <?php else: ?>
                        <img src="<?= htmlspecialchars($imgSrc) ?>"
                            alt="Hero Banner <?= $index + 1 ?>" <?= $index === 0 ? 'fetchpriority="high"' : 'loading="lazy"' ?>
                            class="absolute inset-0 h-full w-full object-contain object-center">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <!-- glass overlay (unchanged) -->
            <div class="pointer-events-none absolute inset-0 z-[2] overflow-hidden">
                <div class="absolute inset-0 bg-white/[0.025]"></div>
                <div
                    class="hero-glass-reflection absolute -inset-y-1/2 left-[-45%] h-[200%] w-[35%] -rotate-[18deg] bg-gradient-to-r from-transparent via-white/[0.22] to-transparent blur-2xl">
                </div>
                <div class="hero-glass-reflection absolute -inset-y-1/2 left-[-45%] h-[200%] w-[18%] -rotate-[18deg] bg-gradient-to-r from-transparent via-white/[0.12] to-transparent blur-xl"
                    style="animation-delay: 0.15s;"></div>
                <div class="absolute inset-x-0 top-0 h-24 bg-gradient-to-b from-white/[0.08] to-transparent"></div>
                <div class="absolute inset-y-0 right-0 w-1/4 bg-gradient-to-l from-white/[0.06] to-transparent"></div>
            </div>

            <?php if ($heroCount > 1): ?>
                <script>
                    (function() {
                        var slides = document.querySelectorAll('#heroBanner .hero-slide');
                        if (slides.length < 2) return;
                        var idx = 0,
                            timer = null,
                            duration = 5000;

                        function show(i) {
                            slides.forEach(function(s, j) {
                                s.classList.toggle('opacity-100', j === i);
                                s.classList.toggle('opacity-0', j !== i);
                            });
                            idx = i;
                        }

                        function next() {
                            show((idx + 1) % slides.length);
                        }

                        function start() {
                            stop();
                            timer = setInterval(next, duration);
                        }

                        function stop() {
                            if (timer) {
                                clearInterval(timer);
                                timer = null;
                            }
                        }
                        show(0);
                        start();
                        document.addEventListener('visibilitychange', function() {
                            if (document.hidden) stop();
                            else start();
                        });
                    })();
                </script>
            <?php endif; ?>
        </section>
    <?php endif; ?>

    <?php
    // ==================== THEME 16 — ARCHERY CATEGORY BANNERS ====================

    $stmt = getCategories();
    if ($stmt) {
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // ✅ Only show categories added to menu
        $categories = array_filter($categories, function ($cat) {
            return isset($cat['add_to_menu']) && (int)$cat['add_to_menu'] === 1;
        });

        $categories = array_reverse($categories);       // newest first
        $categories = array_slice($categories, 0, 4);   // max 4 banners (2x2 grid)
    } else {
        $categories = [];
    }
    ?>

    <?php if (!empty($categories)): ?>
        <!-- ================= ARCHERY CATEGORY BANNERS (THEME 16) ================= -->
        <section class="bg-white px-3 py-5 sm:px-6 lg:px-7">
            <div class="mx-auto max-w-[1500px]">

                <!-- Force 2 columns on ALL screen sizes for the 2x2 grid -->
                <div class="grid grid-cols-2 gap-2.5 sm:gap-4 md:gap-5">

                    <?php foreach ($categories as $index => $cat):
                        $catImg = !empty($cat['icon']) ? UPLOADS_URL . $cat['icon'] : 'https://via.placeholder.com/1400x600?text=No+Image';
                        $catUrl = $storeUrl . 'category/' . $cat['slug'];
                        $catName = htmlspecialchars($cat['name']);

                        // Split name across lines if it has 2+ words
                        $words = preg_split('/\s+/', trim($catName));
                        if (count($words) >= 2) {
                            $mid = (int)ceil(count($words) / 2);
                            $line1 = implode(' ', array_slice($words, 0, $mid));
                            $line2 = implode(' ', array_slice($words, $mid));
                            $titleHtml = htmlspecialchars($line1) . '<br>' . htmlspecialchars($line2);
                        } else {
                            $titleHtml = $catName;
                        }
                    ?>
                        <!-- Drastically reduced height for mobile, original height for desktop -->
                        <a href="<?= $catUrl ?>"
                            class="group relative flex h-[130px] items-center overflow-hidden bg-[#222] sm:h-[220px] lg:h-[300px]">

                            <img src="<?= $catImg ?>"
                                alt="<?= $catName ?>"
                                class="absolute inset-0 h-full w-full object-cover object-center grayscale transition-all duration-700 ease-in-out group-hover:scale-105 group-hover:grayscale-0"
                                onerror="this.onerror=null; this.src='https://via.placeholder.com/1400x600?text=<?= urlencode($catName) ?>';">

                            <div class="absolute inset-0 bg-gradient-to-r from-black/75 via-black/40 to-black/10"></div>

                            <!-- Highly compact content box on mobile -->
                            <div class="relative z-10 w-full max-w-none px-3 py-2 sm:px-8 sm:py-8 lg:px-12">


                                <h2 class="mb-1 w-full max-w-none text-[12px] font-extrabold uppercase leading-tight tracking-tight text-white sm:text-xl md:text-2xl lg:text-[24px]">
                                    <?= $titleHtml ?>
                                </h2>



                                <!-- Hide the redundant category name on mobile to save space -->
                                <p class="hidden sm:block mb-2 text-[10px] font-medium text-white/85 sm:mb-4 sm:text-sm lg:text-base">
                                    <?= $catName ?>
                                </p>

                                <span class="inline-flex items-center gap-1 border-b pb-0.5 text-[9px] font-bold transition-colors duration-300 group-hover:border-white group-hover:text-white sm:gap-2 sm:border-b-2 sm:pb-1 sm:text-sm"
                                    style="border-color: <?= htmlspecialchars($primary_color) ?>; color: <?= htmlspecialchars($primary_color) ?>;">
                                    Shop Now
                                    <svg class="h-2 w-2 sm:h-4 sm:w-4 transition-transform duration-300 group-hover:translate-x-1"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                            </div>
                        </a>
                    <?php endforeach; ?>

                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Latest Arrivals Carousel — theme 13 backend -->
    <?= getLatestArrivalsHtml(8) ?>

    <!-- ================= THEME 16 — BLOG SECTION ================= -->
    <?php
    // Get dynamic colors from DB
    $primary_color = getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ff007f';
    $hover_color   = getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ec4899';

    // Fetch latest 3 blog posts
    $latestPosts = getBlogPosts(3);
    ?>

    <section class="w-full bg-white px-4 py-8 sm:px-6 sm:py-10 lg:px-10 lg:py-10">
        <div class="mx-auto max-w-[1100px]">

            <!-- Header (Reduced margins and sizes) -->
            <div class="mb-8 text-center sm:mb-10">
                <h2 class="text-xl font-bold uppercase leading-tight tracking-tight text-[#292d38] sm:text-[24px]">
                    Aim High, Reach Far
                </h2>
                <p class="mt-2 text-xs font-normal uppercase tracking-wide text-[#444444] sm:text-sm">
                    - Your <?= htmlspecialchars($storeName) ?> Blog -
                </p>
                <!-- Colored Underline -->
                <div class="mx-auto mt-3 h-[2px] w-[30px]" style="background-color: <?= htmlspecialchars($primary_color) ?>;"></div>
            </div>

            <!-- Blog Grid (Reduced gaps) -->
            <div class="grid grid-cols-1 gap-x-6 gap-y-8 md:grid-cols-3">
                <?php if ($latestPosts && $latestPosts->rowCount() > 0): ?>
                    <?php foreach ($latestPosts as $post):
                        $postUrl = $storeUrl . 'post/' . $post['slug'];
                        $date = date("F j, Y", strtotime($post['created_at']));
                        $author = !empty($post['author']) ? $post['author'] : 'Admin';
                        // Get a clean text snippet from the HTML description
                        $excerpt = limit_characters(strip_tags($post['description']), 100);
                    ?>
                        <article class="group flex flex-col">
                            <a href="<?= $postUrl ?>" class="block flex-grow">

                                <!-- Date & Author (Smaller text) -->
                                <p class="mb-3 text-[10px] sm:text-xs leading-relaxed text-[#666666] uppercase tracking-wider">
                                    BLog Post On • <?= $date ?>
                                </p>

                                <!-- Title (Reduced from text-base to text-[15px]) -->
                                <h3 class="mb-3 text-[15px] font-semibold leading-[1.6] text-[#363a45] transition-colors duration-300 sm:text-base"
                                    onmouseover="this.style.color='<?= htmlspecialchars($primary_color) ?>'"
                                    onmouseout="this.style.color='#363a45'">
                                    <?= htmlspecialchars($post['title']) ?>
                                </h3>

                                <!-- Excerpt / Description (Smaller text) -->
                                <p class="mb-4 text-xs sm:text-[13px] leading-relaxed text-gray-500 line-clamp-3">
                                    <?= $excerpt ?>
                                </p>
                            </a>

                            <!-- Read More Link (Smaller) -->
                            <a href="<?= $postUrl ?>" class="inline-flex items-center gap-1.5 text-[10px] sm:text-[11px] font-bold uppercase tracking-widest text-[#292d38] transition-colors duration-300 mt-auto"
                                onmouseover="this.style.color='<?= htmlspecialchars($primary_color) ?>'"
                                onmouseout="this.style.color='#292d38'">
                                Read Article
                                <svg class="w-3.5 h-3.5 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Empty State if no blogs exist -->
                    <div class="col-span-full py-10 text-center">
                        <p class="text-gray-500 text-sm">No blog posts published yet. Check back soon!</p>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </section>

    <?php include_once __DIR__ . "/includes/footer.php"; ?>

</body>

</html>