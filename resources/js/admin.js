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

// 5. Repeater (<x-admin.repeater>): add / remove / move rows, save them as JSON in a hidden input
document.querySelectorAll('[data-repeater]').forEach((repeater) => {
    const rows = repeater.querySelector('[data-repeater-rows]');
    const output = repeater.querySelector('[data-repeater-output]');
    const template = repeater.querySelector('[data-repeater-template]');

    // Read every row's inputs → [{label: "...", url: "..."}, ...] → hidden input
    const save = () => {
        const data = [...rows.querySelectorAll('[data-repeater-row]')].map((row) => {
            const item = {};
            row.querySelectorAll('[data-field]').forEach((input) => { item[input.dataset.field] = input.value; });
            return item;
        });
        output.value = JSON.stringify(data);
    };

    repeater.querySelector('[data-repeater-add]').addEventListener('click', () => {
        rows.append(template.content.cloneNode(true));
        save();
    });

    // One click listener for all buttons inside the rows (also works for rows added later)
    rows.addEventListener('click', (event) => {
        const row = event.target.closest('[data-repeater-row]');
        if (!row) return;

        if (event.target.closest('[data-repeater-remove]')) row.remove();
        if (event.target.closest('[data-repeater-up]') && row.previousElementSibling) rows.insertBefore(row, row.previousElementSibling);
        if (event.target.closest('[data-repeater-down]') && row.nextElementSibling) rows.insertBefore(row.nextElementSibling, row);
        save();
    });

    rows.addEventListener('input', save);
});

// 6. Sortable list with ↑ ↓ buttons (Home Page editor: section order)
document.querySelectorAll('[data-move]').forEach((button) => button.addEventListener('click', () => {
    const row = button.closest('[data-section-row]');
    const list = row.parentElement;

    if (button.dataset.move === 'up' && row.previousElementSibling) list.insertBefore(row, row.previousElementSibling);
    if (button.dataset.move === 'down' && row.nextElementSibling) list.insertBefore(row.nextElementSibling, row);
}));

// 7. "Copy" buttons (Media library: copy a file path)
document.querySelectorAll('[data-copy]').forEach((button) => button.addEventListener('click', async () => {
    await navigator.clipboard.writeText(button.dataset.copy);
    const old = button.textContent;
    button.textContent = 'Copied!';
    setTimeout(() => { button.textContent = old; }, 1500);
}));
