<?php include_once __DIR__ . "/includes/files_includes.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once __DIR__ . "/includes/head_links.php"; ?>
    <style>
        /* Phone Number Input Field Full Width Fix */
        .iti {
            width: 100% !important;
        }

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

    <!-- ================= LOGIN PAGE ================= -->
    <main class="flex-grow mx-auto w-full max-w-[1360px] px-4 py-10 sm:px-6 sm:py-14 lg:px-8">

        <div class="mx-auto max-w-[480px]">

            <!-- PAGE TITLE -->
            <div class="mb-8 text-center">
                <h1 class="text-2xl font-bold uppercase tracking-tight text-[#292d38] sm:text-[28px]">My Account</h1>
                <div class="mx-auto mt-3 h-[2px] w-[30px]" style="background-color: var(--primary-color);"></div>
                <p class="mt-3 text-sm text-gray-500">Login to your account to continue.</p>
            </div>

            <!-- AUTH CARD -->
            <div class="border border-gray-200 bg-white">

                <!-- TABS -->
                <div class="flex border-b border-gray-200">
                    <a href="<?= $storeUrl ?>login" class="auth-tab active flex-1 px-4 py-4 text-center text-sm font-semibold uppercase tracking-wide text-[#292d38] transition sm:px-6">
                        Login
                    </a>
                    <a href="<?= $storeUrl ?>register" class="auth-tab flex-1 px-4 py-4 text-center text-sm font-semibold uppercase tracking-wide text-gray-500 transition hover:text-[#292d38] sm:px-6">
                        Register
                    </a>
                </div>

                <!-- ================= LOGIN FORM ================= -->
                <form id="form" class="space-y-5 p-6 sm:p-8">
                    <div id="step1">

                        <!-- Email / Phone -->
                        <div class="mb-4" id="emailWrapper" style="display:none;">
                            <label for="email" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-gray-700">Email or Phone</label>
                            <input type="email" name="email" id="email" placeholder="Email or Phone"
                                class="h-[46px] w-full rounded border border-gray-200 px-4 text-sm outline-none transition focus:border-[var(--primary-color)] email-and-phone">
                        </div>

                        <div class="mb-4" id="phoneWrapper">
                            <label for="phone" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-gray-700">Phone or Email</label>
                            <input type="tel" name="phone[main]" id="phone" placeholder="Phone or Email"
                                class="h-[46px] w-full rounded border border-gray-200 px-4 text-sm outline-none transition focus:border-[var(--primary-color)] email-and-phone">
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-gray-700">Password</label>
                                <a href="<?= $storeUrl ?>forgot-password" class="text-[var(--primary-color)] text-xs font-semibold hover:underline">Forgot Password?</a>
                            </div>
                            <div class="relative">
                                <input type="password" name="password" id="password" placeholder="Enter a password" required
                                    class="h-[46px] w-full rounded border border-gray-200 px-4 pr-12 text-sm outline-none transition focus:border-[var(--primary-color)]">
                               
                            </div>
                        </div>

                        <!-- Login Button -->
                        <button type="button" id="sendOtp"
                            class="flex h-12 w-full items-center justify-center rounded bg-[var(--primary-color)] text-sm font-semibold uppercase tracking-wider text-white transition hover:opacity-90 login">
                            Login
                        </button>
                    </div>
                </form>

                <!-- Register Link -->
                <div class="border-t border-gray-200 p-6 text-center sm:p-8">
                    <p class="text-sm text-gray-600">
                        Don't have an account?
                        <a href="<?= $storeUrl ?>register" class="font-semibold text-[var(--primary-color)] hover:underline ml-1">Create one</a>
                    </p>
                </div>

            </div>
        </div>
    </main>

    <?php include_once __DIR__ . "/includes/footer.php"; ?>

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
        document.querySelectorAll(".toggle-password").forEach(function(btn) {
            btn.addEventListener("click", function() {
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
    <script src="<?= APP_URL ?>shop/javascripts/login.js"></script>
</body>

</html>