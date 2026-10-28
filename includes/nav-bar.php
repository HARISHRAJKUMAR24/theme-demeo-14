<?php
// ==================== DB COLORS ====================
$headerPrimary = getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ff007f';
$headerHover   = getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ec4899';

// ==================== FETCH CART/WISHLIST COUNTS ====================
$cart_count = 0;
$wishlist_count = 0;

if (isset($cookie_id) && !empty($cookie_id)) {
    $cartQuery = $db->prepare("SELECT SUM(quantity) FROM customer_cart WHERE customer_id = ?");
    $cartQuery->execute([$cookie_id]);
    $cart_count = (int)$cartQuery->fetchColumn();

    $wishQuery = $db->prepare("SELECT COUNT(*) FROM customer_wishlists WHERE customer_id = ?");
    $wishQuery->execute([$cookie_id]);
    $wishlist_count = (int)$wishQuery->fetchColumn();
}

// ==================== FETCH CATEGORIES ====================
$stmt = getCategories();
$allHeaderCategories = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
$allHeaderCategories = array_filter($allHeaderCategories, function ($cat) {
    return isset($cat['add_to_menu']) && (int)$cat['add_to_menu'] === 1;
});
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
                        <span class="font-serifDisplay text-xl font-bold text-gray-900 truncate max-w-[200px]">
                            <?= htmlspecialchars($storeName) ?>
                        </span>
                    </div>
                <?php endif; ?>
            </a>

            <!-- DESKTOP NAVIGATION -->
            <nav class="hidden items-center gap-6 xl:flex 2xl:gap-8">
                <?php foreach ($visibleCats as $cat):
                    $catSubs = $childCatsMap[$cat['id']] ?? [];
                ?>
                    <?php if (!empty($catSubs)): ?>
                        <div class="group relative">
                            <a href="<?= $storeUrl . 'category/' . $cat['slug'] ?>"
                                class="flex items-center gap-1 text-xs sm:text-sm font-semibold uppercase tracking-wide transition hover:text-[<?= htmlspecialchars($headerPrimary) ?>] max-w-[120px]">
                                <span class="truncate"><?= htmlspecialchars($cat['name']) ?></span>
                                <svg class="h-3 w-3 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="m5 7 5 5 5-5" />
                                </svg>
                            </a>
                            <div class="dropdown-panel absolute right-0 top-full z-50 mt-3 w-52 bg-white py-2 shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                <a href="<?= $storeUrl . 'category/' . $cat['slug'] ?>" class="block px-5 py-3 text-sm hover:bg-gray-50 hover:text-[<?= htmlspecialchars($headerPrimary) ?>] truncate">All <?= htmlspecialchars($cat['name']) ?></a>
                                <?php foreach (array_slice($catSubs, 0, 6) as $sub): ?>
                                    <a href="<?= $storeUrl . 'category/' . $sub['slug'] ?>" class="block px-5 py-3 text-sm hover:bg-gray-50 hover:text-[<?= htmlspecialchars($headerPrimary) ?>] truncate"><?= htmlspecialchars($sub['name']) ?></a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?= $storeUrl . 'category/' . $cat['slug'] ?>" class="text-xs sm:text-sm font-semibold uppercase tracking-wide transition hover:text-[<?= htmlspecialchars($headerPrimary) ?>] max-w-[120px] truncate"><?= htmlspecialchars($cat['name']) ?></a>
                    <?php endif; ?>
                <?php endforeach; ?>

                <?php if (!empty($moreCats)): ?>
                    <div class="group relative">
                        <button type="button" class="flex items-center gap-1 text-xs sm:text-sm font-semibold uppercase tracking-wide hover:text-[<?= htmlspecialchars($headerPrimary) ?>]">More <svg class="h-3 w-3 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                <path d="m5 7 5 5 5-5" />
                            </svg></button>
                        <div class="dropdown-panel absolute right-0 top-full z-50 mt-3 w-56 bg-white py-2 shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            <?php foreach ($moreCats as $cat): ?>
                                <a href="<?= $storeUrl . 'category/' . $cat['slug'] ?>" class="block px-5 py-3 text-sm hover:bg-gray-50 hover:text-[<?= htmlspecialchars($headerPrimary) ?>] truncate"><?= htmlspecialchars($cat['name']) ?></a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="group relative">
                    <button type="button" class="flex items-center gap-1 text-xs sm:text-sm font-semibold uppercase tracking-wide hover:text-[<?= htmlspecialchars($headerPrimary) ?>]">Quick Shop <svg class="h-3 w-3 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path d="m5 7 5 5 5-5" />
                        </svg></button>
                    <div class="dropdown-panel absolute right-0 top-full z-50 mt-3 w-52 bg-white py-2 shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                        <a href="<?= $storeUrl ?>shop-all" class="block px-5 py-3 text-sm hover:bg-gray-50 hover:text-[<?= htmlspecialchars($headerPrimary) ?>]">All Products</a>
                        <a href="<?= $storeUrl ?>new-arrivals" class="block px-5 py-3 text-sm hover:bg-gray-50 hover:text-[<?= htmlspecialchars($headerPrimary) ?>]">New Arrivals</a>
                    </div>
                </div>
            </nav>

            <!-- RIGHT HEADER ACTIONS -->
            <div class="flex shrink-0 items-center gap-3 sm:gap-4">
                <?php if (function_exists('isLoggedIn') && isLoggedIn()): ?>
                    <a href="<?= $storeUrl ?>profile" class="hidden whitespace-nowrap text-xs sm:text-sm font-semibold hover:text-[<?= htmlspecialchars($headerPrimary) ?>] lg:block max-w-[150px] truncate">Hi, <?= htmlspecialchars(customer('name') ?: 'Account') ?></a>
                <?php else: ?>
                    <a href="<?= $storeUrl ?>login" class="hidden whitespace-nowrap text-xs sm:text-sm font-semibold hover:text-[<?= htmlspecialchars($headerPrimary) ?>] lg:block">Login / Register</a>
                <?php endif; ?>

                <div class="hidden h-[60px] w-px bg-gray-200 lg:block"></div>

                <a href="<?= $storeUrl ?>profile" aria-label="Account" class="hidden hover:text-[<?= htmlspecialchars($headerPrimary) ?>] sm:block">
                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="currentColor">
                        <circle cx="12" cy="7" r="4" />
                        <path d="M4.5 21v-2a7.5 7.5 0 0 1 15 0v2z" />
                    </svg>
                </a>

                <!-- SEARCH ICON -->
                <button id="headerSearchBtn" aria-label="Search" class="hover:text-[<?= htmlspecialchars($headerPrimary) ?>]">
                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                        <circle cx="10.8" cy="10.8" r="6.3" />
                        <path d="m16 16 4 4" stroke-linecap="round" />
                    </svg>
                </button>

                <!-- WISHLIST ICON -->
                <a href="<?= $storeUrl ?>wishlists" aria-label="Wishlist" class="relative hidden hover:text-[<?= htmlspecialchars($headerPrimary) ?>] sm:block">
                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                        <path d="M20.8 8.8c0 4.2-8.8 10-8.8 10S3.2 13 3.2 8.8A4.3 4.3 0 0 1 12 6.6a4.3 4.3 0 0 1 8.8 2.2Z" />
                    </svg>
                    <span class="wishlistItemsCount absolute -right-2 -top-2 text-[10px] font-bold text-white min-w-[16px] h-[16px] rounded-full items-center justify-center px-1"
                        style="background: <?= htmlspecialchars($headerPrimary) ?>; display: <?= $wishlist_count > 0 ? 'flex' : 'none' ?>;">
                        <?= $wishlist_count ?>
                    </span>
                </a>

                <!-- CART ICON -->
                <a href="<?= $storeUrl ?>cart" aria-label="Shopping cart" class="relative hover:text-[<?= htmlspecialchars($headerPrimary) ?>]">
                    <svg class="h-[20px] w-[20px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                        <path d="M2.5 3.5h2l2.4 12h11.8l2.2-8.5H5.3" stroke-linecap="round" stroke-linejoin="round" />
                        <circle cx="9" cy="19.5" r="1.2" />
                        <circle cx="17" cy="19.5" r="1.2" />
                    </svg>
                    <span class="cartItemsCount absolute -right-2 -top-2 text-[10px] font-bold text-white min-w-[16px] h-[16px] rounded-full items-center justify-center px-1"
                        style="background: <?= htmlspecialchars($headerPrimary) ?>; display: <?= $cart_count > 0 ? 'flex' : 'none' ?>;">
                        <?= $cart_count ?>
                    </span>
                </a>

                <span class="hidden items-center gap-1 text-xs font-medium md:flex"><?= htmlspecialchars(strtoupper($storeCurrency)) ?></span>

                <button id="mobileMenuButton" aria-label="Open navigation menu" aria-expanded="false" class="xl:hidden">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                        <path d="M3 6h18M3 12h18M3 18h18" stroke-linecap="round" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</header>


