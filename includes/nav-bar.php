<?php
// ==================== DB COLORS ====================
$headerPrimary = getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ff007f';
$headerHover   = getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ec4899';

// ==================== FETCH CATEGORIES ====================
$stmt = getCategories();
$allHeaderCategories = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
$allHeaderCategories = array_reverse($allHeaderCategories);

$parentCats   = [];
$childCatsMap = [];
foreach ($allHeaderCategories as $cat) {
    $parentId = (int)($cat['parent_category'] ?? 0);
    if ($parentId <= 0) {
        $parentCats[] = $cat;
    } else {
        if (!isset($childCatsMap[$parentId])) $childCatsMap[$parentId] = [];
        $childCatsMap[$parentId][] = $cat;
    }
}
if (empty($parentCats)) $parentCats = $allHeaderCategories;

$visibleCats = array_slice($parentCats, 0, 4);
$moreCats    = array_slice($parentCats, 4);
?>

<!-- ================================================== -->
<!-- MAIN HEADER                                         -->
<!-- ================================================== -->
<header class="relative z-40 bg-white shadow-sm">
    <div class="mx-auto max-w-[1360px] px-5 sm:px-8">
        <div class="flex h-[100px] items-center justify-between gap-4 lg:h-[132px]">

            <!-- LOGO -->
            <a href="<?= $storeUrl ?>" class="shrink-0">
                <?php if (getSettings("logo")): ?>
                    <img src="<?= UPLOADS_URL . getSettings("logo") ?>"
                        alt="<?= htmlspecialchars($storeName) ?>"
                        class="w-[140px] object-contain sm:w-[175px]">
                <?php else: ?>
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-lg"
                            style="background: <?= htmlspecialchars($headerPrimary) ?>;">
                            <?= strtoupper(substr($storeName, 0, 1)) ?>
                        </div>
                        <span class="font-serifDisplay text-xl font-bold text-gray-900">
                            <?= htmlspecialchars($storeName) ?>
                        </span>
                    </div>
                <?php endif; ?>
            </a>

            <!-- DESKTOP NAVIGATION (max 4 categories) -->
            <nav class="hidden items-center gap-4 xl:flex 2xl:gap-5">
                <?php foreach ($visibleCats as $cat):
                    $catSubs = $childCatsMap[$cat['id']] ?? [];
                ?>
                    <?php if (!empty($catSubs)): ?>
                        <div class="group relative">
                            <a href="<?= $storeUrl . 'category/' . $cat['slug'] ?>"
                                class="flex items-center gap-1 text-[11px] font-semibold uppercase transition hover:text-[<?= htmlspecialchars($headerPrimary) ?>]">
                                <?= htmlspecialchars($cat['name']) ?>
                                <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="m5 7 5 5 5-5" />
                                </svg>
                            </a>
                            <div class="dropdown-panel absolute right-0 top-full z-50 mt-3 w-52 bg-white py-2 shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                <a href="<?= $storeUrl . 'category/' . $cat['slug'] ?>"
                                    class="block px-5 py-3 text-xs hover:bg-gray-50 hover:text-[<?= htmlspecialchars($headerPrimary) ?>]">
                                    All <?= htmlspecialchars($cat['name']) ?>
                                </a>
                                <?php foreach (array_slice($catSubs, 0, 6) as $sub): ?>
                                    <a href="<?= $storeUrl . 'category/' . $sub['slug'] ?>"
                                        class="block px-5 py-3 text-xs hover:bg-gray-50 hover:text-[<?= htmlspecialchars($headerPrimary) ?>]">
                                        <?= htmlspecialchars($sub['name']) ?>
                                    </a>
                                <?php endforeach; ?>
                                <?php if (count($catSubs) > 6): ?>
                                    <a href="<?= $storeUrl . 'category/' . $cat['slug'] ?>"
                                        class="block px-5 py-3 text-xs font-semibold hover:bg-gray-50 hover:text-[<?= htmlspecialchars($headerPrimary) ?>]">
                                        View all →
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?= $storeUrl . 'category/' . $cat['slug'] ?>"
                            class="text-[11px] font-semibold uppercase transition hover:text-[<?= htmlspecialchars($headerPrimary) ?>]">
                            <?= htmlspecialchars($cat['name']) ?>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>

                <?php if (!empty($moreCats)): ?>
                    <div class="group relative">
                        <button type="button"
                            class="flex items-center gap-1 text-[11px] font-semibold uppercase hover:text-[<?= htmlspecialchars($headerPrimary) ?>]">
                            More
                            <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                <path d="m5 7 5 5 5-5" />
                            </svg>
                        </button>
                        <div class="dropdown-panel absolute right-0 top-full z-50 mt-3 w-56 bg-white py-2 shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            <?php foreach ($moreCats as $cat): ?>
                                <a href="<?= $storeUrl . 'category/' . $cat['slug'] ?>"
                                    class="block px-5 py-3 text-xs hover:bg-gray-50 hover:text-[<?= htmlspecialchars($headerPrimary) ?>]">
                                    <?= htmlspecialchars($cat['name']) ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="group relative">
                    <button type="button"
                        class="flex items-center gap-1 text-[11px] font-semibold uppercase hover:text-[<?= htmlspecialchars($headerPrimary) ?>]">
                        Quick Shop
                        <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                            <path d="m5 7 5 5 5-5" />
                        </svg>
                    </button>
                    <div class="dropdown-panel absolute right-0 top-full z-50 mt-3 w-52 bg-white py-2 shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                        <a href="<?= $storeUrl ?>shop-all"
                            class="block px-5 py-3 text-xs hover:bg-gray-50 hover:text-[<?= htmlspecialchars($headerPrimary) ?>]">
                            All Products
                        </a>
                        <a href="<?= $storeUrl ?>new-arrivals"
                            class="block px-5 py-3 text-xs hover:bg-gray-50 hover:text-[<?= htmlspecialchars($headerPrimary) ?>]">
                            New Arrivals
                        </a>
                        <a href="<?= $storeUrl ?>shop-all"
                            class="block px-5 py-3 text-xs hover:bg-gray-50 hover:text-[<?= htmlspecialchars($headerPrimary) ?>]">
                            Best Sellers
                        </a>
                    </div>
                </div>
            </nav>

            <!-- RIGHT HEADER ACTIONS -->
            <div class="flex shrink-0 items-center gap-3 sm:gap-4">
                <?php if (function_exists('isLoggedIn') && isLoggedIn()): ?>
                    <a href="<?= $storeUrl ?>profile"
                        class="hidden whitespace-nowrap text-[11px] font-semibold hover:text-[<?= htmlspecialchars($headerPrimary) ?>] lg:block">
                        Hi, <?= htmlspecialchars(customer('name') ?: 'Account') ?>
                    </a>
                <?php else: ?>
                    <a href="<?= $storeUrl ?>login"
                        class="hidden whitespace-nowrap text-[11px] font-semibold hover:text-[<?= htmlspecialchars($headerPrimary) ?>] lg:block">
                        Login / Register
                    </a>
                <?php endif; ?>

                <div class="hidden h-[60px] w-px bg-gray-200 lg:block"></div>

                <a href="<?= $storeUrl ?>profile" aria-label="Account"
                    class="hidden hover:text-[<?= htmlspecialchars($headerPrimary) ?>] sm:block">
                    <svg class="h-[17px] w-[17px]" viewBox="0 0 24 24" fill="currentColor">
                        <circle cx="12" cy="7" r="4" />
                        <path d="M4.5 21v-2a7.5 7.5 0 0 1 15 0v2z" />
                    </svg>
                </a>

                <a href="<?= $storeUrl ?>shop-all" aria-label="Search"
                    class="hover:text-[<?= htmlspecialchars($headerPrimary) ?>]">
                    <svg class="h-[17px] w-[17px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                        <circle cx="10.8" cy="10.8" r="6.3" />
                        <path d="m16 16 4 4" stroke-linecap="round" />
                    </svg>
                </a>

                <a href="<?= $storeUrl ?>wishlists" aria-label="Wishlist"
                    class="relative hidden hover:text-[<?= htmlspecialchars($headerPrimary) ?>] sm:block">
                    <svg class="h-[17px] w-[17px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                        <path d="M20.8 8.8c0 4.2-8.8 10-8.8 10S3.2 13 3.2 8.8A4.3 4.3 0 0 1 12 6.6a4.3 4.3 0 0 1 8.8 2.2Z" />
                    </svg>
                    <span class="wishlistItemsCount absolute -right-2 -top-2 text-[9px] font-bold text-white min-w-[14px] h-[14px] rounded-full flex items-center justify-center px-1"
                        style="background: <?= htmlspecialchars($headerPrimary) ?>;">0</span>
                </a>

                <a href="<?= $storeUrl ?>cart" aria-label="Shopping cart"
                    class="relative hover:text-[<?= htmlspecialchars($headerPrimary) ?>]">
                    <svg class="h-[19px] w-[19px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                        <path d="M2.5 3.5h2l2.4 12h11.8l2.2-8.5H5.3" stroke-linecap="round" stroke-linejoin="round" />
                        <circle cx="9" cy="19.5" r="1.2" />
                        <circle cx="17" cy="19.5" r="1.2" />
                    </svg>
                    <span class="cartItemsCount absolute -right-2 -top-2 text-[9px] font-bold text-white min-w-[14px] h-[14px] rounded-full flex items-center justify-center px-1"
                        style="background: <?= htmlspecialchars($headerPrimary) ?>;">0</span>
                </a>

                <span class="hidden items-center gap-1 text-[10px] font-medium md:flex">
                    <?= htmlspecialchars(strtoupper($storeCurrency)) ?>
                </span>

                <button id="mobileMenuButton" aria-label="Open navigation menu" aria-expanded="false"
                    class="xl:hidden">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                        <path d="M3 6h18M3 12h18M3 18h18" stroke-linecap="round" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</header>


