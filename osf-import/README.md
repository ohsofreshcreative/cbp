# osf-import

Całe rozwiązanie **Figma (link) → bloki ACF → JSON → szkic strony w WordPress** siedzi w **tym jednym katalogu**.

Do innego motywu Sage kopiujesz folder `osf-import/` (obok `app/`). Nie zbierasz plików z `app/Support`, `app/Cli` ani `resources/cli`.

```
osf-import/
  README.md          ← ten plik
  AGENTS.md          ← instrukcje dla agenta (link z Figmy)
  bootstrap.php      ← jedyny plik do podpięcia
  project.php        ← reguły TEGO motywu; w innym projekcie skasuj
  src/               ← importer PHP
  examples/          ← wzór JSON strony i wpisu
  tests/             ← testy bez WordPressa
```

## Co musisz dopisać w nowym motywie (2 rzeczy, nie pliki z wielu folderów)

1. **WP-CLI** — w korzeniu motywu `wp-cli.yml`:

```yaml
require:
  - osf-import/bootstrap.php
```

Jeśli plik już istnieje, dopisz tylko tę ścieżkę do `require`.

2. **Agent (Cursor)** — skopiuj treść `osf-import/AGENTS.md` do `AGENTS.md` w korzeniu **tego** projektu (albo wklej na górę istniejącego). Cursor czyta `AGENTS.md` z roota, nie z podfolderu.

`project.php` w CBP blokuje `solutions` na stronie `b2c`. W innym motywie **usuń** `osf-import/project.php`.

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
