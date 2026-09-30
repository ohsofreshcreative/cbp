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

Z katalogu motywu, z załadowanym WordPressem i ACF:

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
