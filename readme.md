# Local development

The Docker setup uses PHP 8.4, MariaDB 11.8, and phpMyAdmin.

## Requirements

- Docker with the Compose plugin.
- A local `.env` file created from `.env.example`.

On Windows, Docker Desktop with WSL 2 is recommended. Keep the project inside
the WSL filesystem for better performance when using a WSL-based Docker engine.

## Start the project

1. Copy `.env.example` to `.env` and replace its placeholder passwords with
   local development values. Do not commit `.env`.
2. Run `docker compose up --build -d`.
3. Open the site on `localhost` port `8080` and phpMyAdmin on `localhost`
   port `8081`.

Docker imports `db/create.sql` only when the database volume is created. If you
started the containers before the schema existed, recreate the volume with
`docker compose down -v` and then `docker compose up --build -d` (this deletes
the local Docker database).

## Start with XAMPP

1. In phpMyAdmin create an empty database named `atelier_tang` with collation
   `utf8mb4_unicode_ci`, then import `db/create.sql` into it.
2. Open `http://localhost/atelier-website/`: the repository-level `index.php`
   redirects to `src/index.php`, the same web root used by Docker.
3. XAMPP connects as `root` without password. To use other credentials, for
   example on the university server, create `src/includes/config.local.php`
   (ignored by Git) as described in `src/includes/config.php`.

The repository-level `.htaccess` blocks `.env`, `.git`, `db/`, and `docs/` when
XAMPP serves the whole repository.

## Test accounts

| Username | Password | Role |
| --- | --- | --- |
| `admin` | `admin` | Administrator: sees every request, filters and changes its status. |
| `user` | `user` | Customer: sees, edits, and deletes their own requests. |

## Daily workflow

- Update `main`, create a branch using `type/short-description`, and do not
  commit or push directly to `main`.
- Open a pull request and request at least one approval before merging.
- HTML, CSS, JavaScript, and PHP changes are available after refreshing the
  page. Restart the containers only when the Docker configuration changes.
- Use `docker compose logs -f web` to inspect Apache and PHP logs.
- Use `docker compose stop` to stop the services without removing database
  data.

## Project structure

- `index.php`: XAMPP entry point, redirects to `src/index.php`.
- `src/index.php`: home page with the booking form, processed by `src/prenota.php`.
- `src/atelier.php`, `servizi.php`, `realizzazioni.php`, `contatti.php`:
  content pages.
- `src/privacy.php`, `src/accessibilita.php`: privacy notice and accessibility
  statement, which also lists the access keys.
- `src/cerca.php`: results of the site search. The search form is in the
  header of every page.
- `src/login.php`, `registrazione.php`, `area.php`, `modifica.php`,
  `elimina.php`, `logout.php`: reserved area.
- `src/includes/`: shared code, not reachable from the browser.
  - `bootstrap.php`: session, security headers, database connection, helpers.
  - `header.php` and `footer.php`: markup shared by every page. Each page sets
    a `$page` array, usually with `page_meta()` (title, page heading and
    introduction, description, active menu item, breadcrumb).
  - `content.php`: page texts, working steps, FAQ, fabrics and the site
    search, so the search finds exactly what the pages show.
  - `cta.php`: the closing "Prenota una consulenza" band of the content pages.
  - `site.php`: address, phone, email and opening hours, so every page shows
    the same details.
  - `validation.php`: validation rules and messages, shared by PHP and
    `main.js` through `data-*` attributes.
  - `services.php`: the services with their descriptions (names and codes
    match the `servizi` table).
  - `config.php`: database settings.
- `src/css/style.css` (screen), `mobile.css` (screens narrower than 48em) and
  `print.css` (print: text and tables only).
- `src/js/main.js`: progressive enhancement (menu, form validation). Every
  feature also works without JavaScript.
- `src/fonts/`: self-hosted fonts with their SIL Open Font License files.
- `src/img/`: site images, logos and the decorative icons used by the CSS.
- `db/create.sql`: database schema (`utenti`, `servizi`, `prenotazioni`) and
  test data.

## Visual design

- Concept: "su misura". The tailor's tape measure under the header and above
  the footer is the only bold element; the rest stays quiet.
- Colours: navy `#14213d`, chalk `#f3f3f0`, red thread `#8c1d2c` (links),
  plum `#5b2a6e` (visited links), brass `#d9a86c` (only on dark backgrounds),
  ink `#1c2330`.
- Type: Jost for headings and Atkinson Hyperlegible Next for text, both sans
  serif. They are served from `src/fonts/`, so pages make no external requests.
- Every page uses the same blocks: page heading (`heading` and `lead` in
  `$page`), sections with a `section-title` heading, white cards, the
  closing band from `cta.php`, and the shared footer.

## Course rules checklist

Check these points before merging a change:

- HTML5 written with XML syntax: close every element (`<br />`), quote every
  attribute and write boolean attributes in full (`required="required"`).
- Complete separation of structure, presentation and behaviour: no `style`
  attributes, inline scripts or inline SVG. Decorative icons are CSS
  backgrounds and class names describe the content, not its look.
- Text contrast of at least 4.5:1, distinct colours for visited and active
  links, and visible focus on light and dark backgrounds. Colours and their
  ratios are listed at the top of `style.css`.
- Font sizes in `em`, line height 1.5, sans-serif fonts, no justified text.
- Every page sets `nav` in `$page`: the current menu item is rendered as text,
  not as a link to the page itself (no circular links).
- Access keys: 1 home, 2 skip to content, 3 services, 4 search, 5 booking,
  6 reserved area, 8 privacy, 9 contacts, 0 accessibility statement. Each key
  is used once per page and is listed in `accessibilita.php`.
- Forms are divided into `fieldset` with a `legend`; every field has a visible
  label. Error messages say what is wrong and how to fix it, submitted values
  are kept, and everything is validated again in PHP.
- Tables have `caption`, `thead`, `tfoot`, `tbody`, `scope`, `id` and
  `headers`, and are used only for tabular data.
- `alt` is empty for decorative images and 75 to 100 characters for content
  images. Images stay under 1 MB.
- Foreign words use the `lang` attribute (for example
  `<span lang="en">Home</span>`) and abbreviations use `<abbr title="...">`.
- No visual instructions in the text (no "sotto", "a destra", "clicca").
- The layout works at 320 pixels of width, without JavaScript, without CSS,
  and in print.
- Links are relative, so the site works in any folder of the server.

## Troubleshooting

| Problem | Suggested check |
| --- | --- |
| Docker reports a port conflict | Check whether ports 8080 or 8081 are already in use. |
| The site cannot connect to MariaDB | Confirm that `.env` contains all variables used by `compose.yaml` and inspect `docker compose logs db`. |
| Changes do not appear | Hard-refresh the browser and check that XAMPP is serving the repository root or Docker is serving `src/`. |
| The booking form reports a technical problem | The database is unreachable or empty: check `atelier_tang` exists and that `db/create.sql` was imported. |
