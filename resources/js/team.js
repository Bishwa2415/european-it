// ============================================================
// Home "Our Support Team": when the photo slider moves,
// show the active person's name, designation and bio in the big blue box.
// The slider fires "slider:change" (see resources/js/slider.js).
// ============================================================

document.querySelectorAll('[data-team]').forEach((section) => {
    const slider = section.querySelector('[data-team-slider]');
    const name = section.querySelector('[data-team-name]');
    const designation = section.querySelector('[data-team-designation]');
    const bio = section.querySelector('[data-team-bio]');

    slider?.addEventListener('slider:change', (event) => {
        const slide = event.detail.slide;
        if (!slide) return;

        name.textContent = slide.dataset.name;
        designation.textContent = slide.dataset.designation;
        bio.textContent = slide.dataset.bio ?? '';
    });
});
