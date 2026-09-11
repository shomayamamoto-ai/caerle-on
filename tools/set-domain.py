#!/usr/bin/env python3
"""サイト内に書かれている公開アドレスを、指定したドメインに置き換える。

使い方:
    python3 tools/set-domain.py https://example.com/
"""
import re
import sys
from pathlib import Path

TARGETS = ['index.html', 'sitemap.xml', 'robots.txt', 'llms.txt']
CURRENT = re.compile(r'https://[a-z0-9.-]+\.github\.io/caerle-on/?|https?://[a-z0-9.-]+/(?=(?:images|assets|#|sitemap))')


def normalize(url: str) -> str:
    url = url.strip()
    if not url.startswith(('http://', 'https://')):
        url = 'https://' + url
    return url.rstrip('/') + '/'


def main() -> int:
    if len(sys.argv) != 2:
        print(__doc__)
        return 1

    new = normalize(sys.argv[1])
    root = Path(__file__).resolve().parent.parent

    # 現在のアドレスは index.html の canonical から読み取る
    index = (root / 'index.html').read_text(encoding='utf-8')
    m = re.search(r'<link rel="canonical" href="([^"]+)"', index)
    if not m:
        print('  index.html の canonical が見つかりません')
        return 1
    current = m.group(1)
    if not current.endswith('/'):
        current += '/'
    print(f'  現在のアドレス: {current}')
    print(f'  新しいアドレス: {new}\n')
    old_pat = re.compile(re.escape(current))

    total = 0
    for name in TARGETS:
        path = root / name
        if not path.exists():
            continue
        text = path.read_text(encoding='utf-8')
        hits = len(old_pat.findall(text))
        if hits:
            path.write_text(old_pat.sub(new, text), encoding='utf-8')
            total += hits
        print(f'  {name:14} {hits:2d} 箇所')

    print(f'\n  合計 {total} 箇所を {new} に変更しました')
    return 0


if __name__ == '__main__':
    raise SystemExit(main())
