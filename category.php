<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Archery Asia - Shop</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* dropdown right-to-left animation */
    .dropdown-panel {
      transform: translateX(20px);
      opacity: 0;
      visibility: hidden;
      transition: transform 0.25s ease, opacity 0.2s ease, visibility 0s linear 0.2s;
    }
    .group:hover .dropdown-panel {
      transform: translateX(0);
      opacity: 1;
      visibility: visible;
      transition: transform 0.25s ease, opacity 0.2s ease, visibility 0s linear 0s;
    }
    /* product card base */
    .product-card {
      transition: all 0.2s ease;
    }
    /* custom select arrow styling */
    .custom-select {
      -webkit-appearance: none;
      -moz-appearance: none;
      appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 0.75rem center;
      background-size: 12px 12px;
      padding-right: 2.25rem;
    }
    /* remove default arrow in IE */
    .custom-select::-ms-expand {
      display: none;
    }
    /* responsive list view fix */
    @media (max-width: 640px) {
      .product-card.list-view {
        flex-direction: column !important;
        align-items: stretch !important;
      }
      .product-card.list-view .list-image-link {
        width: 100% !important;
      }
      .product-card.list-view .list-content {
        text-align: center !important;
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
      }
    }
  </style>
</head>
<body class="bg-white font-sans text-[#292d38] antialiased">

  <!-- ================================================== -->
  <!-- TOP BAR                                             -->
  <!-- ================================================== -->
  <div class="bg-[#252a35] text-white">
    <div class="mx-auto flex h-[30px] max-w-[1360px] items-center justify-between px-4 text-[9px] font-semibold sm:px-8">
      <div class="flex items-center gap-2">
        <a href="mailto:support@archeryasia.com" class="hover:text-gray-300">SUPPORT@ARCHERYASIA.COM</a>
        <span>/</span>
        <a href="https://wa.me/919345665181" class="hover:text-gray-300">WHATSAPP: +91 9345665181</a>
      </div>
      <a href="#coach-account" class="hidden hover:text-gray-300 sm:block">OPEN COACH ACCOUNT</a>
    </div>
  </div>

    <!-- ================================================== -->
    <!-- MAIN HEADER                                         -->
    <!-- ================================================== -->
    <header class="relative z-40 bg-white shadow-sm">
        <div class="mx-auto max-w-[1360px] px-5 sm:px-8">
            <div class="flex h-[100px] items-center justify-between gap-4 lg:h-[132px]">

                <!-- LOGO -->
 <a href="./index.html" class="shrink-0">
                    <img src="https://www.archeryasia.com/cdn/shop/files/Logo.png?v=1696172342" alt="Archery Asia"
                        class="w-[140px] object-contain sm:w-[175px]">
                </a>

                <!-- DESKTOP NAVIGATION -->
                <nav class="hidden items-center gap-4 xl:flex 2xl:gap-5">
                    <a href="./category-shown.html"
                        class="text-[11px] font-semibold uppercase transition hover:text-red-600">Bows</a>
                    <a href="./category-shown.html"
                        class="text-[11px] font-semibold uppercase transition hover:text-red-600">Arrows</a>

                    <!-- ACCESSORIES DROPDOWN (opens right to left) -->
                    <div class="group relative">
                        <a href="./category-shown.html"
                            class="flex items-center gap-1 text-[11px] font-semibold uppercase hover:text-red-600">
                            Accessories
                            <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                <path d="m5 7 5 5 5-5" />
                            </svg>
                        </a>
                        <div class="dropdown-panel absolute right-0 top-full z-50 mt-3 w-52 bg-white py-2 shadow-xl">
                            <a href="./category-shown.html"
                                class="block px-5 py-3 text-xs hover:bg-gray-50 hover:text-red-600">All Accessories</a>
                            <a href="./category-shown.html"
                                class="block px-5 py-3 text-xs hover:bg-gray-50 hover:text-red-600">Stabilizers</a>
                            <a href="./category-shown.html"
                                class="block px-5 py-3 text-xs hover:bg-gray-50 hover:text-red-600">Bowstrings</a>
                            <a href="./category-shown.html"
                                class="block px-5 py-3 text-xs hover:bg-gray-50 hover:text-red-600">Quivers</a>
                        </div>
                    </div>

                    <!-- QUICK SHOP DROPDOWN (opens right to left) -->
                    <div class="group relative">
                        <a href="./category-shown.html"
                            class="flex items-center gap-1 text-[11px] font-semibold uppercase hover:text-red-600">
                            Quick Shop
                            <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                <path d="m5 7 5 5 5-5" />
                            </svg>
                        </a>
                        <div class="dropdown-panel absolute right-0 top-full z-50 mt-3 w-52 bg-white py-2 shadow-xl">
                            <a href="./category-shown.html"
                                class="block px-5 py-3 text-xs hover:bg-gray-50 hover:text-red-600">Best Sellers</a>
                            <a href="./category-shown.html"
                                class="block px-5 py-3 text-xs hover:bg-gray-50 hover:text-red-600">New Arrivals</a>
                            <a href="./category-shown.html"
                                class="block px-5 py-3 text-xs hover:bg-gray-50 hover:text-red-600">Starter Pack</a>
                        </div>
                    </div>
                </nav>

                <!-- RIGHT HEADER ACTIONS -->
                <div class="flex shrink-0 items-center gap-3 sm:gap-4">
                    <a href="./login.html"
                        class="hidden whitespace-nowrap text-[11px] font-semibold hover:text-red-600 lg:block">Login /
                        Register</a>
                    <div class="hidden h-[60px] w-px bg-gray-200 lg:block"></div>
                    <a href="./profile.html" aria-label="Account" class="hidden hover:text-red-600 sm:block">
                        <svg class="h-[17px] w-[17px]" viewBox="0 0 24 24" fill="currentColor">
                            <circle cx="12" cy="7" r="4" />
                            <path d="M4.5 21v-2a7.5 7.5 0 0 1 15 0v2z" />
                        </svg>
                    </a>
                    <a href="#" aria-label="Search" class="hover:text-red-600">
                        <svg class="h-[17px] w-[17px]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.6">
                            <circle cx="10.8" cy="10.8" r="6.3" />
                            <path d="m16 16 4 4" stroke-linecap="round" />
                        </svg>
                    </a>
                    <a href="#" aria-label="Wishlist" class="hidden hover:text-red-600 sm:block">
                        <svg class="h-[17px] w-[17px]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.6">
                            <path
                                d="M20.8 8.8c0 4.2-8.8 10-8.8 10S3.2 13 3.2 8.8A4.3 4.3 0 0 1 12 6.6a4.3 4.3 0 0 1 8.8 2.2Z" />
                        </svg>
                    </a>

                    <a href="./cart.html" aria-label="Shopping cart" class="relative hover:text-red-600">
                        <svg class="h-[19px] w-[19px]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.6">
                            <path d="M2.5 3.5h2l2.4 12h11.8l2.2-8.5H5.3" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <circle cx="9" cy="19.5" r="1.2" />
                            <circle cx="17" cy="19.5" r="1.2" />
                        </svg>
                        <span class="absolute -right-2 -top-2 text-[9px]">0</span>
                    </a>
                    <button class="hidden items-center gap-1 text-[10px] font-medium md:flex">INR <svg class="h-3 w-3"
                            viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="m5 7 5 5 5-5" />
                        </svg></button>
                    <button id="mobileMenuButton" aria-label="Open navigation menu" aria-expanded="false"
                        class="xl:hidden">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <path d="M3 6h18M3 12h18M3 18h18" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- MOBILE NAVIGATION (slide from right) -->
            <nav id="mobileMenu"
                class="fixed inset-y-0 right-0 z-50 hidden w-[280px] flex-col bg-white shadow-2xl transition-transform duration-300 ease-in-out xl:hidden mobile-nav-scroll overflow-y-auto">
                <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4">
                    <span class="text-sm font-bold uppercase tracking-wider text-[#292d38]">Menu</span>
                    <button id="closeMobileMenu" aria-label="Close navigation menu"
                        class="text-gray-500 hover:text-red-600">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <path d="M6 6l12 12M18 6L6 18" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
                <div class="flex flex-col gap-1 p-4">
                    <a href="./category-shown.html"
                        class="rounded px-3 py-3 text-sm font-semibold uppercase hover:bg-gray-50 hover:text-red-600">Bows</a>
                    <a href="./category-shown.html"
                        class="rounded px-3 py-3 text-sm font-semibold uppercase hover:bg-gray-50 hover:text-red-600">Arrows</a>
                    <!-- Accessories with submenu -->
                    <div>
                        <button
                            class="flex w-full items-center justify-between rounded px-3 py-3 text-sm font-semibold uppercase hover:bg-gray-50 hover:text-red-600 mobile-dropdown-toggle"
                            data-target="mobileAccordion">
                            Accessories
                            <svg class="h-4 w-4 transition-transform" viewBox="0 0 20 20" fill="currentColor">
                                <path d="m5 7 5 5 5-5" />
                            </svg>
                        </button>
                        <div id="mobileAccordion" class="hidden flex-col pl-6">
                            <a href="./category-shown.html"
                                class="rounded px-3 py-2 text-xs uppercase hover:text-red-600">All Accessories</a>
                            <a href="./category-shown.html"
                                class="rounded px-3 py-2 text-xs uppercase hover:text-red-600">Stabilizers</a>
                            <a href="./category-shown.html"
                                class="rounded px-3 py-2 text-xs uppercase hover:text-red-600">Bowstrings</a>
                            <a href="./category-shown.html"
                                class="rounded px-3 py-2 text-xs uppercase hover:text-red-600">Quivers</a>
                        </div>
                    </div>
                    <!-- Quick Shop with submenu -->
                    <div>
                        <button
                            class="flex w-full items-center justify-between rounded px-3 py-3 text-sm font-semibold uppercase hover:bg-gray-50 hover:text-red-600 mobile-dropdown-toggle"
                            data-target="mobileQuickShop">
                            Quick Shop
                            <svg class="h-4 w-4 transition-transform" viewBox="0 0 20 20" fill="currentColor">
                                <path d="m5 7 5 5 5-5" />
                            </svg>
                        </button>
                        <div id="mobileQuickShop" class="hidden flex-col pl-6">
                            <a href="./category-shown.html"
                                class="rounded px-3 py-2 text-xs uppercase hover:text-red-600">Best Sellers</a>
                            <a href="./category-shown.html"
                                class="rounded px-3 py-2 text-xs uppercase hover:text-red-600">New Arrivals</a>
                            <a href="./category-shown.html"
                                class="rounded px-3 py-2 text-xs uppercase hover:text-red-600">Starter Pack</a>
                        </div>
                    </div>
                    <a href="./category-shown.html"
                        class="rounded px-3 py-3 text-sm font-semibold uppercase hover:bg-gray-50 hover:text-red-600">Shop
                        All</a>
                    <a href="./login.html"
                        class="mt-4 rounded border border-gray-200 px-3 py-3 text-center text-sm font-semibold uppercase hover:border-red-600 hover:text-red-600">Login
                        / Register</a>
                </div>
            </nav>

        </div>
    </header>

  <!-- MAIN SHOP SECTION -->
  <main class="mx-auto max-w-[1360px] px-4 py-8 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-[245px_minmax(0,1fr)] lg:gap-8">

      <!-- ================= SIDEBAR ================= -->
      <aside class="space-y-8">
        <!-- AVAILABILITY -->
        <section>
          <h2 class="border-b-2 border-[#ed0033] pb-3 text-lg font-medium">Availability</h2>
          <div class="mt-4 space-y-4 text-sm">
            <label class="flex cursor-pointer items-center gap-3">
              <input type="radio" name="availability" value="all" checked class="h-4 w-4 accent-[#ed0033]">
              <span>All products</span>
            </label>
            <label class="flex cursor-pointer items-center gap-3">
              <input type="radio" name="availability" value="in-stock" class="h-4 w-4 accent-[#ed0033]">
              <span>In stock</span>
            </label>
            <label class="flex cursor-pointer items-center gap-3">
              <input type="radio" name="availability" value="out-of-stock" class="h-4 w-4 accent-[#ed0033]">
              <span>Out of stock</span>
            </label>
          </div>
          <div class="mt-5 flex items-center justify-between">
            <button type="button" id="clearAvailability" class="rounded bg-[#ed0033] px-5 py-2 text-xs font-medium text-white transition hover:bg-[#c9002b]">Clear</button>
            <button type="button" id="applyAvailability" class="rounded bg-[#ed0033] px-5 py-2 text-xs font-medium text-white transition hover:bg-[#c9002b]">Apply</button>
          </div>
        </section>

        <!-- PRICE FILTER -->
        <section>
          <h2 class="border-b-2 border-[#ed0033] pb-3 text-lg font-medium">Price</h2>
          <p class="mt-4 text-sm text-gray-600">The highest price is Rs. 140,000.00</p>
          <div class="mt-4 space-y-4">
            <div>
              <label for="minPrice" class="mb-2 block text-sm">From ₹</label>
              <input type="number" id="minPrice" min="0" placeholder="Minimum price" class="w-full rounded border border-gray-200 px-3 py-3 text-sm outline-none transition focus:border-[#ed0033]">
            </div>
            <div>
              <label for="maxPrice" class="mb-2 block text-sm">To ₹</label>
              <input type="number" id="maxPrice" min="0" placeholder="Maximum price" class="w-full rounded border border-gray-200 px-3 py-3 text-sm outline-none transition focus:border-[#ed0033]">
            </div>
          </div>
          <div class="mt-5 flex items-center justify-between">
            <button type="button" id="clearPrice" class="rounded bg-[#ed0033] px-5 py-2 text-xs font-medium text-white transition hover:bg-[#c9002b]">Clear</button>
            <button type="button" id="applyPrice" class="rounded bg-[#ed0033] px-5 py-2 text-xs font-medium text-white transition hover:bg-[#c9002b]">Apply</button>
          </div>
        </section>

        <!-- CLEAR ALL -->
        <button type="button" id="clearAll" class="w-full rounded bg-[#ed0033] px-4 py-3 text-sm font-medium text-white transition hover:bg-[#c9002b]">Clear All Filters</button>
      </aside>

      <!-- ================= PRODUCTS AREA ================= -->
      <section class="min-w-0">
        <!-- TOOLBAR -->
