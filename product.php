<?php include_once __DIR__ . "/includes/files_includes.php"; ?>
<?php
// -------------------------
// Get Random Products For Customer Also Bought
// -------------------------
function getRandomProducts($limit = 3)
{
  $stmt = getProducts();
  $allProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
  shuffle($allProducts);
  return array_slice($allProducts, 0, $limit);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include_once __DIR__ . "/includes/head_links.php"; ?>
  <style>
    /* ============================================
           PRODUCT IMAGE (HOVER TO ZOOM)
           ============================================ */
    .zoom-container {
      position: relative;
      width: 100%;
      height: 320px;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      background: #fff;
      cursor: crosshair;
    }

    @media (min-width: 640px) {
      .zoom-container {
        height: 450px;
      }
    }

    @media (min-width: 1024px) {
      .zoom-container {
        height: 650px;
      }
    }

    #mainProductImage {
      width: 100%;
      height: 100%;
      object-fit: contain;
      transition: transform 0.3s ease-out;
    }

    .zoom-container:hover #mainProductImage {
      transform: scale(1.8);
    }

    .zoom-icon {
      transition: opacity 0.3s ease;
      pointer-events: none;
    }

    .zoom-container:hover .zoom-icon {
      opacity: 0;
    }

    /* ============================================
           OPTION BUTTONS
           ============================================ */
    .product-option {
      transition: all 0.2s ease;
      position: relative;
      overflow: hidden;
    }

    .product-option.selected-option {
      border-color: var(--primary-color) !important;
      color: var(--primary-color) !important;
      background-color: color-mix(in srgb, var(--primary-color) 10%, white) !important;
    }

    /* ============================================
           OUT OF STOCK (AMAZON STYLE)
           ============================================ */
    .product-option.out-of-stock {
      background: #f8f9fa !important;
      border: 1px dashed #d1d5db !important;
      color: #9ca3af !important;
      cursor: not-allowed;
      opacity: 0.85;
    }

    .product-option.out-of-stock::after {
      content: '';
      position: absolute;
      top: 50%;
      left: -10%;
      width: 120%;
      height: 1px;
      background: var(--hover-color, #ec4899);
      transform: rotate(-15deg);
      pointer-events: none;
      opacity: 0.5;
    }


    /* ============================================
       RESPONSIVE PRODUCT QUANTITY & ACTIONS
       ============================================ */
    .product-quantity-wrap {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      gap: 18px;
      margin: 22px 0 20px;
    }

    .product-quantity-label {
      min-width: 85px;
      color: #444;
      font-size: 14px;
      font-weight: 600;
    }

    .product-quantity-control {
      display: inline-flex;
      align-items: stretch;
      height: 42px;
      overflow: hidden;
      border: 1px solid #e5e5e5;
      border-radius: 6px;
      background: #fff;
    }

    .product-quantity-control button {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 42px;
      min-width: 42px;
      border: 0;
      background: var(--primary-color);
      color: #fff;
      font-size: 20px;
      font-weight: 600;
      cursor: pointer;
      transition: opacity .2s ease;
    }

    .product-quantity-control button:hover {
      opacity: .85;
    }

    .product-quantity-control input {
      width: 58px;
      min-width: 0;
      padding: 0;
      border: 0;
      border-right: 1px solid #eee;
      border-left: 1px solid #eee;
      outline: none;
      background: #fff;
      color: #333;
      text-align: center;
      font-size: 15px;
      font-weight: 600;
      appearance: textfield;
      -moz-appearance: textfield;
    }

    .product-quantity-control input::-webkit-inner-spin-button,
    .product-quantity-control input::-webkit-outer-spin-button {
      margin: 0;
      -webkit-appearance: none;
    }

    .product-action-wrap {
      display: flex;
      align-items: stretch;
      gap: 12px;
      width: 50%;
      margin-top: 8px;
    }

    .product-cart-btn,
    .product-view-cart-btn {
      display: flex;
      flex: 0 0 200px;
      /* Reduce or increase button width here */
      align-items: center;
      justify-content: center;
      min-width: 0;
      min-height: 48px;
      padding: 10px 18px;
      border: 1px solid var(--primary-color);
      border-radius: 6px;
      background: var(--primary-color);
      color: #fff;
      font-size: 14px;
      font-weight: 600;
      text-align: center;
      text-decoration: none;
      cursor: pointer;
      transition: all .2s ease;
    }

    .product-cart-btn:hover {
      opacity: .88;
    }

    .product-cart-btn:disabled {
      cursor: wait;
      opacity: .65;
    }

    .product-view-cart-btn {
      gap: 8px;
      background: #fff;
      color: var(--primary-color);
    }

    .product-view-cart-btn:hover {
      background: var(--primary-color);
      color: #fff;
    }

    .product-wishlist-btn {
      display: flex;
      flex: 0 0 48px;
      align-items: center;
      justify-content: center;
      width: 48px;
      height: 48px;
      border: 1px solid #e5e5e5;
      border-radius: 6px;
      background: #fff;
      color: #9ca3af;
      font-size: 18px;
      cursor: pointer;
      transition: all .2s ease;
    }

    .product-wishlist-btn:hover {
      border-color: var(--primary-color);
      color: var(--primary-color);
    }

    @media (max-width: 480px) {
      .product-quantity-wrap {
        justify-content: flex-start;
        gap: 12px;
        margin: 18px 0 16px;
      }

      .product-quantity-label {
        min-width: auto;
        font-size: 13px;
      }

      .product-quantity-control {
        height: 40px;
      }

      .product-quantity-control button {
        width: 38px;
        min-width: 38px;
      }

      .product-quantity-control input {
        width: 48px;
      }

      .product-action-wrap {
        gap: 9px;
      }

      .product-cart-btn,
      .product-view-cart-btn {
        min-height: 46px;
        padding: 9px 10px;
        font-size: 13px;
      }

      .product-wishlist-btn {
        flex-basis: 46px;
        width: 46px;
        height: 46px;
      }
    }

    @media (max-width: 340px) {
      .product-quantity-wrap {
        gap: 8px;
      }

      .product-quantity-label {
        font-size: 12px;
      }

      .product-quantity-control button {
        width: 34px;
        min-width: 34px;
      }

      .product-quantity-control input {
        width: 42px;
      }
    }

    /* ============================================
           TABS
           ============================================ */
    .tab-active {
      background: #252a35 !important;
      color: white !important;
    }

    .tab-inactive {
      background: var(--primary-color) !important;
      color: white !important;
    }
  </style>
  <style>
    :root {
      --primary-color: <?= htmlspecialchars(getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ed143b') ?>;
      --hover-color: <?= htmlspecialchars(getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ec4899') ?>;
    }
  </style>
</head>

<body class="font-sans bg-white text-gray-800 min-h-screen antialiased">

  <?php include_once __DIR__ . "/includes/nav-bar.php"; ?>

  <?php
  if (!isset($_GET['slug'])) redirect($storeUrl);
  $slug = $_GET['slug'];

  $productQuery = $db->prepare("
    SELECT * FROM seller_products 
    WHERE slug = ? AND seller_id = ? AND store_id = ? AND status = 1 
      AND (visibility = 'publish' OR (visibility = 'schedule' AND schedule_date <= NOW()))
    ");
  $productQuery->execute([$slug, $sellerId, $storeId]);
  $product = $productQuery->fetch(PDO::FETCH_ASSOC);
  if (!$product) redirect($storeUrl);

  $id = $product['id'];
  $product_id = $product['product_id'];
  $name = $product['name'];
  $variation = $product['variation'];
  $price = $product['price'];
  $mrp_price = $product['mrp_price'];
  $description = $product['description'];
  $image = $product['image'];
  $unlimited_stock = $product['unlimited_stock'];
  $total_stocks = $product['total_stocks'] ?? 0;
  $unit_type = $product['unit_type'] ?? 'pcs';
  $comboEnabled = !empty($product['combo_enabled']) && (int)$product['combo_enabled'] === 1;
  $comboSelectionCount = (int)($product['combo_selection_count'] ?? 0);
  // Fetch Specifications
  $specsJson = getData("specifications", "seller_product_specifications", "product_id='$product_id'");
  $specs = [];
  if (!empty($specsJson)) {
    $decoded = json_decode($specsJson, true);
    if (is_array($decoded)) $specs = $decoded;
  }

  $initialVariantId = isset($_GET['variation']) && $_GET['variation'] != '' && $_GET['variation'] !== 'main' ? $_GET['variation'] : null;

  $db->prepare("UPDATE seller_products SET visitors=visitors+1 WHERE id=?")->execute([$id]);
  $visitors = $product['visitors'] + 1;

  $advancedVariantsCheck = readData("*", "seller_product_advanced_variants", "product_id='$product_id'");
  if ($advancedVariantsCheck && $advancedVariantsCheck->rowCount() > 0) {
    $basicVariants = readData("*", "seller_product_advanced_variants", "product_id='$product_id'");
    $isAdvanced = true;
  } else {
    $basicVariants = readData("*", "seller_product_variants", "product_id='$product_id'");
    $isAdvanced = false;
  }

  // Group advanced variants by color
  $groupedVariants = [];
  if ($isAdvanced && $basicVariants && $basicVariants->rowCount() > 0) {
    $basicVariants->execute();
    while ($bv = $basicVariants->fetch()) {
      $colorId = $bv['color'] ?? '';
      $colorName = getData("color_name", "product_colors", "id='$colorId'");
      $colorCode = getData("color_code", "product_colors", "id='$colorId'") ?? '#ccc';
      $size = $bv['size'] ?? '';

      // ✅ CRITICAL FIX: If variant image is empty, fallback to main product image
      $variantImage = !empty($bv['image']) ? UPLOADS_URL . $bv['image'] : UPLOADS_URL . $image;

      if (!isset($groupedVariants[$colorId])) {
        $groupedVariants[$colorId] = [
          'color_name' => $colorName,
          'color_code' => $colorCode,
          'sizes' => []
        ];
      }
      $groupedVariants[$colorId]['sizes'][] = [
        'id' => $bv['id'],
        'size' => $size,
        'price' => $bv['price'],
        'mrp_price' => $bv['mrp_price'],
        'stock' => $bv['stock'] ?? 0,
        'unlimited_stock' => $bv['unlimited_stock'] ?? 0,
        'unit_type' => $bv['unit_type'] ?? $unit_type,
        'image' => $variantImage // Passed the resolved image here
      ];
    }
  }

  // Read the existing cart quantity for this product and its variants.
  $cartVariantsQuery = $db->prepare("
    SELECT other, advanced_variant, quantity
    FROM customer_cart
    WHERE customer_id=? AND product_id=? AND seller_id=? AND store_id=?
  ");
  $cartVariantsQuery->execute([$cookie_id, $id, $sellerId, $storeId]);
  $cartItems = $cartVariantsQuery->fetchAll(PDO::FETCH_ASSOC);

  $cartVariants = [];
  $cartVariantQuantities = [];
  foreach ($cartItems as $item) {
    if (!empty($item['advanced_variant'])) $key = (string)$item['advanced_variant'];
    elseif (!empty($item['other'])) $key = (string)$item['other'];
    else $key = 'main';

    $cartVariants[] = $key;
    $cartVariantQuantities[$key] = (int)$item['quantity'];
  }

  $inCartInitial = ($initialVariantId === null)
    ? in_array('main', $cartVariants, true)
    : in_array((string)$initialVariantId, $cartVariants, true);

  if ($unlimited_stock == 1) {
    $mainStockDisplay = "Unlimited stock";
  } else {
    if ($unit_type == "kg") $mainStockDisplay = ((int)$total_stocks / 1000) . " kg";
    else if ($unit_type == "litre") $mainStockDisplay = ((int)$total_stocks / 1000) . " litre";
    else if ($unit_type == "meter") $mainStockDisplay = (int)$total_stocks * 0.3048 . " meter";
    else $mainStockDisplay = (int)$total_stocks . " " . $unit_type;
  }

  $variantStocksDisplay = [];
  if ($basicVariants && $basicVariants->rowCount() > 0) {
    $basicVariants->execute();
    while ($bv = $basicVariants->fetch()) {
      if (($bv['unlimited_stock'] ?? 0) == 1) $variantStocksDisplay[$bv['id']] = "Unlimited stock";
      else {
        $vUnit = $bv['unit_type'] ?? $unit_type;
        $vStock = (int)($bv['stock'] ?? 0);
        $variantStocksDisplay[$bv['id']] = "$vStock $vUnit";
      }
    }
  }

  $additionalImages = [];
  $data = readData("*", "seller_product_additional_images", "product_id='$product_id'");
  while ($row = $data->fetch()) $additionalImages[] = UPLOADS_URL . $row['image'];
  ?>

  <section class="mx-auto max-w-[1400px] px-4 py-8 sm:px-6 sm:py-10 lg:px-12">
    <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-2 lg:gap-16">

      <!-- ================= PRODUCT IMAGE ================= -->
      <div class="w-full">
        <!-- HOVER TO ZOOM -->
        <div class="zoom-container" id="zoomContainer">
          <img id="mainProductImage" src="<?= UPLOADS_URL . $image ?>" alt="<?= htmlspecialchars($name) ?>">

          <div class="zoom-icon absolute bottom-4 right-4 flex h-10 w-10 items-center justify-center rounded-full bg-white/90 text-gray-700 shadow-sm">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
              <circle cx="10.8" cy="10.8" r="6.5"></circle>
              <path d="m16 16 4 4M10.8 8v5.6M8 10.8h5.6" stroke-linecap="round"></path>
            </svg>
          </div>
        </div>

        <!-- THUMBNAILS -->
        <div class="mt-5 flex flex-wrap gap-3">
          <button type="button" data-image="<?= UPLOADS_URL . $image ?>" class="thumbnail-btn flex h-[80px] w-[80px] items-center justify-center overflow-hidden border-2 border-[var(--primary-color)] bg-white p-2 transition">
            <img src="<?= UPLOADS_URL . $image ?>" class="h-full w-full object-contain transition duration-300 hover:scale-110">
          </button>
          <?php foreach ($additionalImages as $img): ?>
            <button type="button" data-image="<?= $img ?>" class="thumbnail-btn flex h-[80px] w-[80px] items-center justify-center overflow-hidden border-2 border-gray-200 bg-white p-2 transition hover:border-[var(--primary-color)]">
              <img src="<?= $img ?>" class="h-full w-full object-contain transition duration-300 hover:scale-110">
            </button>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- ================= PRODUCT DETAILS ================= -->
      <div class="w-full pt-0 lg:pt-5">

        <p class="mb-3 text-[11px] font-bold uppercase tracking-[2px] text-[var(--primary-color)] sm:mb-4">
          <?= htmlspecialchars($storeName) ?> · Professional Gear
        </p>

        <h1 class="mb-4 text-xl font-normal leading-tight text-gray-800 sm:text-2xl lg:text-[34px]">
          <?= htmlspecialchars($name) ?>
        </h1>

        <!-- PRICES -->
        <div class="mb-6 space-y-1">
          <?php if ($mrp_price > $price): ?>
            <div class="flex items-center gap-2 text-sm sm:text-base">
              <span class="font-medium text-gray-700">MRP:</span>
              <span class="text-gray-400 line-through productMrp"><?= currencyToSymbol($storeCurrency) . number_format($mrp_price, 2) ?></span>
            </div>
          <?php endif; ?>
          <div class="flex items-center gap-2 text-lg sm:text-xl">
            <span class="font-medium text-gray-700">Price:</span>
            <span class="font-bold text-[var(--primary-color)] productPrice"><?= currencyToSymbol($storeCurrency) . number_format($price, 2) ?></span>
          </div>
        </div>

        <div class="mb-4 text-sm">
          <span class="font-medium text-gray-700">Variant:</span>
          <span id="variantName" class="text-gray-600"><?= !empty($variation) ? htmlspecialchars($variation) : 'Standard' ?></span>
        </div>

        <!-- COLOR SELECTION (Advanced) -->
        <?php if ($isAdvanced && !empty($groupedVariants) && !$comboEnabled): ?>
          <div class="mb-4 flex flex-wrap items-center gap-x-6 gap-y-3 sm:gap-x-8">
            <span class="w-[70px] shrink-0 text-sm font-medium text-gray-700 sm:w-[80px]">Color:</span>
            <div class="flex flex-wrap gap-3" id="colorContainer">
              <?php
              $firstColor = true;
              foreach ($groupedVariants as $colorId => $colorData):
                $colorName = $colorData['color_name'];
                $colorCode = $colorData['color_code'];
              ?>
                <button class="color-btn flex h-8 w-8 items-center justify-center rounded border-2 <?= $firstColor ? 'border-[var(--primary-color)]' : 'border-gray-200' ?> transition hover:border-[var(--primary-color)]"
                  style="background-color: <?= $colorCode ?>;"
                  data-color-id="<?= $colorId ?>"
                  data-color-name="<?= htmlspecialchars($colorName) ?>"
                  title="<?= htmlspecialchars($colorName) ?>">
                </button>
              <?php $firstColor = false;
              endforeach; ?>
            </div>
          </div>

          <!-- SIZE SELECTION (Dynamically updated via JS) -->
          <div class="mb-4 flex flex-wrap items-center gap-x-6 gap-y-3 sm:gap-x-8">
            <span class="w-[70px] shrink-0 text-sm font-medium text-gray-700 sm:w-[80px]">Size:</span>
            <div class="flex flex-wrap gap-2" id="sizeContainer">
              <!-- Sizes injected by JS -->
            </div>
          </div>
        <?php endif; ?>

        <!-- BASIC VARIANTS -->
        <?php if ($basicVariants && $basicVariants->rowCount() > 0 && !$isAdvanced && !$comboEnabled): ?>
          <div class="mb-4 flex flex-wrap items-center gap-x-6 gap-y-3 sm:gap-x-8">
            <span class="w-[70px] shrink-0 text-sm font-medium text-gray-700 sm:w-[80px]">Option:</span>
            <div class="flex flex-wrap gap-2">
              <!-- Main Product Button -->
              <button class="variant-btn product-option rounded-md border px-3 py-2 text-sm transition hover:border-[var(--primary-color)] sm:px-4 <?= $inCartInitial && $initialVariantId === null ? 'bg-green-50 border-green-500 text-green-700' : 'bg-white text-gray-700 border-gray-200' ?>"
                data-variant-id="main" data-variant-image="<?= UPLOADS_URL . $image ?>" data-variant-name="<?= htmlspecialchars(!empty($variation) ? $variation : $name) ?>" data-price="<?= $price ?>" data-mrp="<?= $mrp_price ?>" data-stock="<?= $mainStockDisplay ?>" data-in-cart="<?= in_array('main', $cartVariants) ? '1' : '0' ?>">
                <?= htmlspecialchars(!empty($variation) ? $variation : $name) ?>
              </button>

              <?php $basicVariants->execute(); ?>
              <?php while ($bv = $basicVariants->fetch()):
                $variantId = $bv['id'];
                $isInCart = in_array((string)$variantId, $cartVariants);
                $variantLabel = $bv['variation'] ?? $bv['variant'] ?? ($bv['name'] ?? "Variant " . $variantId);
                // ✅ FIX: Ensure basic variant images fall back to main image
                $basicImage = !empty($bv['image']) ? UPLOADS_URL . $bv['image'] : UPLOADS_URL . $image;
              ?>
                <!-- Basic Variant Buttons -->
                <button class="variant-btn product-option rounded-md border px-3 py-2 text-sm transition hover:border-[var(--primary-color)] sm:px-4 <?= $initialVariantId == $variantId ? 'bg-green-50 border-green-500 text-green-700' : 'bg-white text-gray-700 border-gray-200' ?>"
                  data-variant-id="<?= $variantId ?>" data-variant-image="<?= $basicImage ?>" data-variant-name="<?= htmlspecialchars($variantLabel) ?>" data-price="<?= $bv['price'] ?>" data-mrp="<?= $bv['mrp_price'] ?>" data-stock="<?= $variantStocksDisplay[$variantId] ?? 'Out of stock' ?>" data-in-cart="<?= $isInCart ? '1' : '0' ?>">
                  <?= htmlspecialchars($variantLabel) ?>
                </button>
              <?php endwhile; ?>
            </div>
          </div>
        <?php endif; ?>

        <!-- PRODUCT INFORMATION -->
        <div class="mb-6 space-y-2 text-sm sm:space-y-3 mt-6">

          <!-- Dynamic Specifications -->
          <?php if (!empty($specs)): ?>
            <?php foreach ($specs as $spec): ?>
              <div class="flex items-center gap-2">
                <span class="font-medium text-gray-700 w-[100px]"><?= htmlspecialchars($spec['name']) ?>:</span>
                <span class="font-medium text-gray-600"><?= htmlspecialchars($spec['value']) ?></span>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>

          <!-- Default Availability (Only shows if not overridden by a custom spec) -->
          <div class="flex items-center gap-2">
            <span class="font-medium text-gray-700 w-[100px] mt-2 mb-2">Availability:</span>
            <span id="stockDisplay" class="font-medium <?= ($total_stocks <= 5 && $unlimited_stock != 1) ? 'text-red-500' : 'text-green-600' ?>"><?= $mainStockDisplay ?></span>
          </div>

          <!-- Views -->
          <div class="flex items-center gap-2">
            <span class="font-medium text-gray-700 w-[100px]">Views:</span>
            <span class="text-gray-600"><?= (int)$visitors ?> times</span>
          </div>
        </div>

        <!-- QUANTITY SELECTOR -->
        <div class="product-quantity-wrap">
          <label for="quantity" class="product-quantity-label">Quantity:</label>
          <div class="product-quantity-control">
            <button type="button" id="quantityMinus" aria-label="Decrease quantity">−</button>
            <input type="number" id="quantity" value="<?= $inCartInitial ? (int)($cartVariantQuantities[$initialVariantId === null ? 'main' : (string)$initialVariantId] ?? 1) : 1 ?>" min="1" max="99" inputmode="numeric">
            <button type="button" id="quantityPlus" aria-label="Increase quantity">+</button>
          </div>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="product-action-wrap">
          <button id="addToCartBtn" type="button" class="product-cart-btn" data-id="<?= $id ?>">
            <?= $inCartInitial ? 'Update Cart' : 'Add Item' ?>
          </button>

          <a id="viewCartBtn" href="<?= $storeUrl ?>cart" class="product-view-cart-btn hidden">
            Already in Cart
          </a>

          <?php if (isLoggedIn()):
            $inWishlist = getData("id", "customer_wishlists", "customer_id='$customerId' AND product_id='$id' AND other=''") ? true : false;
          ?>
            <button id="wishlistBtn" data-id="<?= $id ?>" class="product-wishlist-btn handleWishlist" title="<?= $inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' ?>">
              <i class="fa-solid fa-heart <?= $inWishlist ? 'text-rose-500' : '' ?>"></i>
            </button>
          <?php else: ?>
            <a href="<?= $storeUrl ?>wishlists" class="product-wishlist-btn" title="Login to add wishlist">
              <i class="fa-solid fa-heart"></i>
            </a>
          <?php endif; ?>
        </div>

        <?php
        $randomProducts = getRandomProducts(3);
        if ($randomProducts && count($randomProducts) > 0) {
          echo '<div class="mt-8 pt-6 border-t border-gray-200"><h3 class="text-sm font-bold uppercase tracking-wider text-gray-800 mb-4">Customers Also Bought</h3><div class="grid grid-cols-3 gap-4">';
          foreach ($randomProducts as $rp) {
            $rpIsOut = (empty($rp['unlimited_stock']) && (int)$rp['total_stocks'] <= 0);
            echo '<a href="' . $storeUrl . 'product/' . $rp['slug'] . '" class="block group text-center">';
            echo '<div class="aspect-square bg-gray-50 flex items-center justify-center overflow-hidden mb-2 border border-gray-100 rounded">';
            echo '<img src="' . UPLOADS_URL . $rp['image'] . '" class="w-full h-full object-contain transition duration-500 group-hover:scale-105 ' . ($rpIsOut ? 'opacity-50 grayscale' : '') . '">';
            echo '</div>';
            echo '<h4 class="text-xs font-semibold text-gray-800 line-clamp-1">' . htmlspecialchars($rp['name']) . '</h4>';
            echo '<p class="text-xs font-bold text-[var(--primary-color)] mt-1">' . currencyToSymbol($storeCurrency) . $rp['price'] . '</p>';
            echo '</a>';
          }
          echo '</div></div>';
        }
        ?>
      </div>
    </div>
  </section>

  <!-- ================= DETAILS & REVIEWS ================= -->
  <section class="w-full bg-white px-4 py-8 sm:px-6 sm:py-10 lg:px-12 mt-4">
    <div class="mx-auto max-w-[1350px]">
      <div class="flex items-center gap-1 border-b border-gray-200">
        <button type="button" onclick="showProductTab('description')" id="descriptionTab" class="product-tab tab-active px-4 py-3 text-xs font-medium text-white transition-colors duration-300 sm:px-8">About This Product</button>
        <button type="button" onclick="showProductTab('reviews')" id="reviewsTab" class="product-tab tab-inactive px-4 py-3 text-xs font-medium text-white transition-colors duration-300 sm:px-8">The Reviews</button>
      </div>
      <div id="descriptionContent" class="rounded-b border border-t-0 border-gray-200 px-4 py-5 text-sm leading-7 text-gray-700 sm:px-6 sm:py-7"><?= $description ?></div>
      <div id="reviewsContent" class="hidden rounded-b border border-t-0 border-gray-200 px-4 py-5 sm:px-6 sm:py-7">
        <h3 class="mb-3 text-lg font-semibold text-[#252a35]">Customer Reviews</h3>
        <p class="text-sm text-gray-600">No reviews yet. Be the first to review this product.</p>
      </div>
    </div>
  </section>

  <?php include_once __DIR__ . "/includes/footer.php"; ?>

  <script>
    var productId = <?= $id ?>;
    var storeCurrencySymbol = <?= json_encode(currencyToSymbol($storeCurrency)) ?>;
    var groupedVariants = <?= json_encode($groupedVariants) ?>;

    // ✅ Array holding live cart variant IDs
    var cartVariants = <?= json_encode($cartVariants) ?>;

    // Product-page quantity/cart controller. Shared cart files remain unchanged.
    const qtyInput = document.getElementById('quantity');
    const addBtn = document.getElementById('addToCartBtn');
    const viewBtn = document.getElementById('viewCartBtn');
    const cartVariantQuantities = <?= json_encode($cartVariantQuantities) ?>;

    let selectedCartKey = 'main';
    let selectedBasicVariant = '';
    let selectedAdvancedVariant = '';
    let selectedCartQuantity = 0;
    let selectedIsInCart = false;
    let cartRequestRunning = false;

    function selectProductCartVariant(key, basicVariant = '', advancedVariant = '') {
      selectedCartKey = String(key || 'main');
      selectedBasicVariant = basicVariant ? String(basicVariant) : '';
      selectedAdvancedVariant = advancedVariant ? String(advancedVariant) : '';
      selectedCartQuantity = Number(cartVariantQuantities[selectedCartKey] || 0);
      selectedIsInCart = selectedCartQuantity > 0;
      qtyInput.value = selectedIsInCart ? selectedCartQuantity : 1;
      addBtn.textContent = selectedIsInCart ? 'Update Cart' : 'Add Item';
      addBtn.disabled = false;
      addBtn.classList.remove('hidden');
      viewBtn.classList.add('hidden');
    }

    document.getElementById('quantityMinus').addEventListener('click', function() {
      qtyInput.value = Math.max(1, (parseInt(qtyInput.value, 10) || 1) - 1);
      addBtn.textContent = selectedIsInCart ? 'Update Cart' : 'Add Item';
      addBtn.classList.remove('hidden');
      viewBtn.classList.add('hidden');
    });

    document.getElementById('quantityPlus').addEventListener('click', function() {
      qtyInput.value = Math.min(99, (parseInt(qtyInput.value, 10) || 1) + 1);
      addBtn.textContent = selectedIsInCart ? 'Update Cart' : 'Add Item';
      addBtn.classList.remove('hidden');
      viewBtn.classList.add('hidden');
    });

    qtyInput.addEventListener('change', function() {
      let quantity = parseInt(this.value, 10);
      if (!Number.isInteger(quantity) || quantity < 1) quantity = 1;
      this.value = Math.min(99, quantity);
      addBtn.textContent = selectedIsInCart ? 'Update Cart' : 'Add Item';
      addBtn.classList.remove('hidden');
      viewBtn.classList.add('hidden');
    });

    function productPageAjax(url, data) {
      return $.ajax({
        url,
        type: 'POST',
        data,
        dataType: 'json'
      });
    }

    async function saveProductPageQuantity(targetQuantity) {
      let currentQuantity = selectedCartQuantity;

      // Existing add-to-cart endpoint creates a new cart row with quantity 1.
      if (currentQuantity === 0) {
        const added = await productPageAjax('shop/ajax/add-to-cart.php', {
          product_id: productId,
          quantity: 1,
          variant: selectedBasicVariant,
          advanced_variant: selectedAdvancedVariant
        });
        if (!added || !added.success) {
          throw new Error(added?.message || 'Could not add this product.');
        }
        currentQuantity = 1;
      }

      // Use the existing quantity endpoint to reach the requested exact quantity.
      while (currentQuantity < targetQuantity) {
        const result = await productPageAjax('shop/ajax/manage-qty.php', {
          id: productId,
          type: 'increase',
          variant: selectedBasicVariant,
          advancedVariant: selectedAdvancedVariant
        });
        if (!result || !result.success) {
          throw new Error(result?.message || 'Could not increase quantity.');
        }
        currentQuantity++;
      }

      while (currentQuantity > targetQuantity) {
        const result = await productPageAjax('shop/ajax/manage-qty.php', {
          id: productId,
          type: 'decrease',
          variant: selectedBasicVariant,
          advancedVariant: selectedAdvancedVariant
        });
        if (!result || !result.success) {
          throw new Error(result?.message || 'Could not decrease quantity.');
        }
        currentQuantity--;
      }

      return currentQuantity;
    }

    addBtn.addEventListener('click', async function() {
      if (cartRequestRunning || addBtn.disabled) return;

      let targetQuantity = parseInt(qtyInput.value, 10);
      if (!Number.isInteger(targetQuantity) || targetQuantity < 1 || targetQuantity > 99) {
        if (window.showCustomToast) window.showCustomToast('Enter quantity from 1 to 99.', 'error');
        return;
      }

      cartRequestRunning = true;
      addBtn.disabled = true;
      addBtn.textContent = selectedIsInCart ? 'Updating...' : 'Adding...';

      try {
        const savedQuantity = await saveProductPageQuantity(targetQuantity);
        selectedCartQuantity = savedQuantity;
        selectedIsInCart = true;
        cartVariantQuantities[selectedCartKey] = savedQuantity;

        addBtn.classList.add('hidden');
        viewBtn.classList.remove('hidden');

        if (window.showCustomToast) {
          window.showCustomToast('Cart quantity updated successfully!', 'success');
        }
        if (typeof getProductCountAndPrice === 'function') getProductCountAndPrice();
        if (typeof getCartData === 'function') getCartData();
      } catch (error) {
        console.error('Product page cart update:', error);
        addBtn.textContent = selectedIsInCart ? 'Update Cart' : 'Add Item';
        addBtn.disabled = false;
        if (window.showCustomToast) {
          window.showCustomToast(error.message || 'Cart update failed.', 'error');
        }
      } finally {
        cartRequestRunning = false;
      }
    });

    // ============================================
    // HOVER TO ZOOM
    // ============================================
    const zoomContainer = document.getElementById('zoomContainer');
    const mainProductImage = document.getElementById('mainProductImage');

    if (zoomContainer && mainProductImage) {
      zoomContainer.addEventListener("mousemove", function(event) {
        if (window.innerWidth < 1024) return;
        const rect = zoomContainer.getBoundingClientRect();
        const x = ((event.clientX - rect.left) / rect.width) * 100;
        const y = ((event.clientY - rect.top) / rect.height) * 100;
        mainProductImage.style.transformOrigin = x + "% " + y + "%";
      });
      zoomContainer.addEventListener("mouseleave", function() {
        mainProductImage.style.transformOrigin = "center center";
      });
    }

    // Thumbnail logic
    document.querySelectorAll('.thumbnail-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        mainProductImage.src = this.dataset.image;
        document.querySelectorAll('.thumbnail-btn').forEach(b => {
          b.classList.remove('border-[var(--primary-color)]');
          b.classList.add('border-gray-200');
        });
        this.classList.remove('border-gray-200');
        this.classList.add('border-[var(--primary-color)]');
      });
    });

    // Tabs
    function showProductTab(tab) {
      const descC = document.getElementById('descriptionContent');
      const revC = document.getElementById('reviewsContent');
      const descT = document.getElementById('descriptionTab');
      const revT = document.getElementById('reviewsTab');

      if (tab === 'description') {
        descC.classList.remove('hidden');
        revC.classList.add('hidden');
        descT.classList.add('tab-active');
        descT.classList.remove('tab-inactive');
        revT.classList.add('tab-inactive');
        revT.classList.remove('tab-active');
      } else {
        descC.classList.add('hidden');
        revC.classList.remove('hidden');
        revT.classList.add('tab-active');
        revT.classList.remove('tab-inactive');
        descT.classList.add('tab-inactive');
        descT.classList.remove('tab-active');
      }
    }

    // ============================================
    // PRODUCT PAGE VARIANT SELECTION
    // ============================================
    <?php if ($isAdvanced && !empty($groupedVariants) && !$comboEnabled): ?>

      const colorButtons = document.querySelectorAll('.color-btn');
      const sizeContainer = document.getElementById('sizeContainer');

      function renderSizes(colorId) {
        sizeContainer.innerHTML = '';
        if (!groupedVariants[colorId] || !groupedVariants[colorId].sizes) return;

        let firstAvailable = null;

        groupedVariants[colorId].sizes.forEach(sizeData => {
          const outOfStock = (!Number(sizeData.unlimited_stock) && Number(sizeData.stock) <= 0);
          const variantId = String(sizeData.id);
          const button = document.createElement('button');

          button.type = 'button';
          button.className = 'size-btn product-option rounded-md border px-3 py-2 text-sm transition hover:border-[var(--primary-color)] ' +
            (outOfStock ? 'out-of-stock' : 'border-gray-200 bg-white text-gray-700');
          button.textContent = sizeData.size;
          button.dataset.variantId = variantId;
          button.dataset.price = sizeData.price;
          button.dataset.stock = sizeData.unlimited_stock ? 'Unlimited stock' : `${sizeData.stock} ${sizeData.unit_type || 'pcs'}`;
          button.dataset.image = sizeData.image;

          if (outOfStock) {
            button.disabled = true;
          } else {
            if (!firstAvailable) firstAvailable = button;

            button.addEventListener('click', function() {
              sizeContainer.querySelectorAll('.size-btn').forEach(item => {
                item.classList.remove('selected-option', 'border-[var(--primary-color)]', 'text-[var(--primary-color)]');
                item.classList.add('border-gray-200', 'text-gray-700', 'bg-white');
              });

              this.classList.add('selected-option', 'border-[var(--primary-color)]', 'text-[var(--primary-color)]');
              this.classList.remove('border-gray-200', 'text-gray-700', 'bg-white');

              document.querySelector('.productPrice').textContent =
                storeCurrencySymbol + Number(this.dataset.price).toLocaleString();
              mainProductImage.src = this.dataset.image;
              document.getElementById('stockDisplay').textContent = this.dataset.stock;

              selectProductCartVariant(variantId, '', variantId);
            });
          }

          sizeContainer.appendChild(button);
        });

        if (firstAvailable) {
          firstAvailable.click();
        } else {
          addBtn.textContent = 'Sold Out';
          addBtn.disabled = true;
          addBtn.classList.remove('hidden');
          viewBtn.classList.add('hidden');
        }
      }

      colorButtons.forEach(button => {
        button.addEventListener('click', function() {
          colorButtons.forEach(item => {
            item.classList.remove('border-[var(--primary-color)]');
            item.classList.add('border-gray-200');
          });
          this.classList.remove('border-gray-200');
          this.classList.add('border-[var(--primary-color)]');
          renderSizes(this.dataset.colorId);
        });
      });

      if (colorButtons.length) colorButtons[0].click();

    <?php elseif ($basicVariants && $basicVariants->rowCount() > 0 && !$isAdvanced && !$comboEnabled): ?>

      const variantButtons = document.querySelectorAll('.variant-btn');

      variantButtons.forEach(button => {
        button.addEventListener('click', function() {
          variantButtons.forEach(item => {
            item.classList.remove('selected-option', 'border-[var(--primary-color)]', 'text-[var(--primary-color)]');
            item.classList.add('border-gray-200', 'text-gray-700', 'bg-white');
          });

          this.classList.add('selected-option', 'border-[var(--primary-color)]', 'text-[var(--primary-color)]');
          this.classList.remove('border-gray-200', 'text-gray-700', 'bg-white');

          const variantId = this.dataset.variantId;
          const stockText = String(this.dataset.stock || '').toLowerCase();
          const stockNumber = parseFloat(stockText);
          const outOfStock = !stockText.includes('unlimited') &&
            !Number.isNaN(stockNumber) && stockNumber <= 0;

          document.querySelector('.productPrice').textContent =
            storeCurrencySymbol + Number(this.dataset.price).toLocaleString();
          mainProductImage.src = this.dataset.variantImage;
          document.getElementById('stockDisplay').textContent = this.dataset.stock;

          if (outOfStock) {
            addBtn.textContent = 'Sold Out';
            addBtn.disabled = true;
            addBtn.classList.remove('hidden');
            viewBtn.classList.add('hidden');
            return;
          }

          addBtn.disabled = false;
          selectProductCartVariant(
            variantId === 'main' ? 'main' : variantId,
            variantId === 'main' ? '' : variantId,
            ''
          );
        });
      });

      if (variantButtons.length) {
        let initialButton = null;

        <?php if ($initialVariantId !== null): ?>
          initialButton = Array.from(variantButtons).find(
            button => button.dataset.variantId === <?= json_encode((string)$initialVariantId) ?>
          ) || null;
        <?php endif; ?>

        if (!initialButton) {
          initialButton = Array.from(variantButtons).find(
            button => button.dataset.inCart === '1'
          ) || variantButtons[0];
        }
        initialButton.click();
      }

    <?php else: ?>

      selectProductCartVariant('main', '', '');

    <?php endif; ?>
  </script>
</body>

</html>