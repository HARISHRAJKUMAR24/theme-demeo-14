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

        .category-scroll { scroll-behavior: smooth; scrollbar-width: none; -ms-overflow-style: none; }
        .category-scroll::-webkit-scrollbar { display: none; }
        .category-card { transition: all 0.3s ease; }
        .category-card:hover { transform: translateY(-2px); }
        
        .active-category-card {
            background: linear-gradient(135deg, var(--primary), #f59e0b);
            color: white;
        }
        .active-category-card .category-name { color: white; font-weight: 600; }
        .active-category-card .category-icon { background: rgba(255, 255, 255, 0.2) !important; }

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
    // Fetch Latest 40 Products
    $products_stmt = getProducts(["limit" => 40]);
    $products = $products_stmt->fetchAll(PDO::FETCH_ASSOC);
    $products_count = count($products);

    // Fetch Categories for Top Bar
    $categories = getCategories([
        "add_to_menu" => 1,
        "parent_category" => " ",
    ]);
    ?>

    <!-- New Arrivals Header (Solid Primary Color) -->
    <div class="py-6 text-white" style="background-color: var(--primary);">
        <div class="container mx-auto px-4 text-center">
            <div class="flex flex-col items-center justify-center gap-3">
                <div class="bg-white/20 p-3 rounded-full backdrop-blur-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold uppercase tracking-wide">New Arrivals</h1>
                    <p class="opacity-90 mt-2 text-sm font-medium">
                        <?= $products_count > 0 ? $products_count . " new products just arrived" : "Discover our latest products"; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Navigation Bar -->
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
        <!-- ✅ Reduced side padding to maximize grid width -->
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
                            Showing <span class="font-bold text-gray-800"><?= $products_count ?></span> new items
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

                    <!-- ✅ Product Grid (Wider, less gap) -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-x-2 gap-y-4 w-full" id="content">
                        <?php if (!empty($products)): ?>
                            <?php foreach ($products as $product): ?>
                                <?= getProductHtml($product['id']) ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!-- Empty State -->
                            <div class="col-span-full py-16 text-center">
                                <div class="max-w-md mx-auto">
                                    <div class="w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-6" style="background-color: color-mix(in srgb, var(--primary) 15%, white);">
                                        <svg class="w-10 h-10" style="stroke: var(--primary);" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                        </svg>
                                    </div>
                                    <h2 class="text-xl font-bold text-gray-900 mb-2">No New Products</h2>
                                    <p class="text-sm text-gray-500 mb-6">Check back soon for new arrivals!</p>
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
        // Sorting & Filtering Logic
        const urlParams = new URLSearchParams(window.location.search);
        const minPrice = urlParams.get('min_price') || '';
        const maxPrice = urlParams.get('max_price') || '';

        document.getElementById('priceForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const min = document.querySelector('input[name="min_price"]').value;
            const max = document.querySelector('input[name="max_price"]').value;
            if (min && max && Number(min) > Number(max)) { alert('Min price cannot be greater than max'); return; }
            const url = new URL(window.location.href);
            if (min) url.searchParams.set('min_price', min); else url.searchParams.delete('min_price');
            if (max) url.searchParams.set('max_price', max); else url.searchParams.delete('max_price');
            window.location.href = url.toString();
        });

        document.getElementById('sortSelect').addEventListener('change', function() {
            sortProducts(this.value);
        });

        function sortProducts(sortType) {
            const productsContainer = document.getElementById('content');
            if (!productsContainer) return;
            const products = Array.from(productsContainer.querySelectorAll('.group'));

            switch (sortType) {
                case 'low-high': products.sort((a, b) => getProductPrice(a) - getProductPrice(b)); break;
                case 'high-low': products.sort((a, b) => getProductPrice(b) - getProductPrice(a)); break;
                case 'latest': products.sort((a, b) => getProductId(b) - getProductId(a)); break;
                case 'oldest': products.sort((a, b) => getProductId(a) - getProductId(b)); break;
                default: return;
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
    </script>
</body>
</html>