<!-- ================================================== -->
<!-- MOBILE MENU                                        -->
<!-- ================================================== -->
<div id="mobileMenuBackdrop" class="fixed inset-0 z-[55] bg-black/50 backdrop-blur-sm xl:hidden" style="opacity: 0; visibility: hidden; transition: opacity .25s ease, visibility 0s linear .25s;"></div>
<nav id="mobileMenu" aria-hidden="true" class="fixed inset-y-0 right-0 z-[60] w-[85vw] max-w-[340px] flex flex-col bg-white shadow-2xl xl:hidden" style="transform: translateX(100%); opacity: 0; visibility: hidden; transition: transform .3s cubic-bezier(.4,0,.2,1), opacity .25s ease, visibility 0s linear .3s; will-change: transform;">
    <div class="flex shrink-0 items-center justify-between border-b border-gray-200 px-5 py-4">
        <span class="text-base font-bold uppercase tracking-wider text-gray-900">Menu</span>
        <button id="closeMobileMenu" aria-label="Close navigation menu" class="w-10 h-10 flex items-center justify-center rounded-full text-gray-500 hover:bg-gray-100 hover:text-[<?= htmlspecialchars($headerPrimary) ?>] transition-colors">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                <path d="M6 6l12 12M18 6L6 18" stroke-linecap="round" />
            </svg>
        </button>
    </div>
    <div class="flex-1 overflow-y-auto mobile-nav-scroll">
        <div class="flex flex-col divide-y divide-gray-100">
            <?php foreach ($parentCats as $cat):
                $catSubs = $childCatsMap[$cat['id']] ?? [];
            ?>
                <?php if (!empty($catSubs)): ?>
                    <div class="mobile-accordion-group">
                        <button type="button" class="flex w-full items-center justify-between gap-3 px-5 py-5 text-base font-semibold uppercase text-gray-900 hover:bg-gray-50 hover:text-[<?= htmlspecialchars($headerPrimary) ?>] transition-colors mobile-dropdown-toggle" data-target="mobileSub-<?= (int)$cat['id'] ?>">
                            <span class="truncate text-left"><?= htmlspecialchars($cat['name']) ?></span>
                            <svg class="h-5 w-5 shrink-0 text-gray-400 transition-transform duration-200 mobile-chevron" viewBox="0 0 20 20" fill="currentColor">
                                <path d="m5 7 5 5 5-5" />
                            </svg>
                        </button>
                        <div id="mobileSub-<?= (int)$cat['id'] ?>" class="hidden flex-col bg-gray-50/50">
                            <a href="<?= $storeUrl . 'category/' . $cat['slug'] ?>" class="block px-9 py-3 text-sm uppercase text-gray-600 hover:text-[<?= htmlspecialchars($headerPrimary) ?>] transition-colors truncate">All <?= htmlspecialchars($cat['name']) ?></a>
                            <?php foreach ($catSubs as $sub): ?>
                                <a href="<?= $storeUrl . 'category/' . $sub['slug'] ?>" class="block px-9 py-3 text-sm uppercase text-gray-600 hover:text-[<?= htmlspecialchars($headerPrimary) ?>] transition-colors truncate"><?= htmlspecialchars($sub['name']) ?></a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?= $storeUrl . 'category/' . $cat['slug'] ?>" class="block px-5 py-5 text-base font-semibold uppercase text-gray-900 hover:bg-gray-50 hover:text-[<?= htmlspecialchars($headerPrimary) ?>] transition-colors truncate"><?= htmlspecialchars($cat['name']) ?></a>
                <?php endif; ?>
            <?php endforeach; ?>

            <div class="mobile-accordion-group">
                <button type="button" class="flex w-full items-center justify-between gap-3 px-5 py-5 text-base font-semibold uppercase text-gray-900 hover:bg-gray-50 hover:text-[<?= htmlspecialchars($headerPrimary) ?>] transition-colors mobile-dropdown-toggle" data-target="mobileQuickShop">
                    <span class="truncate text-left">Quick Shop</span>
                    <svg class="h-5 w-5 shrink-0 text-gray-400 transition-transform duration-200 mobile-chevron" viewBox="0 0 20 20" fill="currentColor">
                        <path d="m5 7 5 5 5-5" />
                    </svg>
                </button>
                <div id="mobileQuickShop" class="hidden flex-col bg-gray-50/50">
                    <a href="<?= $storeUrl ?>shop-all" class="block px-9 py-3 text-sm uppercase text-gray-600 hover:text-[<?= htmlspecialchars($headerPrimary) ?>] transition-colors">All Products</a>
                    <a href="<?= $storeUrl ?>new-arrivals" class="block px-9 py-3 text-sm uppercase text-gray-600 hover:text-[<?= htmlspecialchars($headerPrimary) ?>] transition-colors">New Arrivals</a>
                </div>
            </div>
        </div>

        <div class="px-5 py-6">
            <?php if (function_exists('isLoggedIn') && isLoggedIn()): ?>
                <a href="<?= $storeUrl ?>profile" class="block w-full text-center rounded-lg border border-gray-300 px-4 py-3 text-sm font-bold uppercase text-gray-900 hover:border-[<?= htmlspecialchars($headerPrimary) ?>] hover:text-[<?= htmlspecialchars($headerPrimary) ?>] transition-colors">My Account</a>
            <?php else: ?>
                <a href="<?= $storeUrl ?>login" class="block w-full text-center rounded-lg border border-gray-300 px-4 py-3 text-sm font-bold uppercase text-gray-900 hover:border-[<?= htmlspecialchars($headerPrimary) ?>] hover:text-[<?= htmlspecialchars($headerPrimary) ?>] transition-colors">Login / Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>


