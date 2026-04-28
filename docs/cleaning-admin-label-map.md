# Cleaning Admin Label Map

This delta changes wording only. It does not rename PHP classes, database tables, routes, or permissions keys.

## Confirmed source-state note
The uploaded `site.zip` did not contain exact source files/classes named `QuotePackage`, `JobType`, `JobChecklistItem`, or `JobTypeChecklistItem` during this pass. The patch therefore updates the wording layer that is present in this artifact: product/item catalog labels.

## Target labels

| Existing wording | Cleaning-business wording |
| --- | --- |
| Product / Products | Add-on / Add-ons |
| Item / Items | Add-on / Add-ons |
| Job Type / Job Types | Service / Services |
| Quote Package / Quote Packages | Cleaning Package / Cleaning Packages |
| Job Checklist Item(s) | Task Library |
| Job Type Checklist Item(s) | Service Checklist(s) |

## Recommended next code rename policy
Keep database/model names stable unless a full migration is planned. Prefer UI labels first:

- `Product` model can remain technical catalog storage while UI says **Add-ons**.
- `JobType` model can remain technical service template storage while UI says **Services**.
- `QuotePackage` can remain technical bundle storage while UI says **Cleaning Packages**.
- Checklist bridge tables can remain technical while UI says **Task Library** and **Service Checklists**.