<!-- ================================================== -->
<!-- MOBILE MENU — full-screen overlay (outside header)  -->
<!-- ================================================== -->

<!-- Backdrop -->
<div id="mobileMenuBackdrop"
    class="fixed inset-0 z-[55] bg-black/50 backdrop-blur-sm xl:hidden"
    style="opacity: 0; visibility: hidden; transition: opacity .25s ease, visibility 0s linear .25s;"></div>

<!-- Panel -->
<nav id="mobileMenu"
    aria-hidden="true"
    class="fixed inset-y-0 right-0 z-[60] w-[85vw] max-w-[340px] flex flex-col bg-white shadow-2xl xl:hidden"
    style="transform: translateX(100%); opacity: 0; visibility: hidden; transition: transform .3s cubic-bezier(.4,0,.2,1), opacity .25s ease, visibility 0s linear .3s; will-change: transform;">

    <!-- Header row -->
    <div class="flex shrink-0 items-center justify-between border-b border-gray-200 px-5 py-4">
        <span class="text-sm font-bold uppercase tracking-wider text-gray-900">Menu</span>
        <button id="closeMobileMenu" aria-label="Close navigation menu"
            class="w-9 h-9 flex items-center justify-center rounded-full text-gray-500 hover:bg-gray-100 hover:text-[<?= htmlspecialchars($headerPrimary) ?>] transition-colors">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                <path d="M6 6l12 12M18 6L6 18" stroke-linecap="round" />
            </svg>
        </button>
    </div>

    <!-- Scroll area -->
    <div class="flex-1 overflow-y-auto mobile-nav-scroll">

        <!-- Category links — line by line -->
        <div class="flex flex-col divide-y divide-gray-100">
            <?php foreach ($parentCats as $cat):
                $catSubs = $childCatsMap[$cat['id']] ?? [];
            ?>
                <?php if (!empty($catSubs)): ?>
                    <div class="mobile-accordion-group">
                        <button type="button"
                            class="flex w-full items-center justify-between gap-3 px-5 py-4 text-sm font-semibold uppercase text-gray-900 hover:bg-gray-50 hover:text-[<?= htmlspecialchars($headerPrimary) ?>] transition-colors mobile-dropdown-toggle"
                            data-target="mobileSub-<?= (int)$cat['id'] ?>">
                            <span class="truncate text-left"><?= htmlspecialchars($cat['name']) ?></span>
                            <svg class="h-4 w-4 shrink-0 text-gray-400 transition-transform duration-200 mobile-chevron"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path d="m5 7 5 5 5-5" />
                            </svg>
                        </button>
                        <div id="mobileSub-<?= (int)$cat['id'] ?>"
                            class="hidden flex-col bg-gray-50/50">
                            <a href="<?= $storeUrl . 'category/' . $cat['slug'] ?>"
                                class="block px-9 py-3 text-xs uppercase text-gray-600 hover:text-[<?= htmlspecialchars($headerPrimary) ?>] transition-colors">
                                All <?= htmlspecialchars($cat['name']) ?>
                            </a>
                            <?php foreach ($catSubs as $sub): ?>
                                <a href="<?= $storeUrl . 'category/' . $sub['slug'] ?>"
                                    class="block px-9 py-3 text-xs uppercase text-gray-600 hover:text-[<?= htmlspecialchars($headerPrimary) ?>] transition-colors">
                                    <?= htmlspecialchars($sub['name']) ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?= $storeUrl . 'category/' . $cat['slug'] ?>"
                        class="block px-5 py-4 text-sm font-semibold uppercase text-gray-900 hover:bg-gray-50 hover:text-[<?= htmlspecialchars($headerPrimary) ?>] transition-colors">
                        <?= htmlspecialchars($cat['name']) ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>

            <!-- Quick Shop -->
            <div class="mobile-accordion-group">
                <button type="button"
                    class="flex w-full items-center justify-between gap-3 px-5 py-4 text-sm font-semibold uppercase text-gray-900 hover:bg-gray-50 hover:text-[<?= htmlspecialchars($headerPrimary) ?>] transition-colors mobile-dropdown-toggle"
                    data-target="mobileQuickShop">
                    <span class="truncate text-left">Quick Shop</span>
                    <svg class="h-4 w-4 shrink-0 text-gray-400 transition-transform duration-200 mobile-chevron"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path d="m5 7 5 5 5-5" />
                    </svg>
                </button>
                <div id="mobileQuickShop" class="hidden flex-col bg-gray-50/50">
                    <a href="<?= $storeUrl ?>shop-all" class="block px-9 py-3 text-xs uppercase text-gray-600 hover:text-[<?= htmlspecialchars($headerPrimary) ?>] transition-colors">All Products</a>
                    <a href="<?= $storeUrl ?>new-arrivals" class="block px-9 py-3 text-xs uppercase text-gray-600 hover:text-[<?= htmlspecialchars($headerPrimary) ?>] transition-colors">New Arrivals</a>
                    <a href="<?= $storeUrl ?>shop-all" class="block px-9 py-3 text-xs uppercase text-gray-600 hover:text-[<?= htmlspecialchars($headerPrimary) ?>] transition-colors">Best Sellers</a>
                </div>
            </div>
        </div>

        <!-- CTA -->
        <div class="px-5 py-5">
            <?php if (function_exists('isLoggedIn') && isLoggedIn()): ?>
                <a href="<?= $storeUrl ?>profile"
                    class="block w-full text-center rounded-lg border border-gray-300 px-4 py-3 text-sm font-semibold uppercase text-gray-900 hover:border-[<?= htmlspecialchars($headerPrimary) ?>] hover:text-[<?= htmlspecialchars($headerPrimary) ?>] transition-colors">
                    My Account
                </a>
            <?php else: ?>
                <a href="<?= $storeUrl ?>login"
                    class="block w-full text-center rounded-lg border border-gray-300 px-4 py-3 text-sm font-semibold uppercase text-gray-900 hover:border-[<?= htmlspecialchars($headerPrimary) ?>] hover:text-[<?= htmlspecialchars($headerPrimary) ?>] transition-colors">
                    Login / Register
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>


