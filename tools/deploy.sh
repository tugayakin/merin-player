#!/bin/sh
# Builds and uploads the site to merintta.com/merin-player/ (only that folder is ever touched).
set -e
cd "$(dirname "$0")/.."
node tools/build.js
rsync -az --delete --exclude .git --exclude .github --exclude tools --exclude README.md --exclude .DS_Store \
  ./ merintta:domains/merintta.com/public_html/merin-player/
echo "deployed to https://merintta.com/merin-player/"
