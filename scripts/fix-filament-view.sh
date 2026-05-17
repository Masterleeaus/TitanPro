#!/bin/bash
set -e

ROOT="/home/saassmar/domains/tradiesm.art/public_html"

cd "$ROOT"

echo "Patching Filament \$view declarations..."

find app Modules -type f -name "*.php" -exec sed -i \
's/protected static string \$view/protected static ?string \$view/g' {} \;

echo "Clearing Laravel caches..."
php artisan optimize:clear || true

echo "Rebuilding autoload..."
composer dump-autoload || true

echo "Done."
