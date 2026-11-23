<?php
/* Atelier Tang - bootstrap.php
   Shared setup for every page: configuration, session, security headers,
   database connection and small helpers. Include it before any output. */

declare(strict_types=1);

define('APP_ROOT', dirname(__DIR__));

date_default_timezone_set('Europe/Rome');
mb_internal_encoding('UTF-8');

require __DIR__ . '/site.php';
require __DIR__ . '/services.php';
require __DIR__ . '/content.php';
require __DIR__ . '/validation.php';
require __DIR__ . '/requests.php';

/* ---------- Session ---------- */
/* A dedicated cookie name keeps this site separate from other projects on localhost. */
/* The cookie is limited to the site folder, so the site can be installed in any
   directory of a shared server (for example tecweb.studenti.math.unipd.it/utente/). */
$cookiePath = rtrim(strtr(dirname($_SERVER['SCRIPT_NAME'] ?? '/'), '\\', '/'), '/') . '/';
session_name('ATELIERTANG');
session_set_cookie_params([
    'lifetime' => 0,
    'path' => $cookiePath,
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'httponly' => true,
    'samesite' => 'Lax',
]);
ini_set('session.use_strict_mode', '1');
session_start();

if (empty($_SESSION['csrf']) || !is_string($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

/* ---------- Security headers ---------- */
header('Content-Type: text/html; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: same-origin');
header("Content-Security-Policy: default-src 'self'; img-src 'self' data:; object-src 'none'; base-uri 'self'; form-action 'self'; frame-ancestors 'none'");

/* ---------- Configuration and database ---------- */
function app_config(): array
{
    static $config = null;
    if ($config === null) {
        $config = require __DIR__ . '/config.php';
    }
    return $config;
}

/* Returns a shared PDO connection. Throws PDOException when the database is unavailable. */
function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $config = app_config();
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
            $config['db_host'],
            $config['db_port'],
            $config['db_name']
        );
        $pdo = new PDO($dsn, $config['db_user'], $config['db_password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
    return $pdo;
}

/* ---------- Output helpers ---------- */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/* Adds the file modification time to static assets so browsers reload them only after a change. */
function asset(string $path): string
{
    $file = APP_ROOT . '/' . $path;
    $version = is_file($file) ? (string) filemtime($file) : '1';
    return e($path . '?v=' . $version);
}

/* Formats a Y-m-d date in Italian, for example "martedì 1 dicembre 2026". */
function format_date_it(string $date): string
{
    $parsed = DateTimeImmutable::createFromFormat('!Y-m-d', substr($date, 0, 10));
    if ($parsed === false) {
        return $date;
    }
    $days = ['domenica', 'lunedì', 'martedì', 'mercoledì', 'giovedì', 'venerdì', 'sabato'];
    $months = [1 => 'gennaio', 'febbraio', 'marzo', 'aprile', 'maggio', 'giugno', 'luglio',
        'agosto', 'settembre', 'ottobre', 'novembre', 'dicembre'];
    return $days[(int) $parsed->format('w')] . ' ' . $parsed->format('j') . ' '
        . $months[(int) $parsed->format('n')] . ' ' . $parsed->format('Y');
}

/* ---------- Requests, redirects and messages ---------- */
function is_post(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

/* Reads a POST value as a string, ignoring arrays and other unexpected types. */
function post_string(string $key): string
{
    $value = $_POST[$key] ?? '';
    return is_string($value) ? $value : '';
}

/* Reads a positive integer id from GET or POST. Returns 0 when it is missing or invalid. */
function request_id(): int
{
    $value = $_POST['id'] ?? $_GET['id'] ?? '';
    if (!is_string($value) || !preg_match('/^[1-9][0-9]{0,9}$/', $value)) {
        return 0;
    }
    return (int) $value;
}

function redirect(string $location): void
{
    header('Location: ' . $location, true, 303);
    exit;
}

/* Stores a one-time message ("successo", "errore" or "info") shown on the next page. */
function flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function take_flash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return is_array($flash) ? $flash : null;
}

/* Renders a flash message. The focusable box is focused on load by main.js so that
   screen reader users hear the outcome of the action they have just completed. */
function render_flash(?array $flash, string $id = 'esito', bool $focus = true): string
{
    if ($flash === null) {
        return '';
    }
    $type = in_array($flash['type'] ?? '', ['successo', 'errore', 'info'], true) ? $flash['type'] : 'info';
    return '<p id="' . e($id) . '" class="flash flash-' . e($type) . '" tabindex="-1"'
        . ($focus ? ' data-focus-on-load="true"' : '') . '>' . e((string) ($flash['message'] ?? '')) . '</p>';
}

/* Keeps errors and submitted values across a redirect (Post/Redirect/Get pattern). */
function remember_form(string $form, array $errors, array $oldValues): void
{
    $_SESSION['forms'][$form] = ['errors' => $errors, 'old' => $oldValues];
}

function take_form(string $form): array
{
    $state = $_SESSION['forms'][$form] ?? null;
    unset($_SESSION['forms'][$form]);
    if (!is_array($state)) {
        return ['errors' => [], 'old' => []];
    }
    return [
        'errors' => is_array($state['errors'] ?? null) ? $state['errors'] : [],
        'old' => is_array($state['old'] ?? null) ? $state['old'] : [],
    ];
}

/* ---------- CSRF protection ---------- */
function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e($_SESSION['csrf']) . '" />';
}

function csrf_valid(): bool
{
    $token = $_POST['csrf'] ?? '';
    return is_string($token) && hash_equals($_SESSION['csrf'], $token);
}

/* ---------- Authentication ---------- */
function current_user(): ?array
{
    $user = $_SESSION['utente'] ?? null;
    return is_array($user) ? $user : null;
}

function is_admin(): bool
{
    $user = current_user();
    return $user !== null && ($user['ruolo'] ?? '') === 'admin';
}

/* Stores the authenticated user and renews the session id to prevent session fixation. */
function log_in(array $user): void
{
    session_regenerate_id(true);
    $_SESSION['utente'] = [
        'id' => (int) $user['id'],
        'username' => (string) $user['username'],
        'nome' => (string) $user['nome'],
        'email' => (string) $user['email'],
        'ruolo' => (string) $user['ruolo'],
    ];
}

/* Sends anonymous visitors to the login page and returns the current user otherwise. */
function require_login(): array
{
    $user = current_user();
    if ($user === null) {
        flash('info', 'Accedi per entrare nell\'area riservata.');
        redirect('login.php');
    }
    return $user;
}
