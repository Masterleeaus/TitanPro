#!/usr/bin/env bash
set -euo pipefail
cd /home/saassmar/domains/tradiesm.art/public_html

mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache database
[ -f database/database.sqlite ] || touch database/database.sqlite
chmod -R ug+rw storage bootstrap/cache database || true

if [ -f .env ]; then
  cp .env .env.pass7.bak.$(date +%Y%m%d%H%M%S)
  sed -i 's/^APP_DEBUG=.*/APP_DEBUG=true/' .env || true
  grep -q '^APP_DEBUG=' .env || echo 'APP_DEBUG=true' >> .env
  grep -q '^APP_ENV=' .env || echo 'APP_ENV=local' >> .env
  grep -q '^DB_CONNECTION=' .env || echo 'DB_CONNECTION=sqlite' >> .env
  if grep -q '^DB_CONNECTION=sqlite' .env; then
    grep -q '^DB_DATABASE=' .env && sed -i 's#^DB_DATABASE=.*#DB_DATABASE=/home/saassmar/domains/tradiesm.art/public_html/database/database.sqlite#' .env || echo 'DB_DATABASE=/home/saassmar/domains/tradiesm.art/public_html/database/database.sqlite' >> .env
  fi
else
  cp .env.example .env
  echo 'APP_DEBUG=true' >> .env
  echo 'DB_DATABASE=/home/saassmar/domains/tradiesm.art/public_html/database/database.sqlite' >> .env
fi

if [ -f artisan ]; then
  php artisan key:generate --force || true
  php artisan optimize:clear || true
  php artisan config:clear || true
  php artisan route:clear || true
  php artisan view:clear || true
fi

echo 'PASS7 rescue applied. Open: https://tradiesm.art/titan-rescue.php'
