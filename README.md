# Scouting Nederland WordPress Theme

[![Build and test](https://github.com/scouting-nl/wordpress-theme/actions/workflows/build-test.yml/badge.svg)](https://github.com/scouting-nl/wordpress-theme/actions/workflows/build-test.yml)
[![License](https://img.shields.io/github/license/scouting-nl/wordpress-theme)](https://github.com/scouting-nl/wordpress-theme/blob/main/LICENSE)

Een responsive classic theme voor WordPress-sites van Scoutinggroepen. Het theme biedt een configureerbare vormgeving, widgets, hero-secties, sliders en ondersteuning voor de WordPress block editor.

## Inhoud

- [Beschrijving](#beschrijving)
- [Screenshots](#screenshots)
- [Installatie](#installatie)
- [Instellingen](#instellingen)
- [Veelgestelde vragen](#veelgestelde-vragen)
- [Beveiliging](#beveiliging)

## Beschrijving

Scouting Nederland is een classic theme met PHP-templates volgens de standaard WordPress-templatehiërarchie. Het werkt zelfstandig; aanvullende plugins zijn optioneel.

Het theme bevat onder andere:

- Responsive templates voor de homepage, pagina's, berichten, archieven, categorieën, zoekresultaten en 404-pagina's.
- Configureerbare hero en slider, met instellingen voor weergave per pagina.
- Widgetgebieden voor onder meer de hero, nieuwssectie, zijbalk en footer.
- Instellingen voor logo, kleuren, header, lettertypes en footer via de Customizer.
- `theme.json`-instellingen, editor-stijlen, block styles en een herbruikbaar block pattern.
- Reacties met threaded replies en paginering.
- Presentatie-aanpassingen voor pagina's die met Elementor zijn opgebouwd. Elementor is niet vereist.

Het theme registreert zelf **geen aangepaste Gutenberg-blokken**. Scouting-specifieke blokken worden als afzonderlijke companion-plugin aangeboden en maken geen deel uit van het theme-pakket.

## Screenshots

### Themaweergave

![Scouting Nederland theme](screenshot.png)

## Installatie

1. Download het theme-ZIP-bestand via [GitHub Releases](https://github.com/scouting-nl/wordpress-theme/releases/latest).
2. Open in WordPress **Weergave > Thema's > Nieuw thema toevoegen > Thema uploaden**.
3. Upload het ZIP-bestand en activeer **Scouting Nederland**.
4. Wijs de hoofd- en footermenu's toe en pas de vormgeving aan via **Weergave > Customizer**.

Het theme vereist WordPress 7.0 of hoger en PHP 7.4 of hoger.

## Instellingen

Gebruik **Weergave > Customizer** om onder meer het logo, kleuren, header, standaard hero, lettertypes en footerinformatie in te stellen.

Per pagina kunnen aanvullende instellingen beschikbaar zijn voor de hero, paginatitel, zijbalk, hero-lettertype en slider.

## Optionele plugins

- [Scouting OpenID Connect](https://wordpress.org/plugins/scouting-openid-connect/) voegt aanmelden met Scouting Nederland toe.
- Scouting Nederland Blocks is een afzonderlijke companion-plugin voor Scouting-specifieke Gutenberg-blokken. Deze blokken zijn niet opgenomen in dit theme.

## Veelgestelde vragen

### Is dit een block theme?

Nee. Dit is een classic PHP theme met ondersteuning voor de block editor, editor-stijlen, block styles, een herbruikbaar pattern en instellingen in `theme.json`.

### Heb ik plugins nodig?

Nee. Het theme werkt zelfstandig. Scouting OpenID Connect en Scouting Nederland Blocks zijn optioneel en worden afzonderlijk geïnstalleerd.

### Bevat het theme Scouting Nederland-blokken?

Nee. Het theme registreert geen aangepaste bloktypen. Installeer daarvoor de afzonderlijke Scouting Nederland Blocks-plugin wanneer die voor je WordPress-installatie beschikbaar is.

### Werkt het theme met Elementor?

Het theme bevat enkele presentatie-aanpassingen voor Elementor-pagina's. Elementor zelf is niet vereist.

## Beveiliging

Meld vermoedelijke kwetsbaarheden privé volgens de [beveiligingsrichtlijnen](.github/SECURITY.md). Plaats geen kwetsbaarheidsdetails, geheimen, tokens of persoonsgegevens in openbare issues of discussies.
