# ROLLBACK

- Restore previous TitanCommand extension zip.
- Or revert:
  - `extension.json` provider value
  - remove `Imports/JobManager_zip_extraction`
  - remove `JobManagerImportController.php` and `resources/views/job-manager/imported.blade.php`
  - revert provider route insertion.
