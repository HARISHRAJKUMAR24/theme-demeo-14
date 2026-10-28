<?php include_once __DIR__ . "/includes/files_includes.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once __DIR__ . "/includes/head_links.php"; ?>
    
    <style>
        :root {
            --primary-color: <?= htmlspecialchars(getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ed143b') ?>;
            --hover-color: <?= htmlspecialchars(getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ec4899') ?>;
        }

        /* ============ Sidebar link ============ */
        .sidebar-link {
            transition: all .2s ease;
        }
        .sidebar-link:hover {
            border-color: var(--primary-color) !important;
            color: var(--primary-color) !important;
        }
        .sidebar-link.active {
            border-color: var(--primary-color) !important;
            color: var(--primary-color) !important;
            background-color: color-mix(in srgb, var(--primary-color) 5%, white);
        }
        .sidebar-link.logout:hover {
            border-color: #ef4444 !important;
            color: #ef4444 !important;
        }

        /* ============ DataTable overrides ============ */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            padding: 8px 0;
        }

        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e5e7eb;
            border-radius: 4px; /* Slightly rounded for Theme 16 */
            padding: 8px 12px;
            background: #fff;
            font-size: 13px;
            color: #374151;
            transition: border-color .2s ease;
        }

        .dataTables_wrapper .dataTables_length select:focus,
        .dataTables_wrapper .dataTables_filter input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px color-mix(in srgb, var(--primary-color) 20%, transparent);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            padding: 6px 12px;
            margin: 0 4px;
            background: #fff;
            color: #374151;
            transition: all .2s ease;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f9fafb;
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: var(--primary-color);
            color: #fff;
            border-color: var(--primary-color);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
            background: #f9fafb;
            color: #9ca3af;
            border-color: #e5e7eb;
            cursor: not-allowed;
        }

        /* ============ Table header ============ */
        #ordersTable thead th {
            background: var(--primary-color);
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 14px 16px;
            text-align: left;
            border: none;
        }

        #ordersTable tbody td {
            padding: 14px 16px;
            font-size: 13px;
            color: #4b5563;
            vertical-align: middle;
            border-bottom: 1px solid #f3f4f6;
        }

        #ordersTable tbody tr {
            transition: background-color .15s ease;
        }

        #ordersTable tbody tr:hover {
            background: #f9fafb;
        }

        /* Empty message */
        .dataTables_empty {
            text-align: center !important;
            padding: 40px 20px !important;
            font-size: 14px !important;
            color: #9ca3af !important;
        }
    </style>
</head>

