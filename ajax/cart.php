<?php
// Get cart items
$cartItems = getCartData()->fetchAll(PDO::FETCH_ASSOC);

// Calculate totals & stock initially
$outOfStockCount = 0;
$subTotal = 0.0;
$subTotalWithTax = 0.0;
$gstAmount = 0.0;
$discountAmount = 0;

foreach ($cartItems as $key => $cart) {
    $productId = $cart['product_id'];
    $productData = readData("*", "seller_products", "id='$productId'")->fetch(PDO::FETCH_ASSOC);

    $image = $productData['image'] ?? '';
    $productName = $productData['name'] ?? '';
    $mrp_price = $cart['mrp_price'] ?? $cart['price'];

    // Stock Logic
    $isOutOfStock = false;
    $unlimitedStock = $productData['unlimited_stock'] ?? 0;
    $totalStocks = $productData['total_stocks'] ?? 0;

    $variantId = $cart['other'] ?? null;
    $advVarId = $cart['advanced_variant'] ?? null;

    if (!empty($advVarId)) {
        $advVarData = readData("*", "seller_product_advanced_variants", "id='$advVarId'")->fetch(PDO::FETCH_ASSOC);
        if ($advVarData) {
            if (!empty($advVarData['image'])) $image = $advVarData['image'];
            if (($advVarData['unlimited_stock'] ?? 0) != 1 && ($advVarData['stock'] ?? 0) <= 0) $isOutOfStock = true;
        }
    } elseif (!empty($variantId)) {
        $varData = readData("*", "seller_product_variants", "id='$variantId'")->fetch(PDO::FETCH_ASSOC);
        if ($varData) {
            if (!empty($varData['image'])) $image = $varData['image'];
            if (($varData['unlimited_stock'] ?? 0) != 1 && ($varData['stock'] ?? 0) <= 0) $isOutOfStock = true;
        }
    } else {
        if ($unlimitedStock != 1 && $totalStocks <= 0) $isOutOfStock = true;
    }

    if ($isOutOfStock) $outOfStockCount++;

    // Variant Text
    $variationParts = [];
    if (!empty($variantId)) {
        $varText = getData("variation", "seller_product_variants", "id='$variantId'");
        if ($varText) $variationParts[] = $varText;
    }
    if (!empty($advVarId)) {
        $size = getData("size", "seller_product_advanced_variants", "id='$advVarId'");
        $colorId = getData("color", "seller_product_advanced_variants", "id='$advVarId'");
        $color = $colorId ? getData("color_name", "product_colors", "id='$colorId'") : '';
        if ($size) $variationParts[] = "Size: $size";
        if ($color) $variationParts[] = "Color: $color";
    }
    $variationText = implode(" | ", $variationParts);

    // Save processed data back
    $cartItems[$key]['is_out_of_stock'] = $isOutOfStock;
    $cartItems[$key]['product_name'] = $productName;
    $cartItems[$key]['image'] = $image;
    $cartItems[$key]['variation_text'] = $variationText;

    // Totals calculation
    if (!$isOutOfStock) {
        $qty = (int)$cart['quantity'];
        $price = (float)$cart['price'];
        $subTotal += $price * $qty;

        $gstPercentage = getData("gst_percentage", "seller_products", "id='$productId'");
        if ($gstPercentage !== null && $gstPercentage !== '') {
            $gstCalc = calculateGst($price * $qty, $gstPercentage);
        } else {
            $gstCalc = calculateGst($price * $qty);
        }
        $subTotalWithTax += isset($gstCalc['getPrice']) ? (float)$gstCalc['getPrice'] : ($price * $qty);
        $gstAmount += isset($gstCalc['gstAmount']) ? (float)$gstCalc['gstAmount'] : 0;
    }
}
?>

<script>
    var currentUserId = <?= isset($customerId) && !empty($customerId) ? json_encode($customerId) : 'null' ?>;
</script>

