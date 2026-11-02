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

// 5. Repeater (<x-admin.repeater>): add / remove / move rows, save them as JSON in a hidden input.
//    A function, so the group repeater (5b) can start the repeater inside a newly added group.
//    ":scope >" = only this repeater's own parts, not those of a repeater inside it.
const initRepeater = (repeater) => {
    const rows = repeater.querySelector(':scope > [data-repeater-rows]');
    const output = repeater.querySelector(':scope > [data-repeater-output]');
    const template = repeater.querySelector(':scope > [data-repeater-template]');

    // Read every row's inputs → [{label: "...", url: "..."}, ...] → hidden input
    const save = () => {
        const data = [...rows.querySelectorAll('[data-repeater-row]')].map((row) => {
            const item = {};
            row.querySelectorAll('[data-field]').forEach((input) => { item[input.dataset.field] = input.value; });
            return item;
        });
        output.value = JSON.stringify(data);
    };

    repeater.querySelector(':scope > [data-repeater-add]').addEventListener('click', () => {
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
    rows.addEventListener('change', save); // <select> sends "change"
};

// Repeaters inside a <template> are not part of the page yet, so this only finds the visible ones
document.querySelectorAll('[data-repeater]').forEach(initRepeater);

// 5b. Group repeater (<x-admin.group-repeater>): groups with a title + their own repeater of rows
//     → [{"title": "Media", "items": [{label, url}, ...]}, ...] in a hidden input
document.querySelectorAll('[data-group-repeater]').forEach((groupRepeater) => {
    const groups = groupRepeater.querySelector('[data-group-rows]');
    const output = groupRepeater.querySelector('[data-group-output]');
    const template = groupRepeater.querySelector('[data-group-template]');

    // The inner repeaters have already saved their JSON (their listeners sit deeper; events bubble up to here after)
    const save = () => {
        output.value = JSON.stringify([...groups.querySelectorAll(':scope > [data-group-row]')].map((group) => ({
            title: group.querySelector('[data-group-title]').value,
            items: JSON.parse(group.querySelector('[data-repeater-output]').value || '[]'),
        })));
    };

    groupRepeater.querySelector('[data-group-add]').addEventListener('click', () => {
        groups.append(template.content.cloneNode(true));
        initRepeater(groups.lastElementChild.querySelector('[data-repeater]'));
        save();
    });

    groups.addEventListener('click', (event) => {
        const group = event.target.closest('[data-group-row]');
        if (!group) return;

        if (event.target.closest('[data-group-remove]')) group.remove();
        if (event.target.closest('[data-group-up]') && group.previousElementSibling) groups.insertBefore(group, group.previousElementSibling);
        if (event.target.closest('[data-group-down]') && group.nextElementSibling) groups.insertBefore(group.nextElementSibling, group);
        save();
    });

    groups.addEventListener('input', save);
    groups.addEventListener('change', save);
});

// 6. Sortable list with ↑ ↓ buttons (Home Page editor: section order)
document.querySelectorAll('[data-move]').forEach((button) => button.addEventListener('click', () => {
    const row = button.closest('[data-section-row]');
    const list = row.parentElement;

    if (button.dataset.move === 'up' && row.previousElementSibling) list.insertBefore(row, row.previousElementSibling);
    if (button.dataset.move === 'down' && row.nextElementSibling) list.insertBefore(row.nextElementSibling, row);
}));

// 8. "Upload" buttons inside repeater rows (<input type="file" data-upload="image|video">)
//    The file goes to /admin/media/upload right away; the answer's path is written into the row's path box,
//    then an "input" event tells the repeater (5) to save its JSON again.
//    One listener on the whole document also catches rows added later.
document.addEventListener('change', async (event) => {
    const fileInput = event.target.closest('[data-upload]');
    if (!fileInput || !fileInput.files[0]) return;

    const box = fileInput.closest('[data-upload-box]');
    const pathInput = box.querySelector('[data-field]');
    const button = fileInput.parentElement;

    const form = new FormData();
    form.append('file', fileInput.files[0]);
    form.append('kind', fileInput.dataset.upload);

    button.classList.add('opacity-50', 'pointer-events-none');
    try {
        const response = await fetch(document.querySelector('meta[name="upload-url"]').content, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                Accept: 'application/json',   // so Laravel answers validation errors as JSON, not a redirect
            },
            body: form,
        });
        const data = await response.json();
        if (!response.ok) throw new Error(data.message || 'Upload failed');

        pathInput.value = data.path;
        const preview = box.querySelector('[data-upload-preview]');
        if (preview) preview.src = data.url;
        pathInput.dispatchEvent(new Event('input', { bubbles: true }));
    } catch (error) {
        alert(error.message);
    } finally {
        button.classList.remove('opacity-50', 'pointer-events-none');
        fileInput.value = '';   // same file can be chosen again
    }
});

// 9. Tabs: <div data-tabs><button data-tab="x"> ... and panels <div data-tab-panel="x" hidden>
//    The open tab is remembered in the URL (#tab-x), so after "Save" the same tab opens again.
document.querySelectorAll('[data-tabs]').forEach((tabs) => {
    const buttons = [...tabs.querySelectorAll('[data-tab]')];
    const panels = [...tabs.parentElement.querySelectorAll('[data-tab-panel]')];

    const open = (name) => {
        buttons.forEach((b) => b.classList.toggle('is-active', b.dataset.tab === name));
        panels.forEach((p) => { p.hidden = p.dataset.tabPanel !== name; });
        history.replaceState(null, '', '#tab-' + name);
    };

    buttons.forEach((b) => b.addEventListener('click', () => open(b.dataset.tab)));

    const fromUrl = location.hash.replace('#tab-', '');
    open(buttons.some((b) => b.dataset.tab === fromUrl) ? fromUrl : buttons[0].dataset.tab);
});

// 7. "Copy" buttons (Media library: copy a file path)
document.querySelectorAll('[data-copy]').forEach((button) => button.addEventListener('click', async () => {
    await navigator.clipboard.writeText(button.dataset.copy);
    const old = button.textContent;
    button.textContent = 'Copied!';
    setTimeout(() => { button.textContent = old; }, 1500);
}));
