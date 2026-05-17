#!/bin/bash
set -e

cd /home/saassmar/domains/tradiesm.art/public_html

rm -rf bootstrap/cache/*.php storage/framework/views/*

/usr/local/php84/bin/php artisan optimize:clear || true

echo "UiStudio customCss hotfix installed."
