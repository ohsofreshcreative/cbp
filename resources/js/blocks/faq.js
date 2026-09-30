document.querySelectorAll('.b-faq .__item').forEach((item) => {
  const summary = item.querySelector('.__summary');
  const content = item.querySelector('.__content');

  if (!summary || !content) return;

  summary.addEventListener('click', (e) => {
    e.preventDefault();

    if (item.hasAttribute('open')) {
      content.style.height = `${content.scrollHeight}px`;

      requestAnimationFrame(() => {
        content.style.height = '0px';
      });

      content.addEventListener('transitionend', function onClose() {
        item.removeAttribute('open');
        content.style.height = '';
        content.removeEventListener('transitionend', onClose);
      }, { once: true });
    } else {
      item.setAttribute('open', '');
      content.style.height = '0px';

      requestAnimationFrame(() => {
        content.style.height = `${content.scrollHeight}px`;
      });

      content.addEventListener('transitionend', function onOpen() {
        content.style.height = 'auto';
        content.removeEventListener('transitionend', onOpen);
      }, { once: true });
    }
  });
});
