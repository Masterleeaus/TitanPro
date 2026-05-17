#!/bin/bash
set -e
cd /home/saassmar/domains/tradiesm.art/public_html
rm -rf bootstrap/cache/*.php storage/framework/views/* storage/framework/cache/*
/usr/local/php84/bin/php artisan optimize:clear || true
/usr/local/php84/bin/php artisan route:clear || true
echo "TitanNexus MarketingBot base installed. Open /titannexus and click Growth Engine."
