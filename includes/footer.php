<!-- Footer Start -->
<?php
$primary_color = getData('color', 'seller_settings', "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ff007f';
$hover_color   = getData('hover_color', 'seller_settings', "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ec4899';

function hexToRgbaFooter($hex, $opacity = 0.08)
{
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) == 3) $hex = "$hex[0]$hex[0]$hex[1]$hex[1]$hex[2]$hex[2]";
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    return "rgba($r, $g, $b, $opacity)";
}

// Fetch pages for Quick Links
$footerPages = getPages();

// Check if any social links exist
$hasSocialLinks = getSettings("facebook") || getSettings("instagram") || getSettings("twitter") || getSettings("linkedin") || getSettings("youtube") || getSettings("pinterest") || getSettings("sharechat");
?>

<footer id="contact" class="bg-[#24151b] text-white px-6 md:px-12 py-12" style="background-color: <?= $hover_color ?>;">
    <div class="container mx-auto">
        <!-- Changed to 4 columns so Brand (Logo + Social) sits on the left -->
        <div class="grid md:grid-cols-4 gap-10">

            <!-- Column 1 — Brand & Social (Left Side) -->
            <div>
                <!-- Logo / Store Name -->
                <div class="mb-5">
                    <?php if (getSettings("logo")): ?>
                        <img src="<?= UPLOADS_URL . getSettings("logo") ?>"
                            alt="<?= htmlspecialchars($storeName) ?>"
                            class="h-12 w-auto object-contain">
                    <?php else: ?>
                        <div class="font-serifDisplay text-3xl" style="color: #ffffff;">
                            <?= htmlspecialchars($storeName) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <p class="text-sm opacity-70 mt-3 leading-relaxed mb-6">
                    <?= htmlspecialchars(getSettings("address") ?: 'Timeless quality for every home.') ?>
                </p>

                <?php if (getSettings("gst_number")): ?>
                    <p class="text-xs opacity-60 mt-2">GSTIN: <?= htmlspecialchars(getSettings("gst_number")) ?></p>
                <?php endif; ?>
                <?php if (getSettings("fssai_number")): ?>
                    <p class="text-xs opacity-60 mt-1">FSSAI: <?= htmlspecialchars(getSettings("fssai_number")) ?></p>
                <?php endif; ?>

                <!-- Social Links Moved Under Brand -->
                <?php if ($hasSocialLinks): ?>
                    <h4 class="tracking-widest text-xs mt-6 mb-4" style="color: <?= $hover_color ?>;">FOLLOW US</h4>
                    <div class="flex flex-wrap gap-3">
                        <?php if (getSettings("facebook")): ?>
                            <a href="<?= getSettings("facebook") ?>" target="_blank"
                                class="w-9 h-9 flex items-center justify-center border border-white/20 rounded-full hover:bg-white/10 transition"
                                onmouseover="this.style.borderColor='<?= $primary_color ?>'"
                                onmouseout="this.style.borderColor='rgba(255,255,255,0.2)'">
                                <i class='bx bxl-facebook text-sm'></i>
                            </a>
                        <?php endif; ?>
                        <?php if (getSettings("instagram")): ?>
                            <a href="<?= getSettings("instagram") ?>" target="_blank"
                                class="w-9 h-9 flex items-center justify-center border border-white/20 rounded-full hover:bg-white/10 transition"
                                onmouseover="this.style.borderColor='<?= $primary_color ?>'"
                                onmouseout="this.style.borderColor='rgba(255,255,255,0.2)'">
                                <i class='bx bxl-instagram text-sm'></i>
                            </a>
                        <?php endif; ?>
                        <?php if (getSettings("twitter")): ?>
                            <a href="<?= getSettings("twitter") ?>" target="_blank"
                                class="w-9 h-9 flex items-center justify-center border border-white/20 rounded-full hover:bg-white/10 transition"
                                onmouseover="this.style.borderColor='<?= $primary_color ?>'"
                                onmouseout="this.style.borderColor='rgba(255,255,255,0.2)'">
                                <i class='bx bxl-twitter text-sm'></i>
                            </a>
                        <?php endif; ?>
                        <?php if (getSettings("linkedin")): ?>
                            <a href="<?= getSettings("linkedin") ?>" target="_blank"
                                class="w-9 h-9 flex items-center justify-center border border-white/20 rounded-full hover:bg-white/10 transition"
                                onmouseover="this.style.borderColor='<?= $primary_color ?>'"
                                onmouseout="this.style.borderColor='rgba(255,255,255,0.2)'">
                                <i class='bx bxl-linkedin text-sm'></i>
                            </a>
                        <?php endif; ?>
                        <?php if (getSettings("youtube")): ?>
                            <a href="<?= getSettings("youtube") ?>" target="_blank"
                                class="w-9 h-9 flex items-center justify-center border border-white/20 rounded-full hover:bg-white/10 transition"
                                onmouseover="this.style.borderColor='<?= $primary_color ?>'"
                                onmouseout="this.style.borderColor='rgba(255,255,255,0.2)'">
                                <i class='bx bxl-youtube text-sm'></i>
                            </a>
                        <?php endif; ?>
                        <?php if (getSettings("pinterest")): ?>
                            <a href="<?= getSettings("pinterest") ?>" target="_blank"
                                class="w-9 h-9 flex items-center justify-center border border-white/20 rounded-full hover:bg-white/10 transition"
                                onmouseover="this.style.borderColor='<?= $primary_color ?>'"
                                onmouseout="this.style.borderColor='rgba(255,255,255,0.2)'">
                                <i class='bx bxl-pinterest text-sm'></i>
                            </a>
                        <?php endif; ?>
                        <?php if (getSettings("sharechat")): ?>
                            <a href="<?= getSettings("sharechat") ?>" target="_blank"
                                class="w-9 h-9 flex items-center justify-center border border-white/20 rounded-full hover:bg-white/10 transition"
                                onmouseover="this.style.borderColor='<?= $primary_color ?>'"
                                onmouseout="this.style.borderColor='rgba(255,255,255,0.2)'">
                                <img src="<?= APP_URL ?>assets/img/sharechat.webp" alt="ShareChat" class="w-4 h-4">
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Column 2 — Quick Links -->
            <div>
                <h4 class="tracking-widest text-xs mb-4 relative inline-block pb-1">
                    QUICK LINKS
                    <!-- Colored Underline -->
                    <span class="absolute left-0 bottom-0 h-[2px] w-full" style="background-color: <?= $primary_color ?>;"></span>
                </h4>

                <?php if ($footerPages && $footerPages->rowCount()): ?>
                    <?php foreach ($footerPages as $page): ?>
                        <a class="block text-sm opacity-70 py-1 hover:opacity-100 transition-opacity duration-200"
                            href="<?= $storeUrl . 'pages/' . $page['slug'] ?>"
                            onmouseover="this.style.color='<?= $primary_color ?>'"
                            onmouseout="this.style.color=''">
                            <?= htmlspecialchars($page['name']) ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>

                <a class="block text-sm opacity-70 py-1 hover:opacity-100 transition-opacity duration-200"
                    href="<?= $storeUrl ?>profile"
                    onmouseover="this.style.color='<?= $primary_color ?>'"
                    onmouseout="this.style.color=''">Profile</a>
                <a class="block text-sm opacity-70 py-1 hover:opacity-100 transition-opacity duration-200"
                    href="<?= $storeUrl ?>delivery-areas"
                    onmouseover="this.style.color='<?= $primary_color ?>'"
                    onmouseout="this.style.color=''">Delivery Areas</a>
                <a class="block text-sm opacity-70 py-1 hover:opacity-100 transition-opacity duration-200"
                    href="<?= $storeUrl ?>track-order"
                    onmouseover="this.style.color='<?= $primary_color ?>'"
                    onmouseout="this.style.color=''">Track Order</a>
                <a class="block text-sm opacity-70 py-1 hover:opacity-100 transition-opacity duration-200"
                    href="<?= $storeUrl ?>wishlists"
                    onmouseover="this.style.color='<?= $primary_color ?>'"
                    onmouseout="this.style.color=''">Wishlists</a>
                <a class="block text-sm opacity-70 py-1 hover:opacity-100 transition-opacity duration-200"
                    href="<?= $storeUrl ?>blog"
                    onmouseover="this.style.color='<?= $primary_color ?>'"
                    onmouseout="this.style.color=''">Blogs</a>
            </div>

            <!-- Column 3 — Contact -->
            <div>
                <h4 class="tracking-widest text-xs mb-4 relative inline-block pb-1">
                    CONTACT
                    <!-- Colored Underline -->
                    <span class="absolute left-0 bottom-0 h-[2px] w-full" style="background-color: <?= $primary_color ?>;"></span>
                </h4>

                <?php if (getSettings("phone")): ?>
                    <a href="tel:<?= getSettings("phone") ?>"
                        class="block text-sm opacity-70 py-1 hover:opacity-100 transition-opacity duration-200"
                        onmouseover="this.style.color='<?= $primary_color ?>'"
                        onmouseout="this.style.color=''">
                        <i class='bx bx-phone text-xs mr-2'></i>
                        <?= htmlspecialchars(formatPhoneNumber(getSettings("phone"))) ?>
                    </a>
                <?php endif; ?>

                <?php if (getSettings("email")): ?>
                    <a href="mailto:<?= getSettings("email") ?>"
                        class="block text-sm opacity-70 py-1 hover:opacity-100 transition-opacity duration-200"
                        onmouseover="this.style.color='<?= $primary_color ?>'"
                        onmouseout="this.style.color=''">
                        <i class='bx bx-envelope text-xs mr-2'></i>
                        <?= htmlspecialchars(getSettings("email")) ?>
                    </a>
                <?php endif; ?>

                <?php if (getSettings("whatsapp_number")): ?>
                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', getSettings("whatsapp_number")) ?>"
                        target="_blank"
                        class="block text-sm opacity-70 py-1 hover:opacity-100 transition-opacity duration-200"
                        onmouseover="this.style.color='<?= $primary_color ?>'"
                        onmouseout="this.style.color=''">
                        <i class='bx bxl-whatsapp text-xs mr-2'></i>
                        WhatsApp
                    </a>
                <?php endif; ?>

                <?php if (getSettings("address")): ?>
                    <p class="text-sm opacity-70 py-1 leading-relaxed">
                        <i class='bx bx-map text-xs mr-2'></i>
                        <?= htmlspecialchars(getSettings("address")) ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Column 4 — About Store / Disclaimer -->
            <div>
                <h4 class="tracking-widest text-xs mb-4 relative inline-block pb-1">
                    ABOUT STORE
                    <!-- Colored Underline -->
                    <span class="absolute left-0 bottom-0 h-[2px] w-full" style="background-color: <?= $primary_color ?>;"></span>
                </h4>
                <p class="text-sm opacity-70 leading-relaxed mb-4">
                    <?= htmlspecialchars(getSettings("address") ?: 'Timeless quality for every home.') ?>
                </p>
            </div>

        </div>

        <!-- Copyright -->
        <div class="border-t border-white/10 mt-10 pt-5 text-xs opacity-60 text-center">
            Copyright © <?= date('Y') ?>, <?= htmlspecialchars($storeName) ?> · All Rights Reserved.
            <span class="block sm:inline">
                · Powered by
                <a href="https://ztorespot.com/?utm_campaign=poweredby&utm_medium=ztorespot&utm_source=onlinestore&utm_tag=<?= urlencode($storeName) ?>"
                    target="_blank"
                    class="hover:opacity-100 opacity-80 underline">
                    Ztorespot
                </a>
            </span>
        </div>

        <!-- Disclaimer -->
        <?php if (getData("disclaimer", "settings") && !$isCustomDomain): ?>
            <div class="mt-6 pt-5 border-t border-white/10 text-center">
                <h3 class="text-xs tracking-widest opacity-60 mb-2">DISCLAIMER</h3>
                <p class="text-xs opacity-50 leading-relaxed max-w-3xl mx-auto">
                    <?= getData("disclaimer", "settings") ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
