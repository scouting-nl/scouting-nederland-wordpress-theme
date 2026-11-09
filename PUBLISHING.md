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
- De optionele OpenID-notice beveelt uitsluitend de gepubliceerde plugin [Scouting OpenID Connect](https://wordpress.org/plugins/scouting-openid-connect/) aan. Niet geinstalleerd: installeren; geinstalleerd maar inactief: activeren met de juiste pluginrechten. Een actieve of netwerkactieve plugin en een expliciet verborgen melding tonen geen aanbeveling. Installatie/activatie gebeurt alleen na een gebruikersactie; permanent verbergen gebruikt een nonce en gebruikersmeta. Voor het tonen van de melding worden geen externe API-calls uitgevoerd.
- De aanbeveling en installatie-/activatieknoppen voor de nog niet gepubliceerde Blocks-plugin zijn verwijderd. Voeg die pas opnieuw toe na bevestiging van een daadwerkelijke WordPress.org-vermelding.
- De drie standaardteksten in het informatiepattern worden met `esc_html__()` en textdomain `scouting-nederland` vertaald en ge-escaped. De Nederlandse terugvaltekst en blokstructuur zijn behouden; er wordt geen site- of gebruikerstaal afgedwongen.
- Het thema schakelt `custom-header` en `custom-background` niet in, zodat hun standaard Customizer-controls niet beschikbaar zijn en Colors als lege sectie niet wordt getoond. Handmatige `remove_section()`-aanroepen zijn verwijderd. De Scouting-kleur- en hero-instellingen, opgeslagen waarden en de terugval naar eerder opgeslagen headerbeelden blijven behouden; de core-achtergrondfunctie is niet meer actief.
- De licentieteksten en credits voor Poppins 4.004, Roboto Slab 2.002 en de in Bootstrap opgenomen Popper 2.11.8 zijn toegevoegd of gecorrigeerd. De hieronder beschreven assetcontrole laat de beeldrechten expliciet open.
- `screenshot.png` voldoet technisch aan de maximumafmeting en 4:3-verhouding: 1200 x 900 px.

## Blokkers voor WordPress.org

Deze punten volgen uit de hieronder gelinkte eisen; reeds aanwezige implementaties staan niet meer als ontbrekende functionaliteit vermeld.

1. **Beeldrechten en oorspronkelijke bronnen:** de technische font- en librarylicenties zijn aangevuld. De beheerder heeft de hieronder genoemde eigen huisstijlelementen, merkafbeeldingen, banner en screenshot op 2026-11-09 vrijgegeven onder GPL-3.0-or-later. Deze vrijgave geldt niet voor de callouts van derden; hun oorspronkelijke bronnen en GPL-compatibele herdistributierechten blijven afzonderlijk te controleren. Merk- en portretrechten vallen niet automatisch onder de auteursrechtelijke GPL-vrijgave. Een stockfoto- of websitegebruikslicentie is niet automatisch toestemming voor verspreiding in een vrij themepakket. Zie de inventaris hieronder en [Licensing & copyright](https://make.wordpress.org/themes/handbook/review/required/#1-licensing-copyright).

## Asset- en licentiecontrole

Op 2026-11-04 zijn alle elf WOFF2-fonts, de 26 bestanden onder `assets/images/`, `screenshot.png` en de gebundelde librarylicenties gecontroleerd. De zeven losse fonts zijn met fontkit 2.0.4 gelezen; de rastermetadata is met exifr 7.1.3 gelezen en de SVG is als XML onderzocht. Metadata vermeldt credits, maar bewijst op zichzelf geen licentie of rechthebbendheid.

| Onderdeel | Vastgesteld en afgehandeld | Bron en licentie |
| --- | --- | --- |
| Zes Poppins-webfonts | Ingebedde versie 4.004 en copyright 2020 The Poppins Project Authors komen overeen met de gecorrigeerde credits. De volledige OFL is toegevoegd; fontbestanden zijn ongewijzigd. | [Project](https://github.com/itfoundry/Poppins), [matching OFL](https://github.com/google/fonts/blob/main/ofl/poppins/OFL.txt), `assets/fonts/Poppins-OFL.txt`. |
| Roboto Slab-webfont | Ingebedde versie 2.002, copyright 2018 The Roboto Slab Project Authors en Apache 2.0 zijn behouden. Volledige licentie toegevoegd; in de onderzochte upstreambron is geen apart `NOTICE`-bestand aangetroffen. | [Project](https://github.com/googlefonts/robotoslab), [upstreamlicentie](https://github.com/googlefonts/robotoslab/blob/main/LICENSE.txt), `assets/fonts/Roboto-Slab-LICENSE.txt`. |
| Bootstrap 5.3.8 | De vier CSS/JS-bestanden en MIT-licentie komen na LF-normalisatie overeen met de getagde release. De vendorbestanden zijn niet gewijzigd. | [Release](https://github.com/twbs/bootstrap/releases/tag/v5.3.8), `assets/vendor/bootstrap/LICENSE`. |
| Popper 2.11.8 | De versie volgt uit de Bootstrap 5.3.8-lockfile. De volledige MIT-notice van Federico Zivolo is toegevoegd buiten de vendormap. | [Releasebron](https://github.com/floating-ui/floating-ui/tree/v2.11.8), `assets/licenses/Popper-MIT.txt`. |
| Font Awesome Free 7.3.1 | CSS en licentie komen overeen met de Git-tag; de vier fonts zijn bytegelijk aan het officiele npm-pakket waarvan de SHA-512-integriteit is geverifieerd. De Git-tag bevat andere WOFF2-builds; die afwijking is dus geen reden geweest om werkende vendorfonts te vervangen. | [npm-release](https://www.npmjs.com/package/@fortawesome/fontawesome-free/v/7.3.1), `assets/vendor/font-awesome/LICENSE.txt`: code MIT, fonts SIL OFL 1.1, iconen CC BY 4.0 en aparte merknotitie. |

Voor de beeldbestanden is de inventaris:

| Bestanden | Aangetroffen credit of bronverwijzing | Nog nodig |
| --- | --- | --- |
| `callout-gps.png` | Ingebedde maker: Dmitry Sytnik; rechtenverwijzing `www.dasytnik.com`. | Oorspronkelijk beeld en GPL-compatibele verspreidingslicentie. |
| `callout-lamp.png` | Ingebedde maker/rechtencredit: Emmanuelle Grimaud. | Oorspronkelijk beeld en GPL-compatibele verspreidingslicentie. |
| `callout-touw.png` | Ingebedde maker: Serhiy Stakhnyk; rechtenverwijzing `www.sergofoto.com`. | Oorspronkelijk beeld en GPL-compatibele verspreidingslicentie. |
| `callout-zakmes.png` | Ingebedde maker/eigenaar: Dmitry Rukhlenko; copyright 2014; `http://www.f9photos.com`. | Oorspronkelijk beeld en GPL-compatibele verspreidingslicentie. |
| `callout-boei.png`, `callout-haken.png`, `callout-hout.png`, `callout-kompas.png`, `callout-zaklamp.png` | Geen bruikbare bron-/rechtenmetadata aangetroffen. | Maker, rechthebbende, oorspronkelijke bron en GPL-compatibele licentie. |
| `footer-grass.png`, `grass-*.png`, `skyline-*.png`, `stamp-shadow.png` | Eigen Scouting Nederland-huisstijlelementen; copyright Scouting Nederland. Door de beheerder vrijgegeven onder GPL-3.0-or-later. | Geen openstaande auteursrechtelijke licentievraag op basis van deze bevestigde vrijgave. |
| `favicon.png`, `snl-logo.svg` | Scouting-merkuitingen; copyright Scouting Nederland. Artwork vrijgegeven onder GPL-3.0-or-later. | Toepasselijke merkrechten afzonderlijk beoordelen; de GPL is een auteursrechtelijke licentie. |
| `default-slide.jpg` | Uitsnede van [Roverway 2018 (91)](https://www.flickr.com/photos/scoutingnederland/53558323048/) uit het [Roverway 2018-album](https://www.flickr.com/photos/scoutingnederland/albums/72177720315134523). Copyright Scouting Nederland; voor deze distributie vrijgegeven onder GPL-3.0-or-later, los van de Flickr-notice. Camera- en opnamemetadata zijn uit het origineel overgenomen; de individuele fotograaf is niet vermeld. | Portretrechten en de directorybeeldregels blijven afzonderlijk te controleren. |
| `screenshot.png` | Screenshot door Scouting Nederland; copyright Scouting Nederland en vrijgegeven onder GPL-3.0-or-later. | Rechten en licenties van eventueel getoonde beelden van derden afzonderlijk controleren. |

De bestandsnamen in de beeldinventaris zijn relatief aan `assets/images/`, behalve `screenshot.png` in de root. Tijdens de licentiecontrole van 2026-11-04 zijn geen beeld- of fontbinaries gewijzigd; de banner en screenshot zijn daarna door de beheerder vernieuwd en op 2026-11-05 opnieuw bekeken. De GPL-vrijgave voor de hierboven genoemde eigen beelden is op 2026-11-09 expliciet door de beheerder bevestigd. Niet geverifieerde rechten op beelden van derden blijven open.

## Uitgevoerde compatibiliteitsvalidatie

Op 2026-11-04 is een snapshot van de werkboom getest met **WordPress 7.1.4**, **PHP 8.2.35** en `WP_DEBUG`, `WP_DEBUG_LOG` en `SCRIPT_DEBUG` ingeschakeld. Git-basis: `ed6c0ba`, met lokale wijzigingen. De geisoleerde Docker-installatie gebruikte dezelfde 99 theme-bestanden als de CI-package-allowlist, zonder de optionele OpenID- of Blocks-plugin.

- Activering, bootstrap, de Bootstrap-navwalker, theme-supports, menulocaties en widgetgebieden: geslaagd. WordPress leest de metadata correct als minimaal PHP 8.2, minimaal WordPress 7.0 en `Tested up to: 7.1`.
- Tien desktopverzoeken op 1280 x 900: front page, berichtenindex, pagina, bericht, categorie, maandarchief, zoeken, 404 en paginering van categorie en bericht. Alle verzoeken gebruikten de verwachte template en HTTP-status; de 404 gaf correct HTTP 404.
- Mobiele controle op 375 x 812 van front page, pagina en bericht: juiste templates, geen horizontale overflow of kapotte afbeeldingen. Ook de desktopcontrole vond geen horizontale overflow of kapotte afbeeldingen.
- Themabeheer, dashboard, Customizer en block-editor: HTTP 200. Bij de browsercontroles zijn geen ongehanteerde JavaScriptfouten of HTTP-fouten bij lokale assets aangetroffen. Dit was een laadcontrole, geen volledige interactietest van alle instellingen.
- OpenID-adminmelding: een administrator ziet precies een melding. Een ongeldige verbergnonce geeft HTTP 403 en laat de melding staan. Geldig verbergen wordt opgeslagen in gebruikersmeta en blijft bij een nieuw dashboardverzoek van kracht.
- Theme Check 20260901 op het geinstalleerde themepakket: geen fouten of waarschuwingen; alleen een informatieve melding over de juiste textdomain `scouting-nederland`.
- De PHP-debuglog na de runtimecontroles was leeg (0 bytes). De WordPress-corebestanden voldeden aan de officiele checksums voor 7.1.4.

Beperkingen: dit bevestigt alleen deze versiecombinatie en de bovenstaande scenario's. WordPress 7.0, nieuwere PHP-versies, de volledige Theme Unit Test Data, Elementor, de optionele plugins en multisite zijn niet in deze run getest. De automatische kliktest voor antwoorden op reacties kon niet worden voltooid doordat de gedeelde browsertab `hidden` was; die kliktest is niet opnieuw uitgevoerd. Echte toetsenbord-, focus- en volledige interactiecontroles blijven open.

Testomgeving: de extra `--context=admin`-controle van WP-CLI 2.12.0 liep vast in de core-menu-initialisatie; de adminpagina's zijn vervolgens via een echte HTTP-sessie gecontroleerd. De initiele CLI-diagnostiek is apart bewaard en niet met de lege runtime-debuglog samengevoegd. De core-checksumcontrole meldde het Docker-hulpbestand `wp-config-docker.php` als extra bestand; dat bestand behoort niet tot het themepakket.

Tijdelijke rapporten staan buiten de repository in `%TEMP%/wordpress-theme-compat-21a33fd0d57948fe9aeac9296a1f1a7c/reports/`.

## Controle per vereist onderwerp

| Onderwerp uit Theme Review Required | Uitkomst van deze controle | Resterende controle |
| --- | --- | --- |
| 1. Licensing & copyright | Font- en librarylicenties zijn aangevuld en met metadata/upstream vergeleken; de volledige beeldinventaris en vier externe makers staan hierboven en in `readme.txt`. | Oorspronkelijke beeldbronnen, GPL-compatibele rechten en toepasselijke merk-/portrettoestemming aantonen. |
| 2. Privacy | Geen tracking aangetroffen; externe video-embeds zijn mogelijk. | Externe requests en vereiste toestemming testen en documenteren. |
| 3. Accessibility | Skiplink, labels en native bediening zijn aanwezig. | Volledige toetsenbord-, focus-, contrast- en responsive controle uitvoeren. Geen `accessibility-ready`-goedkeuring geclaimd. |
| 4. Code | WordPress-API's, escaping, nonces en prefixed PHP-functies zijn aanwezig. | Definitief pakket met linters en `WP_DEBUG` testen; dit is geen volledige veiligheidsaudit. |
| 5. Functionality and Features | Geen custom post types, custom blockregistraties of shortcodes in de theme-PHP gevonden; patterns en block styles blijven presentatief. | Controleren dat het definitieve pakket geen pluginfunctionaliteit of verwijderde niet-presentatieve core-hooks bevat. |
| 6. Plugins | Alleen de gepubliceerde OpenID-plugin wordt aanbevolen; de Blocks-melding is verwijderd. Installeren en activeren zijn gebruikersgestuurd en permanent verbergen is mogelijk. | Test de melding met echte WordPress-rollen en multisite. Verifieer publicatie voordat toekomstige plugin-aanbevelingen worden toegevoegd. |
| 7. Naming, spelling, and trademarks | Themanaam, slug en Scouting-logo zijn aanwezig. | Bevoegdheid voor de Scouting-merken en eventuele naamconflicten in de directory bevestigen. |
| 8. Language & internationalization | Textdomain is `scouting-nederland`; ook de standaardteksten in het pattern gebruiken gettext en HTML-escaping. | Een volledige vertaalcontrole met echte vertaalbestanden uitvoeren. |
| 9. Files | Verplichte metadata/basisbestanden aanwezig; screenshot is 1200 x 900; allowlist bevatte 99 bestanden zonder gevonden verboden bestandsnamen. | Licenties, beeldregels, compatibiliteitsmetadata, consistente regeleinden en de definitieve download-ZIP controleren. |
| 10. Classic themes | Native templates, vereiste hooks, title-tag/feed-support en reacties op berichten zijn aanwezig. | Front-page-instellingen, widgets, paginering, formulieren en reacties met Theme Unit Test Data testen. |
| 11. Block themes | Niet van toepassing: dit is een classic theme met block-editorondersteuning. | Geen block-themebestanden toevoegen uitsluitend om deze niet-toepasselijke sectie af te vinken. |
| 12. Theme settings pages and onboarding | Customizer en presentatiegerichte postmeta; pluginnotices gebruiken core-UI, nonces en capabilitychecks. Permanent verbergen en nonceweigering zijn met een administrator via HTTP getest. | Andere rollen, schermbeperking en de installatie-/activatieflows in WordPress testen. |
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
