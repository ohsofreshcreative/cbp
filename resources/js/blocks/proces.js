import Swiper from 'swiper';
import { A11y, Pagination, Navigation } from 'swiper/modules';

import 'swiper/css';
import 'swiper/css/pagination';
import 'swiper/css/navigation';

export function initProces(scope = document) {
  scope.querySelectorAll('.b-proces').forEach((root) => {
    const slider = root.querySelector('.__slider');
    if (!slider || slider.swiper) return;

    const progressFill = root.querySelector('.__progress-fill');

    const fixOffset = (swiper) => {
      const slideW = swiper.slides[0]?.offsetWidth ?? 0;
      if (!slideW) return;
      const n = swiper.slides.length;
      const gap = Number(swiper.params.spaceBetween) || 0;
      const currentMax = n * slideW + (n - 1) * gap - swiper.width;
      const idealLast = (n - 1) * (slideW + gap);
      swiper.params.slidesOffsetAfter = Math.max(0, idealLast - currentMax);
      swiper.update();
    };

    const updateProgress = (swiper) => {
      if (!progressFill || !swiper.slides.length) return;
      const pct = ((swiper.activeIndex + 1) / swiper.slides.length) * 100;
      progressFill.style.width = pct + '%';
    };

    new Swiper(slider, {
      modules: [A11y, Pagination, Navigation],
      slidesPerView: 'auto',
      spaceBetween: 32,
      loop: false,
      grabCursor: true,
      watchOverflow: true,
      speed: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 0 : 400,
      navigation: {
        prevEl: root.querySelector('.__prev'),
        nextEl: root.querySelector('.__next'),
      },
      on: {
        init(swiper) {
          fixOffset(swiper);
          updateProgress(swiper);
        },
        slideChange: updateProgress,
        resize: fixOffset,
      },
      a11y: {
        prevSlideMessage: 'Poprzedni etap',
        nextSlideMessage: 'Następny etap',
        firstSlideMessage: 'Pierwszy etap',
        lastSlideMessage: 'Ostatni etap',
        slideLabelMessage: 'Etap {{index}} z {{slidesLength}}',
      },
    });
  });
}

initProces();
