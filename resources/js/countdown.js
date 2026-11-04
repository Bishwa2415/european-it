// ============================================================
// Landing page countdown: <div data-countdown="73" data-countdown-key="page-slug">
//   with <span data-countdown-days|hours|minutes|seconds> inside.
// "Evergreen" (like the live site): the end time = first visit + N hours, saved per page in localStorage,
// so a returning visitor sees the same countdown. When it reaches 0 it starts again.
// All countdowns of one page (header + offer section) share the same end time.
// ============================================================
const boxes = document.querySelectorAll('[data-countdown]');

if (boxes.length) {
    const hours = Number(boxes[0].dataset.countdown) || 72;
    const key = 'countdown-' + boxes[0].dataset.countdownKey;
    const length = hours * 3600 * 1000;

    let memoryEnd = 0;   // used when the browser blocks localStorage (private mode)
    const readEnd = () => {
        let end;
        try { end = Number(localStorage.getItem(key)); } catch { end = memoryEnd; }
        if (!end || end < Date.now()) {
            end = Date.now() + length;
            memoryEnd = end;
            try { localStorage.setItem(key, end); } catch { /* keep it in memory only */ }
        }
        return end;
    };

    const pad = (n) => String(n).padStart(2, '0');

    const tick = () => {
        const left = Math.max(0, readEnd() - Date.now());
        const parts = {
            days: Math.floor(left / 86400000),
            hours: Math.floor(left / 3600000) % 24,
            minutes: Math.floor(left / 60000) % 60,
            seconds: Math.floor(left / 1000) % 60,
        };

        boxes.forEach((box) => {
            Object.entries(parts).forEach(([part, value]) => {
                const el = box.querySelector(`[data-countdown-${part}]`);
                if (el) el.textContent = pad(value);
            });
        });
    };

    tick();
    setInterval(tick, 1000);
}

// ============================================================
// Video covers: <button data-video-play="https://www.youtube.com/embed/..." data-video-kind="iframe|video">
// A click swaps the cover picture for the real player (the video only loads when someone wants it).
// ============================================================
document.querySelectorAll('[data-video-play]').forEach((button) => {
    button.addEventListener('click', () => {
        const src = button.dataset.videoPlay;
        const player = document.createElement(button.dataset.videoKind === 'video' ? 'video' : 'iframe');

        if (player.tagName === 'VIDEO') {
            player.src = src;
            player.controls = true;
            player.autoplay = true;
        } else {
            player.src = src + (src.includes('?') ? '&' : '?') + 'autoplay=1';
            player.allow = 'autoplay; encrypted-media; picture-in-picture';
            player.allowFullscreen = true;
        }
        player.className = 'absolute inset-0 h-full w-full bg-black';
        button.replaceWith(player);
    });
});

// ============================================================
// Rotating words: <span data-rotate='["গিফট!","অফার!","গিফট!"]'> – like the live "animated headline".
// Every 2.5 s the word fades out, changes to the next one and fades back in.
// ============================================================
document.querySelectorAll('[data-rotate]').forEach((el) => {
    let words;
    try {
        words = JSON.parse(el.dataset.rotate);
    } catch {
        return;
    }
    if (!Array.isArray(words) || words.length < 2) return;

    let i = 0;
    el.style.transition = 'opacity .3s, transform .3s';
    setInterval(() => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(-6px)';
        setTimeout(() => {
            i = (i + 1) % words.length;
            el.textContent = words[i];
            el.style.opacity = '1';
            el.style.transform = 'none';
        }, 300);
    }, 2500);
});