</footer>
<!-- Footer End -->



<script>
    $(document).on("click", ".searchBtn", function(e) {
        e.preventDefault();
        const searchInput = $(".searchInput");
        const searchCategory = $(".searchCategory");
        let url = '';
        if (typeof searchCategory.val() === 'undefined') {
            url = '<?= $storeUrl ?>search/' + encodeURIComponent(searchInput.val());
        } else {
            url = '<?= $storeUrl ?>search/' + encodeURIComponent(searchInput.val()) + "?category=" + searchCategory.val();
        }
        if (searchInput.val() !== "") window.location.href = url;
    });
</script>


<script src="<?= APP_URL ?>themes/theme13/js/main.js"></script>


<!--This File For my Customise Js For my Theme 13-->
<script src="<?= APP_URL ?>themes/theme13/js/script.js"></script>

<!--This File For Cart Functions For Theme 9-->
<script src="<?= APP_URL ?>themes/theme9/ajax/common-cart.js"></script>

<!--This File For Track Cart & Checkout Theme 9-->
<script src="<?= APP_URL ?>shop/javascripts/utm-tracker.js"></script>

<!--This File For Wishlist-->
<script src="<?= APP_URL ?>themes/theme9/ajax/common-wishlist.js"></script>

<!--This File For Wishlist-->
<script src="<?= APP_URL ?>assets/js/global.js"></script>
<script src="<?= APP_URL ?>assets/js/theme3/app.js"></script>




<!--This File For Checkout -->
<script src="<?= APP_URL ?>assets/js/theme3/checkout.js"></script>
<script src="<?= APP_URL ?>shop/javascripts/checkout.js"></script>


<!-- Datatable JS -->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="<?= APP_URL ?>shop/javascripts/orders.js"></script>