# Job Manager Routes Plan (to be wired in Pass 1)

Target public prefix: `/dashboard/user/jobs` (auth)

Core:
- GET `/dashboard/user/jobs` -> Jobs index
- GET `/dashboard/user/jobs/dispatch` -> Dispatch board
- GET `/dashboard/user/jobs/calendar` -> Job calendar
- GET `/dashboard/user/jobs/{job}` -> Job detail

Imported module routes (raw) currently live in JobManager/Routes/*.php and will be bridged into TitanCommand routing in Pass 1.
