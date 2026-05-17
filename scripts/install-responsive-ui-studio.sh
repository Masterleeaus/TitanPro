#!/bin/bash
set -e
cd /home/saassmar/domains/tradiesm.art/public_html
/usr/local/php84/bin/php artisan optimize:clear || true
npm run build || true
/usr/local/php84/bin/php artisan optimize:clear || true
echo "Responsive UI Studio delta installed."
