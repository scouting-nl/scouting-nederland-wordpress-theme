# Scouting Nederland WordPress Theme Instructions

This repository is a classic WordPress theme targeting PHP 7.4 and newer. Keep
changes focused, preserve the native template hierarchy, and follow
[`.editorconfig`](../.editorconfig).

## Coding Standards

All theme-owned code must comply with the checked-in rulesets, including their
formatting and documentation requirements:

- PHP: [`phpcs.xml.dist`](../phpcs.xml.dist).
- JavaScript: [`eslint.config.mjs`](../eslint.config.mjs).
- CSS: [`stylelint.config.mjs`](../stylelint.config.mjs).

Read the applicable configuration before editing. Fix code that violates these
rules; do not lower severity, disable rules, add suppressions, or expand exclusions
merely to make checks pass. Preserve the existing vendor exclusions and WordPress
core compatibility exceptions.

## Accessibility

- New or changed user-facing output must follow the [WordPress Accessibility Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/accessibility/) and conform to WCAG 2.2 Level AA.
- Prefer semantic HTML and native controls. Use ARIA only when native semantics are insufficient, and keep accessible names, roles, states, and relationships accurate.
- Make interactions keyboard operable with a logical focus order and visible focus. Associate labels, instructions, validation errors, and status updates with the controls or regions they describe.
- Do not convey meaning through color, shape, position, or sound alone. Preserve sufficient contrast and provide appropriate text alternatives for non-text content.

## PHP

- Follow the [WordPress PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/) and [PHP Documentation Standards](https://developer.wordpress.org/coding-standards/inline-documentation-standards/php/).
- Preserve compatibility with the Customizer, widgets, editor styles, `theme.json`, and existing theme settings.
- Sanitize input, validate data, check capabilities and nonces where applicable, and escape output at the point it is rendered.
- Do not add Composer files. CI installs pinned PHPCS and WordPress Coding Standards tools without Composer.
- Add useful DocBlocks for new files and significant functions or hooks. Include accurate `@param` and `@return` tags where applicable; avoid comments that only restate the code.

## HTML

- Follow the [WordPress HTML Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/html/) and the accessibility requirements above for markup in templates, PHP output, and JavaScript.
- Use lowercase tags and attributes, quote attribute values, close elements correctly, and use tabs to reflect logical structure in mixed PHP and HTML.
- Preserve WordPress core and editor classes as compatibility contracts.

## CSS

- Follow the [WordPress CSS Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/css/).
- Use lowercase, hyphen-separated names for theme-owned CSS classes. Do not rename WordPress core or editor classes.
- Keep changes within the relevant modular stylesheet under `assets/css/`; avoid altering vendored styles under `assets/vendor/`.

## JavaScript

- Follow the [WordPress JavaScript Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/) and [JavaScript Documentation Standards](https://developer.wordpress.org/coding-standards/inline-documentation-standards/javascript/) for new or changed code under `assets/js/`.
- Use tabs, single-quoted strings, semicolons, strict comparisons, braces for control blocks, and readable line wrapping. Prefer `const` and `let` for new code; do not refactor untouched code just to replace `var`.
- Add accurate JSDoc for new named functions and significant constants, events, or globals.
- Keep [`eslint.config.mjs`](../eslint.config.mjs) as the persistent ESLint configuration. Do not add `package.json`, lockfiles, committed `node_modules`, or additional JavaScript formatters unless explicitly requested. CI installs its pinned ESLint tools under `RUNNER_TEMP` and excludes vendored assets.

## Theme Structure

- Keep the native classic-theme hierarchy in the repository root, reusable template parts in `parts/`, PHP modules in `inc/`, and theme assets in `assets/`.
- The theme supports the block editor, editor styles, block styles, a reusable pattern, and `theme.json`, but it does not register custom block types. Scouting-specific blocks belong to the separate companion plugin and must not be added to the theme package.
- Avoid unrelated refactors and preserve existing content, saved settings, and template behavior.

## Assets and Releases

- Do not reformat or alter files under `assets/vendor/` unless explicitly requested.
- Review [`PUBLISHING.md`](../PUBLISHING.md) before changing bundled fonts, images, screenshots, or release contents. Redistribution, privacy, and licensing restrictions apply to some assets.
- Build distributable archives with [`scripts/build-zip.ps1`](../scripts/build-zip.ps1). Generated ZIP files belong in `scripts/` and must not be committed.
- Keep release packages limited to distributable theme files. Do not include development tooling, `.github/`, `.vscode/`, or the separate companion plugin.

## GitHub Actions

- Follow the [WordPress GitHub Actions Workflow Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/github-actions/) and use two-space YAML indentation.
- Give each workflow a top-level `permissions: {}` declaration, then grant each job only the permissions it needs.
- Pin every external action to a full commit SHA followed by a version comment; update the SHA and comment together.
- Pass GitHub context values through environment variables before using them in scripts. Do not interpolate untrusted values directly into executable code.
- Set `persist-credentials: false` on `actions/checkout` unless authenticated Git operations are required.

## Validation

- Before completing a code or lint-configuration change, run all three coding-standard checks from [`.github/workflows/build-test.yml`](workflows/build-test.yml), using its pinned tool versions, repository configurations, and complete lint targets. Fix violations introduced by the change before reporting it complete; checking only the edited lines or relying on editor formatting is insufficient.
- For PHP changes, also run PHP syntax lint on every changed PHP file. The PHPCS check must use [`phpcs.xml.dist`](../phpcs.xml.dist) against the repository, as in CI.
- For JavaScript changes, run the pinned ESLint command from [`.github/workflows/build-test.yml`](workflows/build-test.yml) against `assets/js/`, then run `node --check` on each changed `.js` file.
- For CSS changes, run the pinned Stylelint command using [`stylelint.config.mjs`](../stylelint.config.mjs) against `style.css` and `assets/css/**/*.css`, then test affected states and layouts across the relevant responsive breakpoints.
- For rendered markup or interface changes, validate complete HTML when practical and manually check keyboard operation, focus, labels, status and error feedback, text alternatives, and contrast.
- For workflow or VS Code JSON changes, run the corresponding pinned Prettier checks from the build workflow. Run Actionlint for workflow changes.
- Keep the build workflow aligned with the actual theme tooling. It runs configuration formatting, PHP lint, PHPCS, ESLint, Stylelint, workflow lint, and WordPress Theme Check against the packaged theme.
- Run `git diff --check` after every change. Report the checks run and their results. If a required check cannot run or has a pre-existing failure, report that limitation explicitly rather than claiming the change is CI-ready; never claim a check passed unless it ran successfully.
