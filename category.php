<?php include_once __DIR__ . "/includes/files_includes.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include_once __DIR__ . "/includes/head_links.php"; ?>
  <style>
    :root {
      --primary: <?= htmlspecialchars(getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ff007f') ?>;
      --hover-color: <?= htmlspecialchars(getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ec4899') ?>;
    }

    .category-scroll {
      scroll-behavior: smooth;
      scrollbar-width: none;
      -ms-overflow-style: none;
    }

    .category-scroll::-webkit-scrollbar {
      display: none;
    }

    .category-card {
      transition: all 0.3s ease;
    }

    .category-card:hover {
      transform: translateY(-2px);
    }

    .active-category-card {
      background: linear-gradient(135deg, var(--primary), #f59e0b);
      color: white;
    }

    .active-category-card .category-name {
      color: white;
      font-weight: 600;
    }

    .active-category-card .category-icon {
      background: rgba(255, 255, 255, 0.2) !important;
    }

    .sort-dropdown select:focus,
    .size-dropdown select:focus {
      outline: none;
      box-shadow: 0 0 0 2px var(--primary);
      border-color: var(--primary);
    }

    /* List/Grid Styles for Product Cards */
    .product-card-wrapper {
      height: 100%;
    }

    .product-card-inner {
      display: flex;
      flex-direction: column;
      height: 100%;
    }

    .product-card-image {
      width: 100%;
      height: 220px;
      border-bottom: 1px solid #f3f4f6;
    }

    .product-card-content {
      text-align: center;
    }

    .product-card-description {
      display: none !important;
    }

    .list-view .product-card-wrapper {
      grid-column: 1 / -1 !important;
      width: 100% !important;
      max-width: 100% !important;
    }

    .list-view .product-card-inner {
      flex-direction: row !important;
      align-items: stretch;
    }

    .list-view .product-card-image {
      width: 250px !important;
      height: auto;
      min-height: 220px;
      border-bottom: none;
      border-right: 1px solid #f3f4f6;
    }

    .list-view .product-card-content {
      text-align: left !important;
      padding: 1.5rem 2rem !important;
      justify-content: flex-start !important;
      display: flex;
      flex-direction: column;
    }

    .list-view .product-card-description {
      display: -webkit-box !important;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
      margin-bottom: 0.5rem;
    }

    .list-view .product-card-variants,
    .list-view .addToCartWrapper {
      max-width: 300px;
    }

    .list-view .product-card-price {
      justify-content: flex-start !important;
      margin-top: 0.5rem;
    }

    .list-view .text-xs.text-gray-500 {
      justify-content: flex-start !important;
      margin-bottom: 0.5rem;
    }

    @media (max-width: 640px) {
      .list-view .product-card-inner {
        flex-direction: column !important;
      }

      .list-view .product-card-image {
        width: 100% !important;
        border-right: none;
        border-bottom: 1px solid #f3f4f6;
      }
    }

    .hidden-product {
      display: none !important;
    }
  </style>
</head>

<body class="font-sans bg-gray-50 min-h-screen" style="--primary: <?= htmlspecialchars(getData('color', 'seller_settings', "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ff007f') ?>; --hover-color: <?= htmlspecialchars(getData('hover_color', 'seller_settings', "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ec4899') ?>;">


  <!-- Navigation -->
  <?php include_once __DIR__ . "/includes/nav-bar.php"; ?>

  <?php
  if (isset($_GET['slug']) && getData("id", "seller_product_categories", "slug = '{$_GET['slug']}' AND (seller_id = '$sellerId' AND store_id = '$storeId')")) {

    $slug = $_GET['slug'];
    $id = getData("id", "seller_product_categories", "slug = '{$_GET['slug']}' AND (seller_id = '$sellerId' AND store_id = '$storeId')");
    $name = getData("name", "seller_product_categories", "slug = '{$_GET['slug']}' AND (seller_id = '$sellerId' AND store_id = '$storeId')");
    $icon = getData("icon", "seller_product_categories", "slug = '{$_GET['slug']}' AND (seller_id = '$sellerId' AND store_id = '$storeId')");

    $db->prepare("UPDATE seller_product_categories SET visitors = visitors+1 WHERE id = :id")->execute(['id' => $id]);

    // Find the correct category column name
    $category_column = null;
    $possible_columns = ['category_id', 'category', 'product_category', 'cat_id', 'category_name'];

    foreach ($possible_columns as $column) {
      try {
        $check_stmt = $db->query("SHOW COLUMNS FROM seller_products LIKE '$column'");
        if ($check_stmt->rowCount() > 0) {
          $category_column = $column;
          break;
        }
      } catch (Exception $e) {
      }
    }

    // ✅ Determine if this is a Parent or Sub-Category
    $parent_id_of_current = getData("parent_category", "seller_product_categories", "id = '$id' AND seller_id = '$sellerId'");

    $child_ids = [];
    if ($parent_id_of_current > 0) {
      $real_parent_id = $parent_id_of_current;
    } else {
      $real_parent_id = $id;
      $childQuery = $db->prepare("SELECT id FROM seller_product_categories WHERE parent_category = :id AND seller_id = :seller_id");
      $childQuery->execute([':id' => $id, ':seller_id' => $sellerId]);
      $child_ids = $childQuery->fetchAll(PDO::FETCH_COLUMN);
    }

    // ✅ Fetch Products securely
    if ($parent_id_of_current > 0) {
      $products = readData("*", "seller_products", "category = '$real_parent_id' AND sub_category = '$id' AND status = 1 ORDER BY id DESC");
    } else {
      $child_ids_str = !empty($child_ids) ? implode(',', $child_ids) : '0';
      $products = readData("*", "seller_products", "(category = '$id' OR (category = '$id' AND sub_category IN ($child_ids_str))) AND status = 1 ORDER BY id DESC");
    }
  } else {
    redirect($storeUrl);
  }

  // Categories for navigation
  $categories = getCategories(["add_to_menu" => 1, "parent_category" => " "]);

  // Get unique sizes from database for this category
  global $db, $sellerId, $storeId;
  $sizes = [];

  if (!empty($sellerId) && !empty($storeId) && !empty($id)) {
    $sql = "
            SELECT DISTINCT v.size
            FROM seller_product_advanced_variants v
            INNER JOIN seller_products p ON p.product_id = v.product_id
            WHERE p.seller_id = :seller_id AND p.store_id = :store_id AND p.status = 1
              AND (p.visibility = 'publish' OR (p.visibility = 'schedule' AND p.schedule_date <= NOW()))
              AND v.size IS NOT NULL AND v.size != ''
        ";

    if ($category_column) {
      if ($parent_id_of_current > 0) {
        $sql .= " AND p.$category_column = :parent_id AND p.sub_category = :sub_id";
      } else {
        $child_ids_str = !empty($child_ids) ? implode(',', $child_ids) : '0';
        $sql .= " AND (p.$category_column = :parent_id OR p.sub_category IN ($child_ids_str))";
      }
    }
    $sql .= " ORDER BY v.size";

    try {
      $stmt = $db->prepare($sql);
      $params = [':seller_id' => $sellerId, ':store_id' => $storeId];
      if ($category_column) {
        if ($parent_id_of_current > 0) {
          $params[':parent_id'] = $real_parent_id;
          $params[':sub_id'] = $id;
        } else {
          $params[':parent_id'] = $id;
        }
      }
      $stmt->execute($params);
      $sizes = $stmt->fetchAll(PDO::FETCH_COLUMN);
    } catch (Exception $e) {
    }
  }
  ?>

  <!-- Category Navigation -->
  <?php if (!empty($categories) && $categories->rowCount()): ?>
    <section class="py-4 bg-white border-b shadow-sm">
      <div class="container mx-auto px-4">
        <div id="categoriesScroll" class="category-scroll flex gap-4 overflow-x-auto px-2 py-1 justify-center sm:justify-start">
          <?php foreach ($categories as $category): ?>
            <?php $isActive = ($category['slug'] === $slug) ? 'active-category-card' : ''; ?>
            <a href="<?= $storeUrl . "category/" . $category['slug'] ?>"
              class="category-card flex-shrink-0 flex flex-col items-center text-center w-20 p-2 rounded-lg <?= $isActive ?>">
              <div class="w-12 h-12 rounded-full mb-2 flex items-center justify-center shadow-sm category-icon"
                style="background: linear-gradient(to bottom right, var(--primary), #f472b6);">
                <?php if (!empty($category['icon'])): ?>
                  <img src="<?= UPLOADS_URL . $category['icon'] ?>" alt="<?= $category['name'] ?>" class="w-8 h-8 object-contain rounded-full" onerror="this.style.display='none'; this.parentNode.innerHTML='<?= strtoupper(substr($category['name'], 0, 1)) ?>';">
                <?php else: ?>
                  <span class="text-white font-bold"><?= strtoupper(substr($category['name'], 0, 1)) ?></span>
                <?php endif; ?>
              </div>
              <span class="text-[11px] font-semibold text-gray-700 truncate w-full category-name <?= $isActive ? 'text-white' : '' ?>">
                <?= htmlspecialchars($category['name']) ?>
              </span>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <!-- Active Category Header (Solid Primary Color) -->
  <div class="py-6 text-white" style="background-color: var(--primary);">
    <div class="container mx-auto px-4 text-center">
      <div class="flex flex-col items-center justify-center gap-3">
        <div class="bg-white/20 p-3 rounded-full backdrop-blur-sm">
          <?php if (!empty($icon)): ?>
            <img src="<?= UPLOADS_URL . $icon ?>" alt="<?= $name ?>" class="w-10 h-10 object-contain">
          <?php else: ?>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
          <?php endif; ?>
        </div>
        <div>
          <h1 class="text-2xl sm:text-3xl font-bold uppercase tracking-wide"><?= $name ?></h1>
          <p class="opacity-90 mt-2 text-sm font-medium">
            <?php if ($products && $products->rowCount() > 0): ?>
              <?= $products->rowCount() ?> products found
            <?php else: ?>
              Discover our collection
            <?php endif; ?>
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="py-8">
    <div class="w-full max-w-[1920px] mx-auto px-2 sm:px-4 lg:px-6">
      <div class="flex flex-col gap-4 lg:flex-row">

        <!-- Left Sidebar -->
        <div class="lg:w-[220px] shrink-0">
          <div class="flex flex-col gap-5">
            <?php
            // ✅ Sub-categories (Theme 9 style)
            $sidebar_sub_categories = getCategories(array("parent_category" => $real_parent_id));
            if ($sidebar_sub_categories && $sidebar_sub_categories->rowCount() > 0) :
            ?>
              <div class="py-5 bg-white rounded-xl border border-gray-200">
                <h3 class="pb-4 text-sm font-bold uppercase tracking-wider text-gray-800 border-b border-gray-100 px-5 mb-4">Sub Categories</h3>
                <div class="h-auto overflow-y-auto">
                  <?php foreach ($sidebar_sub_categories as $item) :
                    $isActiveSub = ($item['id'] == $id) ? 'bg-gray-100 font-bold' : '';
                  ?>
                    <a href="<?= $storeUrl . "category/" . $item['slug'] ?>"
                      class="transition w-full px-5 h-[50px] flex items-center gap-3 font-medium text-xs border-b border-gray-50 hover:opacity-90 <?= $isActiveSub ?>"
                      style="color: var(--primary);"
                      onmouseover="this.style.backgroundColor='color-mix(in srgb, var(--primary) 10%, white)'; this.style.color='var(--hover-color)'"
                      onmouseout="this.style.backgroundColor=''; this.style.color='var(--primary)'">
                      <?php if (!empty($item['icon'])): ?>
                        <img src="<?= UPLOADS_URL . $item['icon'] ?>" alt="<?= $item['name'] ?>" class="w-[20px] h-[20px] object-contain">
                      <?php endif; ?>
                      <?= $item['name'] ?>
                    </a>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endif; ?>

            <!-- Price Range Filter -->
            <div class="bg-white rounded-xl border border-gray-200 p-5">
              <h3 class="text-sm font-bold uppercase tracking-wider text-gray-800 mb-4 border-b border-gray-100 pb-2">Price Range</h3>
              <form id="priceForm">
                <div class="flex flex-col gap-3">
                  <input type="number" name="min_price" value="<?= isset($_GET['min_price']) ? $_GET['min_price'] : '' ?>" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-[var(--primary)]" placeholder="Min">
                  <input type="number" name="max_price" value="<?= isset($_GET['max_price']) ? $_GET['max_price'] : '' ?>" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-[var(--primary)]" placeholder="Max">
                </div>
                <button type="submit" class="w-full mt-3 py-2 text-xs font-bold uppercase text-white rounded-lg hover:opacity-90 transition" style="background-color: var(--primary);">
                  Apply Filter
                </button>
              </form>
            </div>
          </div>
        </div>

        <!-- Right Products Section -->
        <div class="flex-1 w-full min-w-0">
          <!-- Toolbar (Grid/List + Sort) -->
          <div class="bg-white border border-gray-200 rounded-lg p-3 sm:p-4 mb-6 flex flex-wrap items-center justify-between gap-4">

            <!-- View Toggles -->
            <div class="flex items-center gap-1 bg-gray-100 p-1 rounded-md">
              <button id="gridButton" class="p-1.5 rounded text-white transition" style="background-color: var(--primary);">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M3 3h7v7H3V3zm11 0h7v7h-7V3zM3 14h7v7H3v-7zm11 0h7v7h-7v-7z" />
                </svg>
              </button>
              <button id="listButton" class="p-1.5 rounded text-gray-500 hover:text-gray-800 transition">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M3 4h4v4H3V4zm6 0h12v4H9V4zM3 10h4v4H3v-4zm6 0h12v4H9v-4zM3 16h4v4H3v-4zm6 0h12v4H9v-4z" />
                </svg>
              </button>
            </div>

            <!-- Sorting & Size -->
            <div class="flex items-center gap-4 text-sm">
              <?php if (!empty($sizes)): ?>
                <div class="relative">
                  <select id="sizeSelect" class="appearance-none bg-gray-50 border border-gray-200 rounded-md py-1.5 pl-3 pr-8 font-medium cursor-pointer focus:outline-none">
                    <option value="">All Sizes</option>
                    <?php foreach ($sizes as $size): ?>
                      <option value="<?= htmlspecialchars($size) ?>"><?= htmlspecialchars($size) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              <?php endif; ?>

              <div class="relative">
                <select id="sortSelect" class="appearance-none bg-gray-50 border border-gray-200 rounded-md py-1.5 pl-3 pr-8 font-medium cursor-pointer focus:outline-none">
                  <option value="default">Sort By</option>
                  <option value="low-high">Price: Low to High</option>
                  <option value="high-low">Price: High to Low</option>
                  <option value="latest">Latest First</option>
                  <option value="oldest">Oldest First</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Products Grid -->
          <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-x-2 gap-y-4 w-full" id="content">
            <?php if ($products && $products->rowCount() > 0): ?>
              <?php while ($product = $products->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="product-card" data-id="<?= $product['id'] ?>" data-price="<?= $product['price'] ?>" data-name="<?= htmlspecialchars($product['name']) ?>">
                  <?= getProductHtml($product['id']) ?>
                </div>
              <?php endwhile; ?>
            <?php else: ?>
              <div class="col-span-full py-16 text-center">
                <p class="text-gray-500">No products found in <?= $name ?> category.</p>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include_once __DIR__ . "/includes/footer.php"; ?>

  <script>
    // ============================================
    // CATEGORY PAGE JS - FILTER, SORT, GRID/LIST
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {

      const contentContainer = document.getElementById('content');
      const products = Array.from(document.querySelectorAll(".product-card"));
      const gridButton = document.getElementById("gridButton");
      const listButton = document.getElementById("listButton");

      // 1. Grid/List View Toggle
      gridButton.addEventListener('click', function() {
        contentContainer.className = "grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-x-2 gap-y-4 w-full";
        gridButton.className = "p-1.5 rounded text-white transition";
        gridButton.style.backgroundColor = "var(--primary)";
        listButton.className = "p-1.5 rounded text-gray-500 hover:text-gray-800 transition";
        listButton.style.backgroundColor = "transparent";

        products.forEach(p => {
          p.classList.remove("list-view");
          p.querySelector(".group").classList.remove("flex-row");
        });
      });

      listButton.addEventListener('click', function() {
        contentContainer.className = "grid grid-cols-1 gap-4 w-full";
        gridButton.className = "p-1.5 rounded text-gray-500 hover:text-gray-800 transition";
        gridButton.style.backgroundColor = "transparent";
        listButton.className = "p-1.5 rounded text-white transition";
        listButton.style.backgroundColor = "var(--primary)";

        products.forEach(p => {
          p.classList.add("list-view");
          p.querySelector(".group").classList.add("flex-row");
        });
      });

      // 2. Price Form Submission
      document.getElementById('priceForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const min = document.querySelector('input[name="min_price"]').value;
        const max = document.querySelector('input[name="max_price"]').value;
        if (min && max && Number(min) > Number(max)) {
          alert('Min price cannot be greater than max');
          return;
        }
        const url = new URL(window.location.href);
        if (min) url.searchParams.set('min_price', min);
        else url.searchParams.delete('min_price');
        if (max) url.searchParams.set('max_price', max);
        else url.searchParams.delete('max_price');
        window.location.href = url.toString();
      });

      // 3. Sorting
      document.getElementById('sortSelect').addEventListener('change', function() {
        sortProducts(this.value);
      });

      function sortProducts(sortType) {
        if (!contentContainer) return;
        const productItems = Array.from(contentContainer.querySelectorAll('.product-card'));

        switch (sortType) {
          case 'low-high':
            productItems.sort((a, b) => Number(a.dataset.price) - Number(b.dataset.price));
            break;
          case 'high-low':
            productItems.sort((a, b) => Number(b.dataset.price) - Number(a.dataset.price));
            break;
          case 'latest':
            productItems.sort((a, b) => Number(b.dataset.id) - Number(a.dataset.id));
            break;
          case 'oldest':
            productItems.sort((a, b) => Number(a.dataset.id) - Number(b.dataset.id));
            break;
          default:
            return;
        }
        contentContainer.innerHTML = '';
        productItems.forEach(product => contentContainer.appendChild(product));
      }

      // 4. Variant Selection & Add to Cart
      document.querySelectorAll('.variantSelect').forEach(select => {
        select.addEventListener('change', function() {
          const selected = this.options[this.selectedIndex];
          const card = this.closest('.group');
          const addBtn = card.querySelector('.customAddToCartBtn');
          const viewCartBtn = card.querySelector('.viewCartBtn');

          if (!selected.value) return;

          const img = selected.dataset.image;
          const price = selected.dataset.price;
          const mrp = selected.dataset.mrp;
          const stock = Number(selected.dataset.stock) || 0;
          const unlimited = Number(selected.dataset.unlimited) || 0;
          const variantType = selected.dataset.variantType;
          const variantValue = selected.value;
          const inCart = selected.dataset.inCart === '1';

          // Update Image & Price
          const imageEl = card.querySelector('.productImage');
          const priceEl = card.querySelector('.productPrice');
          const mrpEl = card.querySelector('.productMrp');

          if (imageEl && img) imageEl.src = img;
          if (priceEl) priceEl.textContent = "<?= currencyToSymbol($storeCurrency) ?>" + Number(price).toLocaleString();
          if (mrpEl) {
            if (mrp && mrp > 0) {
              mrpEl.textContent = "<?= currencyToSymbol($storeCurrency) ?>" + Number(mrp).toLocaleString();
              mrpEl.style.display = 'inline';
            } else {
              mrpEl.style.display = 'none';
            }
          }

          // Update Button Data Attributes
          addBtn.dataset.variant = "";
          addBtn.dataset.advancedvariant = "";

          if (variantType === 'main') {
            addBtn.dataset.variant = "";
            addBtn.dataset.advancedvariant = "";
          } else if (variantType === 'advanced') {
            addBtn.dataset.advancedvariant = variantValue;
          } else {
            addBtn.dataset.variant = variantValue;
          }

          const isOutOfStock = stock <= 0 && unlimited !== 1;

          if (inCart) {
            addBtn.classList.add('hidden');
            viewCartBtn.classList.remove('hidden');
          } else {
            addBtn.classList.remove('hidden');
            viewCartBtn.classList.add('hidden');
            addBtn.disabled = false;

            if (isOutOfStock) {
              addBtn.disabled = true;
              addBtn.innerHTML = 'Sold Out';
              addBtn.classList.remove('bg-[var(--primary-color)]', 'text-white');
              addBtn.classList.add('bg-gray-200', 'text-gray-400', 'cursor-not-allowed');
            } else {
              addBtn.innerHTML = 'Add to Bag';
              addBtn.classList.remove('bg-gray-200', 'text-gray-400', 'cursor-not-allowed');
              addBtn.classList.add('bg-[var(--primary-color)]', 'text-white');
            }
          }
        });
      });

      // 5. AJAX Add to Cart
      document.addEventListener('click', function(e) {
        if (e.target.closest('.customAddToCartBtn')) {
          const btn = e.target.closest('.customAddToCartBtn');
          if (btn.disabled) return;

          e.preventDefault();
          const productId = btn.dataset.id;
          const variant = btn.dataset.variant || "";
          const advancedVariant = btn.dataset.advancedvariant || "";
          const card = btn.closest('.group');
          const viewCartBtn = card.querySelector('.viewCartBtn');

          const originalText = btn.innerHTML;
          btn.disabled = true;
          btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

          $.ajax({
            url: 'shop/ajax/add-to-cart.php',
            type: 'POST',
            data: {
              product_id: productId,
              variant: variant,
              advanced_variant: advancedVariant
            },
            success: function(result) {
              btn.disabled = false;
              btn.innerHTML = originalText;

              let response;
              try {
                response = JSON.parse(result);
              } catch (err) {
                response = {
                  success: true
                };
              }

              if (response.success) {
                btn.classList.add('hidden');
                if (viewCartBtn) viewCartBtn.classList.remove('hidden');
                if (window.showCustomToast) window.showCustomToast('Added to cart!', 'success');
              } else {
                if (window.showCustomToast) window.showCustomToast(response.message || 'Failed', 'error');
              }
            },
            error: function() {
              btn.disabled = false;
              btn.innerHTML = originalText;
              if (window.showCustomToast) window.showCustomToast('Network error', 'error');
            }
          });
        }
      });

      // Initialize
      // Price Filter if params exist (re-fetch)
      const urlParams = new URLSearchParams(window.location.search);
      if (urlParams.has('min_price') || urlParams.has('max_price')) {
        // Optional: You can scroll to content or re-trigger filter
      }
    });
  </script>
</body>

</html>