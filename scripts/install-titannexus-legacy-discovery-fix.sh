#!/bin/bash
set -e

ROOT="/home/saassmar/domains/tradiesm.art/public_html"
cd "$ROOT"

# Remove legacy duplicate resource page stubs that collide with the real LeadRecordResource page class.
rm -rf Modules/TitanNexus/Filament/Resources/ExampleRecordResource || true

# Remove legacy module resource stubs that do not extend Filament Resource correctly.
rm -f Modules/TitanNexus/Filament/Resources/CampaignResource.php || true
rm -f Modules/TitanNexus/Filament/Resources/NexusPaymentResource.php || true
rm -f Modules/TitanNexus/Filament/Resources/NexusInvoiceResource.php || true
rm -f Modules/TitanNexus/Filament/Resources/NexusJobResource.php || true
rm -f Modules/TitanNexus/Filament/Resources/NexusAutomationRunResource.php || true

rm -rf bootstrap/cache/*.php storage/framework/views/* storage/framework/cache/*

/usr/local/php84/bin/php artisan optimize:clear || true
/usr/local/php84/bin/php artisan migrate --force || true
/usr/local/php84/bin/php artisan filament:assets || true

echo "PASS66 TitanNexus legacy discovery fix installed."
echo "Open /titannexus/system-status to verify data areas."
