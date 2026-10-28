<?php include_once __DIR__ . "/includes/files_includes.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once __DIR__ . "/includes/head_links.php"; ?>
    <style>
        :root {
            --primary: <?= htmlspecialchars(getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ed143b') ?>;
            --hover-color: <?= htmlspecialchars(getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ec4899') ?>;
        }

        .theme16-btn {
            background-color: var(--primary);
            color: white;
            transition: all 0.3s ease;
        }
        
        .theme16-btn:hover {
            background-color: var(--hover-color);
        }
    </style>
</head>

<body class="bg-white font-sans text-gray-800 min-h-screen antialiased flex flex-col">

    <?php include_once __DIR__ . "/includes/nav-bar.php"; ?>

    <!-- Track Order Section Start -->
    <main class="flex-grow flex items-center justify-center py-12 px-4 sm:px-6">
        <div class="w-full max-w-[480px]">

            <!-- Header -->
            <div class="text-center mb-8">
                <div class="mx-auto mb-4 w-12 h-12 rounded-full flex items-center justify-center text-white" style="background-color: var(--primary);">
                    <i class='bx bx-package text-2xl'></i>
                </div>
                <h1 class="text-2xl font-bold uppercase tracking-tight text-[#292d38] sm:text-[28px]">Track Your Order</h1>
                <div class="mx-auto mt-3 h-[2px] w-[30px]" style="background-color: var(--primary);"></div>
                <p class="text-gray-500 text-sm mt-4">
                    Enter your order ID or invoice number below to check your order status.
                </p>
            </div>

            <!-- Form Card -->
            <div class="border border-gray-200 bg-white p-6 sm:p-8">
                <form action="<?= $storeUrl ?>order">
                    <div class="mb-6">
                        <label for="order_id" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-gray-700">
                            Order ID / Invoice Number
                        </label>
                        <input type="text" id="order_id" name="id"
                            placeholder="e.g. AA1024"
                            class="h-[46px] w-full rounded border border-gray-200 px-4 text-sm outline-none transition focus:border-[var(--primary)]"
                            required>
                    </div>

                    <button type="submit"
                        class="theme16-btn flex h-12 w-full items-center justify-center gap-2 rounded text-sm font-semibold uppercase tracking-wider">
                        Track Order
                        <i class='bx bx-send text-lg'></i>
                    </button>
                </form>
            </div>

            

        </div>
    </main>
    <!-- Track Order Section End -->

    <?php include_once __DIR__ . "/includes/footer.php"; ?>

    <script>
        // Sanitize input — remove # characters
        const order_id = document.getElementById("order_id");
        if (order_id) {
            order_id.addEventListener("input", function() {
                this.value = this.value.replace(/#/g, "");
            });
        }
    </script>

</body>
</html>