# Cleaners Approval and Attendance Workflow

This pass turns the Security module into a cleaners-first operational workflow:

1. Cleaner is registered as `pending`.
2. Supervisor approves, rejects, suspends, or reactivates the cleaner.
3. Only active, approved, non-expired cleaners can check in.
4. A cleaner can only have one open check-in session.
5. Check-out closes the session and records duration.
6. Supervisor force check-out is available for exception handling.
7. Reports expose daily attendance, site summaries, and exceptions.

Primary APIs:

- `POST /api/security/cleaners/{cleaner}/decision`
- `POST /api/security/cleaners/{cleaner}/check-in`
- `POST /api/security/cleaners/{cleaner}/check-out`
- `POST /api/security/cleaners/{cleaner}/force-check-out`
- `GET /api/security/cleaner-reports/daily`
- `GET /api/security/cleaner-reports/sites`
- `GET /api/security/cleaner-reports/exceptions`
