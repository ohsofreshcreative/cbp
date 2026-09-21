# Figma → strona (kit `osf-import`)

Ten plik jest uniwersalny. Wklej go do `AGENTS.md` korzenia motywu albo trzymaj jako źródło prawdy kitu. Kod importera: katalog `osf-import/` obok `app/`.

## Co robi agent po linku z Figmy

1. W Cursorze musi być Figma MCP. Z URL `figma.com/design/:fileKey/…?node-id=12-34` weź `fileKey` i zamień `-` na `:` w `nodeId` (`12:34`). Link bez `node-id` jest niewystarczający — poproś o ramkę podstrony.
2. Wczytaj skill `skill://figma/figma-design-to-code/SKILL.md`. Przy `get_design_context` użyj `skillNames: "resource:figma-design-to-code"`.
3. `get_metadata` na **ramce podstrony**, nie na całym pliku. Pomiń chrome (`menu`, `header`, `footer`).
4. Każdą nazwaną sekcję zmapuj na blok ACF: slug = nazwa warstwy, jedno słowo, lowercase, bez myślników (`Why us` → `whyus`). Nie aliasuj po podobnym H2.
5. Reuse istniejącego bloku w `app/Blocks` tylko przy tym samym slugu i podobnym układzie. Inny layout = zmień Blade/SCSS **tego** bloku albo nowy slug, nie `wehelp2` na siłę.
6. Brakujący blok: klasa PHP + widok Blade + pusty SCSS, według anatomii motywu (Sage / ACF Composer).
7. Potem JSON: `resources/imports/<slug>.json` + unikalne JPG w `resources/imports/assets/`. Nie uruchamiaj `wp osf`, `yarn build`, `acf:cache` — to użytkownik lokalnie.
8. `wp osf page import` nadpisuje stronę o tym samym slugu (`wp_update_post`).

## JSON strony

```json
{
  "title": "B2C",
  "slug": "b2c",
  "status": "draft",
  "blocks": [
    {
      "block": "hero",
      "data": {
        "g_hero": {
          "header": "Nagłówek",
          "text": "<p>Treść</p>",
          "image": { "src": "resources/imports/assets/b2c-hero.jpg", "alt": "…" }
        },
        "background": "section-white"
      }
    }
  ]
}
```

Wzór: `osf-import/examples/page.json`. CPT: to samo + `"post_type": "offer"` i opcjonalnie `"terms"`. Wpis bloga: `osf-import/examples/post.json` + `wp osf post import`.

Zdjęcia jako JPG (`download_assets`, `defaultFormat: "jpg"` na węźle fill, nie na całej ramce). Ikony SVG. Każda podstrona ma własne pliki obrazów.

## Lokalnie (użytkownik)

```bash
wp osf page import resources/imports/<slug>.json
wp osf offer import resources/imports/offers/<slug>.json
wp osf post import resources/imports/posts/<slug>.json
wp acorn acf:cache
yarn build
```

Testy kitu (bez WP): `php osf-import/tests/test-page-import.php`

## Instalacja kitu w motywie

Zobacz `osf-import/README.md`. Kopiujesz **jeden** folder. `project.php` zostawiasz tylko gdy chcesz twardych zakazów typu „slug strony X nie może mieć bloku Y”.
