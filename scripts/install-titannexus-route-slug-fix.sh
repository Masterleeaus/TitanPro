#!/bin/bash
set -e

ROOT="/home/saassmar/domains/tradiesm.art/public_html"
cd "$ROOT"

python3 <<'PY'
from pathlib import Path
import re

pages = {
    'NexusCommandCenter': 'command-center',
    'LeadFinder': 'lead-finder',
    'NexusContacts': 'contacts',
    'ContactSegments': 'segments',
    'ContactLists': 'contact-lists',
    'OutreachRuns': 'outreach-runs',
    'FollowUpQueue': 'follow-up-queue',
    'ConversationInbox': 'conversation-inbox',
    'BookingPipeline': 'booking-handoffs',
    'TrainingLibrary': 'training-library',
    'ContractsPaperwork': 'contracts-paperwork',
    'ChannelSettings': 'channel-settings',
    'NexusSystemStatus': 'system-status',
}

base = Path('app/Filament/TitanNexus/Pages')

for cls, slug in pages.items():
    path = base / f'{cls}.php'
    if not path.exists():
        continue

    src = path.read_text()

    if '$slug' not in src:
        src = re.sub(
            r"(protected static \?string \$title\s*=\s*[^;]+;)",
            r"\1\n\n    protected static ?string $slug = '" + slug + "';",
            src,
            count=1,
        )

    # If title was absent, insert slug before view.
    if '$slug' not in src:
        src = re.sub(
            r"(protected string \$view\s*=)",
            "protected static ?string $slug = '" + slug + "';\n\n    \\1",
            src,
            count=1,
        )

    path.write_text(src)
PY

# Ensure TitanNexus provider has every explicit page class registered.
python3 <<'PY'
from pathlib import Path
import re

path = Path('app/Providers/Filament/TitanNexusPanelProvider.php')
src = path.read_text()

imports = [
    'BookingPipeline',
    'ChannelSettings',
    'ContactLists',
    'ContactSegments',
    'ContractsPaperwork',
    'ConversationInbox',
    'FollowUpQueue',
    'LeadFinder',
    'NexusCommandCenter',
    'NexusContacts',
    'NexusSystemStatus',
    'OutreachRuns',
    'TrainingLibrary',
]

for cls in imports:
    line = f'use App\\Filament\\TitanNexus\\Pages\\{cls};'
    if line not in src:
        src = src.replace('use App\\Filament\\Pages\\UiStudio;\n', 'use App\\Filament\\Pages\\UiStudio;\n' + line + '\n')

pages_list = '''->pages([
                Pages\\Dashboard::class,
                NexusCommandCenter::class,
                Verticals::class,
                LeadPipeline::class,
                LeadFinder::class,
                NexusContacts::class,
                ContactSegments::class,
                ContactLists::class,
                OutreachRuns::class,
                FollowUpQueue::class,
                ConversationInbox::class,
                BookingPipeline::class,
                TrainingContent::class,
                TrainingLibrary::class,
                ContractsPaperwork::class,
                ChannelSettings::class,
                MarketingCampaigns::class,
                NexusSystemStatus::class,
                UiStudio::class,
            ])'''

src = re.sub(r"->pages\(\[\s*.*?\s*\]\)", pages_list, src, flags=re.S)

# Keep app resources active, do not rediscover legacy module Filament stubs.
src = re.sub(r"\n\s*->discoverResources\(in:\s*base_path\('Modules/TitanNexus/Filament/Resources'\).*?\)", "", src)
src = re.sub(r"\n\s*->discoverPages\(in:\s*base_path\('Modules/TitanNexus/Filament/Pages'\).*?\)", "", src)
src = re.sub(r"\n\s*->discoverWidgets\(in:\s*base_path\('Modules/TitanNexus/Filament/Widgets'\).*?\)", "", src)

path.write_text(src)
PY

rm -rf bootstrap/cache/*.php storage/framework/views/* storage/framework/cache/*
/usr/local/php84/bin/php artisan optimize:clear || true
/usr/local/php84/bin/php artisan route:clear || true
/usr/local/php84/bin/php artisan view:clear || true
/usr/local/php84/bin/php artisan filament:assets || true

echo "PASS67 TitanNexus route slug fix installed."
echo "Open /titannexus and /titannexus/outreach-runs."
