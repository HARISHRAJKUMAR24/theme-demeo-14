<?php include_once __DIR__ . "/includes/files_includes.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once __DIR__ . "/includes/head_links.php"; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
    
    <style>
        :root {
            --primary-color: <?= htmlspecialchars(getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ed143b') ?>;
            --hover-color: <?= htmlspecialchars(getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ec4899') ?>;
        }

        /* Custom Toast Styles */
        .custom-product-toast {
            z-index: 9999;
        }

        /* Phone Number Input Field Full Width Fix */
        .iti { width: 100% !important; }
    </style>
</head>

<body class="bg-white font-sans text-gray-800 min-h-screen antialiased">

    <?php include_once __DIR__ . "/includes/nav-bar.php"; ?>

    <!-- ================= PROFILE PAGE ================= -->
    <main class="mx-auto max-w-[1360px] px-4 py-8 sm:px-6 sm:py-10 lg:px-8">

        <!-- PAGE TITLE -->
        <div class="mb-8 text-center sm:mb-10">
            <h1 class="text-2xl font-bold uppercase tracking-tight text-[#292d38] sm:text-[28px]">My Account</h1>
            <div class="mx-auto mt-3 h-[2px] w-[30px]" style="background-color: var(--primary-color);"></div>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-[280px_1fr] lg:gap-10">

            <!-- ================= SIDEBAR ================= -->
            <aside>
                <!-- USER CARD -->
                <div class="border border-gray-200 bg-[#fafafa] p-6 text-center">
                    <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full overflow-hidden border-2 border-white shadow-sm" style="background-color: var(--primary-color);">
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
                    <a href="<?= $storeUrl ?>profile" class="profile-nav-item flex items-center gap-3 border-b border-gray-100 px-5 py-3.5 text-sm font-medium text-white transition" style="background-color: var(--primary-color);">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="7" r="4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        My Profile
                    </a>
                    <a href="<?= $storeUrl ?>orders" class="profile-nav-item flex items-center gap-3 border-b border-gray-100 px-5 py-3.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50" style="--hover-color: var(--primary-color);" onmouseover="this.style.color='var(--primary-color)'" onmouseout="this.style.color=''">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M6 2h12l1 4H5l1-4zM4 6v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6M9 11h6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        My Orders
                    </a>
                    <a href="<?= $storeUrl ?>wishlists" class="profile-nav-item flex items-center gap-3 border-b border-gray-100 px-5 py-3.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50" style="--hover-color: var(--primary-color);" onmouseover="this.style.color='var(--primary-color)'" onmouseout="this.style.color=''">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M20.8 8.8c0 4.2-8.8 10-8.8 10S3.2 13 3.2 8.8A4.3 4.3 0 0 1 12 6.6a4.3 4.3 0 0 1 8.8 2.2Z" stroke-linejoin="round" />
                        </svg>
                        Wishlist
                    </a>
                    <a href="<?= $storeUrl ?>logout" class="profile-nav-item flex items-center gap-3 px-5 py-3.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50" style="--hover-color: var(--primary-color);" onmouseover="this.style.color='var(--primary-color)'" onmouseout="this.style.color=''">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Logout
                    </a>
                </nav>
            </aside>

            <!-- ================= MAIN CONTENT (EDIT PROFILE) ================= -->
            <section>
                <div class="border border-gray-200 bg-white p-6 sm:p-8">
                    <h2 class="mb-6 text-lg font-bold uppercase tracking-wide text-[#292d38] border-b border-gray-100 pb-4">Edit Profile</h2>

                    <form id="form" method="post" enctype="multipart/form-data" class="space-y-5">
                        
                        <!-- Photo Upload -->
                        <div class="flex flex-col sm:flex-row items-center gap-6 mb-6">
                            <div class="relative">
                                <img id="previewImage" src="<?= !empty(customer('photo')) ? UPLOADS_URL . customer('photo') : 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=200&q=80' ?>" alt="User Profile" class="w-24 h-24 rounded-full border-4 border-gray-100 object-cover shadow-sm">
                                <label for="photo" class="absolute bottom-0 right-0 w-8 h-8 rounded-full flex items-center justify-center border-2 border-white cursor-pointer hover:opacity-90 transition" style="background-color: var(--primary-color);">
                                    <i class='bx bx-camera text-white text-sm'></i>
                                </label>
                                <input type="file" name="photo" id="photo" class="hidden" accept="image/*">
                            </div>
                            <div class="text-center sm:text-left">
                                <p class="text-sm font-semibold text-[#292d38]">Profile Photo</p>
                                <p class="text-xs text-gray-500 mt-1">Upload a new avatar. Larger than 400x400px recommended.</p>
                            </div>
                        </div>

                        <!-- Name -->
                        <div>
                            <label for="name" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-gray-700">Full Name</label>
                            <input type="text" name="name" id="name" value="<?= customer('name') ?>" class="h-[46px] w-full rounded border border-gray-200 px-4 text-sm outline-none transition focus:border-[var(--primary-color)]" placeholder="Enter your name" required>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-gray-700">Email Address</label>
                            <input type="email" name="email" id="email" value="<?= customer('email') ?>" class="h-[46px] w-full rounded border border-gray-200 px-4 text-sm outline-none transition focus:border-[var(--primary-color)]" placeholder="Enter your email" required>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-gray-700">Phone Number</label>
                            <input type="tel" name="phone[main]" id="phone" value="<?= customer('phone') ?>" class="h-[46px] w-full rounded border border-gray-200 px-4 text-sm outline-none transition focus:border-[var(--primary-color)]" placeholder="Enter your phone" required>
                        </div>

                        <!-- Save Button -->
                        <div class="pt-4">
                            <button type="submit" class="flex h-12 w-full sm:w-auto items-center justify-center rounded px-8 text-sm font-semibold uppercase tracking-wider text-white transition hover:opacity-90" style="background-color: var(--primary-color);">
                                Save Changes
                            </button>
                        </div>

                    </form>
                </div>
            </section>
        </div>
    </main>

    <?php include_once __DIR__ . "/includes/footer.php"; ?>

    <!-- IntlTelInput -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

    <script>
        // Custom toast function
        function showCustomToast(message, type) {
            const existingToasts = document.querySelectorAll('.custom-product-toast');
            existingToasts.forEach(toast => toast.remove());

            const toast = document.createElement('div');
            toast.className = 'custom-product-toast fixed top-24 right-4 z-[999] transform transition-all duration-300 ease-in-out translate-x-full opacity-0';
            toast.innerHTML = `
                <div style="background-color: var(--primary-color) !important; color: white !important;" 
                     class="px-5 py-3 rounded-lg shadow-xl border-l-4 border-white/30 flex items-center gap-3">
                    <i class='bx ${type === 'success' ? 'bx-check-circle' : 'bx-error-circle'} text-xl'></i>
                    <span class="font-semibold text-sm">${message}</span>
                </div>
            `;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
                toast.classList.add('translate-x-0', 'opacity-100');
            }, 10);

            setTimeout(() => {
                toast.classList.remove('translate-x-0', 'opacity-100');
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => { if (toast.parentNode) toast.remove(); }, 300);
            }, 3000);
        }

        // Phone Input (India only)
        const phoneInputField = document.querySelector("#phone");
        const phoneInput = window.intlTelInput(phoneInputField, {
            separateDialCode: true,
            onlyCountries: ["in"],
            initialCountry: "in",
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        });

        // Live Photo Preview
        document.getElementById("photo").addEventListener("change", function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById("previewImage").src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // AJAX Save Profile
        $(document).on("submit", "#form", function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const fullPhoneNumber = phoneInput.getNumber();
            formData.append("phone[full]", fullPhoneNumber);

            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html(`<i class='bx bx-loader-alt animate-spin mr-2'></i> Saving...`);

            $.ajax({
                url: "shop/ajax/profile.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(result) {
                    submitBtn.prop('disabled', false).html(originalText);
                    try {
                        const response = result && JSON.parse(result);
                        if (response) {
                            if (response.success) {
                                showCustomToast(response.message || "Profile updated successfully!", 'success');
                                setTimeout(() => window.location.reload(), 1500);
                            } else {
                                showCustomToast(response.message || "Failed to update profile.", 'error');
                            }
                        } else {
                            showCustomToast("Invalid response from server.", 'error');
                        }
                    } catch (error) {
                        console.error("Error parsing response:", error);
                        showCustomToast("Invalid response from server.", 'error');
                    }
                },
                error: function(xhr, status, error) {
                    submitBtn.prop('disabled', false).html(originalText);
                    showCustomToast("Something went wrong. Please try again.", 'error');
                }
            });
        });
    </script>

</body>
</html>