# Publicatiecheck

Dit thema is opgeschoond richting het WordPress Theme Developer Handbook, maar is nog niet klaar voor upload naar WordPress.org. De onderstaande punten moeten voor indiening in de Theme Directory worden opgelost.

## Reikwijdte van de controle

Deze checklist is op 2026-11-04 vergeleken met [Theme Review Required](https://make.wordpress.org/themes/handbook/review/required/) en de huidige repository. Het gaat om de eisen voor opname in de WordPress.org Theme Directory. Een controle van code en bestanden is geen volledige praktijktest, toegankelijkheidsaudit of bevestiging van beeldrechten. Niet uitgevoerde releasecontroles blijven hieronder open.

## Statisch gecontroleerd

- De native classic-theme templatehierarchie wordt gebruikt.
- De volledige hierarchy-templates staan direct in de root, zonder een-op-een wrappers.
- `header.php`, `footer.php` en `sidebar.php` worden via de standaard WordPress-functies geladen.
- De verplichte classic-theme hooks en template-markup zijn aanwezig: `language_attributes()`, `wp_head()`, `body_class()`, `wp_body_open()`, `post_class()`, `wp_link_pages()` en `wp_footer()`.
- Bootstrap en Font Awesome worden lokaal geladen in plaats van via CDN's.
- De niet-geminificeerde Bootstrap- en Font Awesome-CSS staat naast de gebruikte geminificeerde bestanden.
- Hero-koppen gebruiken Impact als systeemlettertype met lokale fallbacks. Er wordt geen Impact-fontbestand gebundeld of extern gedownload.
- Merkkleuren worden in `theme.json` beheerd en via WordPress-presetvariabelen in de CSS gebruikt.
- De legacy `template.css`-bundel is vervangen door Bootstrap 5.3.8 en een kleine WordPress-compatibiliteitslaag.
- De front-end bevat een skiplink en focusstyling. Zichtbaarheid, toetsenbordbediening en daadwerkelijke focusverplaatsing moeten nog in een actieve browser worden gecontroleerd.
- Reacties zijn geimplementeerd: `comments.php`, `comments_template()` bij berichten, reactiepaginering, een reactieformulier, voorwaardelijk `comment-reply` en bijbehorende CSS zijn aanwezig. Test geneste reacties nog met echte WordPress-content.
- De ongekoppelde home-fields-module, de exclusieve CSS-selectors daarvan en verweesde afbeeldingen zijn verwijderd.
- Metabox-invoer heeft capability- en noncecontroles, wordt ontslashed en wordt gevalideerd of gesanitized voordat deze wordt opgeslagen.
- De eigen CSS-selectors volgen `scouting-nederland-{bestandsnaam}-*`; override-lagen zijn per component geconsolideerd en de actieve stijlen zijn gedocumenteerd.
- Scripts, stylesheets en fonts worden lokaal geladen; er is geen trackingcode aangetroffen. Externe video-URLs zijn een uitzondering: `inc/slider.php` kan daarvoor `wp_oembed_get()` aanroepen. Het instellen door een beheerder bewijst nog niet dat eventuele externe requests voldoen aan de vereiste toestemming; controleer dit afzonderlijk.
- De optionele OpenID-notice verwijst naar een WordPress.org-plugin, is door de gebruiker gestart en is permanent weg te klikken.
- `screenshot.png` voldoet technisch aan de maximumafmeting en 4:3-verhouding: 1200 x 900 px.

## Blokkers voor WordPress.org

Deze punten volgen uit de hieronder gelinkte eisen; reeds aanwezige implementaties staan niet meer als ontbrekende functionaliteit vermeld.

1. **Licenties en bronvermelding:** vul `== Resources ==` in `readme.txt` aan met controleerbare bron-, copyright- en licentiegegevens voor alle gebundelde assets. De eigen afbeeldingen en screenshot staan daar nog als `License: not documented`. Verifieer de GPL-compatibele rechten voor foto's, illustraties, logo en merken; alleen een copyrightvermelding is geen licentie. Bij `assets/fonts/` ontbreken afzonderlijke licentieteksten voor Poppins en Roboto Slab: verifieer de exacte bron/licentie van de gebruikte bestanden en voeg de vereiste licentieteksten en notices toe. Zie [Licensing & copyright](https://make.wordpress.org/themes/handbook/review/required/#1-licensing-copyright).
2. Controleer de leeftijden van herkenbare personen in de meegeleverde foto's en de screenshot. De [WordPress.org-eis onder Files > Images](https://make.wordpress.org/themes/handbook/review/required/#9-files) zegt expliciet: "images that show children with recognizable facial or body features are not permitted." Dit betreft assets in het pakket voor de WordPress.org Theme Directory, niet alle foto's op een eigen WordPress-site. Uit de beelden alleen zijn de leeftijden niet vastgesteld. Als herkenbare kinderen zijn afgebeeld, vervang die beelden; bronvermelding of toestemming vervangt deze directory-eis niet. Controleer dit voor `assets/images/default-slide.jpg`, `screenshot.png` en de overige meegeleverde foto's. Houd voor een nieuwe screenshot maximaal 1200 x 900 px en een 4:3-verhouding aan.
3. **Plugin-aanbeveling:** `inc/admin-notices.php` toont een installatieaanbod voor `scouting-nederland-blocks` zonder te verifiëren dat de plugin gepubliceerd is. De [publieke pluginvermelding](https://wordpress.org/plugins/scouting-nederland-blocks/) is bij deze controle niet beschikbaar; de WordPress.org-plugin-API geeft `Plugin not found`. Publiceer de plugin eerst of schakel de aanbeveling voor de directoryversie uit totdat de vermelding bestaat. Alleen een optionele tekst en een installatieactie door de gebruiker zijn niet voldoende als de aanbevolen plugin niet op WordPress.org staat. Zie [Plugins](https://make.wordpress.org/themes/handbook/review/required/#6-plugins).
4. **Vertaalbare patterninhoud:** de standaardinhoud van het pattern in `inc/setup.php` bevat onder andere `Belangrijke informatie`, `Voeg hier een korte toelichting toe voor bezoekers van de website.` en `Lees meer` als hardcoded PHP-strings zonder gettext. Maak ook deze teksten vertaalbaar met de textdomain `scouting-nederland`. De tijdelijke uitzondering voor tekst in HTML-templatebestanden dekt deze in PHP opgebouwde patterninhoud niet. Zie [Language & internationalization](https://make.wordpress.org/themes/handbook/review/required/#8-language-internationalization).
5. **Consistente compatibiliteitsinformatie:** `README.md` noemt PHP 7.4, terwijl `style.css` en `readme.txt` PHP 8.2 aangeven. Kies de daadwerkelijk geteste minimumversie en lijn de vermeldingen op elkaar af. Claims zoals `Tested up to` moeten overeenkomen met de uitgevoerde WordPress-tests. Zie [Files](https://make.wordpress.org/themes/handbook/review/required/#9-files).

## Controle per vereist onderwerp

| Onderwerp uit Theme Review Required | Uitkomst van deze controle | Resterende controle |
| --- | --- | --- |
| 1. Licensing & copyright | Bron- en licentievermeldingen zijn gedeeltelijk aanwezig; rechten op eigen beeldmateriaal zijn niet gedocumenteerd. | Assetinventaris, daadwerkelijke licenties en vereiste licentieteksten afronden. |
| 2. Privacy | Geen tracking aangetroffen; externe video-embeds zijn mogelijk. | Externe requests en vereiste toestemming testen en documenteren. |
| 3. Accessibility | Skiplink, labels en native bediening zijn aanwezig. | Volledige toetsenbord-, focus-, contrast- en responsive controle uitvoeren. Geen `accessibility-ready`-goedkeuring geclaimd. |
| 4. Code | WordPress-API's, escaping, nonces en prefixed PHP-functies zijn aanwezig. | Definitief pakket met linters en `WP_DEBUG` testen; dit is geen volledige veiligheidsaudit. |
| 5. Functionality and Features | Geen custom post types, custom blockregistraties of shortcodes in de theme-PHP gevonden; patterns en block styles blijven presentatief. | Controleren dat het definitieve pakket geen pluginfunctionaliteit of verwijderde niet-presentatieve core-hooks bevat. |
| 6. Plugins | Aanbevelingen zijn optioneel en installatie vereist een gebruikersactie. | De niet-gepubliceerde blocks-plugin niet aanbevelen in de directoryversie. |
| 7. Naming, spelling, and trademarks | Themanaam, slug en Scouting-logo zijn aanwezig. | Bevoegdheid voor de Scouting-merken en eventuele naamconflicten in de directory bevestigen. |
| 8. Language & internationalization | Textdomain is `scouting-nederland`; veel PHP-uitvoer gebruikt gettext. | Hardcoded patternteksten oplossen en een volledige vertaalcontrole uitvoeren. |
| 9. Files | Verplichte metadata/basisbestanden aanwezig; screenshot is 1200 x 900; allowlist bevatte 99 bestanden zonder gevonden verboden bestandsnamen. | Licenties, beeldregels, compatibiliteitsmetadata, consistente regeleinden en de definitieve download-ZIP controleren. |
| 10. Classic themes | Native templates, vereiste hooks, title-tag/feed-support en reacties op berichten zijn aanwezig. | Front-page-instellingen, widgets, paginering, formulieren en reacties met Theme Unit Test Data testen. |
| 11. Block themes | Niet van toepassing: dit is een classic theme met block-editorondersteuning. | Geen block-themebestanden toevoegen uitsluitend om deze niet-toepasselijke sectie af te vinken. |
| 12. Theme settings pages and onboarding | Customizer en presentatiegerichte postmeta; pluginnotices gebruiken core-UI, nonces en capabilitychecks. | Permanent verbergen, schermbeperking, bevoegdheden en gebruikersgestuurde acties in WordPress testen. |
| 13. Selling, credits, links, and spam | Footer-copyright gebruikt de sitenaam; sociale links komen uit instellingen. | Definitieve publieke credits, links, teksten en eventuele affiliatevermeldingen beoordelen. |
| 14. Theme author and theme upload restrictions | Niet vast te stellen uit deze repository. | De bevoegde inzender controleert accountregels, andere distributies en eventuele lopende theme-indieningen. |

## Releasevalidatie

Deze praktijktests zijn niet als afgerond gemarkeerd door de bovenstaande statische controle. Noteer bij vrijgave de gebruikte WordPress-/PHP-versies, het commit-ID en de resultaten.

- [ ] Test met `WP_DEBUG` en `SCRIPT_DEBUG` ingeschakeld; er mogen geen PHP-, JavaScript- of deprecated notices zijn. Activeer het theme en laad echte pagina's: statische Theme Check ontdekt niet iedere ontbrekende include.
- [ ] Draai Theme Check en PHP_CodeSniffer op het uiteindelijke releasepakket en ESLint/Stylelint op de volledige theme-targets. De CI-stappen bestaan, maar hun aanwezigheid is geen bewijs dat een nieuwe release is geslaagd.
- [ ] Importeer de WordPress Theme Unit Test Data en controleer lange titels, kernblokken, media, widgets, menu's, paginering en geneste reacties. Controleer ook pagina's met ingeschakelde reacties: de huidige `comments_template()`-aanroep staat alleen in `single.php`.
- [ ] Test front page, page, single, archive, category, search en 404 op desktop, tablet en mobiel, inclusief de Customizer en editor styles; test zowel zonder optionele plugins als met de companion-plugin.
- [ ] Test toetsenbordnavigatie, zichtbare focus en focusverplaatsing via de skiplink, en voer een toegankelijkheidsscan uit met bijvoorbeeld axe of WAVE.
- [ ] Controleer externe video-embeds op uitgaande requests, cookies en de vereiste toestemming voordat de externe inhoud wordt geladen.
- [ ] Controleer met een performance-tool dat gebundelde media en CSS/JavaScript geen onnodige laadtijd veroorzaken.
- [ ] Controleer de uiteindelijke download als direct installeerbare ZIP met `scouting-nederland/style.css`, zonder geneste ZIP, ontwikkelbestanden, verborgen bestanden, verboden bestandstypen of de companion-plugin. Controleer alle licentieteksten en bronvermeldingen.
- [ ] Actualiseer de screenshot na visuele wijzigingen; de juiste afmetingen alleen bewijzen niet dat de afbeelding de actuele themavormgeving weergeeft.

## Bronnen

- [Publishing Themes](https://developer.wordpress.org/themes/advanced-topics/publishing-themes/)
- [Testing](https://developer.wordpress.org/themes/advanced-topics/testing/)
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/)
- [Theme Review Required](https://make.wordpress.org/themes/handbook/review/required/)

## Companion-plugin voor Gutenberg-blokken

De custom blocks zijn ondergebracht in de losse plugin **Scouting Nederland Blocks** (`scouting-nederland-blocks`). Het theme registreert zelf geen custom block types en neemt de plugin niet op in het distributiepakket. Een historische versievermelding zoals "vanaf 1.0.1" is hier niet als vaststaand feit opgenomen: de huidige theme-metadata geeft versie 1.0.0 aan.

Voor publicatie op WordPress.org geldt dat het theme de plugin alleen als **optioneel aanbevolen** mag tonen wanneer de plugin zelf in de WordPress.org Plugin Directory staat. De plugin mag niet als ZIP in het theme worden opgenomen en niet automatisch worden geïnstalleerd.

Tijdens lokale ontwikkeling kan een afzonderlijk verkregen plugin-ZIP handmatig worden geinstalleerd via **Plugins > Nieuwe plugin > Plugin uploaden**. Neem die ZIP nooit op in het theme-pakket. Verifieer een daadwerkelijke WordPress.org-vermelding voordat de directoryversie van het theme een installatieknop voor deze slug toont.
