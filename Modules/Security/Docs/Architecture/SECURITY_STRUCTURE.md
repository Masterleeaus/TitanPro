# Security Module Structure

## Purpose

Combined module for security transfer validation, goods in/out permits, work permits, attached work-permit files, and access-card requests. It now follows the Titan module blueprint while preserving legacy route/view/translation aliases.

## Active legacy-compatible domains

| Domain | Main routes | Controllers | Models/Entities | Views |
|---|---|---|---|---|
| Security transfer validation | `account/security-transfer/*` | `SecurityController` | `Security` | `Resources/views/security`, `Resources/views/transfer` |
| Security work-permit validation | `account/security-workpermit/*` | `SecurityWPController` | `WorkPermits` | `Resources/views/work-permit` |
| Goods in/out permits | `account/trinoutpermit/*` | `TrInOutPermitPermissionController` | `TrInOutPermit` | `Resources/views/_legacy/trinoutpermit` |
| Work permits | `account/work-permits/*` | `WorkPermitsController`, `WorkPermitsFileController` | `WorkPermits`, `WorkPermitsFile` | `Resources/views/_legacy/workpermits` |
| Access cards | `account/card-access/*` | `CardAccessController` | `TrAccessCard`, `CardItems` | `Resources/views/_legacy/traccesscard` |

## Blueprint placement

- `Config/`: module, permissions, features, workflow, AI, billing, notification, integration, and registry configuration.
- `Providers/`: canonical `ModuleServiceProvider`, compatible `SecurityServiceProvider`, route/event/auth/broadcast/workflow/AI providers.
- `Bootstrap/`: module bootstrap, helpers, bindings, startup, and macros.
- `Routes/`: active `web.php`, `api.php`, `web-settings.php`, plus blueprint placeholders for admin/internal/workflow/AI/webhook/channels/console.
- `Database/`: active migrations/seeders plus blueprint subfolders for factories, data, views, procedures, triggers, functions, ERD.
- `Entities/`: current Eloquent module entities retained; blueprint subfolders added for future aggregates/value objects/states/scopes/relations.
- `Http/`: active controllers and requests retained; blueprint subfolders added for Web/API/Internal/Webhooks/AI layering.
- `Resources/`: existing nwidart `views/lang/assets` retained; blueprint aliases `Views/Lang/JS/CSS/Images/Icons/Email/PDF` added for normalized future assets.
- `Docs/Architecture/security-structure-map.json`: generated map of scanned PHP classes and callable methods.

## Feature inventory

### Http/Controllers

- `Http/Controllers/CardAccessController.php` — classes: CardAccessController; functions: __construct, index, create, store, show, edit, update, applyQuickAction, deleteRecords, destroy, download, domPdfObjectForDownload
- `Http/Controllers/SecurityController.php` — classes: SecurityController; functions: __construct, index, show, download, domPdfObjectForDownload, validateData, processValidatedData
- `Http/Controllers/SecurityWPController.php` — classes: SecurityWPController; functions: __construct, index, show, download, domPdfObjectForDownload, validateData, processValidatedData
- `Http/Controllers/TrAccessCardController.php` — classes: TrAccessCardController; functions: index
- `Http/Controllers/TrInOutPermitController.php` — classes: TrInOutPermitController; functions: index
- `Http/Controllers/TrInOutPermitPermissionController.php` — classes: TrInOutPermitPermissionController; functions: __construct, index, create, store, show, edit, update, applyQuickAction, deleteRecords, destroy, download, domPdfObjectForDownload, approved, approved_bm, validateData, processValidatedData
- `Http/Controllers/TrWorkPermitsController.php` — classes: TrWorkPermitsController; functions: index
- `Http/Controllers/WorkPermitsController.php` — classes: WorkPermitsController; functions: __construct, index, create, store, show, edit, update, applyQuickAction, deleteRecords, destroy, download, domPdfObjectForDownload, approved, approved_bm, validateData, processValidatedData
- `Http/Controllers/WorkPermitsFileController.php` — classes: WorkPermitsFileController; functions: store, storeMultiple, storeFiles, destroy, download

### Entities

- `Entities/CardItems.php` — classes: CardItems; functions: card
- `Entities/Security.php` — classes: Security; functions: n/a
- `Entities/TrAccessCard.php` — classes: TrAccessCard; functions: unit, items
- `Entities/TrInOutPermit.php` — classes: TrInOutPermit; functions: unit, tower, pnl, floor, typeunit, approved, approvedBm, validated
- `Entities/WorkPermits.php` — classes: WorkPermits; functions: unit, approved, approvedBm, validated, files
- `Entities/WorkPermitsFile.php` — classes: WorkPermitsFile; functions: getFileUrlAttribute, wp

### DataTables

