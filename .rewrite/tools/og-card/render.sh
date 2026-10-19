#!/bin/sh
# Renders og.template.html to public/assets/img/og.jpg (1200 × 630) with headless Chrome
set -e
cd "$(dirname "$0")"
ROOT=../../..
CHROME="/Applications/Google Chrome.app/Contents/MacOS/Google Chrome"
python3 -I -c 'import sys; t=open(sys.argv[1]).read(); l=open(sys.argv[2]).read(); open(sys.argv[3],"w").write(t.replace("{{LOGO}}", l))' og.template.html "$ROOT/resources/views/components/icons/logo.blade.php" og.html
PROFILE=$(mktemp -d)
"$CHROME" --headless=new --user-data-dir="$PROFILE" --no-first-run --allow-file-access-from-files --virtual-time-budget=3000 --timeout=10000 --disable-gpu --hide-scrollbars --force-device-scale-factor=1 --window-size=1200,630 --screenshot="$PWD/og.png" "file://$PWD/og.html" 2>/dev/null
sips -s format jpeg -s formatOptions 88 og.png --out "$ROOT/public/assets/img/og.jpg" >/dev/null
rm -rf og.png og.html "$PROFILE"
sips -g pixelWidth -g pixelHeight "$ROOT/public/assets/img/og.jpg" | tail -2
