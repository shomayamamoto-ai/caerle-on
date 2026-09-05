<?php
/**
 * Caerle'on 写真管理 API（移管先サーバー用）
 *
 * GitHub Pages では動作しません（PHP が動くサーバーへ移管した際に有効になります）。
 * 設置手順:
 *   1. サイト一式（index.html / admin.html / admin-api.php / images/ / assets/）を
 *      PHP が動作するサーバーへそのままアップロード
 *   2. 下の $ADMIN_PASSWORD を推測されにくい文字列に変更
 *   3. admin.html を開くと自動で PHP モードに切り替わり、
 *      このパスワードでログインして写真の差し替えができます
 */

$ADMIN_PASSWORD = 'CHANGE_ME'; // ← 移管後、必ず変更してください

// ---------------------------------------------------------------

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

$action = isset($_GET['action']) ? $_GET['action'] : '';

// サイトで使用している写真スロット（admin.html と対応）
// 注: Access の看板写真（c0209646.jpg）は店舗ロゴのため書き換え不可（対象外）
$ALLOWED = array(
  'c0209891.jpg', 'p0004.jpg', 'c0209615.jpg',
  'c0209824.jpg', 'c0209748.jpg', 'c0209962.jpg',
  'p0002.jpg', 'c0209948.jpg', 'p0005.jpg',
);

// 稼働確認（認証不要）
if ($action === 'ping') {
  echo json_encode(array(
    'ok' => true,
    'backend' => 'php',
    'configured' => ($ADMIN_PASSWORD !== 'CHANGE_ME'),
  ));
  exit;
}

// ---- 認証 ----
$token = isset($_SERVER['HTTP_X_ADMIN_TOKEN']) ? $_SERVER['HTTP_X_ADMIN_TOKEN'] : '';
if ($ADMIN_PASSWORD === 'CHANGE_ME') {
  http_response_code(403);
  echo json_encode(array('ok' => false, 'error' => 'サーバー側のパスワードが未設定です（admin-api.php の $ADMIN_PASSWORD を変更してください）'));
  exit;
}
if (!hash_equals($ADMIN_PASSWORD, $token)) {
  http_response_code(401);
  echo json_encode(array('ok' => false, 'error' => 'パスワードが一致しません'));
  exit;
}

// 認証チェックのみ
if ($action === 'verify') {
  echo json_encode(array('ok' => true));
  exit;
}

// ---- 画像アップロード ----
if ($action === 'upload' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $body = json_decode(file_get_contents('php://input'), true);
  $file = isset($body['file']) ? $body['file'] : '';
  $data = isset($body['content']) ? $body['content'] : '';

  if (!in_array($file, $ALLOWED, true)) {
    http_response_code(400);
    echo json_encode(array('ok' => false, 'error' => '対象外のファイル名です'));
    exit;
  }

  $bin = base64_decode($data, true);
  if ($bin === false || strlen($bin) < 1000 || strlen($bin) > 8 * 1024 * 1024) {
    http_response_code(400);
    echo json_encode(array('ok' => false, 'error' => '画像データが不正です（8MB 以内の JPEG）'));
    exit;
  }
  // JPEG マジックバイト検証
  if (substr($bin, 0, 3) !== "\xFF\xD8\xFF") {
    http_response_code(400);
    echo json_encode(array('ok' => false, 'error' => 'JPEG 形式のみ対応しています'));
    exit;
  }

  $dir = __DIR__ . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
  if (!is_dir($dir) || !is_writable($dir)) {
    http_response_code(500);
    echo json_encode(array('ok' => false, 'error' => 'images フォルダに書き込めません（権限を確認してください）'));
    exit;
  }

  $path = $dir . $file;
  // 直前の状態をバックアップ（1 世代）
  if (file_exists($path)) {
    @copy($path, $dir . '_backup_' . $file);
  }
  if (file_put_contents($path, $bin) === false) {
    http_response_code(500);
    echo json_encode(array('ok' => false, 'error' => '書き込みに失敗しました'));
    exit;
  }

  echo json_encode(array('ok' => true, 'file' => $file, 'bytes' => strlen($bin)));
  exit;
}

// ---- ギャラリー表示設定（サイトから消す／表示に戻す） ----
if ($action === 'photos_save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $body = json_decode(file_get_contents('php://input'), true);
  $hidden = isset($body['hidden']) ? $body['hidden'] : null;
  if (!is_array($hidden) || count($hidden) > count($ALLOWED)) {
    http_response_code(400);
    echo json_encode(array('ok' => false, 'error' => '表示設定データが不正です'));
    exit;
  }
  // 許可リストにあるファイル名のみを保存
  $clean = array();
  foreach ($hidden as $f) {
    if (is_string($f) && in_array($f, $ALLOWED, true) && !in_array($f, $clean, true)) {
      $clean[] = $f;
    }
  }
  $json = json_encode(array('hidden' => $clean), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
  if (file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . 'photos.json', $json) === false) {
    http_response_code(500);
    echo json_encode(array('ok' => false, 'error' => 'photos.json に書き込めません（権限を確認してください）'));
    exit;
  }
  echo json_encode(array('ok' => true, 'hidden' => $clean));
  exit;
}

// ---- 文章（content.json）の更新 ----
if ($action === 'content_save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $body = json_decode(file_get_contents('php://input'), true);
  $content = isset($body['content']) ? $body['content'] : null;
  if (!is_array($content) || count($content) === 0) {
    http_response_code(400);
    echo json_encode(array('ok' => false, 'error' => '文章データが不正です'));
    exit;
  }
  $json = json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
  if ($json === false || strlen($json) > 512 * 1024) {
    http_response_code(400);
    echo json_encode(array('ok' => false, 'error' => '文章データを保存できません（上限 512KB）'));
    exit;
  }
  $path = __DIR__ . DIRECTORY_SEPARATOR . 'content.json';
  if (file_exists($path)) {
    @copy($path, __DIR__ . DIRECTORY_SEPARATOR . '_backup_content.json');
  }
  if (file_put_contents($path, $json) === false) {
    http_response_code(500);
    echo json_encode(array('ok' => false, 'error' => 'content.json に書き込めません（権限を確認してください）'));
    exit;
  }
  echo json_encode(array('ok' => true));
  exit;
}

http_response_code(400);
echo json_encode(array('ok' => false, 'error' => '不明なリクエストです'));
