<?php include_once __DIR__ . "/includes/files_includes.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once __DIR__ . "/includes/head_links.php"; ?>
    <style>
        /* Phone Number Input Field Full Width Fix */
        .iti { width: 100% !important; }
        
        /* Tab underline animation */
        .auth-tab {
            position: relative;
            transition: color 0.2s ease;
        }
        .auth-tab::after {
            content: "";
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 2px;
            background: var(--primary-color);
            transform: scaleX(0);
            transform-origin: left center;
            transition: transform 0.3s ease;
        }
        .auth-tab.active::after {
            transform: scaleX(1);
        }
    </style>
</head>

<body class="font-sans bg-white text-gray-800 min-h-screen antialiased flex flex-col">

    <?php include_once __DIR__ . "/includes/nav-bar.php"; ?>

    <!-- ================= REGISTER PAGE ================= -->
    <main class="flex-grow mx-auto w-full max-w-[1360px] px-4 py-10 sm:px-6 sm:py-14 lg:px-8">

        <div class="mx-auto max-w-[480px]">

            <!-- PAGE TITLE -->
            <div class="mb-8 text-center">
                <h1 class="text-2xl font-bold uppercase tracking-tight text-[#292d38] sm:text-[28px]">Create Account</h1>
                <div class="mx-auto mt-3 h-[2px] w-[30px]" style="background-color: var(--primary-color);"></div>
                <p class="mt-3 text-sm text-gray-500">Join us to start shopping.</p>
            </div>

            <!-- AUTH CARD -->
            <div class="border border-gray-200 bg-white">

                <!-- TABS -->
                <div class="flex border-b border-gray-200">
                    <a href="<?= $storeUrl ?>login" class="auth-tab flex-1 px-4 py-4 text-center text-sm font-semibold uppercase tracking-wide text-gray-500 transition hover:text-[#292d38] sm:px-6">
                        Login
                    </a>
                    <a href="<?= $storeUrl ?>register" class="auth-tab active flex-1 px-4 py-4 text-center text-sm font-semibold uppercase tracking-wide text-[#292d38] transition sm:px-6">
                        Register
                    </a>
                </div>

                <!-- ================= REGISTER FORM ================= -->
                <form id="form" class="space-y-5 p-6 sm:p-8">
                    <!-- Step 1 -->
                    <div id="step1">
                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-gray-700">Full Name</label>
                            <input type="text" name="name" id="name" placeholder="Enter your name" required
                                class="h-[46px] w-full rounded border border-gray-200 px-4 text-sm outline-none transition focus:border-[var(--primary-color)]">
                        </div>

                        <!-- Phone Number -->
                        <div class="mb-4">
                            <label for="phone" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-gray-700">Phone Number</label>
                            <input type="tel" name="phone[main]" id="phone" placeholder="Enter your phone" required
                                class="h-[46px] w-full rounded border border-gray-200 px-4 text-sm outline-none transition focus:border-[var(--primary-color)]">
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-gray-700">Email Address (optional)</label>
                            <input type="email" name="email" id="email" placeholder="Enter your email"
                                class="h-[46px] w-full rounded border border-gray-200 px-4 text-sm outline-none transition focus:border-[var(--primary-color)]">
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-gray-700">Password</label>
                            <div class="relative">
                                <input type="password" name="password" id="password" placeholder="Enter a password" required
                                    class="h-[46px] w-full rounded border border-gray-200 px-4 pr-12 text-sm outline-none transition focus:border-[var(--primary-color)]">
                               
                            </div>
                        </div>

                        <!-- Send OTP Button -->
                        <button type="button" id="sendOtp"
                            class="flex h-12 w-full items-center justify-center rounded bg-[var(--primary-color)] text-sm font-semibold uppercase tracking-wider text-white transition hover:opacity-90">
                            SEND OTP
                        </button>
                    </div>

                    <!-- Step 2 -->
                    <div id="step2" style="display:none;">
                        <div id="msg" class="p-3 rounded-lg bg-blue-50 text-blue-700 text-sm mb-4 border border-blue-100"></div>

                        <div class="mb-4">
                            <label for="otp" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-gray-700">Enter OTP</label>
                            <input type="number" name="otp" id="otp" placeholder="Enter OTP" required
                                class="h-[46px] w-full rounded border border-gray-200 px-4 text-sm outline-none transition focus:border-[var(--primary-color)]">
                        </div>

                        <!-- Create Account Button -->
                        <button type="button" id="create"
                            class="flex h-12 w-full items-center justify-center rounded bg-[var(--primary-color)] text-sm font-semibold uppercase tracking-wider text-white transition hover:opacity-90">
                            CREATE ACCOUNT
                        </button>
                    </div>
                </form>

                <!-- Login Link -->
                <div class="border-t border-gray-200 p-6 text-center sm:p-8">
                    <p class="text-sm text-gray-600">
                        Already have an account?
                        <a href="<?= $storeUrl ?>login" class="font-semibold text-[var(--primary-color)] hover:underline ml-1">Sign in</a>
                    </p>
                </div>

            </div>
        </div>
    </main>

    <?php include_once __DIR__ . "/includes/footer.php"; ?>

    <script src="<?= APP_URL ?>shop/javascripts/register.js"></script>

    <!-- Js Code For Automatically Take (+91) -->
    <script>
        const phoneInputField = document.querySelector("#phone");
        const phoneInput = window.intlTelInput(phoneInputField, {
            separateDialCode: true,
            onlyCountries: ['IN'],
            preferredCountries: ["<?= getSettings("country") ?>"],
            hiddenInput: "full",
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        });

        // Password Show/Hide
        document.querySelectorAll(".toggle-password").forEach(function (btn) {
            btn.addEventListener("click", function () {
                const targetId = this.getAttribute("data-target");
                const input = document.getElementById(targetId);
                if (input.type === "password") {
                    input.type = "text";
                } else {
                    input.type = "password";
                }
            });
        });
    </script>

</body>
</html>