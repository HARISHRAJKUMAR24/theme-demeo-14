<?php
if (!function_exists('getLatestArrivalsHtml')) {
    /**
     * Returns the entire "Latest Arrivals For Pros" carousel section.
     */
    function getLatestArrivalsHtml($limit = 8, $heading = 'Latest Arrivals For Pros', $subheading = '- Conquer The Ranks -')
    {
        global $storeCurrency, $storeUrl, $db, $sellerId, $storeId, $cookie_id, $customerId;

        $primaryColor = getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?: '#ff007f';
        $hoverColor   = getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?: '#ec4899';

        // Use dynamic colors
        $accent      = $primaryColor;
        $accentHover = $hoverColor;

        $stmt = $db->prepare(
            "
            SELECT id, product_id, name, slug, image, price, mrp_price,
                   total_stocks, unlimited_stock, badge
            FROM seller_products
            WHERE seller_id = ? AND store_id = ? AND status = 1
              AND (visibility = 'publish'
                   OR (visibility = 'schedule' AND schedule_date <= NOW()))
            ORDER BY id DESC
            LIMIT " . (int)$limit
        );
        $stmt->execute([$sellerId, $storeId]);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($products)) {
            return '<p class="text-center text-gray-500 py-8">No products yet.</p>';
        }

        $currencySym = currencyToSymbol($storeCurrency);

        // Check wishlist status for each product (batch)
        $wishlistIds = [];
        if (!empty($customerId)) {
            $pids = array_map(fn($p) => (int)$p['id'], $products);
            $in = implode(',', $pids);
            if ($in) {
                try {
                    $wq = $db->prepare("SELECT product_id FROM customer_wishlists WHERE customer_id = ? AND product_id IN ($in)");
                    $wq->execute([$customerId]);
                    $wishlistIds = $wq->fetchAll(PDO::FETCH_COLUMN);
                    $wishlistIds = array_map('intval', $wishlistIds);
                } catch (Throwable $e) {
                }
            }
        }

        ob_start();
?>
        <!-- ================= LATEST ARRIVALS CAROUSEL ================= -->
        <section class="w-full overflow-hidden bg-white py-10 sm:py-14">
            <div class="mx-auto max-w-[1400px] px-4 sm:px-8">
                <div class="mb-8 text-center sm:mb-10">
                    <p class="mb-1.5 text-xs sm:text-sm font-medium uppercase tracking-wider text-gray-500"><?= htmlspecialchars($subheading) ?></p>
                    <h2 class="text-xl sm:text-2xl font-bold uppercase tracking-tight text-[#292d38]"><?= htmlspecialchars($heading) ?></h2>
                    <div class="mx-auto mt-3 h-[2px] w-[30px]" style="background: <?= $accent ?>;"></div>
                </div>

                <div id="latestCarousel" class="relative mx-auto max-w-[1200px]">
                    <div id="latestViewport" class="overflow-hidden">
                        <div id="latestTrack" class="flex items-stretch transition-transform duration-700 ease-in-out">
                            <?php foreach ($products as $p):
                                $pId     = (int)$p['id'];
                                $pSlug   = $p['slug'];
                                $pName   = $p['name'];
                                $pImage  = UPLOADS_URL . $p['image'];
                                $pPrice  = (float)$p['price'];
                                $pMrp    = (float)($p['mrp_price'] ?? 0);
                                $isOut   = (empty($p['unlimited_stock']) && (int)$p['total_stocks'] <= 0);
                                $pUrl    = $storeUrl . 'product/' . $pSlug;
                                $inWish  = in_array($pId, $wishlistIds);
                                $heartColor = '#ffffff';   // always white
                            ?>
                                <!-- Reduced height: 200px mobile, 280px desktop -->
                                <article class="latest-slide group shrink-0 px-2 sm:px-4" style="width: 33.333%;">
                                    <div class="relative flex h-[200px] items-center justify-center overflow-hidden bg-white sm:h-[280px]">
                                        <a href="<?= htmlspecialchars($pUrl) ?>" class="flex h-full w-full items-center justify-center">
                                            <img src="<?= htmlspecialchars($pImage) ?>"
                                                alt="<?= htmlspecialchars($pName) ?>"
                                                class="h-full w-full object-contain transition duration-500 group-hover:scale-105"
                                                loading="lazy"
                                                onerror="this.src='https://via.placeholder.com/400x400?text=No+Image'">
                                        </a>

                                        <?php if ($isOut): ?>
                                            <span class="absolute top-2 left-2 px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider bg-gray-800 text-white">
                                                Sold Out
                                            </span>
                                        <?php endif; ?>

                                        <!-- Compact action buttons -->
                                        <div class="latest-actions absolute bottom-3 left-1/2 flex -translate-x-1/2 gap-1.5 transition-all duration-300">

                                            <!-- WISHLIST (heart) -->
                                            <button type="button"
                                                class="latest-wishlist-btn flex h-8 w-8 items-center justify-center rounded text-white transition"
                                                data-id="<?= $pId ?>"
                                                data-in-wishlist="<?= $inWish ? '1' : '0' ?>"
                                                aria-label="<?= $inWish ? 'Remove from wishlist' : 'Add to wishlist' ?>"
                                                style="background: <?= $accent ?>;"
                                                onmouseover="this.style.background='<?= $accentHover ?>'"
                                                onmouseout="this.style.background='<?= $accent ?>'">
                                                <svg class="h-3.5 w-3.5 latest-heart" viewBox="0 0 24 24"
                                                    fill="<?= $inWish ? 'currentColor' : 'none' ?>"
                                                    stroke="currentColor" stroke-width="2"
                                                    style="color: <?= $heartColor ?>; transition: all .2s ease;">
                                                    <path d="M20.8 8.8c0 4.2-8.8 10-8.8 10S3.2 13 3.2 8.8A4.3 4.3 0 0 1 12 6.6a4.3 4.3 0 0 1 8.8 2.2Z" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </button>
                                            <!-- QUICK VIEW (eye) -->
                                            <a href="<?= htmlspecialchars($pUrl) ?>" aria-label="Quick view"
                                                class="flex h-8 w-8 items-center justify-center rounded text-white transition"
                                                style="background: <?= $accent ?>;"
                                                onmouseover="this.style.background='<?= $accentHover ?>'"
                                                onmouseout="this.style.background='<?= $accent ?>'">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" />
                                                    <circle cx="12" cy="12" r="3" />
                                                </svg>
                                            </a>

                                            <!-- COPY LINK -->
                                            <button type="button"
                                                class="latest-copy-btn flex h-8 w-8 items-center justify-center rounded text-white transition"
                                                data-copy-link="<?= htmlspecialchars($pUrl) ?>"
                                                aria-label="Copy product link"
                                                style="background: <?= $accent ?>;"
                                                onmouseover="this.style.background='<?= $accentHover ?>'"
                                                onmouseout="this.style.background='<?= $accent ?>'">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <rect x="8" y="8" width="12" height="12" rx="2" />
                                                    <path d="M16 8V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h3" />
                                                </svg>
                                            </button>

                                        </div>
                                    </div>

                                    <!-- Reduced text size and spacing -->
                                    <div class="px-1 pb-2 pt-3 text-center">
                                        <a href="<?= htmlspecialchars($pUrl) ?>"
                                            class="block text-sm font-semibold leading-5 text-[#353943] transition-colors duration-300 hover:text-[<?= $accent ?>] line-clamp-2 min-h-[2.5rem]">
                                            <?= htmlspecialchars($pName) ?>
                                        </a>
                                        <p class="mt-1.5 text-xs sm:text-sm font-medium text-gray-500">
                                            <?= $currencySym . number_format($pPrice, 2) ?>
                                            <?php if ($pMrp > $pPrice): ?>
                                                <span class="text-[10px] sm:text-xs line-through ml-1"><?= $currencySym . number_format($pMrp, 2) ?></span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div id="latestDots" class="mt-5 flex items-center justify-center gap-1.5"></div>
                </div>

                <div class="mt-6 text-center">
                    <a href="<?= $storeUrl ?>shop-all"
                        class="inline-flex items-center gap-2 border px-6 py-2.5 text-[11px] sm:text-xs font-semibold uppercase tracking-wider transition duration-300"
                        style="border-color: <?= $accent ?>; color: <?= $accent ?>;"
                        onmouseover="this.style.borderColor='<?= $accentHover ?>'; this.style.backgroundColor='<?= $accentHover ?>'; this.style.color='#fff';"
                        onmouseout="this.style.borderColor='<?= $accent ?>'; this.style.backgroundColor='transparent'; this.style.color='<?= $accent ?>';">
                        View All Products <span aria-hidden="true">→</span>
                    </a>
                </div>
            </div>
        </section>

        <style>
            .latest-actions {
                opacity: 0;
                transform: translate(-50%, 12px);
                transition: opacity .3s ease, transform .3s ease;
                pointer-events: none;
            }

            .latest-slide:hover .latest-actions {
                opacity: 1;
                transform: translate(-50%, 0);
                pointer-events: auto;
            }

            @media (max-width: 768px) {
                .latest-actions {
                    opacity: 1 !important;
                    transform: translate(-50%, 0) !important;
                    pointer-events: auto !important;
                }
            }

            /* Loading state for wishlist button */
            .latest-wishlist-btn.loading {
                opacity: 0.6;
                pointer-events: none;
            }
        </style>

        <script>
            (function() {
                'use strict';
                if (!document.getElementById('latestCarousel')) return;

                const carousel = document.getElementById('latestCarousel');
                const viewport = document.getElementById('latestViewport');
                const track = document.getElementById('latestTrack');
                const dotsWrap = document.getElementById('latestDots');
                if (!carousel || !viewport || !track || !dotsWrap) return;

                const slides = Array.from(track.querySelectorAll('.latest-slide'));
                let currentIndex = 0;
                let visibleCount = 3;
                let maxIndex = 0;
                let autoTimer = null;
                let resizeTimer = null;

                function getVisibleCount() {
                    if (window.innerWidth < 640) return 1;
                    if (window.innerWidth < 1024) return 2;
                    return 3;
                }

                function updateCarousel() {
                    visibleCount = getVisibleCount();
                    slides.forEach(s => s.style.width = (100 / visibleCount) + '%');
                    maxIndex = Math.max(0, slides.length - visibleCount);
                    currentIndex = Math.min(currentIndex, maxIndex);
                    renderDots();
                    moveToIndex(currentIndex, false);
                }

                function renderDots() {
                    dotsWrap.innerHTML = '';
                    for (let i = 0; i <= maxIndex; i++) {
                        const dot = document.createElement('button');
                        dot.type = 'button';
                        dot.setAttribute('aria-label', 'Show product group ' + (i + 1));
                        dot.className = 'h-[8px] w-[8px] rounded-full transition-all duration-300 ' +
                            (i === currentIndex ? 'scale-110' : 'bg-gray-200');
                        
                        // Set active dot to primary color
                        if (i === currentIndex) {
                            dot.style.backgroundColor = '<?= $accent ?>';
                        }

                        // Hover color for inactive dots
                        dot.addEventListener('mouseenter', () => {
                            if (i !== currentIndex) dot.style.backgroundColor = '<?= $accentHover ?>';
                        });
                        dot.addEventListener('mouseleave', () => {
                            if (i !== currentIndex) dot.style.backgroundColor = '#e5e7eb'; // tailwind gray-200
                        });

                        dot.addEventListener('click', () => {
                            currentIndex = i;
                            moveToIndex(currentIndex, true);
                            restartAutoplay();
                        });
                        dotsWrap.appendChild(dot);
                    }
                }

                function moveToIndex(index, animate) {
                    currentIndex = Math.max(0, Math.min(index, maxIndex));
                    track.style.transition = animate ? 'transform 700ms ease-in-out' : 'none';
                    const slideWidth = slides[0] ? slides[0].getBoundingClientRect().width : 0;
                    track.style.transform = 'translate3d(-' + (currentIndex * slideWidth) + 'px, 0, 0)';
                    
                    Array.from(dotsWrap.children).forEach((dot, i) => {
                        const isActive = i === currentIndex;
                        dot.classList.toggle('scale-110', isActive);
                        
                        if (isActive) {
                            dot.style.backgroundColor = '<?= $accent ?>';
                        } else {
                            dot.style.backgroundColor = ''; // reset to default class (gray-200)
                        }
                    });
                }

                function nextSlide() {
                    if (maxIndex <= 0) return;
                    currentIndex = currentIndex >= maxIndex ? 0 : currentIndex + 1;
                    moveToIndex(currentIndex, true);
                }

                function startAutoplay() {
                    stopAutoplay();
                    if (slides.length > visibleCount) autoTimer = setInterval(nextSlide, 4000);
                }

                function stopAutoplay() {
                    if (autoTimer !== null) {
                        clearInterval(autoTimer);
                        autoTimer = null;
                    }
                }

                function restartAutoplay() {
                    startAutoplay();
                }

                carousel.addEventListener('mouseenter', stopAutoplay);
                carousel.addEventListener('mouseleave', startAutoplay);

                let touchStartX = 0;
                viewport.addEventListener('touchstart', e => {
                    touchStartX = e.changedTouches[0].screenX;
                    stopAutoplay();
                }, {
                    passive: true
                });
                viewport.addEventListener('touchend', e => {
                    const diff = touchStartX - e.changedTouches[0].screenX;
                    if (Math.abs(diff) > 40) {
                        if (diff > 0) nextSlide();
                        else {
                            currentIndex = currentIndex <= 0 ? maxIndex : currentIndex - 1;
                            moveToIndex(currentIndex, true);
                        }
                    }
                    startAutoplay();
                }, {
                    passive: true
                });

                window.addEventListener('resize', () => {
                    clearTimeout(resizeTimer);
                    resizeTimer = setTimeout(() => {
                        updateCarousel();
                        startAutoplay();
                    }, 150);
                });

                /* ============================================
                   COPY LINK
                ============================================ */
                document.querySelectorAll('.latest-copy-btn').forEach(btn => {
                    btn.addEventListener('click', async function() {
                        const path = this.getAttribute('data-copy-link');
                        const fullUrl = new URL(path, window.location.origin).href;
                        const original = this.innerHTML;
                        try {
                            await navigator.clipboard.writeText(fullUrl);
                            this.innerHTML = '<svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
                            if (window.showCustomToast) window.showCustomToast('Link copied!', 'success');
                            setTimeout(() => this.innerHTML = original, 1200);
                        } catch (err) {
                            window.prompt('Copy this product link:', fullUrl);
                        }
                    });
                });

                /* ============================================
                   WISHLIST TOGGLE
                ============================================ */
                document.querySelectorAll('.latest-wishlist-btn').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        if (this.classList.contains('loading')) return;

                        const productId = this.dataset.id;
                        const wasInWishlist = this.dataset.inWishlist === '1';

                        if (!window.jQuery) {
                            window.location.href = '<?= $storeUrl ?>login';
                            return;
                        }

                        this.classList.add('loading');
                        const self = this;

                        jQuery.ajax({
                            url: 'shop/ajax/handle-wishlist.php',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                product_id: productId,
                                variant: ''
                            },
                            success: function(res) {
                                self.classList.remove('loading');

                                if (res && res.success) {
                                    const nowInWishlist = !wasInWishlist;
                                    self.dataset.inWishlist = nowInWishlist ? '1' : '0';
                                    self.setAttribute('aria-label', nowInWishlist ? 'Remove from wishlist' : 'Add to wishlist');

                                    const heart = self.querySelector('.latest-heart');
                                    if (heart) {
                                        // Fill = solid when active, none when inactive. Color ALWAYS white.
                                        heart.setAttribute('fill', nowInWishlist ? 'currentColor' : 'none');
                                        heart.style.color = '#ffffff';
                                    }

                                    if (window.showCustomToast) {
                                        window.showCustomToast(
                                            nowInWishlist ? 'Added to wishlist' : 'Removed from wishlist',
                                            'success'
                                        );
                                    }

                                    if (typeof window.updateHeaderCounts === 'function') {
                                        window.updateHeaderCounts();
                                    } else if (typeof window.updateCartCounts === 'function') {
                                        window.updateCartCounts();
                                    }
                                } else {
                                    if (res && res.message && res.message.toLowerCase().includes('login')) {
                                        window.location.href = '<?= $storeUrl ?>login';
                                        return;
                                    }
                                    if (window.showCustomToast) {
                                        window.showCustomToast(res?.message || 'Wishlist update failed', 'error');
                                    }
                                }
                            },
                            error: function() {
                                self.classList.remove('loading');
                                if (window.showCustomToast) {
                                    window.showCustomToast('Network error. Please try again.', 'error');
                                }
                            }
                        });
                    });
                });

                updateCarousel();
                startAutoplay();
            })();
        </script>
<?php
        return ob_get_clean();
    }
}
?>