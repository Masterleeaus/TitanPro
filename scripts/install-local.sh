#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")/.."
[ -f .env ] || cp .env.example .env
mkdir -p database storage/app/public storage/framework/cache/data storage/framework/sessions storage/framework/testing storage/framework/views storage/logs bootstrap/cache
touch database/database.sqlite
if command -v composer >/dev/null 2>&1; then
  composer install
  php artisan key:generate --force
  php artisan migrate --force
else
  echo "Composer is not installed. Install Composer, then rerun this script." >&2
  exit 1
fi
if command -v npm >/dev/null 2>&1; then
  npm install
  npm run build
else
  echo "npm is not installed. Install Node.js/npm, then run: npm install && npm run build" >&2
fi
