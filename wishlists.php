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

        .wishlist-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            width: 100%;
        }

        @media (min-width: 1024px) {
            .wishlist-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 1.5rem;
            }
        }

        @media (min-width: 1280px) {
            .wishlist-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 640px) {
            .wishlist-grid {
                gap: 0.75rem;
            }
        }

        .product-card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
    </style>
</head>

<body class="font-sans bg-white text-gray-800 min-h-screen antialiased">

    <?php include_once __DIR__ . "/includes/nav-bar.php"; ?>

    <?php $page = basename($_SERVER['PHP_SELF']); ?>

    <!-- ================= WISHLIST PAGE ================= -->
    <main class="mx-auto max-w-[1360px] px-4 py-8 sm:px-6 sm:py-10 lg:px-8">

        <!-- PAGE TITLE -->
        <div class="mb-8 text-center sm:mb-10">
            <h1 class="text-2xl font-bold uppercase tracking-tight text-[#292d38] sm:text-[28px]">My Wishlist</h1>
            <div class="mx-auto mt-3 h-[2px] w-[30px]" style="background-color: var(--primary);"></div>
            <p class="mt-3 text-sm text-gray-500">Quick grab! These products are waiting for you to bring them home.</p>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-[280px_1fr] lg:gap-10">

            <!-- ================= SIDEBAR ================= -->
            <aside>
                <!-- USER CARD -->
                <div class="border border-gray-200 bg-[#fafafa] p-6 text-center">
                    <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full overflow-hidden border-2 border-white shadow-sm" style="background-color: var(--primary);">
                        <?php if(!empty(customer('photo'))): ?>
                            <img src="<?= UPLOADS_URL . customer('photo') ?>" alt="Profile" class="h-full w-full object-cover">
                        <?php else: ?>
                            <span class="text-2xl font-bold text-white uppercase">
                                <?= substr(customer('name'), 0, 1) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <h2 class="text-base font-bold text-[#292d38]"><?= customer('name') ?></h2>
                    <p class="mt-1 text-xs text-gray-500"><?= customer('email') ?></p>
                    <p class="mt-1 text-xs text-gray-500"><?= customer('phone') ?></p>
                </div>

                <!-- NAVIGATION MENU -->
                <nav class="mt-6 border border-gray-200">
                    <a href="<?= $storeUrl ?>profile" class="profile-nav-item flex items-center gap-3 border-b border-gray-100 px-5 py-3.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50" style="--hover-color: var(--primary);" onmouseover="this.style.color='var(--primary-color)'" onmouseout="this.style.color=''">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="7" r="4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        My Profile
                    </a>
                    <a href="<?= $storeUrl ?>orders" class="profile-nav-item flex items-center gap-3 border-b border-gray-100 px-5 py-3.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50" style="--hover-color: var(--primary);" onmouseover="this.style.color='var(--primary-color)'" onmouseout="this.style.color=''">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M6 2h12l1 4H5l1-4zM4 6v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6M9 11h6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        My Orders
                    </a>
                    <!-- Active Wishlist Link -->
                    <a href="<?= $storeUrl ?>wishlists" class="profile-nav-item flex items-center gap-3 border-b border-gray-100 px-5 py-3.5 text-sm font-medium text-white transition" style="background-color: var(--primary);">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M20.8 8.8c0 4.2-8.8 10-8.8 10S3.2 13 3.2 8.8A4.3 4.3 0 0 1 12 6.6a4.3 4.3 0 0 1 8.8 2.2Z" stroke-linejoin="round" />
                        </svg>
                        Wishlist
                    </a>
                    <a href="<?= $storeUrl ?>logout" class="profile-nav-item flex items-center gap-3 px-5 py-3.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50" style="--hover-color: var(--primary);" onmouseover="this.style.color='var(--primary-color)'" onmouseout="this.style.color=''">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Logout
                    </a>
                </nav>
            </aside>

            <!-- ================= MAIN CONTENT ================= -->
            <section>
                <?php
                $productsStmt = getWishlists();
                $products = $productsStmt->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($products)) {
                    echo '<div class="wishlist-grid">';
                    foreach ($products as $product) {
                        echo '<div class="product-card">';
                        echo getProductHtml($product['product_id']);
                        echo '</div>';
                    }
                    echo '</div>';
                } else {
                ?>
                    <!-- Empty Wishlist State -->
                    <div class="border border-gray-200 bg-white p-12 text-center h-full flex flex-col items-center justify-center min-h-[400px]">
                        <div class="w-20 h-20 rounded-full flex items-center justify-center mb-6" style="background-color: color-mix(in srgb, var(--primary) 10%, white);">
                            <svg class="w-10 h-10" style="stroke: var(--primary);" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Your Wishlist is Empty</h3>
                        <p class="text-sm text-gray-500 mb-6 max-w-sm mx-auto">Looks like you haven't added any products yet. Explore our store and save your favourites here.</p>
                        <a href="<?= $storeUrl ?>" class="inline-flex items-center justify-center px-6 py-2.5 text-white text-sm font-bold uppercase tracking-wider rounded-lg hover:opacity-90 transition" style="background-color: var(--primary);">
                            Go to Shop
                        </a>
                    </div>
                <?php
                }
                ?>
            </section>
        </div>
    </main>

    <?php include_once __DIR__ . "/includes/footer.php"; ?>

</body>
</html>