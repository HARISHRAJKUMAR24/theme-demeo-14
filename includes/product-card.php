<?php
// =====================
// BADGE FUNCTIONS
// =====================
function calculateSavePercent($mrp_price, $price)
{
    $savePercent = 0;
    if ($mrp_price && $mrp_price > $price) {
        $savePercent = round((($mrp_price - $price) / $mrp_price) * 100);
    }
    return $savePercent;
}

function displayProductBadge($badge, $savePercent = 0)
{
    if ($badge || $savePercent > 0) {
        $html = '<div class="absolute top-2 left-2 flex flex-col items-center justify-center min-w-[3.5rem] px-2 py-1 
                       bg-gradient-to-b from-red-600 to-red-800 text-white rounded shadow-lg border-2 border-red-300 border-opacity-50 
                       text-[6px] sm:text-[8px] font-bold uppercase text-center z-10">
                    <span>' . strtoupper($badge ?: 'SAVE') . '</span>';

        if ($savePercent > 0) {
            $html .= '<span class="text-sm font-black leading-none">' . $savePercent . '%</span>';
        }

        $html .= '<div class="absolute -bottom-1 w-4/5 h-1 bg-red-900 rounded-b-lg opacity-80"></div>
                </div>';

        return $html;
    }
    return '';
}

// =====================
// ADD TO CART FUNCTION
// =====================
function addToCartSection($id, $product_id, $cookie_id, $hasAdvancedVariants, $hasBasicVariants, $totalStocks, $isMainInCart = false, $comboEnabled = 0, $comboSelectionCount = 0)
{
    global $storeUrl;
    $isCombo = ($comboEnabled == 1 && $comboSelectionCount > 0);
    $html = '<div class="addToCartWrapper relative mt-3 w-full" data-id="' . $id . '" id="addCart-' . $product_id . '">';

    $isOutOfStock = (!$totalStocks && !getData("id", "seller_products", "id='$id' AND unlimited_stock=1"));

    // Button base styles
    $btnBase = 'relative overflow-hidden px-3 py-2 rounded-none transition-all duration-300 w-full flex items-center justify-center text-[11px] font-bold uppercase tracking-wider';

    // View Cart (Hover color, no icon)
    $viewCartHtml = '<a href="' . $storeUrl . 'cart" class="viewCartBtn hidden px-3 py-2 rounded-none text-white transition w-full flex items-center justify-center text-[11px] font-bold uppercase tracking-wider" style="background-color: var(--hover-color);">
                    View Cart
                  </a>';

    if ($isMainInCart && !$hasAdvancedVariants && !$hasBasicVariants) {
        $html .= '<button type="button" class="customAddToCartBtn hidden ' . $btnBase . ' bg-[var(--primary-color)] text-white hover:opacity-90" data-id="' . $id . '" data-variant="" data-advancedvariant="" data-has-advanced-variants="false" data-has-basic-variants="false">Add to Bag</button>';
        $html .= str_replace('hidden ', '', $viewCartHtml);
    } else if ($hasAdvancedVariants) {
        $html .= '<button type="button" class="customAddToCartBtn ' . $btnBase . ' bg-gray-200 text-gray-400 cursor-not-allowed" data-id="' . $id . '" data-variant="" data-advancedvariant="" disabled data-has-advanced-variants="true" data-has-basic-variants="' . ($hasBasicVariants ? 'true' : 'false') . '">Select Option</button>';
        $html .= $viewCartHtml;
    } else if ($hasBasicVariants) {
        if ($isMainInCart) {
            $html .= '<button type="button" class="customAddToCartBtn hidden ' . $btnBase . ' bg-[var(--primary-color)] text-white hover:opacity-90" data-id="' . $id . '" data-variant="" data-advancedvariant="" data-has-advanced-variants="false" data-has-basic-variants="true">Add to Bag</button>';
            $html .= str_replace('hidden ', '', $viewCartHtml);
        } else {
            $btnClass = $isOutOfStock ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-[var(--primary-color)] text-white hover:opacity-90';
            $btnText = $isOutOfStock ? 'Sold Out' : 'Add to Bag';
            $disabled = $isOutOfStock ? 'disabled' : '';
            $html .= '<button type="button" class="customAddToCartBtn ' . $btnBase . ' ' . $btnClass . '" data-id="' . $id . '" data-variant="" data-advancedvariant="" ' . $disabled . ' data-has-advanced-variants="false" data-has-basic-variants="true">' . $btnText . '</button>';
            $html .= $viewCartHtml;
        }
    } else {
        $btnClass = $isOutOfStock ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-[var(--primary-color)] text-white hover:opacity-90';
        $btnText = $isOutOfStock ? 'Sold Out' : 'Add to Bag';
        $disabled = $isOutOfStock ? 'disabled' : '';
        $html .= '<button type="button" class="customAddToCartBtn ' . $btnBase . ' ' . $btnClass . '" data-id="' . $id . '" data-variant="" data-advancedvariant="" ' . $disabled . ' data-has-advanced-variants="false" data-has-basic-variants="false">' . $btnText . '</button>';
        $html .= $viewCartHtml;
    }

    $html .= '</div>';
    return $html;
}

