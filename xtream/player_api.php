<?php
// Minimal Xtream Codes API for testing Merin Player.
require __DIR__ . '/config.php';
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Cache-Control: no-store');
$u = $_GET['username'] ?? ''; $p = $_GET['password'] ?? ''; $action = $_GET['action'] ?? '';
if (!hash_equals(XT_USER, $u) || !hash_equals(XT_PASS, $p)) { echo json_encode(['user_info' => ['auth' => 0]]); exit; }
$D = json_decode(file_get_contents(__DIR__ . '/data.json'), true);
$out = function ($x) { echo json_encode($x, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); exit; };
$cat = $_GET['category_id'] ?? null;
$filter = function ($rows) use ($cat) { return $cat === null ? $rows : array_values(array_filter($rows, function ($r) use ($cat) { return (string)$r['category_id'] === (string)$cat; })); };
switch ($action) {
  case '':
    $out(['user_info' => ['username' => XT_USER, 'auth' => 1, 'status' => 'Active', 'exp_date' => (string)(time() + 365 * 86400), 'is_trial' => '0',
      'active_cons' => '0', 'max_connections' => '2', 'allowed_output_formats' => ['m3u8', 'ts']],
      'server_info' => ['url' => 'merintta.com', 'server_protocol' => 'https', 'timezone' => 'UTC', 'timestamp_now' => time()]]);
  case 'get_live_categories': $out($D['live']['cats']);
  case 'get_live_streams': $out($filter($D['live']['streams']));
  case 'get_vod_categories': $out($D['vod']['cats']);
  case 'get_vod_streams': $out($filter($D['vod']['streams']));
  case 'get_vod_info':
    $id = $_GET['vod_id'] ?? '';
    $out(['info' => $D['vod']['info'][$id] ?? new stdClass(), 'movie_data' => ['stream_id' => (int)$id, 'container_extension' => 'mp4']]);
  case 'get_series_categories': $out($D['series']['cats']);
  case 'get_series': $out($filter($D['series']['list']));
  case 'get_series_info':
    $id = $_GET['series_id'] ?? ''; $info = new stdClass();
    foreach ($D['series']['list'] as $s) if ((string)$s['series_id'] === (string)$id) $info = ['name' => $s['name'], 'plot' => $s['plot'], 'genre' => $s['genre'], 'rating' => $s['rating'], 'releaseDate' => $s['releaseDate'], 'cover' => $s['cover']];
    $out(['info' => $info, 'episodes' => $D['series']['episodes'][$id] ?? new stdClass()]);
  case 'get_short_epg':
    $titles = ['Morning Edition', 'Market Watch', 'Garden Hour', 'Weekend Sport', 'The Late Show', 'World Tonight', 'Cartoon Club', 'Nature Files', 'Studio Live', 'Top Twenty'];
    $n = ((int)($_GET['stream_id'] ?? 0)) % 10; $limit = max(1, min(10, (int)($_GET['limit'] ?? 4)));
    $H = 3600; $t0 = intdiv(time(), $H) * $H; $list = [];
    for ($k = 0; $k < $limit; $k++) $list[] = ['title' => base64_encode($titles[($n + $k) % 10]), 'description' => base64_encode('Test programme for Merin Player.'),
      'start_timestamp' => (string)($t0 + $k * $H), 'stop_timestamp' => (string)($t0 + ($k + 1) * $H)];
    $out(['epg_listings' => $list]);
  default: $out([]);
}
