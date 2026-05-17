#!/bin/bash
set -e

cd /home/saassmar/domains/tradiesm.art/public_html

rm -rf bootstrap/cache/*.php storage/framework/views/* storage/framework/cache/*

/usr/local/php84/bin/php artisan optimize:clear || true

echo "PASS50 UiStudio force single-column installed."
echo "Open /titanpro/ui-studio and confirm PASS50 STACKED badge appears near title."
