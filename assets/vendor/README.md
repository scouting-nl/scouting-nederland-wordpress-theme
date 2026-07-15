# Vendored dependencies

This directory contains third-party front-end dependencies that are distributed with the theme and loaded locally. Keeping these files in the theme avoids runtime requests to a CDN and complies with the WordPress.org requirement that themes include their scripts, styles, fonts, and other resources in the theme package.

Do not edit generated vendor files directly. Theme-specific overrides belong in `assets/css/`.

## Bootstrap

- Version: 5.3.8
- Source: [Bootstrap 5.3.8 release](https://github.com/twbs/bootstrap/releases/tag/v5.3.8)
- Website: [getbootstrap.com](https://getbootstrap.com/)
- License: MIT
- License file: `bootstrap/LICENSE`

Included files:

- `bootstrap/css/bootstrap.css`
- `bootstrap/css/bootstrap.min.css`
- Matching CSS source maps
- `bootstrap/js/bootstrap.bundle.js`
- `bootstrap/js/bootstrap.bundle.min.js`
- Matching JavaScript source maps

The bundle build includes Popper. The minified CSS and JavaScript files are loaded by `inc/enqueue.php`; the unminified files and source maps are included for review and debugging.

## Font Awesome Free

- Version: 7.3.1
- Source: [Font Awesome 7.3.1 release](https://github.com/FortAwesome/Font-Awesome/releases/tag/7.3.1)
- Website: [fontawesome.com](https://fontawesome.com/)
- Licenses: icons under CC BY 4.0, fonts under SIL OFL 1.1, and code under MIT
- License file: `font-awesome/LICENSE.txt`

Included files:

- `font-awesome/css/all.css`
- `font-awesome/css/all.min.css`
- Four required WOFF2 webfonts in `font-awesome/webfonts/`

The minified stylesheet is loaded by `inc/enqueue.php`. Version 7 only ships WOFF2 webfonts; every font referenced by the stylesheet is kept locally. Theme CSS restores the automatic icon width used before Font Awesome 7 unless a fixed-width utility class is explicitly applied.

## Updating

When updating a dependency:

1. Download the official release distribution from the source linked above.
2. Replace the minified and unminified files, source maps, webfonts, and license files together.
3. Update the version passed to `wp_enqueue_style()` or `wp_enqueue_script()` in `inc/enqueue.php`.
4. Update the version and attribution in `readme.txt` and this file.
5. Confirm every local `url()` and `sourceMappingURL` reference resolves.
6. Run JavaScript syntax checks and responsive visual regression tests before publishing.

Never replace these files with CDN URLs or remove their license files from the release package.
