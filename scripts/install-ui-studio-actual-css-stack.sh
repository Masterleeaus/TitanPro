#!/bin/bash
set -e
cd /home/saassmar/domains/tradiesm.art/public_html
rm -rf bootstrap/cache/*.php storage/framework/views/* storage/framework/cache/*
/usr/local/php84/bin/php artisan optimize:clear || true
echo "PASS51 installed. Verify:"
grep -n "PASS51" resources/views/filament/pages/ui-studio.blade.php || true
grep -n "grid-template-areas" resources/views/filament/pages/ui-studio.blade.php | head || true
