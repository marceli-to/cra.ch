# Map each cached production crop to the URL params that produced it
import hashlib, json, os, subprocess, sys
root = sys.argv[1]  # .rewrite/data/public/cache/crop
rows = subprocess.run(['mysql','-uroot','-h127.0.0.1','-P3306','cristinarutz_snapshot','-N','-e',
  "SELECT name, coords_w, coords_h, coords_x, coords_y FROM images"], capture_output=True, text=True).stdout.splitlines()
coords = {}
for r in rows:
    n, w, h, x, y = r.split('\t')
    f = [int(float(v)) if v != 'NULL' else 0 for v in (w, h, x, y)]
    c = '0,0,0,0' if not (f[0] and f[1]) else ','.join(map(str, f))
    coords.setdefault(n, set()).add(c)
sizes = [None, 900, 1000, 1200, 1500, 1600, 2000, 2400, 2600]
def h(p): return hashlib.md5(json.dumps(p, separators=(',', ':')).encode()).hexdigest()
found, missing = [], 0
for d, _, fs in os.walk(root):
    for f in fs:
        key = os.path.relpath(d, root).replace('/', '')
        hit = None
        for c in list(coords.get(f, [])) + [None]:
            for s in sizes:
                for ratio in (None, '3x2'):
                    if h({'maxSize': s, 'coords': c, 'ratio': ratio}) == key:
                        hit = (s, c, ratio)
        if hit: found.append((os.path.join(d, f), f) + hit)
        else: missing += 1
print(json.dumps(found))
print(f'matched {len(found)}, unmatched {missing}', file=sys.stderr)
