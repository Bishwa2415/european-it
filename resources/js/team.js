// ============================================================
// Home "Our Support Team" (resources/views/home/team.blade.php)
// 1. When the photo slider moves, the name / designation / bio in the blue box
//    slide out and the new ones slide in (like the live site).
// 2. The dot bar on the left shows which person is active; clicking a dot jumps there.
// The slider fires "slider:change" (see resources/js/slider.js).
// ============================================================

document.querySelectorAll('[data-team]').forEach((section) => {
    const slider = section.querySelector('[data-team-slider]');
    const dots = [...section.querySelectorAll('[data-team-dot]')];
    const fields = {
        name: section.querySelector('[data-team-name]'),
        designation: section.querySelector('[data-team-designation]'),
        bio: section.querySelector('[data-team-bio]'),
    };

    const setActiveDot = (index) => {
        dots.forEach((dot, i) => dot.classList.toggle('is-active', i === index));
    };

    // Old text leaves (up for "next", down for "prev"), then the new text comes in from the other side
    const swapText = (element, text, direction) => {
        const leave = direction === 'next' ? 'leave-up' : 'leave-down';
        const enter = direction === 'next' ? 'enter-up' : 'enter-down';

        element.classList.remove('enter-up', 'enter-down', 'leave-up', 'leave-down');
        void element.offsetWidth;   // restart the animation even if the same class was used just before
        element.classList.add(leave);

        clearTimeout(element.swapTimer);
        element.swapTimer = setTimeout(() => {   // .35s = the "leave" animation length in app.css
            element.textContent = text;
            element.classList.remove(leave);
            element.classList.add(enter);
        }, 350);
    };

    slider?.addEventListener('slider:change', ({ detail }) => {
        if (!detail.slide) return;

        swapText(fields.name, detail.slide.dataset.name, detail.direction);
        swapText(fields.designation, detail.slide.dataset.designation, detail.direction);
        swapText(fields.bio, detail.slide.dataset.bio ?? '', detail.direction);
        setActiveDot(detail.index);
    });

    dots.forEach((dot, i) => dot.addEventListener('click', () => slider.swiper?.slideToLoop(i)));
});
