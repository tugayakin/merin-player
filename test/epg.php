<?php
// Merin Player test TV guide (XMLTV), generated on every request so programmes are always around now.
header('Content-Type: application/xml; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Cache-Control: public, max-age=600');
$channels = json_decode(file_get_contents(__DIR__ . '/guide-channels.json'), true);
$titles = ['Morning Edition', 'Market Watch', 'Garden Hour', 'Weekend Sport', 'The Late Show', 'World Tonight', 'Cartoon Club', 'Nature Files', 'Studio Live', 'Top Twenty'];
$H = 3600; $start = intdiv(time(), $H) * $H - 6 * $H;
$stamp = function ($t) { return gmdate('YmdHis', $t) . ' +0000'; };
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<tv generator-info-name=\"merin-player test guide\">\n";
foreach ($channels as $c) echo '  <channel id="' . htmlspecialchars($c['id']) . '"><display-name>' . htmlspecialchars($c['name']) . "</display-name></channel>\n";
foreach ($channels as $i => $c) {
  for ($k = 0; $k < 36; $k++) {
    $s = $start + $k * $H;
    echo '  <programme channel="' . htmlspecialchars($c['id']) . '" start="' . $stamp($s) . '" stop="' . $stamp($s + $H) . '"><title>' .
      htmlspecialchars($titles[($i + $k) % count($titles)]) . "</title><desc>Test programme for Merin Player.</desc></programme>\n";
  }
}
echo "</tv>\n";