- `DataTables/CardDataTable.php` — classes: CardDataTable; functions: __construct, dataTable, query, child, html, getColumns
- `DataTables/SecurityDataTable.php` — classes: SecurityDataTable; functions: __construct, dataTable, query, child, html, getColumns
- `DataTables/SecurityWPDataTable.php` — classes: SecurityWPDataTable; functions: __construct, dataTable, query, child, html, getColumns
- `DataTables/TrInOutPermitDataTable.php` — classes: TrInOutPermitDataTable; functions: __construct, dataTable, query, child, html, getColumns
- `DataTables/WorkPermitDataTable.php` — classes: WorkPermitDataTable; functions: __construct, dataTable, query, child, html, getColumns

### Http/Requests

- `Http/Requests/CardRequest.php` — classes: CardRequest; functions: authorize, rules
- `Http/Requests/GoodsInOutValidation.php` — classes: GoodsInOutValidation; functions: authorize, rules
- `Http/Requests/StoreTrInOutPermit.php` — classes: StoreTrInOutPermit; functions: authorize, rules
- `Http/Requests/StoreWorkPermits.php` — classes: StoreWorkPermits; functions: authorize, rules

### Listeners

- `Listeners/CompanyCreatedListener.php` — classes: CompanyCreatedListener; functions: handle
- `Listeners/TrAccessCardCompanyCreatedListener.php` — classes: TrAccessCardCompanyCreatedListener; functions: handle
- `Listeners/TrInOutPermitCompanyCreatedListener.php` — classes: TrInOutPermitCompanyCreatedListener; functions: handle
- `Listeners/TrWorkPermitsCompanyCreatedListener.php` — classes: TrWorkPermitsCompanyCreatedListener; functions: handle

### Observers

- `Observers/CardItemsObserver.php` — classes: CardItemsObserver; functions: saving
- `Observers/CardObserver.php` — classes: CardObserver; functions: saving, creating, created, deleting
- `Observers/SecurityObserver.php` — classes: SecurityObserver; functions: saving
- `Observers/TrInOutPermitObserver.php` — classes: TrInOutPermitObserver; functions: saving
- `Observers/WorkPermitsFileObserver.php` — classes: WorkPermitsFileObserver; functions: saving
- `Observers/WorkPermitsObserver.php` — classes: WorkPermitsObserver; functions: saving

### Providers

- `Providers/AIServiceProvider.php` — classes: AIServiceProvider; functions: register, boot
- `Providers/AuthServiceProvider.php` — classes: AuthServiceProvider; functions: register, boot
- `Providers/BroadcastServiceProvider.php` — classes: BroadcastServiceProvider; functions: register, boot
- `Providers/EventServiceProvider.php` — classes: EventServiceProvider; functions: boot
- `Providers/FilamentServiceProvider.php` — classes: FilamentServiceProvider; functions: register, boot
- `Providers/ModuleServiceProvider.php` — classes: ModuleServiceProvider; functions: n/a
- `Providers/RouteServiceProvider.php` — classes: RouteServiceProvider; functions: boot, map, mapWebRoutes, mapSettingRoutes, mapOptionalWebRoutes, mapInternalRoutes, mapApiRoutes
- `Providers/SecurityServiceProvider.php` — classes: SecurityServiceProvider; functions: boot, register, registerConfig, registerViews, registerTranslations, provides, getPublishableViewPaths
- `Providers/WorkflowServiceProvider.php` — classes: WorkflowServiceProvider; functions: register, boot

### Database/Migrations

- `Database/Migrations/2021_07_27_153806_add_security_permission.php` — classes: extends; functions: up, down
- `Database/Migrations/2023_03_21_020522_create_tenan_izin_brg_table.php` — classes: extends; functions: up, down
- `Database/Migrations/2023_03_21_021030_create_tenan_card_akses_table.php` — classes: extends; functions: up, down
- `Database/Migrations/2023_03_21_021031_create_tenan_card_akses_items_table.php` — classes: extends; functions: up, down
- `Database/Migrations/2023_07_24_085655_create_workpermits_table.php` — classes: extends; functions: up, down
- `Database/Migrations/2023_07_25_021173_create_workpermit_file_table.php` — classes: extends; functions: up, down
- `Database/Migrations/2023_07_27_153811_add_card_akses_permission.php` — classes: extends; functions: up, down
- `Database/Migrations/2023_07_27_153811_add_transfer_permission.php` — classes: extends; functions: up, down
- `Database/Migrations/2023_07_27_153811_add_work_permits_permission.php` — classes: extends; functions: up, down

### Database/Seeders

- `Database/Seeders/CardAksesDatabaseSeeder.php` — classes: CardAksesDatabaseSeeder; functions: run
- `Database/Seeders/SecurityDatabaseSeeder.php` — classes: SecurityDatabaseSeeder; functions: run
- `Database/Seeders/TrAccessCardDatabaseSeeder.php` — classes: TrAccessCardDatabaseSeeder; functions: run
- `Database/Seeders/TransferDatabaseSeeder.php` — classes: TransferDatabaseSeeder; functions: run
- `Database/Seeders/WorkPermitsDatabaseSeeder.php` — classes: WorkPermitsDatabaseSeeder; functions: run
