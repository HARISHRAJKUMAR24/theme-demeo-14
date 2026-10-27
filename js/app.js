
document.addEventListener("DOMContentLoaded", function () {
    'use strict';

    /* ============================================================
       1) MOBILE MENU — smooth open/close (single source of truth)
       ============================================================ */
    const mobileBtn  = document.getElementById('mobileMenuButton');
    const mobileMenu = document.getElementById('mobileMenu');
    const closeBtn   = document.getElementById('closeMobileMenu');
    let   isOpen     = false;

    function openMobileMenu() {
        if (!mobileMenu || isOpen) return;
        isOpen = true;

        // Show first (so transition has something to animate from)
        mobileMenu.style.visibility = 'visible';

        // Force reflow so the browser registers the starting state
        void mobileMenu.offsetWidth;

        mobileMenu.style.transform = 'translateX(0)';
        mobileMenu.style.opacity   = '1';

        mobileBtn?.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileMenu() {
        if (!mobileMenu || !isOpen) return;
        isOpen = false;

        mobileMenu.style.transform = 'translateX(100%)';
        mobileMenu.style.opacity   = '0';

        mobileBtn?.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';

        // After transition ends, hide from a11y tree
        window.setTimeout(function () {
            if (!isOpen) mobileMenu.style.visibility = 'hidden';
        }, 300);
    }

    mobileBtn?.addEventListener('click', function (e) {
        e.stopPropagation();
        isOpen ? closeMobileMenu() : openMobileMenu();
    });

    closeBtn?.addEventListener('click', function (e) {
        e.stopPropagation();
        closeMobileMenu();
    });

    // Backdrop click (click outside the menu)
    document.addEventListener('click', function (e) {
        if (!isOpen) return;
        if (!mobileMenu.contains(e.target) && !mobileBtn.contains(e.target)) {
            closeMobileMenu();
        }
    });

    // ESC key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && isOpen) closeMobileMenu();
    });

    /* ============================================================
       2) MOBILE ACCORDION (sub-categories)
       ============================================================ */
    document.querySelectorAll('.mobile-dropdown-toggle').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const target = document.getElementById(this.dataset.target);
            if (!target) return;

            const isHidden = target.classList.contains('hidden');
            target.classList.toggle('hidden', !isHidden);

            const icon = this.querySelector('svg');
            if (icon) icon.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
        });
    });

    /* ============================================================
       3) CART + WISHLIST COUNT UPDATER
       ============================================================ */
    window.updateHeaderCounts = function () {
        if (!window.jQuery) return;

        // Cart
        jQuery.ajax({
            url: 'shop/ajax/get-product-count-and-price.php',
            type: 'GET',
            cache: false,
            success: function (result) {
                try {
                    const r = JSON.parse(result);
                    const count = r.itemsCount || r.count || 0;
                    document.querySelectorAll('.cartItemsCount').forEach(function (el) {
                        el.textContent = count;
                        el.style.display = count > 0 ? 'flex' : 'none';
                    });
                } catch (e) {}
            }
        });

        // Wishlist
        jQuery.ajax({
            url: 'shop/ajax/get-wishlist-count.php',
            type: 'GET',
            cache: false,
            success: function (result) {
                try {
                    const r = JSON.parse(result);
                    const count = r.count || r.wishlistCount || 0;
                    document.querySelectorAll('.wishlistItemsCount').forEach(function (el) {
                        el.textContent = count;
                        el.style.display = count > 0 ? 'flex' : 'none';
                    });
                } catch (e) {}
            },
            error: function () {
                document.querySelectorAll('.wishlistItemsCount').forEach(function (el) {
                    el.style.display = 'none';
                });
            }
        });
    };
    window.refreshCartUI = window.refreshCartUI || function () {
        if (typeof window.updateHeaderCounts === 'function') window.updateHeaderCounts();
    };
    setTimeout(window.updateHeaderCounts, 300);

    /* ============================================================
       4) PRODUCT CAROUSEL  (from your footer JS)
       ============================================================ */
    const carousel = document.getElementById("productCarousel");
    const viewport = document.getElementById("productViewport");
    const track    = document.getElementById("productTrack");
    const dotsContainer = document.getElementById("productDots");

    if (carousel && viewport && track && dotsContainer) {
        const slides = Array.from(track.querySelectorAll(".product-slide"));
        let currentIndex = 0, visibleCount = 3, maxIndex = 0;
        let autoTimer = null, resizeTimer = null;

        function getVisibleCount() {
            if (window.innerWidth < 640) return 1;
            if (window.innerWidth < 1024) return 2;
            return 3;
        }
        function updateCarousel() {
            visibleCount = getVisibleCount();
            slides.forEach(function (s) { s.style.width = (100 / visibleCount) + "%"; });
            maxIndex = Math.max(0, slides.length - visibleCount);
            currentIndex = Math.min(currentIndex, maxIndex);
            renderDots();
            moveToIndex(currentIndex, false);
        }
        function renderDots() {
            dotsContainer.innerHTML = "";
            for (let i = 0; i <= maxIndex; i++) {
                const dot = document.createElement("button");
                dot.type = "button";
                dot.setAttribute("aria-label", "Show product group " + (i + 1));
                dot.className = "h-[9px] w-[9px] rounded-full transition-all duration-300 " +
                    (i === currentIndex ? "bg-[#292d38] scale-110" : "bg-gray-200 hover:bg-[#ed143b]");
                dot.addEventListener("click", function () {
                    currentIndex = i;
                    moveToIndex(currentIndex, true);
                    restartAutoplay();
                });
                dotsContainer.appendChild(dot);
            }
        }
        function moveToIndex(index, animate) {
            currentIndex = Math.max(0, Math.min(index, maxIndex));
            track.style.transition = animate ? "transform 700ms ease-in-out" : "none";
            const slideWidth = slides[0] ? slides[0].getBoundingClientRect().width : 0;
            track.style.transform = "translate3d(-" + (currentIndex * slideWidth) + "px, 0, 0)";
            Array.from(dotsContainer.children).forEach(function (dot, i) {
                dot.classList.toggle("bg-[#292d38]", i === currentIndex);
                dot.classList.toggle("bg-gray-200", i !== currentIndex);
                dot.classList.toggle("scale-110", i === currentIndex);
            });
        }
        function nextSlide() {
            if (maxIndex <= 0) return;
            currentIndex = currentIndex >= maxIndex ? 0 : currentIndex + 1;
            moveToIndex(currentIndex, true);
        }
        function startAutoplay() {
            stopAutoplay();
            if (slides.length > visibleCount) autoTimer = setInterval(nextSlide, 3500);
        }
        function stopAutoplay() {
            if (autoTimer !== null) { clearInterval(autoTimer); autoTimer = null; }
        }
        function restartAutoplay() { startAutoplay(); }

        carousel.addEventListener("mouseenter", stopAutoplay);
        carousel.addEventListener("mouseleave", startAutoplay);
        carousel.addEventListener("focusin", stopAutoplay);
        carousel.addEventListener("focusout", startAutoplay);

        let touchStartX = 0;
        viewport.addEventListener("touchstart", function (e) {
            touchStartX = e.changedTouches[0].screenX;
            stopAutoplay();
        }, { passive: true });
        viewport.addEventListener("touchend", function (e) {
            const diff = touchStartX - e.changedTouches[0].screenX;
            if (Math.abs(diff) > 40) {
                if (diff > 0) nextSlide();
                else { currentIndex = currentIndex <= 0 ? maxIndex : currentIndex - 1; moveToIndex(currentIndex, true); }
            }
            startAutoplay();
        }, { passive: true });

        window.addEventListener("resize", function () {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function () { updateCarousel(); startAutoplay(); }, 150);
        });

        document.querySelectorAll(".copy-product-link").forEach(function (button) {
            button.addEventListener("click", async function () {
                const path = button.getAttribute("data-copy-link");
                const original = button.innerHTML;
                const fullUrl = new URL(path, window.location.origin).href;
                try {
                    await navigator.clipboard.writeText(fullUrl);
                    button.textContent = "✓";
                    setTimeout(function () { button.innerHTML = original; }, 1200);
                } catch (err) { window.prompt("Copy this product link:", fullUrl); }
            });
        });

        updateCarousel();
        startAutoplay();
    }

    /* ============================================================
       5) HERO GLASS REFLECTION
       ============================================================ */
    const heroBanner = document.getElementById("heroBanner");
    if (heroBanner) {
        const reflections = heroBanner.querySelectorAll(".hero-glass-reflection");
        let reflectionTimer;

        function playGlassReflection() {
            reflections.forEach(function (reflection, index) {
                reflection.getAnimations().forEach(function (a) { a.cancel(); });
                reflection.animate(
                    [
                        { left: "-45%", opacity: 0 },
                        { left: "0%",   opacity: 1, offset: 0.2 },
                        { left: "120%", opacity: 0 }
                    ],
                    { duration: 2200, delay: index * 120, easing: "ease-in-out", fill: "none" }
                );
            });
        }
        const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)");
        if (!reduceMotion.matches) {
            playGlassReflection();
            reflectionTimer = setInterval(playGlassReflection, 5000);
        }
        document.addEventListener("visibilitychange", function () {
            if (document.hidden) {
                clearInterval(reflectionTimer);
                reflections.forEach(function (r) {
                    r.getAnimations().forEach(function (a) { a.cancel(); });
                });
            } else if (!reduceMotion.matches) {
                playGlassReflection();
                clearInterval(reflectionTimer);
                reflectionTimer = setInterval(playGlassReflection, 5000);
            }
        });
    }
});
