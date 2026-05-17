#!/bin/bash
set -e

ROOT="/home/saassmar/domains/tradiesm.art/public_html"
cd "$ROOT"

# Remove stale compiled classes/routes/views.
rm -rf bootstrap/cache/*.php storage/framework/views/* storage/framework/cache/*

# Remove legacy module stubs that break panel boot.
rm -rf Modules/TitanNexus/Filament/Resources/ExampleRecordResource || true
rm -f Modules/TitanNexus/Filament/Resources/CampaignResource.php || true
rm -f Modules/TitanNexus/Filament/Resources/NexusPaymentResource.php || true
rm -f Modules/TitanNexus/Filament/Resources/NexusInvoiceResource.php || true
rm -f Modules/TitanNexus/Filament/Resources/NexusJobResource.php || true
rm -f Modules/TitanNexus/Filament/Resources/NexusAutomationRunResource.php || true

/usr/local/php84/bin/php artisan optimize:clear || true
/usr/local/php84/bin/php artisan route:clear || true
/usr/local/php84/bin/php artisan view:clear || true
/usr/local/php84/bin/php artisan migrate --force || true
/usr/local/php84/bin/php artisan filament:assets || true

echo "PASS69 hard TitanNexus route fix installed."
echo "Check routes:"
/usr/local/php84/bin/php artisan route:list | grep "titannexus.*outreach-runs" || true
