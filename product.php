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

    @media (min-width: 640px) { .zoom-container { height: 450px; } }
    @media (min-width: 1024px) { .zoom-container { height: 650px; } }

    #mainProductImage {
      width: 100%;
      height: 100%;
      object-fit: contain;
      transition: transform 0.3s ease-out;
    }

    .zoom-container:hover #mainProductImage { transform: scale(1.8); }

    .zoom-icon {
      transition: opacity 0.3s ease;
      pointer-events: none;
    }

    .zoom-container:hover .zoom-icon { opacity: 0; }

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
           TABS
           ============================================ */
    .tab-active { background: #252a35 !important; color: white !important; }
    .tab-inactive { background: var(--primary-color) !important; color: white !important; }
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
        'image' => $bv['image'] ?? $image
      ];
    }
  }

  $cartVariantsQuery = $db->prepare("SELECT other, advanced_variant FROM customer_cart WHERE customer_id=? AND product_id=?");
  $cartVariantsQuery->execute([$cookie_id, $id]);
  $cartItems = $cartVariantsQuery->fetchAll(PDO::FETCH_ASSOC);

  $cartVariants = [];
  foreach ($cartItems as $item) {
    if (!empty($item['advanced_variant'])) $cartVariants[] = (string)$item['advanced_variant'];
    else if (!empty($item['other'])) $cartVariants[] = (string)$item['other'];
    else $cartVariants[] = 'main';
  }

  $inCartInitial = ($initialVariantId === null) ? in_array('main', $cartVariants) : in_array((string)$initialVariantId, $cartVariants);

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
              <?php $firstColor = false; endforeach; ?>
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
              <!-- ✅ Added data-in-cart properly for Main Product -->
              <button class="variant-btn product-option rounded-md border px-3 py-2 text-sm transition hover:border-[var(--primary-color)] sm:px-4 <?= $inCartInitial && $initialVariantId === null ? 'bg-green-50 border-green-500 text-green-700' : 'bg-white text-gray-700 border-gray-200' ?>"
                data-variant-id="main" data-variant-image="<?= UPLOADS_URL . $image ?>" data-variant-name="<?= htmlspecialchars(!empty($variation) ? $variation : $name) ?>" data-price="<?= $price ?>" data-mrp="<?= $mrp_price ?>" data-stock="<?= $mainStockDisplay ?>" data-in-cart="<?= in_array('main', $cartVariants) ? '1' : '0' ?>">
                <?= htmlspecialchars(!empty($variation) ? $variation : $name) ?>
              </button>
              
              <?php $basicVariants->execute(); ?>
              <?php while ($bv = $basicVariants->fetch()):
                $variantId = $bv['id'];
                $isInCart = in_array((string)$variantId, $cartVariants);
                $variantLabel = $bv['variation'] ?? $bv['variant'] ?? ($bv['name'] ?? "Variant " . $variantId);
              ?>
                <!-- ✅ Added data-in-cart properly for basic variants -->
                <button class="variant-btn product-option rounded-md border px-3 py-2 text-sm transition hover:border-[var(--primary-color)] sm:px-4 <?= $initialVariantId == $variantId ? 'bg-green-50 border-green-500 text-green-700' : 'bg-white text-gray-700 border-gray-200' ?>"
                  data-variant-id="<?= $variantId ?>" data-variant-image="<?= UPLOADS_URL . ($bv['image'] ?? $image) ?>" data-variant-name="<?= htmlspecialchars($variantLabel) ?>" data-price="<?= $bv['price'] ?>" data-mrp="<?= $bv['mrp_price'] ?>" data-stock="<?= $variantStocksDisplay[$variantId] ?? 'Out of stock' ?>" data-in-cart="<?= $isInCart ? '1' : '0' ?>">
                  <?= htmlspecialchars($variantLabel) ?>
                </button>
              <?php endwhile; ?>
            </div>
          </div>
        <?php endif; ?>

        <!-- PRODUCT INFORMATION -->
        <div class="mb-6 space-y-2 text-sm sm:space-y-3 mt-6">
          <div class="flex items-center gap-2">
            <span class="font-medium text-gray-700 w-[100px]">Availability:</span>
            <span id="stockDisplay" class="font-medium <?= ($total_stocks <= 5 && $unlimited_stock != 1) ? 'text-red-500' : 'text-green-600' ?>"><?= $mainStockDisplay ?></span>
          </div>
          <div class="flex items-center gap-2">
            <span class="font-medium text-gray-700 w-[100px]">Views:</span>
            <span class="text-gray-600"><?= (int)$visitors ?> times</span>
          </div>
        </div>

        <!-- QUANTITY SELECTOR -->
        <div class="mb-7 flex flex-wrap items-center gap-x-6 gap-y-3 sm:gap-x-8 mt-4">
          <label for="quantity" class="w-[70px] shrink-0 text-sm font-medium text-gray-700 sm:w-[80px]">Quantity:</label>
          <div class="inline-flex h-[42px] overflow-hidden rounded-md border border-gray-200">
            <button type="button" id="quantityMinus" class="flex w-10 items-center justify-center bg-[var(--primary-color)] text-lg font-medium text-white transition hover:opacity-90">−</button>
            <input type="number" id="quantity" value="1" min="1" max="99" class="h-full w-14 appearance-none border-0 bg-white text-center text-sm text-gray-800 outline-none focus:ring-0">
            <button type="button" id="quantityPlus" class="flex w-10 items-center justify-center bg-[var(--primary-color)] text-lg font-medium text-white transition hover:opacity-90">+</button>
          </div>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="flex flex-col gap-3 sm:flex-row mt-2">
          <!-- ✅ Check $inCartInitial properly -->
          <button id="addToCartBtn" class="flex h-12 w-full items-center justify-center rounded-md bg-[var(--primary-color)] px-8 text-sm font-semibold text-white transition duration-300 hover:opacity-90 sm:w-[200px] <?= $inCartInitial ? 'hidden' : '' ?>" data-id="<?= $id ?>">
            Add to Cart
          </button>

          <a id="viewCartBtn" href="<?= $storeUrl ?>cart" class="flex h-12 w-full items-center justify-center rounded-md border border-[var(--primary-color)] bg-white px-8 text-sm font-semibold text-[var(--primary-color)] transition duration-300 hover:bg-[var(--primary-color)] hover:text-white sm:w-[200px] <?= $inCartInitial ? '' : 'hidden' ?>">
            View Cart
          </a>

          <?php if (isLoggedIn()):
            $inWishlist = getData("id", "customer_wishlists", "customer_id='$customerId' AND product_id='$id' AND other=''") ? true : false;
          ?>
            <button id="wishlistBtn" data-id="<?= $id ?>" class="w-12 h-12 flex items-center justify-center rounded-md border border-gray-200 shadow-sm transition hover:bg-gray-50 handleWishlist" title="<?= $inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' ?>">
              <i class="fa-solid fa-heart text-lg <?= $inWishlist ? 'text-rose-500' : 'text-gray-400' ?>"></i>
            </button>
          <?php else: ?>
            <a href="<?= $storeUrl ?>wishlists" class="w-12 h-12 flex items-center justify-center rounded-md border border-gray-200 shadow-sm transition hover:bg-gray-50" title="Login to add wishlist">
              <i class="fa-solid fa-heart text-lg text-gray-400"></i>
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

    // Quantity Selector
    const qtyInput = document.getElementById('quantity');
    document.getElementById('quantityMinus').addEventListener('click', function() {
      if (qtyInput.value > 1) qtyInput.value = parseInt(qtyInput.value) - 1;
    });
    document.getElementById('quantityPlus').addEventListener('click', function() {
      qtyInput.value = parseInt(qtyInput.value) + 1;
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
        descC.classList.remove('hidden'); revC.classList.add('hidden');
        descT.classList.add('tab-active'); descT.classList.remove('tab-inactive');
        revT.classList.add('tab-inactive'); revT.classList.remove('tab-active');
      } else {
        descC.classList.add('hidden'); revC.classList.remove('hidden');
        revT.classList.add('tab-active'); revT.classList.remove('tab-inactive');
        descT.classList.add('tab-inactive'); descT.classList.remove('tab-active');
      }
    }

    // ============================================
    // VARIANT LOGIC
    // ============================================
    <?php if ($isAdvanced): ?>
      const colorButtons = document.querySelectorAll('.color-btn');
      const sizeContainer = document.getElementById('sizeContainer');
      const addBtn = document.getElementById('addToCartBtn');
      const viewBtn = document.getElementById('viewCartBtn');
      let selectedVariantId = null;

      function renderSizes(colorId) {
        sizeContainer.innerHTML = '';
        if (!groupedVariants[colorId] || !groupedVariants[colorId].sizes) return;

        let hasAvailableSize = false; // ✅ Track if any size is available

        groupedVariants[colorId].sizes.forEach(sizeData => {
          const isOutOfStock = (!sizeData.unlimited_stock && sizeData.stock <= 0);
          
          if (!isOutOfStock) hasAvailableSize = true;

          const isInCart = cartVariants.includes(String(sizeData.id));

          const btn = document.createElement('button');
          btn.className = `size-btn product-option rounded-md border px-3 py-2 text-sm transition hover:border-[var(--primary-color)] sm:px-4 
                    ${isOutOfStock ? 'out-of-stock' : ''} 
                    ${isInCart ? 'bg-green-50 border-green-500 text-green-700' : 'bg-white text-gray-700 border-gray-200'}`;

          btn.textContent = sizeData.size;
          btn.dataset.variantId = sizeData.id;
          btn.dataset.colorId = colorId;
          btn.dataset.size = sizeData.size;
          btn.dataset.price = sizeData.price;
          btn.dataset.mrp = sizeData.mrp_price;
          btn.dataset.stock = sizeData.unlimited_stock ? 'Unlimited' : `${sizeData.stock} ${sizeData.unit_type || 'pcs'}`;
          btn.dataset.image = sizeData.image;

          if (isOutOfStock) {
            btn.disabled = true;
          } else {
            btn.addEventListener('click', function() {
              sizeContainer.querySelectorAll('.size-btn').forEach(b => {
                b.classList.remove('selected-option', 'border-[var(--primary-color)]', 'text-[var(--primary-color)]', 'bg-[color-mix(in_srgb,var(--primary-color)_10%,white)]');
                b.classList.add('border-gray-200', 'text-gray-700', 'bg-white');
              });
              this.classList.add('selected-option', 'border-[var(--primary-color)]', 'text-[var(--primary-color)]', 'bg-[color-mix(in_srgb,var(--primary-color)_10%,white)]');
              this.classList.remove('border-gray-200', 'text-gray-700', 'bg-white');

              selectedVariantId = this.dataset.variantId;

              document.querySelector('.productPrice').textContent = storeCurrencySymbol + parseFloat(this.dataset.price).toLocaleString();
              document.getElementById('mainProductImage').src = this.dataset.image;
              document.getElementById('stockDisplay').textContent = this.dataset.stock;

              const inCart = cartVariants.includes(String(selectedVariantId));

              if (inCart) {
                addBtn.classList.add('hidden');
                viewBtn.classList.remove('hidden');
              } else {
                addBtn.classList.remove('hidden');
                viewBtn.classList.add('hidden');
                addBtn.disabled = false;
                addBtn.innerHTML = 'Add to Cart';
                addBtn.classList.add('bg-[var(--primary-color)]');
                addBtn.classList.remove('bg-gray-300', 'cursor-not-allowed');
              }
            });
          }

          sizeContainer.appendChild(btn);
        });

        // ✅ 2. If NO sizes are available, force "Sold Out" state
        if (!hasAvailableSize) {
          addBtn.classList.remove('hidden');
          viewBtn.classList.add('hidden');
          addBtn.disabled = true;
          addBtn.innerHTML = 'Sold Out';
          addBtn.classList.remove('bg-[var(--primary-color)]');
          addBtn.classList.add('bg-gray-300', 'cursor-not-allowed');
          selectedVariantId = null;
        } else {
          // ✅ 1. Auto-click first available size
          const firstAvailable = sizeContainer.querySelector('.size-btn:not([disabled])');
          if (firstAvailable) firstAvailable.click();
        }
      }

      colorButtons.forEach(btn => {
        btn.addEventListener('click', function() {
          colorButtons.forEach(b => {
            b.classList.remove('border-[var(--primary-color)]');
            b.classList.add('border-gray-200');
          });
          this.classList.remove('border-gray-200');
          this.classList.add('border-[var(--primary-color)]');

          renderSizes(this.dataset.colorId);
        });
      });

      if (colorButtons.length > 0) {
        colorButtons[0].click();
      }

      addBtn.addEventListener('click', function() {
        if (this.disabled || !selectedVariantId) return;
        this.innerHTML = 'Adding...';
        this.disabled = true;

        $.ajax({
          url: "shop/ajax/add-to-cart.php",
          type: "POST",
          data: {
            product_id: productId,
            quantity: qtyInput.value,
            variant: '',
            advanced_variant: selectedVariantId
          },
          success: function(result) {
            const response = JSON.parse(result);
            if (response && response.success) {
              cartVariants.push(String(selectedVariantId));

              const currentSizeBtn = sizeContainer.querySelector(`.size-btn[data-variant-id="${selectedVariantId}"]`);
              if (currentSizeBtn) {
                currentSizeBtn.classList.add('bg-green-50', 'border-green-500', 'text-green-700');
                currentSizeBtn.classList.remove('bg-white', 'text-gray-700', 'border-gray-200');
              }

              addBtn.classList.add('hidden');
              viewBtn.classList.remove('hidden');
              if (window.showCustomToast) window.showCustomToast('Added to cart successfully!', 'success');
              if (typeof getProductCountAndPrice === 'function') getProductCountAndPrice();
            } else {
              addBtn.innerHTML = 'Add to Cart';
              addBtn.disabled = false;
              if (window.showCustomToast) window.showCustomToast(response?.message || 'Failed', 'error');
            }
          },
          error: function() {
            addBtn.innerHTML = 'Add to Cart';
            addBtn.disabled = false;
            if (window.showCustomToast) window.showCustomToast('Network error', 'error');
          }
        });
      });

    <?php elseif ($basicVariants && $basicVariants->rowCount() > 0 && !$isAdvanced): ?>
      const variantBtns = document.querySelectorAll('.variant-btn');
      const addBtn = document.getElementById('addToCartBtn');
      const viewBtn = document.getElementById('viewCartBtn');
      let currentVariantId = null;

      variantBtns.forEach(btn => {
        btn.addEventListener('click', function() {
          variantBtns.forEach(b => {
            b.classList.remove('selected-option', 'border-[var(--primary-color)]', 'text-[var(--primary-color)]');
            b.classList.add('border-gray-200', 'text-gray-700');
          });
          this.classList.add('selected-option', 'border-[var(--primary-color)]', 'text-[var(--primary-color)]');
          this.classList.remove('border-gray-200', 'text-gray-700');

          currentVariantId = this.dataset.variantId;
          document.querySelector('.productPrice').textContent = storeCurrencySymbol + parseFloat(this.dataset.price).toLocaleString();
          document.getElementById('mainProductImage').src = this.dataset.variantImage;
          document.getElementById('stockDisplay').textContent = this.dataset.stock;

          // ✅ 3. Live check for basic variant
          const inCart = cartVariants.includes(String(currentVariantId));
          
          const stockText = this.dataset.stock;
          let stockNumber = parseInt(stockText);
          if (stockText.toLowerCase().includes('unlimited')) stockNumber = Infinity;
          const isOutOfStock = stockNumber < 1;

          if (isOutOfStock) {
            addBtn.disabled = true;
            addBtn.innerHTML = 'Sold Out';
            addBtn.classList.remove('bg-[var(--primary-color)]');
            addBtn.classList.add('bg-gray-300', 'cursor-not-allowed');
            addBtn.classList.remove('hidden');
            viewBtn.classList.add('hidden');
          } else if (inCart) {
            addBtn.classList.add('hidden');
            viewBtn.classList.remove('hidden');
          } else {
            addBtn.classList.remove('hidden');
            viewBtn.classList.add('hidden');
            addBtn.disabled = false;
            addBtn.innerHTML = 'Add to Cart';
            addBtn.classList.add('bg-[var(--primary-color)]');
            addBtn.classList.remove('bg-gray-300', 'cursor-not-allowed');
          }
        });
      });

      addBtn.addEventListener('click', function() {
        if (this.disabled) return;
        this.innerHTML = 'Adding...';
        this.disabled = true;

        $.ajax({
          url: "shop/ajax/add-to-cart.php",
          type: "POST",
          data: {
            product_id: productId,
            quantity: qtyInput.value,
            variant: (currentVariantId === 'main' ? '' : currentVariantId),
            advanced_variant: ''
          },
          success: function(result) {
            const response = JSON.parse(result);
            if (response && response.success) {
              cartVariants.push(String(currentVariantId));

              addBtn.classList.add('hidden');
              viewBtn.classList.remove('hidden');
              if (window.showCustomToast) window.showCustomToast('Added successfully!', 'success');
              if (typeof getProductCountAndPrice === 'function') getProductCountAndPrice();
            } else {
              addBtn.innerHTML = 'Add to Cart';
              addBtn.disabled = false;
              if (window.showCustomToast) window.showCustomToast(response?.message || 'Failed', 'error');
            }
          },
          error: function() {
            addBtn.innerHTML = 'Add to Cart';
            addBtn.disabled = false;
            if (window.showCustomToast) window.showCustomToast('Network error', 'error');
          }
        });
      });

      // ✅ 4. Auto-click logic for basic variants
      if (variantBtns.length > 0) {
        let foundInCart = false;
        variantBtns.forEach(btn => {
          if (btn.dataset.inCart === '1') {
            btn.click();
            foundInCart = true;
          }
        });
        if (!foundInCart) variantBtns[0].click();
      }

    <?php else: ?>
      const addBtn = document.getElementById('addToCartBtn');
      const viewBtn = document.getElementById('viewCartBtn');

      if (addBtn) {
        addBtn.addEventListener('click', function() {
          if (this.disabled) return;
          this.innerHTML = 'Adding...';
          this.disabled = true;

          $.ajax({
            url: "shop/ajax/add-to-cart.php",
            type: "POST",
            data: {
              product_id: productId,
              quantity: qtyInput.value,
              variant: '',
              advanced_variant: ''
            },
            success: function(result) {
              const response = JSON.parse(result);
              if (response && response.success) {
                cartVariants.push('main');

                addBtn.classList.add('hidden');
                viewBtn.classList.remove('hidden');
                if (window.showCustomToast) window.showCustomToast('Added to cart successfully!', 'success');
                if (typeof getProductCountAndPrice === 'function') getProductCountAndPrice();
              } else {
                addBtn.innerHTML = 'Add to Cart';
                addBtn.disabled = false;
                if (window.showCustomToast) window.showCustomToast(response?.message || 'Failed', 'error');
              }
            },
            error: function() {
              addBtn.innerHTML = 'Add to Cart';
              addBtn.disabled = false;
              if (window.showCustomToast) window.showCustomToast('Network error', 'error');
            }
          });
        });
      }
    <?php endif; ?>
  </script>
</body>

</html>