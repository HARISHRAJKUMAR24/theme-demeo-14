<?php include_once __DIR__ . "/includes/files_includes.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once __DIR__ . "/includes/head_links.php"; ?>
    <style>
        /* Custom Scrollbar for Mobile */
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        /* Remove number input spinners */
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type="number"] {
            -moz-appearance: textfield;
            appearance: textfield;
        }

        /* Toast Animation */
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        .toast-slide-in { animation: slideInRight 0.5s ease-out forwards; }
        .toast-slide-out { animation: slideOutRight 0.5s ease-in forwards; }
    </style>
    <style>
        :root {
            --primary-color: <?= htmlspecialchars(getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ed143b') ?>;
            --hover-color: <?= htmlspecialchars(getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ec4899') ?>;
        }
    </style>
</head>

<body class="bg-white font-sans text-gray-800 antialiased min-h-screen">

    <?php include_once __DIR__ . "/includes/nav-bar.php"; ?>

    <!-- ================= CART PAGE ================= -->
    <main class="mx-auto max-w-[1360px] px-4 py-8 sm:px-6 sm:py-10 lg:px-8">

        <!-- PAGE TITLE -->
        <div class="mb-8 text-center sm:mb-10">
            <h1 class="text-2xl font-bold uppercase tracking-tight text-[#292d38] sm:text-[28px]">Shopping Cart</h1>
            <div class="mx-auto mt-3 h-[2px] w-[30px]" style="background: var(--primary-color);"></div>
        </div>

        <!-- AJAX CONTAINER -->
        <div id="content" class="min-h-[400px]">
            <!-- AJAX CART ITEMS WILL LOAD HERE -->
            <div class="flex justify-center items-center py-20">
                <i class="fas fa-spinner fa-spin text-3xl text-gray-300"></i>
            </div>
        </div>

    </main>

    <?php include_once __DIR__ . "/includes/footer.php"; ?>

    <!-- Cart JS -->
    <script src="<?= APP_URL ?>shop/javascripts/cart.js"></script>
    
    <!-- Load the AJAX cart content automatically -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof loadCart === 'function') {
                loadCart();
            } else if (window.jQuery) {
                // Fallback if cart.js expects a specific trigger
                jQuery('#content').load('shop/ajax/cart.php');
            }
        });
    </script>
</body>
</html>