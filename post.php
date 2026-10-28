<?php
include_once __DIR__ . "/includes/files_includes.php";

if (isset($_GET['slug']) && getData("id", "seller_blog", "slug = '{$_GET['slug']}' AND (seller_id = '$sellerId' AND store_id = '$storeId')")) {

    $slug = $_GET['slug'];
    $id = getData("id", "seller_blog", "slug = '{$_GET['slug']}' AND (seller_id = '$sellerId' AND store_id = '$storeId')");
    $title = getData("title", "seller_blog", "slug = '{$_GET['slug']}' AND (seller_id = '$sellerId' AND store_id = '$storeId')");
    $image = getData("image", "seller_blog", "slug = '{$_GET['slug']}' AND (seller_id = '$sellerId' AND store_id = '$storeId')");
    $description = getData("description", "seller_blog", "slug = '{$_GET['slug']}' AND (seller_id = '$sellerId' AND store_id = '$storeId')");
    $created_at = getData("created_at", "seller_blog", "slug = '{$_GET['slug']}' AND (seller_id = '$sellerId' AND store_id = '$storeId')");
    $visitors = getData("visitors", "seller_blog", "slug = '{$_GET['slug']}' AND (seller_id = '$sellerId' AND store_id = '$storeId')");

    $db->prepare("UPDATE seller_blog SET visitors = visitors+1 WHERE id = :id")->execute(['id' => $id]);
} else {
    redirect($storeUrl);
}

// Get related posts
$relatedPosts = getBlogPosts(3, $id);

// Fetch dynamic colors
$primary_color = getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ff007f';
$hover_color   = getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ec4899';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once __DIR__ . "/includes/head_links.php"; ?>
    <style>
        .blog-content {
            font-size: 1rem;
            line-height: 1.8;
            color: #374151;
        }

        .blog-content p {
            margin-bottom: 1.25rem;
        }

        .blog-content h2,
        .blog-content h3 {
            margin-top: 2rem;
            margin-bottom: 1rem;
            font-weight: 700;
            color: #1a1a2e;
            letter-spacing: -0.01em;
        }

        .blog-content h2 { font-size: 1.5rem; }
        .blog-content h3 { font-size: 1.25rem; }

        .blog-content ul,
        .blog-content ol {
            margin-bottom: 1.25rem;
            padding-left: 1.5rem;
        }

        .blog-content ul { list-style-type: disc; }
        .blog-content ol { list-style-type: decimal; }

        .blog-content ul li,
        .blog-content ol li {
            margin-bottom: 0.5rem;
            padding-left: 0.25rem;
        }

        .blog-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1.5rem 0;
            border: 1px solid #e5e7eb;
        }

        .blog-content blockquote {
            border-left: 4px solid var(--primary);
            padding: 1rem 1.5rem;
            margin: 1.5rem 0;
            background: #f9fafb;
            border-radius: 0 8px 8px 0;
            font-style: italic;
            color: #4b5563;
        }

        .blog-content a {
            color: var(--primary);
            text-decoration: underline;
            text-underline-offset: 2px;
        }
    </style>
</head>

<body class="font-sans bg-gray-50 min-h-screen text-[#292d38]" style="--primary: <?= htmlspecialchars($primary_color) ?>;">

    <?php include_once __DIR__ . "/includes/nav-bar.php"; ?>

    <!-- ================= BLOG POST ================= -->
    <main class="mx-auto max-w-[900px] px-4 py-10 sm:px-6 sm:py-14 lg:px-8">
        
        <!-- Back Button -->
        <a href="<?= $storeUrl ?>blog" class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-gray-500 hover:text-[var(--primary)] transition-colors mb-8">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Journal
        </a>

        <!-- Article Card -->
        <article class="bg-white border border-gray-200 p-6 sm:p-10">
            
            <!-- Title -->
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold leading-tight text-[#1a1a2e] mb-6">
                <?= htmlspecialchars($title) ?>
            </h1>

            <!-- Meta Info -->
            <div class="flex flex-wrap items-center gap-4 sm:gap-6 pb-6 mb-8 border-b border-gray-100">
                <div class="flex items-center gap-2 text-xs font-medium text-gray-500">
                    <svg class="w-4 h-4 text-[var(--primary)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <?= date("d M Y", strtotime($created_at)) ?>
                </div>
                <div class="flex items-center gap-2 text-xs font-medium text-gray-500">
                    <svg class="w-4 h-4 text-[var(--primary)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <?= htmlspecialchars($storeName ?? 'Admin') ?>
                </div>
                <div class="flex items-center gap-2 text-xs font-medium text-gray-500">
                    <svg class="w-4 h-4 text-[var(--primary)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <?= (int)$visitors + 1 ?> Views
                </div>
            </div>

            <!-- Featured Image -->
            <img src="<?= UPLOADS_URL . $image ?>" 
                 alt="<?= htmlspecialchars($title) ?>" 
                 class="w-full h-[250px] sm:h-[350px] md:h-[450px] object-cover border border-gray-100 rounded-lg mb-8">

            <!-- Content -->
            <div class="blog-content">
                <?= $description ?>
            </div>

        </article>

        <!-- Related Posts -->
        <?php if ($relatedPosts && $relatedPosts->rowCount() > 1): ?>
        <section class="mt-14">
            <div class="flex items-center gap-4 mb-8">
                <h3 class="text-lg font-bold uppercase tracking-wider text-[#292d38]">Keep Reading</h3>
                <div class="h-px flex-1 bg-gray-200"></div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <?php 
                $shown = 0;
                while ($relatedPost = $relatedPosts->fetch()): 
                    if ($relatedPost['id'] == $id) continue;
                    if ($shown >= 3) break;
                    $shown++;
                ?>
                    <a href="<?= $storeUrl . "post/" . $relatedPost['slug'] ?>" class="group flex flex-col bg-white border border-gray-200 hover:border-[var(--primary)] transition-colors duration-300 rounded-lg overflow-hidden h-full">
                        <div class="overflow-hidden h-[160px] bg-gray-50 relative">
                            <img src="<?= UPLOADS_URL . $relatedPost['image'] ?>" 
                                 alt="<?= htmlspecialchars($relatedPost['title']) ?>"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                 loading="lazy">
                        </div>
                        <div class="p-4 flex flex-col flex-grow">
                            <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-2">
                                <svg class="w-3 h-3 text-[var(--primary)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <?= date("d M Y", strtotime($relatedPost['created_at'])) ?>
                            </div>
                            <h4 class="text-sm font-bold leading-snug text-[#292d38] group-hover:text-[var(--primary)] transition-colors line-clamp-2">
                                <?= htmlspecialchars($relatedPost['title']) ?>
                            </h4>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
        </section>
        <?php endif; ?>

    </main>

    <?php include_once __DIR__ . "/includes/footer.php"; ?>

</body>
</html>