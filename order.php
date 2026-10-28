<?php
include_once __DIR__ . "/includes/files_includes.php";

if (isset($_GET['id']) && getData("id", "seller_orders", "order_id = '{$_GET['id']}' AND (seller_id = '$sellerId' AND store_id = '$storeId') AND customer_id = '$customerId'")) {
    $order_id = $_GET['id'];

    $ordered_date = date('F d, Y', strtotime(getData("created_at", "seller_orders", "order_id = '$order_id'")));
    $status = ucwords(getData("status", "seller_orders", "order_id = '$order_id'"));
    $payment_status = ucwords(getData("payment_status", "seller_orders", "order_id = '$order_id'"));
    $courier_service = getData("courier_service", "seller_orders", "order_id = '$order_id'");
    $tracking_id = getData("tracking_id", "seller_orders", "order_id = '$order_id'");
    $expected_delivery_date = getData("expected_delivery_date", "seller_orders", "order_id = '$order_id'");
    $tax = getData("tax", "seller_orders", "order_id = '$order_id'");
    $customized_gift = getData("customized_gift", "seller_orders", "order_id = '$order_id'");
    $delivery_charges = getData("delivery_charges", "seller_orders", "order_id = '$order_id'");
    $total = getData("total", "seller_orders", "order_id = '$order_id'");
    $discount = getData("discount", "seller_orders", "order_id = '$order_id'");
    $discount_type = getData("discount_type", "seller_orders", "order_id = '$order_id'");
    $currency = getData("currency", "seller_orders", "order_id = '$order_id'");
    $payment_method = getData("payment_method", "seller_orders", "order_id = '$order_id'");

    $gst_number = getData("gst_number", "seller_orders", "order_id = '$order_id'");
    $gst_tax_type = getData("gst_tax_type", "seller_orders", "order_id = '$order_id'");
    $gst_percentage = getData("gst_percentage", "seller_orders", "order_id = '$order_id'");

    $gstTaxAmount = '';
    $totalGst = '';
    if ($tax != 0 && !empty($gst_percentage)) {
        $totalGst = $gst_tax_type == "inclusive" ? "(Inclusive GST -$gst_percentage%)" : "(Exclusive GST +$gst_percentage%)";

        if ($gst_tax_type != "inclusive") {
            $gstTaxAmount = '<li class="grid grid-cols-11 max-w-[350px] text-sm mt-1">
                        <span class="col-span-4 text-gray-600 font-medium">GST (' . $gst_percentage . '%)</span>
                        <span class="col-span-1 text-gray-400">:</span>
                        <span class="col-span-6 text-gray-900">' . currencyToSymbol($currency) . number_format($tax, 2) . '</span>
                    </li>';
        }
    }

    $subTotal = 0;
    foreach (getOrderedProducts(array("orderId" => $order_id)) as $key => $product) {
        $subTotal += $product['price'] * $product['quantity'];
    }
} else {
    redirect($storeUrl);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once __DIR__ . "/includes/head_links.php"; ?>
    <style>
        :root {
            --primary: <?= htmlspecialchars(getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ed143b') ?>;
            --hover-color: <?= htmlspecialchars(getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ec4899') ?>;
        }

        .theme16-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            transition: border-color .25s ease;
        }

        .theme16-card:hover {
            border-color: color-mix(in srgb, var(--primary) 30%, #e5e7eb);
        }

        .theme16-btn {
            background-color: var(--primary);
            color: white;
            transition: all .3s ease;
        }

        .theme16-btn:hover {
            background-color: var(--hover-color);
        }

        .theme16-table th {
            background: var(--primary);
            color: #fff;
            text-align: left;
            font-weight: 600;
            padding: 12px 16px;
            font-size: 11px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .theme16-table td {
            padding: 14px 16px;
            border-top: 1px solid #f3f4f6;
            vertical-align: middle;
            font-size: 13px;
            color: #4b5563;
        }

        .theme16-table tbody tr:hover {
            background: #f9fafb;
        }
    </style>
</head>

<body class="bg-white font-sans text-gray-800 min-h-screen antialiased flex flex-col">

    <?php include_once __DIR__ . "/includes/nav-bar.php"; ?>

    <main class="flex-grow mx-auto w-full max-w-[1360px] px-4 py-10 sm:px-6 sm:py-14 lg:px-8">

        <!-- Page Header -->
        <div class="mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">Order Details</p>
                <h1 class="text-2xl font-bold uppercase tracking-tight text-[#292d38] sm:text-[28px] mt-1">
                    Order #<?= htmlspecialchars($order_id) ?>
                </h1>
            </div>

            <a href="<?= $storeUrl ?>download-invoice?id=<?= $order_id ?>"
                class="theme16-btn inline-flex items-center gap-2 px-5 py-2.5 rounded text-xs font-bold uppercase tracking-wider">
                <i class='bx bxs-download text-base'></i>
                Download Invoice
            </a>
        </div>

        <!-- Order Status + Products -->
        <div class="flex flex-col lg:flex-row gap-6 mb-6">

            <!-- Order Status Sidebar -->
            <div class="theme16-card lg:w-[35%] p-6 flex flex-col">
                <h3 class="text-base font-bold uppercase tracking-wide text-[#292d38] mb-5 border-b border-gray-100 pb-3">Order Status</h3>

                <ul class="space-y-3">
                    <li class="grid grid-cols-11 text-sm w-full border border-gray-100 p-3 rounded bg-gray-50">
                        <span class="col-span-4 text-gray-500 font-medium">Ordered At</span>
                        <span class="col-span-1 text-gray-400">:</span>
                        <span class="col-span-6 text-gray-900 font-medium"><?= $ordered_date ?></span>
                    </li>

                    <li class="grid grid-cols-11 text-sm w-full border border-gray-100 p-3 rounded bg-gray-50">
                        <span class="col-span-4 text-gray-500 font-medium">Status</span>
                        <span class="col-span-1 text-gray-400">:</span>
                        <span class="col-span-6 font-bold" style="color: var(--hover-color);"><?= $status ?></span>
                    </li>

                    <li class="grid grid-cols-11 text-sm w-full border border-gray-100 p-3 rounded bg-gray-50">
                        <span class="col-span-4 text-gray-500 font-medium">Payment Status</span>
                        <span class="col-span-1 text-gray-400">:</span>
                        <span class="col-span-6 font-bold" style="color: var(--primary);"><?= $payment_status ?></span>
                    </li>

                    <?php if ($courier_service) : ?>
                        <li class="grid grid-cols-11 text-sm w-full border border-gray-100 p-3 rounded bg-gray-50">
                            <span class="col-span-4 text-gray-500 font-medium">Courier</span>
                            <span class="col-span-1 text-gray-400">:</span>
                            <span class="col-span-6 text-gray-900 font-medium"><?= $courier_service ?: 'update soon' ?></span>
                        </li>
                    <?php endif; ?>

                    <?php if ($expected_delivery_date) : ?>
                        <li class="grid grid-cols-11 text-sm w-full border border-gray-100 p-3 rounded bg-gray-50">
                            <span class="col-span-4 text-gray-500 font-medium">Expected Delivery</span>
                            <span class="col-span-1 text-gray-400">:</span>
                            <span class="col-span-6 text-gray-900 font-medium"><?= $expected_delivery_date ? date('M d, Y', strtotime($expected_delivery_date)) : 'update soon' ?></span>
                        </li>
                    <?php endif; ?>
                </ul>

                <?php if ($payment_status == "Unpaid") : ?>
                    <p class="block text-xs mt-5 p-3 border-l-2 border-red-400 bg-red-50 text-red-600 rounded-r">
                        <strong>Note:</strong> Once you paid manually it can take 3-5 hours to verify the payment.
                    </p>
                <?php endif; ?>
            </div>

            <!-- Ordered Products -->
            <div class="theme16-card lg:w-[65%] p-6">
                <h3 class="text-base font-bold uppercase tracking-wide text-[#292d38] mb-5 flex items-center gap-2 border-b border-gray-100 pb-3">
                    <i class='bx bx-package text-lg' style="color: var(--primary);"></i>
                    Ordered Products
                </h3>

                <div class="w-full overflow-x-auto">
                    <table class="theme16-table table-auto w-full">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>MRP Price</th>
                                <th>Qty</th>
                                <th>Sub Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $products = getOrderedProducts(array("orderId" => $order_id));
                            $subTotal = 0;

                            foreach ($products as $key => $product) :
                                $subTotal += $product['price'] * $product['quantity'];

                                $image = getData("image", "seller_products", "id = '{$product['product_id']}'");
                                if ($product['other'] && getData("image", "seller_product_variants", "id = '{$product['other']}'")) {
                                    $image = getData("image", "seller_product_variants", "id = '{$product['other']}'");
                                }

                                $other = "";
                                $advanced_variant = "";
                                $size = '';
                                $colorId = '';

                                if ($product['other']) {
                                    $other = " - " . getData("variation", "seller_product_variants", "id = '{$product['other']}'");
                                }
                                if ($product && $product['advanced_variant']) {
                                    $size = getData("size", "seller_product_advanced_variants", "id = '{$product['advanced_variant']}'");
                                    $colorId = getData("color", "seller_product_advanced_variants", "id = '{$product['advanced_variant']}'");
                                    $color = getData("color_name", "product_colors", "id = '$colorId'");

                                    $advanced_variant = "Size: $size | Color: $color";

                                    if (getData("image", "seller_product_advanced_variants", "id = '{$product['advanced_variant']}'")) {
                                        $image = getData("image", "seller_product_advanced_variants", "id = '{$product['advanced_variant']}'");
                                    }
                                }

                                $productUrl = $storeUrl . "product/" . getData("slug", "seller_products", "id = '{$product['product_id']}'");
                                if ($product['other']) {
                                    $productUrl .= "?variation=" . $product['other'];
                                }
                                if ($size && $colorId) {
                                    $productUrl .= "?size=" . $size . "&color=" . $colorId;
                                }
                            ?>
                                <tr>
                                    <td>
                                        <a href="<?= $productUrl ?>" class="flex items-center gap-4 group">
                                            <img class="object-contain w-14 h-14 border border-gray-100 p-1 rounded bg-white"
                                                src="<?= UPLOADS_URL . $image ?>"
                                                alt="<?= htmlspecialchars(getData("name", "seller_products", "id = '{$product['product_id']}'") . $other) ?>">

                                            <div>
                                                <span class="font-semibold block text-[#292d38] transition group-hover:text-[var(--primary)] text-sm leading-snug">
                                                    <?= htmlspecialchars(getData("name", "seller_products", "id = '{$product['product_id']}'") . $other) ?>
                                                </span>
                                                <span class="block text-[11px] text-gray-400 mt-0.5">
                                                    <?= htmlspecialchars($advanced_variant) ?>
                                                </span>
                                            </div>
                                        </a>
                                    </td>
                                    <td class="font-medium text-gray-800"><?= currencyToSymbol($currency) . number_format($product['price'], 2) ?></td>
                                    <td class="text-gray-400 line-through"><?= currencyToSymbol($currency) . number_format($product['mrp_price'], 2) ?></td>
                                    <td><span class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded text-xs font-bold"><?= $product['quantity'] ?></span></td>
                                    <td class="font-bold" style="color: var(--primary);">
                                        <?= currencyToSymbol($currency) . number_format($product['price'] * $product['quantity'], 2) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Summary + Address + Courier cards grid -->
        <div class="grid gap-6 lg:grid-cols-3 md:grid-cols-2 mb-6">

            <!-- Bank Info (unpaid + bank transfer) -->
            <?php if ($payment_status == "Unpaid" && !empty(getSettings("bank_details")) && plan("payment_bank") && $payment_method === "Bank Transfer") : ?>
                <div class="theme16-card p-6">
                    <h3 class="text-base font-bold uppercase tracking-wide text-[#292d38] mb-4 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class='bx bx-credit-card text-lg' style="color: var(--primary);"></i>
                        Bank Information
                    </h3>
                    <div class="p-4 border border-gray-100 bg-gray-50 text-xs leading-6 rounded text-gray-700">
                        <?= nl2br(getSettings("bank_details")) ?>
                    </div>
                </div>
            <?php endif ?>

            <!-- UPI QR (unpaid + UPI) -->
            <?php if ($payment_status == "Unpaid" && !empty(getSettings("upi_qr_code")) && plan("payment_upi") && $payment_method === "UPI") : ?>
                <div class="theme16-card p-6">
                    <h3 class="text-base font-bold uppercase tracking-wide text-[#292d38] mb-4 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class='bx bx-qr text-lg' style="color: var(--primary);"></i>
                        UPI Payment
                    </h3>
                    <div class="p-4 border border-gray-100 bg-gray-50 rounded text-center">
                        <img src="<?= UPLOADS_URL . getSettings("upi_qr_code") ?>" alt="UPI QR" class="max-h-[200px] mx-auto rounded border border-gray-200">
                    </div>
                </div>
            <?php endif ?>

            <!-- Order Summary -->
            <div class="theme16-card p-6">
                <h3 class="text-base font-bold uppercase tracking-wide text-[#292d38] mb-4 flex items-center gap-2 border-b border-gray-100 pb-3">
                    <i class='bx bx-wallet text-lg' style="color: var(--primary);"></i>
                    Order Summary
                </h3>

                <ul class="space-y-3">
                    <li class="grid grid-cols-11 text-sm">
                        <span class="col-span-4 text-gray-500 font-medium">Sub Total</span>
                        <span class="col-span-1 text-gray-400">:</span>
                        <span class="col-span-6 text-gray-900 font-medium"><?= currencyToSymbol($storeCurrency) . number_format($subTotal, 2) ?></span>
                    </li>

                    <li class="grid grid-cols-11 text-sm">
                        <span class="col-span-4 text-gray-500 font-medium">Delivery Charge</span>
                        <span class="col-span-1 text-gray-400">:</span>
                        <span class="col-span-6 text-gray-900 font-medium"><?= currencyToSymbol($storeCurrency) . $delivery_charges ?></span>
                    </li>

                    <?= $gstTaxAmount ?>

                    <?php if ($discount_type == "fixed") { ?>
                        <li class="grid grid-cols-11 text-sm">
                            <span class="col-span-4 text-gray-500 font-medium">Discount</span>
                            <span class="col-span-1 text-gray-400">:</span>
                            <span class="col-span-6 text-green-600 font-medium">- <?= currencyToSymbol($storeCurrency) . number_format($discount, 2) ?></span>
                        </li>
                    <?php } else { ?>
                        <li class="grid grid-cols-11 text-sm">
                            <span class="col-span-4 text-gray-500 font-medium">Discount</span>
                            <span class="col-span-1 text-gray-400">:</span>
                            <span class="col-span-6 text-green-600 font-medium">- <?= number_format($discount, 2) . "%" ?></span>
                        </li>
                    <?php } ?>

                    <li class="grid grid-cols-11 text-base border-t border-gray-200 pt-3 mt-3">
                        <span class="col-span-4 font-bold text-[#292d38]">Total</span>
                        <span class="col-span-1 text-gray-400">:</span>
                        <span class="col-span-6 font-bold" style="color: var(--primary);">
                            <?= currencyToSymbol($currency) . number_format($total, 2) ?>
                        </span>
                    </li>
                </ul>
            </div>

            <!-- Delivery Address -->
            <div class="theme16-card p-6">
                <h3 class="text-base font-bold uppercase tracking-wide text-[#292d38] mb-4 flex items-center gap-2 border-b border-gray-100 pb-3">
                    <i class='bx bx-map text-lg' style="color: var(--primary);"></i>
                    Delivery Address
                </h3>

                <ul class="space-y-3 text-sm">
                    <li class="grid grid-cols-11">
                        <span class="col-span-4 text-gray-500 font-medium">Name</span>
                        <span class="col-span-1 text-gray-400">:</span>
                        <span class="col-span-6 text-gray-900 font-medium"><?= getData("name", "seller_order_address", "type = 'shipping' AND order_id = '$order_id'") ?></span>
                    </li>
                    <li class="grid grid-cols-11">
                        <span class="col-span-4 text-gray-500 font-medium">Phone</span>
                        <span class="col-span-1 text-gray-400">:</span>
                        <span class="col-span-6 text-gray-900"><?= getData("phone", "seller_order_address", "type = 'shipping' AND order_id = '$order_id'") ?></span>
                    </li>
                    <li class="grid grid-cols-11">
                        <span class="col-span-4 text-gray-500 font-medium">Email</span>
                        <span class="col-span-1 text-gray-400">:</span>
                        <span class="col-span-6 text-gray-900 break-all"><?= getData("email", "seller_order_address", "type = 'shipping' AND order_id = '$order_id'") ?></span>
                    </li>

                    <li class="text-sm border-t border-gray-100 pt-3 mt-3">
                        <span class="block mb-1 text-xs uppercase tracking-wider text-gray-400 font-semibold">Address</span>
                        <span class="block text-gray-800 leading-relaxed text-[13px]">
                            <?= getData("address", "seller_order_address", "type = 'shipping' AND order_id = '$order_id'") ?><br>
                            <?= getData("city", "seller_order_address", "type = 'shipping' AND order_id = '$order_id'") ?>,
                            <?= getData("state", "seller_order_address", "type = 'shipping' AND order_id = '$order_id'") ?>
                            <?= getData("pin_code", "seller_order_address", "type = 'shipping' AND order_id = '$order_id'") ?>
                        </span>
                    </li>
                </ul>
            </div>

            <!-- Billing Address -->
            <div class="theme16-card p-6">
                <h3 class="text-base font-bold uppercase tracking-wide text-[#292d38] mb-4 flex items-center gap-2 border-b border-gray-100 pb-3">
                    <i class='bx bx-receipt text-lg' style="color: var(--primary);"></i>
                    Billing Address
                </h3>

                <ul class="space-y-3 text-sm">
                    <li class="grid grid-cols-11">
                        <span class="col-span-4 text-gray-500 font-medium">Name</span>
                        <span class="col-span-1 text-gray-400">:</span>
                        <span class="col-span-6 text-gray-900 font-medium"><?= getData("name", "seller_order_address", "type = 'billing' AND order_id = '$order_id'") ?></span>
                    </li>
                    <li class="grid grid-cols-11">
                        <span class="col-span-4 text-gray-500 font-medium">Phone</span>
                        <span class="col-span-1 text-gray-400">:</span>
                        <span class="col-span-6 text-gray-900"><?= getData("phone", "seller_order_address", "type = 'billing' AND order_id = '$order_id'") ?></span>
                    </li>
                    <li class="grid grid-cols-11">
                        <span class="col-span-4 text-gray-500 font-medium">Email</span>
                        <span class="col-span-1 text-gray-400">:</span>
                        <span class="col-span-6 text-gray-900 break-all"><?= getData("email", "seller_order_address", "type = 'billing' AND order_id = '$order_id'") ?></span>
                    </li>

                    <li class="text-sm border-t border-gray-100 pt-3 mt-3">
                        <span class="block mb-1 text-xs uppercase tracking-wider text-gray-400 font-semibold">Address</span>
                        <span class="block text-gray-800 leading-relaxed text-[13px]">
                            <?= getData("address", "seller_order_address", "type = 'billing' AND order_id = '$order_id'") ?><br>
                            <?= getData("city", "seller_order_address", "type = 'billing' AND order_id = '$order_id'") ?>,
                            <?= getData("state", "seller_order_address", "type = 'billing' AND order_id = '$order_id'") ?>
                            <?= getData("pin_code", "seller_order_address", "type = 'billing' AND order_id = '$order_id'") ?>
                        </span>
                    </li>
                </ul>
            </div>

            <!-- Courier Information -->
            <?php if (!empty($courier_service)) : ?>
                <div class="theme16-card p-6">
                    <h3 class="text-base font-bold uppercase tracking-wide text-[#292d38] mb-4 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class='bx bx-package text-lg' style="color: var(--primary);"></i>
                        Courier Information
                    </h3>

                    <ul class="space-y-3 text-sm">
                        <li class="grid grid-cols-11">
                            <span class="col-span-4 text-gray-500 font-medium">Service</span>
                            <span class="col-span-1 text-gray-400">:</span>
                            <span class="col-span-6 text-gray-900 font-medium"><?= $courier_service ?></span>
                        </li>
                        <li class="grid grid-cols-11">
                            <span class="col-span-4 text-gray-500 font-medium">Tracking ID</span>
                            <span class="col-span-1 text-gray-400">:</span>
                            <span class="col-span-6 text-gray-900"><?= $tracking_id ?></span>
                        </li>
                        <li class="grid grid-cols-11">
                            <span class="col-span-4 text-gray-500 font-medium">Expected</span>
                            <span class="col-span-1 text-gray-400">:</span>
                            <span class="col-span-6 text-gray-900"><?= $expected_delivery_date ? date('M d, Y', strtotime($expected_delivery_date)) : 'update soon' ?></span>
                        </li>
                    </ul>
                </div>
            <?php endif ?>
        </div>

        <!-- Customized Gift -->
        <?php if ($customized_gift) : ?>
            <div class="theme16-card p-6 mb-6">
                <h3 class="text-base font-bold uppercase tracking-wide text-[#292d38] mb-4 flex items-center gap-2 border-b border-gray-100 pb-3">
                    <i class='bx bx-gift text-lg' style="color: var(--primary);"></i>
                    Customized Gift
                </h3>
                <div class="text-sm text-gray-700 leading-relaxed [&_ul]:ml-6 [&_ul]:mt-2 [&_ul]:list-disc pl-2">
                    <?= preg_replace('/customers\/\//', UPLOADS_URL . 'customers//', $customized_gift) ?>
                </div>
            </div>
        <?php endif ?>

        <!-- Contact -->
        <p class="mt-8 text-center text-sm text-gray-500">
            Contact us for any help at
            <a href="tel:<?= getSettings("phone") ?>" class="font-bold hover:underline" style="color: var(--primary);">
                <?= getSettings("phone") ?>
            </a>
            — We're always here to assist you.
        </p>

    </main>

    <?php include_once __DIR__ . "/includes/footer.php"; ?>

</body>
</html>