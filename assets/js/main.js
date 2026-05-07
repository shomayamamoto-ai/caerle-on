/* Caerle'on – main.js (v3) */
(() => {
  'use strict';

  // ---------- Loader ----------
  const loader = document.getElementById('loader');
  const hideLoader = () => loader && loader.classList.add('is-hidden');
  if (document.readyState === 'complete') {
    setTimeout(hideLoader, 600);
  } else {
    window.addEventListener('load', () => setTimeout(hideLoader, 600));
  }

  // ---------- Sticky header & floating CTA ----------
  const header = document.getElementById('siteHeader');
  const cta = document.querySelector('.floating-cta');
  const onScroll = () => {
    const y = window.scrollY;
    if (header) header.classList.toggle('is-scrolled', y > 20);
    if (cta) cta.classList.toggle('is-visible', y > window.innerHeight * 0.6);
  };
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  // ---------- Mobile nav ----------
  const toggle = document.querySelector('.nav__toggle');
  const mobileNav = document.getElementById('mobileNav');
  if (toggle && mobileNav) {
    const setOpen = (open) => {
      toggle.setAttribute('aria-expanded', String(open));
      mobileNav.setAttribute('aria-hidden', String(!open));
      mobileNav.classList.toggle('is-open', open);
      document.body.style.overflow = open ? 'hidden' : '';
    };
    toggle.addEventListener('click', () => {
      setOpen(toggle.getAttribute('aria-expanded') !== 'true');
    });
    mobileNav.querySelectorAll('a').forEach(a => a.addEventListener('click', () => setOpen(false)));
  }

  // ---------- Reveal on scroll ----------
  const items = document.querySelectorAll('[data-reveal]');
  if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver((entries) => {
      entries.forEach(en => {
        if (en.isIntersecting) {
          en.target.classList.add('is-in');
          io.unobserve(en.target);
        }
      });
    }, { rootMargin: '0px 0px -10% 0px', threshold: 0.08 });
    items.forEach(el => io.observe(el));
  } else {
    items.forEach(el => el.classList.add('is-in'));
  }

  // ---------- Hero slideshow + dots ----------
  const slides = document.querySelectorAll('.hero__slide');
  const dots = document.querySelectorAll('.hero__dot');
  if (slides.length > 1) {
    let idx = 0;
    const interval = 7200;
    let timer;
    const go = (i) => {
      slides[idx].classList.remove('is-active');
      if (dots[idx]) dots[idx].classList.remove('is-active');
      idx = (i + slides.length) % slides.length;
      slides[idx].classList.add('is-active');
      if (dots[idx]) dots[idx].classList.add('is-active');
    };
    const start = () => { timer = setInterval(() => go(idx + 1), interval); };
    const stop = () => clearInterval(timer);
    dots.forEach((d, i) => d.addEventListener('click', () => { stop(); go(i); start(); }));
    start();
    document.addEventListener('visibilitychange', () => {
      if (document.hidden) stop(); else start();
    });
  }

  // ---------- Gallery Lightbox ----------
  const lightbox = document.getElementById('lightbox');
  if (lightbox) {
    const lbImg = lightbox.querySelector('img');
    const lbCap = lightbox.querySelector('figcaption');
    const closeBtn = lightbox.querySelector('.lightbox__close');
    const prevBtn = lightbox.querySelector('.lightbox__nav--prev');
    const nextBtn = lightbox.querySelector('.lightbox__nav--next');
    const gItems = Array.from(document.querySelectorAll('.gallery__item img'));
    let cur = 0;
    const open = (i) => {
      cur = i;
      const im = gItems[cur];
      lbImg.src = im.src;
      lbImg.alt = im.alt || '';
      lbCap.textContent = im.alt || '';
      lightbox.classList.add('is-open');
      lightbox.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';
    };
    const close = () => {
      lightbox.classList.remove('is-open');
      lightbox.setAttribute('aria-hidden', 'true');
      document.body.style.overflow = '';
    };
    const step = (n) => open((cur + n + gItems.length) % gItems.length);
    gItems.forEach((im, i) => im.parentElement.addEventListener('click', (e) => {
      e.preventDefault(); open(i);
    }));
    closeBtn.addEventListener('click', close);
    prevBtn.addEventListener('click', () => step(-1));
    nextBtn.addEventListener('click', () => step(1));
    lightbox.addEventListener('click', (e) => { if (e.target === lightbox) close(); });
    document.addEventListener('keydown', (e) => {
      if (!lightbox.classList.contains('is-open')) return;
      if (e.key === 'Escape') close();
      if (e.key === 'ArrowLeft') step(-1);
      if (e.key === 'ArrowRight') step(1);
    });
  }

  // ---------- Custom Cursor ----------
  const cursor = document.getElementById('cursor');
  if (cursor && window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
    let x = window.innerWidth / 2, y = window.innerHeight / 2;
    let rx = x, ry = y;
    const dot = cursor.querySelector('.cursor__dot');
    const ring = cursor.querySelector('.cursor__ring');
    document.addEventListener('mousemove', (e) => { x = e.clientX; y = e.clientY; });
    const tick = () => {
      rx += (x - rx) * 0.22;
      ry += (y - ry) * 0.22;
      dot.style.transform = `translate(${x}px, ${y}px) translate(-50%, -50%)`;
      ring.style.transform = `translate(${rx}px, ${ry}px) translate(-50%, -50%)`;
      requestAnimationFrame(tick);
    };
    tick();
    const hoverable = 'a, button, .gallery__item, input, textarea, label, [data-cursor]';
    document.addEventListener('mouseover', (e) => {
      if (e.target.closest(hoverable)) cursor.classList.add('is-hover');
    });
    document.addEventListener('mouseout', (e) => {
      if (e.target.closest(hoverable)) cursor.classList.remove('is-hover');
    });
  }

  // ---------- Magnetic buttons ----------
  if (window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
    document.querySelectorAll('.btn').forEach(btn => {
      const inner = btn.querySelector('span') || btn;
      btn.addEventListener('mousemove', (e) => {
        const r = btn.getBoundingClientRect();
        const mx = e.clientX - r.left - r.width / 2;
        const my = e.clientY - r.top - r.height / 2;
        inner.style.transform = `translate(${mx * 0.18}px, ${my * 0.25}px)`;
      });
      btn.addEventListener('mouseleave', () => { inner.style.transform = ''; });
    });
  }

  // ---------- Year ----------
  const y = document.getElementById('year');
  if (y) y.textContent = new Date().getFullYear();
})();
