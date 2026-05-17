#!/bin/bash
set -e

ROOT="/home/saassmar/domains/tradiesm.art/public_html"
cd "$ROOT"

rm -f resources/views/filament/titan-nexus/pages/*bot-page.blade.php
rm -f Modules/TitanNexus/Upgrade/Scripts/import_*bot.php

rm -rf bootstrap/cache/*.php storage/framework/views/* storage/framework/cache/*

/usr/local/php84/bin/php artisan optimize:clear || true
/usr/local/php84/bin/php artisan migrate --force || true
/usr/local/php84/bin/php artisan filament:assets || true

echo "PASS64 TitanNexus clean panel upgrade installed."
echo "Open /titannexus and confirm TitanNexus, Acquisition, Outreach, Conversion, Delivery Readiness, and Channels navigation groups."
