// ============================================================
// One slider setup for the WHOLE site (hero, success stories, reviews, mentors ...)
// Library: Swiper (installed with: npm install swiper)
//
// HTML pattern:
//   <div data-slider='{"slidesPerView": 1}'>          ← optional JSON options
//       <div class="swiper">
//           <div class="swiper-wrapper">
//               <div class="swiper-slide">...</div>
//           </div>
//       </div>
//       <button data-slider-prev>←</button>
//       <button data-slider-next>→</button>
//   </div>
// ============================================================

import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/pagination';

// Rules every slider must follow: drag, smooth, seamless loop, autoplay
const defaults = {
    modules: [Navigation, Pagination, Autoplay],
    loop: true,                  // after the last slide comes the first, without jumping back
    speed: 700,                  // smooth slide animation (ms)
    grabCursor: true,            // hand cursor -> shows it can be dragged with the mouse
    simulateTouch: true,         // drag with mouse on desktop, swipe on phone
    slidesPerView: 1,
    spaceBetween: 20,
    autoplay: {
        delay: 5000,
        disableOnInteraction: false,  // keep auto-playing after the user drags
        pauseOnMouseEnter: true,
    },
};

document.querySelectorAll('[data-slider]').forEach((wrapper) => {
    // Per-slider options from the HTML, e.g. data-slider='{"slidesPerView": 3}'
    const custom = wrapper.dataset.slider ? JSON.parse(wrapper.dataset.slider) : {};

    const pagination = wrapper.querySelector('[data-slider-pagination]');

    new Swiper(wrapper.querySelector('.swiper'), {
        ...defaults,
        ...custom,
        navigation: {
            prevEl: wrapper.querySelector('[data-slider-prev]'),
            nextEl: wrapper.querySelector('[data-slider-next]'),
        },
        // Dots under the slider, only if the HTML has a [data-slider-pagination] element
        pagination: pagination ? { el: pagination, clickable: true } : false,
        on: {
            // Tell other scripts which slide is active now (used by resources/js/team.js)
            slideChange(swiper) {
                wrapper.dispatchEvent(new CustomEvent('slider:change', {
                    detail: { slide: swiper.slides[swiper.activeIndex] },
                }));
            },
        },
    });
});
