# Filament One-Page Module Standard

Every module gets exactly one sidebar item in the Filament panel.

## Page layout

### Top half

- quick metric cards
- widgets with operational summaries
- module agent chatbot
- voice input button when enabled
- shortcuts card
- settings card

### Bottom half

- one card containing all module tables as tabs
- no separate sidebar links for individual module resources

Filament resources may still exist for forms, table definitions, relation managers, and policies, but they should not register their own navigation entries. They are surfaced inside the page's tabbed table card.
