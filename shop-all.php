<?php include_once __DIR__ . "/includes/files_includes.php";

// ==================== DB COLORS ====================
$primary_color = getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ff007f';
$hover_color   = getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ec4899';

// ==================== FETCH CATEGORIES ====================
$stmt = getCategories();
$allCategories = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
$allCategories = array_reverse($allCategories);

$parentCategories = [];
$subCategoriesMap = [];
$allSubs = [];

foreach ($allCategories as $cat) {
    $parentId = (int)($cat['parent_category'] ?? 0);
    if ($parentId <= 0) {
        $parentCategories[] = $cat;
    } else {
        if (!isset($subCategoriesMap[$parentId])) $subCategoriesMap[$parentId] = [];
        $subCategoriesMap[$parentId][] = $cat;
        $allSubs[] = $cat;
    }
}
if (empty($parentCategories)) $parentCategories = $allCategories;

// ==================== FETCH PRODUCTS ====================
$productsStmt = getProducts();
$allProducts = $productsStmt ? $productsStmt->fetchAll(PDO::FETCH_ASSOC) : [];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once __DIR__ . "/includes/head_links.php"; ?>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Oswald', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Oswald:wght@400;500;600;700&display=swap');

        :root {
            --primary: <?= htmlspecialchars($primary_color) ?>;
            --hover-color: <?= htmlspecialchars($hover_color) ?>;
        }

        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        .editorial-hover { transition: all 0.4s cubic-bezier(0.2, 0.8, 0.2, 1); }
        .editorial-hover:hover { transform: translateY(-6px); }

        .sort-dropdown select:focus, .page-size-select select:focus {
            outline: none;
            box-shadow: 0 0 0 2px var(--primary);
            border-color: var(--primary);
        }

        /* ==================================================== */
        /* DEFAULT GRID VIEW (Vertical)                         */
        /* ==================================================== */
        .product-card-wrapper { height: 100%; }
        .product-card-inner { display: flex; flex-direction: column; height: 100%; }
        .product-card-image { width: 100%; height: 220px; border-bottom: 1px solid #f3f4f6; }
        .product-card-content { text-align: center; }
        .product-card-description { display: none !important; }

        /* ==================================================== */
        /* LIST VIEW (Full Width + Horizontal Layout)           */
        /* ==================================================== */
        .list-view { grid-column: 1 / -1 !important; width: 100% !important; max-width: 100% !important; }
        .list-view .product-card-inner { flex-direction: row !important; align-items: stretch; }
        .list-view .product-card-image { width: 250px !important; height: auto; min-height: 220px; border-bottom: none; border-right: 1px solid #f3f4f6; }
        .list-view .product-card-content { text-align: left !important; padding: 1.5rem 2rem !important; justify-content: flex-start !important; display: flex; flex-direction: column; }
        .list-view .product-card-description { display: -webkit-box !important; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; margin-bottom: 0.5rem; }
        .list-view .product-card-variants, .list-view .addToCartWrapper { max-width: 300px; }
        .list-view .product-card-price { justify-content: flex-start !important; margin-top: 0.5rem; }
        .list-view .product-card-views { justify-content: flex-start !important; margin-bottom: 0.5rem; }

        @media (max-width: 640px) {
            .list-view .product-card-inner { flex-direction: column !important; }
            .list-view .product-card-image { width: 100% !important; border-right: none; border-bottom: 1px solid #f3f4f6; }
        }
    </style>
</head>

<body class="font-sans bg-[#f4f4f0] min-h-screen text-[#111]" style="--primary: <?= htmlspecialchars($primary_color) ?>;">

    <?php include_once __DIR__ . "/includes/nav-bar.php"; ?>

    <!-- ============================================ -->
    <!-- 1. ASYMMETRIC HERO                           -->
    <!-- ============================================ -->
    <section class="w-full px-4 sm:px-8 lg:px-12 pt-12 pb-16 border-b border-black/10">
        <div class="container mx-auto max-w-7xl">
            <div class="flex flex-col lg:flex-row gap-8 lg:gap-16 items-end">
                <div class="w-full lg:w-2/3">
                    <p class="text-xs tracking-[0.3em] uppercase font-bold text-gray-500 mb-4">Catalog Vol. 01</p>
                    <h1 class="font-display text-5xl sm:text-6xl md:text-7xl lg:text-[90px] leading-[0.9] uppercase tracking-tighter mb-4">
                        The<br>
                        <span class="text-transparent" style="-webkit-text-stroke: 2px <?= htmlspecialchars($primary_color) ?>;">Shop</span><br>
                        All.
                    </h1>
                </div>
                <div class="w-full lg:w-1/3 flex flex-col gap-4 pb-2">
                    <div class="w-full h-1 bg-black mb-2"></div>
                    <p class="text-sm sm:text-base text-gray-600 font-medium leading-relaxed">
                        A curated archive of our finest pieces. Browse through <?= count($allProducts) ?> unique items across <?= count($parentCategories) ?> collections.
                    </p>
                    <div class="flex gap-8 mt-2">
                        <div>
                            <p class="font-display text-3xl"><?= count($parentCategories) ?></p>
                            <p class="text-[10px] tracking-widest uppercase font-bold text-gray-500 mt-1">Collections</p>
                        </div>
                        <div>
                            <p class="font-display text-3xl"><?= count($allProducts) ?></p>
                            <p class="text-[10px] tracking-widest uppercase font-bold text-gray-500 mt-1">Products</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- 2. HORIZONTAL SCROLLING MARQUEE              -->
    <!-- ============================================ -->
    <style>
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .animate-marquee { animation: marquee 60s linear infinite; display: flex; width: max-content; }
        .animate-marquee:hover { animation-play-state: paused; }
    </style>

    <?php if (!empty($parentCategories)): ?>
        <section class="w-full bg-[#111] text-white py-3 overflow-hidden relative">
            <div class="animate-marquee">
                <?php for ($i = 0; $i < 2; $i++): ?>
                    <div class="flex items-center">
                        <?php foreach ($parentCategories as $cat): ?>
                            <a href="<?= $storeUrl . 'category/' . $cat['slug'] ?>" class="inline-flex items-center mx-6 font-display text-xl uppercase tracking-widest hover:text-[<?= htmlspecialchars($primary_color) ?>] transition-colors">
                                <?= htmlspecialchars($cat['name']) ?>
                                <span class="mx-4" style="color: <?= htmlspecialchars($primary_color) ?>;">/</span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endfor; ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- ============================================ -->
    <!-- 3. DUAL CATEGORY SPLIT (Randomized)          -->
    <!-- ============================================ -->
    <?php
    $dualStagePool = $parentCategories;
    $dualStageWithIcon = array_filter($dualStagePool, fn($c) => !empty($c['icon']));
    if (count($dualStageWithIcon) >= 2) {
        $dualStagePool = array_values($dualStageWithIcon);
    }
    shuffle($dualStagePool);
    $dualStageCats = array_slice($dualStagePool, 0, 2);
    ?>

    <?php if (count($dualStageCats) >= 2): ?>
        <section class="w-full">
            <div class="grid md:grid-cols-2 gap-1 p-1 bg-[#111]">
                <?php foreach ($dualStageCats as $index => $parent):
                    $parentIcon = !empty($parent['icon']) ? UPLOADS_URL . $parent['icon'] : null;
                    $parentUrl  = $storeUrl . 'category/' . $parent['slug'];
                ?>
                    <a href="<?= $parentUrl ?>" class="group relative block w-full h-[350px] sm:h-[450px] overflow-hidden bg-gray-900">
                        <?php if ($parentIcon): ?>
                            <img src="<?= htmlspecialchars($parentIcon) ?>" alt="<?= htmlspecialchars($parent['name']) ?>" class="absolute inset-0 w-full h-full object-cover opacity-70 group-hover:opacity-100 group-hover:scale-105 transition-all duration-700">
                        <?php endif; ?>
                        <div class="absolute top-6 right-6 text-white/50 font-display uppercase tracking-widest text-xs" style="writing-mode: vertical-rl;">
                            Collection 0<?= $index + 1 ?>
                        </div>
                        <div class="absolute inset-0 flex flex-col justify-end p-6 sm:p-10">
                            <h3 class="font-display text-3xl sm:text-5xl text-white uppercase leading-none mb-3">
                                <?= htmlspecialchars($parent['name']) ?>
                            </h3>
                            <div class="w-12 h-1 mb-5 group-hover:w-24 transition-all duration-500" style="background-color: <?= htmlspecialchars($primary_color) ?>;"></div>
                            <span class="inline-flex items-center text-white text-[10px] sm:text-xs font-bold tracking-[0.2em] uppercase">
                                Explore Category <span class="ml-2 text-base">→</span>
                            </span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- ============================================ -->
    <!-- 4. MAIN SHOP ALL CONTENT (With Toolbar)      -->
    <!-- ============================================ -->
    <section class="w-full py-12 bg-white">
        <div class="container mx-auto max-w-[1500px] px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">

                <!-- Left Sidebar (Filters) -->
                <aside class="lg:w-[250px] shrink-0">
                    <div class="bg-white rounded-xl border border-gray-200 p-5 sticky top-24">
                        <!-- Availability -->
                        <div class="mb-6">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-800 mb-4 border-b border-gray-100 pb-2">Availability</h3>
                            <div class="space-y-2">
                                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                                    <input type="radio" name="availability" value="all" checked class="accent-[var(--primary)]"> All Products
                                </label>
                                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                                    <input type="radio" name="availability" value="in-stock" class="accent-[var(--primary)]"> In Stock
                                </label>
                                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                                    <input type="radio" name="availability" value="out-of-stock" class="accent-[var(--primary)]"> Out of Stock
                                </label>
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-6">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-800 mb-4 border-b border-gray-100 pb-2">Price Range</h3>
                            <div class="flex flex-col gap-3">
                                <input type="number" id="minPrice" placeholder="Min" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-[var(--primary)]">
                                <input type="number" id="maxPrice" placeholder="Max" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-[var(--primary)]">
                            </div>
                            <button id="applyPrice" class="w-full mt-3 py-2 text-xs font-bold uppercase text-white rounded-lg hover:opacity-90 transition" style="background-color: var(--primary);">Apply</button>
                        </div>

                        <button id="clearAll" class="w-full py-2 text-xs font-bold uppercase text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition">Clear All Filters</button>
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

                        <!-- Dropdowns -->
                        <div class="flex items-center gap-4 text-sm">
                            <div class="flex items-center gap-2">
                                <label class="text-gray-500 font-medium hidden sm:block">Show:</label>
                                <div class="relative">
                                    <select id="pageSize" class="appearance-none bg-gray-50 border border-gray-200 rounded-md py-1.5 pl-3 pr-8 font-medium cursor-pointer focus:outline-none">
                                        <option value="9">9</option>
                                        <option value="12">12</option>
                                        <option value="24">24</option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <label class="text-gray-500 font-medium hidden sm:block">Sort:</label>
                                <div class="relative">
                                    <select id="sortProducts" class="appearance-none bg-gray-50 border border-gray-200 rounded-md py-1.5 pl-3 pr-8 font-medium cursor-pointer focus:outline-none">
                                        <option value="featured">Featured</option>
                                        <option value="price-asc">Price: Low to High</option>
                                        <option value="price-desc">Price: High to Low</option>
                                        <option value="name-asc">Name: A to Z</option>
                                        <option value="name-desc">Name: Z to A</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div id="productGrid" class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 gap-y-6">
                        <?php if (!empty($allProducts)): ?>
                            <?php foreach ($allProducts as $product): ?>
                                <div class="product-card" data-name="<?= htmlspecialchars($product['name']) ?>" data-price="<?= $product['price'] ?>" data-stock="<?= ($product['total_stocks'] > 0 || $product['unlimited_stock'] == 1) ? 'in-stock' : 'out-of-stock' ?>">
                                    <?= getProductHtml($product['id']) ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-span-full py-16 text-center">
                                <p class="text-gray-500">No products found.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Pagination -->
                    <div id="pagination" class="mt-10 flex flex-wrap justify-center gap-2"></div>
                    <p id="resultsMessage" class="mt-4 text-center text-sm text-gray-500"></p>
                </div>
            </div>
        </div>
    </section>

    <?php include_once __DIR__ . "/includes/footer.php"; ?>

    <!-- ============================================ -->
    <!-- SCRIPTS                                      -->
    <!-- ============================================ -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            
            // 1. SHOP ALL TOOLBAR
            const productGrid = document.getElementById("productGrid");
            const products = Array.from(document.querySelectorAll(".product-card"));
            const gridButton = document.getElementById("gridButton");
            const listButton = document.getElementById("listButton");
            const pageSizeSelect = document.getElementById("pageSize");
            const sortSelect = document.getElementById("sortProducts");
            const pagination = document.getElementById("pagination");
            const resultsMessage = document.getElementById("resultsMessage");
            const minPriceInput = document.getElementById("minPrice");
            const maxPriceInput = document.getElementById("maxPrice");

            let currentView = "grid";
            let currentPage = 1;
            let activeAvailability = "all";
            let activeMinPrice = "";
            let activeMaxPrice = "";

            function setView(view) {
                currentView = view;
                if (view === "grid") {
                    productGrid.className = "grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 gap-y-6";
                    gridButton.className = "p-1.5 rounded text-white transition";
                    gridButton.style.backgroundColor = "var(--primary)";
                    listButton.className = "p-1.5 rounded text-gray-500 hover:text-gray-800 transition";
                    listButton.style.backgroundColor = "transparent";

                    products.forEach(p => {
                        const innerWrapper = p.querySelector('.product-card-wrapper');
                        if (innerWrapper) innerWrapper.classList.remove("list-view");
                    });
                } else {
                    productGrid.className = "grid grid-cols-1 gap-6";
                    gridButton.className = "p-1.5 rounded text-gray-500 hover:text-gray-800 transition";
                    gridButton.style.backgroundColor = "transparent";
                    listButton.className = "p-1.5 rounded text-white transition";
                    listButton.style.backgroundColor = "var(--primary)";

                    products.forEach(p => {
                        const innerWrapper = p.querySelector('.product-card-wrapper');
                        if (innerWrapper) innerWrapper.classList.add("list-view");
                    });
                }
                renderProducts();
            }

            function getFilteredProducts() {
                let filtered = products.filter(function(product) {
                    const price = Number(product.dataset.price);
                    const stock = product.dataset.stock;
                    const matchesAvailability = activeAvailability === "all" || stock === activeAvailability;
                    const matchesMin = activeMinPrice === "" || price >= Number(activeMinPrice);
                    const matchesMax = activeMaxPrice === "" || price <= Number(activeMaxPrice);
                    return matchesAvailability && matchesMin && matchesMax;
                });

                const sortValue = sortSelect.value;
                if (sortValue === "name-asc") filtered.sort((a, b) => a.dataset.name.localeCompare(b.dataset.name));
                if (sortValue === "name-desc") filtered.sort((a, b) => b.dataset.name.localeCompare(a.dataset.name));
                if (sortValue === "price-asc") filtered.sort((a, b) => Number(a.dataset.price) - Number(b.dataset.price));
                if (sortValue === "price-desc") filtered.sort((a, b) => Number(b.dataset.price) - Number(a.dataset.price));

                return filtered;
            }

            function renderProducts() {
                const filtered = getFilteredProducts();
                const pageSize = Number(pageSizeSelect.value);
                const totalPages = Math.max(1, Math.ceil(filtered.length / pageSize));
                currentPage = Math.min(currentPage, totalPages);
                const start = (currentPage - 1) * pageSize;
                const visibleProducts = filtered.slice(start, start + pageSize);

                products.forEach(p => {
                    p.style.display = "none";
                });

                visibleProducts.forEach(product => {
                    product.style.display = "block";
                    productGrid.appendChild(product);
                });

                pagination.innerHTML = "";
                if (totalPages > 1) {
                    const prevBtn = document.createElement("button");
                    prevBtn.textContent = "Prev";
                    prevBtn.className = "px-4 py-2 border border-gray-200 rounded text-sm transition hover:border-[var(--primary)] disabled:opacity-50";
                    prevBtn.disabled = currentPage === 1;
                    prevBtn.addEventListener("click", () => {
                        if (currentPage > 1) {
                            currentPage--;
                            renderProducts();
                        }
                    });
                    pagination.appendChild(prevBtn);

                    for (let page = 1; page <= totalPages; page++) {
                        const btn = document.createElement("button");
                        btn.textContent = page;
                        btn.className = page === currentPage ?
                            "w-9 h-9 rounded text-white text-sm" :
                            "w-9 h-9 rounded border border-gray-200 text-sm transition hover:border-[var(--primary)]";
                        if (page === currentPage) btn.style.backgroundColor = "var(--primary)";
                        btn.addEventListener("click", () => {
                            currentPage = page;
                            renderProducts();
                        });
                        pagination.appendChild(btn);
                    }

                    const nextBtn = document.createElement("button");
                    nextBtn.textContent = "Next";
                    nextBtn.className = "px-4 py-2 border border-gray-200 rounded text-sm transition hover:border-[var(--primary)] disabled:opacity-50";
                    nextBtn.disabled = currentPage === totalPages;
                    nextBtn.addEventListener("click", () => {
                        if (currentPage < totalPages) {
                            currentPage++;
                            renderProducts();
                        }
                    });
                    pagination.appendChild(nextBtn);
                }

                resultsMessage.textContent = filtered.length ?
                    `Showing ${start + 1}–${Math.min(start + pageSize, filtered.length)} of ${filtered.length} products` :
                    "No products found. Try adjusting your filters.";
            }

            gridButton.addEventListener("click", () => setView("grid"));
            listButton.addEventListener("click", () => setView("list"));
            pageSizeSelect.addEventListener("change", () => { currentPage = 1; renderProducts(); });
            sortSelect.addEventListener("change", () => { currentPage = 1; renderProducts(); });

            document.getElementById("applyPrice").addEventListener("click", () => {
                activeMinPrice = minPriceInput.value.trim();
                activeMaxPrice = maxPriceInput.value.trim();
                currentPage = 1;
                renderProducts();
            });

            document.getElementById("clearAll").addEventListener("click", () => {
                document.querySelector('input[name="availability"][value="all"]').checked = true;
                minPriceInput.value = "";
                maxPriceInput.value = "";
                activeAvailability = "all";
                activeMinPrice = "";
                activeMaxPrice = "";
                sortSelect.value = "featured";
                currentPage = 1;
                renderProducts();
            });

            document.querySelectorAll('input[name="availability"]').forEach(radio => {
                radio.addEventListener("change", () => {
                    activeAvailability = document.querySelector('input[name="availability"]:checked').value;
                    currentPage = 1;
                    renderProducts();
                });
            });

            renderProducts();


            // 2. VARIANT SELECTION & ADD TO CART LOGIC
            document.querySelectorAll('.variantSelect').forEach(select => {
                select.addEventListener('change', function() {
                    const selected = this.options[this.selectedIndex];
                    const card = this.closest('.product-card-wrapper'); 
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
                            addBtn.classList.remove('text-white');
                            addBtn.classList.add('bg-gray-200', 'text-gray-400', 'cursor-not-allowed');
                            addBtn.style.backgroundColor = ''; 
                        } else {
                            addBtn.innerHTML = 'Add Item';
                            addBtn.classList.remove('bg-gray-200', 'text-gray-400', 'cursor-not-allowed');
                            addBtn.classList.add('text-white');
                            addBtn.style.backgroundColor = 'var(--primary)';
                        }
                    }
                });
            });

            document.addEventListener('click', function(e) {
                if (e.target.closest('.customAddToCartBtn')) {
                    const btn = e.target.closest('.customAddToCartBtn');
                    if (btn.disabled) return;

                    e.preventDefault();
                    const productId = btn.dataset.id;
                    const variant = btn.dataset.variant || "";
                    const advancedVariant = btn.dataset.advancedvariant || "";
                    const card = btn.closest('.product-card-wrapper'); 
                    const viewCartBtn = card.querySelector('.viewCartBtn');
                    
                    const originalText = btn.innerHTML;
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                    $.ajax({
                        url: 'shop/ajax/add-to-cart.php',
                        type: 'POST',
                        data: { product_id: productId, variant: variant, advanced_variant: advancedVariant },
                        success: function(result) {
                            btn.disabled = false;
                            btn.innerHTML = originalText;
                            
                            let response;
                            try { response = JSON.parse(result); } 
                            catch (err) { response = { success: true }; }

                            if (response.success) {
                                btn.classList.add('hidden');
                                if (viewCartBtn) viewCartBtn.classList.remove('hidden');
                                
                                const select = card.querySelector('.variantSelect');
                                if (select) {
                                    const selectedOpt = select.options[select.selectedIndex];
                                    if (selectedOpt) selectedOpt.dataset.inCart = '1';
                                }

                                if (window.showCustomToast) window.showCustomToast('Added to cart!', 'success');
                            } else {
                                if (window.showCustomToast) window.showCustomToast(response.message || 'Failed to add', 'error');
                            }
                        },
                        error: function() {
                            btn.disabled = false;
                            btn.innerHTML = originalText;
                            if (window.showCustomToast) window.showCustomToast('Network error', 'error');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>