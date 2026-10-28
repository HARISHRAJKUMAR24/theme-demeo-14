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

        .blog-card {
            transition: all 0.3s ease;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .blog-card:hover {
            border-color: var(--primary);
        }

        .blog-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .blog-card:hover img {
            transform: scale(1.05);
        }

        .blog-content {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .blog-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1a1a2e;
            transition: color 0.3s ease;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.4;
            margin-bottom: 0.75rem;
        }

        .blog-card:hover .blog-title {
            color: var(--primary);
        }

        .blog-meta {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            margin-bottom: 1rem;
        }

        .blog-meta i {
            font-size: 1rem;
            color: var(--primary);
        }

        .blog-excerpt {
            color: #4b5563;
            font-size: 0.9rem;
            line-height: 1.6;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .read-more-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: auto;
            padding: 0.6rem 1.25rem;
            background-color: var(--primary);
            color: #fff;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-radius: 6px;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .read-more-btn:hover {
            background-color: var(--hover-color);
        }

        .read-more-btn i {
            transition: transform 0.3s ease;
        }

        .read-more-btn:hover i {
            transform: translateX(4px);
        }

        @media (max-width: 768px) {
            .blog-card img { height: 200px; }
            .blog-title { font-size: 1rem; }
        }
    </style>
</head>

<body class="font-sans bg-gray-50 min-h-screen text-[#292d38]">

    <?php include_once __DIR__ . "/includes/nav-bar.php"; ?>

    <!-- ================= BLOG HEADER (Solid Primary Color) ================= -->
    <div class="py-8 text-white" style="background-color: var(--primary);">
        <div class="container mx-auto px-2 text-center">
            <div class="flex flex-col items-center justify-center gap-3">
                <div class="bg-white/20 p-3 rounded-full backdrop-blur-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl sm:text-4xl font-bold uppercase tracking-wide">Our Journal</h1>
                    <p class="opacity-90 mt-2 text-sm font-medium">Explore our latest articles, tips, and insights.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= BLOG GRID ================= -->
    <section class="py-12">
        <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            
            <?php
            $posts = getBlogPosts();
            if ($posts && $posts->rowCount() > 0):
            ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php foreach ($posts as $post): ?>
                    <div class="blog-card group">
                        <a href="<?= $storeUrl . "post/" . $post['slug'] ?>" class="block overflow-hidden relative">
                            <img src="<?= UPLOADS_URL . $post['image'] ?>" 
                                 alt="<?= htmlspecialchars($post['title']) ?>"
                                 loading="lazy">
                        </a>

                        <div class="blog-content">
                            <a href="<?= $storeUrl . "post/" . $post['slug'] ?>" class="blog-title">
                                <?= htmlspecialchars($post['title']) ?>
                            </a>

                            <div class="blog-meta">
                                <i class='bx bx-time-five'></i>
                                <span><?= date("d M Y", strtotime($post['created_at'])) ?></span>
                                <?php if (!empty($post['author'])): ?>
                                    <span class="mx-1 text-gray-300">|</span>
                                    <i class='bx bx-user'></i>
                                    <span><?= htmlspecialchars($post['author']) ?></span>
                                <?php endif; ?>
                            </div>

                            <p class="blog-excerpt">
                                <?= limit_characters(strip_tags($post['description']), 100) ?>
                            </p>

                            <a href="<?= $storeUrl . "post/" . $post['slug'] ?>" class="read-more-btn">
                                Read Article
                                <i class='bx bx-right-arrow-alt text-lg'></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
                <!-- No Posts Message -->
                <div class="text-center py-20 bg-white rounded-xl border border-gray-200">
                    <div class="w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-6" style="background-color: color-mix(in srgb, var(--primary) 10%, white);">
                        <svg class="w-10 h-10" style="stroke: var(--primary);" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">No Blog Posts Yet</h3>
                    <p class="text-sm text-gray-500">Check back soon for our latest articles and updates.</p>
                </div>
            <?php endif; ?>

            <!-- Pagination -->
            <?php if (isset($totalPages) && $totalPages > 1): ?>
            <div class="flex justify-center mt-12">
                <nav class="flex items-center gap-2">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?= $i ?>" 
                           class="flex items-center justify-center w-10 h-10 rounded-md text-sm font-bold transition-all duration-200 border
                           <?= ($currentPage == $i) 
                               ? 'text-white border-transparent' 
                               : 'bg-white text-gray-700 border-gray-200 hover:border-[var(--primary)] hover:text-[var(--primary)]' ?>"
                           <?= ($currentPage == $i) ? 'style="background-color: var(--primary);"' : '' ?>>
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </nav>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <?php include_once __DIR__ . "/includes/footer.php"; ?>

</body>
</html>