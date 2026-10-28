<?php include_once __DIR__ . "/includes/files_includes.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!--Php File Include For Head Links & Scripts-->
    <?php include_once __DIR__ . "/includes/head_links.php"; ?>

</head>

<body class="font-sans">




    <!--Php File Include For Nav Bar-->
    <?php include_once __DIR__ . "/includes/nav-bar.php"; ?>

    <!-- Intl tell input css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .iti,
        .select2 {
            width: 100% !important;
        }

        .select2-selection--single {
            height: 55.2px !important;
            display: flex !important;
            justify-content: center;
            align-items: center;
            font-size: 1rem;
            border-radius: 0.5rem !important;
            font-weight: 500;
            border: 2px solid #e5e7eb !important;
            padding: 0 15px;
        }

        .select2-selection__arrow {
            height: 95% !important;
        }

        .appliedCoupon {
            background-color: #ec4899 !important;
            color: #fff !important;
            border-color: #ec4899 !important;
            cursor: default;
        }

        .appliedCoupon .glowDot {
            box-shadow: 0 0 10px rgba(255, 105, 180, 0.8);
            transform: scale(1.2);
        }

        .couponItem:hover {
            cursor: pointer;
            opacity: 0.9;
        }

        /* Timeline and Step Number Styles */
        .checkout-timeline {
            position: relative;
        }

        .checkout-timeline::before {
            content: '';
            position: absolute;
            left: 25px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, #ec4899, #f97316);
            z-index: 0;
        }

        @media (max-width: 640px) {
            .checkout-timeline::before {
                display: none;
            }
        }

        .step-number {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #ec4899, #f97316);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.25rem;
            box-shadow: 0 4px 15px rgba(236, 72, 153, 0.4);
            position: relative;
            z-index: 0;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .step-number.completed {
            background: linear-gradient(135deg, #10b981, #059669);
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
            animation: none;
            /* Remove pulse animation for completed steps */
        }

        .step-number.active {
            box-shadow: 0 0 25px rgba(236, 72, 153, 0.8), 0 4px 20px rgba(236, 72, 153, 0.6);
            animation: pulse 2s infinite;
            transform: scale(1.05);
            background: linear-gradient(135deg, #ec4899, #f97316);
            /* Ensure active stays pink */
        }

        .step-content {
            width: 100%;
            transition: all 0.3s ease;
        }

        @media (min-width: 640px) {
            .step-content {
                width: calc(100% - 66px);
            }
        }

        .step-container {
            transition: all 0.3s ease;
        }

        .step-container.active .step-content {
            border-color: #ec4899;
            box-shadow: 0 10px 25px rgba(236, 72, 153, 0.15);
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Responsive improvements */
        @media (max-width: 640px) {
            .step-container {
                flex-direction: column;
                gap: 1rem;
            }

            .delivery-options {
                flex-direction: column;
                align-items: center;
            }

            .payment-options {
                grid-template-columns: 1fr;
            }

            .step-number {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }
        }

        /* Custom toastr colors to match theme */
        .toast-success {
            background-color: <?= htmlspecialchars(getData('color', 'seller_settings', "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ff007f') ?> !important;
            border-color: <?= htmlspecialchars(getData('color', 'seller_settings', "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ff007f') ?> !important;
        }

        .toast-info {
            background-color: <?= htmlspecialchars(getData('hover_color', 'seller_settings', "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ec4899') ?> !important;
            border-color: <?= htmlspecialchars(getData('hover_color', 'seller_settings', "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ec4899') ?> !important;
        }

        .toast-success .toast-close-button,
        .toast-info .toast-close-button {
            color: white !important;
            opacity: 0.8;
        }

        .toast-success .toast-close-button:hover,
        .toast-info .toast-close-button:hover {
            opacity: 1;
            color: white !important;
        }

        /* UPI QR Code Styles */
        #upiContainer {
            display: none;
        }

        #upiContainer[style*="display: block"] {
            display: block !important;
        }

        #upiQR canvas {
            max-width: 100%;
            height: auto;
            margin: 10px auto;
            display: block;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 10px;
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* UPI blur overlay for minimum order */
        #upiContainer {
            position: relative;
        }

        .upi-blur-content {
            filter: blur(4px);
            opacity: 0.6;
            pointer-events: none;
            user-select: none;
            transition: all 0.3s ease;
        }

        .upi-blur-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.85);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            border-radius: 16px;
            text-align: center;
            padding: 20px;
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
            animation: fadeInOverlay 0.3s ease;
        }

        @keyframes fadeInOverlay {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .upi-blur-overlay .lock-icon {
            font-size: 32px;
            color: #dc2626;
            margin-bottom: 8px;
        }

        .upi-blur-overlay .blur-title {
            color: #dc2626;
            font-weight: 700;
            font-size: 15px;
            margin-bottom: 4px;
        }

        .upi-blur-overlay .blur-sub {
            color: #6b7280;
            font-weight: 400;
            font-size: 13px;
        }
    </style>

    <?php
    $subTotal = 0;
    $subTotalMrp = 0;
    $subTotalWithTax = 0;
    $gstAmount = 0;
    ?>

    <div class="py-6 sm:py-10">
        <div class="px-3 sm:px-4 lg:container-fluid max-w-7xl mx-auto">

            <div class="bg-white rounded-lg mb-4 sm:mb-6">
                <?php include_once __DIR__ . "/includes/checkout-step.php"; ?>
            </div>

            <?php if (countData("*", "customer_cart", "customer_id = '$cookie_id' AND (seller_id = '$sellerId' AND store_id = '$storeId')")) { ?>
                <form id="placeOrderForm" onkeydown="return event.key != 'Enter';" class="flex flex-col gap-6 lg:flex-row lg:gap-8">

                    <!-- Hidden Order ID -->
                    <input type="hidden" id="hiddenPaymentId" name="payment_id" value="<?= $_SESSION['PENDING_PAYMENT_ID'] ?? '' ?>">

                    <!-- PayU: preserve origin -->
                    <input type="hidden" name="_return_origin" value="<?= htmlspecialchars(rtrim($storeUrl, '/'), ENT_QUOTES, 'UTF-8') ?>">

                    <!-- Left Column - Steps -->
                    <div class="lg:w-3/5 flex flex-col gap-6 checkout-timeline">

                        <!-- ================= Step 1: Delivery Area ================= -->
                        <div class="flex gap-4 step-container active" id="step-1">
                            <div class="step-number active">01</div>
                            <div class="step-content p-4 sm:p-6 bg-white border border-gray-200">
                                <h3 class="font-serifDisplay text-xl sm:text-2xl text-gray-900 mb-2">
                                    <?= getData("delivery_area_type", "seller_settings", "(seller_id = '$sellerId' AND store_id = '$storeId')") == "zip_code" ? "Check Pin Code" : "Select State" ?>
                                </h3>
                                <p class="text-xs tracking-widest uppercase text-gray-500 mb-4">
                                    <?= getData("delivery_area_type", "seller_settings", "(seller_id = '$sellerId' AND store_id = '$storeId')") == "zip_code" ? 'Please select a pin code' : 'Please select a state' ?>
                                </p>
                                <div class="flex items-center gap-3">
                                    <select class="custom-select px-4 h-[45px] border border-gray-300 w-full text-sm
                                   focus:outline-none focus:border-[<?= htmlspecialchars($primary_color) ?>]
                                   transition-colors duration-200 step-field"
                                        id="deliveryArea"
                                        name="delivery_area"
                                        data-step="1"
                                        <?= getData("delivery_area_type", "seller_settings", "(seller_id = '$sellerId' AND store_id = '$storeId')") == "zip_code" ? 'data-pincode="true"' : null ?>>
                                        <option value="" hidden selected>
                                            <?= getData("delivery_area_type", "seller_settings", "(seller_id = '$sellerId' AND store_id = '$storeId')") == "state" ? "Select State" : "Select Pin Code" ?>
                                        </option>
                                        <?php
                                        $areas = getDeliveryAreas();
                                        foreach ($areas as $key => $area) {
                                            echo '<option value="' . $area['id'] . '">' . $area['value'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <?php if (getData("delivery_area_type", "seller_settings", "(seller_id = '$sellerId' AND store_id = '$storeId')") == "zip_code") : ?>
                                    <p class="mt-4 text-sm text-gray-500">
                                        You can check our delivery pin codes from here
                                        <a href="<?= $storeUrl ?>delivery-areas"
                                            class="font-semibold hover:underline"
                                            style="color: <?= htmlspecialchars($primary_color) ?>;">Delivery Areas</a>
                                    </p>
                                <?php endif ?>
                                <div id="deliveryNotes" class="mt-3"></div>
                            </div>
                        </div>

                        <!-- ================= Step 2: Delivery Option ================= -->
                        <div class="flex gap-4 step-container" id="step-2">
                            <div class="step-number">02</div>
                            <div class="step-content relative p-4 sm:p-6 overflow-hidden bg-white border border-gray-200">
                                <h3 class="font-serifDisplay text-xl sm:text-2xl text-gray-900 mb-4">Delivery Option</h3>

                                <div class="flex gap-4 sm:gap-6 delivery-options">

                                    <label for="deliveryDelivery" class="cursor-pointer flex flex-col items-center">
                                        <div class="w-24 h-24 sm:w-32 sm:h-32 flex items-center justify-center border border-gray-200 bg-white">
                                            <img src="<?= APP_URL ?>assets/img/delivery-truck.png" alt="Delivery" class="w-16 h-16 sm:w-20 sm:h-20">
                                        </div>
                                        <div class="flex items-center justify-center gap-2 mt-3">
                                            <input type="radio" name="delivery_option" id="deliveryDelivery"
                                                class="accent-[<?= htmlspecialchars($primary_color) ?>] w-4 h-4 deliveryOption step-field"
                                                value="delivery" data-step="2" checked>
                                            <span class="text-gray-700 font-medium">Delivery</span>
                                        </div>
                                    </label>

                                    <?php if (getData("id", "seller_locations", "(seller_id = '$sellerId' AND store_id = '$storeId') AND location_type = 'pickup'")) : ?>
                                        <label for="deliveryPickup" class="cursor-pointer flex flex-col items-center">
                                            <div class="w-24 h-24 sm:w-32 sm:h-32 flex items-center justify-center border border-gray-200 bg-white">
                                                <img src="<?= APP_URL ?>assets/img/pickup.png" alt="Pickup" class="w-16 h-16 sm:w-20 sm:h-20">
                                            </div>
                                            <div class="flex items-center justify-center gap-2 mt-3">
                                                <input type="radio" name="delivery_option" id="deliveryPickup"
                                                    class="accent-[<?= htmlspecialchars($primary_color) ?>] w-4 h-4 deliveryOption step-field"
                                                    value="pickup" data-step="2">
                                                <span class="text-gray-700 font-medium">Pickup</span>
                                            </div>
                                        </label>
                                    <?php endif ?>

                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Address -->
                        <div class="flex gap-4 step-container" id="step-3">
                            <div class="step-number">03</div>
                            <div class="step-content">

                                <!-- Pickup Date & Time -->
                                <div class="p-4 sm:p-6 bg-white border border-gray-200 mb-4" id="pickupDateAndTime" style="display: none;">
                                    <h3 class="font-serifDisplay text-xl sm:text-2xl text-gray-900 mb-4">Pickup Date & Time</h3>
                                    <div class="grid gap-4 sm:gap-5 sm:grid-cols-2">
                                        <div class="col-span-2">
                                            <label for="pickupPoint" class="block text-xs tracking-widest uppercase text-gray-600 mb-2">Pickup Point</label>
                                            <select id="pickupPoint" name="pickup_point"
                                                class="px-4 h-[45px] border border-gray-300 w-full text-sm
                                           focus:outline-none focus:border-[<?= htmlspecialchars($primary_color) ?>]
                                           transition-colors duration-200 step-field" data-step="3">
                                                <option value="" hidden disabled selected>Select</option>
                                                <?php
                                                $locations = getLocations("pickup");
                                                foreach ($locations as $key => $location) :
                                                ?>
                                                    <option value="<?= $location['id'] ?>"><?= $location['name'] . " - " . $location['address_1'] ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="pickup_days" class="block text-xs tracking-widest uppercase text-gray-600 mb-2">Day</label>
                                            <select id="pickup_days" name="pickup_day"
                                                class="px-4 h-[45px] border border-gray-300 w-full text-sm
                                           focus:outline-none focus:border-[<?= htmlspecialchars($primary_color) ?>]
                                           transition-colors duration-200 step-field" data-step="3">
                                                <option value="" hidden disabled selected>Select</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="pickup_times" class="block text-xs tracking-widest uppercase text-gray-600 mb-2">Time</label>
                                            <select id="pickup_times" name="pickup_time"
                                                class="px-4 h-[45px] border border-gray-300 w-full text-sm
                                           focus:outline-none focus:border-[<?= htmlspecialchars($primary_color) ?>]
                                           transition-colors duration-200 step-field" data-step="3">
                                                <option value="" hidden disabled selected>Select</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="pickupLocations"></div>
                                </div>

                                <!-- Delivery Address -->
                                <div class="p-4 sm:p-6 bg-white border border-gray-200" id="deliveryAddress">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-2">
                                        <h3 class="font-serifDisplay text-xl sm:text-2xl text-gray-900">Delivery Address</h3>
                                        <button class="underline addAddressToggle font-semibold text-xs tracking-widest uppercase transition"
                                            style="color: <?= htmlspecialchars($primary_color) ?>;"
                                            type="button">Add Address</button>
                                    </div>
                                    <div class="mb-4 p-3 border-l-2 bg-gray-50"
                                        style="border-color: <?= htmlspecialchars($primary_color) ?>;">
                                        <p class="text-xs text-gray-600">
                                            Selecting a delivery address will automatically set the same address for billing
                                        </p>
                                    </div>
                                    <div class="grid gap-4 lg:grid-cols-2 shippingAddressList">
                                        <!-- Address cards will be loaded here -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Billing Address -->
                        <div class="flex gap-4 step-container" id="step-4">
                            <div class="step-number">04</div>
                            <div class="step-content p-4 sm:p-6 bg-white border border-gray-200">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-2">
                                    <h3 class="font-serifDisplay text-xl sm:text-2xl text-gray-900">Billing Address</h3>
                                    <button class="underline addAddressToggle font-semibold text-xs tracking-widest uppercase transition"
                                        style="color: <?= htmlspecialchars($primary_color) ?>;"
                                        type="button">Add Address</button>
                                </div>
                                <div class="mb-4 p-3 border-l-2 bg-gray-50"
                                    style="border-color: <?= htmlspecialchars($primary_color) ?>;">
                                    <p class="text-xs text-gray-600">
                                        Same as delivery address by default. You can select a different billing address if needed.
                                    </p>
                                </div>
                                <div class="grid gap-4 lg:grid-cols-2 billingAddressList">
                                    <!-- Billing address cards will be loaded here -->
                                </div>
                            </div>
                        </div>

                        <!-- Step 5: Payment Method -->
                        <div class="flex gap-4 step-container" id="step-5">
                            <div class="step-number">05</div>
                            <div class="step-content p-4 sm:p-6 bg-white border border-gray-200">
                                <h3 class="font-serifDisplay text-xl sm:text-2xl text-gray-900 mb-4">Payment Method</h3>

                                <div class="flex flex-col gap-3" id="paymentOptions">

                                    <?php if (!empty(getSettings("cod")) && plan("payment_cod")) : ?>
                                        <label for="paymentCOD"
                                            class="flex items-center gap-3 p-4 border border-gray-200 cursor-pointer hover:border-[<?= htmlspecialchars($primary_color) ?>] transition-colors duration-200">
                                            <input type="radio" name="payment_method" id="paymentCOD"
                                                class="accent-[<?= htmlspecialchars($primary_color) ?>] w-5 h-5 paymentMethod step-field"
                                                value="COD" data-step="5">
                                            <img src="<?= APP_URL ?>assets/img/cod.png" alt="COD" class="w-8 h-8 flex-shrink-0">
                                            <div>
                                                <h3 class="font-semibold text-gray-800 leading-tight">Cash On Delivery (COD)</h3>
                                                <?php $codCharges = getSettings("cod_charges") ? (float)getSettings("cod_charges") : 0; ?>
                                                <?php if ($codCharges > 0): ?>
                                                    <p class="text-sm text-gray-600">Extra <?= currencyToSymbol($storeCurrency) . number_format($codCharges, 2) ?> charges</p>
                                                <?php else: ?>
                                                    <p class="text-sm text-gray-600">No extra charges</p>
                                                <?php endif; ?>
                                            </div>
                                        </label>
                                    <?php endif ?>

                                    <?php if (!empty(getSettings("razorpay_key_id")) && plan("payment_razorpay")) : ?>
                                        <label for="paymentRazorpay"
                                            class="flex items-center gap-3 p-4 border border-gray-200 cursor-pointer hover:border-[<?= htmlspecialchars($primary_color) ?>] transition-colors duration-200">
                                            <input type="radio" name="payment_method" id="paymentRazorpay"
                                                class="accent-[<?= htmlspecialchars($primary_color) ?>] w-5 h-5 paymentMethod step-field"
                                                value="Razorpay" data-step="5">
                                            <img src="<?= APP_URL ?>assets/img/razorpay.png" alt="Razorpay" class="w-8 h-8 flex-shrink-0">
                                            <h3 class="font-semibold text-gray-800 leading-tight">Razorpay</h3>
                                        </label>
                                    <?php endif ?>

                                    <?php if ((!empty(getSettings("phonepe_key")) || !empty(getSettings("phonepe_client_id"))) && plan("payment_phonepe")) : ?>
                                        <label for="paymentPhonePe"
                                            class="flex items-center gap-3 p-4 border border-gray-200 cursor-pointer hover:border-[<?= htmlspecialchars($primary_color) ?>] transition-colors duration-200">
                                            <input type="radio" name="payment_method" id="paymentPhonePe"
                                                class="accent-[<?= htmlspecialchars($primary_color) ?>] w-5 h-5 paymentMethod step-field"
                                                value="PhonePe" data-step="5">
                                            <img src="<?= APP_URL ?>assets/img/phonepe.png" alt="PhonePe" class="w-8 h-8 flex-shrink-0">
                                            <h3 class="font-semibold text-gray-800 leading-tight">PhonePe</h3>
                                        </label>
                                    <?php endif ?>

                                    <?php if (!empty(getSettings("ippo_pay_public_key")) && plan("payment_ippopay")) : ?>
                                        <label for="paymentIppoPay"
                                            class="flex items-center gap-3 p-4 border border-gray-200 cursor-pointer hover:border-[<?= htmlspecialchars($primary_color) ?>] transition-colors duration-200">
                                            <input type="radio" name="payment_method" id="paymentIppoPay"
                                                class="accent-[<?= htmlspecialchars($primary_color) ?>] w-5 h-5 paymentMethod step-field"
                                                value="IppoPay" data-step="5">
                                            <img src="<?= APP_URL ?>assets/img/ippo-pay.png" alt="IppoPay" class="w-8 h-8 flex-shrink-0">
                                            <h3 class="font-semibold text-gray-800 leading-tight">IppoPay</h3>
                                        </label>
                                    <?php endif ?>

                                    <?php if (!empty(getSettings("bank_details")) && plan("payment_bank")) : ?>
                                        <label for="paymentBankTransfer"
                                            class="flex items-center gap-3 p-4 border border-gray-200 cursor-pointer hover:border-[<?= htmlspecialchars($primary_color) ?>] transition-colors duration-200">
                                            <input type="radio" name="payment_method" id="paymentBankTransfer"
                                                class="accent-[<?= htmlspecialchars($primary_color) ?>] w-5 h-5 paymentMethod step-field"
                                                value="Bank Transfer" data-step="5">
                                            <img src="<?= APP_URL ?>assets/img/bank.png" alt="Bank Transfer" class="w-8 h-8 flex-shrink-0">
                                            <h3 class="font-semibold text-gray-800 leading-tight">Bank Transfer</h3>
                                        </label>
                                    <?php endif; ?>

                                    <?php if (!empty(getSettings("upi_id")) && plan("payment_upi")) : ?>
                                        <label for="paymentUPI"
                                            class="flex items-center gap-3 p-4 border border-gray-200 cursor-pointer hover:border-[<?= htmlspecialchars($primary_color) ?>] transition-colors duration-200">
                                            <input type="radio" name="payment_method" id="paymentUPI"
                                                class="accent-[<?= htmlspecialchars($primary_color) ?>] w-5 h-5 paymentMethod step-field"
                                                value="UPI" data-step="5">
                                            <h3 class="font-semibold text-gray-800 leading-tight">UPI (Scan QR / Pay to UPI ID)</h3>
                                        </label>
                                    <?php endif; ?>

                                    <?php if (!empty(getSettings("payu_merchant_key")) && !empty(getSettings("payu_merchant_salt"))) : ?>
                                        <label for="paymentPayU"
                                            class="flex items-center gap-3 p-4 border border-gray-200 cursor-pointer hover:border-[<?= htmlspecialchars($primary_color) ?>] transition-colors duration-200">
                                            <input type="radio" name="payment_method" id="paymentPayU"
                                                class="accent-[<?= htmlspecialchars($primary_color) ?>] w-5 h-5 paymentMethod step-field"
                                                value="PayU" data-step="5">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 60" class="w-16 h-8">
                                                <rect width="200" height="60" rx="8" fill="#4B0082" />
                                                <text x="100" y="38" font-family="Arial, sans-serif" font-size="28" font-weight="bold" fill="white" text-anchor="middle">PayU</text>
                                            </svg>
                                            <h3 class="font-semibold text-gray-800 leading-tight">PayU</h3>
                                        </label>
                                    <?php endif; ?>
                                </div>

                                <!-- EXTRA PAYMENT DETAILS -->
                                <div id="extraPaymentDetails" class="mt-4 space-y-4">

                                    <?php if (!empty(getSettings("bank_details")) && plan("payment_bank")) : ?>
                                        <div id="bankDetails" class="p-5 border border-gray-200 bg-gray-50" style="display:none;">
                                            <?= nl2br(getSettings("bank_details")) ?>
                                            <div class="mt-5 pt-4 border-t border-gray-200 text-sm text-gray-600">
                                                <i>Please email <?= getSettings("email") ?> with your bank slip once payment is made.</i>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty(getSettings("upi_id")) && plan("payment_upi")) : ?>
                                        <div id="upiContainer" class="p-5 sm:p-6 border border-gray-200 bg-gray-50" style="display:none;">

                                            <?php
                                            $tempSubTotal = 0;
                                            $cartData = getCartData();
                                            foreach ($cartData as $key => $cart) {
                                                $tempSubTotal += ((int)$cart['price'] * $cart['quantity']);
                                            }
                                            $minAmount = !empty(getSettings("minimum_order_amount")) ? (int)getSettings("minimum_order_amount") : 0;
                                            $isBelowMin = ($tempSubTotal < $minAmount && $minAmount > 0);
                                            ?>

                                            <div class="<?= $isBelowMin ? 'upi-blur-content' : '' ?>">

                                                <!-- Timer -->
                                                <div id="qrTimerContainer" style="text-align: center; margin-bottom: 15px; display: none;">
                                                    <div class="inline-flex items-center gap-3 px-5 py-2 bg-white border border-gray-300">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <span id="qrTimer" class="text-xl font-bold text-red-600 tracking-wider">04:00</span>
                                                    </div>
                                                </div>

                                                <h4 class="font-serifDisplay text-lg text-gray-900 mb-3">Pay via UPI</h4>

                                                <div class="mb-5">
                                                    <p class="text-sm text-gray-600 mb-2">Copy UPI ID and pay using any app</p>
                                                    <div class="flex items-center gap-2 p-3 bg-white border border-gray-200">
                                                        <span class="text-base font-semibold flex-1 truncate"
                                                            style="color: <?= htmlspecialchars($primary_color) ?>;"><?= getSettings("upi_id") ?></span>
                                                        <button type="button"
                                                            onclick="copyUPIID(this, '<?= getSettings('upi_id') ?>')"
                                                            class="px-3 py-2 text-xs uppercase tracking-widest text-white font-semibold theme13-checkout-btn">
                                                            Copy
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="p-4 bg-white border border-gray-200">
                                                    <h4 class="font-serifDisplay text-base text-gray-900 mb-4">Scan QR Code</h4>
                                                    <div class="flex flex-col lg:flex-row items-center gap-6">
                                                        <div class="bg-white p-4 border border-gray-200">
                                                            <div id="upiQR" style="text-align:center; min-height: 200px;"></div>
                                                        </div>
                                                        <div class="flex-1 space-y-3">
                                                            <div class="p-3 border-l-2 bg-gray-50"
                                                                style="border-color: <?= htmlspecialchars($primary_color) ?>;">
                                                                <h5 class="font-semibold text-gray-800 mb-1 text-sm">How to Pay</h5>
                                                                <ul class="text-xs text-gray-600 space-y-1">
                                                                    <li>• Open any UPI app</li>
                                                                    <li>• Tap "Scan QR"</li>
                                                                    <li>• Point camera at the code</li>
                                                                    <li>• Enter amount and pay</li>
                                                                </ul>
                                                            </div>
                                                            <div class="p-3 bg-gray-50 border border-gray-200">
                                                                <h5 class="font-semibold text-gray-800 mb-1 text-sm">Supported Apps</h5>
                                                                <div class="flex flex-wrap gap-1.5 mt-2">
                                                                    <span class="px-2 py-0.5 bg-white text-gray-600 text-[10px] uppercase tracking-widest border border-gray-200">GPay</span>
                                                                    <span class="px-2 py-0.5 bg-white text-gray-600 text-[10px] uppercase tracking-widest border border-gray-200">PhonePe</span>
                                                                    <span class="px-2 py-0.5 bg-white text-gray-600 text-[10px] uppercase tracking-widest border border-gray-200">Paytm</span>
                                                                    <span class="px-2 py-0.5 bg-white text-gray-600 text-[10px] uppercase tracking-widest border border-gray-200">BHIM</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mt-4 p-4 border-l-2 bg-gray-50"
                                                    style="border-color: <?= htmlspecialchars($primary_color) ?>;">
                                                    <p class="text-xs text-gray-600">
                                                        Note: If you pay manually via UPI, it may take 3–5 hours to verify your payment and process your order.
                                                    </p>
                                                </div>

                                            </div>

                                            <?php if ($isBelowMin): ?>
                                                <div class="upi-blur-overlay">
                                                    <div>
                                                        <div class="lock-icon"><i class="fas fa-lock"></i></div>
                                                        <div class="blur-title">Add <?= currencyToSymbol($storeCurrency) . number_format($minAmount - $tempSubTotal, 0) ?> more to pay via UPI</div>
                                                        <div class="blur-sub">Minimum order: <?= currencyToSymbol($storeCurrency) . number_format($minAmount, 0) ?></div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>

                        <!-- Step 6: Notes -->
                        <div class="flex gap-4 step-container" id="step-6">
                            <div class="step-number">06</div>
                            <div class="step-content p-4 sm:p-6 bg-white border border-gray-200">
                                <label for="notes" class="block text-xs tracking-widest uppercase text-gray-600 mb-3">Additional Notes</label>
                                <textarea name="notes" id="notes" rows="4"
                                    class="w-full p-4 text-sm border border-gray-300 bg-white
                                 focus:outline-none focus:border-[<?= htmlspecialchars($primary_color) ?>]
                                 transition-colors duration-200 step-field"
                                    placeholder="Any special instructions for your order..."
                                    data-step="6"></textarea>
                            </div>
                        </div>

                        <!-- Step 7: Customized Gift -->
                        <?php if (getSettings("customized_gift")) : ?>
                            <div class="flex gap-4 step-container" id="step-7">
                                <div class="step-number">07</div>
                                <div class="step-content p-4 sm:p-6 bg-white border border-gray-200">
                                    <h4 class="font-serifDisplay text-lg text-gray-900 mb-4">Customized Gift <sup class="text-xs text-gray-500">(optional)</sup></h4>
                                    <div class="grid gap-4">
                                        <div>
                                            <label for="customized_gift_images" class="block text-xs tracking-widest uppercase text-gray-600 mb-2">Images</label>
                                            <input type="file" name="customized_gift_images[]" id="customized_gift_images"
                                                class="w-full p-3 text-sm border border-gray-300 bg-white step-field"
                                                accept="image/*" max="3" multiple data-step="7">
                                            <small class="text-gray-500 block mt-2 text-xs">Maximum 3 images.</small>
                                        </div>
                                        <div>
                                            <label for="customized_gift_notes" class="block text-xs tracking-widest uppercase text-gray-600 mb-2">Details</label>
                                            <textarea name="customized_gift_notes" id="customized_gift_notes" rows="4"
                                                class="w-full p-4 text-sm border border-gray-300 bg-white
                                             focus:outline-none focus:border-[<?= htmlspecialchars($primary_color) ?>]
                                             transition-colors duration-200 step-field"
                                                placeholder="Enter gift customization details..."
                                                data-step="7"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>
                    </div>

                    <!-- ================= Right Column - Order Summary ================= -->
                    <div class="lg:w-2/5 flex flex-col gap-6">

                        <!-- Promo Code -->
                        <div class="bg-white border border-gray-200 p-4 sm:p-6">
                            <label class="block text-xs tracking-widest uppercase text-gray-600 mb-3">Have a promo code?</label>
                            <div class="flex items-center gap-2 flex-nowrap mb-3">
                                <input type="text" id="couponCode" placeholder="Enter code"
                                    class="flex-1 min-w-0 px-4 py-3 border border-gray-300 text-sm
                              focus:outline-none focus:border-[<?= htmlspecialchars($primary_color) ?>]
                              transition-colors duration-200">
                                <button id="applyCoupon" type="button"
                                    class="px-6 py-3 text-xs uppercase tracking-widest font-semibold text-white theme13-checkout-btn">
                                    Apply
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mb-3">Click a coupon below to apply</p>
                            <div class="flex flex-wrap gap-2 couponSuggestions">
                                <?php $discounts = getDiscounts();
                                foreach ($discounts as $discount): ?>
                                    <span class="flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 border cursor-pointer copyCoupon transition-colors duration-200"
                                        style="border-color: <?= htmlspecialchars($primary_color) ?>; color: <?= htmlspecialchars($primary_color) ?>;"
                                        data-code="<?= htmlspecialchars($discount['code']) ?>">
                                        <?= htmlspecialchars($discount['code']) ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- GST Number -->
                        <div class="p-4 sm:p-6 bg-white border border-gray-200">
                            <label for="gst_number" class="block text-xs tracking-widest uppercase text-gray-600 mb-3">Display my GSTIN number on invoice</label>
                            <input type="text" id="gst_number" name="gst_number"
                                class="w-full px-4 py-3 border border-gray-300 text-sm
                          focus:outline-none focus:border-[<?= htmlspecialchars($primary_color) ?>]
                          transition-colors duration-200">
                        </div>

                        <!-- Order Summary -->
                        <div class="p-4 sm:p-6 bg-white border border-gray-200">
                            <h3 class="pb-4 mb-6 font-serifDisplay text-xl sm:text-2xl text-gray-900 border-b border-gray-200">Order Summary</h3>

                            <!-- Products List -->
                            <div id="productScroll" class="overflow-x-auto hide-scrollbar flex gap-4 whitespace-nowrap mb-4">
                                <ul role="list" class="flex gap-4">
                                    <?php
                                    $subTotal = 0;
                                    $subTotalWithTax = 0;
                                    $gstAmount = 0;
                                    $data = getCartData();
                                    foreach ($data as $key => $cart):
                                        $subTotal += ((int)$cart['price'] * $cart['quantity']);
                                        if (getData("gst_percentage", "seller_products", "id = '{$cart['product_id']}'")) {
                                            $subTotalWithTax += calculateGst((int)$cart['price'] * $cart['quantity'], getData("gst_percentage", "seller_products", "id = '{$cart['product_id']}'"))['getPrice'];
                                            $gstAmount += calculateGst((int)$cart['price'] * $cart['quantity'], getData("gst_percentage", "seller_products", "id = '{$cart['product_id']}'"))['gstAmount'];
                                        } else {
                                            $subTotalWithTax += calculateGst((int)$cart['price'] * $cart['quantity'])['getPrice'];
                                            $gstAmount += calculateGst((int)$cart['price'] * $cart['quantity'])['gstAmount'];
                                        }
                                        $variation = $cart['other'] ? getData("variation", "seller_product_variants", "id = '{$cart['other']}'") : getData("variation", "seller_products", "id = '{$cart['product_id']}'");
                                        $size = getData("size", "seller_product_advanced_variants", "id = '{$cart['advanced_variant']}'");
                                        $color = getData("color", "seller_product_advanced_variants", "id = '{$cart['advanced_variant']}'");
                                        $color = $color ? getData("color_name", "product_colors", "id = '$color'") : '';
                                        if (!$variation && $size) {
                                            $variation = "Size: $size" . ($color ? " | Color: $color" : "");
                                        }
                                        $image = getData("image", "seller_products", "id = '{$cart['product_id']}'");
                                        if ($cart['other'] && getData("image", "seller_product_variants", "id = '{$cart['other']}'")) {
                                            $image = getData("image", "seller_product_variants", "id = '{$cart['other']}'");
                                        }
                                        if ($cart['advanced_variant'] && getData("image", "seller_product_advanced_variants", "id = '{$cart['advanced_variant']}'")) {
                                            $image = getData("image", "seller_product_advanced_variants", "id = '{$cart['advanced_variant']}'");
                                        }
                                    ?>
                                        <li class="flex-none w-40 bg-gray-50 p-3 border border-gray-200">

                                            <?php
                                            $isCombo = !empty($cart['combo_variants']);
                                            $comboVariants = [];
                                            if ($isCombo) {
                                                $decodedCombo = json_decode($cart['combo_variants'], true);
                                                if (is_array($decodedCombo)) {
                                                    $comboVariants = $decodedCombo;
                                                }
                                            }
                                            ?>

                                            <div class="w-full h-40 overflow-hidden mb-2">
                                                <img src="<?= !empty($image) ? UPLOADS_URL . $image : UPLOADS_URL . 'default-product.png' ?>"
                                                    alt="Product Image"
                                                    class="w-full h-full object-cover">
                                            </div>

                                            <h4 class="text-sm font-medium text-gray-900 truncate">
                                                <?= limit_characters(getData("name", "seller_products", "id = '{$cart['product_id']}'"), 30) ?>
                                            </h4>

                                            <?php if ($isCombo && !empty($comboVariants)): ?>
                                                <div class="mt-1 space-y-0.5">
                                                    <?php foreach ($comboVariants as $comboItem): ?>
                                                        <?php
                                                        $variantId = $comboItem['variantId'] ?? '';
                                                        $comboLabel = $comboItem['label'] ?? '';
                                                        if (empty($comboLabel)) {
                                                            $comboColor = $comboItem['colorName'] ?? '';
                                                            $comboSize  = $comboItem['size'] ?? '';
                                                            if ($comboColor && $comboSize) {
                                                                $comboLabel = $comboColor . ' - Size: ' . $comboSize;
                                                            } elseif ($comboColor) {
                                                                $comboLabel = $comboColor;
                                                            } elseif ($comboSize) {
                                                                $comboLabel = 'Size: ' . $comboSize;
                                                            } elseif ($variantId) {
                                                                $basicVariation = getData("variation", "seller_product_variants", "id = '$variantId'");
                                                                if ($basicVariation) $comboLabel = $basicVariation;
                                                            }
                                                        }
                                                        ?>
                                                        <?php if (!empty($comboLabel)): ?>
                                                            <p class="text-xs text-gray-500 leading-5 truncate" title="<?= htmlspecialchars($comboLabel) ?>">
                                                                <?= htmlspecialchars($comboLabel) ?>
                                                            </p>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php else: ?>
                                                <p class="text-xs text-gray-500 truncate"><?= htmlspecialchars($variation ?? '') ?></p>
                                            <?php endif; ?>

                                            <div class="flex justify-between mt-2 text-sm text-gray-600">
                                                <span><?= currencyToSymbol($storeCurrency) . number_format((int)$cart['price'] * $cart['quantity'], 2) ?></span>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                            <!-- Scroll Buttons -->
                            <div class="flex justify-end gap-3 mb-6">
                                <button id="scrollLeftBtn" type="button"
                                    class="w-10 h-10 flex items-center justify-center text-white border theme13-checkout-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <button id="scrollRightBtn" type="button"
                                    class="w-10 h-10 flex items-center justify-center text-white border theme13-checkout-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Totals -->
                            <div class="pt-4 border-t border-gray-200 space-y-3">
                                <div class="flex justify-between text-gray-600 text-sm">
                                    <span>Subtotal</span>
                                    <span id="subTotal" class="font-semibold"><?= currencyToSymbol($storeCurrency) . number_format($subTotal, 2) ?></span>
                                </div>
                                <div class="flex justify-between text-gray-600 text-sm">
                                    <span>Discount</span>
                                    <span id="discount" class="font-semibold">0.00</span>
                                </div>
                                <div class="flex justify-between text-gray-600 text-sm <?= getSettings("gst_tax_type") == "inclusive" || !getSettings("gst_number") ? 'hidden' : '' ?>">
                                    <span>GST Tax Amount</span>
                                    <span id="gstTax" class="font-semibold"><?= currencyToSymbol($gstAmount) ?></span>
                                </div>
                                <div class="flex justify-between text-gray-600 text-sm">
                                    <span>Shipping Charges</span>
                                    <span id="charges" class="font-semibold">0.00</span>
                                </div>
                            </div>

                            <div class="flex justify-between mt-4 pt-4 border-t border-gray-200 font-bold text-lg"
                                style="color: <?= htmlspecialchars($primary_color) ?>;">
                                <span>Total (<?= $storeCurrency ?>)</span>
                                <span id="total"><?= currencyToSymbol($subTotalWithTax) ?></span>
                            </div>

                            <!-- Place Order -->
                            <div class="mt-6">
                                <?php
                                $minimumOrder = getSettings("minimum_order_amount");
                                if (!empty($minimumOrder) && $subTotal < $minimumOrder): ?>
                                    <div class="py-3 px-4 text-center border-l-2 bg-gray-50"
                                        style="border-color: <?= htmlspecialchars($primary_color) ?>;">
                                        <p class="text-sm font-semibold" style="color: <?= htmlspecialchars($primary_color) ?>;">
                                            Minimum order is
                                            <span class="font-bold"><?= currencyToSymbol($storeCurrency) . number_format($minimumOrder, 2) ?></span>
                                        </p>
                                    </div>
                                <?php else: ?>
                                    <button id="payBtn" type="submit"
                                        class="theme13-checkout-btn w-full py-4 text-xs tracking-[2px] uppercase font-bold text-white transition-all duration-300">
                                        Place Order
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Theme 13 checkout button style -->
                <style>
                    .theme13-checkout-btn {
                        background: <?= htmlspecialchars($primary_color) ?>;
                        border: 1px solid <?= htmlspecialchars($primary_color) ?>;
                        color: #fff;
                    }

                    .theme13-checkout-btn:hover {
                        background: <?= htmlspecialchars($hover_color) ?>;
                        border-color: <?= htmlspecialchars($hover_color) ?>;
                        color: #fff;
                    }
                </style>

                <!-- Add Address Modal -->
                <div id="addAddressModal" class="fixed top-0 left-0 flex items-center justify-center hidden w-full h-full bg-black/70 backdrop-blur-sm transition-opacity duration-300 opacity-0 z-50">
                    <div class="p-6 bg-white/95 rounded-2xl max-w-[750px] w-full shadow-2xl border border-gray-100">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-6 border-b pb-3">
                            <h3 class="text-2xl font-semibold text-gray-800">Add Address</h3>
                            <button class="addAddressToggle w-[44px] h-[44px] bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl hover:shadow-md hover:scale-105 transition">
                                <i class="text-lg font-medium bx bx-x"></i>
                            </button>
                        </div>

                        <!-- Form -->
                        <form id="addAddressForm" class="max-h-[75vh] overflow-y-auto space-y-5 pb-[50px] lg:pb-0 px-1">

                            <!-- Name -->
                            <div>
                                <label for="name" class="block mb-2 font-medium text-gray-600">Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" placeholder="Enter your name"
                                    class="border border-gray-200 font-medium text-sm h-12 px-4 rounded-xl w-full shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition"
                                    value="<?= customer('name') ?>" required>
                            </div>

                            <!-- Phone + Email -->
                            <div class="grid gap-5 sm:grid-cols-2">
                                <div>
                                    <label for="phone" class="block mb-2 font-medium text-gray-600">Phone Number <span class="text-red-500">*</span></label>
                                    <input type="tell" name="phone[main]" id="phone" placeholder="XXXXX XXXXX"
                                        class="border border-gray-200 font-medium text-sm h-12 px-4 rounded-xl w-full shadow-sm focus:ring-2 focus:ring-primary-500 transition"
                                        value="<?= customer('phone') ?>" required>
                                </div>
                                <div>
                                    <label for="email" class="block mb-2 font-medium text-gray-600">Email Address</label>
                                    <input type="email" name="email" id="email" placeholder="Enter your email"
                                        class="border border-gray-200 font-medium text-sm h-12 px-4 rounded-xl w-full shadow-sm focus:ring-2 focus:ring-primary-500 transition"
                                        value="<?= customer('email') ?>">
                                </div>
                            </div>

                            <!-- Address -->
                            <div>
                                <label for="address" class="block mb-2 font-medium text-gray-600">Address <span class="text-red-500">*</span></label>
                                <input type="text" name="address" id="address" placeholder="Flat / House No, Building, Colony"
                                    class="border border-gray-200 font-medium text-sm h-12 px-4 rounded-xl w-full shadow-sm focus:ring-2 focus:ring-primary-500 transition"
                                    required>
                            </div>

                            <!-- Area + Landmark -->
                            <div class="grid gap-5 sm:grid-cols-2">
                                <div>
                                    <label for="area" class="block mb-2 font-medium text-gray-600">Locality / Area <small class="text-gray-400">(optional)</small></label>
                                    <input type="text" name="area" id="area" placeholder="E.g. MG Road, Gandhi Nagar"
                                        class="border border-gray-200 font-medium text-sm h-12 px-4 rounded-xl w-full shadow-sm focus:ring-2 focus:ring-primary-500 transition">
                                </div>
                                <div>
                                    <label for="landmark" class="block mb-2 font-medium text-gray-600">Landmark <small class="text-gray-400">(optional)</small></label>
                                    <input type="text" name="landmark" id="landmark" placeholder="E.g. Near Bank, Chowk, etc."
                                        class="border border-gray-200 font-medium text-sm h-12 px-4 rounded-xl w-full shadow-sm focus:ring-2 focus:ring-primary-500 transition">
                                </div>
                            </div>

                            <!-- City, Pincode, State -->
                            <div class="grid gap-5 sm:grid-cols-3">
                                <div>
                                    <label for="city" class="block mb-2 font-medium text-gray-600">City <span class="text-red-500">*</span></label>
                                    <input type="text" name="city" id="city" placeholder="Enter city"
                                        class="border border-gray-200 font-medium text-sm h-12 px-4 rounded-xl w-full shadow-sm focus:ring-2 focus:ring-primary-500 transition"
                                        required>
                                </div>

                                <div>
                                    <label for="pin_code" class="block mb-2 font-medium text-gray-600">Pin Code <span class="text-red-500">*</span></label>
                                    <input type="text" name="pin_code" id="pin_code" placeholder="Enter pin code"
                                        class="border border-gray-200 font-medium text-sm h-12 px-4 rounded-xl w-full shadow-sm focus:ring-2 focus:ring-primary-500 transition"
                                        required>
                                </div>

                                <div>
                                    <label for="state" class="block mb-2 font-medium text-gray-600">State <span class="text-red-500">*</span></label>
                                    <?php if ($storeCountry == "IN") { ?>
                                        <select name="state" id="state"
                                            class="border border-gray-200 font-medium text-sm h-12 px-4 rounded-xl w-full shadow-sm focus:ring-2 focus:ring-primary-500 transition"
                                            required>
                                            <option value="" disabled hidden selected>Select state</option>
                                            <option value="Andhra Pradesh">Andhra Pradesh</option>
                                            <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                            <option value="Assam">Assam</option>
                                            <option value="Bihar">Bihar</option>
                                            <option value="Goa">Goa</option>
                                            <option value="Gujarat">Gujarat</option>
                                            <option value="Haryana">Haryana</option>
                                            <option value="Himachal Pradesh">Himachal Pradesh</option>
                                            <option value="Jharkhand">Jharkhand</option>
                                            <option value="Karnataka">Karnataka</option>
                                            <option value="Kerala">Kerala</option>
                                            <option value="Madhya Pradesh">Madhya Pradesh</option>
                                            <option value="Maharashtra">Maharashtra</option>
                                            <option value="Manipur">Manipur</option>
                                            <option value="Meghalaya">Meghalaya</option>
                                            <option value="Mizoram">Mizoram</option>
                                            <option value="Nagaland">Nagaland</option>
                                            <option value="Odisha">Odisha</option>
                                            <option value="Punjab">Punjab</option>
                                            <option value="Rajasthan">Rajasthan</option>
                                            <option value="Sikkim">Sikkim</option>
                                            <option value="Tamil Nadu">Tamil Nadu</option>
                                            <option value="Telangana">Telangana</option>
                                            <option value="Tripura">Tripura</option>
                                            <option value="Uttar Pradesh">Uttar Pradesh</option>
                                            <option value="Uttarakhand">Uttarakhand</option>
                                            <option value="West Bengal">West Bengal</option>
                                        </select>
                                    <?php } else { ?>
                                        <input type="text" name="state" id="state" placeholder="Enter state"
                                            class="border border-gray-200 font-medium text-sm h-12 px-4 rounded-xl w-full shadow-sm focus:ring-2 focus:ring-primary-500 transition"
                                            required>
                                    <?php } ?>
                                </div>
                            </div>

                            <!-- Submit -->
                            <button
                                class="bg-gradient-to-r bg-primary-500 hover:from-primary-600 hover:to-primary-700 h-[50px] rounded-xl font-semibold text-base text-white flex items-center justify-center w-full transition-all shadow-md hover:shadow-lg">
                                Add Address
                            </button>
                        </form>
                    </div>
                </div>

<?php } else { ?>
    <!-- Empty cart state — Theme 13 -->
    <div class="w-full text-center bg-white border border-gray-200 p-8 sm:p-12">
        <img alt=""
             src="<?= APP_URL ?>assets/img/empty-bag.svg"
             width="200"
             height="120"
             class="mx-auto opacity-80">

        <h3 class="font-serifDisplay text-2xl sm:text-3xl text-gray-900 mt-6">
            Your bag is empty
        </h3>

        <p class="text-gray-500 text-sm sm:text-base mt-3 max-w-sm mx-auto leading-relaxed">
            Looks like you haven't made your choice yet.
        </p>

        <a href="<?= $storeUrl ?>"
           class="theme13-empty-btn mt-8 inline-flex items-center gap-2 px-8 py-3 text-xs tracking-[2px] uppercase font-semibold transition-all duration-300">
            <i class="fa-solid fa-shop text-sm"></i>
            <span>Go to Shop</span>
        </a>
    </div>
<?php } ?>

<!-- Theme 13 empty-state button style -->
<style>
    .theme13-empty-btn {
        background: <?= htmlspecialchars($primary_color) ?>;
        border: 1px solid <?= htmlspecialchars($primary_color) ?>;
        color: #fff;
    }
    .theme13-empty-btn:hover {
        background: <?= htmlspecialchars($hover_color) ?>;
        border-color: <?= htmlspecialchars($hover_color) ?>;
        color: #fff;
    }
</style>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <!-- QRCode.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        // Step activation logic
        document.querySelectorAll('.step-field').forEach(field => {
            field.addEventListener('focus', function() {
                const step = this.dataset.step;

                // Remove active class from all steps
                document.querySelectorAll('.step-container').forEach(container => {
                    container.classList.remove('active');
                });
                document.querySelectorAll('.step-number').forEach(number => {
                    number.classList.remove('active');
                });

                // Add active class to current step
                const currentStep = document.getElementById(`step-${step}`);
                const currentNumber = currentStep.querySelector('.step-number');

                if (currentStep && currentNumber) {
                    currentStep.classList.add('active');
                    currentNumber.classList.add('active');
                }

                // Mark all previous steps as completed ONLY if they have user interaction
                markPreviousStepsAsCompleted(step);
            });

            // Mark step as completed only when user actually interacts
            field.addEventListener('input', function() {
                const step = this.dataset.step;
                // Mark this specific step as completed since user interacted with it
                const currentStepNumber = document.querySelector(`#step-${step} .step-number`);
                if (currentStepNumber && this.value && this.value.trim() !== '') {
                    currentStepNumber.classList.add('completed');
                }
                markPreviousStepsAsCompleted(step);
            });

            field.addEventListener('change', function() {
                const step = this.dataset.step;
                // Mark this specific step as completed since user made a selection
                const currentStepNumber = document.querySelector(`#step-${step} .step-number`);
                if (currentStepNumber) {
                    currentStepNumber.classList.add('completed');
                }
                markPreviousStepsAsCompleted(step);
            });
        });

        // Special handler for radio buttons (Steps 2 and 5)
        document.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                // Find which step this radio belongs to
                const stepField = this.closest('.step-field');
                if (stepField) {
                    const step = stepField.dataset.step;
                    const stepNumber = document.querySelector(`#step-${step} .step-number`);
                    if (stepNumber) {
                        stepNumber.classList.add('completed');
                    }
                    markPreviousStepsAsCompleted(parseInt(step) + 1);
                } else {
                    // If no step-field class, check parent containers
                    const stepContainer = this.closest('[id^="step-"]');
                    if (stepContainer) {
                        const step = stepContainer.id.split('-')[1];
                        const stepNumber = document.querySelector(`#step-${step} .step-number`);
                        if (stepNumber) {
                            stepNumber.classList.add('completed');
                        }
                        markPreviousStepsAsCompleted(parseInt(step) + 1);
                    }
                }
            });
        });

        // Function to mark previous steps as completed (only those with user interaction)
        function markPreviousStepsAsCompleted(currentStep) {
            document.querySelectorAll('.step-number').forEach(number => {
                const stepContainer = number.closest('.step-container');
                const stepId = stepContainer.id;
                const stepNumber = stepId.split('-')[1];

                // Only mark as completed if it's a previous step AND has user interaction
                if (stepNumber < currentStep) {
                    // Check if this step has any filled fields
                    const hasInteraction = checkStepInteraction(stepNumber);
                    if (hasInteraction) {
                        number.classList.add('completed');
                    }
                    number.classList.remove('active');
                } else if (stepNumber == currentStep) {
                    number.classList.add('active');
                    number.classList.remove('completed');
                } else {
                    number.classList.remove('active', 'completed');
                }
            });
        }

        // Check if a step has any user interaction (filled fields)
        function checkStepInteraction(stepNumber) {
            const stepFields = document.querySelectorAll(`.step-field[data-step="${stepNumber}"]`);
            let hasInteraction = false;

            stepFields.forEach(field => {
                if (field.type === 'radio' || field.type === 'checkbox') {
                    if (field.checked) {
                        hasInteraction = true;
                    }
                } else if (field.value && field.value.trim() !== '') {
                    hasInteraction = true;
                }
            });

            // Also check for radio buttons without step-field class
            if (!hasInteraction) {
                const stepContainer = document.getElementById(`step-${stepNumber}`);
                const radios = stepContainer.querySelectorAll('input[type="radio"]:checked');
                if (radios.length > 0) {
                    hasInteraction = true;
                }
            }

            return hasInteraction;
        }

        // Form submission handler to mark all steps as completed
        document.getElementById('placeOrderForm')?.addEventListener('submit', function(e) {
            // Mark all steps as completed when form is submitted
            document.querySelectorAll('.step-number').forEach(number => {
                number.classList.add('completed');
                number.classList.remove('active');
            });
        });

        // Scroll functionality
        const scrollContainer = document.getElementById('productScroll');
        const scrollLeftBtn = document.getElementById('scrollLeftBtn');
        const scrollRightBtn = document.getElementById('scrollRightBtn');

        if (scrollLeftBtn && scrollRightBtn) {
            scrollLeftBtn.addEventListener('click', () => {
                scrollContainer.scrollBy({
                    left: -200,
                    behavior: 'smooth'
                });
            });
            scrollRightBtn.addEventListener('click', () => {
                scrollContainer.scrollBy({
                    left: 200,
                    behavior: 'smooth'
                });
            });
        }

        // Coupon copy functionality
        document.querySelectorAll('.copyCoupon').forEach(el => {
            el.addEventListener('click', function() {
                const code = this.dataset.code;
                const input = document.getElementById('couponCode');
                const applyBtn = document.getElementById('applyCoupon');

                input.value = code;
                applyBtn.classList.add('shadow-[0_0_12px_rgba(236,72,153,0.8)]');
                setTimeout(() => {
                    applyBtn.classList.remove('shadow-[0_0_12px_rgba(236,72,153,0.8)]');
                }, 1000);
            });
        });

        // Initialize first step as active ONLY - no green on load
        document.addEventListener('DOMContentLoaded', function() {
            const firstStep = document.getElementById('step-1');
            const firstNumber = firstStep.querySelector('.step-number');
            firstStep.classList.add('active');
            firstNumber.classList.add('active');

            // Remove any completed classes that might be there by default
            document.querySelectorAll('.step-number').forEach(number => {
                number.classList.remove('completed');
            });

            // Check if any radio buttons are pre-selected and mark steps as completed
            checkPreSelectedRadios();
        });

        // Function to check for pre-selected radio buttons
        function checkPreSelectedRadios() {
            // Check Step 2 (Delivery Option) - "delivery" is pre-checked
            const deliveryChecked = document.querySelector('input[name="delivery_option"]:checked');
            if (deliveryChecked) {
                const step2Number = document.querySelector('#step-2 .step-number');
                if (step2Number) {
                    step2Number.classList.add('completed');
                }
            }

            // You can add similar checks for other pre-selected radios if needed
        }
        // Auto-select billing address when delivery address is selected
        $(document).on('change', '.shipping_address', function() {
            const selectedAddressId = $(this).val();

            if (selectedAddressId) {
                // Find the corresponding billing address radio button and check it
                $(`.billing_address[value="${selectedAddressId}"]`).prop('checked', true);

                // Trigger change event to update UI
                $(`.billing_address[value="${selectedAddressId}"]`).trigger('change');

                // Show success message with primary color
                toastr.success('Billing address set to match delivery address', '', {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    timeOut: 3000,
                    showMethod: "fadeIn",
                    hideMethod: "fadeOut"
                });
            }
        });

        // When user manually changes billing address, show info message
        $(document).on('change', '.billing_address', function() {
            const shippingAddress = $('.shipping_address:checked').val();
            const billingAddress = $(this).val();

            if (shippingAddress && billingAddress !== shippingAddress) {
                // Show info message with hover color styling
                toastr.info('You have selected a different billing address', '', {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    timeOut: 3000,
                    showMethod: "fadeIn",
                    hideMethod: "fadeOut"
                });
            }
        });
    </script>
    <script>
        const phoneInputField = document.querySelector("#phone");
        const phoneInput = window.intlTelInput(phoneInputField, {
            separateDialCode: true,
            onlyCountries: ['IN'],
            preferredCountries: ["<?= getSettings("country") ?>"],
            hiddenInput: "full",
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        });

        // ========== QR TIMER FUNCTIONALITY ==========
        let qrTimerInterval = null;
        let qrTimeSeconds = 240; // 4 minutes

        function startQRTimer() {
            // Clear any existing timer
            if (qrTimerInterval) {
                clearInterval(qrTimerInterval);
                qrTimerInterval = null;
            }

            // Reset time to 4 minutes
            qrTimeSeconds = 240;

            // Show timer container
            $("#qrTimerContainer").fadeIn(300);

            // Update display immediately
            updateTimerDisplay();

            // Start interval
            qrTimerInterval = setInterval(function() {
                qrTimeSeconds--;

                if (qrTimeSeconds <= 60) {
                    // Add warning class when less than 1 minute
                    $("#qrTimer").addClass('timer-warning');
                }

                if (qrTimeSeconds <= 0) {
                    // Timer reaches 0, reset to 4 minutes (dummy loop)
                    qrTimeSeconds = 240;
                    $("#qrTimer").removeClass('timer-warning');
                }

                updateTimerDisplay();
            }, 1000);
        }

        function updateTimerDisplay() {
            const minutes = Math.floor(qrTimeSeconds / 60);
            const seconds = qrTimeSeconds % 60;
            const display = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
            $("#qrTimer").text(display);
        }

        function stopQRTimer() {
            if (qrTimerInterval) {
                clearInterval(qrTimerInterval);
                qrTimerInterval = null;
            }
            $("#qrTimerContainer").fadeOut(300);
            $("#qrTimer").removeClass('timer-warning');
        }


        // ========== CHECK FORM VALIDITY FOR QR ==========
        function checkFormValidityForQR() {
            // Check if UPI is visible and QR is generated
            if (!$("#upiContainer").is(':visible')) {
                stopQRTimer();
                return;
            }

            // Check if all required fields are filled
            let allFilled = true;

            // Check delivery area
            const deliveryArea = $("#deliveryArea").val();
            if (!deliveryArea) {
                allFilled = false;
            }

            // Check delivery option
            const deliveryOption = $("input[name='delivery_option']:checked").val();
            if (!deliveryOption) {
                allFilled = false;
            }

            // Check shipping address
            const shippingAddress = $("input[name='shipping_address']:checked").val();
            if (!shippingAddress) {
                allFilled = false;
            }

            // Check billing address
            const billingAddress = $("input[name='billing_address']:checked").val();
            if (!billingAddress) {
                allFilled = false;
            }

            // Check payment method
            const paymentMethod = $(".paymentMethod:checked").val();
            if (!paymentMethod) {
                allFilled = false;
            }

            // Start timer if all fields are filled
            if (allFilled && deliveryOption) {
                if (!qrTimerInterval) {
                    setTimeout(function() {
                        startQRTimer();
                    }, 500);
                }
            } else {
                stopQRTimer();
            }
        }
        // ========== EVENT LISTENERS ==========


        // Handle payment method selection
        $(document).on("change", ".paymentMethod", function() {
            if ($(this).val() == 'Bank Transfer') {
                $("#bankDetails").fadeIn();
            } else {
                $("#bankDetails").hide();
            }

            if ($(this).val() == 'UPI') {
                $("#upiContainer").fadeIn();
                setTimeout(function() {
                    generateUPIQR();
                    // Check form validity after UPI shows
                    setTimeout(checkFormValidityForQR, 600);
                }, 500);
            } else {
                $("#upiContainer").hide();
                stopQRTimer();
            }
        });
        // Check form validity on any form field change
        $(document).on('change keyup input', 'input, select, textarea', function() {
            checkFormValidityForQR();
        });

        // Also check when UPI becomes visible
        $(document).on('DOMSubtreeModified', '#upiContainer', function() {
            if ($("#upiContainer").is(':visible')) {
                setTimeout(checkFormValidityForQR, 500);
            }
        });


        // Re-generate QR when total amount changes
        $(document).on('DOMSubtreeModified', '#total', function() {
            if ($("#upiContainer").is(':visible')) {
                setTimeout(generateUPIQR, 200);
            }
        });

        // Also regenerate when discount or charges change
        $(document).on('DOMSubtreeModified', '#discount, #charges', function() {
            if ($("#upiContainer").is(':visible')) {
                setTimeout(generateUPIQR, 200);
            }
        });

        // ========== ADD TIMER WARNING CSS ==========
        $('<style>')
            .prop('type', 'text/css')
            .html(`
        .timer-warning {
            animation: pulse-red 1s infinite;
            color: #dc2626 !important;
        }
        @keyframes pulse-red {
            0%, 100% { color: #dc2626; transform: scale(1); }
            50% { color: #ef4444; transform: scale(1.05); }
        }
    `)
            .appendTo('head');

        // UPI QR Code Generation using QRCode.js
        function generateUPIQR() {
            // Check if UPI ID exists
            let upi_id = "<?= getSettings('upi_id') ?>";
            if (!upi_id || upi_id.trim() === '') {
                console.log("UPI ID is empty - cannot generate QR");
                $("#upiQR").html('<p class="text-red-500 text-center">UPI ID not configured. Please contact store admin.</p>');
                return;
            }

            let amount = $("#total").text().replace(/,/g, '').replace(/[^\d.]/g, '');
            let name = "<?= getData("business_name", "seller_settings", "seller_id='$sellerId' AND store_id='$storeId'") ?>";

            // ========== Get Order ID and Payment ID from hidden inputs ==========
            let orderId = $("#hiddenOrderId").val();
            let paymentId = $("#hiddenPaymentId").val();

            // If orderId is empty, generate fallback
            if (!orderId || orderId === '') {
                let storeId = "<?= $storeId ?>";
                let randomNum = Math.floor(1000000 + Math.random() * 9000000);
                orderId = storeId + "-" + randomNum;
                $("#hiddenOrderId").val(orderId);
                console.warn("Order ID not in hidden input, generated fallback:", orderId);
            }

            // If paymentId is empty, generate fallback
            if (!paymentId || paymentId === '') {
                let storeId = "<?= $storeId ?>";
                let randomNum = Math.floor(1000000 + Math.random() * 9000000);
                paymentId = storeId + "-" + randomNum;
                $("#hiddenPaymentId").val(paymentId);
                console.warn("Payment ID not in hidden input, generated fallback:", paymentId);
            }

            // Display IDs
            $("#orderIdDisplay").text(orderId);
            $("#paymentIdDisplay").text(paymentId);
            // ========== END ==========

            if (!amount || amount == 0) {
                console.log("Invalid amount");
                return;
            }

            // ========== Add Payment ID to UPI link (tn - transaction note) ==========
            let upiLink =
                "upi://pay?pa=" + upi_id +
                "&pn=" + encodeURIComponent(name) +
                "&am=" + amount +
                "&cu=INR" +
                "&tn=" + encodeURIComponent(paymentId);
            // ========== END ==========

            console.log("UPI LINK with Payment ID:", upiLink);

            // clear old QR
            $("#upiQR").html("");

            // generate QR using QRCode.js library
            new QRCode(document.getElementById("upiQR"), {
                text: upiLink,
                width: 250,
                height: 250
            });
        }

        // Handle payment method selection
        $(document).on("change", ".paymentMethod", function() {
            if ($(this).val() == 'Bank Transfer') {
                $("#bankDetails").fadeIn();
            } else {
                $("#bankDetails").hide();
            }

            if ($(this).val() == 'UPI') {
                $("#upiContainer").fadeIn();
                setTimeout(generateUPIQR, 300);
            } else {
                $("#upiContainer").hide();
            }
        });

        // Auto-generate UPI QR on page load if UPI ID exists
        function autoGenerateUPIQRIfAvailable() {
            let upi_id = "<?= getSettings('upi_id') ?>";

            if (upi_id && upi_id.trim() !== '') {
                // console.log("UPI ID found: " + upi_id + " - QR will generate when UPI is selected");
            } else {
                //  console.log("No UPI ID found in settings");
                $("#upiContainer").hide();
            }
        }

        // Call on page load
        $(document).ready(function() {
            autoGenerateUPIQRIfAvailable();

            // Check if UPI is already selected on page load
            if ($(".paymentMethod:checked").val() == 'UPI') {
                $("#upiContainer").show();
                setTimeout(generateUPIQR, 500);
            }
        });

        // Re-generate QR when total amount changes
        $(document).on('DOMSubtreeModified', '#total', function() {
            if ($("#upiContainer").is(':visible')) {
                generateUPIQR();
            }
        });

        // Also regenerate when discount or charges change
        $(document).on('DOMSubtreeModified', '#discount, #charges', function() {
            if ($("#upiContainer").is(':visible')) {
                setTimeout(generateUPIQR, 200);
            }
        });
    </script>


    <!--Footer File Includes that file has all JS Files includes links-->
    <?php include_once __DIR__ . "/includes/footer.php"; ?>

</body>

</html>