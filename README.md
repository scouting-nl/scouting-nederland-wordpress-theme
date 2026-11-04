# Scouting Nederland

Een classic WordPress-thema voor Scouting Nederland-websites, met ondersteuning voor hero-secties, sliders, sidebar-instellingen, reacties, editor-styles, block styles, een block pattern en `theme.json`.

Het thema gebruikt de native WordPress-templatehiërarchie met de volledige templates in de root. Herbruikbare template parts staan in `parts/`, custom PHP-modules staan in `inc/` en assets staan in `assets/`.

## Functies

- Aanpasbare hero per pagina
- Sliderondersteuning
- Optie om de sidebar per pagina te verbergen
- Thema-instellingen via de WordPress Customizer
- Centrale kleuren, lettertypes en layoutinstellingen in `theme.json`
- Block editor-styles en block styles
- Een herbruikbaar WordPress block pattern
- Reacties op berichten, inclusief threaded replies en commentpaginering
- Standaard WordPress-zoekformulier via `get_search_form()`
- Responsive ontwerp voor desktop, tablet en mobiel
- Automatische ondersteuning voor pagina's die met Elementor zijn opgebouwd

## Installatie

1. Download het zipbestand van het thema.
2. Ga in WordPress naar **Weergave > Thema's**.
3. Klik op **Nieuw thema toevoegen**.
4. Klik op **Thema uploaden**.
5. Upload het `.zip`-bestand.
6. Activeer het thema.
7. Installeer optioneel de plugin `scouting-openid-connect` wanneer inloggen via Scouting gewenst is.
8. Configureer het thema via **Weergave > Customizer** en wijs het hoofdmenu en footermenu toe.

## Aanbevolen plugins

Voor aanvullende functionaliteit kunnen deze plugins handig zijn:

- `scouting-openid-connect`, indien login via Scouting gewenst is. Het thema toont beheerders hiervoor ook een installatiemelding in WordPress.
- `scouting-thema-blokken`, indien je gebruik wil maken van speciale Scouting Nederland blokken dien je deze plugin te installeren. Het thema toont beheerders hiervoor ook een installatiemelding in WordPress.
- Eventuele SEO- of cachingplugin naar keuze
- Een SEO- of cachingplugin naar keuze.

## Thema-structuur

- `style.css` - verplichte stylesheet met de WordPress-themaheader.
- `index.php`, `front-page.php`, `page.php`, `single.php`, `archive.php`, `category.php`, `search.php` en `404.php` - standaard WordPress-templatehiërarchie.
- `header.php`, `footer.php` en `sidebar.php` - standaard classic-theme entry points.
- `comments.php` - weergave van reacties en het standaard WordPress-reactieformulier.
- `searchform.php` - het zoekformulier dat via `get_search_form()` wordt gebruikt.
- `functions.php` - laadpunt voor theme setup, assets en losse modules.
- `theme.json` - bron voor globale editorinstellingen, merkkleuren, lettertypes en layoutmaten.
- `parts/` - herbruikbare template parts, waaronder de gedeelde contentweergave.
- `assets/` - modulaire CSS, JavaScript, fonts, afbeeldingen en lokale vendorbestanden.
- `inc/` - setup, Customizer, navigatie, zoeklogica, slider, page settings, assets en admin notices.
- `screenshot.png` - screenshot voor **Weergave > Thema's**.
- `readme.txt` - WordPress-thema-informatie, requirements, changelog en bronvermeldingen.

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