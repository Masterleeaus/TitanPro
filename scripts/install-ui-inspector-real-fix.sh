#!/bin/bash
set -e
cd /home/saassmar/domains/tradiesm.art/public_html
rm -rf bootstrap/cache/*.php storage/framework/views/* storage/framework/cache/*
/usr/local/php84/bin/php artisan optimize:clear || true
echo "PASS55 UI inspector real in-page settings fix installed."
echo "Open any Filament dashboard, click the gear, and look for PASS55 under Page UI."
