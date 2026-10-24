// Site Navigation & Interactive Features
document.addEventListener('DOMContentLoaded', function () {
  // 1. Mobile Menu Toggle
  const toggle = document.querySelector('.menu-toggle');
  const nav = document.querySelector('.main-nav');
  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      const open = nav.classList.toggle('open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
    document.querySelectorAll('.main-nav a').forEach(function (link) {
      link.addEventListener('click', function () {
        nav.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
      });
    });
  }

  // 2. Copyright Year Safe Update
  const yearEl = document.getElementById('year');
  if (yearEl) {
    yearEl.textContent = new Date().getFullYear();
  }

  // 3. Interactive Photo Gallery Lightbox with Prev / Next & Keyboard Navigation
  const lightbox = document.getElementById('siteLightbox') || document.querySelector('.lightbox');
  if (!lightbox) return;

  const lightboxImg = lightbox.querySelector('#lightboxImg') || lightbox.querySelector('.lightbox-img') || lightbox.querySelector('img');
  const captionEl = lightbox.querySelector('#lightboxCaption');
  const counterEl = lightbox.querySelector('#lightboxCounter');
  const closeBtn = lightbox.querySelector('.lightbox-close') || lightbox.querySelector('button');
  const prevBtn = lightbox.querySelector('.lightbox-prev');
  const nextBtn = lightbox.querySelector('.lightbox-next');

  let galleryItems = [];
  let currentIndex = 0;

  function collectItems() {
    galleryItems = [];
    const elements = document.querySelectorAll('.gallery-item, .gallery-card');
    elements.forEach(function (el, idx) {
      const img = el.querySelector('img');
      const src = el.getAttribute('data-src') || (img ? img.src : '');
      const caption = el.getAttribute('data-title') ||
                      (el.querySelector('strong') ? el.querySelector('strong').textContent.trim() : '') ||
                      (img ? img.alt : '');
      if (src) {
        galleryItems.push({ src: src, caption: caption });
        el.setAttribute('data-gallery-idx', idx);
      }
    });
  }

  function showImage(index) {
    if (!galleryItems.length) collectItems();
    if (!galleryItems.length) return;

    if (index < 0) index = galleryItems.length - 1;
    if (index >= galleryItems.length) index = 0;
    currentIndex = index;

    const item = galleryItems[currentIndex];
    if (lightboxImg) {
      lightboxImg.style.opacity = '0';
      lightboxImg.src = item.src;
      lightboxImg.alt = item.caption || '';
      lightboxImg.onload = function () {
        lightboxImg.style.opacity = '1';
      };
      // In case cached and already loaded
      setTimeout(function () {
        lightboxImg.style.opacity = '1';
      }, 50);
    }
    if (captionEl) {
      captionEl.textContent = item.caption || '';
    }
    if (counterEl) {
      counterEl.textContent = (currentIndex + 1) + ' / ' + galleryItems.length;
    }

    lightbox.classList.add('open');
    lightbox.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden'; // prevent page scrolling while modal is open
  }

  function closeLightbox() {
    lightbox.classList.remove('open');
    lightbox.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    if (lightboxImg) {
      lightboxImg.src = '';
    }
  }

  // Open on card or item click
  document.addEventListener('click', function (e) {
    const target = e.target.closest('.gallery-item, .gallery-card');
    if (target) {
      collectItems();
      const idx = parseInt(target.getAttribute('data-gallery-idx'), 10);
      showImage(isNaN(idx) ? 0 : idx);
    }
  });

  // Global window methods
  window.openSiteLightbox = function (src, title) {
    collectItems();
    const foundIdx = galleryItems.findIndex(function (it) {
      return it.src === src;
    });
    if (foundIdx !== -1) {
      showImage(foundIdx);
    } else {
      galleryItems.push({ src: src, caption: title || '' });
      showImage(galleryItems.length - 1);
    }
  };
  window.showModalImage = window.openSiteLightbox;

  // Navigation button listeners
  if (closeBtn) {
    closeBtn.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      closeLightbox();
    });
  }
  if (prevBtn) {
    prevBtn.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      showImage(currentIndex - 1);
    });
  }
  if (nextBtn) {
    nextBtn.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      showImage(currentIndex + 1);
    });
  }

  // Close when clicking outside image
  lightbox.addEventListener('click', function (e) {
    if (e.target === lightbox || e.target.classList.contains('lightbox-stage')) {
      closeLightbox();
    }
  });

  // Keyboard navigation (Esc to close, Left Arrow for Prev, Right Arrow for Next)
  document.addEventListener('keydown', function (e) {
    if (!lightbox.classList.contains('open')) return;
    if (e.key === 'Escape') {
      closeLightbox();
    } else if (e.key === 'ArrowLeft') {
      showImage(currentIndex - 1);
    } else if (e.key === 'ArrowRight') {
      showImage(currentIndex + 1);
    }
  });

  // Initial collect
  collectItems();
});
