# Module Deep Scan Pass 12

## Fixed

- Restored drifted/lost v4 module files that were missing from v5 Money Agent pass.
- Preserved Money Agent conversion files from v5.
- Fixed single-sidebar architecture:
  - Sidebar label: **Money Manager**
  - Registered page title/link: **ZeroPay Panel**
  - Slug: `zeropay-panel`
- Prevented duplicate finance sidebar entries by keeping Accountings hidden from sidebar and exposed as workspace tabs.
- Added populated workspace tabs:
  - Overview
  - Invoices
  - Payments
  - Collections
  - Bank Matching
  - Accounting
  - Compliance
  - AI Control
- Repaired empty `ControlPanelTabs::make()` methods.
- Repaired control-panel Blade comment syntax and added tab/metric rendering.
- Added `money.view` and `money.agent.use` permissions across config and manifests.
- Updated navigation manifests to match the single Money Manager panel.
- Normalised the ZeroPay fee policy in control panel layouts:
  - business fee = 0
  - primary rails = bank transfer, PayID, cash
  - customer-paid fee rails = card and configured PayID fee variants

## Preserved

- Existing Accounting controllers, migrations, views, services, DataTables, and reports.
- Existing EInvoice XML, AI-note, invoice, settings, and ZeroPay handoff code.
- Legacy class aliases such as `FinanceOpsAgent` and `InvoiceCollectionsAgent` as compatibility wrappers around `MoneyAgent`.

## Recommended next pass

Implement concrete Filament Tables/Widgets for each workspace tab and bind them to existing models:
`Invoice`, `PaymentSession`, `Transaction`, `BankDeposit`, `Journal`, `GSTReport`, and Money Agent tool logs.