<!-- ================= CART LAYOUT ================= -->
<div class="grid grid-cols-1 gap-8 lg:grid-cols-[1fr_380px] lg:gap-10">

    <!-- ================= LEFT: CART ITEMS ================= -->
    <section>
        <!-- CART HEADER ROW (desktop only) -->
        <div class="mb-3 hidden grid-cols-[2fr_1fr_1fr_1fr_40px] gap-4 border-b border-gray-200 pb-3 text-xs font-bold uppercase tracking-wider text-gray-500 sm:grid">
            <span>Product</span>
            <span class="text-center">Price</span>
            <span class="text-center">Quantity</span>
            <span class="text-center">Total</span>
            <span></span>
        </div>

        <!-- CART ITEMS CONTAINER -->
        <div id="cartItemsContainer" class="divide-y divide-gray-100 border-b border-gray-100 sm:border-b-0">
            <?php if (empty($cartItems)): ?>
                <!-- Empty State -->
                <div class="py-16 text-center">
                    <svg class="mx-auto mb-4 h-16 w-16 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                        <path d="M2.5 3.5h2l2.4 12h11.8l2.2-8.5H5.3" stroke-linecap="round" stroke-linejoin="round" />
                        <circle cx="9" cy="19.5" r="1.2" />
                        <circle cx="17" cy="19.5" r="1.2" />
                    </svg>
                    <p class="mb-6 text-base text-gray-500">Your cart is currently empty.</p>
                    <a href="<?= $storeUrl ?>shop-all" class="inline-flex items-center gap-2 px-7 py-3 text-xs font-semibold uppercase tracking-wider text-white transition hover:opacity-90" style="background: var(--primary-color);">
                        Continue Shopping <span aria-hidden="true">→</span>
                    </a>
                </div>
            <?php else: ?>
                <?php foreach ($cartItems as $cart): ?>
                    <article class="cart-row py-5 sm:grid sm:grid-cols-[2fr_1fr_1fr_1fr_40px] sm:items-center sm:gap-4"
                        data-id="<?= $cart['product_id'] ?>"
                        data-variant="<?= $cart['other'] ?? '' ?>"
                        data-advancedvariant="<?= $cart['advanced_variant'] ?? '' ?>">

                        <!-- Mobile/Desktop: Image & Details -->
                        <div class="flex items-start gap-4 mb-4 sm:mb-0">
                            <a href="<?= $storeUrl ?>product/<?= getData('slug', 'seller_products', "id='{$cart['product_id']}'") ?>" class="shrink-0">
                                <div class="flex h-20 w-20 sm:h-[90px] sm:w-[90px] items-center justify-center overflow-hidden border border-gray-100 bg-white p-2 <?= $cart['is_out_of_stock'] ? 'opacity-50 grayscale' : '' ?>">
                                    <img src="<?= UPLOADS_URL . $cart['image'] ?>" alt="<?= htmlspecialchars($cart['product_name']) ?>" class="h-full w-full object-contain">
                                </div>
                            </a>
                            <div class="min-w-0 flex-1">
                                <a href="<?= $storeUrl ?>product/<?= getData('slug', 'seller_products', "id='{$cart['product_id']}'") ?>" class="text-sm font-semibold leading-6 text-[#292d38] transition hover:text-[var(--primary-color)] line-clamp-2">
                                    <?= htmlspecialchars($cart['product_name']) ?>
                                </a>

                                <?php if (!empty($cart['variation_text'])): ?>
                                    <p class="mt-1 text-xs text-gray-500 line-clamp-1"><?= htmlspecialchars($cart['variation_text']) ?></p>
                                <?php endif; ?>

                                <?php if ($cart['is_out_of_stock']): ?>
                                    <p class="mt-1 text-xs font-semibold text-red-500">Out of stock</p>
                                <?php else: ?>
                                    <p class="mt-1 text-xs text-green-600">In stock</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Mobile/Desktop: Price -->
                        <div class="text-sm text-gray-700 sm:text-center flex justify-between sm:block mb-2 sm:mb-0">
                            <span class="font-medium text-gray-500 sm:hidden">Price:</span>
                            <span><?= currencyToSymbol($storeCurrency) . number_format($cart['price'], 2) ?></span>
                        </div>

                        <!-- Mobile/Desktop: Quantity Controls -->
                        <div class="flex items-center justify-between sm:justify-center mb-2 sm:mb-0">
                            <span class="font-medium text-gray-500 text-sm sm:hidden">Quantity:</span>
                            <?php if ($cart['is_out_of_stock']): ?>
                                <span class="text-xs text-gray-400 italic">Unavailable</span>
                            <?php else: ?>
                                <div class="inline-flex h-[38px] overflow-hidden rounded-md border border-gray-200">
                                    <button type="button" class="decreaseQtyBtn flex w-9 items-center justify-center text-base font-medium text-white transition hover:opacity-90" style="background: var(--primary-color);" data-id="<?= $cart['product_id'] ?>" data-variant="<?= $cart['other'] ?? '' ?>" data-advancedVariant="<?= $cart['advanced_variant'] ?? '' ?>">−</button>
                                    <input type="number" class="currentQty h-full w-12 appearance-none border-0 bg-white text-center text-sm text-gray-800 outline-none focus:ring-0" value="<?= $cart['quantity'] ?>" min="1" max="99" readonly>
                                    <button type="button" class="increaseQtyBtn flex w-9 items-center justify-center text-base font-medium text-white transition hover:opacity-90" style="background: var(--primary-color);" data-id="<?= $cart['product_id'] ?>" data-variant="<?= $cart['other'] ?? '' ?>" data-advancedVariant="<?= $cart['advanced_variant'] ?? '' ?>">+</button>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Mobile/Desktop: Total -->
                        <div class="text-sm font-semibold text-[#292d38] sm:text-center flex justify-between sm:block mb-4 sm:mb-0">
                            <span class="font-medium text-gray-500 sm:hidden">Total:</span>
                            <span><?= currencyToSymbol($storeCurrency) . number_format($cart['price'] * $cart['quantity'], 2) ?></span>
                        </div>

                        <!-- Mobile/Desktop: Remove Button -->
                        <div class="flex justify-end sm:justify-center">
                            <button type="button" class="removeQtyBtn text-gray-400 transition hover:text-red-600" data-id="<?= $cart['product_id'] ?>" data-variant="<?= $cart['other'] ?? '' ?>" data-advancedvariant="<?= $cart['advanced_variant'] ?? '' ?>" title="Remove item">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                                    <path d="M3 6h18M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M10 11v6M14 11v6" stroke-linecap="round" />
                                </svg>
                            </button>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Continue Shopping -->
        <?php if (!empty($cartItems)): ?>
            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <a href="<?= $storeUrl ?>shop-all" class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 transition hover:text-[var(--primary-color)]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M11 6l-6 6 6 6" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Continue Shopping
                </a>
            </div>
        <?php endif; ?>
    </section>

    <!-- ================= RIGHT: ORDER SUMMARY ================= -->
    <?php if (!empty($cartItems)): ?>
        <aside class="lg:sticky lg:top-6 lg:self-start">
            <div class="border border-gray-200 bg-[#fafafa] p-5 sm:p-7">
                <h2 class="mb-5 border-b border-gray-200 pb-4 text-base sm:text-lg font-bold uppercase tracking-wide text-[#292d38]">
                    Order Summary
                </h2>

                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-semibold text-[#292d38]"><?= currencyToSymbol($storeCurrency) . number_format($subTotal, 2) ?></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Shipping</span>
                        <span class="text-gray-600">Calculated at checkout</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Estimated GST</span>
                        <span class="font-semibold text-[#292d38]"><?= getSettings("gst_tax_type") == "inclusive" ? currencyToSymbol($storeCurrency) . "0.00" : currencyToSymbol($storeCurrency) . number_format($gstAmount, 2) ?></span>
                    </div>
                </div>

                <!-- Total -->
                <div class="mt-5 flex items-center justify-between border-t border-gray-200 pt-5">
                    <span class="text-base font-bold uppercase text-[#292d38]">Total</span>
                    <span class="text-lg font-bold" style="color: var(--primary-color);"><?= currencyToSymbol($storeCurrency) . number_format($subTotalWithTax, 2) ?></span>
                </div>

                <!-- Checkout Button -->
                <button onclick="proceedToCheckout()" class="mt-5 flex h-12 w-full items-center justify-center rounded-md text-sm font-semibold uppercase tracking-wider text-white transition duration-300 hover:opacity-90" style="background: var(--primary-color);" <?= ($outOfStockCount > 0) ? 'disabled style="background:#9ca3af;cursor:not-allowed;"' : '' ?>>
                    <?= ($outOfStockCount > 0) ? 'Remove Out of Stock' : 'Proceed to Checkout' ?>
                </button>

                <!-- Trust Badges -->
                <div class="mt-5 space-y-2.5 border-t border-gray-200 pt-5 text-xs text-gray-500">
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4 shrink-0" style="color: var(--primary-color);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M12 3 4 6v6c0 5 3.4 8.5 8 9 4.6-.5 8-4 8-9V6l-8-3Z" stroke-linejoin="round" />
                            <path d="m9 12 2 2 4-4" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span>Secure SSL encrypted checkout</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4 shrink-0" style="color: var(--primary-color);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <rect x="3" y="6" width="18" height="13" rx="2" />
                            <path d="M3 10h18" stroke-linecap="round" />
                        </svg>
                        <span>GST invoice available for clubs</span>
                    </div>
                </div>
            </div>
        </aside>
    <?php endif; ?>
</div>

<!-- ================= JAVASCRIPT LOGIC ================= -->
<script>
    // ✅ SIMPLE TOAST NOTIFICATION
    function showCustomToast(message, type = 'success') {
        const existing = document.querySelectorAll('.simple-toast');
        existing.forEach(t => t.remove());

        const toast = document.createElement('div');
        toast.className = 'simple-toast fixed top-24 right-4 z-[999] px-5 py-3 rounded-lg shadow-xl text-sm font-semibold text-white transition-opacity duration-300 flex items-center gap-2';
        toast.style.backgroundColor = type === 'success' ? '#10b981' : '#ef4444';
        
        toast.innerHTML = `
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
            <span>${message}</span>
        `;
        
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Handle Quantity Changes
        document.addEventListener('click', function(e) {
            if (e.target.closest('.increaseQtyBtn')) {
                const btn = e.target.closest('.increaseQtyBtn');
                const input = btn.parentElement.querySelector('.currentQty');
                updateCartQty(btn.dataset.id, btn.dataset.variant, btn.dataset.advancedvariant, parseInt(input.value) + 1);
            }

            if (e.target.closest('.decreaseQtyBtn')) {
                const btn = e.target.closest('.decreaseQtyBtn');
                const input = btn.parentElement.querySelector('.currentQty');
                if (parseInt(input.value) > 1) {
                    updateCartQty(btn.dataset.id, btn.dataset.variant, btn.dataset.advancedvariant, parseInt(input.value) - 1);
                }
            }

            if (e.target.closest('.removeQtyBtn')) {
                const btn = e.target.closest('.removeQtyBtn');
                if (confirm('Remove item from cart?')) {
                    removeCartItem(btn.dataset.id, btn.dataset.variant, btn.dataset.advancedvariant);
                }
            }
        });

        function updateCartQty(id, variant, advancedVariant, qty) {
            $.ajax({
                url: 'shop/ajax/update-cart.php',
                type: 'POST',
                data: {
                    product_id: id,
                    variant: variant,
                    advanced_variant: advancedVariant,
                    quantity: qty
                },
                success: function(res) {
                    try {
                        const response = JSON.parse(res);
                        if (response.success) {
                            location.reload();
                        } else {
                            showCustomToast(response.message || 'Failed to update', 'error');
                        }
                    } catch (err) {
                        location.reload();
                    }
                }
            });
        }

        function removeCartItem(id, variant, advancedVariant) {
            $.ajax({
                url: 'shop/ajax/remove-from-cart.php',
                type: 'POST',
                data: {
                    product_id: id,
                    variant: variant,
                    advanced_variant: advancedVariant
                },
                success: function(res) {
                    try {
                        const response = JSON.parse(res);
                        if (response.success) {
                            location.reload();
                        } else {
                            showCustomToast(response.message || 'Failed to remove', 'error');
                        }
                    } catch (err) {
                        location.reload();
                    }
                }
            });
        }
    });

    // Checkout Tracking Function
    function proceedToCheckout() {
        const btn = event.target;
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Processing...';

        const userId = typeof currentUserId !== 'undefined' ? currentUserId : null;
        if (!userId) {
            alert('Please login to continue checkout');
            window.location.href = '<?= $storeUrl ?>login';
            return;
        }

        window.location.href = '<?= $storeUrl ?>checkout';
    }
</script>