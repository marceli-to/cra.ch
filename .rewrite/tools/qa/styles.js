// Computed styles of every element: current CSS vs a reference CSS file
const { chromium } = require('playwright');
const [path, ref] = [process.argv[2], process.argv[3]];
(async () => {
  const b = await chromium.launch();
  const grab = async (useRef) => {
    const p = await b.newPage({ viewport: { width: 1440, height: 900 } });
    if (useRef) await p.route('**/build/assets/app-*.css', r => r.fulfill({ path: ref, contentType: 'text/css' }));
    await p.route('**/build/assets/app-*.js', r => r.fulfill({ body: '', contentType: 'text/javascript' }));
    await p.goto('http://127.0.0.1:8765' + path, { waitUntil: 'networkidle' });
    const r = await p.evaluate(() => [...document.querySelectorAll('body *')].map(el => { const cs = getComputedStyle(el); const o = {}; for (const k of cs) o[k] = cs.getPropertyValue(k); return { tag: el.tagName + '.' + el.className, o }; }));
    await p.close(); return r;
  };
  const a = await grab(false), c = await grab(true);
  const seen = new Set();
  a.forEach((e, i) => { for (const k in e.o) if (e.o[k] !== c[i].o[k]) { const key = e.tag + ' ' + k; if (!seen.has(key)) { seen.add(key); console.log(path, key, '| new:', e.o[k], '| ref:', c[i].o[k]); } } });
  if (a.length !== c.length) console.log('element count differs');
  await b.close();
})();
