#!/bin/bash
set -e

ROOT="/home/saassmar/domains/tradiesm.art/public_html"
cd "$ROOT"

echo "Installing Platform Panel Links page..."

mkdir -p app/Filament/Platform/Pages
mkdir -p resources/views/filament/platform/pages

cp -f app/Filament/Platform/Pages/PanelLinks.php "$ROOT/app/Filament/Platform/Pages/PanelLinks.php"
cp -f resources/views/filament/platform/pages/panel-links.blade.php "$ROOT/resources/views/filament/platform/pages/panel-links.blade.php"

rm -rf bootstrap/cache/*.php storage/framework/views/*

if [ -x /usr/local/php84/bin/php ]; then
  /usr/local/php84/bin/php artisan optimize:clear || true
else
  php artisan optimize:clear || true
fi

echo "Done. Open: https://tradiesm.art/platform/filament-panels"
