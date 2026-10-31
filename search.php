<?php include_once __DIR__ . "/includes/files_includes.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once __DIR__ . "/includes/head_links.php"; ?>
    <style>
        :root {
            --primary: <?= htmlspecialchars(getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ff007f') ?>;
            --hover-color: <?= htmlspecialchars(getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ec4899') ?>;
        }

        .category-scroll {
            scroll-behavior: smooth;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .category-scroll::-webkit-scrollbar {
            display: none;
        }

        .category-card {
            transition: all 0.3s ease;
        }

        .category-card:hover {
            transform: translateY(-2px);
        }

        .active-category-card {
            background: linear-gradient(135deg, var(--primary), #f59e0b);
            color: white;
        }

        .active-category-card .category-name {
            color: white;
            font-weight: 600;
        }

        .active-category-card .category-icon {
            background: rgba(255, 255, 255, 0.2) !important;
        }

        .sort-dropdown select:focus {
            outline: none;
            box-shadow: 0 0 0 2px var(--primary);
            border-color: var(--primary);
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ==================================================== */
        /* DEFAULT GRID VIEW (Vertical)                         */
        /* ==================================================== */
        .product-card-wrapper {
            height: 100%;
        }

        .product-card-inner {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .product-card-image {
            width: 100%;
            height: 220px;
            border-bottom: 1px solid #f3f4f6;
        }

        .product-card-content {
            text-align: center;
        }

        /* Hide description in grid view */
        .product-card-description {
            display: none !important;
        }

        /* ==================================================== */
        /* ✅ LIST VIEW (Full Width + Horizontal Layout)        */
        /* ==================================================== */

        /* Force the wrapper to span the full width of the grid */
        .list-view .product-card-wrapper {
            grid-column: 1 / -1 !important;
            width: 100% !important;
            max-width: 100% !important;
        }

        /* Make the inner layout horizontal */
        .list-view .product-card-inner {
            flex-direction: row !important;
            align-items: stretch;
        }

        /* Image on the Left */
        .list-view .product-card-image {
            width: 250px !important;
            height: auto;
            min-height: 220px;
            border-bottom: none;
            border-right: 1px solid #f3f4f6;
        }

        /* Content on the Right */
        .list-view .product-card-content {
            text-align: left !important;
            padding: 1.5rem 2rem !important;
            justify-content: flex-start !important;
            display: flex;
            flex-direction: column;
        }

        /* Show description in list view */
        .list-view .product-card-description {
            display: -webkit-box !important;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }

        /* Fix button and variant widths in list view so they don't stretch */
        .list-view .product-card-variants,
        .list-view .addToCartWrapper {
            max-width: 300px;
        }

        /* Adjust price and views alignment for list view */
        .list-view .product-card-price {
            justify-content: flex-start !important;
            margin-top: 0.5rem;
        }

        .list-view .text-xs.text-gray-500 {
            justify-content: flex-start !important;
            margin-bottom: 0.5rem;
        }

        /* Simple category sidebar: compact square thumbnails and clean text links */
        .category-sidebar-list {
            display: flex;
            flex-direction: column;
        }

        .category-sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            min-height: 48px;
            padding: 7px 2px;
            color: #374151;
            text-decoration: none;
            border-bottom: 1px solid #f1f2f4;
            transition: color .18s ease, background-color .18s ease;
        }

        .category-sidebar-link:last-child {
            border-bottom: 0;
        }

        .category-sidebar-link:hover {
            color: var(--primary);
        }

        .category-sidebar-image,
        .category-sidebar-placeholder {
            width: 30px;
            height: 30px;
            flex: 0 0 30px;
            object-fit: contain;
            background: #f8f8f9;
        }

        .category-sidebar-placeholder {
            display: block;
        }

        .category-sidebar-name {
            min-width: 0;
            flex: 1;
            font-size: 12px;
            font-weight: 500;
            line-height: 1.35;
        }

        .category-sidebar-arrow {
            color: #b0b4bc;
            font-size: 18px;
            line-height: 1;
        }

        .category-sidebar-link:hover .category-sidebar-arrow {
            color: var(--primary);
        }

        /* Mobile adjustments for list view */
        @media (max-width: 640px) {
            .list-view .product-card-inner {
                flex-direction: column !important;
            }

            .list-view .product-card-image {
                width: 100% !important;
                border-right: none;
                border-bottom: 1px solid #f3f4f6;
            }
        }
    </style>
</head>

<body class="font-sans bg-gray-50 min-h-screen" style="--primary: <?= htmlspecialchars(getData('color', 'seller_settings', "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ff007f') ?>;">

    <?php include_once __DIR__ . "/includes/nav-bar.php"; ?>

    <?php
    if (isset($_GET['q'])) {
        $q = $_GET['q'];
    } else {
        redirect($storeUrl);
    }

    $count = getProducts(array("count" => true, "q" => $q));
    foreach ($count as $key => $value) {
        $count = $value[0];
    }

    if (isset($_GET['category'])) {
        $category = $_GET['category'];
    } else {
        $category = '';
    }

    // Get search results
    $search_products_stmt = getProducts(array("q" => $q, "category" => $category));
    $search_products = $search_products_stmt->fetchAll(PDO::FETCH_ASSOC);
    $search_products_count = count($search_products);

    // Categories for navigation
    $categories = getCategories([
        "add_to_menu" => 1,
        "parent_category" => " ",
    ]);
    ?>



    <!-- Main Content -->
    <div class="py-8">
        <div class="w-full max-w-[1920px] mx-auto px-2 sm:px-4 lg:px-6">
            <div class="flex flex-col gap-4 lg:flex-row">

                <!-- Left Sidebar: Categories + Price Filter -->
                <aside class="w-full lg:w-[240px] shrink-0">
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden lg:sticky lg:top-24">

                        <?php if (!empty($categories)): ?>
                            <div class="px-4 pt-4">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-800 pb-3 border-b border-gray-100">
                                    Categories
                                </h3>

                                <nav class="category-sidebar-list" aria-label="Product categories">
                                    <?php foreach ($categories as $cat): ?>
                                        <a
                                            href="<?= htmlspecialchars($storeUrl . 'category/' . $cat['slug']) ?>"
                                            class="category-sidebar-link">
                                            <?php if (!empty($cat['icon'])): ?>
                                                <img
                                                    src="<?= htmlspecialchars(UPLOADS_URL . $cat['icon']) ?>"
                                                    alt=""
                                                    class="category-sidebar-image"
                                                    loading="lazy"
                                                    onerror="this.style.display='none';">
                                            <?php else: ?>
                                                <span class="category-sidebar-placeholder"></span>
                                            <?php endif; ?>
                                            <span class="category-sidebar-name"><?= htmlspecialchars($cat['name']) ?></span>
                                            <span class="category-sidebar-arrow">›</span>
                                        </a>
                                    <?php endforeach; ?>
                                </nav>
                            </div>
                        <?php endif; ?>

                        <div class="p-4 mt-2 border-t border-gray-100">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-800 mb-4 pb-3 border-b border-gray-100">
                                Price Range
                            </h3>
                            <form id="priceForm">
                                <div class="flex flex-col gap-3">
                                    <input type="number" name="min_price" value="<?= isset($_GET['min_price']) ? htmlspecialchars($_GET['min_price']) : '' ?>" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md focus:outline-none focus:border-[var(--primary)]" placeholder="Min (<?= currencyToSymbol($storeCurrency) ?>)">
                                    <input type="number" name="max_price" value="<?= isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : '' ?>" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md focus:outline-none focus:border-[var(--primary)]" placeholder="Max (<?= currencyToSymbol($storeCurrency) ?>)">
                                </div>
                                <button type="submit" class="flex items-center justify-center px-3 py-2 text-xs font-bold uppercase tracking-wider text-white transition rounded-md w-full mt-3 hover:opacity-90" style="background-color: var(--primary);">
                                    Apply Filter
                                </button>
                            </form>
                        </div>
                    </div>
                </aside>

                <!-- Right Products Section -->
                <div class="flex-1 w-full min-w-0">

                    <!-- Toolbar -->
                    <div class="bg-white border border-gray-200 rounded-lg p-3 sm:p-4 mb-6 flex flex-wrap items-center justify-between gap-4">
                        <!-- View Toggles -->
                        <div class="flex items-center gap-1 bg-gray-100 p-1 rounded-md">
                            <button id="gridButton" class="p-1.5 rounded text-white transition" style="background-color: var(--primary);">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M3 3h7v7H3V3zm11 0h7v7h-7V3zM3 14h7v7H3v-7zm11 0h7v7h-7v-7z" />
                                </svg>
                            </button>
                            <button id="listButton" class="p-1.5 rounded text-gray-500 hover:text-gray-800 transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M3 4h4v4H3V4zm6 0h12v4H9V4zM3 10h4v4H3v-4zm6 0h12v4H9v-4zM3 16h4v4H3v-4zm6 0h12v4H9v-4z" />
                                </svg>
                            </button>
                        </div>

                        <!-- Sorting -->
                        <div class="flex items-center gap-4 text-sm">
                            <div class="text-sm text-gray-500">
                                Showing <span class="font-bold text-gray-800"><?= $search_products_count ?></span> results
                            </div>
                            <div class="sort-dropdown relative w-full sm:w-auto">
                                <select id="sortSelect" class="w-full sm:w-48 pl-4 pr-8 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none bg-white cursor-pointer appearance-none">
                                    <option value="default">Sort By: Default</option>
                                    <option value="low-high">Price: Low to High</option>
                                    <option value="high-low">Price: High to Low</option>
                                    <option value="latest">Latest First</option>
                                    <option value="oldest">Oldest First</option>
                                </select>
                                <i class="bi bi-funnel absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-x-2 gap-y-4 w-full" id="content">
                        <?php if (!empty($search_products)): ?>
                            <?php foreach ($search_products as $product): ?>
                                <div class="product-card" data-name="<?= htmlspecialchars($product['name']) ?>" data-price="<?= $product['price'] ?>">
                                    <?= getProductHtml($product['id']) ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!-- Empty State -->
                            <div class="col-span-full py-16 text-center">
                                <div class="max-w-md mx-auto">
                                    <div class="w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-6" style="background-color: color-mix(in srgb, var(--primary) 15%, white);">
                                        <svg class="w-10 h-10" style="stroke: var(--primary);" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <h2 class="text-xl font-bold text-gray-900 mb-2">No Products Found</h2>
                                    <p class="text-sm text-gray-500 mb-6">We couldn't find any products matching "<?= htmlspecialchars($q) ?>"</p>
                                    <a href="<?= $storeUrl ?>" class="inline-block px-6 py-2.5 text-white text-sm font-bold uppercase tracking-wider rounded-lg hover:opacity-90 transition" style="background-color: var(--primary);">
                                        Back to Store
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once __DIR__ . "/includes/footer.php"; ?>

    <script>
        // ============================================
        // SEARCH PAGE JS - TOOLBAR, VARIANT & CART LOGIC
        // ============================================
        document.addEventListener('DOMContentLoaded', function() {

            // --------------------------------------------
            // 1. PRICE FILTER
            // --------------------------------------------
            document.getElementById('priceForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const min = document.querySelector('input[name="min_price"]').value;
                const max = document.querySelector('input[name="max_price"]').value;
                if (min && max && Number(min) > Number(max)) {
                    alert('Min price cannot be greater than max');
                    return;
                }
                const url = new URL(window.location.href);
                if (min) url.searchParams.set('min_price', min);
                else url.searchParams.delete('min_price');
                if (max) url.searchParams.set('max_price', max);
                else url.searchParams.delete('max_price');
                window.location.href = url.toString();
            });

            // --------------------------------------------
            // 2. GRID / LIST VIEW TOGGLE
            // --------------------------------------------
            const contentContainer = document.getElementById('content');
            const products = Array.from(document.querySelectorAll(".product-card"));
            const gridButton = document.getElementById("gridButton");
            const listButton = document.getElementById("listButton");

            gridButton.addEventListener('click', function() {
                contentContainer.className = "grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-x-2 gap-y-4 w-full";
                gridButton.className = "p-1.5 rounded text-white transition";
                gridButton.style.backgroundColor = "var(--primary)";
                listButton.className = "p-1.5 rounded text-gray-500 hover:text-gray-800 transition";
                listButton.style.backgroundColor = "transparent";

                products.forEach(p => {
                    p.classList.remove("list-view");
                    p.querySelector(".group").classList.remove("flex-row");
                });
            });

            listButton.addEventListener('click', function() {
                contentContainer.className = "grid grid-cols-1 gap-4 w-full";
                gridButton.className = "p-1.5 rounded text-gray-500 hover:text-gray-800 transition";
                gridButton.style.backgroundColor = "transparent";
                listButton.className = "p-1.5 rounded text-white transition";
                listButton.style.backgroundColor = "var(--primary)";

                products.forEach(p => {
                    p.classList.add("list-view");
                    p.querySelector(".group").classList.add("flex-row");
                });
            });

            // --------------------------------------------
            // 3. SORTING
            // --------------------------------------------
            document.getElementById('sortSelect').addEventListener('change', function() {
                sortProducts(this.value);
            });

            function sortProducts(sortType) {
                if (!contentContainer) return;
                const productItems = Array.from(contentContainer.querySelectorAll('.product-card'));

                switch (sortType) {
                    case 'low-high':
                        productItems.sort((a, b) => Number(a.dataset.price) - Number(b.dataset.price));
                        break;
                    case 'high-low':
                        productItems.sort((a, b) => Number(b.dataset.price) - Number(a.dataset.price));
                        break;
                    case 'latest':
                        productItems.sort((a, b) => getProductId(b) - getProductId(a));
                        break;
                    case 'oldest':
                        productItems.sort((a, b) => getProductId(a) - getProductId(b));
                        break;
                    default:
                        return;
                }
                contentContainer.innerHTML = '';
                productItems.forEach(product => contentContainer.appendChild(product));
            }

            function getProductId(element) {
                const card = element.querySelector('.group');
                return parseInt(card.getAttribute('data-product-id')) || 0;
            }

            // --------------------------------------------
            // 4. VARIANT SELECTION & ADD TO CART LOGIC
            // --------------------------------------------
            document.querySelectorAll('.variantSelect').forEach(select => {
                select.addEventListener('change', function() {
                    const selected = this.options[this.selectedIndex];
                    const card = this.closest('.group');
                    const addBtn = card.querySelector('.customAddToCartBtn');
                    const viewCartBtn = card.querySelector('.viewCartBtn');

                    if (!selected.value) return;

                    const img = selected.dataset.image;
                    const price = selected.dataset.price;
                    const mrp = selected.dataset.mrp;
                    const stock = Number(selected.dataset.stock) || 0;
                    const unlimited = Number(selected.dataset.unlimited) || 0;
                    const variantType = selected.dataset.variantType;
                    const variantValue = selected.value;
                    const inCart = selected.dataset.inCart === '1';

                    // Update Image & Price
                    const imageEl = card.querySelector('.productImage');
                    const priceEl = card.querySelector('.productPrice');
                    const mrpEl = card.querySelector('.productMrp');

                    if (imageEl && img) imageEl.src = img;
                    if (priceEl) priceEl.textContent = "<?= currencyToSymbol($storeCurrency) ?>" + Number(price).toLocaleString();
                    if (mrpEl) {
                        if (mrp && mrp > 0) {
                            mrpEl.textContent = "<?= currencyToSymbol($storeCurrency) ?>" + Number(mrp).toLocaleString();
                            mrpEl.style.display = 'inline';
                        } else {
                            mrpEl.style.display = 'none';
                        }
                    }

                    // Update Button Data Attributes
                    addBtn.dataset.variant = "";
                    addBtn.dataset.advancedvariant = "";

                    if (variantType === 'main') {
                        addBtn.dataset.variant = "";
                        addBtn.dataset.advancedvariant = "";
                    } else if (variantType === 'advanced') {
                        addBtn.dataset.advancedvariant = variantValue;
                    } else {
                        addBtn.dataset.variant = variantValue;
                    }

                    const isOutOfStock = stock <= 0 && unlimited !== 1;

                    // Update Button State
                    if (inCart) {
                        addBtn.classList.add('hidden');
                        viewCartBtn.classList.remove('hidden');
                    } else {
                        addBtn.classList.remove('hidden');
                        viewCartBtn.classList.add('hidden');
                        addBtn.disabled = false;

                        if (isOutOfStock) {
                            addBtn.disabled = true;
                            addBtn.innerHTML = 'Sold Out';
                            addBtn.classList.remove('bg-[var(--primary-color)]', 'text-white');
                            addBtn.classList.add('bg-gray-200', 'text-gray-400', 'cursor-not-allowed');
                        } else {
                            addBtn.innerHTML = 'Add to Bag';
                            addBtn.classList.remove('bg-gray-200', 'text-gray-400', 'cursor-not-allowed');
                            addBtn.classList.add('bg-[var(--primary-color)]', 'text-white');
                        }
                    }
                });
            });

            // Handle Add to Cart Click
            document.addEventListener('click', function(e) {
                if (e.target.closest('.customAddToCartBtn')) {
                    const btn = e.target.closest('.customAddToCartBtn');
                    if (btn.disabled) return;

                    e.preventDefault();
                    const productId = btn.dataset.id;
                    const variant = btn.dataset.variant || "";
                    const advancedVariant = btn.dataset.advancedvariant || "";
                    const card = btn.closest('.group');
                    const viewCartBtn = card.querySelector('.viewCartBtn');

                    const originalText = btn.innerHTML;
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';

                    $.ajax({
                        url: 'shop/ajax/add-to-cart.php',
                        type: 'POST',
                        data: {
                            product_id: productId,
                            variant: variant,
                            advanced_variant: advancedVariant
                        },
                        success: function(result) {
                            btn.disabled = false;
                            btn.innerHTML = originalText;

                            let response;
                            try {
                                response = JSON.parse(result);
                            } catch (err) {
                                response = {
                                    success: true,
                                    message: "Added to cart"
                                };
                            }

                            if (response.success) {
                                btn.classList.add('hidden');
                                if (viewCartBtn) viewCartBtn.classList.remove('hidden');
                                showCustomToast('Added to cart successfully!', 'success');
                                updateCartCounts();
                            } else {
                                showCustomToast(response.message || 'Failed to add', 'error');
                            }
                        },
                        error: function() {
                            btn.disabled = false;
                            btn.innerHTML = originalText;
                            showCustomToast('Network error', 'error');
                        }
                    });
                }
            });

            // --------------------------------------------
            // 5. TOAST & CART COUNTS
            // --------------------------------------------
            function showCustomToast(message, type) {
                const existing = document.querySelectorAll('.custom-toast');
                existing.forEach(t => t.remove());

                const toast = document.createElement('div');
                toast.className = 'custom-toast fixed top-24 right-4 z-[999] px-5 py-3 rounded-lg shadow-xl text-sm font-semibold text-white transition-opacity duration-300 flex items-center gap-2';
                toast.style.backgroundColor = type === 'success' ? 'var(--primary)' : '#ef4444';
                toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i> <span>${message}</span>`;

                document.body.appendChild(toast);
                setTimeout(() => {
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }

            function updateCartCounts() {
                $.ajax({
                    url: 'shop/ajax/get-product-count-and-price.php',
                    success: function(result) {
                        try {
                            const response = JSON.parse(result);
                            $('.cartItemsCount').text(response.itemsCount || '0');
                            $('.cartItemsPrice').text(response.price || '0');
                        } catch (e) {}
                    }
                });
            }
        });
    </script>
</body>

</html>