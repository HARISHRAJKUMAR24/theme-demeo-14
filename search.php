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

    <!-- Search Header (Solid Primary Color) -->
    <div class="py-6 text-white" style="background-color: var(--primary);">
        <div class="container mx-auto px-2 text-center">
            <div class="flex flex-col items-center justify-center gap-3">
                <div class="bg-white/20 p-3 rounded-full backdrop-blur-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold uppercase tracking-wide">Search Results</h1>
                    <p class="opacity-90 mt-2 text-sm font-medium">
                        <?php
                        if ($search_products_count > 0) {
                            echo $search_products_count . " products found for \"" . htmlspecialchars($q) . "\"";
                        } else {
                            echo "No products found for \"" . htmlspecialchars($q) . "\"";
                        }
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Navigation -->
    <?php if (!empty($categories) && $categories->rowCount()): ?>
        <section class="py-4 bg-white border-b shadow-sm">
            <div class="container mx-auto px-4">
                <div id="categoriesScroll" class="category-scroll flex gap-4 overflow-x-auto px-2 py-1 justify-center sm:justify-start">
                    <?php foreach ($categories as $cat): ?>
                        <a href="<?= $storeUrl . "category/" . $cat['slug'] ?>" class="category-card flex-shrink-0 flex flex-col items-center text-center w-20 p-2 rounded-lg">
                            <div class="w-12 h-12 rounded-full mb-2 flex items-center justify-center shadow-sm category-icon" style="background: linear-gradient(to bottom right, var(--primary), #ec4899);">
                                <?php if (!empty($cat['icon'])): ?>
                                    <img src="<?= UPLOADS_URL . $cat['icon'] ?>" alt="<?= $cat['name'] ?>" class="w-8 h-8 object-contain rounded-full" onerror="this.style.display='none'; this.parentNode.innerHTML='<?= strtoupper(substr($cat['name'], 0, 1)) ?>';">
                                <?php else: ?>
                                    <span class="text-white font-bold"><?= strtoupper(substr($cat['name'], 0, 1)) ?></span>
                                <?php endif; ?>
                            </div>
                            <span class="text-[11px] font-semibold text-gray-700 truncate w-full category-name">
                                <?= htmlspecialchars($cat['name']) ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Main Content -->
    <div class="py-8">
        <!-- ✅ Wide Container -->
        <div class="w-full max-w-[1920px] mx-auto px-2 sm:px-4 lg:px-6">
            <div class="flex flex-col gap-4 lg:flex-row">

                <!-- Left Sidebar (Filters) -->
                <div class="lg:w-[220px] shrink-0">
                    <div class="bg-white rounded-xl border border-gray-200 p-5 sticky top-24">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-gray-800 mb-4 border-b pb-3">Price Range</h3>
                        <form id="priceForm">
                            <div class="flex flex-col gap-3">
                                <input type="number" name="min_price" value="<?= isset($_GET['min_price']) ? $_GET['min_price'] : '' ?>" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-[var(--primary)]" placeholder="Min (<?= currencyToSymbol($storeCurrency) ?>)">
                                <input type="number" name="max_price" value="<?= isset($_GET['max_price']) ? $_GET['max_price'] : '' ?>" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-[var(--primary)]" placeholder="Max (<?= currencyToSymbol($storeCurrency) ?>)">
                            </div>
                            <button type="submit" class="flex items-center justify-center gap-2 px-3 py-2 text-xs font-bold uppercase tracking-wider text-white transition rounded-lg w-full mt-4 hover:opacity-90" style="background-color: var(--primary);">
                                Apply Filter
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Right Products Section -->
                <div class="flex-1 w-full min-w-0">
                    <!-- Sorting -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
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

                    <!-- Products Grid (Carousel Style) -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-x-2 gap-y-4 w-full" id="content">
                        <?php if (!empty($search_products)): ?>
                            <?php foreach ($search_products as $product): ?>
                                <?= getProductHtml($product['id']) ?>
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
        // SEARCH PAGE JS - VARIANT & CART LOGIC
        // ============================================
        document.addEventListener('DOMContentLoaded', function() {

            // 1. Price Filter Logic
            const urlParams = new URLSearchParams(window.location.search);
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

            // 2. Sorting Logic
            document.getElementById('sortSelect').addEventListener('change', function() {
                sortProducts(this.value);
            });

            function sortProducts(sortType) {
                const productsContainer = document.getElementById('content');
                if (!productsContainer) return;
                const products = Array.from(productsContainer.querySelectorAll('.group'));

                switch (sortType) {
                    case 'low-high':
                        products.sort((a, b) => getProductPrice(a) - getProductPrice(b));
                        break;
                    case 'high-low':
                        products.sort((a, b) => getProductPrice(b) - getProductPrice(a));
                        break;
                    case 'latest':
                        products.sort((a, b) => getProductId(b) - getProductId(a));
                        break;
                    case 'oldest':
                        products.sort((a, b) => getProductId(a) - getProductId(b));
                        break;
                    default:
                        return;
                }
                productsContainer.innerHTML = '';
                products.forEach(product => productsContainer.appendChild(product));
            }

            function getProductPrice(element) {
                const priceEl = element.querySelector('.productPrice');
                if (priceEl) {
                    const match = priceEl.textContent.match(/(\d+\.?\d*)/);
                    if (match) return parseFloat(match[1]);
                }
                return 0;
            }

            function getProductId(element) {
                return parseInt(element.getAttribute('data-product-id')) || 0;
            }

            // ============================================
            // 3. VARIANT SELECTION & ADD TO CART LOGIC
            // ============================================
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

                    // Update Button State
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

            // ============================================
            // 4. AJAX ADD TO CART
            // ============================================
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

            // Custom Toast
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

            // Update Cart Counts
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