# Basic Banner — Agent Guidelines

## Language rules

- All code, identifiers, comments, commit messages, and documentation **must be written in English**.
- Chat replies to the user may be written in any language the user prefers.

## Coding standards

- All PHP code must follow the [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/).
- JavaScript and CSS should follow the [WordPress JavaScript and CSS coding standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/).
- PHP code must pass PHPCS checks defined in `.phpcs.xml.dist` before changes are considered complete:
  ```bash
  vendor/bin/phpcs
  ```
- Text domain for all internationalized strings: `basic_banner`.
- Prefix for all global functions, classes, options, and hooks: `basic_banner`.

## Build & verification

- Frontend assets live in `assets/src/` (block in `assets/src/banner/`, admin styles in `assets/src/admin.scss`) and are compiled to `assets/build/` via `@wordpress/scripts`:
  ```bash
  npm run build
  ```
- After changing block sources or PHP files, verify with:
  ```bash
  npm run build
  vendor/bin/phpcs
  ```
