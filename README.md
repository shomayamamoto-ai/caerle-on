# Caerle'on（カーリアン）

六本木クラブ「カーリアン」（Club Caerle'on）の公式ホームページ。
黒×金を基調としたシングルページ構成で、CSS / JS は `index.html` に一体化しています。

## 構成

```
caerle-on/
├── index.html          # 本体（CSS / JS 込みの単一ファイル）
├── admin.html          # 文章・写真の管理ページ
├── admin-api.php       # 管理ページの保存処理（PHP サーバー用）
├── content.json        # 文章データ（管理ページから書き換わる）
├── photos.json         # 写真の割り当て（管理ページから書き換わる）
├── images/             # 店内写真（対応表は images/README.md）
├── assets/
│   ├── favicon.svg     # ブラウザタブ用アイコン
│   └── logo-*.png      # 透過ロゴ（dark / light / gold）
├── wp-theme/caerleon/  # WordPress 版テーマ（移行する場合に使用）
├── tools/set-domain.py # 公開ドメインを一括で差し替えるツール
└── .github/workflows/  # GitHub Pages 自動デプロイ
```

## セクション

Hero ／ Concept ／ Gallery ／ System（料金）／ Access ／ Recruit ／ Footer

## 実装済みの演出

- ヘッダーロゴ：ロード時の文字別フェードイン、ゴールドライン伸長、アポストロフィの明滅、ホバー時のシマー
- スクロール時のヘッダー縮小
- 各セクションのスクロール連動フェードイン
- ギャラリー写真のホバーズーム＋ゴールド枠

## 掲載情報

電話番号・住所・営業時間・料金表はすべて実データです（ダミーはありません）。
メールアドレスと SNS リンクは掲載していません。
変更は管理ページ（`admin.html`）から行います。

## 管理ページ（admin.html）

`https://caerleon-club.com/admin.html` から、コードを触らずに**文章と写真**を差し替えられます。

1. 1つめのパスワード（ページを開くため）でログイン
2. 「文章」タブで本文を編集、「写真」タブで差し替えたい枠の「写真を選ぶ」
3. 2つめのパスワード（保存するため）を入れて「保存」

写真はブラウザ内で横 1920px の JPEG に最適化されて送信されます。
**パスワードはこのリポジトリには記載しません**（誰でも読める場所のため）。
管理ページ側はパスワードの SHA-256 ハッシュのみを保持しています。

## 別サーバーへの移管（データ移管後の写真管理）

admin.html は 2 つの動作モードを自動判別します。

| モード | 動作環境 | 認証 | 仕組み |
| --- | --- | --- | --- |
| Server モード | PHP が動くサーバー（**現在の本番**） | 管理パスワード | 同梱の `admin-api.php` が直接書き込み |
| GitHub モード | GitHub Pages（確認用ミラー） | Fine-grained PAT | GitHub API でコミット |

### 移管手順

1. サイト一式（`index.html` / `admin.html` / `admin-api.php` / `images/` / `assets/` / `robots.txt` / `sitemap.xml`）を移管先サーバーへそのままアップロード
2. `admin-api.php` 冒頭の `$ADMIN_PASSWORD = 'CHANGE_ME'` を推測されにくいパスワードに変更
3. 移管先の `admin.html` を開くと自動で **Server モード** に切り替わるので、設定したパスワードでログイン

これだけで、移管後も同じ画面で写真の差し替え・取り出しができます。
（PHP が動けばレンタルサーバー・VPS など移管先を問いません。バックアップとして直前の写真が `images/_backup_*.jpg` に 1 世代残ります）

## 公開

**本番**：エックスサーバー上の `caerleon-club.com`（PHP モード）
公開 URL: https://caerleon-club.com/
設置手順は [SETUP-XSERVER.md](SETUP-XSERVER.md)（他社サーバーは [SETUP-PHP.md](SETUP-PHP.md)）

**確認用ミラー**：main ブランチへ push すると GitHub Actions で GitHub Pages に自動デプロイされます。
https://shomayamamoto-ai.github.io/caerle-on/

ドメインを変えるときは `python3 tools/set-domain.py https://新しいドメイン/` で全ファイルを一括更新できます。
