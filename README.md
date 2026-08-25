# Caerle'on（カーリアン）

会員制ラウンジ Caerle'on の公式ホームページ。
黒×金を基調としたシングルページ構成で、CSS / JS は `index.html` に一体化しています。

## 構成

```
caerle-on/
├── index.html          # 本体（CSS / JS 込みの単一ファイル）
├── images/             # 店内写真 10 枚（対応表は images/README.md）
├── assets/
│   ├── favicon.svg     # ブラウザタブ用アイコン
│   └── logo-*.png      # 透過ロゴ（dark / light / gold）
└── .github/workflows/  # GitHub Pages 自動デプロイ
```

## セクション

Hero ／ Concept ／ Gallery ／ System（会員制・料金）／ Access ／ Recruit ／ Footer

## 実装済みの演出

- ヘッダーロゴ：ロード時の文字別フェードイン、ゴールドライン伸長、アポストロフィの明滅、ホバー時のシマー
- スクロール時のヘッダー縮小
- 各セクションのスクロール連動フェードイン
- ギャラリー写真のホバーズーム＋ゴールド枠

## 本番前に要差し替え（ダミー箇所）

- 電話番号：`03-XXXX-XXXX`
- メール：`contact@caerleon.tokyo`
- 料金表の金額（仮の数値）
- SNS リンク／予約・応募ボタンのリンク先

## 写真管理ページ（admin.html）

`https://shomayamamoto-ai.github.io/caerle-on/admin.html` から、コードを触らずにサイトの写真を差し替えられます。

1. [GitHub の Fine-grained personal access token](https://github.com/settings/personal-access-tokens/new) を作成
   - Repository access: **Only select repositories → `caerle-on`**
   - Permissions: **Contents → Read and write**
2. admin ページでトークンを入力して「接続」
3. 差し替えたい枠の「写真を選ぶ」→ プレビュー確認 →「保存」

選んだ写真はブラウザ内で横 1920px の JPEG に最適化され、main ブランチへ直接コミットされます。
Pages の再デプロイ後（1〜2 分）にサイトへ反映されます。
トークンは端末のブラウザ（localStorage）にのみ保存され、GitHub 以外へは送信されません。

## 公開

main ブランチへ push すると GitHub Actions で GitHub Pages に自動デプロイされます。
公開 URL: https://shomayamamoto-ai.github.io/caerle-on/
