nAGENTS.md

Wspólne instrukcje dla Codex, GitHub Copilot i Claude Code.
Edytuj wyłącznie ten plik. CLAUDE.md jest dowiązaniem do AGENTS.md.

Przenośny importer Figma → WP jest w **jednym** katalogu `osf-import/` (obok `app/`). Do innego motywu Sage kopiujesz ten folder — instrukcja: `osf-import/README.md`, uniwersalne reguły agenta: `osf-import/AGENTS.md`. `osf-import/project.php` to tylko reguły CBP (B2C ≠ Solutions); w innym projekcie ten plik usuń. Poniżej zostają zasady **tego** motywu.

Jedna gałąź robocza: `cursor-work`.
Nie twórz nowych branchy per podstrona, per blok, per import ani `cursor/<nazwa>-…`.
Nie otwieraj osobnych PR-ów dla kolejnych podstron — commituj i pushuj na `cursor-work`.
Jeśli sesja startuje na innej gałęzi, przełącz się na `cursor-work` (stwórz ją z aktualnego HEAD, gdy nie istnieje) i tam pracuj.
Nazwy `cursor` Git nie przyjmie, dopóki istnieją gałęzie `cursor/…` (konflikt ścieżki refs).

Kolejność jest sztywna — faz nie odwracaj. W jednym zadaniu z Figmy/screenem zrób fazę 1, a gdy bloki albo szablony są na miejscu, **od razu sam startuj fazę 2**. Dla Pages: JSON + assety + commit + push. Dla Blog / Blog-single: dopracowane Blade + JSON **wpisu** (`resources/imports/posts/`) + commit + push, **bez** JSON-a strony. Nie czekaj na osobną prośbę „krok 2”.

Faza 1 — bloki ACF albo szablony (najpierw, dla wszystkich wskazanych ramek):
- Weź ramki z Figmy (link z `node-id`) albo screen. Workflow MCP: „Working from screenshots or designs”.
- Najpierw rozstrzygnij typ ramki (patrz „Szablony WP, nie Pages” oraz „Źródło danych: Options i CPT”). `Blog` i `Blog-single` to **nie** Pages — od razu edytuj Blade listingu / wpisu, nie składaj strony Gutenberg.
- Dla prawdziwych Pages: `get_metadata` na **ramce podstrony** (tabela Devs), rozpakuj `Frame 4xx` / `Content` / `Banner & Problem`, zmapuj nazwane sekcje na bloki. Reuse before creation. Jeśli blok już czyta z Options albo CPT — nie twórz drugiej strony opcji ani repeatera z kafelkami w JSON strony.
- Brakujące bloki stwórz według anatomii ACF w tym pliku. Trigger: zwykła prośba + link Figma lub screen. Nie wymagaj skilla ani `/nowy-blok`.
- Nazwę nowego bloku bierz z nazwy warstwy/ramki sekcji w Figmie (patrz „Nazwa bloku z Figmy”). Jeśli użytkownik poda nazwę w prompcie, ta wygrywa.
- Nie pisz JSON-a importu, zanim bloki potrzebne na tych podstronach są w motywie. Dla Blog / Blog-single JSON-a **strony** nie pisz wcale — treść artykułu idzie do JSON-a wpisu (faza 2).

Faza 2 — treść (automatycznie po fazie 1, ten sam agent / ta sama sesja):
- Tylko Pages: JSON `resources/imports/<slug>.json` (tytuł, slug, status `draft`, bloki ACF, dane, obrazy) + assety w `resources/imports/assets/`.
- Bloki z Options / CPT: w JSON strony tylko pola lokalne (tło, nagłówek listingu, override CTA). Kafelki opinii, certyfikatów, logotypów i ofert **nie** idą do JSON strony — patrz „Źródło danych: Options i CPT”.
- Zdjęcia (hero, problem, about, action, kafelki) zapisuj jako **JPG**, nie PNG. Z Figmy: `download_assets` z `defaultFormat: "jpg"` na węźle **samego zdjęcia** (prostokąt fill), nie na całej ramce z menu. Jeśli fill wraca jako PNG — skonwertuj do JPG zanim zapiszesz do `resources/imports/assets/`. Ikony i logotypy zostają SVG.
- Każda podstrona ma własne pliki (`about-hero.jpg`, nie `b2b-hero.png`), nawet gdy Figma współdzieli fill — inaczej w JSON-ie i w bibliotece mediów WP ląduje cudze zdjęcie.
- Blog / Blog-single: **nie** twórz `blog.json` / `blog-single.json` i **nie** dopisuj ich do `wp osf page import`. Dopracuj szablony Blade i partiale. Copy artykułu zapisz jako **wpis**: `resources/imports/posts/<slug>.json` + HTML + miniaturka JPG. Dolne CTA strony: `@include('partials.cta')`. Żółte CTA w środku artykułu to blok ACF `action` (`CTA - Wpis`) przez `embeds` + znacznik `<!-- osf:embed:action -->` w HTML — nie twardy HTML w treści.
- Commit i push na `cursor-work`.
- **Import WP-CLI zawsze zostaje u użytkownika** (lokalny WordPress / LocalWP). Agent w chmurze nie ma bazy WP — nie uruchamiaj `wp osf page import`, `wp osf offer import`, `wp osf post import`, `git pull` na maszynie użytkownika, `yarn build` ani `wp acorn acf:cache`.
- `wp osf page import` **nadpisuje** stronę o tym samym slugu (nie drugi szkic). Stary B2C z blokiem `solutions` znika po imporcie `b2c.json` (Wehelp). B2C z `solutions` w JSON-ie importer odrzuca.
- W podsumowaniu wypisz konkretne komendy: `git pull origin cursor-work`, `wp osf page import resources/imports/<slug>.json` dla każdej **strony**, `wp osf offer import resources/imports/offers/<slug>.json` dla każdego **wpisu CPT oferta** oraz `wp osf post import resources/imports/posts/<slug>.json` dla każdego **wpisu**.

W podsumowaniu wymień zmienione pliki (w tym Blade listingu/wpisu), sprawdzenia i wymagane komendy użytkownika.

Szablony WP, nie Pages

Ramki Figmy `Blog` i `Blog-single` (też `Blog - single`, cała podstrona `Blogs`) **nie są stronami** z Kokpitu → Strony. Nie składaj ich z bloków ACF przez importer.

| Ramka Figma | Co to jest w WP | Pliki, które masz zmienić |
|---|---|---|
| `Blog` | listing wpisów: `is_home()` (Ustawienia → Czytanie → strona wpisów) oraz `is_category()` | `resources/views/home.blade.php` (główny listing; **utwórz**, gdy brakuje), `resources/views/category.blade.php`, kafelki `resources/views/partials/content-post.blade.php`. `partials/content.blade.php` zostaw dla wyszukiwarki. `index.blade.php` tylko jako fallback pętli, nie jako „strona Blog”. |
| `Blog-single` / `Blog - single` | pojedynczy wpis (`post`, `is_single()`) | `resources/views/single.blade.php`, `resources/views/partials/content-single.blade.php` + JSON wpisu `resources/imports/posts/<slug>.json` |

Blok ACF `posts` (`app/Blocks/Posts.php`, widok `resources/views/blocks/posts.php`) to lista wpisów **wpleciona w inną stronę** (np. homepage). Nie zastępuje widoku bloga.

Na tych widokach pomiń chrome (`menu`, `header`, `footer`) tak samo jak na Pages. CTA bierz z opcji motywu (`g_octa` / blok opcji) przez **jeden** include:

```
@include('partials.cta')
```

Partial `resources/views/partials/cta.blade.php` to ten sam snippet (`get_field('g_octa', 'option')` + `@include('blocks.cta')`). **Nie dodawaj drugiego CTA**, gdy widok już ma `@include('partials.cta')` albo ręczny blok:

```
@php
$g_octa = get_field('g_octa', 'option');
$form = true;
$sectionClass = '-smt';
$section_id = '';
$section_class = '';
$background = 'none';
@endphp
@include('blocks.cta')
```

Nie importuj bloku `cta` jako osobnej strony Blog / Blog-single.

Import wpisu (Blog-single)

Ramka `Blog-single` to treść **wpisu** (`post`), nie strony. Po dopracowaniu Blade zapisz copy z Figmy do:

- `resources/imports/posts/<slug>.json` — `title`, `slug`, `status: draft`, opcjonalnie `date`, `author`, `categories`, `excerpt`, `featured_image: {src, alt}`, `content` albo `content_file`, oraz `embeds` (blok `action` / CTA - Wpis w środku artykułu)
- HTML Gutenberg (`core/paragraph`, `core/heading`, `core/list`, `core/html`) — nagłówki H2 z Figmy, żeby spis treści w `content-single` się zbudował
- miniaturka JPG w `resources/imports/assets/`

