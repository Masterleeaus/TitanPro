# Cleaners-First Security Module

This pass narrows the module from enterprise security scaffolding into a simpler first product for cleaning operations.

## Core flow
1. Register cleaner.
2. Supervisor approves cleaner.
3. Cleaner receives / links access-card request.
4. Cleaner checks in at a site checkpoint.
5. Work permits and goods movement records can be associated with the cleaner.
6. Cleaner checks out.
7. Admin reviews simple counts and audit records.

## New domain objects
- `Cleaner`
- `CleanerSiteLog`

## New API endpoints
- `GET /api/security/cleaners/dashboard`
- `GET /api/security/cleaners`
- `POST /api/security/cleaners`
- `GET /api/security/cleaners/{cleaner}`
- `POST /api/security/cleaners/{cleaner}/approve`
- `POST /api/security/cleaners/{cleaner}/check-in`
- `POST /api/security/cleaners/{cleaner}/check-out`

## Preserved legacy operations
- Access cards
- Work permits
- Goods in/out permits
