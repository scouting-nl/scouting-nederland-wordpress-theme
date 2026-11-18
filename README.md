# Scouting Nederland WordPress Theme

[![WordPress](https://img.shields.io/badge/WordPress-7.0%2B-blue.svg)](https://wordpress.org/themes/scouting-nederland-wordpress-theme/)
[![WordPress-Version](https://img.shields.io/wordpress/theme/v/scouting-nederland-wordpress-theme.svg)](https://wordpress.org/themes/scouting-nederland-wordpress-theme/)
[![WordPress-Downloads](https://img.shields.io/wordpress/theme/dt/scouting-nederland-wordpress-theme.svg)](https://wordpress.org/themes/scouting-nederland-wordpress-theme/)
[![WordPress-Active-Installs](https://img.shields.io/wordpress/theme/installs/scouting-nederland-wordpress-theme.svg)](https://wordpress.org/themes/scouting-nederland-wordpress-theme/)
[![License](https://img.shields.io/github/license/scouting-nl/scouting-nederland-wordpress-theme)](https://github.com/scouting-nl/scouting-nederland-wordpress-theme/blob/main/LICENSE)

[![CI](https://github.com/scouting-nl/scouting-nederland-wordpress-theme/actions/workflows/build-test.yml/badge.svg)](https://github.com/scouting-nl/scouting-nederland-wordpress-theme/actions/workflows/build-test.yml)

**Contributors:** scoutingnederland \
**Requires at least:** 7.0 \
**Tested up to:** 7.1 \
**Requires PHP:** 8.2 \
**Stable tag:** 1.0.0 \
**Tags:** custom-colors, custom-logo, custom-menu, editor-style, featured-images, footer-widgets, theme-options, translation-ready \
**License:** GPLv3 or later \
**License URI:** <https://www.gnu.org/licenses/gpl-3.0.html>

A responsive classic WordPress theme for Scouting websites, developed and published by Scouting Nederland.

## Index

* [Description](#description)
* [Documentation](#documentation)
* [Screenshots](#screenshots)
* [Installation](#installation)
* [Configuration](#configuration)
* [Optional Plugins](#optional-plugins)
* [Frequently Asked Questions](#frequently-asked-questions)
* [Security](#security)
* [Translations](#translations)

## Description

Scouting Nederland gives Scouting groups a recognizable website design with configurable colors, logos, navigation, hero sections, sliders, and widget areas.

The theme uses the native WordPress template hierarchy and works without additional plugins. It supports the WordPress block editor while retaining the flexibility of a classic PHP theme.

Features include:

* Responsive templates for the home page, pages, posts, archives, categories, search results, and 404 pages.
* Customizer settings for the logo, primary and accent colors, header layout, hero image, fonts, and social links.
* Page-level controls for the hero, page title, excerpt, sidebar, and hero heading font.
* Image sliders and video banners, including videos selected from the media library.
* Main and footer menu locations, plus widget areas for the hero, sidebar, footer, and other theme sections.
* Block editor styles, a Scouting color palette, button block styles, and a reusable information panel pattern.
* WordPress comments with threaded replies and pagination.
* English source strings and a bundled Dutch translation.

Scouting-specific Gutenberg blocks belong to the separate Scouting Nederland Blocks companion plugin. The theme does not register custom block types or bundle companion plugins.

Bugs and feature requests can go to [GitHub](https://github.com/scouting-nl/scouting-nederland-wordpress-theme/issues) or [WordPress](https://wordpress.org/support/theme/scouting-nederland-wordpress-theme/).

Do not report security vulnerabilities publicly. Follow the [security policy](.github/SECURITY.md) to submit a private report.

## Documentation

Installation and configuration instructions are included below. For development, see the [contributing guide](.github/CONTRIBUTING.md).

## Screenshots

### 1. Front page

![Scouting Nederland theme](screenshot.png)

## Installation

1. Log in to your WordPress admin dashboard.
2. Navigate to **Appearance > Themes > Add New Theme**.
3. Search for **Scouting Nederland**.
4. Find the theme and click **Install**.
5. After installation, click **Activate**.

You can also download the theme ZIP from [WordPress.org](https://wordpress.org/themes/scouting-nederland-wordpress-theme/) or [GitHub Releases](https://github.com/scouting-nl/scouting-nederland-wordpress-theme/releases/latest), then install it through **Appearance > Themes > Add New Theme > Upload Theme**.

The theme requires WordPress 7.0 or later and PHP 8.2 or later.

## Configuration

1. Open **Appearance > Customize** to set your logo, colors, header layout, default hero image, hero heading font, and social links.
2. Assign menus to the main and footer menu locations under **Appearance > Menus**.
3. Configure widget areas under **Appearance > Widgets**.
4. Choose your home page and posts page under **Settings > Reading**.
5. Edit individual pages to adjust their hero, title, excerpt, sidebar, and slider settings.

### Header

The header width defaults to **Wide**, with a horizontal menu on desktop. Select **Narrow** for a compact header with a hamburger menu. Both layouts use a hamburger menu on tablets and mobile devices. Previously saved choices are preserved.

The Customizer also lets you configure the phone, contact, and search actions and toggle their visibility. Leave the search page URL empty to use WordPress's built-in search page.

### Hero and slider

Set a default hero image and heading font in the Customizer. Individual pages can override the heading font, hide the default hero or page title, show the page excerpt below the hero heading, or hide the sidebar.

Use the **Slider** panel in the page editor to enable an image slider or video banner. Videos can come from the media library or an external video URL; a selected library video takes priority. A configured slider or video remains visible when the default hero is hidden.

## Optional Plugins

* [Scouting OpenID Connect](https://wordpress.org/plugins/scouting-openid-connect/) adds sign-in through Scouting Nederland.
* Scouting Nederland Blocks adds Scouting-specific Gutenberg blocks as a separate companion plugin.

Neither plugin is required or included in the theme package. Install companion plugins separately.

## Frequently Asked Questions

### Is this a block theme?

No. This is a classic PHP theme with block editor support, editor styles, block styles, a reusable block pattern, and `theme.json` settings.

### Does this theme require additional plugins?

No. The theme works on its own. Scouting OpenID Connect and Scouting Nederland Blocks are optional plugins installed separately.

### Does the theme include Scouting Nederland blocks?

No. Install the separate Scouting Nederland Blocks companion plugin to add Scouting-specific blocks.

### Does the theme support comments?

Yes. The theme supports WordPress comments and threaded replies on posts, subject to your site's discussion settings.

### Does the theme work with Elementor?

The page template includes presentation adjustments for pages built with Elementor. Elementor is not required.

## Security

Report suspected vulnerabilities privately through the repository's [security policy](.github/SECURITY.md). Do not include vulnerability details, secrets, tokens, or personal data in public issues or support topics.

## Translations

* English (source language)
* Dutch (`nl_NL`, bundled translation)

WordPress selects the language using the site language for visitors and the user's language preference in the dashboard. Set the site language under **Settings > General > Site Language**. Saved content, menu names, and settings are not translated or changed.

The `languages/` directory contains the English `scouting-nederland-wordpress-theme.pot` template, the editable Dutch `scouting-nederland-wordpress-theme-nl_NL.po` catalog, and the compiled `scouting-nederland-wordpress-theme-nl_NL.mo` file loaded by WordPress. The theme maps WordPress's locale-only requests to these domain-prefixed filenames without overriding site-wide language packs. English is the fallback language and does not require a separate translation catalog.

The **Translations** workflow checks catalog freshness automatically through **Build and test**. Run **Translations** manually with **update** enabled to download updated POT, PO, and MO files as an artifact. Translate new messages and review fuzzy entries before using the generated files; the workflow never commits changes automatically.
