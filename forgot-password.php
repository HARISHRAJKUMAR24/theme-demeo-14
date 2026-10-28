<?php include_once __DIR__ . "/includes/files_includes.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once __DIR__ . "/includes/head_links.php"; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />

    <style>
        :root {
            --primary: <?= htmlspecialchars(getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ed143b') ?>;
            --hover-color: <?= htmlspecialchars(getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ec4899') ?>;
        }

        /* intl-tel-input full width */
        .iti { width: 100% !important; }
        .iti input { width: 100% !important; }

        /* Primary Button Style */
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

    <!-- Forgot Password Section Start -->
    <main class="flex-grow flex items-center justify-center py-12 px-4 sm:px-6">
        <div class="w-full max-w-[420px]">

            <!-- Header -->
            <div class="text-center mb-8">
                <?php if (getSettings("logo")) : ?>
                    <img src="<?= UPLOADS_URL . getSettings("logo") ?>" alt="Logo" class="mx-auto mb-4 h-12 w-auto object-contain">
                <?php else : ?>
                    <div class="mx-auto mb-4 w-12 h-12 rounded-full flex items-center justify-center text-lg font-bold text-white" style="background-color: var(--primary);">
                        <?= substr($storeName, 0, 1) ?>
                    </div>
                <?php endif; ?>

                <h1 class="text-2xl font-bold uppercase tracking-tight text-[#292d38] sm:text-[28px]">Forgot Password</h1>
                <div class="mx-auto mt-3 h-[2px] w-[30px]" style="background-color: var(--primary);"></div>
                <p class="text-gray-500 text-sm mt-4">
                    <?php if ((int)getSeller("credits") <= 0) { ?>
                        Enter your email address to reset your password.
                    <?php } else { ?>
                        Enter your phone number to reset your password.
                    <?php } ?>
                </p>
            </div>

            <!-- Form -->
            <form id="form" class="space-y-5" autocomplete="off">

                <!-- Step 1 -->
                <div id="step1">
                    <?php if ((int)getSeller("credits") <= 0) { ?>
                        <div class="mb-4">
                            <label for="email" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-gray-700">Email Address</label>
                            <input type="email" name="user" id="email" placeholder="Enter your email"
                                class="h-[46px] w-full rounded border border-gray-200 px-4 text-sm outline-none transition focus:border-[var(--primary)]">
                        </div>
                    <?php } else { ?>
                        <div class="mb-4">
                            <label for="phone" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-gray-700">Phone Number</label>
                            <input type="tel" name="user" id="phone" placeholder="Enter your phone number"
                                class="h-[46px] w-full rounded border border-gray-200 px-4 text-sm outline-none transition focus:border-[var(--primary)]">
                        </div>
                    <?php } ?>

                    <button id="sendOtp" type="button"
                        class="theme16-btn flex h-12 w-full items-center justify-center rounded text-sm font-semibold uppercase tracking-wider">
                        Send OTP
                    </button>
                </div>

                <!-- Step 2 -->
                <div id="step2" style="display: none;">

                    <div id="msg" class="p-3 mb-4 text-sm rounded" style="background: color-mix(in srgb, var(--primary) 10%, white); color: var(--primary);"></div>

                    <div class="mb-4">
                        <label for="otp" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-gray-700">OTP Code</label>
                        <input type="number" name="otp" id="otp" placeholder="Enter OTP code"
                            class="h-[46px] w-full rounded border border-gray-200 px-4 text-sm outline-none transition focus:border-[var(--primary)]">
                    </div>

                    <div class="mb-4">
                        <label for="new_password" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-gray-700">New Password</label>
                        <input type="password" name="new_password" id="new_password" placeholder="Enter new password"
                            class="h-[46px] w-full rounded border border-gray-200 px-4 text-sm outline-none transition focus:border-[var(--primary)]">
                    </div>

                    <div class="mb-4">
                        <label for="confirm_password" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-gray-700">Confirm Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm new password"
                            class="h-[46px] w-full rounded border border-gray-200 px-4 text-sm outline-none transition focus:border-[var(--primary)]">
                    </div>

                    <button id="change" type="button"
                        class="theme16-btn flex h-12 w-full items-center justify-center rounded text-sm font-semibold uppercase tracking-wider">
                        Change Password
                    </button>
                </div>

            </form>

            <!-- Back to Login -->
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-600">
                    Remembered your password?
                    <a href="<?= $storeUrl ?>login" class="font-semibold hover:underline ml-1" style="color: var(--primary);">
                        Login
                    </a>
                </p>
            </div>

        </div>
    </main>
    <!-- Forgot Password Section End -->

    <?php include_once __DIR__ . "/includes/footer.php"; ?>

    <!-- Intl Tel Input JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

    <script>
        // Initialize intl-tel-input only if phone field exists
        const phoneInputField = document.querySelector("#phone");
        if (phoneInputField) {
            const phoneInput = window.intlTelInput(phoneInputField, {
                initialCountry: "in",
                separateDialCode: true,
                onlyCountries: ['IN'],
                preferredCountries: ["<?= getSettings("country") ?>"],
                hiddenInput: "full",
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
            });
        }
    </script>

    <script src="<?= APP_URL ?>shop/javascripts/forgot-password.js"></script>

</body>
</html>