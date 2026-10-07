const { chromium } = require('playwright');
const mode = process.argv[2];               // v3 | new
const base = 'http://127.0.0.1:8765';
const sel = mode === 'v3' ? '[data-fancybox="gallery"]' : '[data-lightbox]';
(async () => {
  const b = await chromium.launch();
  for (const vp of [{ width: 1440, height: 900 }, { width: 390, height: 844, isMobile: true, hasTouch: true }]) {
    const ctx = await b.newContext({ viewport: { width: vp.width, height: vp.height }, isMobile: !!vp.isMobile, hasTouch: !!vp.hasTouch });
    const p = await ctx.newPage();
    const errs = []; p.on('pageerror', e => errs.push(e.message));
    if (mode === 'v3') await p.route('**/build/assets/app-*.js', r => r.fulfill({ path: process.env.REF + '/app.js', contentType: 'text/javascript' }));
    for (const [path, idx] of [['/projekt/wohnhaus-mottelistrasse', 0], ['/projekt/wohnhaus-mottelistrasse', 5], ['/ueber-uns/tagebuch', null]]) {
      await p.goto(base + path, { waitUntil: 'networkidle' });
      let i = idx;
      if (i === null) i = await p.evaluate(s => [...document.querySelectorAll(s)].findIndex(a => a.dataset.caption), sel);
      await p.evaluate(([s, i]) => document.querySelectorAll(s)[i].click(), [sel, i]);
      await p.waitForTimeout(1500);
      const r = await p.evaluate(() => {
        const rect = s => { const e = document.querySelector(s); if (!e) return null; const r = e.getBoundingClientRect(); return [r.x, r.y, r.width, r.height].map(Math.round).join(','); };
        const img = document.querySelector('.fancybox-slide--current img, .lightbox img.is-current, .lightbox__image');
        return {
          img: img ? (() => { const r = img.getBoundingClientRect(); return [r.x, r.y, r.width, r.height].map(Math.round).join(','); })() : null,
          src: img && img.currentSrc.split('/').pop().slice(0, 40),
          caption: (document.querySelector('.fancybox-caption, .lightbox__caption') || {}).innerText,
          captionRect: rect('.fancybox-caption, .lightbox__caption'),
          close: rect('.btn-fancybox-close, .lightbox__close'), prev: rect('.btn-fancybox-prev, .lightbox__prev'), next: rect('.btn-fancybox-next, .lightbox__next'),
          prevCls: (document.querySelector('.btn-fancybox-prev, .lightbox__prev') || {}).className,
          htmlOverflow: getComputedStyle(document.body).overflow, scrollY: window.scrollY,
        };
      });
      console.log(mode, vp.width, path, i, JSON.stringify(r));
      await p.screenshot({ path: `${process.env.OUT || '/tmp'}/lbm-${mode}-${vp.width}-${path.split('/').pop()}-${i}.png` });
      // keyboard next, then swipe back (touch only) / Esc
      await p.keyboard.press('ArrowRight'); await p.waitForTimeout(900);
      const afterKey = await p.evaluate(() => (document.querySelector('.fancybox-caption, .lightbox__caption') || {}).innerText);
      let afterSwipe = '-';
      if (vp.hasTouch) {
        const cdp = await ctx.newCDPSession(p);
        const y = 400, steps = 8;
        await cdp.send('Input.dispatchTouchEvent', { type: 'touchStart', touchPoints: [{ x: 100, y }] });
        for (let s = 1; s <= steps; s++) { await cdp.send('Input.dispatchTouchEvent', { type: 'touchMove', touchPoints: [{ x: 100 + s * 25, y }] }); await p.waitForTimeout(16); }
        await cdp.send('Input.dispatchTouchEvent', { type: 'touchEnd', touchPoints: [] });
        await p.waitForTimeout(1000);
        afterSwipe = await p.evaluate(() => (document.querySelector('.fancybox-caption, .lightbox__caption') || {}).innerText);
      }
      await p.keyboard.press('Escape'); await p.waitForTimeout(900);
      const open = await p.evaluate(() => !!document.querySelector('.fancybox-container, .lightbox.is-open'));
      console.log('   key→', JSON.stringify(afterKey), 'swipe→', JSON.stringify(afterSwipe), 'open after Esc', open);
    }
    console.log('   errors', errs);
    await ctx.close();
  }
  await b.close();
})();
