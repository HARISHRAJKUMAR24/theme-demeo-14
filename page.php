<?php include_once __DIR__ . "/includes/files_includes.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once __DIR__ . "/includes/head_links.php"; ?>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        wine: '#65132f',
                        cream: '#f7f2ee',
                        gold: '#b68a3b'
                    },
                    fontFamily: {
                        serifDisplay: ['Georgia', 'serif'],
                        clean: ['Arial', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --primary-color: <?= htmlspecialchars(getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ff007f') ?>;
            --hover-color: <?= htmlspecialchars(getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ec4899') ?>;
        }

        /* ---------- THEME 16 EDITORIAL PAGE ---------- */
        .t16-page {
            font-family: Arial, Helvetica, sans-serif;
            color: #1a1a1a;
            background: #ffffff;
        }

        /* HERO */
        .t16-hero {
            position: relative;
            padding: 88px 24px 72px;
            border-bottom: 1px solid #eaeaea;
            background:
                radial-gradient(circle at 15% 20%, color-mix(in srgb, var(--primary-color) 6%, transparent) 0%, transparent 55%),
                radial-gradient(circle at 85% 80%, color-mix(in srgb, var(--hover-color) 6%, transparent) 0%, transparent 55%),
                #ffffff;
            overflow: hidden;
        }

        .t16-hero-inner {
            max-width: 900px;
            margin: 0 auto;
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .t16-kicker {
            display: inline-block;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 3.5px;
            text-transform: uppercase;
            color: #9a527f;
            margin-bottom: 18px;
        }

        .t16-kicker::before,
        .t16-kicker::after {
            content: "";
            display: inline-block;
            width: 28px;
            height: 1px;
            background: currentColor;
            vertical-align: middle;
            margin: 0 12px;
            opacity: .5;
        }

        .t16-title {
            font-family: Georgia, 'Times New Roman', serif;
            font-size: clamp(32px, 5.5vw, 56px);
            font-weight: 400;
            line-height: 1.08;
            letter-spacing: -1.5px;
            color: #111;
            margin: 0;
        }

        .t16-title em {
            font-style: italic;
            color: var(--primary-color);
        }

        .t16-subtitle {
            margin: 22px auto 0;
            max-width: 520px;
            font-size: 13px;
            line-height: 1.7;
            color: #6b7280;
        }

        .t16-hero-rule {
            width: 56px;
            height: 1px;
            background: var(--primary-color);
            margin: 28px auto 0;
        }

        /* BODY */
        .t16-body {
            padding: 72px 24px 96px;
            background: #ffffff;
        }

        .t16-body-inner {
            max-width: 780px;
            margin: 0 auto;
        }

        /* Rich content typography — editorial */
        .t16-content {
            font-family: Georgia, 'Times New Roman', serif;
            font-size: 16px;
            line-height: 1.85;
            color: #2b2b2b;
            word-wrap: break-word;
        }

        .t16-content > *:first-child {
            margin-top: 0;
        }

        .t16-content h1,
        .t16-content h2,
        .t16-content h3,
        .t16-content h4 {
            font-family: Georgia, 'Times New Roman', serif;
            color: #111;
            font-weight: 400;
            letter-spacing: -0.5px;
            line-height: 1.25;
        }

        .t16-content h1 { font-size: 34px; margin: 48px 0 20px; }
        .t16-content h2 { font-size: 26px; margin: 40px 0 18px; }
        .t16-content h3 { font-size: 20px; margin: 32px 0 14px; }
        .t16-content h4 { font-size: 17px; margin: 28px 0 12px; font-style: italic; }

        .t16-content p {
            margin: 0 0 22px;
        }

        .t16-content p:last-child {
            margin-bottom: 0;
        }

        .t16-content > p:first-of-type::first-letter {
            font-size: 62px;
            font-family: Georgia, serif;
            float: left;
            line-height: .9;
            padding: 6px 12px 0 0;
            color: var(--primary-color);
            font-weight: 400;
        }

        .t16-content a {
            color: var(--primary-color);
            text-decoration: none;
            border-bottom: 1px solid currentColor;
            padding-bottom: 1px;
            transition: color .2s ease;
        }

        .t16-content a:hover {
            color: var(--hover-color);
        }

        .t16-content strong,
        .t16-content b {
            font-weight: 700;
            color: #111;
        }

        .t16-content em,
        .t16-content i {
            font-style: italic;
        }

        .t16-content ul,
        .t16-content ol {
            margin: 0 0 22px;
            padding-left: 24px;
        }

        .t16-content li {
            margin-bottom: 10px;
            line-height: 1.8;
        }

        .t16-content ul li { list-style-type: disc; }
        .t16-content ol li { list-style-type: decimal; }

        .t16-content blockquote {
            margin: 40px 0;
            padding: 24px 28px;
            border-left: 2px solid var(--primary-color);
            background: color-mix(in srgb, var(--primary-color) 3%, white);
            font-family: Georgia, serif;
            font-style: italic;
            font-size: 19px;
            line-height: 1.65;
            color: #333;
        }

        .t16-content blockquote p {
            margin: 0;
        }

        .t16-content img {
            max-width: 100%;
            height: auto;
            margin: 32px 0;
            display: block;
        }

        .t16-content figure {
            margin: 32px 0;
        }

        .t16-content figure img {
            margin: 0;
        }

        .t16-content figcaption {
            font-size: 12px;
            color: #888;
            margin-top: 10px;
            font-family: Arial, sans-serif;
            text-align: center;
            font-style: italic;
        }

        .t16-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 32px 0;
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
        }

        .t16-content th,
        .t16-content td {
            border: 0;
            border-bottom: 1px solid #eaeaea;
            padding: 14px 16px;
            text-align: left;
            vertical-align: top;
        }

        .t16-content th {
            background: #fafafa;
            font-weight: 600;
            color: #111;
            font-size: 12px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .t16-content hr {
            margin: 48px 0;
            border: 0;
            height: 1px;
            background: #eaeaea;
            position: relative;
        }

        .t16-content hr::before {
            content: "◆";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            padding: 0 12px;
            color: var(--primary-color);
            font-size: 10px;
        }

        .t16-content code {
            background: #f5f5f5;
            padding: 2px 6px;
            font-size: 13px;
            font-family: monospace;
            color: #111;
            border-radius: 2px;
        }

        .t16-content pre {
            background: #1a1a1a;
            color: #f5f5f5;
            padding: 22px;
            overflow-x: auto;
            font-size: 13px;
            font-family: monospace;
            margin: 32px 0;
            border-radius: 4px;
            line-height: 1.6;
        }

        /* FOOTER NAV */
        .t16-footer-nav {
            text-align: center;
            padding-top: 64px;
            margin-top: 64px;
            border-top: 1px solid #eaeaea;
        }

        .t16-footer-nav a {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 32px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            background: transparent;
            text-decoration: none;
            transition: all .25s ease;
        }

        .t16-footer-nav a:hover {
            background: var(--primary-color);
            color: #ffffff;
            gap: 14px;
        }

        .t16-footer-nav svg {
            width: 14px;
            height: 14px;
            transition: transform .25s ease;
        }

        .t16-footer-nav a:hover svg {
            transform: translateX(-3px);
        }

        /* MOBILE */
        @media (max-width: 640px) {
            .t16-hero {
                padding: 56px 20px 48px;
            }

            .t16-body {
                padding: 44px 20px 64px;
            }

            .t16-content {
                font-size: 15px;
                line-height: 1.8;
            }

            .t16-content h1 { font-size: 26px; margin: 36px 0 16px; }
            .t16-content h2 { font-size: 22px; margin: 32px 0 14px; }
            .t16-content h3 { font-size: 18px; margin: 26px 0 12px; }

            .t16-content > p:first-of-type::first-letter {
                font-size: 48px;
                padding: 4px 10px 0 0;
            }

            .t16-content blockquote {
                font-size: 17px;
                padding: 18px 20px;
                margin: 32px 0;
            }

            .t16-kicker::before,
            .t16-kicker::after {
                width: 20px;
                margin: 0 8px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .t16-content a,
            .t16-footer-nav a,
            .t16-footer-nav svg {
                transition: none !important;
            }
        }
    </style>
</head>

<body class="bg-white min-h-screen">

    <?php


    if (isset($_GET['slug']) && getData("id", "seller_pages", "slug = '{$_GET['slug']}' AND seller_id = '$sellerId'")) {
        $slug    = $_GET['slug'];
        $id      = getData("id", "seller_pages", "slug = '{$_GET['slug']}' AND seller_id = '$sellerId'");
        $name    = getData("name", "seller_pages", "slug = '{$_GET['slug']}' AND seller_id = '$sellerId'");
        $content = getData("content", "seller_pages", "slug = '{$_GET['slug']}' AND seller_id = '$sellerId'");
    } else {
        redirect($storeUrl);
    }

    $primary_color = getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ff007f';
    $hover_color   = getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ec4899';
    ?>


    <!-- Navbar -->
    <?php include_once __DIR__ . "/includes/nav-bar.php"; ?>


    <main class="t16-page">

        <!-- ============ HERO ============ -->
        <section class="t16-hero">
            <div class="t16-hero-inner">
                <span class="t16-kicker">Information</span>
                <h1 class="t16-title">
                    <?= htmlspecialchars($name) ?>
                </h1>
                <p class="t16-subtitle">
                    Everything you need to know — curated and composed with care.
                </p>
                <div class="t16-hero-rule"></div>
            </div>
        </section>

        <!-- ============ BODY ============ -->
        <section class="t16-body">
            <div class="t16-body-inner">

                <article class="t16-content">
                    <?= $content ?>
                </article>

                <!-- Back to Home -->
                <div class="t16-footer-nav">
                    <a href="<?= $storeUrl ?>">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Home
                    </a>
                </div>

            </div>
        </section>

    </main>

    <!-- Footer -->
    <?php include_once __DIR__ . "/includes/footer.php"; ?>

</body>

</html>