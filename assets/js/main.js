/* Caerle'on – main.js (v2) */
(() => {
  'use strict';

  // Loader
  const loader = document.getElementById('loader');
  const hideLoader = () => loader && loader.classList.add('is-hidden');
  if (document.readyState === 'complete') {
    setTimeout(hideLoader, 600);
  } else {
    window.addEventListener('load', () => setTimeout(hideLoader, 600));
  }

  // Sticky header state
  const header = document.getElementById('siteHeader');
  const cta = document.querySelector('.floating-cta');
  const onScroll = () => {
    const y = window.scrollY;
    if (header) header.classList.toggle('is-scrolled', y > 20);
    if (cta) cta.classList.toggle('is-visible', y > window.innerHeight * 0.6);
  };
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  // Mobile nav
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

  // Reveal on scroll
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

  // Hero slideshow + dots
  const slides = document.querySelectorAll('.hero__slide');
  const dots = document.querySelectorAll('.hero__dot');
  if (slides.length > 1) {
    let idx = 0;
    const interval = 6500;
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

  // Year
  const y = document.getElementById('year');
  if (y) y.textContent = new Date().getFullYear();
})();
