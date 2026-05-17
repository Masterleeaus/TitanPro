# REAL PASS F3 - Cleaners Workflow and Reports

Implemented actual cleaners-first workflow functionality:

- supervisor decision endpoint and request validation
- approve/reject/suspend/reactivate state handling
- access expiry enforcement before check-in
- single-open-session check-in rule
- check-out duration tracking
- supervisor force check-out support
- daily attendance report service
- site operations summary report
- exception report for long-open sessions and pending approvals
- web actions for approve/reject/check-in/check-out/suspend
- migration for workflow and duration fields

Validation performed:

- PHP syntax lint across all module PHP files
- ZIP archive integrity check
