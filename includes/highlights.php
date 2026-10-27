<!-- ================================================== -->
<!-- HIGHLIGHTS TICKER + MIN ORDER (DB COLORS)           -->
<!-- ================================================== -->
<?php
$primary_color = getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ff007f';
$hover_color   = getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ec4899';


$minOrder      = getSettings("minimum_order_amount");
$minOrderText  = !empty($minOrder) ? 'Minimum Order: ' . currencyToSymbol($storeCurrency) . ' ' . $minOrder : '';

// Build ticker items
$tickerItems = [];

if (!empty($minOrderText)) {
    $tickerItems[] = $minOrderText;
}

$tickerItems[] = 'Limited Time Deal - Grab Yours Before It’s Gone';
$tickerItems[] = 'Discount and Offers Available — Shop Now and Save Big';
$tickerItems[] = '100% Secure Checkout · Easy Returns';
$tickerItems[] = 'Loved by 10,000+ Happy Customers';
$tickerItems[] = 'Fast Shipping';
$tickerItems[] = 'GST Invoices';
$tickerItems[] = 'WhatsApp Support Available';
$tickerItems[] = 'EMI Available';
?>

<section class="flex h-[43px] overflow-hidden border-y-2 sm:h-[44px] text-white"
    style="background: <?= htmlspecialchars($hover_color) ?>; border-color: <?= htmlspecialchars($primary_color) ?>;">

    <!-- Left Badge (Primary Color) -->
    <div class="z-10 flex shrink-0 items-center gap-2 px-3 sm:px-5"
        style="background: <?= htmlspecialchars($primary_color) ?>;">
        <svg class="h-[15px] w-[15px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="m12 3 2.7 5.5 6.1.9-4.4 4.3 1 6.1-5.4-2.9-5.4 2.9 1-6.1-4.4-4.3 6.1-.9Z" stroke-linejoin="round" />
        </svg>
        <span class="whitespace-nowrap text-[9px] font-extrabold uppercase tracking-[1.3px]">Highlights</span>
    </div>

    <!-- Scrolling Ticker -->
    <div class="min-w-0 flex-1 overflow-hidden">
        <div class="flex h-full w-max animate-[marquee_55s_linear_infinite] hover:[animation-play-state:paused] max-sm:animate-[marquee_75s_linear_infinite]">

            <!-- First set -->
            <div class="flex h-full shrink-0 items-center">
                <?php foreach ($tickerItems as $item): ?>
                    <span class="mx-7 whitespace-nowrap text-[11px] font-semibold text-gray-300">
                        <!-- Dots (Primary Color) -->
                        <span class="mr-3" style="color: <?= htmlspecialchars($primary_color) ?>;">•</span>
                        <?= htmlspecialchars($item) ?>
                    </span>
                <?php endforeach; ?>
            </div>

            <!-- Duplicate set (seamless loop) -->
            <div class="flex h-full shrink-0 items-center" aria-hidden="true">
                <?php foreach ($tickerItems as $item): ?>
                    <span class="mx-7 whitespace-nowrap text-[11px] font-semibold text-gray-300">
                        <!-- Dots (Primary Color) -->
                        <span class="mr-3" style="color: <?= htmlspecialchars($primary_color) ?>;">•</span>
                        <?= htmlspecialchars($item) ?>
                    </span>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
</section>

<!-- Marquee keyframes (only if not already defined elsewhere) -->
<style>
    @keyframes marquee {
        from { transform: translateX(0); }
        to   { transform: translateX(-50%); }
    }
</style>