Nie wymyślaj sekcji, których nie ma w body ramki (spis treści w Figmie bywa dłuższy niż artykuł). Autora przypisz tylko, gdy w WP jest użytkownik o tej samej nazwie wyświetlanej.

Lokalnie: `wp osf post import resources/imports/posts/<slug>.json`

Źródło danych: Options i CPT

Nawias na końcu nazwy ramki Figmy to znacznik źródła, nie część sluga ani nazwy bloku.
Przed normalizacją warstwy zetnij: `(Options)`, `(CPT)`, `(CPT: slug)`.

| Miejsce | Przykład | Znaczenie |
|---|---|---|
| Główna ramka podstrony | `Offer-single (CPT: offer)` | całość = wpis CPT, nie Page |
| Sekcja na stronie | `Offers (CPT: offer)` | blok na Page, kafelki z CPT |
| Sekcja na stronie | `Reviews (Options)` | blok na Page, treść z Options |

Bez nawiasu i tak honoruj mapę poniżej. Nawias `(Options)` na warstwie **spoza** tej tabeli (np. `Values (Options)`, `Offer (Options)`) nie tworzy strony Options — zostaw treść w bloku / CPT.

Istniejące strony Options — **nie twórz drugich**

Te strony już są. Blok tylko je wyświetla. Inny układ w Figmie = zmiana **Blade/SCSS istniejącego bloku**, nie nowa Options i nie `reviews2` / `cta2`.

| Kokpit | Plik | slug | Pole | Blok ACF (`$slug`) | JSON strony (Page import) |
|---|---|---|---|---|---|
| Wezwanie do działania | `app/Options/OCta.php` (`Octa`) | `octa` | `g_octa` | `cta` | lokalne: `form`, `content`, opcjonalny override `header` / `txt`, tło. Nie wklejaj do JSON strony image / benefits / phone / shortcode z Options. |
| Opinie | `app/Options/OReviews.php` (`Oreviews`) | `oreviews` | `header`, `reviews_rating`, `reviews_google_url`, `r_reviews` | `reviews` | zwykle samo tło |
| Certyfikaty i uprawnienia | `app/Options/OCertificates.php` (`OCertificates`) | `ocertificates` | `g_certificates` | `certificates` | zwykle samo tło |
| Logotypy partnerów | `app/Options/OLogos.php` (`OLogos`) | `ologos` | `g_logos` | `logos` | zwykle samo tło |

`theme-settings` (`app/Fields/ThemeSettings.php`) to logo i dane kontaktowe (View Composer `App`), nie blok sekcji.

Gdy Options już jest, a Figma ma inny layout tej sekcji:
- zmień widok istniejącego bloku (`resources/views/blocks/cta.blade.php`, `reviews.blade.php`, `certificates.blade.php`, `logos.blade.php`) i ewentualnie jego SCSS,
- **nie** twórz drugiej strony Options ani drugiego bloku,
- **nie** przenoś kafelków z Options do pól bloku / repeatera w JSON strony,
- treść (opinie, certyfikaty, logotypy, bazowe CTA) zostaje w Kokpicie na stronie Options.

CTA jest hybrydą: treść bazowa z `g_octa` (Options). Na stronie można nadpisać `header` / `txt`, gdy `content` = true, oraz przełączyć `form`. Dolne CTA na Blogu: `@include('partials.cta')`, nie drugi import bloku.

Istniejące CPT — listing z query, nie repeater

| CPT | rewrite | Taksonomia | Pola ACF | Blok listingu |
|---|---|---|---|---|
| `offer` | `oferta` | `offer_category` (`kategoria-oferty`) | `app/Fields/OfferFields.php` (`offer_icon`) | `offers` — siatka opublikowanych wpisów; w JSON strony tylko `header`, `link_label`, opcjonalnie `offer_category`. Blok `offer` (homepage, ramka `Offer (Options)`) — taby z kategorii CPT, też bez kafelków w JSON. |

Ramka `Offer-single (CPT: offer)` = szablon single + JSON wpisu CPT w `resources/imports/offers/`, nie `offer-single.json` jako Page.
Lokalnie: `wp osf offer import resources/imports/offers/<slug>.json` (ten sam importer co strony, `post_type: offer`).

Project overview

This repository contains a custom WordPress project built with Roots Sage 11.

The project is developed as a custom implementation. Existing code in the repository is the primary source of truth for architecture, conventions, naming, structure, styling, and implementation patterns.

Before implementing anything, inspect the existing project and understand how similar functionality is already implemented.

Do not introduce a new architectural pattern when an established pattern already exists in the repository.

⸻

Repository map