// =====================
// PRODUCT CARD FUNCTION (THEME 16 - FLEXIBLE LAYOUT)
// =====================
function getProductHtml($id)
{
    global $storeCurrency, $storeUrl, $customerId, $cookie_id, $db;

    $product_id = getData("product_id", "seller_products", "id='$id'") ?: die("Product ID not found");
    $slug       = getData("slug", "seller_products", "id='$id'") ?: die("Slug not found");
    $name       = getData("name", "seller_products", "id='$id'") ?: die("Product name not found");
    $comboEnabled = (int) (getData("combo_enabled", "seller_products", "id='$id'") ?? 0);
    $comboSelectionCount = (int) (getData("combo_selection_count", "seller_products", "id='$id'") ?? 0);
    $image      = getData("image", "seller_products", "id='$id'") ?: die("Product image not found");
    $price      = getData("price", "seller_products", "id='$id'") ?: die("Price not found");
    $mrp_price  = getData("mrp_price", "seller_products", "id='$id'") ?: 0;
    $badge      = getData("badge", "seller_products", "id='$id'");
    $totalStocks = getData("total_stocks", "seller_products", "id='$id'") ?: 0;
    $variation  = getData("variation", "seller_products", "id='$id'");

    // FETCH DESCRIPTION FOR LIST VIEW
    $description = getData("description", "seller_products", "id='$id'");

    $productSizes = [];
    $sizeAttr = '';
    try {
        $stmt = $db->prepare("SELECT DISTINCT size FROM seller_product_advanced_variants WHERE product_id = :product_id AND size IS NOT NULL AND size != ''");
        $stmt->execute([':product_id' => $product_id]);
        $productSizes = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $sizeAttr = !empty($productSizes) ? implode(',', $productSizes) : '';
    } catch (Exception $e) {
    }

    $savePercent = 0;
    if ($badge === "Save" && $mrp_price && $mrp_price > $price) {
        $savePercent = calculateSavePercent($mrp_price, $price);
    }

    $hasAdvancedVariants = getData("id", "seller_product_advanced_variants", "product_id='$product_id'");
    $hasBasicVariants = getData("id", "seller_product_variants", "product_id='$product_id'");

    $cartVariants = [];
    $isMainInCart = false;
    $cartQuery = $db->prepare("SELECT other, advanced_variant FROM customer_cart WHERE customer_id=? AND product_id=?");
    $cartQuery->execute([$cookie_id, $id]);
    $cartItems = $cartQuery->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cartItems as $item) {
        if (!empty($item['advanced_variant'])) $cartVariants[] = $item['advanced_variant'];
        else if (!empty($item['other'])) $cartVariants[] = $item['other'];
        else {
            $cartVariants[] = 'main';
            $isMainInCart = true;
        }
    }

    $inWishlist = false;
    if (isLoggedIn()) {
        $inWishlist = getData("id", "customer_wishlists", "customer_id='$customerId' AND product_id='$id'") ? true : false;
    }

    $btnBg   = $inWishlist ? "bg-white" : "bg-white/80 backdrop-blur-sm";
    $btnText = $inWishlist ? "text-rose-500" : "text-gray-500";

    $wishlist = '<button class="absolute top-2 right-2 w-8 h-8 flex items-center justify-center rounded-full shadow-sm transition transform hover:scale-110 handleWishlist z-20 ' . $btnBg . '" data-id="' . $id . '">
        <i class="fa-solid fa-heart text-sm ' . $btnText . '"></i>  
     </button>';

    // ✅ FLEXIBLE LAYOUT
    $html = '<div class="group product-card-wrapper relative bg-white border border-gray-200 hover:border-[var(--primary-color)] transition-all duration-300 h-full overflow-hidden"
    data-id="' . $id . '"
    data-product-id="' . $product_id . '"
    data-size="' . htmlspecialchars($sizeAttr) . '"
    data-combo-enabled="' . $comboEnabled . '"
    data-combo-count="' . $comboSelectionCount . '"
    data-combo-selections="[]">
            
            <div class="product-card-inner w-full h-full flex">
                
                <!-- Image Area -->
                <div class="product-card-image relative bg-gray-50 flex items-center justify-center p-4 overflow-hidden shrink-0">
                    <a href="' . $storeUrl . 'product/' . $slug . '" class="w-full h-full flex items-center justify-center">
                        <img src="' . UPLOADS_URL . $image . '" alt="' . htmlspecialchars($name) . '" class="productImage max-w-full max-h-[200px] sm:max-h-[250px] object-contain transition-transform duration-500 group-hover:scale-110">
                    </a>';

    $html .= displayProductBadge($badge, $savePercent);
    $html .= '</div>';

    // Content Area
    $html .= '<div class="product-card-content flex flex-col flex-grow text-center p-4 relative w-full">';

    // Wishlist Button
    $html .= $wishlist;

    // Title
    $html .= '<a href="' . $storeUrl . 'product/' . $slug . '" class="product-card-title block text-sm font-semibold leading-5 text-[#353943] transition-colors duration-300 hover:text-[var(--primary-color)] line-clamp-2 min-h-[2.5rem]">' . htmlspecialchars($name) . '</a>';

    // DESCRIPTION (Will only show in List View)
    if (!empty($description)) {
        $html .= '<div class="product-card-description hidden text-xs text-gray-500 leading-relaxed mt-2 text-left">' . strip_tags($description) . '</div>';
    }

    // Variant Dropdown
    if ($hasAdvancedVariants || $hasBasicVariants) {
        $html .= '<div class="product-card-variants mt-3 text-left w-full">
                <div class="relative">
                    <select class="variantSelect block w-full appearance-none border border-gray-200 rounded-none bg-white px-3 py-2 pr-8 text-gray-700 text-xs font-medium focus:outline-none focus:border-[var(--primary-color)]">
                        <option value="" selected disabled>Select Variant</option>';

        $mainStock = getData("unlimited_stock", "seller_products", "id='$id'") ? "Unlimited" : $totalStocks;
        $mainLabel = !empty($variation) ? $variation : $name;
        $html .= '<option value="main" data-image="' . UPLOADS_URL . $image . '" data-price="' . $price . '" data-mrp="' . $mrp_price . '" data-stock="' . $mainStock . '" data-unlimited="' . (getData("unlimited_stock", "seller_products", "id='$id'") ?? 0) . '" data-variant-type="main" data-is-main="true" data-in-cart="' . ($isMainInCart ? '1' : '0') . '">
                ' . htmlspecialchars(mb_strlen($mainLabel) > 25 ? mb_substr($mainLabel, 0, 25) . '...' : $mainLabel) . '</option>';

        if ($hasBasicVariants) {
            $basicVariants = readData("*", "seller_product_variants", "product_id='$product_id'");
            while ($bv = $basicVariants->fetch(PDO::FETCH_ASSOC)) {
                $stock = ($bv['unlimited_stock'] ?? 0) == 1 ? "Unlimited" : (int)($bv['stock'] ?? 0);
                $variantLabel = $bv['variation'] ?? $bv['name'] ?? "Variant";
                $isVariantInCart = in_array($bv['id'], $cartVariants);
                $html .= '<option value="' . $bv['id'] . '" data-image="' . UPLOADS_URL . ($bv['image'] ?? $image) . '" data-price="' . $bv['price'] . '" data-mrp="' . $bv['mrp_price'] . '" data-stock="' . $stock . '" data-unlimited="' . ($bv['unlimited_stock'] ?? 0) . '" data-variant-type="basic" data-is-main="false" data-in-cart="' . ($isVariantInCart ? '1' : '0') . '">
                ' . htmlspecialchars(mb_strlen($variantLabel) > 25 ? mb_substr($variantLabel, 0, 25) . '...' : $variantLabel) . '</option>';
            }
        }

        if ($hasAdvancedVariants) {
            $advancedVariants = readData("*", "seller_product_advanced_variants", "product_id='$product_id'");
            while ($av = $advancedVariants->fetch(PDO::FETCH_ASSOC)) {
                $size = $av['size'] ?? '';
                $colorId = $av['color'] ?? '';
                $colorName = getData("color_name", "product_colors", "id='$colorId'") ?? '';
                $variantLabel = $size ? "Size: $size" : "Option";
                if ($colorName) $variantLabel .= " / $colorName";
                $stock = ($av['unlimited_stock'] ?? 0) == 1 ? "Unlimited" : (int)($av['stock'] ?? 0);
                $isVariantInCart = in_array($av['id'], $cartVariants);
                $html .= '<option value="' . $av['id'] . '" data-image="' . UPLOADS_URL . ($av['image'] ?? $image) . '" data-price="' . $av['price'] . '" data-mrp="' . $av['mrp_price'] . '" data-stock="' . $stock . '" data-unlimited="' . ($av['unlimited_stock'] ?? 0) . '" data-variant-type="advanced" data-is-main="false" data-in-cart="' . ($isVariantInCart ? '1' : '0') . '">
                ' . htmlspecialchars(mb_strlen($variantLabel) > 25 ? mb_substr($variantLabel, 0, 25) . '...' : $variantLabel) . '</option>';
            }
        }

        $html .= '</select>
      <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400">
        <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
      </div></div>';

        if ($comboEnabled && $comboSelectionCount > 0) {
            $html .= '<div class="comboCardSelections mt-2 space-y-1 text-left w-full">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-semibold text-gray-700">Combo Selection</span>
                    <span class="comboCardCounter font-semibold text-gray-500">0 / ' . $comboSelectionCount . '</span>
                </div>
                <div class="comboCardSelectedList flex flex-wrap gap-1"></div>
            </div>';
        }
        $html .= '</div>';
    } else {
        $visitors = getData("visitors", "seller_products", "id='$id'") ?: 0;
        $isUnlimited = getData("unlimited_stock", "seller_products", "id='$id'");
        $isInStock = ($totalStocks > 0 || $isUnlimited);
        $stockText = 'In Stock';
        $stockColor = 'text-green-600';
        if (!$isUnlimited && $totalStocks <= 5 && $totalStocks > 0) {
            $stockText = 'Low Stock';
            $stockColor = 'text-red-600';
        } else if (!$isUnlimited && $totalStocks <= 0) {
            $stockText = 'Out of Stock';
            $stockColor = 'text-red-600';
        }

        $html .= '<div class="mt-2 mb-1 text-xs text-gray-500 flex justify-center gap-3">
            <span class="flex items-center gap-1"><span class="text-red-500">❤️</span> ' . number_format($visitors) . ' views</span>
            <span class="' . $stockColor . ' font-medium">' . $stockText . '</span>
        </div>';
    }

    // Price
    $html .= '<div class="product-card-price mt-2 flex items-center justify-center gap-2">';
    $html .= '<span class="productPrice text-base font-bold text-[var(--primary-color)]">' . currencyToSymbol($storeCurrency) . number_format($price) . '</span>';
    if ($mrp_price && $mrp_price > $price) {
        $html .= '<span class="productMrp text-xs text-gray-400 line-through">' . currencyToSymbol($storeCurrency) . number_format($mrp_price) . '</span>';
    }
    $html .= '</div>';

    // Add to Cart
    $html .= addToCartSection($id, $product_id, $cookie_id, $hasAdvancedVariants, $hasBasicVariants, $totalStocks, $isMainInCart, $comboEnabled, $comboSelectionCount);

    $html .= '</div></div></div>';

    return $html;
}
