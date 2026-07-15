# Publicatiecheck

Dit thema is opgeschoond richting de WordPress Theme Developer Handbook, maar is nog niet klaar voor upload naar WordPress.org. De onderstaande punten moeten voor een officiele release worden opgelost.

## Afgerond

- De native classic-theme templatehierarchie wordt gebruikt.
- De volledige hierarchy-templates staan direct in de root, zonder een-op-een wrappers.
- `header.php`, `footer.php` en `sidebar.php` worden via de standaard WordPress-functies geladen.
- Bootstrap en Font Awesome worden lokaal geladen in plaats van via CDN's.
- Merkkleuren worden in `theme.json` beheerd en via WordPress-presetvariabelen in de CSS gebruikt.
- De legacy `template.css`-bundel is vervangen door Bootstrap 5.3.8 en een kleine WordPress-compatibiliteitslaag.
- De front-end bevat een toetsenbordtoegankelijke skiplink.
- De ongekoppelde home-fields-module, de exclusieve CSS-selectors daarvan en verweesde afbeeldingen zijn verwijderd.
- Metabox-invoer wordt ontslashed, gevalideerd en gesanitized voordat deze wordt opgeslagen.

## Blokkers

1. De custom Gutenberg-blokken in `inc/blocks.php` en `assets/js/blocks/` horen volgens de Theme Review-regels in een losse companion-plugin. Verplaats de registraties en render-callbacks pas samen met een migratieplan, zodat bestaande content blijft werken.
2. `assets/fonts/urban-jungle.otf` is door de maker alleen vrijgegeven voor persoonlijk gebruik. Voor WordPress.org is een GPL-compatibele herdistributielicentie of een visueel goedgekeurd alternatief nodig.
3. `assets/css/header.css` en `assets/css/homepage.css` bevatten nog opeenvolgende override-lagen. De veilige verwijderingen zijn met computed-style-vergelijkingen gecontroleerd; verdere consolidatie vraagt om een representatieve WordPress-testsite en screenshots per viewport.
4. Voeg volledige bron-, copyright- en licentiegegevens toe voor alle afbeeldingen en graphics. Controleer ook of de screenshot voldoet aan de actuele regels voor herkenbare personen.
5. Classic themes in de WordPress.org-directory moeten comments ondersteunen. Voeg `comments.php`, threaded comments en passende styling toe nadat het gewenste ontwerp is vastgesteld.

## Releasevalidatie

- Test met `WP_DEBUG` en `SCRIPT_DEBUG` ingeschakeld.
- Draai Theme Check en WordPress Coding Standards op het uiteindelijke releasepakket.
- Test front page, page, single, archive, category, search en 404 op desktop, tablet en mobiel.
- Test navigatie, Customizer-instellingen, widgets, editor styles en de companion-plugin samen.
- Bouw een schone zip zonder `.git`, tijdelijke bestanden of ontwikkelbestanden.
