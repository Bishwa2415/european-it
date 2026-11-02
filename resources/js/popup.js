// ============================================================
// Site popup (resources/views/partials/popup.blade.php)
// - Opens after a short delay, only if this visitor has not closed this popup before.
// - The key includes the popup's id + last update time, so after the admin edits it, it shows again.
// - Closes with the × button, the button inside, a click on the dark background, or Esc.
// ============================================================

const popup = document.querySelector('[data-popup]');

if (popup) {
    const key = popup.dataset.popupKey;

    // localStorage can throw in private mode → then simply show the popup
    const wasClosed = () => { try { return localStorage.getItem(key) === 'closed'; } catch { return false; } };
    const rememberClosed = () => { try { localStorage.setItem(key, 'closed'); } catch { /* ignore */ } };

    const open = () => { popup.classList.remove('hidden'); popup.classList.add('flex'); };
    const close = () => { popup.classList.add('hidden'); popup.classList.remove('flex'); rememberClosed(); };

    if (!wasClosed()) {
        setTimeout(open, Number(popup.dataset.popupDelay || 0) * 1000);
    }

    popup.addEventListener('click', (event) => {
        if (event.target === popup || event.target.closest('[data-popup-close]')) close();
    });
    document.addEventListener('keydown', (event) => { if (event.key === 'Escape') close(); });
}
