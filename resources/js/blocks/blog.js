document.addEventListener('DOMContentLoaded', () => {
  const headings = document.querySelectorAll('.b-blog-single h1[id], .b-blog-single h2[id], .b-blog-single h3[id], .b-blog-single h4[id]');
  const tocLinks = document.querySelectorAll('.b-blog-single .toc ul li a');

  if (!headings.length || !tocLinks.length) {
    return;
  }

  const updateActiveLink = () => {
    headings.forEach((heading) => {
      const headingTop = heading.getBoundingClientRect().top;
      const windowHeight = window.innerHeight;

      if (headingTop < windowHeight - 300) {
        tocLinks.forEach((link) => {
          link.parentNode.classList.remove('active');
        });

        const activeLink = document.querySelector(`.b-blog-single .toc ul li a[href="#${heading.id}"]`);
        if (activeLink) {
          activeLink.parentNode.classList.add('active');
        }
      }
    });
  };

  updateActiveLink();
  window.addEventListener('scroll', updateActiveLink);
});
