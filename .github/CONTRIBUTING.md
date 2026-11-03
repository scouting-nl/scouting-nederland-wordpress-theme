# Contributing to the Scouting Nederland WordPress Theme

Thank you for helping improve the Scouting Nederland WordPress theme.

## Where to Contribute

- Report reproducible theme bugs through the [bug report form](https://github.com/scouting-nl/wordpress-theme/issues/new/choose).
- Ask for help through the [support discussions](https://github.com/scouting-nl/wordpress-theme/discussions/categories/support).
- Share feature requests and ideas through the [ideas discussions](https://github.com/scouting-nl/wordpress-theme/discussions/categories/ideas).
- Report security vulnerabilities privately according to [SECURITY.md](SECURITY.md). Do not open a public issue or discussion for a suspected vulnerability.

Do not include passwords, API keys, client secrets, access tokens, personal data, or production
logs in issues, pull requests, or discussions.

## Development

Use a WordPress test installation with content and accounts that you are authorized to use.
Refer to [README.md](../README.md) for installation and supported WordPress and PHP versions.
Review [PUBLISHING.md](../PUBLISHING.md) for the current release blockers and validation checklist.

Native PHP templates live in the repository root, reusable template parts in `parts/`, PHP modules
in `inc/`, and frontend assets in `assets/`.

Build a local distribution ZIP with PowerShell:

```powershell
pwsh -NoProfile -File .\scripts\build-zip.ps1
```

The ZIP is created in `scripts/`. Do not commit generated archives.

CSS uses the official WordPress Stylelint configuration, including formatting rules. With Node.js
22.13 or newer, install the pinned tools and format the theme-owned stylesheets:

```powershell
npm install --global --ignore-scripts --omit=peer --no-audit --no-fund `
 stylelint@17.14.1 @wordpress/stylelint-config@27.0.0 stylelint-scss@7.2.0
stylelint --fix style.css "assets/css/**/*.css"
```

Omit `--fix` to check without modifying files. The recommended VS Code Stylelint extension applies
fixable rules on explicit save. Vendored stylesheets are excluded from linting and formatting.

GitHub Actions checks PHP syntax, WordPress PHP, JavaScript and CSS coding standards, workflow
configuration, and WordPress Theme Check against the packaged theme.

Keep changes focused and consistent with the existing WordPress coding and security practices.
In particular:

- Sanitize input, validate authorization and nonces where applicable, and escape output in its rendering context.
- Preserve the native classic-theme template hierarchy and compatibility with the Customizer, widgets, editor styles, and `theme.json`.
- Keep custom blocks and their editor and render behavior compatible with existing content.
- Update translations when changing user-facing strings.
- Update [README.md](../README.md), [CHANGELOG.md](../CHANGELOG.md), `readme.txt`, or other documentation when user-visible behavior, configuration, or release information changes.

## Pull Requests

Before opening a pull request:

- Start from the latest `main` branch and keep unrelated changes out of the pull request.
- Add or update focused tests when practical, and describe the validation you performed.
- Explain the problem, the chosen solution, and any configuration, upgrade, or compatibility impact.
- Test the front page, page, single, archive, category, search, and 404 templates when the change affects front-end behavior.
- Do not commit generated ZIP files, credentials, or environment-specific files.

## Reviews and Releases

Maintainers review pull requests for behavior, security, accessibility, backward compatibility,
WordPress Theme Check results, and the effect on existing content and settings. Releases are
created by maintainers from version tags and release packages; contributors should not prepare
or publish release archives unless requested.