<!-- ================= TOOLBAR ================= -->
<div class="mb-6 flex flex-wrap items-center justify-between gap-x-6 gap-y-3 bg-[#f3f3f3] px-3 py-3 sm:px-4">

  <!-- GRID / LIST BUTTONS -->
  <div class="flex items-center gap-1">
    <button type="button" id="gridButton" aria-label="Grid view" aria-pressed="true"
      class="flex h-9 w-9 items-center justify-center rounded bg-[#ed0033] text-white transition hover:bg-[#c9002b]">
      <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
        <path d="M3 3h7v7H3V3zm11 0h7v7h-7V3zM3 14h7v7H3v-7zm11 0h7v7h-7v-7z" />
      </svg>
    </button>

    <button type="button" id="listButton" aria-label="List view" aria-pressed="false"
      class="flex h-9 w-9 items-center justify-center rounded bg-white text-gray-600 transition hover:text-[#ed0033]">
      <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
        <path d="M3 4h4v4H3V4zm6 0h12v4H9V4zM3 10h4v4H3v-4zm6 0h12v4H9v-4zM3 16h4v4H3v-4zm6 0h12v4H9v-4z" />
      </svg>
    </button>
  </div>

  <!-- PAGINATE BY + SORT BY GROUP -->
  <div class="flex flex-wrap items-center gap-x-8 gap-y-3">

    <!-- PAGINATE BY -->
    <div class="flex items-center gap-2 text-sm">
      <label for="pageSize" class="whitespace-nowrap font-medium">Paginate by</label>
      <div class="relative">
        <select id="pageSize"
          class="cursor-pointer appearance-none rounded border-0 bg-[#ed0033] py-2 pl-3 pr-9 text-sm font-medium text-white outline-none">
          <option value="3">3</option>
          <option value="6">6</option>
          <option value="9" selected>9</option>
          <option value="10">10</option>
        </select>
        <!-- custom arrow icon -->
        <span class="pointer-events-none absolute right-2.5 top-1/2 flex -translate-y-1/2 items-center justify-center text-white">
          <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m5 7 5 5 5-5" />
          </svg>
        </span>
      </div>
    </div>

    <!-- SORT BY -->
    <div class="flex items-center gap-2 text-sm">
      <label for="sortProducts" class="whitespace-nowrap font-medium">Sort by</label>
      <div class="relative">
        <select id="sortProducts"
          class="max-w-[170px] cursor-pointer appearance-none rounded border-0 bg-[#ed0033] py-2 pl-3 pr-9 text-sm font-medium text-white outline-none">
          <option value="featured">Featured</option>
          <option value="name-asc">Name, A to Z</option>
          <option value="name-desc">Name, Z to A</option>
          <option value="price-asc">Price, low to high</option>
          <option value="price-desc">Price, high to low</option>
        </select>
        <!-- custom arrow icon -->
        <span class="pointer-events-none absolute right-2.5 top-1/2 flex -translate-y-1/2 items-center justify-center text-white">
          <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m5 7 5 5 5-5" />
          </svg>
        </span>
      </div>
    </div>

  </div>

