# Coursework assessment

Review date: 26 October 2026. Based on source inspection; this does not establish independent authorship or mastery.

## Company Directory

The most complete CRUD lab in the set. Shared layout and database code, parameterized queries, escaped output, date/URL validation, CSRF checks, and image type/size checks are present. Missing: authentication, management roles, old upload cleanup, and robust handling of malformed array inputs.

## Event Registration

Shows SQL joins and a many-to-many relationship through registrations. Prepared inserts/deletes and output escaping are present. The new setup schema supplies the unique registration constraint expected by the UI. Missing: CSRF checks on create/register, access control, and consistent field-length validation.

## Smaller labs

Membership form: required-field checks, email validation, and escaping; no account persistence.

Sessions/cookies: redirects, session state, preference cookie, and logout; name entry is a classroom login simulation. Session regeneration, cookie options, preference allowlisting, and CSRF protection remain future work.

HTML profile and first PHP page: introductory exercises best grouped in a coursework collection.

## Packaging changes

Added documentation and ignore rules, replaced hardcoded database settings with ignored local config plus example templates, and added the missing event schema with fictional records. Removed the personal photo and university email from the publication copy. ZIP archives and the Laragon welcome page were excluded. Original Laragon files and existing databases were not modified.

## Next improvements

1. Exercise both database apps against disposable databases and document results.
2. Add missing CSRF protection and consistent input validation.
3. Add access control before any public deployment.
4. Capture screenshots using fictional data.
5. Record the parts implemented independently and credit starter code or references.
