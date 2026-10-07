const { chromium } = require('playwright');
const path = process.argv[2] || '/login';
(async () => {
  const b = await chromium.launch(); const p = await b.newPage({ viewport: { width: 1440, height: 900 } });
  await p.goto('http://127.0.0.1:8765' + path, { waitUntil: 'networkidle' });
  const grab = () => p.evaluate(() => [...document.querySelectorAll('body *')].map((el, i) => {
    const cs = getComputedStyle(el); const o = {};
    for (const k of cs) o[k] = cs.getPropertyValue(k);
    return { tag: el.tagName + '.' + el.className, o };
  }));
  const a = await grab();
  await p.evaluate(() => { const l = document.querySelector('link[rel=stylesheet][href*="build"]'); l.href = '/assets/css/app.css'; });
  await p.waitForTimeout(800);
  const c = await grab();
  const seen = new Set();
  a.forEach((e, i) => { for (const k in e.o) if (e.o[k] !== c[i].o[k]) { const key = e.tag + ' ' + k; if (!seen.has(key)) { seen.add(key); console.log(key, '| vite:', e.o[k], '| mix:', c[i].o[k]); } } });
  await b.close();
})();
