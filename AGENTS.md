nAGENTS.md

Wspólne instrukcje dla Codex, GitHub Copilot i Claude Code.
Edytuj wyłącznie ten plik. CLAUDE.md jest dowiązaniem do AGENTS.md.

Jedna gałąź robocza: `cursor-work`.
Nie twórz nowych branchy per podstrona, per blok, per import ani `cursor/<nazwa>-…`.
Nie otwieraj osobnych PR-ów dla kolejnych podstron — commituj i pushuj na `cursor-work`.
Jeśli sesja startuje na innej gałęzi, przełącz się na `cursor-work` (stwórz ją z aktualnego HEAD, gdy nie istnieje) i tam pracuj.
Nazwy `cursor` Git nie przyjmie, dopóki istnieją gałęzie `cursor/…` (konflikt ścieżki refs).

Kolejność jest sztywna — faz nie odwracaj. W jednym zadaniu z Figmy/screenem zrób fazę 1, a gdy bloki albo szablony są na miejscu, **od razu sam startuj fazę 2**. Dla Pages: JSON + assety + commit + push. Dla Blog / Blog-single: dopracowane Blade + JSON **wpisu** (`resources/imports/posts/`) + commit + push, **bez** JSON-a strony. Nie czekaj na osobną prośbę „krok 2”.

Faza 1 — bloki ACF albo szablony (najpierw, dla wszystkich wskazanych ramek):
- Weź ramki z Figmy (link z `node-id`) albo screen.
- Najpierw rozstrzygnij typ ramki (patrz „Szablony WP, nie Pages” oraz „Źródło danych: Options i CPT”). `Blog` i `Blog-single` to **nie** Pages — od razu edytuj Blade listingu / wpisu, nie składaj strony Gutenberg.
- Dla prawdziwych Pages: zmapuj sekcje na istniejące bloki w `app/Blocks`. Reuse before creation. Jeśli blok już czyta z Options albo CPT — nie twórz drugiej strony opcji ani repeatera z kafelkami w JSON strony.
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

Bez nawiasu i tak honoruj mapę poniżej. Samo istnienie strony Options albo query CPT w `with()` wygrywa nad copy z Figmy w JSON strony.

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
| `offer` | `oferta` | `offer_category` (`kategoria-oferty`) | `app/Fields/OfferFields.php` (`offer_icon`) | `offers` — `get_posts` opublikowanych wpisów; w JSON strony tylko `header`, `link_label`, opcjonalnie `offer_category` |

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
| `resources/views/components/*.blade.php` | `x-button`, `x-picture`, `x-alert`, `x-icon.arrow-up` |
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
- Jeśli po normalizacji slug albo oczywisty alias już istnieje w `app/Blocks` — **użyj istniejącego bloku**, nie twórz drugiego.
- Aliasy warstw z pliku Figmy (Design / Devs): `Process` → `proces`, `FAQ` → `faq`, `CTA` / `cta-section` → `cta`, `Testimonials` → `reviews`, `Aboutv2` / `Aobut` → `about`, `Solution` → `content`, `Banner` → `banner` (Hero - Podstrona, nie homepage Hero), `Standard` → `cards`.
- Nowe warstwy Devs bez aliasu (slug = nazwa warstwy): `Reach` → `reach`, `Explore` → `explore`, `Gains` → `gains`, `Tiles` → `tiles`.
- `Blogs` jako **sekcja na stronie** (np. homepage) → blok `posts`. Cała ramka podstrony `Blog` / `Blog-single` to szablony WP, nie alias na blok i nie Page (patrz „Szablony WP, nie Pages”).
- `Frame 460` i `fi_*` to nie nazwy bloków — pomiń, gdy puste. Żółte CTA we wpisie (`__cta`, „Masz więcej pytań dotyczących badania?”) → blok `action` (`CTA - Wpis`).
- Nie używaj jako nazwy bloku: `Frame 123`, `Group`, `Rectangle`, `__wrapper`, warstw wewnętrznych ani copy z H1/H2.
- Pomiń chrome strony: `menu`, `header`, `footer` i puste kontenery-opakowania.

Nie wymyślaj nazw spoza warstwy i spoza promptu. Nie tłumacz polskich nagłówków na angielski slug, jeśli ramka ma już angielską nazwę (`Dla kogo pracujemy?` to treść, ramka to `Problem` → `problem`).

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

Listę teł pobieraj z `\App\Support\SectionClasses::backgroundChoices()`, jeśli ta metoda istnieje.
Jeśli jej nie ma, zachowaj listę `choices` z istniejących bloków PHP. Przed wyborem tła sprawdź
aktualne opcje w repozytorium — nie wymyślaj nowych wartości.

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

Przed implementacją sprawdź strukturę repozytorium, Blade, ACF/PHP, komponenty, SCSS i JS. Rozpoznaj nazewnictwo, kontenery, grid, odstępy, breakpointy, typografię, assety, obrazy i przyciski. Dla projektu graficznego znajdź podobny istniejący UI. Nie generuj plików przed analizą.

