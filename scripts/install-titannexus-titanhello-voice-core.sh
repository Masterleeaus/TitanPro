#!/bin/bash
set -e

ROOT="/home/saassmar/domains/tradiesm.art/public_html"
cd "$ROOT"

if [ -f routes/api.php ] && ! grep -q "titannexus_voice_core.php" routes/api.php; then
    cat >> routes/api.php <<'PHP'

require __DIR__.'/titannexus_voice_core.php';
PHP
fi

rm -rf bootstrap/cache/*.php storage/framework/views/* storage/framework/cache/*

/usr/local/php84/bin/php artisan optimize:clear || true
/usr/local/php84/bin/php artisan route:clear || true
/usr/local/php84/bin/php artisan view:clear || true
/usr/local/php84/bin/php artisan migrate --force || true
/usr/local/php84/bin/php artisan filament:assets || true

echo "PASS76 TitanNexus TitanHello voice core installed."
/usr/local/php84/bin/php artisan route:list | grep "titan-nexus/voice-core" || true
