// ============================================================
// Fade in when scrolled into view (like Elementor's "fadeIn" entrance animation).
// Use: <div data-reveal> ... </div>   (styles in resources/css/app.css)
// ============================================================

const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        if (!entry.isIntersecting) return;

        entry.target.classList.add('is-visible');
        revealObserver.unobserve(entry.target);   // animate only once
    });
}, { threshold: 0.15 });

document.querySelectorAll('[data-reveal]').forEach((el) => revealObserver.observe(el));
