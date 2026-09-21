# Figma → strona (`osf-import`)

Ten plik opisuje **tylko** workflow: link Figmy → bloki ACF → JSON → szkic w WP.

Anatomia bloku: `osf-import/BLOCKS.md` (szkielet wspólny) + **ten motyw** (`app/Blocks`, `variables.scss`, ewentualnie `BLOCKS_SYSTEM_PROMPT.md`). Nie kopiuj kolorów ani skali typu z innego projektu.

Kod kitu: katalog `osf-import/` obok `app/`. W korzeniu motywu muszą być widoczne dla Cursora: `AGENTS.md` (ten workflow) i anatomia (`BLOCKS.md` wklejona / `BLOCKS_SYSTEM_PROMPT.md`).

## Podpięcie WP-CLI

Wystarczy **jeden** sposób.

Preferowany (LocalWP, `wp` z katalogu WordPressa): w `ThemeServiceProvider::boot()` po `parent::boot();`

```php
if (defined('WP_CLI') && WP_CLI) {
	$cli = get_theme_file_path('osf-import/bootstrap.php');
	if (is_readable($cli)) {
		require_once $cli;
	}
}
```

Nie dokładaj `wp-cli.yml`, jeśli masz ten fragment. `wp-cli.yml` w motywie działa tylko, gdy odpalasz `wp` z katalogu motywu.

W nowym motywie skasuj `osf-import/project.php` (to twarde zakazy poprzedniej firmy). Zostawiasz go tylko, gdy sam dodasz reguły typu „slug X nie może mieć bloku Y”.

## Faza 1, potem od razu faza 2

Jedno zadanie z linkiem Figmy = oba kroki. Nie czekaj na „teraz JSON”.

Zakres linku:
- ramka **podstrony** (Homepage, Contact, …) → tylko ta;
- **Page / canvas** w Figmie (tablica z wieloma ramkami ~1920, np. `Devs`) → **wszystkie** bezpośrednie nazwane ramki podstron. Nie pytaj „którą”. Nie kończ po pierwszej. Każdą zrób fazą 1 i od razu fazą 2, potem następną.
- typ z nazwy ramki: domyślnie Page; `Blog` / `Blog-single` → szablony Blade (listing / wpis), nie JSON strony; główna ramka `… (CPT)` → wpis tego CPT; sekcja `… (Options)` → istniejąca strona Options, bez drugiej kopii w JSON.

Faza 1 — bloki albo szablony:
1. Figma MCP musi być włączone. Z URL `figma.com/design/:fileKey/…?node-id=12-34` weź `fileKey`; w `nodeId` zamień `-` na `:` (`12:34`). Link bez `node-id` jest niewystarczający — poproś o Page/canvas **albo** ramkę podstrony.
2. Skill: `skill://figma/figma-design-to-code/SKILL.md`. Przy `get_design_context` zawsze `skillNames: "resource:figma-design-to-code"`.
3. Canvas: `get_metadata` raz, żeby listę ramek (gdy timeout — dzieci z XML / nazwy warstw). Potem `get_metadata` i `get_design_context` na **każdej** ramce podstrony z osobna. Nie składaj serwisu ze screenshotu całego Page.
4. Pomiń chrome: `menu`, `header`, `footer`. Rozpakuj opakowania (`Content`, `Frame 4xx`, `Banner & Problem`) i mapuj **nazwane sekcje**.
5. `get_design_context` na **każdej** sekcji, którą składasz. Screenshot całej strony nie zastępuje kontekstu sekcji.
6. Slug bloku = nazwa warstwy: lowercase, jedno słowo, bez myślników (`Why us` → `whyus`). Nie aliasuj po podobnym H2. Ten sam tytuł ≠ ten sam blok.
7. Najpierw `app/Blocks`. Reuse tylko przy tym samym slugu **i** podobnym układzie. Inny layout = zmień Blade/SCSS **tego** bloku albo nowy slug. Nie twórz `hero2`.
8. Brakujący blok: PHP + Blade + SCSS według `osf-import/BLOCKS.md` i najbliższego istniejącego bloku **w tym motywie**. Nazwy pól ACF (`g_<slug>`, `r_<slug>`) z klasy, nie z głowy.
9. Blog / listing / single: jeśli w motywie to szablony (`home.blade.php`, `single.blade.php`), nie składaj ich jako Page z importera.

Faza 2 — treść:
10. Pages: `resources/imports/<slug>.json` (`title`, `slug`, `status: draft`, `blocks`) + JPG w `resources/imports/assets/`. Wzór: `osf-import/examples/page.json`.
11. Klucze w `data` muszą zgadzać się z polami ACF bloku. Tło: istniejące klasy motywu (`section-white`, `section-dark`, …), nie nowe nazwy.
12. Zdjęcia: JPG, `download_assets` z `defaultFormat: "jpg"` na węźle **fill zdjęcia**, nie na całej ramce z menu. Ikony i logotypy: SVG. Każda podstrona ma własne pliki (`kontakt-hero.jpg`), nawet gdy Figma współdzieli fill.
13. CPT / wpis: tylko gdy motyw już ma ten `post_type`. Oferta: `"post_type": "offer"` + `terms` + `wp osf offer import`. Blog-single: `osf-import/examples/post.json` + `resources/imports/posts/` + `wp osf post import`. Nie wymyślaj CPT.
14. Agent **nie** uruchamia `wp osf`, `yarn build`, `wp acorn acf:cache`. To użytkownik po `git pull`.
15. `wp osf page import` **nadpisuje** stronę o tym samym slugu. W podsumowaniu wypisz konkretne komendy i zmienione pliki.

## JSON strony (przykład)

```json
{
  "title": "Kontakt",
  "slug": "kontakt",
  "status": "draft",
  "blocks": [
    {
      "block": "banner",
      "data": {
        "g_banner": {
          "header": "Nagłówek",
          "text": "<p>Treść z Figmy.</p>",
          "image": {
            "src": "resources/imports/assets/kontakt-hero.jpg",
            "alt": "Nagłówek"
          }
        },
        "background": "none",
        "nomt": true
      }
    }
  ]
}
```

`block` i `g_banner` muszą istnieć w motywie. Jeśli pierwszy blok strony to `hero`, użyj `hero` / `g_hero`.

## Lokalnie (użytkownik)

```bash
git pull
wp acorn acf:cache
yarn build
wp osf page import resources/imports/<slug>.json
```

`acf:cache` gdy doszły nowe bloki. `yarn build` gdy zmienił się CSS/JS. Oferta / wpis — tylko gdy JSON leży w `resources/imports/offers/` albo `resources/imports/posts/`.
