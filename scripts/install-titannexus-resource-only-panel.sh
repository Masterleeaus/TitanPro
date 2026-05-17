#!/bin/bash
set -e

ROOT="/home/saassmar/domains/tradiesm.art/public_html"
cd "$ROOT"

# Remove stale legacy module Filament stubs and cached page registrations.
rm -rf Modules/TitanNexus/Filament/Resources/ExampleRecordResource || true
rm -f Modules/TitanNexus/Filament/Resources/CampaignResource.php || true
rm -f Modules/TitanNexus/Filament/Resources/NexusPaymentResource.php || true
rm -f Modules/TitanNexus/Filament/Resources/NexusInvoiceResource.php || true
rm -f Modules/TitanNexus/Filament/Resources/NexusJobResource.php || true
rm -f Modules/TitanNexus/Filament/Resources/NexusAutomationRunResource.php || true

# Remove custom page files from active app discovery risk. Provider no longer discovers pages,
# but this prevents stale class maps / cached navigation from old installs.
mkdir -p storage/titannexus-disabled-pages
find app/Filament/TitanNexus/Pages -maxdepth 1 -type f -name "*.php" -not -name ".gitkeep" -exec mv -f {} storage/titannexus-disabled-pages/ \; 2>/dev/null || true

rm -rf bootstrap/cache/*.php storage/framework/views/* storage/framework/cache/*

/usr/local/php84/bin/php artisan optimize:clear || true
/usr/local/php84/bin/php artisan route:clear || true
/usr/local/php84/bin/php artisan view:clear || true
/usr/local/php84/bin/php artisan cache:clear || true
/usr/local/php84/bin/php artisan migrate --force || true
/usr/local/php84/bin/php artisan filament:assets || true

echo "PASS72 TitanNexus resource-only panel installed."
echo "Open /titannexus. The sidebar should now contain Resource entries only."
echo "Disabled old Page files are backed up in storage/titannexus-disabled-pages."
