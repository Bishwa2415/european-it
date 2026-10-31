// ===== Sliders / carousels (Swiper) — code lives in resources/js/slider.js =====
import './slider';

// ===== Mobile menu: open / close =====
const mobileMenu = document.getElementById('mobile-menu');

document.querySelectorAll('[data-mobile-menu-open]').forEach((button) => {
    button.addEventListener('click', () => {
        mobileMenu.classList.remove('hidden');
        document.body.classList.add('overflow-hidden'); // stop the page behind from scrolling
    });
});

document.querySelectorAll('[data-mobile-menu-close]').forEach((button) => {
    button.addEventListener('click', () => {
        mobileMenu.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    });
});

// ===== Mobile menu: accordion (All Course, Media ...) =====
document.querySelectorAll('[data-accordion-toggle]').forEach((button) => {
    button.addEventListener('click', () => {
        const panel = button.nextElementSibling;          // the hidden list right after the button
        const willOpen = panel.classList.contains('hidden');

        panel.classList.toggle('hidden', !willOpen);
        button.classList.toggle('bg-primary', willOpen);  // active item turns blue
        button.classList.toggle('text-white', willOpen);
        button.querySelector('i').classList.toggle('rotate-180', willOpen);
    });
});

// ===== Number counter: 0 → data-count when it scrolls into view =====
const counterObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        if (!entry.isIntersecting) return;

        const el = entry.target;
        const target = Number(el.dataset.count);
        const duration = 2000;                                // 2 seconds
        const start = performance.now();

        const tick = (now) => {
            const progress = Math.min((now - start) / duration, 1);
            el.textContent = Math.floor(progress * target);
            if (progress < 1) requestAnimationFrame(tick);
        };
        requestAnimationFrame(tick);

        counterObserver.unobserve(el);                        // count only once
    });
});

document.querySelectorAll('[data-count]').forEach((el) => counterObserver.observe(el));
