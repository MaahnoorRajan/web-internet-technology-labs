# Web and Internet Technology Labs

Coursework by Maahnoor Rajan, a Computer Science undergraduate. This repository documents practice with HTML, CSS, PHP, and MySQL during my Web and Internet Technology course.

These are learning exercises at different stages of complexity, intended for local study with fictional data.

## Labs

| Folder | Exercise | Concepts visible in the code |
| --- | --- | --- |
| [company_directory](company_directory) | Add, list, edit, and delete companies; optional logo uploads | PDO, CRUD, validation, prepared statements, CSRF tokens, file checks |
| [event_registration_app](event_registration_app) | List and add events, register demo students, remove registrations | Related tables, SQL joins, form processing, prepared statements |
| [membership-registration](membership-registration) | Validate and display a membership form | POST, required fields, email validation, escaping |
| [week5_sessions_cookies](week5_sessions_cookies) | Remember a name and language preference | Sessions, cookies, redirects, logout |
| [profile](profile) | Personal profile HTML exercise | Semantic HTML, navigation, tables, links |
| [lab1](lab1) | First PHP page | Variables, output, dates |

## Run locally with Laragon

Requirements: PHP 8.3 (used for syntax checks), MySQL 8 or a compatible server, PDO MySQL, and Fileinfo for logo validation.

1. Clone or download this repository.
2. Copy its six lab folders directly into C:/laragon/www. Keep the company_directory name because its navigation uses absolute paths.
3. Start Apache and MySQL in Laragon.
4. For Company Directory, import company_directory/config/database/company_directory.sql.
5. For Event Registration, import event_registration_app/database/schema.sql into a NEW empty database. It includes fictional demo users, categories, and an event.
6. In each database project folder, copy database.example.php to database.local.php and enter your local database settings. The local file is ignored by Git.
7. Open http://localhost/company_directory/ or http://localhost/event_registration_app/.

The form and session labs need PHP but no database; open their corresponding localhost folder URLs. Open profile/profile.html for the HTML exercise. Use fictional inputs; the membership exercise does not create accounts or save passwords.

## Scope and limitations

- Company and event management pages have no authentication or administrator roles.
- Event creation and registration still need CSRF protection; registration removal has a token check.
- The session lab accepts a name rather than verifying credentials. It demonstrates session state, not secure authentication.
- The membership form displays fields without storing accounts.
- Session hardening, pagination, accessibility review, and automated functional testing remain future improvements.
- The event home page prints a connection debugging message. Some event forms enable development error display.
- Old logos are not cleaned up on company replacement/deletion.

## Attribution and repository preparation

The application source comes from my course lab folders. Coursework is labelled as coursework; no professional experience or user-impact claims are implied. Specific instructor-provided starter material and external references should be credited where applicable; their provenance was not established during this review.

AI assistance was used for assessment, documentation, separating local database configuration, and reconstructing the missing event schema with fictional records. Original Laragon files were left unchanged. The personal photo was omitted and the university email replaced with a placeholder in the publication copy.

## Verification

See [docs/ASSESSMENT.md](docs/ASSESSMENT.md) and [docs/VALIDATION.md](docs/VALIDATION.md). No production deployment or live-user testing is claimed.
