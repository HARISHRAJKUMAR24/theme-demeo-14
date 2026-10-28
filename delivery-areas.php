<?php include_once __DIR__ . "/includes/files_includes.php"; ?>

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
            border-color: color-mix(in srgb, var(--primary) 40%, #e5e7eb);
        }

        .theme16-btn {
            background: var(--primary);
            color: white;
            transition: all .3s ease;
        }

        .theme16-btn:hover {
            background: var(--hover-color);
            color: white;
        }

        .info-row {
            transition: background .2s ease;
        }

        .info-row:hover {
            background: color-mix(in srgb, var(--primary) 4%, white);
        }
    </style>
</head>

<body class="bg-white font-sans text-gray-800 min-h-screen antialiased">

    <?php include_once __DIR__ . "/includes/nav-bar.php"; ?>

    <!-- Page Title -->
    <main class="mx-auto max-w-[1360px] px-4 py-10 sm:px-6 sm:py-14 lg:px-8">
        <div class="mb-10 text-center">
            <h1 class="text-2xl font-bold uppercase tracking-tight text-[#292d38] sm:text-[28px]">Where We Deliver</h1>
            <div class="mx-auto mt-3 h-[2px] w-[30px]" style="background-color: var(--primary);"></div>
            <p class="mt-4 text-sm text-gray-500 max-w-2xl mx-auto">
                Check our delivery areas and visit our stores. We bring your order straight to your doorstep.
            </p>
        </div>

        <!-- ================= DELIVERY AREAS ================= -->
        <div class="mb-16">
            <div class="text-center mb-8">
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">Now Serving</p>
                <h2 class="text-xl font-bold uppercase tracking-wide text-[#292d38] mt-2">Delivery Areas</h2>
                <div class="w-10 h-[2px] mx-auto mt-3" style="background-color: var(--primary);"></div>
            </div>

            <?php
            $areasStmt = getDeliveryAreas();
            $areas = $areasStmt instanceof PDOStatement ? $areasStmt->fetchAll(PDO::FETCH_ASSOC) : (is_array($areasStmt) ? $areasStmt : []);

            if (!empty($areas)):
            ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($areas as $index => $area): ?>
                        <div class="theme16-card p-5">
                            <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-100">
                                <div class="w-8 h-8 flex items-center justify-center text-white text-xs font-bold shrink-0" style="background: var(--primary);">
                                    <?= $index + 1 ?>
                                </div>
                                <h3 class="text-base font-bold text-[#292d38] truncate">
                                    <?= htmlspecialchars($area['value']) ?>
                                </h3>
                            </div>

                            <div class="space-y-2 text-sm">
                                <div class="info-row flex items-center justify-between p-2 rounded">
                                    <span class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        <i class='bx bx-map text-base' style="color: var(--primary);"></i>
                                        <?= getData("delivery_area_type", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") == "zip_code" ? 'Postal Code' : 'State' ?>
                                    </span>
                                    <span class="font-medium text-gray-900"><?= htmlspecialchars($area['value']) ?></span>
                                </div>

                                <div class="info-row flex items-center justify-between p-2 rounded">
                                    <span class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        <i class='bx bx-package text-base' style="color: var(--primary);"></i>
                                        Delivery Fee
                                    </span>
                                    <?php if (getSettings("delivery_charges_type")): ?>
                                        <span class="font-medium text-gray-900">
                                            <?= ucwords(str_replace("_", " ", getSettings("delivery_charges_type"))) ?>
                                        </span>
                                    <?php elseif ($area['charges'] > 0): ?>
                                        <span class="font-medium text-gray-900"><?= currencyToSymbol($storeCurrency) . $area['charges'] ?></span>
                                    <?php else: ?>
                                        <span class="font-bold" style="color: var(--hover-color);">Free</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <?php if (!empty($area['delivery_notes'])): ?>
                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <div class="flex items-start gap-2 text-xs text-gray-500 leading-relaxed">
                                        <i class='bx bx-info-circle shrink-0 mt-0.5' style="color: var(--primary);"></i>
                                        <p><?= htmlspecialchars($area['delivery_notes']) ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-center text-gray-500 py-12">No delivery areas configured yet.</p>
            <?php endif; ?>
        </div>

        <!-- Divider -->
        <div class="mx-auto w-full max-w-lg h-px bg-gray-100 mb-16"></div>

        <!-- ================= OUR STORES ================= -->
        <div>
            <div class="text-center mb-8">
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">Visit Us</p>
                <h2 class="text-xl font-bold uppercase tracking-wide text-[#292d38] mt-2">Our Stores</h2>
                <div class="w-10 h-[2px] mx-auto mt-3" style="background-color: var(--primary);"></div>
            </div>

            <?php
            $locationsStmt = readData("*", "seller_locations", "(seller_id = '$sellerId' AND store_id = '$storeId')");
            $locations = $locationsStmt ? $locationsStmt->fetchAll(PDO::FETCH_ASSOC) : [];

            if (!empty($locations)):
            ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($locations as $index => $location): ?>
                        <div class="theme16-card p-5">
                            <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-100">
                                <div class="w-8 h-8 flex items-center justify-center text-white text-xs font-bold shrink-0" style="background: var(--primary);">
                                    <?= $index + 1 ?>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-base font-bold text-[#292d38] truncate"><?= htmlspecialchars($location['name']) ?></h3>
                                    <?php if ($location['phone']): ?>
                                        <a href="tel:<?= $location['phone'] ?>" class="text-xs hover:underline font-medium" style="color: var(--primary);">
                                            <i class='bx bx-phone'></i> <?= formatPhoneNumber($location['phone']) ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="space-y-3 text-sm">
                                <div class="info-row flex items-start gap-2 p-2 rounded">
                                    <i class='bx bx-map-pin text-base shrink-0 mt-0.5' style="color: var(--primary);"></i>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-0.5">Address</p>
                                        <p class="text-gray-900 break-words leading-relaxed">
                                            <?= htmlspecialchars($location['address_1']) ?>
                                            <?php if ($location['address_2']): ?><br><?= htmlspecialchars($location['address_2']) ?><?php endif; ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-2">
                                    <div class="info-row p-2 rounded">
                                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-0.5">City</p>
                                        <p class="text-sm font-semibold text-gray-900 break-words"><?= htmlspecialchars($location['city']) ?></p>
                                    </div>
                                    <div class="info-row p-2 rounded">
                                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-0.5">Postal Code</p>
                                        <p class="text-sm font-semibold text-gray-900"><?= htmlspecialchars($location['zip_code']) ?></p>
                                    </div>
                                    <div class="info-row p-2 rounded">
                                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-0.5">State</p>
                                        <p class="text-sm font-semibold text-gray-900 break-words"><?= htmlspecialchars($location['state']) ?></p>
                                    </div>
                                    <div class="info-row p-2 rounded">
                                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-0.5">Country</p>
                                        <p class="text-sm font-semibold text-gray-900 break-words"><?= codeToCountry($location['country']) ?></p>
                                    </div>
                                </div>

                                <?php if ($location['email']): ?>
                                    <div class="info-row flex items-center gap-2 p-2 rounded">
                                        <i class='bx bx-envelope text-base shrink-0' style="color: var(--primary);"></i>
                                        <a href="mailto:<?= $location['email'] ?>" class="text-sm truncate hover:underline font-medium" style="color: var(--primary);">
                                            <?= htmlspecialchars($location['email']) ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php
                            $business_hours = getLocationBusinessHour($location['location_id']);
                            if (!empty($business_hours)):
                            ?>
                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">Business Hours</p>
                                    <div class="space-y-1 text-xs">
                                        <?php foreach ($business_hours as $hour): ?>
                                            <div class="flex items-center justify-between py-1 border-b border-gray-50 last:border-0">
                                                <span class="text-gray-500 font-medium"><?= htmlspecialchars($hour['day']) ?></span>
                                                <span class="font-bold text-gray-800">
                                                    <?= strtoupper(date('h:i a', strtotime($hour['time_from']))) ?> – <?= strtoupper(date('h:i a', strtotime($hour['time_to']))) ?>
                                                </span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-center text-gray-500 py-12">No store locations added yet.</p>
            <?php endif; ?>
        </div>

        <!-- ================= CTA ================= -->
        <div class="mt-16 py-12 px-6 text-center" style="background-color: var(--primary);">
            <p class="text-xs font-semibold tracking-[3px] uppercase text-white/70 mb-3">Ready to shop?</p>
            <h2 class="text-2xl sm:text-3xl font-bold uppercase tracking-wide text-white mb-4">Start Shopping Today</h2>
            <p class="text-white/90 text-sm mb-6 max-w-lg mx-auto">Order now and let us bring your favourites right to your door.</p>
            <a href="<?= $storeUrl ?>shop-all" class="inline-flex items-center justify-center gap-2 px-8 py-3 text-sm font-bold uppercase tracking-wider bg-white transition-colors hover:bg-gray-50" style="color: var(--primary);">
                Shop Now <span class="text-lg">→</span>
            </a>
        </div>
    </main>

    <?php include_once __DIR__ . "/includes/footer.php"; ?>

</body>
</html>