</div>

        <!-- PRODUCT GRID -->
        <div id="productGrid" class="grid grid-cols-1 gap-x-5 gap-y-10 sm:grid-cols-2 xl:grid-cols-3">
          <!-- PRODUCT 1 -->
          <article class="product-card group min-w-0" data-name="Cartel Hip Quiver Competition" data-price="5000" data-stock="in-stock">
            <a href="./product-2.html" class="block">
              <div class="flex aspect-square items-center justify-center overflow-hidden bg-white">
                <img src="https://www.archeryasia.com/cdn/shop/files/101857-2_600x.webp?v=1782881634" alt="Cartel Hip Quiver Competition" class="h-full w-full object-contain p-3 transition duration-500 group-hover:scale-110">
              </div>
            </a>
            <div class="px-2 pt-4 text-center">
              <a href="./product-2.html" class="text-sm font-semibold leading-6 transition-colors hover:text-[#ed0033]">Cartel | Hip Quiver (Competition)</a>
              <p class="mt-1 text-sm text-gray-500">Rs. 5,000.00</p>
              <div class="mt-3 flex justify-center gap-2">
                <button type="button" aria-label="Black" class="h-6 w-6 border border-gray-300 bg-black ring-offset-2 transition hover:ring-2 hover:ring-[#ed0033]"></button>
                <button type="button" aria-label="Blue" class="h-6 w-6 border border-gray-300 bg-blue-600 ring-offset-2 transition hover:ring-2 hover:ring-[#ed0033]"></button>
              </div>
            </div>
          </article>

          <!-- PRODUCT 2 -->
          <article class="product-card group min-w-0" data-name="JVD Target Face FITA" data-price="54.09" data-stock="in-stock">
            <a href="./product.html" class="block">
              <div class="flex aspect-square items-center justify-center overflow-hidden bg-white">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR1l41-wyds5aC3azjn36WVKKh6hAMaL8qn3vp0SNXnLg&s=10" alt="JVD Target Face FITA" class="h-full w-full object-contain p-3 transition duration-500 group-hover:scale-110">
              </div>
            </a>
            <div class="px-2 pt-4 text-center">
              <a href="./product.html" class="text-sm font-semibold leading-6 transition-colors hover:text-[#ed0033]">JVD Target Face FITA 60cm Centre 5-Ring</a>
              <p class="mt-1 text-sm text-gray-500">Rs. 54.09</p>
            </div>
          </article>

          <!-- PRODUCT 3 -->
          <article class="product-card group min-w-0" data-name="MK Korea Limbs Zest Formula" data-price="140000" data-stock="in-stock">
            <a href="./product.html" class="block">
              <div class="flex aspect-square items-center justify-center overflow-hidden bg-white">
                <img src="https://www.archeryasia.com/cdn/shop/files/5220010_1024x_216e9a1a-4eb3-4393-8b3a-7bc678c00473_600x.jpg?v=1784287443" alt="MK Korea Limbs Zest Formula" class="h-full w-full object-contain p-3 transition duration-500 group-hover:scale-110">
              </div>
            </a>
            <div class="px-2 pt-4 text-center">
              <a href="./product.html" class="text-sm font-semibold leading-6 transition-colors hover:text-[#ed0033]">MK Korea | Limbs Zest Formula Carbon/Wood</a>
              <p class="mt-1 text-sm text-gray-500">Rs. 140,000.00</p>
            </div>
          </article>

          <!-- PRODUCT 4 -->
          <article class="product-card group min-w-0" data-name="RamRods Stabilizer Short Vektor" data-price="35000" data-stock="in-stock">
            <a href="./product-2.html" class="block">
              <div class="flex aspect-square items-center justify-center overflow-hidden bg-white">
                <img src="https://www.archeryasia.com/cdn/shop/files/Vektor_v2_Side_Rod_13_Iso_1000px_1200x1200_d8a99da3-9629-415a-bc7f-ba6264601cf4_600x.png?v=1781619116" alt="RamRods Stabilizer Short Vektor" class="h-full w-full object-contain p-3 transition duration-500 group-hover:scale-110">
              </div>
            </a>
            <div class="px-2 pt-4 text-center">
              <a href="./product-2.html" class="text-sm font-semibold leading-6 transition-colors hover:text-[#ed0033]">RamRods | Stabilizer Short Vektor 2 Tungsten</a>
              <p class="mt-1 text-sm text-gray-500">Rs. 35,000.00</p>
            </div>
          </article>

          <!-- PRODUCT 5 -->
          <article class="product-card group min-w-0" data-name="RamRods Stabilizer Long Vektor" data-price="65000" data-stock="in-stock">
            <a href="./product.html" class="block">
              <div class="flex aspect-square items-center justify-center overflow-hidden bg-white">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQNT2SH3U4ChsnEeJn-qWeXD4teurPMwQUW7xGSuwEmGmHbjOER_4scVak&s=10" alt="RamRods Stabilizer Long Vektor" class="h-full w-full object-contain p-3 transition duration-500 group-hover:scale-110">
              </div>
            </a>
            <div class="px-2 pt-4 text-center">
              <a href="./product.html" class="text-sm font-semibold leading-6 transition-colors hover:text-[#ed0033]">RamRods | Stabilizer Long Vektor 2 Tungsten</a>
              <p class="mt-1 text-sm text-gray-500">Rs. 65,000.00</p>
            </div>
          </article>

          <!-- PRODUCT 6 -->
          <article class="product-card group min-w-0" data-name="Archery Competition Arrows" data-price="8500" data-stock="out-of-stock">
            <a href="./product-2.html" class="block">
              <div class="flex aspect-square items-center justify-center overflow-hidden bg-white">
                <img src="https://www.archeryasia.com/cdn/shop/files/6180271A_1024x_0cdb7a54-8b22-490e-bfdf-3a779c866258_600x.jpg?v=1781802274" alt="Archery Competition Arrows" class="h-full w-full object-contain p-3 transition duration-500 group-hover:scale-110">
              </div>
            </a>
            <div class="px-2 pt-4 text-center">
              <a href="./product-2.html" class="text-sm font-semibold leading-6 transition-colors hover:text-[#ed0033]">Competition Archery Arrows</a>
              <p class="mt-1 text-sm text-gray-500">Rs. 8,500.00</p>
              <p class="mt-2 text-xs text-gray-400">Out of stock</p>
            </div>
          </article>

          <!-- PRODUCT 7 -->
          <article class="product-card group min-w-0" data-name="Professional Bow String" data-price="2500" data-stock="in-stock">
            <a href="./product.html" class="block">
              <div class="flex aspect-square items-center justify-center overflow-hidden bg-white">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRN-tE0Ft-zAl5VbXfzH94-xxJeCtZUaJdz3E54oNtE256AF10I9w7qPqO3&s=10" alt="Professional Bow String" class="h-full w-full object-contain p-3 transition duration-500 group-hover:scale-110">
              </div>
            </a>
            <div class="px-2 pt-4 text-center">
              <a href="./product.html" class="text-sm font-semibold leading-6 transition-colors hover:text-[#ed0033]">Professional Bow String</a>
              <p class="mt-1 text-sm text-gray-500">Rs. 2,500.00</p>
            </div>
          </article>

          <!-- PRODUCT 8 -->
          <article class="product-card group min-w-0" data-name="Archery Arm Guard" data-price="1800" data-stock="in-stock">
            <a href="./product-2.html" class="block">
              <div class="flex aspect-square items-center justify-center overflow-hidden bg-white">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRtkBFKrmbYKaaxDPm0VwGnUU0-gMpSJ-yGMgOPLTO8Zw&s=10" alt="Archery Arm Guard" class="h-full w-full object-contain p-3 transition duration-500 group-hover:scale-110">
              </div>
            </a>
            <div class="px-2 pt-4 text-center">
              <a href="./product-2.html" class="text-sm font-semibold leading-6 transition-colors hover:text-[#ed0033]">Archery Arm Guard</a>
              <p class="mt-1 text-sm text-gray-500">Rs. 1,800.00</p>
            </div>
          </article>

          <!-- PRODUCT 9 -->
          <article class="product-card group min-w-0" data-name="Professional Archery Finger Tab" data-price="3200" data-stock="in-stock">
            <a href="./product.html" class="block">
              <div class="flex aspect-square items-center justify-center overflow-hidden bg-white">
                <img src="https://m.media-amazon.com/images/I/6191Hnr-RKL._AC_UF1000,1000_QL80_.jpg" alt="Professional Archery Finger Tab" class="h-full w-full object-contain p-3 transition duration-500 group-hover:scale-110">
              </div>
            </a>
            <div class="px-2 pt-4 text-center">
              <a href="./product.html" class="text-sm font-semibold leading-6 transition-colors hover:text-[#ed0033]">Professional Archery Finger Tab</a>
              <p class="mt-1 text-sm text-gray-500">Rs. 3,200.00</p>
            </div>
          </article>
        </div>

        <!-- PAGINATION -->
        <div id="pagination" class="mt-12 flex flex-wrap items-center justify-center gap-2"></div>
        <p id="resultsMessage" class="mt-4 text-center text-sm text-gray-500"></p>
      </section>
    </div>
  </main>

  <!-- ================= JAVASCRIPT ================= -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      // ---------- MOBILE MENU ----------
      const mobileMenuButton = document.getElementById("mobileMenuButton");
      const mobileMenu = document.getElementById("mobileMenu");
      const closeMobileMenu = document.getElementById("closeMobileMenu");

      function openMobileMenu() {
        mobileMenu.classList.remove("hidden");
        requestAnimationFrame(() => {
          mobileMenu.style.transform = "translateX(0)";
          mobileMenu.style.opacity = "1";
        });
        mobileMenuButton.setAttribute("aria-expanded", "true");
        mobileMenuButton.setAttribute("aria-label", "Close navigation menu");
      }

      function closeMobileMenuFunc() {
        mobileMenu.style.transform = "translateX(100%)";
        mobileMenu.style.opacity = "0";
        mobileMenuButton.setAttribute("aria-expanded", "false");
        mobileMenuButton.setAttribute("aria-label", "Open navigation menu");
        setTimeout(() => {
          mobileMenu.classList.add("hidden");
        }, 300);
      }

      mobileMenu.style.transform = "translateX(100%)";
      mobileMenu.style.opacity = "0";
      mobileMenu.style.transition = "transform 0.3s ease, opacity 0.3s ease";

      mobileMenuButton.addEventListener("click", function (e) {
        e.stopPropagation();
        if (mobileMenu.classList.contains("hidden")) {
          openMobileMenu();
        } else {
          closeMobileMenuFunc();
        }
      });

      closeMobileMenu.addEventListener("click", closeMobileMenuFunc);

      document.addEventListener("click", function (event) {
        if (!mobileMenu.classList.contains("hidden") &&
            !mobileMenu.contains(event.target) &&
            event.target !== mobileMenuButton &&
            !mobileMenuButton.contains(event.target)) {
          closeMobileMenuFunc();
        }
      });

      // mobile dropdown toggles
      document.querySelectorAll('.mobile-dropdown-toggle').forEach(btn => {
        btn.addEventListener('click', function (e) {
          e.preventDefault();
          const targetId = this.getAttribute('data-target');
          const target = document.getElementById(targetId);
          const arrow = this.querySelector('svg');
          if (target) {
            target.classList.toggle('hidden');
            if (arrow) arrow.style.transform = target.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
          }
        });
      });

      // ---------- SHOP FILTERING / PAGINATION / VIEW ----------
      const productGrid = document.getElementById("productGrid");
      const products = Array.from(document.querySelectorAll(".product-card"));
      const gridButton = document.getElementById("gridButton");
      const listButton = document.getElementById("listButton");
      const pageSizeSelect = document.getElementById("pageSize");
      const sortSelect = document.getElementById("sortProducts");
      const pagination = document.getElementById("pagination");
      const resultsMessage = document.getElementById("resultsMessage");
      const minPriceInput = document.getElementById("minPrice");
      const maxPriceInput = document.getElementById("maxPrice");

      let currentView = "grid";
      let currentPage = 1;

      let activeAvailability = "all";
      let activeMinPrice = "";
      let activeMaxPrice = "";

      // ----- view switching (grid / list) -----
      function setView(view) {
        currentView = view;

        if (view === "grid") {
          productGrid.className = "grid grid-cols-1 gap-x-5 gap-y-10 sm:grid-cols-2 xl:grid-cols-3";
          gridButton.className = "flex h-9 w-9 items-center justify-center rounded bg-[#ed0033] text-white transition";
          listButton.className = "flex h-9 w-9 items-center justify-center rounded bg-white text-gray-600 transition hover:text-[#ed0033]";
          gridButton.setAttribute("aria-pressed", "true");
          listButton.setAttribute("aria-pressed", "false");

          products.forEach(function (product) {
            product.classList.remove("sm:col-span-2", "list-view");
            const imageLink = product.querySelector("a.block");
            const imageBox = imageLink.querySelector("div");
            imageLink.className = "block";
            imageBox.className = "flex aspect-square items-center justify-center overflow-hidden bg-white";
            const contentDiv = product.querySelector(".px-2");
            if (contentDiv) contentDiv.className = "px-2 pt-4 text-center";
            product.style.display = "";
            imageLink.style.width = "";
          });
        } else {
          productGrid.className = "grid grid-cols-1 gap-6";
          gridButton.className = "flex h-9 w-9 items-center justify-center rounded bg-white text-gray-600 transition hover:text-[#ed0033]";
          listButton.className = "flex h-9 w-9 items-center justify-center rounded bg-[#ed0033] text-white transition";
          gridButton.setAttribute("aria-pressed", "false");
          listButton.setAttribute("aria-pressed", "true");

          products.forEach(function (product) {
            product.classList.add("list-view");
            const imageLink = product.querySelector("a.block");
            const imageBox = imageLink.querySelector("div");
            imageLink.className = "block w-full shrink-0 sm:w-[220px] list-image-link";
            imageBox.className = "flex aspect-square items-center justify-center overflow-hidden bg-white";
            const contentDiv = product.querySelector(".px-2");
            if (contentDiv) contentDiv.className = "min-w-0 flex-1 px-2 pt-4 text-left list-content";
            product.classList.add("sm:flex-row", "sm:items-start", "sm:gap-4", "border-b", "border-gray-100", "pb-6");
          });
        }
        renderProducts();
      }

      // ----- filtering -----
      function getFilteredProducts() {
        let filtered = products.filter(function (product) {
          const price = Number(product.dataset.price);
          const stock = product.dataset.stock;
          const matchesAvailability = activeAvailability === "all" || stock === activeAvailability;
          const matchesMin = activeMinPrice === "" || price >= Number(activeMinPrice);
          const matchesMax = activeMaxPrice === "" || price <= Number(activeMaxPrice);
          return matchesAvailability && matchesMin && matchesMax;
        });

        const sortValue = sortSelect.value;
        if (sortValue === "name-asc") filtered.sort((a, b) => a.dataset.name.localeCompare(b.dataset.name));
        if (sortValue === "name-desc") filtered.sort((a, b) => b.dataset.name.localeCompare(a.dataset.name));
        if (sortValue === "price-asc") filtered.sort((a, b) => Number(a.dataset.price) - Number(b.dataset.price));
        if (sortValue === "price-desc") filtered.sort((a, b) => Number(b.dataset.price) - Number(a.dataset.price));

        return filtered;
      }

      // ----- render products with pagination -----
      function renderProducts() {
        const filtered = getFilteredProducts();
        const pageSize = Number(pageSizeSelect.value);
        const totalPages = Math.max(1, Math.ceil(filtered.length / pageSize));
        currentPage = Math.min(currentPage, totalPages);
        const start = (currentPage - 1) * pageSize;
        const visibleProducts = filtered.slice(start, start + pageSize);

        products.forEach(p => { p.style.display = "none"; });

        visibleProducts.forEach(product => {
          if (currentView === "grid") {
            product.style.display = "block";
          } else {
            product.style.display = "flex";
          }
          productGrid.appendChild(product);
        });

        pagination.innerHTML = "";
        if (totalPages > 1) {
          const prevBtn = document.createElement("button");
          prevBtn.type = "button";
          prevBtn.textContent = "Previous";
          prevBtn.className = "rounded border border-gray-200 px-4 py-2 text-sm transition hover:border-[#ed0033] hover:text-[#ed0033] disabled:cursor-not-allowed disabled:opacity-40";
          prevBtn.disabled = currentPage === 1;
          prevBtn.addEventListener("click", () => { if (currentPage > 1) { currentPage--; renderProducts(); } });
          pagination.appendChild(prevBtn);

          for (let page = 1; page <= totalPages; page++) {
            const btn = document.createElement("button");
            btn.type = "button";
            btn.textContent = page;
            btn.className = page === currentPage
              ? "h-9 w-9 rounded bg-[#ed0033] text-sm text-white"
              : "h-9 w-9 rounded border border-gray-200 text-sm transition hover:border-[#ed0033] hover:text-[#ed0033]";
            btn.addEventListener("click", () => { currentPage = page; renderProducts(); });
            pagination.appendChild(btn);
          }

          const nextBtn = document.createElement("button");
          nextBtn.type = "button";
          nextBtn.textContent = "Next";
          nextBtn.className = "rounded border border-gray-200 px-4 py-2 text-sm transition hover:border-[#ed0033] hover:text-[#ed0033] disabled:cursor-not-allowed disabled:opacity-40";
          nextBtn.disabled = currentPage === totalPages;
          nextBtn.addEventListener("click", () => { if (currentPage < totalPages) { currentPage++; renderProducts(); } });
          pagination.appendChild(nextBtn);
        }

        resultsMessage.textContent = filtered.length
          ? "Showing " + (start + 1) + "–" + Math.min(start + pageSize, filtered.length) + " of " + filtered.length + " products"
          : "No products found. Try changing your filters.";
      }

      // ----- event listeners -----
      gridButton.addEventListener("click", () => setView("grid"));
      listButton.addEventListener("click", () => setView("list"));

      document.getElementById("applyAvailability").addEventListener("click", () => {
        activeAvailability = document.querySelector('input[name="availability"]:checked').value;
        currentPage = 1;
        renderProducts();
      });

      document.getElementById("clearAvailability").addEventListener("click", () => {
        document.querySelector('input[name="availability"][value="all"]').checked = true;
        activeAvailability = "all";
        currentPage = 1;
        renderProducts();
      });

      document.getElementById("applyPrice").addEventListener("click", () => {
        const min = minPriceInput.value.trim();
        const max = maxPriceInput.value.trim();

        if (min !== "" && Number(min) < 0 || max !== "" && Number(max) < 0) {
          alert("Price cannot be negative.");
          return;
        }
        if (min !== "" && max !== "" && Number(min) > Number(max)) {
          alert("Minimum price cannot exceed maximum price.");
          return;
        }

        activeMinPrice = min;
        activeMaxPrice = max;
        currentPage = 1;
        renderProducts();
      });

      document.getElementById("clearPrice").addEventListener("click", () => {
        minPriceInput.value = "";
        maxPriceInput.value = "";
        activeMinPrice = "";
        activeMaxPrice = "";
        currentPage = 1;
        renderProducts();
      });

      document.getElementById("clearAll").addEventListener("click", () => {
        document.querySelector('input[name="availability"][value="all"]').checked = true;
        minPriceInput.value = "";
        maxPriceInput.value = "";
        activeAvailability = "all";
        activeMinPrice = "";
        activeMaxPrice = "";
        sortSelect.value = "featured";
        currentPage = 1;
        renderProducts();
      });

      pageSizeSelect.addEventListener("change", () => { currentPage = 1; renderProducts(); });
      sortSelect.addEventListener("change", () => { currentPage = 1; renderProducts(); });

      renderProducts();
    });
  </script>

  <!-- ================= ARCHERY ASIA FOOTER ================= -->
  <footer class="w-full bg-[#1b1919] text-white">
    <div class="mx-auto max-w-[1100px] px-5 pb-8 pt-12 sm:px-8">
      <div class="mb-12 flex justify-center">
        <div class="flex w-[300px] flex-col items-center border border-[#484444] p-[6px]">
          <div class="flex w-full flex-col items-center border border-[#383434] px-5 py-5">
            <a href="/" class="mb-3">
              <img src="https://www.archeryasia.com/cdn/shop/files/Asset_1_x86@2x.svg?v=1694454685" alt="Archery Asia" class="w-[210px] object-contain">
            </a>
            <div class="flex items-center gap-5 text-[#858585]">
              <a href="#" aria-label="Facebook" class="transition-colors duration-300 hover:text-white"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M13.4 21v-8h2.7l.4-3.1h-3.1v-2c0-.9.3-1.5 1.6-1.5h1.7V3.6c-.3 0-1.3-.1-2.5-.1-2.5 0-4.2 1.5-4.2 4.3v2.1H7.2V13H10v8z" /></svg></a>
              <a href="#" aria-label="Twitter" class="transition-colors duration-300 hover:text-white"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M22 5.9a8 8 0 0 1-2.4.7 4.2 4.2 0 0 0 1.8-2.3 8.4 8.4 0 0 1-2.7 1 4.2 4.2 0 0 0-7.2 3.8 11.9 11.9 0 0 1-8.6-4.4 4.2 4.2 0 0 0 1.3 5.6 4.1 4.1 0 0 1-1.9-.5v.1a4.2 4.2 0 0 0 3.4 4.1 4.2 4.2 0 0 1-1.9.1 4.2 4.2 0 0 0 3.9 2.9A8.4 8.4 0 0 1 2.5 19a11.9 11.9 0 0 0 18.3-10c0-.2 0-.4-.1-.6A8.5 8.5 0 0 0 22 5.9z" /></svg></a>
              <a href="#" aria-label="Pinterest" class="transition-colors duration-300 hover:text-white"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-3.6 19.3c0-.8 0-1.9.2-2.8l1.3-5.5s-.3-.7-.3-1.6c0-1.5.9-2.6 2-2.6.9 0 1.3.7 1.3 1.5 0 .9-.6 2.3-.9 3.6-.3 1.1.5 2 1.6 2 1.9 0 3.3-2.4 3.3-5.2 0-2.1-1.4-3.7-3.9-3.7-2.8 0-4.5 2.1-4.5 4.4 0 .8.2 1.5.6 2 .2.2.2.3.1.6l-.2.8c-.1.3-.3.4-.6.3-1.3-.5-1.9-1.9-1.9-3.4 0-2.5 2.1-5.6 6.4-5.6 3.4 0 5.6 2.5 5.6 5.1 0 3.5-2 6.2-4.9 6.2-1 0-2-.6-2.3-1.3l-.7 2.8c-.2 1-.8 2-1.2 2.7A10 10 0 1 0 12 2z" /></svg></a>
              <a href="#" aria-label="Instagram" class="transition-colors duration-300 hover:text-white"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="5" /><circle cx="12" cy="12" r="4" /><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none" /></svg></a>
              <a href="#" aria-label="LinkedIn" class="transition-colors duration-300 hover:text-white"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M5.2 8.5H2V22h3.2zM3.6 2a1.9 1.9 0 1 0 0 3.8 1.9 1.9 0 0 0 0-3.8zM22 13.8c0-4.1-2.2-6-5.2-6a4.5 4.5 0 0 0-4 2.2V8.5H9.6V22h3.2v-6.7c0-1.8.3-3.5 2.5-3.5s2.2 2 2.2 3.6V22H22z" /></svg></a>
            </div>
          </div>
        </div>
      </div>
      <div class="grid grid-cols-1 gap-x-8 gap-y-9 sm:grid-cols-2 lg:grid-cols-[1fr_1fr_0.9fr_1.15fr_1.65fr]">
        <div>
          <h3 class="mb-5 text-lg font-bold uppercase">Information <span class="mt-1.5 block h-[2px] w-[30px] bg-[#ed1234]"></span></h3>
          <ul class="space-y-3 text-[15px] text-[#858585]">
            <li><a href="/pages/about-us" class="transition hover:text-white">» About Archery Asia</a></li>
            <li><a href="/pages/careers" class="transition hover:text-white">» Careers</a></li>
            <li><a href="/pages/influencers" class="transition hover:text-white">» Influencers</a></li>
            <li><a href="/pages/coaches-program" class="transition hover:text-white">» Coaches Program</a></li>
            <li><a href="/pages/sitemap" class="transition hover:text-white">» Sitemap</a></li>
          </ul>
        </div>
        <div>
          <h3 class="mb-5 text-lg font-bold uppercase">Quick Shop <span class="mt-1.5 block h-[2px] w-[30px] bg-[#ed1234]"></span></h3>
          <ul class="space-y-3 text-[15px] text-[#858585]">
            <li><a href="/collections/all" class="transition hover:text-white">» Catalog</a></li>
            <li><a href="/collections/starter-pack" class="transition hover:text-white">» Starter Pack</a></li>
            <li><a href="/collections/new-arrivals" class="transition hover:text-white">» New Arrivals</a></li>
            <li><a href="/collections/left-hand" class="transition hover:text-white">» Left Hand</a></li>
            <li><a href="/collections/right-hand" class="transition hover:text-white">» Right Hand</a></li>
            <li><a href="/collections/clothing" class="transition hover:text-white">» Clothing</a></li>
          </ul>
        </div>
        <div>
          <h3 class="mb-5 text-lg font-bold uppercase">Service <span class="mt-1.5 block h-[2px] w-[30px] bg-[#ed1234]"></span></h3>
          <ul class="space-y-3 text-[15px] text-[#858585]">
            <li><a href="/search" class="transition hover:text-white">» Search</a></li>
            <li><a href="/blogs/news" class="transition hover:text-white">» News</a></li>
            <li><a href="/pages/help" class="transition hover:text-white">» Help</a></li>
            <li><a href="/pages/contact" class="transition hover:text-white">» Contact Us</a></li>
            <li><a href="/pages/dealer-login" class="transition hover:text-white">» Dealer Login</a></li>
          </ul>
        </div>
        <div>
          <h3 class="mb-5 text-lg font-bold uppercase">Useful Links <span class="mt-1.5 block h-[2px] w-[30px] bg-[#ed1234]"></span></h3>
          <ul class="space-y-3 text-[15px] text-[#858585]">
            <li><a href="/policies/privacy-policy" class="transition hover:text-white">» Privacy Policy</a></li>
            <li><a href="/policies/refund-policy" class="transition hover:text-white">» Returns</a></li>
            <li><a href="/policies/shipping-policy" class="transition hover:text-white">» Shipping &amp; Delivery</a></li>
            <li><a href="/policies/terms-of-service" class="transition hover:text-white">» Terms of Service</a></li>
            <li><a href="/pages/support" class="transition hover:text-white">» Support</a></li>
            <li><a href="/pages/become-a-supplier" class="transition hover:text-white">» Become a Supplier</a></li>
          </ul>
        </div>
        <div>
          <h3 class="mb-5 text-lg font-bold uppercase">About the Store <span class="mt-1.5 block h-[2px] w-[30px] bg-[#ed1234]"></span></h3>
          <p class="mb-5 text-[15px] leading-[2.3] text-[#858585]">India's professional archery store. Present in Thanjavur, Chennai, Bangalore &amp; Mumbai. Delivering all over India, Singapore and the US.</p>
          <div class="space-y-3 text-[14px] leading-relaxed text-[#858585]">
            <a href="#" class="flex items-start gap-2 transition hover:text-white"><svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="m12 3 10 9h-3v9h-6v-6h-2v6H5v-9H2z" /></svg><span>Thanjavur &amp; Chennai, Tamil Nadu, India</span></a>
            <a href="https://wa.me/919345665181" class="flex items-center gap-2 transition hover:text-white"><svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 2h10.8A2.6 2.6 0 0 1 20 4.6v14.8a2.6 2.6 0 0 1-2.6 2.6H6.6A2.6 2.6 0 0 1 4 19.4V4.6A2.6 2.6 0 0 1 6.6 2ZM12 19.5a1.2 1.2 0 1 0 0-2.4 1.2 1.2 0 0 0 0 2.4Z" /></svg><span>+91 93456 65181 (WhatsApp)</span></a>
            <a href="mailto:support@archeryasia.com" class="flex items-center gap-2 transition hover:text-white"><svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M2 5h20v14H2z" /><path d="m2 6 10 7 10-7" fill="none" stroke="#1b1919" stroke-width="1.5" /></svg><span class="break-all">support@archeryasia.com</span></a>
          </div>
        </div>
      </div>
      <div class="mt-10 border-t border-white/10 pt-5 text-center text-xs text-[#777777]">© 2026 Archery Asia. All rights reserved.</div>
    </div>
  </footer>

</body>
</html>