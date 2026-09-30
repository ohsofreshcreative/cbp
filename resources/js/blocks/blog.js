document.addEventListener('DOMContentLoaded', () => {
  const headings = document.querySelectorAll('.b-blog-single h1[id], .b-blog-single h2[id], .b-blog-single h3[id], .b-blog-single h4[id]');
  const tocLinks = document.querySelectorAll('.b-blog-single .toc ul li a');

  if (!headings.length || !tocLinks.length) {
    return;
  }

  const setActive = (id) => {
    tocLinks.forEach((link) => {
      link.parentNode.classList.toggle('active', link.getAttribute('href') === `#${id}`);
    });
  };

  let visibleIds = [];

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        if (!visibleIds.includes(entry.target.id)) visibleIds.push(entry.target.id);
      } else {
        visibleIds = visibleIds.filter((id) => id !== entry.target.id);
      }
    });

    if (visibleIds.length) {
      setActive(visibleIds[0]);
    }
  }, {
    rootMargin: '0px 0px -70% 0px',
    threshold: 0,
  });

  headings.forEach((heading) => observer.observe(heading));
  setActive(headings[0].id);
});