<body class="bg-white font-sans text-gray-800 min-h-screen antialiased flex flex-col">

    <?php include_once __DIR__ . "/includes/nav-bar.php"; ?>

    <!-- Main Container -->
    <main class="flex-grow mx-auto w-full max-w-[1360px] px-4 py-10 sm:px-6 sm:py-14 lg:px-8">

        <!-- Page Header -->
        <div class="mb-10 text-center">
            <h1 class="text-2xl font-bold uppercase tracking-tight text-[#292d38] sm:text-[28px]">My Orders</h1>
            <div class="mx-auto mt-3 h-[2px] w-[30px]" style="background-color: var(--primary-color);"></div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">

            <!-- Left Sidebar -->
            <div class="w-full lg:w-[280px] shrink-0">
                <div class="border border-gray-200 bg-[#fafafa] p-6">
                    <!-- Profile Info -->
                    <div class="flex flex-col items-center mb-8">
                        <div class="relative mb-4">
                            <img id="previewImage"
                                src="<?= !empty(customer('photo')) ? UPLOADS_URL . customer('photo') : 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=200&q=80' ?>"
                                alt="User Profile"
                                class="w-20 h-20 rounded-full border-2 border-white shadow-sm object-cover">
                        </div>
                        <h2 class="text-base font-bold text-[#292d38]"><?= customer("name") ?></h2>
                        <p class="text-gray-500 text-xs mt-1"><?= customer("email") ?></p>
                    </div>

                    <!-- Sidebar Links -->
                    <div class="space-y-2">
                        <a href="<?= $storeUrl ?>profile" class="sidebar-link flex items-center gap-3 px-4 py-3 border border-gray-200 bg-white text-gray-700 rounded">
                            <i class='bx bx-user text-lg'></i>
                            <span class="font-medium text-sm">My Profile</span>
                        </a>

                        <a href="<?= $storeUrl ?>orders" class="sidebar-link active flex items-center gap-3 px-4 py-3 border bg-white text-[var(--primary-color)] rounded" style="border-color: var(--primary-color);">
                            <i class='bx bx-package text-lg'></i>
                            <span class="font-medium text-sm">My Orders</span>
                        </a>

                        <a href="<?= $storeUrl ?>wishlists" class="sidebar-link flex items-center gap-3 px-4 py-3 border border-gray-200 bg-white text-gray-700 rounded">
                            <i class='bx bx-heart text-lg'></i>
                            <span class="font-medium text-sm">Wishlists</span>
                        </a>

                        <a href="<?= $storeUrl ?>logout" class="sidebar-link logout flex items-center gap-3 px-4 py-3 border border-gray-200 bg-white text-gray-700 rounded">
                            <i class='bx bx-log-out text-lg'></i>
                            <span class="font-medium text-sm">Logout</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right: Orders Table -->
            <div class="flex-1 min-w-0">
                <div class="border border-gray-200 bg-white p-4 sm:p-6">
                    <div class="overflow-x-auto">
                        <table id="ordersTable" class="min-w-full table-auto text-sm">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Created</th>
                                    <th>Total</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include_once __DIR__ . "/includes/footer.php"; ?>

    <!-- DataTables Script -->
    <script>
        $(document).ready(function() {
            // Initialize DataTable with server-side processing
            var table = $('#ordersTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "shop/ajax/orders.php",
                    "type": "POST",
                    "data": function(d) {
                        if (!d.order || d.order.length === 0) {
                            d.order = [[1, 'desc']];
                        }
                    }
                },
                "columns": [
                    { "data": "order_id", "className": "whitespace-nowrap font-medium text-gray-900" },
                    {
                        "data": "created_at",
                        "className": "whitespace-nowrap",
                        "render": function(data, type, row) {
                            if (!data) return "";
                            const date = new Date(data);
                            const day = String(date.getDate()).padStart(2, '0');
                            const month = String(date.getMonth() + 1).padStart(2, '0');
                            const year = date.getFullYear();
                            return `${day}/${month}/${year}`;
                        }
                    },
                    { "data": "total", "className": "whitespace-nowrap font-semibold" },
                    { "data": "payment_method", "className": "whitespace-nowrap" },
                    { 
                        "data": "status", 
                        "className": "whitespace-nowrap font-semibold text-xs uppercase tracking-wider"
                    }
                ],
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50], [10, 25, 50]],
                "order": [[1, 'desc']],
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search orders...",
                    "lengthMenu": "Show _MENU_ entries",
                    "info": "Showing _START_ to _END_ of _TOTAL_ orders",
                    "infoEmpty": "No orders found",
                    "infoFiltered": "(filtered from _MAX_ total orders)",
                    "zeroRecords": "You haven't placed any orders yet 🛒",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "previous": "‹",
                        "next": "›"
                    },
                    "processing": "Loading orders..."
                },
                "responsive": true,
                "dom": '<"flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4"<"w-full sm:w-auto"l><"w-full sm:w-auto flex justify-end"f>>rt<"flex flex-col sm:flex-row justify-between items-center gap-4 mt-6"<"text-xs text-gray-500 uppercase tracking-wider"i><"flex gap-2"p>>',
                "initComplete": function() {
                    $('.dataTables_filter')
                        .addClass('w-full sm:w-auto')
                        .find('input')
                        .addClass('w-full sm:w-64 px-3 py-2 border border-gray-200 bg-white text-gray-800 focus:outline-none transition placeholder-gray-400 rounded')
                        .attr('placeholder', 'Search orders...');

                    $('.dataTables_length select')
                        .addClass('px-3 py-2 border border-gray-200 bg-white text-gray-800 focus:outline-none transition rounded');
                }
            });
        });
    </script>

</body>
</html>