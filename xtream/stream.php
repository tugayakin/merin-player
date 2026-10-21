<?php
// /live|movie|series/{user}/{pass}/{id}.{ext} -> the matching test stream
require __DIR__ . '/config.php';
header('Access-Control-Allow-Origin: *');
if (!hash_equals(XT_USER, $_GET['u'] ?? '') || !hash_equals(XT_PASS, $_GET['p'] ?? '')) { http_response_code(403); exit('forbidden'); }
$id = (int)($_GET['id'] ?? 0);
$to = ($_GET['type'] ?? '') === 'live' ? XT_LIVE[$id % count(XT_LIVE)] : XT_CLIP;
header('Location: ' . $to, true, 302);
