Titan Time Engine minimal package

Includes only the files needed for the Time Engine calendar/scheduler build.

Included:
- tenant trait used by tz_ models
- tz calendar/scheduler models
- TitanCalendarSystem services
- TitanScheduleEngine services
- Calendar controllers
- TitanRunScheduledTasks command + updated Console Kernel
- routes/panel.php with calendar routes
- calendar/theme views + dashboard widget views
- SQL installer: database/sql/titan_calendar_install.sql

Notes:
- This package preserves original Laravel paths.
- Merge into your codebase root, then review routes/panel.php and app/Console/Kernel.php before deploy.
- Run the SQL installer manually.