Theme root: `wp-content/themes/bergermann` (Sage 11 + Acorn 5, PHP >= 8.2, namespace `App\` → `app/`).

| Ścieżka | Zawartość |
|---|---|
| `osf-import/` | przenośny kit: Figma → JSON → `wp osf page/post import` (kopiuj cały folder do innego Sage) |
| `app/Blocks/*.php` | 35 bloków ACF Composer (`Log1x\AcfComposer\Block`) |
| `app/Options/*.php` | strony opcji ACF (`OCta`, `OReviews`, `OCertificates`, `OLogos`) |
| `app/Fields/*.php` | grupy pól (`ThemeSettings`, `OfferFields`, `PostCategory`) |
| `app/Support/SectionClasses.php` | budowanie klas sekcji + lista teł |
| `app/View/Composers/*.php` | View Composers (`App` działa na `*`) |
| `app/Walkers/*.php` | walkery menu (desktop + mobile) |
| `app/setup.php`, `app/filters.php`, `app/post-types.php` | ładowane z `functions.php` przez `collect([...])` |
| `resources/views/blocks/*.blade.php` | widoki bloków (nazwa = `$slug`) |
| `resources/views/home.blade.php`, `category.blade.php` | listing wpisów (`is_home` / kategoria) — **nie** Page |
| `resources/views/single.blade.php`, `partials/content-single.blade.php` | pojedynczy wpis — **nie** Page |
| `resources/imports/posts/` | JSON + HTML treści wpisów z Figmy (`wp osf post import`) |
| `resources/views/partials/content-post.blade.php` | kafelek wpisu na Blogu (listing + related) |
| `resources/views/partials/cta.blade.php` | globalne CTA z opcji — include raz, na dole widoku |
| `resources/views/partials/content.blade.php` | kafelek wpisu w wyszukiwarce |
| `resources/views/components/*.blade.php` | `x-button`, `x-alert`, `x-icon.arrow-up`, `x-icon.ekg` (brak `x-picture`) |
| `resources/views/sections|partials|layouts` | header/footer/sidebar, partiale, layout `app` |
| `resources/css/variables.scss` | **cały design system** (~1500 linii) |
| `resources/css/blocks/*.scss` | styl per blok, importowany w `app.css` |
| `resources/js/blocks/*.js` | JS per blok, ładowany warunkowo z `app.js` |
| `public/build/**` | **artefakty builda są w gicie** (patrz „Build i assety") |

Zanim dodasz nową abstrakcję, sprawdź `app/Support/SectionClasses.php` i `resources/css/variables.scss` —
większość rzeczy tam już jest.

⸻

Komendy

Menedżer pakietów: **yarn** (`.yarnrc.yml`, `nodeLinker: node-modules`). W repo leżą też
`package-lock.json` i `pnpm-lock.yaml` — są nieaktualne, nie używaj npm ani pnpm i nie aktualizuj tych plików.

```bash
yarn dev      # Vite dev server: https://bergermann.local:5981 (strictPort, HMR przez ws)
yarn build    # produkcyjny build do public/build
```

```bash
composer install
wp acorn acf:cache        # przebuduj cache pól ACF po zmianach w app/Blocks|Fields|Options
wp acorn view:clear       # gdy Blade zwraca stary widok
wp osf page import resources/imports/<slug>.json   # lokalnie u użytkownika: strona-szkic z blokami i treścią
wp osf offer import resources/imports/offers/<slug>.json   # lokalnie: wpis CPT oferta (bloki ACF)
wp osf post import resources/imports/posts/<slug>.json   # lokalnie: wpis-szkic (treść z Figmy, nie strona)
```

Node >= 20.

Build (`yarn build`) i komendy Acorn (`wp acorn acf:cache`, `wp acorn view:clear`) uruchamia użytkownik
samodzielnie — nie wykonuj ich automatycznie. Jeśli zmiana tego wymaga, poinformuj o tym w podsumowaniu
zamiast odpalać komendę.

Nie uruchamiaj `vendor/bin/pint` na całym repo — w projekcie **nie ma `pint.json`**, więc Pint użyje
presetu Laravel (4 spacje) i przeformatuje wszystkie pliki PHP, które są pisane **tabami**
(patrz „Formatowanie i język kodu"). Pint co najwyżej na własnym, nowym pliku.

⸻

Anatomia bloku ACF (najczęstsze zadanie w tym repo)

Nazwa bloku jest zawsze jednowyrazowa, lowercase, bez myślników i podkreśleń — ta sama forma
w klasie PHP (`About`, `Whyus`, `Paths`), `$slug` (`about`, `whyus`, `paths`), pliku blade
(`about.blade.php`) i klasie CSS sekcji (`b-about`). Tak jest we wszystkich 35 istniejących blokach.

Nazwa bloku z Figmy

Źródło nazwy (w tej kolejności):
1. Nazwa podana w prompcie użytkownika, jeśli jest.
2. Nazwa ramki/warstwy sekcji w Figmie (bezpośrednie dziecko ramki podstrony, np. `Hero`, `Problem`, `Process`).
3. Pytaj tylko gdy po pominięciu chrome i kontenerów nie zostaje żadna sensowna nazwa.

Normalizacja warstwy → slug:
- Najpierw zetnij znacznik źródła na końcu nazwy: `(Options)`, `(CPT)`, `(CPT: offer)` itd. `Reviews (Options)` → `reviews`, `Offers (CPT: offer)` → `offers`. Nawias nie wchodzi do sluga.
- lowercase, wytnij spacje, myślniki i podkreślenia (`Why us` / `Why-Us` → `whyus`).
- **Slug = znormalizowana nazwa warstwy.** Reuse istniejącego bloku tylko gdy ten slug (albo wpis z tabeli aliasów poniżej) już jest w `app/Blocks`.
- **Nie aliasuj** warstwy na inny blok tylko dlatego, że nagłówek albo copy wygląda podobnie. Ten sam H2 nie znaczy ten sam blok.
- `Wehelp` ≠ `Solutions`. Oba mogą mieć nagłówek „W jakich sprawach pomagamy?”, ale układ jest inny: `Wehelp` (B2C) = lista punktów + okrągłe zdjęcie 512px + EKG; `Solutions` (B2B) = dwa kafelki. To dwa osobne bloki (`wehelp`, `solutions`). Nie mapuj Wehelp na `solutions`.
- Aliasy warstw — **wyłącznie ta lista**, nic „na czuja”: `Process` → `proces`, `FAQ` / `Faq` → `faq`, `CTA` / `Cta` / `cta-section` → `cta`, `Testimonials` → `reviews`, `Aboutv2` / `Aobut` → `about`, `Solution` (liczba pojedyncza) → `content`, `Solutions` (liczba mnoga) → `solutions`, `Banner` → `banner` (Hero - Podstrona, nie homepage `Hero`), `Standard` → `cards`, `Wehelp` → `wehelp`, `Offer` / `Offer (Options)` → `offer` (taby z kategoriami CPT, nie strona Options), `Offers` / `Offers (CPT)` → `offers` (siatka wpisów).
- Nowe warstwy Devs bez aliasu (slug = nazwa warstwy): `Reach` → `reach`, `Explore` → `explore`, `Gains` → `gains`, `Tiles` → `tiles`.
- Nawias `(Options)` na warstwie **nie** tworzy nowej strony opcji. Honoruj wyłącznie mapę w „Źródło danych”. `Values (Options)` → blok `values` + repeater w JSON strony (`OValues` nie istnieje). `Offer (Options)` → blok `offer` (query CPT/kategorii, bez kafelków w JSON).
- `Blogs` jako **sekcja na stronie** (np. homepage) → blok `posts`. Cała ramka podstrony `Blog` / `Blog-single` to szablony WP, nie alias na blok i nie Page (patrz „Szablony WP, nie Pages”).
- `Frame 460` i `fi_*` to nie nazwy bloków — pomiń, gdy puste. Żółte CTA we wpisie (`__cta`, „Masz więcej pytań dotyczących badania?”) → blok `action` (`CTA - Wpis`).
- Nie używaj jako nazwy bloku: `Frame 123`, `Group`, `Rectangle`, `__wrapper`, warstw wewnętrznych ani copy z H1/H2.
- Pomiń chrome strony: `menu`, `header`, `footer` i puste kontenery-opakowania.

Nie wymyślaj nazw spoza warstwy i spoza promptu. Nie tłumacz polskich nagłówków na angielski slug, jeśli ramka ma już angielską nazwę (`Dla kogo pracujemy?` to treść, ramka to `Problem` → `problem`).

FAQ — accordion

- W Figmie Devs często są **same pytania**, bez odpowiedzi. Nie wymyślaj odpowiedzi do JSON-a.
- Accordion zawsze na natywnym `<details>` / `<summary>`. Checkbox + sibling CSS (`input:checked ~ .tabs-content`) nie działa, gdy brak `txt`, a `z-index: -1` / `overflow` / GSAP `transform` zabija klikalność.
- Panel odpowiedzi renderuj zawsze (także przy pustym `txt`), żeby otwieranie działało.
- Nie dawaj `data-gsap-element` na wrapper całej listy pytań — GSAP dokłada `transform` i psuje hit-testing. Animuj nagłówek sekcji albo pojedyncze karty (`card`).
- Layout Devs: eyebrow + H2 **wyśrodkowane** w kolumnie 624 (`text-center` na `__top`), karty `FAQ card` pełna szerokość `c-main` (1280 wewnątrz paddingu), odstęp między kartami `spacing-2` (16 → `gap-4`).

Blok = 2–3 pliki:

1. `app/Blocks/Nazwa.php` — klasa `App\Blocks\Nazwa extends Log1x\AcfComposer\Block`
2. `resources/views/blocks/nazwa.blade.php` — widok (nazwa pliku = `$slug`)
3. `resources/css/blocks/nazwa.scss` — **twórz zawsze**, nawet pusty, z gotowym pustym selektorem
   `.b-<slug> { }` (patrz np. `resources/css/blocks/map.scss`)
4. `resources/js/blocks/nazwa.js` — opcjonalnie, **musisz** dodać warunkowy import w `resources/js/app.js`

Import nowego pliku scss w `resources/css/app.css` dodawaj zawsze na dole listy pod komentarzem
`/*-- USED ---*/` i nad `/*-- NOT USED ---*/` (nowy blok jest od razu używany, więc trafia do sekcji
USED, a nie do listy nieużywanych na dole pliku).

Rejestracja jest automatyczna (ACF Composer skanuje `app/Blocks`) — nie dopisuj bloków ręcznie
do `functions.php` ani do `ThemeServiceProvider`.

Klasa PHP — obowiązkowy szkielet

Wzorzec referencyjny: `app/Blocks/Hero.php`.

```php
public $name = 'Hero';          // nazwa widoczna w edytorze
public $slug = 'hero';          // lowercase, bez spacji; = nazwa pliku blade
public $category = 'formatting';
public $mode = 'edit';
```

W `fields()`:

- `->setLocation('block', '==', 'acf/<slug>')` — zawsze, inaczej pola się nie pokażą,
- nie dodawaj pola `block-title` ani akordeonu `accordion1` („Treści bloku”); definicję treści zaczynaj od zakładki, zgodnie z istniejącymi blokami. Nie kopiuj tych dwóch pól z bloków, które je zawierają, chyba że użytkownik wyraźnie o nie poprosi,
- zakładka treści: `->addTab('Elementy', ['placement' => 'top'])`,
- główne pola w grupie `g_<slug>` (`->addGroup('g_hero', ['label' => ''])` … `->endGroup()`),
- powtarzalne w repeaterze `r_<slug>` (`'layout' => 'table'`) … `->endRepeater()`,
- unikaj pól luźnych poza grupą/repeaterem — domyślnie pola idą do grupy albo repeatera, luźne pole
  tylko gdy naprawdę nie ma co grupować (np. jedno pole `header` przy repeaterze, jak w `Numbers.php`),
- **gdy blok ma zarówno grupę, jak i repeater — każda dostaje własny Tab**: grupa w pierwszym
  (`->addTab('Elementy', ['placement' => 'top'])` lub podobnym, np. „Treści"), repeater w kolejnym
  (np. `->addTab('Kafelki', ['placement' => 'top'])`) — wzorzec widoczny w `About.php`, `Faq.php`,
  `Cards.php`, `Tabs.php`,
- **jeśli poza kafelkami jest tylko jeden nagłówek / jedna główna treść, nie rozbijamy tego na osobny tab**;
  nagłówek i główna sekcja trzymają się w pierwszej zakładce z treściami, a nie w osobnym "Nagłówek" / "Header" tabie,
  chyba że blok ma naprawdę osobny, dodatkowy układ lub osobną grupę logiczną,
- **w prostych blokach z nagłówkiem i repeaterem można zwinąć cały układ do jednej grupy `g_<slug>` i umieścić repeater
  `r_<slug>` wewnątrz tej samej zakładki** — nie tworzymy osobnej zakładki tylko dla nagłówka, gdy nie ma osobnej logiki.
- **ostatnia zakładka zawsze**: `->addTab('Ustawienia bloku', ['placement' => 'top'])` z polami
  `section_id`, `section_class`, przełącznikami `flip` / `wide` / `nomt` / `gap` i selectem `background`.

Nagłówek i opis — zawsze ten sam typ pola, niezależnie od tego, czy pole jest w grupie czy w repeaterze:
- nagłówek zawsze jako zwykły tekst: `->addText('header', ['label' => 'Nagłówek'])`,
- opis/treść zawsze jako WYSIWYG: `->addWysiwyg('text', ['label' => 'Treść', 'tabs' => 'all', 'toolbar' => 'full', 'media_upload' => true])` —
  nie używaj `addTextarea` do opisu.

Etykiety pól po polsku, nazwy pól po angielsku. Przełączniki zawsze
`'ui' => 1, 'ui_on_text' => 'Tak', 'ui_off_text' => 'Nie'`.

`SectionClasses::backgroundChoices()` **nie istnieje** — kopiuj `choices` tła 1:1 z analogicznego bloku
(np. `Hero.php` / `Wehelp.php`). Przed wyborem tła sprawdź aktualne opcje w repozytorium — nie
wymyślaj nowych wartości. Figma `colors/bg` #141210 to `--bg` strony; ciemna sekcja w motywie to
`section-dark` (nie wpisuj hexu w Blade). Jasna = `section-white`.

**Tło nowego bloku ustawiaj w PHP przez `default_value` pola ACF `background`.**
Jeśli projekt wskazuje tło, wybierz odpowiadającą mu istniejącą opcję (np. `section-dark`).
Jeśli nie wiadomo, jakie tło wybrać, ustaw `'default_value' => 'section-white'` (białe),
a nie `none`. Nie dodawaj na sztywno tła całej sekcji w Blade (np. `bg-dark`, `bg-white`
czy `section-dark`) ani w SCSS. Widok ma korzystać z wartości pola `background` zgodnie
z poniższym szkieletem, aby użytkownik mógł zmienić tło w edytorze. Zachowaj istniejący
fallback w `with()` — domyślny wygląd nowego bloku określa `default_value` w definicji pola.

`with()` — obowiązkowy fragment

```php
'background' => get_field('background') ?: get_field('default_block_background', 'option') ?: 'none',

$fields['sectionClass'] = SectionClasses::fromMap($fields, [
    'flip' => 'order-flip',
    'wide' => 'wide',
    'nomt' => '!mt-0',
    'gap'  => 'wider-gap',
    // + mapowania specyficzne dla bloku, np. 'nolist' => 'no-list'
]);
```

Fallback na `default_block_background` z opcji motywu jest w 32 z 35 bloków — nowe bloki mają go mieć.
Booleany rzutuj przez `(bool) get_field(...)`.

Widok Blade — obowiązkowy szkielet

```blade
<!--- nazwa -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-nazwa relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main">
		{{-- treść --}}
	</div>
</section>
```

- klasa główna sekcji: `b-<slug>`,
- elementy wewnętrzne w konwencji `__nazwa`: `__wrapper`, `__col`, `__top`, `__content`, `__img`,
  `__card`, `__txt`, `__inside`,
- `{{ }}` dla tekstu, `{!! !!}` **tylko** dla WYSIWYG,
- każde opcjonalne pole owinięte w `@if (!empty(...))`.

⸻

Core principle

Istniejący kod jest głównym wzorcem architektury i konwencji. Przed zmianą przeczytaj podobne implementacje, zrozum ich działanie i wykorzystaj istniejące rozwiązania. Nowe podejście wprowadzaj tylko przy braku odpowiedniego wzorca. Rozbieżności instrukcji z kodem oceniaj świadomie; nie kopiuj błędów.

⸻

Technology stack

Projekt może używać WordPress, Sage 11, Acorn, Blade, Tailwind, Vite, ACF Composer, WooCommerce, CF7, Swiper i JavaScript. Przed użyciem sprawdź obecność danej technologii. Nie instaluj zbędnych zależności ani nie zastępuj istniejącej biblioteki bardziej znajomą.

⸻

Before writing code

Przed implementacją sprawdź strukturę repozytorium, Blade, ACF/PHP, komponenty, SCSS i JS. Rozpoznaj nazewnictwo, kontenery, grid, odstępy, breakpointy, typografię, assety, obrazy i przyciski. Dla ramki Figmy najpierw `get_metadata` dzieci podstrony, potem `get_design_context` **tej sekcji**, którą składasz — nie zgaduj układu ze screenshotu całej strony. Nie generuj plików przed tą analizą.

⸻

Reuse before creation

Wyszukaj i wykorzystaj istniejące komponenty, helpery, tokeny i wzorce: przyciski, nagłówki, kontenery, karty, ikony, formularze, slidery, akordeony, modale, breadcrumbs, menu, obrazy, ACF i responsywność. Nie twórz niemal identycznych wersji.

⸻

Working from screenshots or designs

Ramka Figmy jest specyfikacją layoutu **1:1**, nie szkicem „do dopracowania później”. Złóż tę samą hierarchię, kolumny, kadrowanie, dekoracje i odstępy **tokenami motywu** — bez ściany klas z MCP i bez pustego grida.

Źródło: plik CBP, `fileKey` `XgSjGFlGU7QLHjQnqt3M3F`, canvas **Devs** `73580:4973` (artboard 1920). Z URL `figma.com/design/:fileKey/…?node-id=73580-9237` bierz `fileKey` i zamień `-` na `:` w `nodeId` (`73580:9237`). Link bez `node-id` jest niewystarczający — poproś o ramkę. Inny `node-id` z promptu wygrywa nad tabelą poniżej.

Z Figmy najpierw bloki ACF albo szablony Blade (faza 1), potem od razu JSON **dla Pages** oraz JSON **wpisu** dla `Blog-single` (faza 2). `Blog` / `Blog-single` → Blade listingu/wpisu, bez JSON-a strony. Komend `wp osf` / `yarn build` / `acf:cache` nie uruchamiaj. Slug = nazwa ramki sekcji (patrz „Nazwa bloku z Figmy”).

Ramki podstron na Devs (wołaj MCP na **tej** ramce, nie na całym canvasie)

| Ramka | nodeId | Sekcje w kolejności (chrome pomiń) |
|---|---|---|
| Homepage | `73580:8603` | `Hero`, `Offer (Options)` → `offer` (CPT/kategorie, nie Options WP), `Values (Options)` → `values` (repeater w JSON), `Process`, `Aobut` → `about`, `Certificates (Options)`, `Myth`, `Reviews (Options)`, `Cta (Options)` |
| Offer | `73580:8926` | `Banner`, `Offers (CPT)` → `offers`, `Cta` |
| Offer-single (CPT) | `73580:9016` | banner, `Solution` (**hidden** — nie renderuj), `Content` → `content`, `Process`, `Gains`, `Tiles`, `Faq`, `Cta` |
| B2C | `73580:9237` | `Banner`, `Problem` (wewnątrz `Content`), `Wehelp`, `Offers (CPT)`, `Reach`, `Process`, `Values (Options)` → `values`, `Faq`, `Cta` |
| B2B | `73580:9461` | `Banner` + `Problem` (wewnątrz `Banner & Problem`), `Solutions`, `Offers (CPT)`, `Reach`, `Process`, `Values` → `values`, `Faq`, `Cta` |
| About | `73580:9747` | `Banner`, `Explore`, `Certificates (Options)`, `Standard` → `cards`, `Reviews (Options)`, `Cta` |
| Blog | `73580:9974` | `Hero`, `Blogs`, `Cta` — szablon WP, nie Page |
| Blog - single | `73580:10057` | `Hero`, treść `Blog`, `Blogs`, `Cta` — szablon + JSON wpisu |
| Contact | `73580:10279` | `Contact` |

Figma → kod (kolejność obowiązkowa)

1. Wczytaj skill MCP `skill://figma/figma-design-to-code/SKILL.md`. Przy `get_design_context` zawsze `skillNames: "resource:figma-design-to-code"`, `clientLanguages: "php,html,css"`, `clientFrameworks: "wordpress,sage,blade,tailwind"`.
2. `get_metadata` na ramce **podstrony** z tabeli (nie na Devs `73580:4973`, jeśli timeout). `get_variable_defs` też na ramce podstrony (na canvasie pada — „no selection”).
3. Rozpakuj opakowania: `Frame 4xx`, `Group`, `Content` gdy ma jedno nazwane dziecko-sekcję (`Problem`), `Banner & Problem`. Sekcja = nazwane dziecko (`Hero`, `Wehelp`, `Faq`…). Pomiń `menu` / `header` / `footer` / `hidden`.
4. Dla **każdej** sekcji, którą składasz: `get_design_context` na węźle **tej** sekcji (np. Wehelp `73580:9262`). Screenshot całej strony nie zastępuje kontekstu sekcji. Timeout → retry na `__wrapper` albo nazwanym dziecku, nie zgaduj ze screena.
5. Kod MCP to **referencja** (React + Tailwind Figmy). Nie wklejaj. Złóż Blade + klasy motywu + minimum SCSS według tabeli „Tłumaczenie klas MCP”.
6. Assety: zdjęcie = `download_assets` `defaultFormat: "jpg"` na węźle **fill** (ellipse / rectangle), nie na całej ramce z menu. Ikony, logotypy i EKG = SVG. Wymiary liścia i boxa z Figmy (`width`/`height` na `<img>` albo w SCSS) — nie `width: auto` na stałym kształcie.

Układ z warstwy, nie z copy

Ten sam H2 **nie** znaczy ten sam blok. Reuse tylko przy tym samym slugu / aliasie **i** tym samym fingerprintie. Inny layout przy tej samej nazwie warstwy = zmień Blade/SCSS **tego** bloku, nie twórz `wehelp2`. Nazwy warstw `__wrapper`, `__list`, `__point`, `__card`, `__top`, `__txt` przenoś 1:1 do BEM.

| Fingerprint (struktura, nie H2) | Blok |
|---|---|
| `__list` / `__point` + `ellipse` 512×512 + wektor EKG ~409×324 | `wehelp` |
| `__cards` → dwa `__card` 624 z własną listą punktów (ten sam H2 co Wehelp) | `solutions` |
| zdjęcie `rounded-rectangle` ~608×413 + kolumna tekstu (jedna para) | `problem` |
| dwie pary zdjęcie+tekst jedna pod drugą, druga lustrzana | nadal `problem` (B2B), nie `wehelp` |
| siatka ~405 (`sizes/grid-3`) + małe EKG 115×92 + `__button` | `offers` |
| taby + jedno duże zdjęcie 624 + lista z `__arrow` | `offer` (homepage, CPT/kategorie) |
| instancje `FAQ card` jedna pod drugą, header wyśrodkowany | `faq` |
| instancja `__slider` | `proces` |
| dwie kolumny: copy + formularz | `cta` |
| `Banner` pełna szerokość na podstronie | `banner` (nie homepage `hero`) |
| `Solution` liczba pojedyncza | `content` |

Tłumaczenie klas MCP → motyw (nie kopiuj lewej kolumny)

| MCP (React/Tailwind Figmy) | W motywie |
|---|---|
| `content-stretch`, `size-full`, `shrink-0`, `min-w-px`, `data-node-id`, `data-name` | usuń |
| `[text-box-trim]`, `[text-box-edge]`, `[word-break:break-word]` | usuń |
| `w-[1920px]`, `w-[1280px]`, `max-w-[1328px]`, `px-[24px]`, `py-[104px]` | `__wrapper c-main` + `-smt` / `section-*` (padding 104 już w tle) |
| `h-[87px]` na rzędzie nagłówka | usuń (hug treści) |
| `text-[length:var(--font/size/h2,54px)]` + `leading-[56px]` | `<h2>` albo `text-h2` — bez `text-[54px]` |
| `font-[family-name:var(--font/family/header)]` / `body` | nic (`h1–h6` = Neue Montreal, body = Montserrat) |
| `text-[color:var(--text/text-p-light,#ffd95f)]` | `text-primary-400` |
| `text-[color:var(--text/text-primary,#ffd23f)]` | `text-primary` |
| `text-white` na `p`/`h*` wewnątrz `section-dark` | nic (dziedziczy) |
| `size-[512px]` / ellipse | BEM `__img` + SCSS koło 512, nie `size-[512px]` w Blade |
| `flex … justify-between` na węźle (Wehelp: 624+512 na 1280) | `flex flex-col lg:flex-row lg:items-center lg:justify-between` — **bez** `gap-12` |
| `gap-[var(--section-gap-xl,48px)]` albo para 608+624 z luką 48 | `lg:grid-cols-2 gap-12` albo flex + `gap-12` — tylko gdy **ten** węzeł ma gap, nie justify |
| `lg:grid` 3 × `sizes/grid-3` ~405 | `lg:grid-cols-3` |
| `border-dashed border-t` / `colors/bg-p-dark` | SCSS `.__point + .__point { border-top: 1px dashed var(--color-primary-dark); }` |
| `absolute left-[1324px] top-[469px]` (współrzędne artboardu 1920) | pozycja względem `<section>`, nie wklejaj `left: 1324px` do `c-main`. Policz `right`/`bottom` z ramki sekcji |
| `hidden` / `eyebrow-container` hidden | nie renderuj |

Auto-layout: czytaj **ten** węzeł

Nie zakładaj, że każda dwukolumna to `gap-12`. Wehelp: `justify-between` (zostaje ~144px między listą 624 a kołem 512). Problem B2C: luka 48 = `gap-12`. Gap jest na konkretnym frame — przenieś tylko jego wartość z tabeli spacing. Nie dokładaj `gap-6` „na wszystko”.

Nie składaj `.m-header` (16px) **i** `mb-*` na tym samym nagłówku. Jeden odstęp: albo `m-header`, albo gap rodzica. `m-btn` / `m-img` = 40px = Figma `img-margin` / prawie `btn-margin` 32 — nie dokładaj drugiego `mt-*`.

Checklista layoutu (Devs, 1920)

| W Figmie | W motywie |
|---|---|
| `__wrapper` x=296, w=1328, padding 24 (`section-wrapper`) | `c-main` (1376 = 1328+24+24). **Nie** `max-w-[1328px]` ani `max-w-[624px]` na wrapperze |
| padding pionowy `section` 104 | `-smt` + `section-*` (już `--smt: 104px`). **Nie** `py-14 md:py-24` |
| `ellipse` 512×512 | koło w SCSS, `object-fit: cover`, `width`/`height` 512 |
| `rounded-rectangle` 608×413 itd. | `radius-img` + `object-cover`, nie koło |
| wektor EKG (`Vector` 409×324 obok Wehelp; 115×92 na ofercie) | SVG + jawne px, `pointer-events-none` |
| `ambient-glow-*` (ellipse-blur) | nie pomijaj, jeśli sąsiednie bloki już mają glow; nie buduj osobnego silnika. Nie zastępuje EKG |
| instancja `FAQ card` | `<details>`/`<summary>` |
| instancja `__button` | `x-button` (żółty fill → `primary`, ciemny → `secondary`, link z strzałką → `underline`) |
| instancja `__slider` | Swiper jak `proces` |
| instancja `Tab` | istniejący blok `offer`, nie nowy |
| `__arrow` | `x-icon.arrow-up` albo SVG z `download_assets`, jeśli glif inny |
| karta `bg-s-darker` #292929 | `bg-secondary-700` |
| tło ciemne / jasne | `section-dark` / `section-white` — nie hex, nie `bg-[#141210]` |
| `text-center` / `items-center` na `__top` (FAQ) | `text-center` na tym wrapperze, nie na całej sekcji „na zapas” |

Dekoracje wychodzące poza `c-main` (EKG Wehelp jest rodzeństwem `__wrapper`, x=1324 na ramce 1920): pozycjonuj względem `<section class="relative">`. **Nie** dawaj `overflow: hidden` na sekcji, jeśli obcina EKG / glow. Na mobile EKG może zejść pod zdjęcie albo się schować w `@media` — nie rozjeżdżaj grida.

Instancje i chrome

Pomiń `menu`, `header`, `footer`. `basic/buttons` / `btn/btn-x-padding` 64 / `btn-y-padding` 24 nie odtwarzaj ręcznie — tylko `x-button`. `basic/menu` to chrome.

Spacing Figmy → Tailwind (gdy na węźle jest auto-layout **gap**)

| Token Figma | px | Klasa |
|---|---|---|
| `spacing/spacing-05` | 4 | `gap-1` |
| `spacing/spacing-1` / `sub-margin` | 8 | `gap-2` |
| `spacing/spacing-2` | 16 | `gap-4` |
| `spacing/spacing-3` / `head-margin` | 24 | `gap-6` |
| `spacing/spacing-4` / `section-gap` | 32 | `gap-8` |
| `spacing/spacing-5` / `img-margin` | 40 | `gap-10` / `m-img` / `m-btn` |
| `spacing/spacing-6` / `section-gap-xl` | 48 | `gap-12` |
| `spacing/spacing-7` | 56 | `gap-14` |
| `spacing/spacing-8` | 64 | `gap-16` |
| `spacing/spacing-9` | 72 | nie wymyślaj `gap-[72px]` — najbliższy token albo gap rodzica |
| `section` / `spacing-13` | 104 | `-smt` / `--smt` |

Typografia i kolor z Figmy → token motywu (nie `text-[54px]`, nie hex w Blade)

| Figma | Motyw |
|---|---|
| `header/h1` 60/66 … `header/h7` 20/26 | `text-h1` … `text-h7` (h1–h6 dziedziczą po tagu; motyw clampuje h2 do 52 przy Figmie 54 — nie walcz `text-[54px]`) |
| `basic/body 18pt` / `16pt` / `14pt` | default body — **nie** `text-lg` / `text-base` / `text-sm` |
| `font/family/header` Neue Montreal | default nagłówków |
| `font/family/body` Montserrat | default body |
| `text/text-primary` #ffd23f | `text-primary` |
| `text/text-p-light` #ffd95f | `text-primary-400` |
| `text/text-p-lighter` #fff0bf | `text-primary-100` |
| `text/text-white` | dziedziczy na `section-dark`; `text-white` tylko poza nim |
| `text/text-body` / `text/text-main` #554615 | `text-primary-900` |
| `text/text-gray` #929292 | `text-secondary-300` / `text-muted` |
| `colors/bg-primary` | `bg-primary` / `x-button` `primary` |
| `colors/bg-secondary` #3d3d3d | `bg-secondary` / `x-button` `secondary` |
| `colors/bg-s-darker` #292929 | `bg-secondary-700` |
| `colors/bg-p-dark` #aa8c2a | `border-primary-700` / `border-primary-dark` |
| `radius/global-radius-s` 24 | `radius` |
| `radius/global-radius` 40 | brak tokenu — przyciski przez `x-button`, nie `rounded-[40px]` |

Po złożeniu sekcji porównaj screenshot z `get_design_context` z markupem: liczba kolumn, `justify-between` vs `gap`, kształt zdjęcia (koło vs `radius-img`), EKG/wektor, rodzaj kafelka (lista / karta / accordion / slider), hidden warstwy nieobecne, tło `section-*`. Jeśli się nie zgadza — popraw Blade/SCSS, nie „zostaw użytkownikowi”.

⸻

Responsive implementation

Devs jest desktopem 1920. Na `lg:` odwzoruj kolumny 1:1 z Figmy; poniżej złóż w jedną kolumnę (zdjęcie pod lub nad tekstem według `flip` / kolejności w ramce). Nie skaluj 1920 proporcjonalnie i nie dodawaj zbędnych breakpointów. Czytelna hierarchia, brak overflow poziomy, widoczne CTA, kadrowanie `object-cover`.

⸻

WordPress

Follow WordPress best practices while respecting the architecture already established by Sage and the repository.

Avoid:

* unnecessary global state,
* unnecessary queries,
* hardcoded URLs,
* hardcoded attachment URLs,
* hardcoded site-specific paths,
* duplicated WordPress queries,
* unnecessary plugin dependencies.

Use existing project helpers and abstractions where available.

Escape output appropriately.

Sanitize user-controlled input appropriately.

Do not modify WordPress core files.

⸻

Sage 11 / Blade

Sprawdź podobne widoki. Używaj istniejących komponentów, partiali i View Composerów. Widoki mają być czytelne, z minimalną logiką biznesową. Nie wprowadzaj konkurencyjnego sposobu przygotowania danych.

⸻

ACF and blocks

Przestrzegaj wzorców istniejących pól: nazw i kluczy, grup, tabów, warunków, wartości domyślnych, rejestracji, podglądów, pobierania danych, obrazów, linków, repeaterów i flexible content. Dodawaj tylko potrzebną edytorowi konfigurację treści; decyzje strukturalne i wizualne pozostaw w kodzie.

⸻

Styling

Use the styling system already established in the project.

### Layout z Figmy, minimum klas

Cel: **ten sam układ co w Figmie**, zapisany najmniejszą liczbą klas motywu. Nie: pusty szkielet „do dopracowania”. Nie: zrzut wszystkich klas z MCP (`content-stretch`, `w-[1920px]`, `text-[54px]`).

W Blade (Tailwind / tokeny motywu) zapisuj:

* `display`, grid i flex oraz kolejność kolumn,
* gap **z auto-layoutu Figmy** (tabela w „Working from screenshots”),
* tło `section-*` / `bg-secondary-700` / `text-primary` — token, nie hex,
* `c-main`, `radius`, `radius-img`, `m-header`, `m-btn`, `m-img`, `text-h*`, `x-button`,
* `text-center` / `lg:justify-between` / `lg:items-center` **tylko** gdy auto-layout Figmy tak ma,
* breakpointy `md:` / `lg:` na złożenie kolumn (desktop z Figmy → jedna kolumna na mobile).

SCSS bloku (`resources/css/blocks/<slug>.scss`) twórz zawsze i importuj. Zostaw pusty selektor tylko gdy **cały** układ siada utility. Do SCSS idzie wyłącznie to, czego nie da się tokenem / utility:

* koło (`border-radius: 50%` + stały rozmiar z Figmy, np. 512px),
* stały box dekoracji (EKG 409×324, logo kafelka 112×112, Offers 115×92),
* dashed divider listy / karty,
* ukrycie markera `<summary>`, stan `[open]`,
* pseudoelementy, HTML z WYSIWYG / CF7.

Nie przenoś do SCSS całego grida i RWD, które da się napisać w Blade. Nie dodawaj `order-flip`, dodatkowych breakpointów ani stanów „na zapas”.

Nie używaj: `rounded-[…]`, `min-h-[…]`, `text-[54px]`, `leading-[1.1]`, `py-14 md:py-24` na sekcji (to dubluje `--smt`), `gap-6` „bo tak”. `gap-6` wolno, gdy Figma ma `spacing-3` / `head-margin` 24px na **tym** węźle.

Nie używaj `rounded-*` Tailwinda, gdy jest `radius` / `radius-img`. Nie używaj `max-w-*` zamiast `c-main` / `c-narrow`.

Dekoracja z Figmy (EKG, chevron, wektor na ofercie) ma markup **i** wymiary. Nie wstawiaj pustego `<span class="__glow">` bez SVG / stylu i nie pomijaj warstwy, bo „za dużo CSS”. `overflow: hidden` na `.b-<slug>` tylko gdy Figma przycina — Wehelp/Offers z EKG poza wrapperem mają `overflow: visible` na sekcji.

⸻

Design tokens

Respect existing design tokens.

Before introducing a new:

* color,
* font size,
* spacing value,
* border radius,
* shadow,
* container width,
* breakpoint,

check whether an equivalent token or convention already exists.

Do not redefine existing tokens locally.

Do not use approximate colors when an appropriate project color already exists.

⸻

JavaScript

Używaj JS tylko gdy potrzebny; preferuj możliwości przeglądarki i rozwiązania projektu. Sprawdź strukturę, podobne funkcje i dostępność bibliotek. Trzymaj skrypty w ich zakresie, bez globalnych zmiennych i wielokrotnej inicjalizacji.

⸻

Third-party libraries

Do not add a dependency without a clear reason.

Before adding one:

* check whether the repository already contains a solution,
* check whether the browser can handle the functionality natively,
* evaluate whether the dependency is justified.

If an existing library such as Swiper is already used for the required functionality, reuse it instead of adding another slider library.

⸻

WooCommerce

If WooCommerce is present, preserve WooCommerce compatibility.

Before modifying WooCommerce behavior:

* inspect existing overrides,
* inspect hooks and filters,
* inspect Sage/WooCommerce integration,
* check for project-specific helpers.

Prefer hooks and filters over unnecessarily copying WooCommerce templates.

Only override templates when there is a clear reason.

Do not modify WooCommerce plugin files.

⸻

Forms

When working with forms, inspect the existing form implementation first.

Preserve:

* validation,
* accessibility,
* error handling,
* success states,
* required fields,
* spam protection,
* existing Contact Form 7 conventions where applicable.

Do not create a custom form system if the project already uses Contact Form 7 or another established solution unless explicitly requested.

⸻

Accessibility

New UI should be reasonably accessible by default.

Pay attention to:

* semantic HTML,
* heading hierarchy,
* labels,
* keyboard interaction,
* focus states,
* button vs link semantics,
* alt text handling,
* ARIA attributes where genuinely necessary,
* sufficient interactive target sizes.

Do not add ARIA attributes unnecessarily when native HTML semantics already provide the correct behavior.

⸻

Performance

Avoid unnecessary performance regressions.

Pay attention to:

* image sizes,
* responsive images,
* lazy loading,
* unnecessary JavaScript,
* duplicate queries,
* expensive loops,
* unnecessary DOM complexity,
* unnecessary dependencies.

Use WordPress image functions and existing project image helpers when available instead of hardcoding image URLs.

⸻

Code quality

Kod ma być produkcyjny, czytelny, prosty, utrzymywalny i spójny z projektem. Oddzielaj odpowiedzialności; unikaj nadmiernych abstrakcji, duplikowania logiki i skomplikowanych sztuczek. Nie twórz abstrakcji jednorazowych bez uzasadnienia architektonicznego.

⸻

Naming

Nazwy plików, klas, metod, zmiennych, komponentów, pól ACF i CSS mają odpowiadać konwencjom repozytorium oraz opisywać przeznaczenie. Nie wprowadzaj nowego systemu nazewnictwa.

⸻

Comments

Do not over-comment obvious code.

Comments should explain:

* non-obvious decisions,
* unusual workarounds,
* external limitations,
* important architectural reasoning.

Avoid comments that simply translate the code into English.

⸻

Scope discipline

Realizuj tylko zakres zadania. Bez potrzeby nie refaktoryzuj, nie zmieniaj nazw, zależności, konfiguracji ani formatowania innych plików. Drobne poprawki bezpośrednio związane z zadaniem są dopuszczalne; większe niezwiązane problemy zgłoś.

⸻

Existing functionality

Zachowaj istniejące funkcje i kompatybilność. Przed zmianą współdzielonych komponentów, globalnych styli, JS, hooków WordPress/WooCommerce, ACF lub konfiguracji sprawdź wszystkie zastosowania.

⸻

Assets

Before adding a new asset, inspect existing assets.

Reuse existing:

* icons,
* SVGs,
* logos,
* placeholders,
* decorative graphics,

when they match the design.

Do not embed large base64 assets directly in templates.

Follow the project's established asset pipeline.

⸻

Icons

Use the icon system already established in the repository.

Do not introduce another icon library simply for one icon.

Do not substitute random icons when a design clearly requires a specific one.

⸻

Content

Do not unnecessarily hardcode editable content into templates.

Determine whether content is:

* global,
* page-specific,
* block-specific,
* structural,
* dynamic.

Use the same content-management strategy as similar existing elements.

Do not turn every piece of text into an ACF field automatically.

⸻

Handling uncertainty

Rozstrzygaj niejasności na podstawie zadania, projektu, kodu i konwencji. Pytaj tylko o istotne decyzje, których nie da się ustalić z tych źródeł; drobne decyzje podejmuj zgodnie z repozytorium.

⸻

Implementation workflow

Zrozum zadanie → skill `figma-design-to-code` → `get_metadata` ramki podstrony z tabeli Devs → rozpakuj `Frame 4xx` / `Content` / `Banner & Problem` → `get_design_context` **każdej** sekcji → `get_variable_defs` na ramce strony → analogiczny blok w repo → złóż layout tokenami (tabela MCP) → JSON/assety → porównaj screenshot sekcji z markupem. Samo utworzenie plików nie kończy zadania.

⸻

Validation

Wykonuj dostępne, adekwatne kontrole PHP, Blade, JS, importów i formatowania. Raportuj rzeczywiście wykonane sprawdzenia i ograniczenia. Nie uruchamiaj yarn build, yarn dev ani komend Acorn — wykonuje je użytkownik; przypomnij wymagane kroki.

⸻

File creation

Nie twórz zbędnych plików ani nowych struktur katalogów. Najpierw sprawdź, czy zmiana należy do istniejącego pliku lub komponentu. Nowe pliki umieszczaj zgodnie z repozytorium.

⸻

Refactoring

Refactoring is allowed when it directly supports the requested implementation.

Avoid large unsolicited refactors.

If existing code is problematic but unrelated to the current task, leave it alone unless it prevents implementation.

⸻

Design system — używaj tokenów, nie wartości

Wszystko jest w `resources/css/variables.scss` i w bloku `@theme` w `resources/css/app.css`
(Tailwind v4, konfiguracja CSS-first — `tailwind.config.js` zawiera tylko plugin `forms`,
nie dopisuj tam kolorów ani spacingu).

**Kontenery** (nie rób własnych `max-w-*`):

Figma `sizes/max-width` 1328 + `section-wrapper` 24 z każdej strony = `c-main` 1376. Nie dopisuj
`max-w-[1328px]` ani `max-w-*` na wrapperze sekcji. `min-h-*` / `leading-*` ad hoc — nie.
`text-h1`…`text-h7` **wolno i trzeba**, gdy warstwa Figmy używa `header/h1`…`header/h7`.
Nie dodawaj `text-sm` / `text-lg` / `font-bold` / `mt-*` na tekście „na oko” — tylko token albo gap z tabeli Figmy.

| Klasa | Max-width |
|---|---|
| `c-main` | 1376px — domyślny wrapper bloku (= Figma 1328 + padding 24) |
| `c-narrow` | 1176px — węższe treści (np. wąski FAQ, gdy Figma ma węższą kolumnę) |
| `c-wide` / `.wide .c-main` | 100% — tryb `wide` |

**Odstępy sekcji** — nie używaj `mt-*` ani `py-*` na `<section>`, tylko: `-smt` / `-spt` / `-smb` / `-spb` (104px = Figma `section`),
oraz `-menu-mt` / `-menu-pt`. Marginesy wewnętrzne: `m-header` (16px w motywie), `m-title`, `m-btn`, `m-img`.

**Typografia**: `text-h1` … `text-h7`, `text-big`, `text-gradient`, `font-header` (Neue Montreal) /
`font-body` (Montserrat). Figma → token: h1 60, h2 54 (motyw clamp 52), h3 48, h4 36, h5 30, h6 24, h7 20.
`basic/body 16pt` / `18pt` zostaw default — bez `text-base` / `text-lg`.

**Kolory** (stan motywu = Devs, nie stary błękit/róż): `--color-primary*` (#FFD23F), `--color-secondary*` (#3D3D3D), `--bg` (#141210),
skale 50–900 + `-hover` i `-dark`. Nie wpisuj hexów w Blade — dobierz token z tabeli Figmy.

**Obrazy**: klasy rozmiarów `img-xs` (176px) … `img-3xl` (664px), zaokrąglenia `radius` (24px) /
`radius-img` (32px).

**Przyciski** — wyłącznie przez komponent `x-button`:

```blade
<x-button :href="$g_hero['button1']['url']" variant="primary" data-gsap-element="btn">
	{{ $g_hero['button1']['title'] }}
</x-button>
```

Dostępne warianty (`.btn-<variant>` w `variables.scss`): `primary`, `secondary`, `white`, `underline`,
`outline-primary`, `outline-secondary`, `primary-small`, `secondary-small`.
Grupę przycisków owijaj w `<div class="inline-buttons m-btn">`.

**Zdjęcia** — jak w istniejących blokach (`<img>` / `<picture>`). Komponentu `x-picture` **nie ma** w `resources/views/components` — nie twórz go przy okazji. Ellipse z Figmy → koło w SCSS + jawne px. Prostokąt → `radius-img` + `object-cover`.

```blade
<figure class="__img m-0">
	<img src="{{ $g_wehelp['image']['url'] }}" alt="{{ $g_wehelp['image']['alt'] ?? '' }}" width="512" height="512" data-gsap-element="img">
</figure>
```

Pola obrazów zawsze `'return_format' => 'array'`, pola linków też `'array'` (`['url']`, `['title']`).

⸻

Animacje GSAP

GSAP + ScrollTrigger ładowane są **z CDN** w `app/setup.php` (`gsap-cdn`, `gsap-st-cdn`) i dostępne
jako globalne `gsap` / `ScrollTrigger`. Pakiet `gsap` z `package.json` nie jest importowany w `app.js` —
nie zmieniaj tego bez potrzeby.

Animacje są sterowane atrybutami, nie kodem per blok:

- `data-gsap-anim="section"` na `<section>`,
- `data-gsap-element="img|header|txt|text|card|btn"` na animowanych elementach,
- `data-gsap-element="stagger"` + `data-gsap-edit="delay-0.2"` dla animacji kaskadowych.

Nie pisz własnych `gsap.from()` w widoku bloku, jeśli wystarczą te atrybuty.

⸻

JavaScript — stan faktyczny

`resources/js/app.js` ładuje JS bloków **warunkowo**, po obecności klasy bloku w DOM:

```js
if (document.querySelector('.b-nazwa')) import('./blocks/nazwa');
```

Dodając JS bloku, dopisz taki warunek — nie importuj modułu bezpośrednio na górze pliku.
Sliderem w projekcie jest **Swiper 11** (wzorzec: `resources/js/blocks/slider.js`), lightboxem
**baguetteBox** (`.lightbox-gallery`). Dostępne są też Alpine.js (`window.Alpine`, wystartowany)
i jQuery. React jest w zależnościach, ale nie jest używany we froncie — nie buduj na nim UI.

Aliasy Vite: `@scripts`, `@styles`, `@fonts`, `@images`.

⸻

Build i assety

`.gitignore` ignoruje `public/*` **z wyjątkiem `public/build/**`** — skompilowane assety są śledzone w gicie.
Po każdej zmianie w `resources/css` lub `resources/js` przypomnij, że trzeba uruchomić `yarn build`
i uwzględnić `public/build` w commicie (robi to użytkownik samodzielnie) — inaczej produkcja dostanie
stary CSS/JS.

`theme.json` w rootcie jest źródłem preprocesowanym; realny `theme.json` powstaje w
`public/build/assets/theme.json` (podmiana przez filtr `theme_file_path` w `app/setup.php`).
Nie edytuj pliku w `public/build`.

Pliki `.scss` są importowane z `app.css`. Jeśli build wywali się na SCSS — sprawdź, czy `sass`
jest zainstalowany; nie ma go obecnie w `devDependencies`.

⸻

Formatowanie i język kodu

- PHP w `app/` jest pisany **tabami** (wszystkie 35 bloków) — trzymaj się tabów, mimo że
  `.editorconfig` deklaruje spacje. Nie przeformatowuj istniejących plików „przy okazji".
- Blade / JS / CSS: 2 spacje, LF, końcowy newline, single quotes.
- Nazwy techniczne (klasy, metody, pola ACF, klasy CSS, pliki) — po angielsku.
- Etykiety i instrukcje ACF, teksty w adminie, komentarze sekcyjne (`/*--- ... ---*/`) — po polsku,
  zgodnie z istniejącym kodem.

⸻

WordPress / WooCommerce — stan faktyczny

- CPT: `offer` (slug `oferta`) + taksonomia `offer_category` (`kategoria-oferty`) — `app/post-types.php`.
- Menu: `primary_navigation`; renderowane walkerami `App\Walkers\DropdownWalker` i `MobileDropdownWalker`.
- Sidebary: `sidebar-primary`, `sidebar-shop`, `sidebar-footer-1..4`.
- Komentarze są globalnie wyłączone (`app/setup.php`) — nie dodawaj UI komentarzy.
- Edytor blokowy ma whitelistę bloków (`allowed_block_types_all` w `functions.php`): wszystkie `acf/*`
  plus `core/paragraph`, `core/heading`, `core/list`. Nowy blok core trzeba tam świadomie dopuścić.
- Contact Form 7: `wpcf7_autop_or_not` wyłączone, custom tag `[subsidy_checkboxes]`.
- Woo: wrappery `woocommerce_output_content_wrapper` są usunięte — layout robi motyw.
- Opcje globalne: strona `theme-settings` (`App\Fields\ThemeSettings`) + strony Options
  `OCta` (Wezwanie do działania), `OReviews` (Opinie), `OCertificates` (Certyfikaty), `OLogos` (Logotypy partnerów).
  Bloki `cta` / `reviews` / `certificates` / `logos` czytają z tych stron — patrz „Źródło danych: Options i CPT”.
  Dane globalne (logo, dane kontaktowe) są wstrzykiwane do wszystkich widoków przez
  `App\View\Composers\App` — nie wołaj `get_field(..., 'option')` w Blade, jeśli dane już tam są.

⸻

Znane niespójności — nie „naprawiaj" ich mimochodem, ale nie kopiuj wzorca

Zgłoś je, jeśli wejdą w drogę; samodzielna naprawa tylko wtedy, gdy blokuje zadanie:

- `functions.php` i `ThemeServiceProvider` odwołują się do `App\Blocks\ExampleBlock`, która **nie istnieje**.
- `app/setup.php` globuje `app/Woo/*.php` — katalog nie istnieje.
- `app/filters.php` wskazuje `resources/views/patterns/coming-soon.php` — plik nie istnieje
  (realny pattern to `patterns/woo-coming-soon.php`).
- `get_pdf_thumbnail_url()` jest zduplikowany w `app/setup.php` i `app/helpers.php` (różne DPI).
- `resources/views/blocks/posts.php` nie ma rozszerzenia `.blade.php`, choć `app/Blocks/Posts.php`
  ma `$slug = 'posts'`.
- W repo **nie ma** komponentu `x-picture` — zdjęcia przez `<img>` / `<picture>`; nie twórz `x-picture` przy okazji.
- `SectionClasses::backgroundChoices()` jest wspomniane w starszych notatkach, ale **metody nie ma** — kopiuj `choices` z istniejącego bloku.
- `app.css` definiuje `--color-third-*` na podstawie nieistniejących zmiennych `--third` / `--t-*`.
- W repo są trzy lockfile'e (yarn / npm / pnpm) — aktualny jest `yarn.lock`.

⸻

Git

Jedna gałąź: `cursor-work`. Nie twórz kolejnych feature branchy.

Do not rewrite Git history.

Do not force push.

Do not delete branches.

Do not discard unrelated local changes.

Do not commit unrelated files.

Before destructive Git operations, request explicit approval.

If commits are requested, keep them focused and use meaningful commit messages.

⸻

Security

Never expose or commit:

* passwords,
* API secrets,
* private keys,
* access tokens,
* database credentials,
* .env secrets,
* production credentials.

Do not print secrets into logs or responses.

Treat existing secrets found in the repository as sensitive.

W repozytorium są śledzone przez git pliki `key` i `key.pub` — `key` to prywatny klucz OpenSSH
(prawdopodobnie klucz deploymentu). Nie odczytuj jego zawartości, nie wypisuj jej w odpowiedziach,
nie kopiuj do innych plików i nie wysyłaj nigdzie. Jeśli zadanie dotyczy deploymentu — zgłoś to
użytkownikowi zamiast korzystać z klucza.

⸻

Definition of done

Zadanie kończy kompletna, przejrzana implementacja **zgodna z layoutem ramki Figmy** (fingerprint sekcji, kolumny, `justify-between` vs gap, kształt mediów, EKG/wektor, tło `section-*`, accordion jeśli w designie), konwencjami motywu, responsywnością i dostępnością. Nie zostawiaj szkieletu grid/flex, gdy Figma pokazuje gotową sekcję. Nie wklejaj klas MCP (`content-stretch`, `w-[1920px]`, `text-[54px]`). Nie wprowadzaj zbędnych duplikatów ani regresji. Sprawdź składnię i importy w dostępnym zakresie. Nowy blok ma pełne ustawienia i `SectionClasses::fromMap()`, `x-button` tam gdzie jest CTA, sekcyjne `-smt`, import SCSS i warunkowy import potrzebnego JS. Przypomnij użytkownikowi o wymaganym acf:cache, yarn build i commicie public/build; nie wykonuj tych komend.

⸻

Final rule

Understand the project before changing the project.

The repository is the source of truth.

When multiple technically correct solutions exist, prefer the solution that looks like it was written by the existing project team.

# Language

Communicate with the user in Polish.

All explanations, summaries, questions, implementation notes, and development-related communication should be written in Polish.

Code must follow the language conventions already established in the repository.

Unless the existing project uses a different convention, use English for:
- PHP class names,
- method and function names,
- variable names,
- file and directory names,
- ACF field names and keys,
- JavaScript identifiers,
- technical identifiers.

User-facing website content should remain in the language required by the project or provided design.

Do not translate existing code identifiers from English to Polish.
