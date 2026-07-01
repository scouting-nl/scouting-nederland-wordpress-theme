=== Scouting Nederland ===
Contributors: geldwolfje216, jobvk
Tags: custom-logo, custom-menu, editor-style, featured-images, one-column, right-sidebar, two-columns, wide-blocks, block-styles
Requires at least: 7.0
Tested up to: 7.0
Stable tag: 1.0.0
Requires PHP: 7.4
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Classic WordPress theme for Scouting Nederland websites.

== Description ==

Scouting Nederland is a classic WordPress theme for Scouting Nederland websites. It includes responsive templates, widget areas, editor styles, custom blocks, sliders, custom colors, and page-level display settings for local scouting groups and national sites.

The theme follows the WordPress Theme Handbook structure while preserving classic theme compatibility: the required root `index.php` acts as a fallback dispatcher, template bodies live in `templates/`, reusable template parts live in `parts/`, custom PHP lives in `inc/`, and assets live in `assets/`.

== Installation ==

1. Download the theme as a `.zip` file.
2. In WordPress, go to Appearance > Themes.
3. Choose Add New Theme, then Upload Theme.
4. Upload the `.zip` file and activate Scouting Nederland.
5. Optional but recommended: install the `scouting-openid-connect` plugin when you want visitors or members to sign in with their Scouting account.
6. Configure the theme in Appearance > Customize and assign the Primary and Footer menus.

== Recommended Plugins ==

= Scouting OpenID Connect =

Install the optional `scouting-openid-connect` plugin when the site needs Scouting login. The theme displays an admin notice with a direct install link for administrators who can install plugins.

== Theme Structure ==

= Required theme files =

* `style.css` - theme metadata and WordPress theme header.
* `index.php` - required classic theme fallback that dispatches to `templates/`.

= Standard folders =

* `assets/` - CSS, JavaScript, fonts, and images.
* `inc/` - setup code, Customizer settings, navigation, blocks, search helpers, and admin notices.
* `templates/` - template bodies for index, pages, posts, archives, search results, and 404 pages.
* `parts/` - reusable template parts for the header, footer, sidebar, and content.

= Optional handbook files =

* `theme.json` - global editor settings, color palette, font families, and layout sizes.
* `screenshot.png` - theme screenshot for Appearance > Themes.
* `readme.txt` - user-facing release and installation information.

== Frequently Asked Questions ==

= Is Scouting OpenID Connect required? =

No. It is optional and only needed for Scouting login functionality.

= Is this a block theme? =

No. This is a classic PHP theme with block editor support, custom blocks, editor styles, and `theme.json` settings.

== Screenshots ==

1. Front page

== Changelog ==

= Unreleased =
* Development changes for handbook-aligned theme metadata, theme structure documentation, `theme.json`, and optional Scouting OpenID Connect installation guidance.

= 1.0.0 =
* Initial release