const { chromium } = require('playwright');
const base = 'http://127.0.0.1:8765';
(async () => {
  const b = await chromium.launch(); const p = await b.newPage({ viewport: { width: 1440, height: 900 } });
  const errs = []; p.on('pageerror', e => errs.push(e.message)); p.on('console', m => m.type() === 'error' && errs.push(m.text()));
  const log = (...a) => console.log(...a);
  const cls = s => p.evaluate(s => [...document.querySelectorAll(s)].map(e => e.className).join(' | '), s);
  const cap = () => p.evaluate(() => document.querySelector('.lightbox__caption')?.innerText);
  const isOpen = () => p.evaluate(() => !!document.querySelector('.lightbox.is-open') && !document.querySelector('.lightbox').hidden);

  await p.goto(base + '/projekt/tossbrucke-fur-hauptzulauf', { waitUntil: 'networkidle' });
  await p.click('[data-menu-btn]'); await p.waitForTimeout(300);
  log('menu open:', await cls('[data-menu]'), '/ btn', await cls('[data-menu-btn]'));
  await p.click('[data-menu-btn]'); await p.waitForTimeout(300);
  log('menu closed:', await cls('[data-menu]'));
  const more = await p.$('[data-more]:visible');
  await more.click(); await p.waitForTimeout(200);
  log('after mehr: more visible', await more.isVisible(), 'less visible', await p.isVisible('[data-less]'));
  await p.click('[data-less]'); await p.waitForTimeout(200);
  log('after weniger: more visible', await more.isVisible(), 'less visible', await p.isVisible('[data-less]'));
  await (await p.$('[data-btn-project-info]')).evaluate(e => e.click()); await p.waitForTimeout(200);
  log('info:', await cls('[data-project-info]'));
  const browse = await p.$('[data-browse-btn]');
  if (browse) { await browse.evaluate(e => e.dispatchEvent(new MouseEvent('mouseover'))); log('browse preview:', await p.evaluate(() => { const e = document.querySelector('[data-browse-preview]'); return e.className + ' "' + e.textContent + '"'; })); await browse.evaluate(e => e.dispatchEvent(new MouseEvent('mouseout'))); log('browse preview out:', await cls('[data-browse-preview]')); }

  // Lightbox
  await p.evaluate(() => window.scrollTo(0, 600)); await p.waitForTimeout(200);
  const link = p.locator('[data-lightbox]').nth(0);
  await link.evaluate(e => e.click()); await p.waitForTimeout(800);
  log('open:', await isOpen(), await cap(), 'focus:', await p.evaluate(() => document.activeElement.className), 'html:', await p.evaluate(() => document.documentElement.className));
  await p.click('.lightbox__prev'); await p.waitForTimeout(300);
  log('prev at first:', await cap());
  await p.click('.lightbox__next'); await p.waitForTimeout(800);
  log('next:', await cap(), 'imgs in stage', await p.evaluate(() => document.querySelectorAll('.lightbox__stage img').length));
  await p.click('.lightbox__stage img'); await p.waitForTimeout(500);
  log('click on image keeps it open:', await isOpen());
  await p.keyboard.press('Tab'); await p.keyboard.press('Tab'); await p.keyboard.press('Tab'); await p.keyboard.press('Tab');
  log('focus after 4×Tab:', await p.evaluate(() => document.activeElement.className));
  await p.mouse.click(90, 200); await p.waitForTimeout(600);
  log('click beside image closes:', !(await isOpen()), 'scrollY', await p.evaluate(() => scrollY), 'html:', JSON.stringify(await p.evaluate(() => document.documentElement.className)), 'focus back on link:', await p.evaluate(() => !!document.activeElement.closest('[data-lightbox]')));
  // last image: next inactive
  const n = await p.locator('[data-lightbox]').count();
  await p.locator('[data-lightbox]').nth(n - 1).evaluate(e => e.click()); await p.waitForTimeout(800);
  log('last:', await cap(), await cls('.lightbox__next'));
  await p.keyboard.press('ArrowRight'); await p.waitForTimeout(300);
  log('right at last:', await cap());
  await p.click('.lightbox__close'); await p.waitForTimeout(600);
  log('close button:', !(await isOpen()));

  await p.goto(base + '/kontakt', { waitUntil: 'networkidle' });
  const before = await cls('[data-imprint]'); await p.click('[data-btn-imprint]'); await p.waitForTimeout(200);
  log('imprint:', before, '->', await cls('[data-imprint]'));
  log('contact lightbox links:', await p.locator('[data-lightbox]').count());
  log('errors', errs);
  await b.close();
})();
