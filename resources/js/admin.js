// ============================================================
// Small helpers for the dashboard (loaded only on /admin pages)
// Each one works through data-* attributes in the HTML.
// ============================================================

// 1. Auto slug: typing the title fills the slug, until the admin edits the slug by hand
const slugSource = document.querySelector('[data-slug-source]');
const slugTarget = document.querySelector('[data-slug-target]');

if (slugSource && slugTarget) {
    let slugEditedByHand = slugTarget.value !== '';

    const slugify = (text) => text.toLowerCase().trim()
        .replace(/&/g, 'and')
        .replace(/[^a-z0-9]+/g, '-')     // anything that is not a-z or 0-9 becomes "-"
        .replace(/^-+|-+$/g, '');        // no "-" at the start or end

    slugSource.addEventListener('input', () => {
        if (!slugEditedByHand) slugTarget.value = slugify(slugSource.value);
    });
    slugTarget.addEventListener('input', () => { slugEditedByHand = slugTarget.value !== ''; });
}

// 2. Image preview: show the chosen picture before saving
document.querySelectorAll('[data-image-preview]').forEach((img) => {
    const input = document.getElementById(img.dataset.imagePreview);

    input?.addEventListener('change', () => {
        const file = input.files[0];
        if (file) img.src = URL.createObjectURL(file);
    });
});

// 3. "Are you sure?" before delete forms
document.querySelectorAll('form[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (!confirm(form.dataset.confirm)) event.preventDefault();
    });
});

// 4. Online / Offline plan: fade the fields while the plan is switched off
document.querySelectorAll('[data-plan]').forEach((box) => {
    const toggle = box.querySelector('[data-plan-toggle]');
    const fields = box.querySelector('[data-plan-fields]');

    const update = () => fields.classList.toggle('opacity-40', !toggle.checked);
    toggle.addEventListener('change', update);
    update();
});
