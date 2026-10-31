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
