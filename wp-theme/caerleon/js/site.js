const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('visible');
      observer.unobserve(entry.target);
    }
  });
}, { threshold: 0.12 });

document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));

// ヘッダーのスクロール検知 + 進捗バー + 固定 CTA / ページトップ
const siteHeader = document.getElementById('siteHeader');
const progressFill = document.getElementById('progressFill');
const stickyCta = document.getElementById('stickyCta');
const pageTop = document.getElementById('pageTop');

window.addEventListener('scroll', () => {
  const scroll = window.scrollY;
  siteHeader.classList.toggle('scrolled', scroll > 80);

  if (progressFill) {
    const max = document.documentElement.scrollHeight - window.innerHeight;
    progressFill.style.width = (max > 0 ? Math.min(100, (scroll / max) * 100) : 0) + '%';
  }

  const past = scroll > window.innerHeight * 0.7;
  if (stickyCta) stickyCta.classList.toggle('visible', past);
  if (pageTop) pageTop.classList.toggle('visible', past);
}, { passive: true });

// モバイルメニュー
const navToggle = document.querySelector('.nav-toggle');
const mobileMenu = document.getElementById('mobileMenu');
if (navToggle && mobileMenu) {
  const setOpen = (open) => {
    navToggle.setAttribute('aria-expanded', String(open));
    mobileMenu.setAttribute('aria-hidden', String(!open));
    mobileMenu.classList.toggle('open', open);
    document.body.style.overflow = open ? 'hidden' : '';
  };
  navToggle.addEventListener('click', () => {
    setOpen(navToggle.getAttribute('aria-expanded') !== 'true');
  });
  mobileMenu.querySelectorAll('a').forEach(a => a.addEventListener('click', () => setOpen(false)));

  // 閉じる手段を増やす（何もない所をタップ / Esc キー）
  mobileMenu.addEventListener('click', (e) => {
    if (e.target === mobileMenu) setOpen(false);
  });
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && navToggle.getAttribute('aria-expanded') === 'true') setOpen(false);
  });
}


// リロード時は最上部から表示（URL にアンカーがある場合はその位置へ）
window.addEventListener('load', () => {
  const html = document.documentElement;
  const prev = html.style.scrollBehavior;
  html.style.scrollBehavior = 'auto';   // smooth を一時停止して確実に移動
  if (!location.hash) {
    window.scrollTo(0, 0);
  } else {
    const target = document.querySelector(location.hash);
    if (target) target.scrollIntoView();
  }
  requestAnimationFrame(() => { html.style.scrollBehavior = prev; });
});

// ページトップ
if (pageTop) {
  pageTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
}

function initGallerySlider() {
  const track = document.getElementById('galleryGrid');
  const dotsEl = document.getElementById('galleryDots');
  const prev = document.getElementById('galleryPrev');
  const next = document.getElementById('galleryNext');
  if (!track || !dotsEl || !prev || !next) return;

  const items = Array.from(track.querySelectorAll('.gallery__item'));
  dotsEl.innerHTML = items.map((_, i) =>
    `<button class="gallery__dot" type="button" data-i="${i}" aria-label="${i + 1} 枚目へ"></button>`
  ).join('');
  const dots = Array.from(dotsEl.querySelectorAll('.gallery__dot'));

  // 最後の写真は左端まで送れない（右端で止まる）ため、
  // 写真の位置ではなくスクロール量の割合でドットと対応させる
  const lastIndex = () => items.length - 1;
  const maxScroll = () => Math.max(1, track.scrollWidth - track.clientWidth);

  const current = () => {
    if (lastIndex() < 1) return 0;
    const ratio = track.scrollLeft / maxScroll();
    return Math.max(0, Math.min(lastIndex(), Math.round(ratio * lastIndex())));
  };

  const sync = () => {
    const i = current();
    dots.forEach((d, n) => d.setAttribute('aria-current', String(n === i)));
    prev.disabled = track.scrollLeft <= 1;
    next.disabled = track.scrollLeft >= maxScroll() - 1;
  };

  const goTo = i => {
    if (lastIndex() < 1) return;
    const n = Math.max(0, Math.min(lastIndex(), i));
    track.scrollTo({ left: (maxScroll() * n) / lastIndex(), behavior: 'smooth' });
  };

  prev.addEventListener('click', () => goTo(current() - 1));
  next.addEventListener('click', () => goTo(current() + 1));
  dots.forEach(d => d.addEventListener('click', () => goTo(Number(d.dataset.i))));

  let raf = 0;
  track.addEventListener('scroll', () => {
    cancelAnimationFrame(raf);
    raf = requestAnimationFrame(sync);
  }, { passive: true });
  window.addEventListener('resize', sync);

  // 写真が 1 枚だけのときは操作要素を隠す
  const solo = items.length < 2;
  dotsEl.style.display = solo ? 'none' : '';
  prev.style.display = solo ? 'none' : '';
  next.style.display = solo ? 'none' : '';

  sync();
}

initGallerySlider();
