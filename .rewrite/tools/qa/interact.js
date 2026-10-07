const { chromium } = require('playwright');
const mode = process.argv[2];
const base = 'http://127.0.0.1:8765';
(async () => {
  const b = await chromium.launch(); const p = await b.newPage({ viewport: { width: 1440, height: 900 } });
  const errs = []; p.on('pageerror', e => errs.push(e.message));
  if (mode === 'mix') await p.route('**/build/assets/app-*.js', r => r.fulfill({ path: '/Users/marceli.to/Jamon.digital/Webroot/cristinarutz.ch/public/assets/js/app.js', contentType: 'text/javascript' }));
  const log = (...a) => console.log(mode, ...a);
  const cls = s => p.evaluate(s => [...document.querySelectorAll(s)].map(e => e.className).join(' | '), s);

  await p.goto(base + '/projekt/tossbrucke-fur-hauptzulauf', { waitUntil: 'networkidle' });
  await p.click('[data-menu-btn]'); await p.waitForTimeout(300);
  log('menu open:', await cls('[data-menu]'), '/ btn', await cls('[data-menu-btn]'));
  const parent = await p.$('[data-menu-parent]');
  if (parent) { await parent.click(); await p.waitForTimeout(200); log('submenu:', await p.evaluate(() => document.querySelector('[data-menu-parent]').nextElementSibling.className)); }
  await p.click('[data-menu-btn]'); await p.waitForTimeout(300);
  log('menu closed:', await cls('[data-menu]'));

  const more = await p.$('[data-more]:visible');
  if (more) { await more.click(); await p.waitForTimeout(200); log('after mehr: more visible', await more.isVisible(), 'less visible', await p.isVisible('[data-less]')); await p.click('[data-less]'); await p.waitForTimeout(200); log('after weniger: more visible', await more.isVisible()); }
  const info = await p.$('[data-btn-project-info]:visible');
  if (info) { await info.evaluate(e => e.click()); await p.waitForTimeout(200); log('info:', await cls('[data-project-info]')); }

  await p.click('[data-fancybox="gallery"] >> nth=1'); await p.waitForTimeout(1200);
  log('lightbox:', await p.evaluate(() => { const c = document.querySelector('.fancybox-container,.fancybox__container'); return c ? c.className + ' caption=' + (document.querySelector('.fancybox-caption')||{}).innerText : 'none'; }));
  await p.screenshot({ path: `/tmp/cr-qa/lb-${mode}-1.png` });
  await p.click('.btn-fancybox-next'); await p.waitForTimeout(1000);
  log('after next:', await p.evaluate(() => (document.querySelector('.fancybox-caption')||{}).innerText), 'prev', await cls('.btn-fancybox-prev'));
  await p.screenshot({ path: `/tmp/cr-qa/lb-${mode}-2.png` });
  await p.keyboard.press('Escape'); await p.waitForTimeout(1000);
  log('after esc:', await p.evaluate(() => !!document.querySelector('.fancybox-container')));

  await p.goto(base + '/kontakt', { waitUntil: 'networkidle' });
  const before = await cls('[data-imprint]'); await p.click('[data-btn-imprint]'); await p.waitForTimeout(200);
  log('imprint:', before, '->', await cls('[data-imprint]'));
  log('errors', errs);
  await b.close();
})();
