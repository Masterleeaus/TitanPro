# PASS65 TitanNexus CRUD Resources

## Files changed
- app/Models/TitanNexus/*
- app/Filament/TitanNexus/Resources/*
- scripts/install-titannexus-crud-resources.sh

## Fixes applied
- Added Eloquent models for TitanNexus workflow data areas.
- Added Filament CRUD resources for target verticals, lead records, contacts, outreach runs, conversations, booking handoffs, training content, and contract documents.
- Connected resources to the TitanNexus panel discovery path.
- Kept wording focused on operational instructions and direct workflow labels.

## Next steps
- Add relation managers between leads, contacts, conversations, booking handoffs, documents, and training records.
- Add actions to move records through acquisition, outreach, conversion, and delivery readiness stages.
