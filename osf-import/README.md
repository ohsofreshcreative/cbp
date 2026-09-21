# osf-import

Całe rozwiązanie **Figma (link) → bloki ACF → JSON → szkic strony w WordPress** siedzi w **tym jednym katalogu**.

Do innego motywu Sage kopiujesz folder `osf-import/` (obok `app/`). Nie zbierasz plików z `app/Support`, `app/Cli` ani `resources/cli`.

```
osf-import/
  README.md          ← ten plik
  AGENTS.md          ← workflow Figma → JSON
  BLOCKS.md          ← anatomia bloku (uniwersalna; tokeny i kolory z motywu)
  bootstrap.php      ← jedyny plik do podpięcia
  project.php        ← reguły TEGO motywu; w innym projekcie skasuj
  src/               ← importer PHP
  examples/          ← wzór JSON strony i wpisu
  tests/             ← testy bez WordPressa
```

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

Agent dostaje link Figmy → robi bloki (jeśli brak) + `resources/imports/<slug>.json` + JPG w `resources/imports/assets/` (to treść motywu, nie część kitu).

Lokalnie:

```bash
php osf-import/tests/test-page-import.php
wp osf page import resources/imports/<slug>.json
wp osf post import resources/imports/posts/<slug>.json
```

Przykłady:

```bash
wp osf page import osf-import/examples/page.json
```

JSON stron i zdjęcia konkretnej firmy **nie** wchodzą w ten folder — zostają w `resources/imports/` motywu.
