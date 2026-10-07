const { chromium } = require('playwright');
const out = process.argv[2];
const base = 'http://127.0.0.1:8765';
(async () => {
  const fs = require('fs'); fs.mkdirSync(out, { recursive: true });
  const b = await chromium.launch();
  const html = await (await fetch(base + '/werkliste')).text();
  const projects = [...new Set([...html.matchAll(/href="(?:https?:\/\/[^"\/]+)?(\/projekt\/[^"]+)"/g)].map(m => m[1]))].slice(0, 6);
  const pages = ['/', '/leistungen', '/kontakt', '/werkliste', '/ueber-uns/team', '/ueber-uns/tagebuch', '/login', ...projects];
  for (const w of [1440, 390]) {
    const ctx = await b.newContext({ viewport: { width: w, height: 900 } });
    const p = await ctx.newPage();
    const errs = [];
    p.on('pageerror', e => errs.push(e.message));
    p.on('console', m => m.type() === 'error' && errs.push(m.text()));
    p.on('response', r => r.status() >= 400 && errs.push(r.status() + ' ' + r.url()));
    for (const path of pages) {
      await p.goto(base + path, { waitUntil: 'networkidle' });
      // scroll through so lazy images load
      await p.evaluate(async () => { for (let y = 0; y < document.body.scrollHeight; y += 600) { window.scrollTo(0, y); await new Promise(r => setTimeout(r, 60)); } window.scrollTo(0, 0); });
      await p.waitForLoadState('networkidle');
      await p.waitForTimeout(300);
      await p.screenshot({ path: `${out}/${w}${path.replace(/\//g, '_') || '_'}.png`, fullPage: true });
    }
    console.log(w, 'errors:', errs.length ? errs : 'none');
    await ctx.close();
  }
  await b.close();
})();
