const { chromium } = require('playwright');
const mode = process.argv[2], path = process.argv[3] || '/';
(async () => {
  const b = await chromium.launch(); const p = await b.newPage({ viewport: { width: 1440, height: 900 } });
  const errs = []; p.on('pageerror', e => errs.push(e.message));
  if (mode === 'ref') await p.route('**/build/assets/app-*.js', r => r.fulfill({ path: process.env.REF + '/app.js', contentType: 'text/javascript' }));
  await p.goto('http://127.0.0.1:8765' + path, { waitUntil: 'networkidle' });
  const total = await p.evaluate(() => document.querySelectorAll('[data-srcset],[data-src],img.lazy').length);
  for (let y = 0; y < 20000; y += 400) { await p.evaluate(y => window.scrollTo(0, y), y); await p.waitForTimeout(150); }
  await p.waitForLoadState('networkidle'); await p.waitForTimeout(1000);
  const st = await p.evaluate(() => { const c = {}; document.querySelectorAll('img').forEach(i => { const k = (i.className || '-') + ' complete=' + (i.complete && i.naturalWidth > 0); c[k] = (c[k] || 0) + 1; }); return c; });
  console.log(mode, path, 'lazy elems', total, st, errs);
  await b.close();
})();
