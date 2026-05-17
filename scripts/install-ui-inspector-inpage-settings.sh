#!/bin/bash
set -e
cd /home/saassmar/domains/tradiesm.art/public_html
rm -rf bootstrap/cache/*.php storage/framework/views/* storage/framework/cache/*
/usr/local/php84/bin/php artisan optimize:clear || true
echo 'PASS53 UI inspector in-page settings installed. Hard refresh, then click the bottom-right gear.'
