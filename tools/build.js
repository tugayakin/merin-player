// Builds test/playlist.m3u and test/epg.xml. The guide is regenerated daily by .github/workflows/guide.yml
// so the programmes are always around "now". Only official test streams and CC-BY Blender films are used.
const fs = require('fs');
const BASE = 'https://tugayakin.github.io/merin-player';
const S = {
  bbb: 'https://test-streams.mux.dev/x36xhzz/x36xhzz.m3u8',                        // Big Buck Bunny (CC BY 3.0, Blender Foundation), Mux test stream
  tos: 'https://test-streams.mux.dev/tos_ismc/main.m3u8',                          // Tears of Steel (CC BY 3.0, Blender Foundation), Mux test stream
  pts: 'https://test-streams.mux.dev/pts_shift/master.m3u8',                       // Mux test stream
  t1: 'https://test-streams.mux.dev/test_001/stream.m3u8',                         // Mux test stream
  bip: 'https://devstreaming-cdn.apple.com/videos/streaming/examples/bipbop_16x9/bipbop_16x9_variant.m3u8',          // Apple HLS example
  bipts: 'https://devstreaming-cdn.apple.com/videos/streaming/examples/img_bipbop_adv_example_ts/master.m3u8'        // Apple HLS example
};
const LIVE = [
  ['Netherlands - Algemeen', 'NL: Polder One HD', 'p1', 'bbb'], ['Netherlands - Nieuws', 'NL: Northwind News FHD', 'nn', 'bip'],
  ['Netherlands - Sport', 'NL: Harbor Sports 1 FHD', 'hs', 'tos'], ['Netherlands - Kinderen', 'NL: Maple Kids HD', 'mk', 'bbb'],
  ['Netherlands - Films en Series', 'NL: Lantern Cinema FHD', 'lc', 'tos'], ['Netherlands - Documentaires', 'NL: Tundra Docs HD', 'td', 'pts'],
  ['Türkiye - Ulusal', 'TR: Boğaz TV HD', 'bt', 'bipts'], ['Türkiye - Haber', 'TR: Anadolu Haber 24', 'ah', 'bip'], ['Türkiye - Spor', 'TR: Kartal Spor 1 FHD', 'ks', 't1'],
  ['United Kingdom - News', 'UK: Thames News', 'tn', 'bip'], ['United Kingdom - Kids', 'UK: Puddle Kids', 'pk', 'bbb'], ['Germany - Sport', 'DE: Alpen Sport 1 FHD', 'as', 'tos']
];
const MOVIES = [['Saltwater Summer (2021)', 'saltwater', 'VOD - Drama'], ['The Quiet Orchard (2019)', 'orchard', 'VOD - Comedy'],
  ['North of Nowhere (2018)', 'nowhere', 'VOD - Drama'], ['Paper Lanterns (2023)', 'lanterns', 'VOD - Family']];
const SERIES = [['Harbour Lights', 'harbour', 'Series - Drama', [[1, 1, 'Arrival'], [1, 2, 'Low Tide'], [1, 3, 'The Storm'], [2, 1, 'New Season']]],
  ['Desk Job', 'desk', 'Series - Comedy', [[1, 1, 'Monday'], [1, 2, 'The Meeting']]]];
const clip = BASE + '/test/media/big-buck-bunny-10s.mp4';

let m = `#EXTM3U x-tvg-url="${BASE}/test/epg.xml"\n`;
LIVE.forEach(([g, n, id, s]) => { m += `#EXTINF:-1 tvg-id="${id}.merin" tvg-logo="${BASE}/test/logos/${id}.svg" group-title="${g}",${n}\n${S[s]}\n`; });
MOVIES.forEach(([n, p, g], i) => { m += `#EXTINF:-1 tvg-logo="${BASE}/test/logos/${p}.svg" group-title="${g}",${n}\n${BASE}/test/media/big-buck-bunny-10s.mp4?movie=${i + 1}\n`; });
SERIES.forEach(([show, p, g, eps]) => eps.forEach(([se, ep, t]) => {
  m += `#EXTINF:-1 tvg-logo="${BASE}/test/logos/${p}.svg" group-title="${g}",${show} S0${se} E0${ep} ${t}\n${clip}?series=${encodeURIComponent(show)}&s=${se}&e=${ep}\n`;
}));
fs.writeFileSync('test/playlist.m3u', m);

const H = 3600e3, now = Date.now(), start = Math.floor(now / H) * H - 6 * H;
const stamp = ms => new Date(ms).toISOString().replace(/[-:T]/g, '').slice(0, 14) + ' +0000';
const TITLES = ['Morning Edition', 'Market Watch', 'Garden Hour', 'Weekend Sport', 'The Late Show', 'World Tonight', 'Cartoon Club', 'Nature Files', 'Studio Live', 'Top Twenty'];
let x = '<?xml version="1.0" encoding="UTF-8"?>\n<tv generator-info-name="merin-player test guide">\n';
LIVE.forEach(([, n, id]) => { x += `  <channel id="${id}.merin"><display-name>${n.replace(/^\w+: /, '').replace(/ (FHD|HD)$/, '')}</display-name></channel>\n`; });
LIVE.forEach(([, , id], i) => { for (let k = 0; k < 36; k++) { const s = start + k * H; x += `  <programme channel="${id}.merin" start="${stamp(s)}" stop="${stamp(s + H)}"><title>${TITLES[(i + k) % TITLES.length]}</title><desc>Test programme for Merin Player.</desc></programme>\n`; } });
fs.writeFileSync('test/epg.xml', x + '</tv>\n');
console.log('playlist:', LIVE.length, 'channels,', MOVIES.length, 'movies,', SERIES.reduce((a, s) => a + s[3].length, 0), 'episodes; guide: 36 hours from', new Date(start).toISOString());
