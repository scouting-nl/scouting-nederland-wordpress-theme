# Scouting Nederland

Een classic WordPress thema voor Scouting Nederland websites, met ondersteuning voor hero-secties, sliders, speltakken, sidebar-instellingen, editor-styles, `theme.json` en Gutenberg-blokken.

Het thema volgt de WordPress Theme Developer Handbook-opzet met een enkele root fallback-loader voor classic theme compatibility, template bodies in `templates/`, herbruikbare template parts in `parts/`, custom PHP in `inc/` en assets in `assets/`.

## Functies

- Aanpasbare hero per pagina
- Slider ondersteuning
- Speltakken-grid Gutenberg block
- Optie om sidebar per pagina te verbergen
- Thema-instellingen via de WordPress Customizer
- Ondersteuning voor eigen kleuren
- Responsive ontwerp voor desktop, tablet en mobiel
- Elementor-vriendelijke paginatemplates

## Installatie

1. Download het zipbestand van het thema.
2. Ga in WordPress naar **Weergave > Thema's**.
3. Klik op **Nieuw thema toevoegen**.
4. Klik op **Thema uploaden**.
5. Upload het `.zip` bestand.
6. Activeer het thema.
7. Installeer optioneel de plugin `scouting-openid-connect` wanneer inloggen via Scouting gewenst is.
8. Configureer het thema via **Weergave > Customizer** en wijs het hoofdmenu en footermenu toe.

## Aanbevolen plugins

Voor volledige werking kunnen deze plugins nodig of handig zijn:

- `scouting-openid-connect`, indien login via Scouting gewenst is. Het thema toont beheerders hiervoor ook een installatiemelding in WordPress.
- Eventuele SEO- of cachingplugin naar keuze

## Thema-structuur

- `style.css` - verplichte stylesheet met de WordPress thema-header.
- `index.php` - verplichte fallback-loader die de juiste template uit `templates/` kiest.
- `functions.php` - laadpunt voor setup, assets en losse modules.
- `theme.json` - globale editorinstellingen, kleuren, lettertypes en layoutmaten.
- `templates/` - template bodies voor index, pagina's, berichten, archieven, zoekresultaten en 404.
- `parts/` - herbruikbare template parts voor header, footer, sidebar en content.
- `assets/` - CSS, JavaScript, fonts en afbeeldingen.
- `inc/` - Customizer, navigatie, blocks, zoeklogica, page settings en admin notices.
- `screenshot.png` - screenshot voor **Weergave > Thema's**.
- `readme.txt` - installatie-, release- en gebruikersinformatie.

## Thema-instellingen

De meeste instellingen zijn te vinden via **Weergave > Customizer**.

Daar kun je onder andere instellen:

- Logo
- Kleuren
- Headerinstellingen
- Standaard hero-instellingen
- Lettertypes
- Footerinformatie

## Pagina-instellingen

Per pagina kunnen extra opties beschikbaar zijn, zoals:

- Hero verbergen
- Paginatitel verbergen
- Sidebar verbergen
- Alternatief lettertype voor de hero
- Slider wel of niet tonen

## Elementor

Het thema bevat twee paginatemplates voor Elementor:

- **Elementor Full Width** - gebruikt de thema-header en footer, maar laat Elementor de volledige contentbreedte gebruiken.
- **Elementor Canvas** - laat Elementor de volledige pagina beheren zonder thema-header en footer.

Wanneer een normale pagina met Elementor is gebouwd, verbergt het standaard paginatemplate automatisch de thema-hero, sidebar en extra widgetbanden zodat Elementor niet tegen de thema-layout werkt.

## Gutenberg blokken

Het thema bevat meerdere aangepaste Gutenberg-blokken onder de Scouting Nederland blokcategorie.

### Featured items

Toont uitgelichte onderdelen of snelle links, bijvoorbeeld naar belangrijke pagina's.

### Callout

Een opvallend tekstblok met titel, tekst, knop en optionele scouting-afbeelding.

### Nieuwsitems

Toont nieuwsberichten uit WordPress, bijvoorbeeld de nieuwste berichten of berichten uit een bepaalde categorie.

### Speltakken-grid

Toont meerdere speltakken met naam, leeftijd, opkomsttijd, locatie, afbeelding en knop.

### Twee kolommen

Een layoutblok waarmee content in twee kolommen kan worden geplaatst.

### Scouting OIDC

Toont een login-knop of login-link wanneer de Scouting OIDC plugin beschikbaar is.
