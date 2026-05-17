#!/bin/bash
set -e

ROOT="/home/saassmar/domains/tradiesm.art/public_html"
cd "$ROOT"

python3 <<'PY'
from pathlib import Path
import re

disable_nav = [
    'LeadFinder',
    'NexusContacts',
    'ContactSegments',
    'ContactLists',
    'OutreachRuns',
    'FollowUpQueue',
    'ConversationInbox',
    'BookingPipeline',
    'TrainingLibrary',
    'ContractsPaperwork',
    'ChannelSettings',
    'MarketingCampaigns',
    'TrainingContent',
    'LeadPipeline',
    'Verticals',
]

base = Path('app/Filament/TitanNexus/Pages')

for cls in disable_nav:
    path = base / f'{cls}.php'
    if not path.exists():
        continue

    src = path.read_text()

    if 'function shouldRegisterNavigation' not in src:
        insert = """
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

"""
        pos = src.rfind("\n}")
        if pos != -1:
            src = src[:pos] + insert + src[pos:]

    path.write_text(src)

provider = Path('app/Providers/Filament/TitanNexusPanelProvider.php')
if provider.exists():
    src = provider.read_text()

    src = re.sub(r"\n\s*->discoverResources\(in:\s*base_path\('Modules/TitanNexus/Filament/Resources'\).*?\)", "", src)
    src = re.sub(r"\n\s*->discoverPages\(in:\s*base_path\('Modules/TitanNexus/Filament/Pages'\).*?\)", "", src)
    src = re.sub(r"\n\s*->discoverWidgets\(in:\s*base_path\('Modules/TitanNexus/Filament/Widgets'\).*?\)", "", src)

    provider.write_text(src)
PY

rm -rf Modules/TitanNexus/Filament/Resources/ExampleRecordResource || true
rm -f Modules/TitanNexus/Filament/Resources/CampaignResource.php || true
rm -f Modules/TitanNexus/Filament/Resources/NexusPaymentResource.php || true
rm -f Modules/TitanNexus/Filament/Resources/NexusInvoiceResource.php || true
rm -f Modules/TitanNexus/Filament/Resources/NexusJobResource.php || true
rm -f Modules/TitanNexus/Filament/Resources/NexusAutomationRunResource.php || true

rm -rf bootstrap/cache/*.php storage/framework/views/* storage/framework/cache/*

/usr/local/php84/bin/php artisan optimize:clear || true
/usr/local/php84/bin/php artisan route:clear || true
/usr/local/php84/bin/php artisan view:clear || true
/usr/local/php84/bin/php artisan migrate --force || true
/usr/local/php84/bin/php artisan filament:assets || true

echo "PASS71 TitanNexus conflicting page navigation disabled."
echo "Open /titannexus. Sidebar should use Resource menus instead of old workflow Page route names."
