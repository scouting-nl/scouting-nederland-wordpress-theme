# Publicatiecheck

Dit thema is opgeschoond richting de WordPress Theme Developer Handbook, maar is nog niet klaar voor upload naar WordPress.org. De onderstaande punten moeten voor een officiele release worden opgelost.

## Gecontroleerd

- De native classic-theme templatehierarchie wordt gebruikt.
- De volledige hierarchy-templates staan direct in de root, zonder een-op-een wrappers.
- `header.php`, `footer.php` en `sidebar.php` worden via de standaard WordPress-functies geladen.
- De verplichte classic-theme hooks en template-markup zijn aanwezig: `language_attributes()`, `wp_head()`, `body_class()`, `wp_body_open()`, `post_class()`, `wp_link_pages()` en `wp_footer()`.
- Bootstrap en Font Awesome worden lokaal geladen in plaats van via CDN's.
- De niet-geminificeerde Bootstrap- en Font Awesome-CSS staat naast de gebruikte geminificeerde bestanden.
- Merkkleuren worden in `theme.json` beheerd en via WordPress-presetvariabelen in de CSS gebruikt.
- De legacy `template.css`-bundel is vervangen door Bootstrap 5.3.8 en een kleine WordPress-compatibiliteitslaag.
- De front-end bevat een toetsenbordtoegankelijke skiplink.
- De ongekoppelde home-fields-module, de exclusieve CSS-selectors daarvan en verweesde afbeeldingen zijn verwijderd.
- Metabox-invoer wordt ontslashed, gevalideerd en gesanitized voordat deze wordt opgeslagen.
- De eigen CSS-selectors volgen `scouting-nederland-{bestandsnaam}-*`; override-lagen zijn per component geconsolideerd en de actieve stijlen zijn gedocumenteerd.
- Een statische controle vond geen automatisch geladen externe front-endresources of tracking. Een externe video-URL kan alleen door een beheerder expliciet worden ingesteld.
- De optionele OpenID-notice verwijst naar een WordPress.org-plugin, is door de gebruiker gestart en is permanent weg te klikken.
- `screenshot.png` voldoet technisch aan de maximumafmeting en 4:3-verhouding: 1200 x 900 px.

## Blokkers voor WordPress.org

Zie [Theme Review Required](https://make.wordpress.org/themes/handbook/review/required/#5-functionality-and-features).
3. Vul `== Resources ==` in `readme.txt` aan met volledige bron-, copyright-, licentie- en toestemminggegevens voor alle gebundelde assets, waaronder alle afbeeldingen, `screenshot.png`, `snl-logo.svg` en de Scouting-merken. De huidige lijst dekt alleen de externe libraries en fonts.
4. Vervang `assets/images/default-slide.jpg` en `screenshot.png` door beelden zonder herkenbare kinderen. De huidige bestanden tonen herkenbare scouts; bronvermelding alleen maakt die beelden niet toegestaan in de directory. Houd voor de vervangende screenshot 1200 x 900 px en een 4:3-verhouding aan.
5. Classic themes in de WordPress.org-directory moeten comments ondersteunen. Voeg `comments.php`, `comments_template()` op singular content, threaded comments, `comment-reply` en passende styling toe.

## Releasevalidatie

- Test met `WP_DEBUG` en `SCRIPT_DEBUG` ingeschakeld; er mogen geen PHP-, JavaScript- of deprecated notices zijn.
- Draai Theme Check en PHP_CodeSniffer met WordPress Coding Standards op het uiteindelijke releasepakket en los alle relevante meldingen op.
- Importeer de WordPress Theme Unit Test Data en controleer lange titels, alle kernblokken, afbeeldingen, widgets, menus, paginering en geneste comments.
- Test front page, page, single, archive, category, search en 404 op desktop, tablet en mobiel, inclusief de Customizer, editor styles en de companion-plugin.
- Test toetsenbordnavigatie, zichtbare focus en skiplink, en voer een toegankelijkheidsscan uit met bijvoorbeeld axe of WAVE.
- Controleer met een performance-tool dat gebundelde media en CSS/JavaScript geen onnodige laadtijd veroorzaken.
- Bouw een schone zip zonder `.git`, tijdelijke bestanden, verboden bestandstypen of niet-herdistribueerbare assets.

## Bronnen

- [Publishing Themes](https://developer.wordpress.org/themes/advanced-topics/publishing-themes/)
- [Testing](https://developer.wordpress.org/themes/advanced-topics/testing/)
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/)
- [Theme Review Required](https://make.wordpress.org/themes/handbook/review/required/)

## Companion-plugin voor Gutenberg-blokken

De custom blocks zijn vanaf themeversie 1.0.1 verplaatst naar de losse plugin **Scouting Nederland Blocks** (`scouting-nederland-blocks`). Het theme registreert zelf geen custom block types meer.

Voor publicatie op WordPress.org geldt dat het theme de plugin alleen als **optioneel aanbevolen** mag tonen wanneer de plugin zelf in de WordPress.org Plugin Directory staat. De plugin mag niet als ZIP in het theme worden opgenomen en niet automatisch worden geïnstalleerd.

Tijdens lokale ontwikkeling kan `scouting-nederland-blocks.zip` handmatig worden geïnstalleerd via **Plugins > Nieuwe plugin > Plugin uploaden**. Zodra de plugin onder slug `scouting-nederland-blocks` op WordPress.org staat, werkt de installatieknop in de beheerder rechtstreeks.