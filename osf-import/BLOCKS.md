# Anatomia bloku ACF (`osf-import`)

Uniwersalne dla motywów OhSoFresh: Sage 11 + Acorn + ACF Composer + Blade + Tailwind.

**Ten plik = szkielet i nazwy.** Kolory, px, warianty przycisków, lista teł i to, czy jest `block-title`, bierz z **tego** motywu: jeden istniejący blok (np. `Hero.php` / `Banner.php`), `resources/css/variables.scss` (albo `@theme` w `app.css`) oraz opcjonalnie `BLOCKS_SYSTEM_PROMPT.md` w korzeniu. Nie kopiuj hexów ani skali typu z CBP / TSP.

W Cursorze: treść tego pliku wklej do `AGENTS.md` pod workflow Figmy **albo** zostaw jako `BLOCKS_SYSTEM_PROMPT.md` w korzeniu (Cursor i tak musi to widzieć poza samym `osf-import/`).

## Pliki bloku

| Plik | Ścieżka |
|---|---|
| Klasa | `app/Blocks/Nazwa.php` (`$slug` = `nazwa`) |
| Widok | `resources/views/blocks/nazwa.blade.php` |
| SCSS | `resources/css/blocks/nazwa.scss` — zawsze, choćby pusty `.b-nazwa { }`, import w `app.css` |
| JS | `resources/js/blocks/nazwa.js` tylko gdy potrzebny; w `app.js` warunek `if (document.querySelector('.b-nazwa'))` |

Slug: jedno słowo, lowercase, bez myślników. Klasa PHP = studly (`Whyus`). Rejestracja automatyczna (ACF Composer skanuje `app/Blocks`).

Zanim napiszesz nowy blok: otwórz **najbliższy istniejący** w tym motywie i skopiuj jego taby / `with()` / ustawienia. Ten plik nie wygrywa z kodem w repo.

## PHP — szkielet

```php
public $name = 'Nazwa w edytorze';
public $slug = 'nazwa';
public $category = 'formatting';
public $mode = 'edit';
```

W `fields()`:

- `->setLocation('block', '==', 'acf/<slug>')` — zawsze
- `block-title` i akordeon „Treści bloku” **tylko jeśli już są** w blokach tego motywu. Nie dodawaj ich „na zapas”
- zakładka treści: `Elementy` albo `Treści`
- grupa `g_<slug>`, repeater `r_<slug>` (`layout => table`)
- grupa i repeater: osobne taby, gdy oba są. Sam nagłówek + repeater = jedna zakładka, bez taba tylko na H2
- ostatnia zakładka zawsze: `Ustawienia bloku` (`section_id`, `section_class`, `flip`, `wide`, `nomt`, `gap`, `background`)

Nagłówek: `addText('header')`. Opis: `addWysiwyg('text', …)` — nie `addTextarea` do opisu, chyba że analogiczny blok w tym motywie tak ma.

Etykiety po polsku, nazwy pól po angielsku. TrueFalse: `'ui' => 1, 'ui_on_text' => 'Tak', 'ui_off_text' => 'Nie'`.

Obraz i link: `'return_format' => 'array'`.

`background`: `choices` skopiuj z istniejącego bloku tego motywu (nie wymyślaj `section-*`). `default_value` zwykle `section-white`, nie `none`, gdy Figma ma jasną sekcję. Fallback w `with()`:

```php
'background' => get_field('background') ?: get_field('default_block_background', 'option') ?: 'none',
```

(jeśli motyw nie ma opcji `default_block_background`, zostaje `?: 'none'`).

```php
$fields['sectionClass'] = SectionClasses::fromMap($fields, [
	'flip' => 'order-flip',
	'wide' => 'wide',
	'nomt' => '!mt-0',
	'gap' => 'wider-gap',
]);
```

Booleany: `(bool) get_field(...)`.

## Blade — szkielet

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

- `{{ }}` tekst, `{!! !!}` tylko WYSIWYG
- każde opcjonalne pole w `@if (!empty(...))`
- BEM: `__wrapper`, `__col`, `__top`, `__content`, `__img`, `__card`, `__txt`, `__inside`
- GSAP: `data-gsap-anim="section"`; elementy `data-gsap-element="img|header|txt|text|card|btn"`
- przyciski: komponent `x-button` tego motywu, warianty jak w istniejących widokach; grupa w `inline-buttons m-btn`
- spacing sekcji: `-smt` / `-spt` na `<section>`, nie `mt-*` / `py-*` na sekcji
- kontener: `c-main` (w starterze OSF zwykle 1376px) — **sprawdź** `variables.scss`. Nie wymyślaj `max-w-[1328px]`
- nie dokładaj mikro-typografii (`text-sm`, `font-semibold`, `text-[42px]`) ani `gap-6` „bo tak”, jeśli użytkownik o to nie prosi i Figma nie wymusza. Tokeny `text-h*`, `m-header`, `m-btn` — tylko te, które motyw już ma

## SCSS

Domyślnie tylko:

```scss
.b-nazwa {
}
```

Import w `app.css` do sekcji używanych bloków. Custom CSS: pseudoelementy, HTML z WYSIWYG, coś czego nie da się zapisać utility. Nie przenoś całego layoutu do SCSS.

## Checklist

- `$slug` = nazwa pliku Blade = klasa `b-<slug>`
- `setLocation` na `acf/<slug>`
- `with()` + `SectionClasses::fromMap`
- import SCSS; JS tylko z warunkiem w `app.js`
- pola JSON importu (`g_<slug>`, `r_<slug>`) = pola ACF

## Czego tu nie ma (zostaje w motywie)

Hexy (`--color-primary`), dokładne `text-h1` w px, lista wariantów `btn-*`, niestandardowe tła, `x-picture` vs zwykłe `<img>`. To różni TSP od CBP — agent ma to odczytać z kodu, nie z tego pliku.
