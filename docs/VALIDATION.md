# Validation

26 October 2026

- All 22 PHP files in the prepared repository passed PHP 8.3.33 syntax checks.
- PDO MySQL and Fileinfo extensions are available in the installed PHP runtime.
- The event schema was checked against table and column names referenced by the source; it has not been imported or tested against MySQL.
- No live database records were read, exported, or changed.
- Full database workflows, browser layouts, and file uploads have not been functionally tested.
- The original Laragon coursework folders were left unchanged.

Manual checks to perform on disposable databases: create/edit/delete a fictional company; reject an invalid logo; add an event; register a demo student; reject a duplicate registration; remove a registration; validate an invalid membership email; save a language preference; log out and confirm the dashboard redirects.
