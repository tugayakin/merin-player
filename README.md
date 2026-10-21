# Merin Player site

Source of the Merin Player support page, privacy policy and test playlist, published at
https://merintta.com/merin-player/ (Hostinger).

- Support: https://merintta.com/merin-player/
- Privacy policy: https://merintta.com/merin-player/privacy
- Test playlist: https://merintta.com/merin-player/test/playlist.m3u

Deploy: `./tools/deploy.sh` (builds the playlist and uploads over SSH; only touches the merin-player folder).
The TV guide is generated live by `test/epg.php`.

The test playlist uses only official test streams (Mux, Apple) and Creative Commons films from the Blender Foundation
(Big Buck Bunny, Tears of Steel — CC BY 3.0).