<!-- ================================================== -->
<!-- HEADER JS                                          -->
<!-- ================================================== -->
<script>
    (function() {
        'use strict';

        const mobileBtn = document.getElementById('mobileMenuButton');
        const mobileMenu = document.getElementById('mobileMenu');
        const closeBtn = document.getElementById('closeMobileMenu');
        const backdrop = document.getElementById('mobileMenuBackdrop');
        let isOpen = false;
        let closeTimer = null;

        function openMobileMenu() {
            if (!mobileMenu || isOpen) return;
            isOpen = true;
            clearTimeout(closeTimer);

            // Make visible BEFORE transform (else animation snaps)
            mobileMenu.style.visibility = 'visible';
            mobileMenu.setAttribute('aria-hidden', 'false');

            // Force reflow so browser registers starting state
            void mobileMenu.offsetWidth;

            // Animate in
            mobileMenu.style.transform = 'translateX(0)';
            mobileMenu.style.opacity = '1';

            if (backdrop) {
                backdrop.style.visibility = 'visible';
                backdrop.style.opacity = '1';
            }

            mobileBtn?.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileMenu() {
            if (!mobileMenu || !isOpen) return;
            isOpen = false;

            mobileMenu.style.transform = 'translateX(100%)';
            mobileMenu.style.opacity = '0';
            mobileMenu.setAttribute('aria-hidden', 'true');

            if (backdrop) {
                backdrop.style.opacity = '0';
            }

            mobileBtn?.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';

            closeTimer = window.setTimeout(function() {
                if (!isOpen) {
                    mobileMenu.style.visibility = 'hidden';
                    if (backdrop) backdrop.style.visibility = 'hidden';
                }
            }, 300);
        }

        mobileBtn?.addEventListener('click', function(e) {
            e.stopPropagation();
            isOpen ? closeMobileMenu() : openMobileMenu();
        });

        closeBtn?.addEventListener('click', closeMobileMenu);
        backdrop?.addEventListener('click', closeMobileMenu);

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isOpen) closeMobileMenu();
        });

        // ============ ACCORDION ============
        document.querySelectorAll('.mobile-dropdown-toggle').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const targetId = this.dataset.target;
                const target = document.getElementById(targetId);
                if (!target) return;

                const isHidden = target.classList.contains('hidden');
                target.classList.toggle('hidden', !isHidden);

                const chevron = this.querySelector('.mobile-chevron');
                if (chevron) {
                    chevron.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
                }
            });
        });

        // ============ HEADER COUNTS ============
        window.updateHeaderCounts = function() {
            if (!window.jQuery) return;

            jQuery.ajax({
                url: 'shop/ajax/get-product-count-and-price.php',
                type: 'GET',
                cache: false,
                success: function(result) {
                    try {
                        const r = JSON.parse(result);
                        const count = r.itemsCount || r.count || 0;
                        document.querySelectorAll('.cartItemsCount').forEach(function(el) {
                            el.textContent = count;
                            el.style.display = count > 0 ? 'flex' : 'none';
                        });
                    } catch (e) {}
                }
            });

            jQuery.ajax({
                url: 'shop/ajax/get-wishlist-count.php',
                type: 'GET',
                cache: false,
                success: function(result) {
                    try {
                        const r = JSON.parse(result);
                        const count = r.count || r.wishlistCount || 0;
                        document.querySelectorAll('.wishlistItemsCount').forEach(function(el) {
                            el.textContent = count;
                            el.style.display = count > 0 ? 'flex' : 'none';
                        });
                    } catch (e) {}
                },
                error: function() {
                    document.querySelectorAll('.wishlistItemsCount').forEach(function(el) {
                        el.style.display = 'none';
                    });
                }
            });
        };

        window.refreshCartUI = window.refreshCartUI || function() {
            if (typeof window.updateHeaderCounts === 'function') {
                window.updateHeaderCounts();
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(window.updateHeaderCounts, 300);
        });
    })();
</script>

<style>
    /* Dropdown hover */
    .dropdown-panel {
        transform: translateY(8px);
        transition: opacity .2s ease, visibility .2s ease, transform .2s ease;
    }

    .group:hover .dropdown-panel {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    /* Hide scrollbar inside mobile menu */
    .mobile-nav-scroll {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .mobile-nav-scroll::-webkit-scrollbar {
        display: none;
    }

    /* Badge hiding when empty */
    .cartItemsCount[data-count="0"],
    .wishlistItemsCount[data-count="0"] {
        display: none !important;
    }
</style>