⸻

Reuse before creation

Wyszukaj i wykorzystaj istniejące komponenty, helpery, tokeny i wzorce: przyciski, nagłówki, kontenery, karty, ikony, formularze, slidery, akordeony, modale, breadcrumbs, menu, obrazy, ACF i responsywność. Nie twórz niemal identycznych wersji.

⸻

Working from screenshots or designs

Screen, ramka Figma albo link `figma.com/design/…?node-id=` jest specyfikacją: zachowaj układ, hierarchię, proporcje, odstępy, wyrównanie i kadrowanie. Używaj istniejących tokenów oraz ograniczeń sekcji Styling. Nie wymyślaj dekoracji ani nie upraszczaj istotnych szczegółów tylko dla wygody.

Link Figma bez `node-id` jest niewystarczający — poproś o ramkę sekcji albo podstrony, nie zgaduj węzła. Z Figmy najpierw bloki ACF albo szablony Blade (faza 1), potem od razu JSON **dla Pages** oraz JSON **wpisu** dla `Blog-single` (faza 2). `Blog` / `Blog-single` → Blade listingu/wpisu, bez JSON-a strony. Komendy `wp osf page import` / `wp osf post import` nie uruchamiaj — zostają u użytkownika. Slug bloku bierz z nazwy ramki sekcji (patrz „Nazwa bloku z Figmy”); nazwa podana w prompcie wygrywa.

⸻

Responsive implementation

Stosuj breakpointy projektu. Z desktopowego projektu wyprowadź logiczny układ mobilny: czytelna hierarchia, brak overflow, naturalne ułożenie kolumn, sensowne odstępy, widoczne CTA i odpowiednie kadrowanie. Nie skaluj wszystkiego proporcjonalnie i nie dodawaj zbędnych breakpointów.

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

### Minimum CSS — Tailwind w Blade jest domyślne

Dodawaj minimalną liczbę klas Tailwind potrzebną do układu i działania. Nie dopisuj klas redundantnych, dekoracyjnych, zbędnych nadpisań ani wariantów „na zapas”. Mniej klas nie oznacza przenoszenia ich do SCSS — najpierw usuń zbędną stylizację, a potrzebny układ zapisz utility classes. Plik SCSS bloku i jego import twórz zawsze, nawet gdy selektor pozostaje pusty.

Nowe bloki implementuj przede wszystkim klasami Tailwind bezpośrednio w widoku Blade. Dotyczy to
w szczególności:

* `display`, grid i flex,
* szerokości, wysokości oraz `min-height` / `max-width`,
* pozycjonowania i `inset`,
* paddingów, marginesów i gapów,
* kolorów i podstawowych struktur przestrzennych,
* kolejności elementów,
* breakpointów i całego zachowania responsywnego.

Ważne: nie dodawaj niestandardowych klas CSS/SCSS do nowych bloków tylko po to, żeby odwzorować
screen. Dla mockupów lepiej zrobić prosty układ i zostawić resztę użytkownikowi do dopracowania.
Nie twórz dekoracyjnych klas typu `__shape`, `__glow`, `__icon`, `__grid` z osobnym SCSS, jeśli nie jest
to konieczne dla poprawnego działania. Jeśli element dekoracyjny ma być w markupu, dodaj tylko prosty
semanticzny znacznik bez osobnej stylizacji, a nie cały zestaw customowych klas i reguł.

Nie dodawaj automatycznie `gap-6` do wrapperów treści ani jako jednakowego odstępu między wszystkimi elementami bloku. Odstępy między nagłówkiem, treścią, podpisem i innymi elementami są dobierane indywidualnie przez użytkownika. Nie zastępuj `gap-6` inną arbitralnie wybraną klasą `gap-*`; dodawaj takie odstępy tylko na wyraźną prośbę użytkownika lub zgodnie z ustalonym wzorcem konkretnego układu.

**Zasada absolutnego braku mikro-typografii i klas ozdobnych:**
Nie dodawaj w szablonie żadnych klas związanych z dokładnym rozmiarem pisma (`text-sm`, `text-xs`, `text-lg`), wagą czcionki (`font-medium`, `font-semibold`, `font-bold`), wysokością linii (`leading-relaxed`, `leading-normal`) czy zaokrągleniami oraz mikromarginesami tekstowymi, jeśli nie zostaniesz o to wyraźnie poproszony. Tworzymy wyłącznie czysty szkielet strukturalny (grid, flex, gap, paddingi sekcji, bordery i kolory tła). Cała typografia i niestandardowy wygląd tekstu są dopracowywane bezpośrednio przez użytkownika we własnym zakresie.

Nie używaj ad-hoc klas typu `min-h-*`, `max-h-*`, `rounded-*`, `rounded-[...]`, `min-h-[...]`,
`radius-*` (jeśli nie istnieje to w projekcie jako token), chyba że dana klasa jest już zdefiniowana
w design systemie motywu. W tym repo preferowane są istniejące klasy typu `radius`, `radius-img`,
`c-main`, `m-header`, `m-btn`, `m-img`, a nie arbitralne wartości na siłę.

