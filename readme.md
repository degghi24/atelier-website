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

The XAMPP document root uses the repository-level `index.php`. Docker mounts
`src/` as its web root and serves `src/index.php`.

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

- `index.php`: XAMPP entry point and booking form.
- `src/index.php`: Docker entry point and booking form.
- `src/html/`: static home, atelier, services, portfolio, and contact pages.
- `src/css/` and `src/js/`: shared presentation and behavior.
- `src/img/`: site images and logos.
- `db/`: database initialization scripts mounted by the database service.

## Known implementation gaps

- `db/create.sql` is currently empty, so the database has no application schema.
- The booking form points to `php/prenota.php`, and the login and reservation
  links point to PHP routes that are not present yet. Server-side validation,
  persistence, and account management therefore remain unimplemented.
- No npm manifest or lint script is currently defined.

## Troubleshooting

| Problem | Suggested check |
| --- | --- |
| Docker reports a port conflict | Check whether ports 8080 or 8081 are already in use. |
| The site cannot connect to MariaDB | Confirm that `.env` contains all variables used by `compose.yaml` and inspect `docker compose logs db`. |
| Changes do not appear | Hard-refresh the browser and check that XAMPP is serving the repository root or Docker is serving `src/`. |