<!-- ============================================ -->
<!-- SEARCH OVERLAY                               -->
<!-- ============================================ -->
<div id="searchOverlay" class="fixed inset-0 bg-white z-[9999] hidden transition-transform duration-500 transform -translate-y-full">
    <div class="relative h-full flex flex-col">
        <button id="searchOverlayClose" class="absolute top-6 right-6 z-10 w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100 transition-colors" aria-label="Close search">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M6 6L18 18M18 6L6 18"></path>
            </svg>
        </button>

        <div class="flex-1 flex flex-col items-center justify-center px-6 py-8">
            <div class="mb-8 text-center">
                <h2 class="font-serifDisplay text-3xl text-gray-900">Search</h2>
                <p class="text-gray-500 text-sm mt-2">Find your favourite products</p>
            </div>

            <div class="w-full max-w-2xl relative" id="searchForDesktop">
                <form id="desktopSearchForm" method="GET" action="<?= $storeUrl ?>search/" autocomplete="off" class="relative">
                    <input type="text" name="q" id="desktopSearchInput" class="searchInput w-full h-[64px] pl-14 pr-4 text-lg bg-white border-b-2 text-gray-800 placeholder-gray-400 focus:outline-none transition-colors duration-200" placeholder="Search..." autocomplete="off" style="border-color: var(--primary); --tw-ring-color: var(--primary);">

                    <button type="submit" class="searchBtn mgc_search_line text-2xl absolute top-[50%] right-[14px] -translate-y-[50%] transition-all duration-300" style="color: var(--primary);" onmouseover="this.style.color='var(--hover-color)'" onmouseout="this.style.color='var(--primary)'"></button>

                    <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none" style="color: var(--primary);">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <circle cx="11" cy="11" r="7.5"></circle>
                            <path d="M16.5 16.5L22 22"></path>
                        </svg>
                    </div>
                </form>

                <div id="desktopSearchSuggestions" class="absolute left-0 right-0 top-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-50 hidden max-h-[400px] overflow-y-auto">
                    <div id="desktopSuggestionsList" class="py-2"></div>
                </div>
            </div>

            <div class="mt-10 text-center">
                <p class="text-xs tracking-[3px] uppercase text-gray-400 mb-3">Popular</p>
                <div class="flex flex-wrap justify-center gap-2">
                    <?php
                    $quickCategories = getCategories();
                    $quickCats = $quickCategories ? array_slice($quickCategories->fetchAll(PDO::FETCH_ASSOC), 0, 5) : [];
                    foreach ($quickCats as $qc):
                    ?>
                        <a href="<?= $storeUrl ?>category/<?= htmlspecialchars($qc['slug']) ?>" class="px-4 py-2 text-xs tracking-widest uppercase border border-gray-200 text-gray-600 hover:border-[<?= htmlspecialchars($headerPrimary) ?>] hover:text-[<?= htmlspecialchars($headerPrimary) ?>] transition-colors truncate max-w-[150px]">
                            <?= htmlspecialchars($qc['name']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --primary: <?= htmlspecialchars($headerPrimary) ?>;
        --hover-color: <?= htmlspecialchars($headerHover) ?>;
    }

    #desktopSearchSuggestions {
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);
        border-radius: 12px;
        background: white;
        border: 1px solid #e5e7eb;
    }

    .suggestion-item {
        display: flex;
        align-items: center;
        padding: 10px 16px;
        cursor: pointer;
        transition: background-color 0.2s ease;
        gap: 12px;
        border-bottom: 1px solid #f3f4f6;
        text-decoration: none !important;
        color: inherit;
    }

    .suggestion-item:last-child {
        border-bottom: none;
    }

    .suggestion-item:hover {
        background-color: #f9fafb;
    }

    .suggestion-item .suggestion-image {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        object-fit: cover;
        flex-shrink: 0;
        background: #f3f4f6;
    }

    .suggestion-item .suggestion-info {
        flex: 1;
        min-width: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .suggestion-item .suggestion-name {
        font-size: 14px;
        font-weight: 500;
        color: #1f2937;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .suggestion-item .suggestion-badge {
        font-size: 10px;
        background: #fef3c7;
        color: #d97706;
        padding: 2px 8px;
        border-radius: 12px;
        font-weight: 600;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .suggestion-empty {
        padding: 20px 16px;
        text-align: center;
        color: #9ca3af;
        font-size: 14px;
    }

    .suggestion-loading {
        padding: 20px 16px;
        text-align: center;
        color: #6b7280;
        font-size: 14px;
    }

    .suggestion-item .highlight {
        color: var(--hover-color);
        font-weight: 600;
    }

    @media (max-width: 768px) {
        #desktopSearchSuggestions {
            max-height: 300px;
        }

        .suggestion-item {
            padding: 8px 12px;
        }

        .suggestion-item .suggestion-image {
            width: 32px;
            height: 32px;
        }

        .suggestion-item .suggestion-name {
            font-size: 13px;
        }
    }

    .dropdown-panel {
        transform: translateY(8px);
        transition: opacity .2s ease, visibility .2s ease, transform .2s ease;
    }

    .group:hover .dropdown-panel {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .mobile-nav-scroll {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .mobile-nav-scroll::-webkit-scrollbar {
        display: none;
    }
</style>

<script>
    (function() {
        'use strict';

        // ============ MOBILE MENU (SMOOTH ANIMATION) ============
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

            // 1. Make visible BEFORE transform
            mobileMenu.style.visibility = 'visible';
            mobileMenu.setAttribute('aria-hidden', 'false');

            // 2. Force reflow so browser registers starting state
            void mobileMenu.offsetWidth;

            // 3. Animate in
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

            if (backdrop) backdrop.style.opacity = '0';
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
                if (chevron) chevron.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
            });
        });

        // ============ HEADER COUNTS ============
        window.updateHeaderCounts = function() {
            if (!window.jQuery) return;

            // Cart Count
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

            // Wishlist Count
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
            if (typeof window.updateHeaderCounts === 'function') window.updateHeaderCounts();
        };

        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(window.updateHeaderCounts, 300);
        });

        // ============ SEARCH OVERLAY LOGIC ============
        const overlay = document.getElementById('searchOverlay');
        const openSearchBtn = document.getElementById('headerSearchBtn');
        const closeSearchBtn = document.getElementById('searchOverlayClose');

        if (openSearchBtn) {
            openSearchBtn.addEventListener('click', function() {
                overlay.classList.remove('hidden');
                requestAnimationFrame(() => {
                    overlay.classList.remove('-translate-y-full');
                });
                document.body.style.overflow = 'hidden';
                setTimeout(() => {
                    document.getElementById('desktopSearchInput')?.focus();
                }, 350);
            });
        }

        if (closeSearchBtn) {
            closeSearchBtn.addEventListener('click', function() {
                overlay.classList.add('-translate-y-full');
                document.body.style.overflow = '';
                setTimeout(() => {
                    overlay.classList.add('hidden');
                    const inp = document.getElementById('desktopSearchInput');
                    if (inp) inp.value = '';
                    document.getElementById('desktopSearchSuggestions')?.classList.add('hidden');
                }, 500);
            });
        }

        // ============ SEARCH SUGGESTIONS ============
        if (window.jQuery) {
            $(document).ready(function() {
                var desktopSearchInput = $('#desktopSearchInput');
                var desktopSuggestionsContainer = $('#desktopSearchSuggestions');
                var desktopSuggestionsList = $('#desktopSuggestionsList');
                var desktopSearchForm = $('#desktopSearchForm');
                var currentSearchTerm = '';
                var debounceTimer;
                var storeUrl = '<?= $storeUrl ?>';

                function getSuggestions(query, container, list) {
                    if (query.length < 1) {
                        container.addClass('hidden');
                        return;
                    }
                    currentSearchTerm = query;
                    list.html('<div class="suggestion-loading">Searching...</div>');
                    container.removeClass('hidden');

                    $.ajax({
                        url: 'shop/ajax/get-search-suggestions.php',
                        type: 'POST',
                        data: {
                            q: query,
                            store: '<?= $storeSlug ?>'
                        },
                        dataType: 'json',
                        timeout: 5000,
                        success: function(response) {
                            if (currentSearchTerm !== query) return;
                            if (response.success && response.data && response.data.length > 0) {
                                var html = '';
                                $.each(response.data, function(index, product) {
                                    var image = product.image && product.image !== 'no-image.png' ? '<?= UPLOADS_URL ?>' + product.image : '<?= APP_URL ?>assets/image/no-image.png';
                                    var nameHighlight = highlightText(product.name, query);
                                    var slug = product.slug || '';
                                    var badge = product.badge || '';
                                    var productUrl = storeUrl + 'product/' + slug;

                                    html += `
                                    <a href="${productUrl}" class="suggestion-item no-underline">
                                        <img src="${image}" alt="${product.name}" class="suggestion-image" onerror="this.src='<?= APP_URL ?>assets/image/no-image.png'">
                                        <div class="suggestion-info">
                                            <div class="suggestion-name">${nameHighlight}</div>
                                            ${badge ? `<span class="suggestion-badge">${badge}</span>` : ''}
                                        </div>
                                    </a>`;
                                });
                                list.html(html);
                                container.removeClass('hidden');
                            } else {
                                list.html('<div class="suggestion-empty">No products found for "<strong>' + query + '</strong>"</div>');
                            }
                        },
                        error: function() {
                            list.html('<div class="suggestion-empty">Error loading suggestions. Please try again.</div>');
                        }
                    });
                }

                function highlightText(text, query) {
                    if (!query) return text;
                    var regex = new RegExp('(' + query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');
                    return text.replace(regex, '<span class="highlight">$1</span>');
                }

                desktopSearchInput.on('input', function() {
                    clearTimeout(debounceTimer);
                    var query = $(this).val().trim();
                    if (query.length === 0) {
                        desktopSuggestionsContainer.addClass('hidden');
                        return;
                    }
                    debounceTimer = setTimeout(function() {
                        getSuggestions(query, desktopSuggestionsContainer, desktopSuggestionsList);
                    }, 300);
                });

                desktopSearchInput.on('focus', function() {
                    var query = $(this).val().trim();
                    if (query.length > 0) {
                        getSuggestions(query, desktopSuggestionsContainer, desktopSuggestionsList);
                    }
                });

                desktopSearchForm.on('submit', function(e) {
                    e.preventDefault();
                    var query = desktopSearchInput.val().trim();
                    if (query && query.length > 0) {
                        window.location.href = storeUrl + 'search/' + encodeURIComponent(query);
                    }
                });

                $(document).on('click', function(e) {
                    if (!$(e.target).closest('#desktopSearchForm').length && !$(e.target).closest('#desktopSearchSuggestions').length) {
                        desktopSuggestionsContainer.addClass('hidden');
                    }
                });
            });
        }
    })();
</script>