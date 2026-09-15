import Swiper from 'swiper';
import { A11y, Navigation } from 'swiper/modules';
import 'swiper/css';

const initReviewsSwiper = (scope = document) => {
  scope.querySelectorAll('.b-reviews').forEach((root) => {
    const slider = root.querySelector('.reviews-swiper');
    if (!slider || slider.swiper) return;
    const fixOffset = (swiper) => {
      const lastSlide = swiper.slides[swiper.slides.length - 1];
      if (!lastSlide) return;
      swiper.params.slidesOffsetAfter = Math.max(0, swiper.width - lastSlide.offsetWidth);
      swiper.update();
    };
    new Swiper(slider, {
      modules: [Navigation, A11y],
      slidesPerView: 'auto',
      spaceBetween: 32,
      loop: false,
      grabCursor: true,
      watchOverflow: true,
      speed: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 0 : 400,
      navigation: { nextEl: root.querySelector('.__next'), prevEl: root.querySelector('.__prev') },
      a11y: {
        prevSlideMessage: 'Poprzednia opinia', nextSlideMessage: 'Następna opinia',
        firstSlideMessage: 'Pierwsza opinia', lastSlideMessage: 'Ostatnia opinia',
        slideLabelMessage: 'Opinia {{index}} z {{slidesLength}}',
      },
      on: { init: fixOffset, resize: fixOffset },
    });
  });
};

initReviewsSwiper();
if (window.acf) {
  window.acf.addAction('render_block', (el) => {
    const node = el?.[0] ?? el;
    if (node) initReviewsSwiper(node);
  });
}
export default initReviewsSwiper;
