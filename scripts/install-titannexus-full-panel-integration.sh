#!/bin/bash
set -e
cd /home/saassmar/domains/tradiesm.art/public_html

/usr/local/php84/bin/php artisan optimize:clear || true
/usr/local/php84/bin/php artisan migrate --force || true
/usr/local/php84/bin/php artisan filament:assets || true
/usr/local/php84/bin/php artisan optimize:clear || true

echo "TitanNexus full MarketingBot panel integration installed. Open /titannexus."