Tak samo nie używaj arbitralnych klas typograficznych typu `text-[42px]`, `leading-[1.1]`,
`font-header`, `text-primary-dark` tam, gdzie projekt nie ma już gotowego tokenu lub wzorca.
Nie tworzymy nowych klas „na szybko” dla jednego mockupu; jeśli czegoś nie ma w design systemie,
lepiej zostawić prosty układ i pozwolić użytkownikowi dopracować styl ręcznie.

Plik `resources/css/blocks/<slug>.scss` nadal utwórz i zaimportuj, ale domyślnie zostaw w nim tylko:

```scss
.b-<slug> {
}
```

Nie przenoś klas możliwych do zapisania w Tailwindzie do selektorów `.__wrapper`, `.__content`,
`.__media`, `.__img`, `.__txt` ani do lokalnych `@media`. Nie twórz w SCSS kompletnego layoutu bloku
ani jego osobnej implementacji responsywnej.

Custom CSS dodawaj wyłącznie wtedy, gdy jest rzeczywiście niezbędny i nie da się go rozsądnie zapisać
istniejącymi utility classes. Typowe wyjątki to pseudoelementy, stylowanie HTML generowanego przez
WYSIWYG lub zewnętrzną wtyczkę oraz złożony selektor niemożliwy do wyrażenia w Blade. Nawet wtedy
dodaj absolutne minimum deklaracji potrzebnych dla tego wyjątku.

Nie dodawaj zapasowych wariantów, dodatkowych breakpointów, stanów, klas typu `order-flip` ani
rozbudowanych styli „na przyszłość”, jeśli użytkownik nie poprosił o nie w danym zadaniu. Użytkownik
samodzielnie rozbuduje później styling, jeśli będzie potrzebny.

Używaj istniejących tokenów i standardowych klas projektu. Wartości arbitralne Tailwinda stosuj tylko
wtedy, gdy konkretna wartość wynika bezpośrednio z projektu i nie ma odpowiadającego jej tokenu.

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

Zrozum zadanie → zbadaj repozytorium i podobne implementacje → znajdź elementy do ponownego użycia → wybierz najmniejszą zmianę → zaimplementuj → przejrzyj całość, błędy, responsywność i wpływ na istniejące funkcje. Samo utworzenie plików nie kończy zadania.

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

W nowych blokach nie dodawaj klas `max-w-*`, `min-h-*`, `leading-normal` ani żadnych klas rozmiaru
fontu (`text-base`, `text-lg`, `text-xl`, `text-h*` itd.), chyba że użytkownik wyraźnie poprosi
o konkretną klasę. Nie ograniczaj nimi nagłówków, treści ani wrapperów na podstawie własnych założeń.
Nie dodawaj też elementom tekstowym klas marginesu (`mt-*`, `mb-*`, `mx-*`, `my-*`, `m-*`) bez
wyraźnej prośby użytkownika.

| Klasa | Max-width |
|---|---|
| `c-main` | 1376px — domyślny wrapper bloku |
| `c-narrow` | 1176px — węższe treści |
| `c-wide` / `.wide .c-main` | 100% — tryb `wide` |

**Odstępy sekcji** — nie używaj `mt-*` na `<section>`, tylko: `-smt` / `-spt` / `-smb` / `-spb` (104px),
oraz `-menu-mt` / `-menu-pt`. Marginesy wewnętrzne: `m-header`, `m-title`, `m-btn`, `m-img`
(utility zdefiniowane w `app.css`).

**Typografia**: `text-h1` … `text-h7`, `text-big`, `text-gradient`, `font-header` (Poppins) /
`font-body` (GeneralSans, lokalne `.otf` w `resources/fonts`).

**Kolory**: `--color-primary*` (#00A6DF), `--color-secondary*` (#EB007F), `--color-page`, `--color-bright`,
skale 50–900 + `-hover` i `-dark`. Nie wpisuj hexów w Blade.

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

**Zdjęcia** — komponent `x-picture` (art direction przez `<source>`):

```blade
<x-picture :image="$g_hero['image']" figureClass="__img" class="w-full object-cover" data-gsap-element="img" />
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
- `x-picture` używa rozmiarów `img-sm|md|lg|xl`, ale w motywie nie ma żadnego `add_image_size()` —
  zweryfikuj przed poleganiem na `<source>`.
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

Zadanie kończy kompletna, przejrzana implementacja zgodna z projektem, repozytorium, responsywnością i dostępnością. Nie wprowadzaj zbędnych duplikatów ani regresji. Sprawdź składnię i importy w dostępnym zakresie. Nowy blok ma pełne ustawienia i SectionClasses::fromMap(), komponenty x-button/x-picture, sekcyjne odstępy, import SCSS i warunkowy import potrzebnego JS. Przypomnij użytkownikowi o wymaganym acf:cache, yarn build i commicie public/build; nie wykonuj tych komend.

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
