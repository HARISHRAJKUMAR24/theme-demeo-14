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

// ==================== SHUFFLE SECTION ORDER ====================
$sectionKeys = ['hero_products', 'mingled_products', 'sub_collections'];
shuffle($sectionKeys);

$shuffledPool = $allProducts;
shuffle($shuffledPool);
$heroProducts = array_slice($shuffledPool, 0, 4);
$gridProducts = array_slice($shuffledPool, 4, 15);
$scrollProducts = array_slice($shuffledPool, 19, 10);
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

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Unique Hover Effect */
        .editorial-hover {
            transition: all 0.4s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        .editorial-hover:hover {
            transform: translateY(-6px);
        }
    </style>
</head>

<body class="font-sans bg-[#f4f4f0] min-h-screen text-[#111]">

    <?php include_once __DIR__ . "/includes/nav-bar.php"; ?>

    <!-- ============================================ -->
    <!-- 1. ASYMMETRIC HERO (Reduced Size)            -->
    <!-- ============================================ -->
    <section class="w-full px-4 sm:px-8 lg:px-12 pt-12 pb-16 border-b border-black/10">
        <div class="container mx-auto max-w-7xl">
            <div class="flex flex-col lg:flex-row gap-8 lg:gap-16 items-end">

                <!-- Typography (Reduced from 140px to 90px) -->
                <div class="w-full lg:w-2/3">
                    <p class="text-xs tracking-[0.3em] uppercase font-bold text-gray-500 mb-4">Catalog Vol. 01</p>
                    <h1 class="font-display text-5xl sm:text-6xl md:text-7xl lg:text-[90px] leading-[0.9] uppercase tracking-tighter mb-4">
                        The<br>
                        <span class="text-transparent" style="-webkit-text-stroke: 2px <?= htmlspecialchars($primary_color) ?>;">Shop</span><br>
                        All.
                    </h1>
                </div>

                <!-- Stats -->
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
    <!-- 2. HORIZONTAL SCROLLING MARQUEE (FIXED)      -->
    <!-- ============================================ -->
    <style>
        @keyframes marquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .animate-marquee {
            animation: marquee 25s linear infinite;
            display: flex;
            width: max-content;
        }

        .animate-marquee:hover {
            animation-play-state: paused;
        }
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
    // Create a pool of categories that have images
    $dualStagePool = $parentCategories;
    $dualStageWithIcon = array_filter($dualStagePool, fn($c) => !empty($c['icon']));

    // If we have at least 2 categories with icons, use only those
    if (count($dualStageWithIcon) >= 2) {
        $dualStagePool = array_values($dualStageWithIcon);
    }

    // Shuffle the pool to guarantee randomness on every load
    shuffle($dualStagePool);

    // Pick the first 2 random categories
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
    <!-- ✅ LATEST ARRIVALS CAROUSEL                  -->
    <!-- ============================================ -->
    <div class="py-4">
        <?= getLatestArrivalsHtml(8) ?>
    </div>

    <!-- ============================================ -->
    <!-- 4. RANDOM SECTIONS                           -->
    <!-- ============================================ -->
    <?php foreach ($sectionKeys as $key): ?>

        <!-- A) HERO PRODUCTS -->
        <?php if ($key === 'hero_products' && !empty($heroProducts)): ?>
            <section class="w-full px-4 sm:px-8 py-16">
                <div class="container mx-auto max-w-7xl">

                    <div class="flex flex-col md:flex-row justify-between items-end mb-8 border-b-2 border-black pb-4">
                        <div>
                            <h2 class="font-display text-3xl sm:text-5xl uppercase tracking-tighter">Spotlight</h2>
                            <p class="text-xs font-bold tracking-widest uppercase text-gray-500 mt-2">Fresh Drops / Curated</p>
                        </div>
                        <a href="<?= $storeUrl ?>shop-all" class="mt-4 md:mt-0 text-xs font-bold uppercase tracking-widest border-b border-black pb-1 hover:text-[<?= htmlspecialchars($primary_color) ?>] hover:border-[<?= htmlspecialchars($primary_color) ?>] transition-colors">
                            View All
                        </a>
                    </div>

                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                        <?php foreach ($heroProducts as $product): ?>
                            <div class="editorial-hover">
                                <?= getProductHtml($product['id']); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <!-- B) MINGLED PRODUCTS -->
        <?php elseif ($key === 'mingled_products' && !empty($gridProducts)): ?>
            <section class="w-full px-4 sm:px-8 py-16 bg-white">
                <div class="container mx-auto max-w-7xl">
                    <div class="mb-8">
                        <h2 class="font-display text-3xl sm:text-5xl uppercase tracking-tighter">The Archive</h2>
                        <p class="text-xs font-bold tracking-widest uppercase text-gray-500 mt-2"><?= count($gridProducts) ?> Items Found</p>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 gap-y-8">
                        <?php foreach ($gridProducts as $product): ?>
                            <div class="editorial-hover">
                                <?= getProductHtml($product['id']); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <!-- C) SUB-COLLECTIONS -->
        <?php elseif ($key === 'sub_collections' && !empty($allSubs)): ?>
            <section class="w-full px-4 sm:px-8 py-16 border-b border-black/10">
                <div class="container mx-auto max-w-7xl">
                    <div class="flex justify-between items-end mb-6">
                        <h2 class="font-display text-2xl sm:text-4xl uppercase tracking-tighter">Sub-Categories</h2>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-500">Scroll horizontally →</p>
                    </div>

                    <div class="flex gap-4 overflow-x-auto hide-scrollbar pb-6 -mx-4 px-4 sm:mx-0 sm:px-0">
                        <?php foreach ($allSubs as $sub):
                            $subIcon = !empty($sub['icon']) ? UPLOADS_URL . $sub['icon'] : null;
                        ?>
                            <a href="<?= $storeUrl . 'category/' . $sub['slug'] ?>" class="group shrink-0 w-[160px] sm:w-[180px]">
                                <div class="relative w-full aspect-[4/5] overflow-hidden bg-gray-100 border border-gray-200">
                                    <?php if ($subIcon): ?>
                                        <img src="<?= htmlspecialchars($subIcon) ?>" alt="<?= htmlspecialchars($sub['name']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    <?php endif; ?>

                                    <div class="absolute top-2 left-2 bg-white px-2 py-1 text-[9px] font-bold uppercase tracking-wider">
                                        Explore
                                    </div>
                                </div>
                                <h3 class="mt-2 font-bold text-xs sm:text-sm uppercase tracking-wide group-hover:text-[<?= htmlspecialchars($primary_color) ?>] transition-colors">
                                    <?= htmlspecialchars($sub['name']) ?>
                                </h3>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    <?php endforeach; ?>

    <!-- ============================================ -->
    <!-- 5. HORIZONTAL SCROLLING PRODUCT RAIL         -->
    <!-- ============================================ -->
    <?php if (!empty($scrollProducts)): ?>
        <section class="w-full py-12 bg-[#111] text-white overflow-hidden">
            <div class="container mx-auto px-4 sm:px-8 mb-6">
                <h2 class="font-display text-2xl sm:text-3xl uppercase tracking-widest">You Might Also Like</h2>
            </div>

            <div class="flex gap-4 overflow-x-auto hide-scrollbar pb-6 px-4 sm:px-8">
                <?php foreach ($scrollProducts as $product): ?>
                    <div class="shrink-0 w-[220px] sm:w-[260px] bg-white text-black rounded-lg overflow-hidden group">
                        <a href="<?= $storeUrl . 'product/' . getData('slug', 'seller_products', "id='{$product['id']}'") ?>" class="block">
                            <div class="aspect-square bg-gray-50 flex items-center justify-center p-3">
                                <img src="<?= UPLOADS_URL . getData('image', 'seller_products', "id='{$product['id']}'") ?>" class="max-h-full max-w-full object-contain group-hover:scale-105 transition-transform duration-500">
                            </div>
                            <div class="p-3">
                                <h3 class="font-bold text-xs truncate"><?= htmlspecialchars(getData('name', 'seller_products', "id='{$product['id']}'")) ?></h3>
                                <p class="text-[<?= htmlspecialchars($primary_color) ?>] font-bold text-sm mt-1"><?= currencyToSymbol($storeCurrency) . number_format(getData('price', 'seller_products', "id='{$product['id']}'")) ?></p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php include_once __DIR__ . "/includes/footer.php"; ?>

</body>

</html>