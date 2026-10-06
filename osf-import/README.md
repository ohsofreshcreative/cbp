# Importer treści

Narzędzia importu są odłączone od uruchamiania motywu i jego autoloadera Composer.
Zwykłe otwarcie strony, edytora lub uruchomienie WP-CLI nie ładuje tego katalogu.

- `bootstrap.php` — ręczne włączenie komendy `osf page import`.
- `src/` — komenda CLI, importer, walidacja JSON, obsługa assetów i serializacja ACF.
- `imports/` — dane stron, ofert i wpisów; `imports/assets/` — pliki źródłowe obrazów.
- `examples/hero-page.json` — przykładowa strona.
- `tests/page-import.php` — testy bez uruchamiania WordPressa.
- `osf-import.zip` — zachowane archiwum wcześniejszej wersji, nie jest ładowane.

## Uruchomienie

<<<<<<< HEAD
Z katalogu motywu, z załadowanym WordPressem i ACF:
=======
## Co musisz dopisać w nowym motywie (2 rzeczy)

1. **WP-CLI** — preferowany sposób (LocalWP): w `ThemeServiceProvider::boot()` po `parent::boot();`

```php
if (defined('WP_CLI') && WP_CLI) {
	$cli = get_theme_file_path('osf-import/bootstrap.php');
	if (is_readable($cli)) {
		require_once $cli;
	}
}
```

Nie dokładaj `wp-cli.yml`, jeśli masz ten fragment. `wp-cli.yml` w motywie (`require: osf-import/bootstrap.php`) działa tylko, gdy `wp` odpalasz z katalogu motywu.

2. **Agent (Cursor)** — w korzeniu motywu:
- `AGENTS.md` ← treść `osf-import/AGENTS.md`
- anatomia: wklej `osf-import/BLOCKS.md` do `AGENTS.md` albo zapisz jako `BLOCKS_SYSTEM_PROMPT.md` (zastępuje stary prompt TSP/CBP). Hexów i `text-h*` w px nie kopiuj z poprzedniej firmy — agent ma czytać `variables.scss` tego motywu.

W nowym motywie **usuń** `osf-import/project.php` (zakazy poprzedniej firmy).

Opcjonalnie: `composer dump-autoload`, gdy w `composer.json` motywu dodasz `"classmap": ["osf-import/src"]`. Bez tego i tak działa, bo `bootstrap.php` ładuje klasy sam.

## Wymagania docelowego projektu

- WordPress + WP-CLI
- Sage 11 / Acorn, ACF Pro, ACF Composer (`app/Blocks/*.php`)
- w Cursorze: **Figma MCP** i dostęp do pliku Figmy
- link z `node-id` ramki podstrony: `figma.com/design/:fileKey/…?node-id=12-34`

Bez MCP i `AGENTS.md` sam importer nie odczyta Figmy — złoży tylko JSON, który agent już zapisze.

## Użycie

Agent dostaje link Figmy. Ramka podstrony = jedna strona WP. Page/canvas (np. `Devs`) = wszystkie ramki podstron, każda end-to-end. Bloki (jeśli brak) + `resources/imports/<slug>.json` + JPG w `resources/imports/assets/` (to treść motywu, nie część kitu).

Lokalnie:
>>>>>>> origin/cursor-work

```bash
wp --require=osf-import/bootstrap.php osf page import osf-import/examples/hero-page.json
```

Aktualna komenda tworzy nową stronę; nie aktualizuje istniejącej. Domyślny status to
`draft`, a JSON może ustawić inny status. Dane ofert i wpisów zachowano w `imports/`,
ale aktualny kod komendy obsługuje tylko strony (`post_type: page`).

Nie dodawaj importera do `functions.php`, providera motywu, autoloadera Composer
ani głównego `wp-cli.yml`. Cały katalog można pominąć przy wdrażaniu strony.
Zaimportowane obrazy są kopiowane do biblioteki mediów WordPressa.

Po przeniesieniu plików w istniejącej instalacji odśwież mapę klas:

```bash
composer dump-autoload --no-scripts
```

Testy:

```bash
php osf-import/tests/page-import.php
```

Poprawka walidacji wcześniej zapisanych bloków pozostaje w `app/setup.php`.
Nie korzysta z importera i jest potrzebna do edycji istniejących treści.
