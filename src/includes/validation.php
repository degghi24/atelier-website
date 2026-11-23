<?php
/* Atelier Tang - validation.php
   Server-side validation rules and form rendering helpers. The same messages are
   written into data-* attributes and reused by main.js, so client-side and
   server-side feedback always match. */

declare(strict_types=1);

if (!defined('APP_ROOT')) {
    http_response_code(404);
    exit;
}

/* Patterns shared by the HTML pattern attribute, main.js and PHP. */
const NAME_PATTERN = '[\p{L}\p{M}\'’ .\-]+';
const PHONE_PATTERN = '[0-9+ ]{6,20}';
const USERNAME_PATTERN = '[A-Za-z0-9._\-]{3,30}';

/* Every message says what is wrong and, after the colon, how to fix it. */
function field_messages(): array
{
    return [
        'full_name' => [
            'required' => 'Manca il nome: scrivi nome e cognome.',
            'invalid' => 'Il nome non è valido: usa da 2 a 80 caratteri tra lettere, spazi, apostrofi e trattini.',
        ],
        'email' => [
            'required' => 'Manca l\'indirizzo email: scrivi l\'email a cui vuoi ricevere la risposta.',
            'invalid' => 'L\'indirizzo email non è valido: controlla che sia scritto come nome@dominio.it.',
        ],
        'phone' => [
            'invalid' => 'Il numero di telefono non è valido: usa da 6 a 20 caratteri tra numeri, spazi e il segno +.',
        ],
        'service' => [
            'required' => 'Manca il servizio: scegline uno dall\'elenco.',
            'invalid' => 'Il servizio scelto non esiste: scegline uno dall\'elenco.',
        ],
        'preferred_date' => [
            'required' => 'Manca la data: scegli il giorno che preferisci per l\'appuntamento.',
            'invalid' => 'La data non è valida: scegli un giorno compreso tra oggi e i prossimi 12 mesi.',
        ],
        'message' => [
            'required' => 'Manca il messaggio: descrivi in breve il capo che desideri.',
            'invalid' => 'Il messaggio è troppo corto o troppo lungo: scrivi da 10 a 1000 caratteri.',
        ],
        'privacy_consent' => [
            'required' => 'Manca il consenso al trattamento dei dati: seleziona la casella per inviare la richiesta.',
        ],
        'username' => [
            'required' => 'Manca il nome utente: scrivi il nome utente.',
            'invalid' => 'Il nome utente non è valido: usa da 3 a 30 caratteri tra lettere senza accenti, numeri, punto, trattino e trattino basso.',
        ],
        'password' => [
            'required' => 'Manca la password: scrivi la password.',
            'invalid' => 'La password non è valida: usa da 8 a 72 caratteri.',
        ],
        'password_confirm' => [
            'required' => 'Manca la conferma della password: scrivi di nuovo la stessa password.',
            'invalid' => 'Le due password sono diverse: scrivi la stessa password in entrambi i campi.',
        ],
    ];
}

/* Earliest and latest dates accepted for an appointment (Y-m-d). */
function booking_min_date(): string
{
    return (new DateTimeImmutable('today'))->format('Y-m-d');
}

function booking_max_date(): string
{
    return (new DateTimeImmutable('today'))->modify('+1 year')->format('Y-m-d');
}

/* Validates the listed fields. Returns [cleanValues, errors], where errors maps
   each invalid field name to the message to show. */
function validate_fields(array $names, array $input): array
{
    $clean = [];
    $errors = [];
    $messages = field_messages();

    foreach ($names as $name) {
        $raw = isset($input[$name]) && is_string($input[$name]) ? $input[$name] : '';
        $raw = str_replace(["\r\n", "\r"], "\n", $raw);
        $value = in_array($name, ['password', 'password_confirm'], true) ? $raw : trim($raw);

        $problem = check_field($name, $value, $input);
        if ($problem === null) {
            $clean[$name] = $value;
        } else {
            $errors[$name] = $messages[$name][$problem];
        }
    }

    return [$clean, $errors];
}

/* Returns "required", "invalid" or null when the value is acceptable. */
function check_field(string $name, string $value, array $input): ?string
{
    $length = mb_strlen($value);

    switch ($name) {
        case 'full_name':
            if ($value === '') {
                return 'required';
            }
            return ($length < 2 || $length > 80 || !preg_match('/^' . NAME_PATTERN . '$/u', $value)) ? 'invalid' : null;

        case 'email':
            if ($value === '') {
                return 'required';
            }
            return ($length > 120 || filter_var($value, FILTER_VALIDATE_EMAIL) === false) ? 'invalid' : null;

        case 'phone':
            if ($value === '') {
                return null;
            }
            return preg_match('/^' . PHONE_PATTERN . '$/', $value) ? null : 'invalid';

        case 'service':
            if ($value === '') {
                return 'required';
            }
            return array_key_exists($value, services()) ? null : 'invalid';

        case 'preferred_date':
            if ($value === '') {
                return 'required';
            }
            $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value);
            if ($date === false || $date->format('Y-m-d') !== $value) {
                return 'invalid';
            }
            return ($value < booking_min_date() || $value > booking_max_date()) ? 'invalid' : null;

        case 'message':
            if ($value === '') {
                return 'required';
            }
            return ($length < 10 || $length > 1000) ? 'invalid' : null;

        case 'privacy_consent':
            return $value === '1' ? null : 'required';

        case 'username':
            if ($value === '') {
                return 'required';
            }
            return preg_match('/^' . USERNAME_PATTERN . '$/', $value) ? null : 'invalid';

        case 'password':
            if ($value === '') {
                return 'required';
            }
            return ($length < 8 || strlen($value) > 72) ? 'invalid' : null;

        case 'password_confirm':
            if ($value === '') {
                return 'required';
            }
            $password = isset($input['password']) && is_string($input['password']) ? $input['password'] : '';
            return hash_equals($password, $value) ? null : 'invalid';
    }

    return null;
}

/* ---------- Form rendering helpers ---------- */

/* Field names use underscores, element ids use hyphens: full_name -> full-name. */
function field_id(string $name): string
{
    return str_replace('_', '-', $name);
}

function old_value(array $oldValues, string $name): string
{
    $value = $oldValues[$name] ?? '';
    return e(is_string($value) ? $value : '');
}

function invalid_attr(array $errors, string $name): string
{
    return isset($errors[$name]) ? ' aria-invalid="true"' : '';
}

/* Writes the validation messages into data attributes read by main.js. */
function msg_attrs(string $name): string
{
    $messages = field_messages()[$name] ?? [];
    $html = '';
    if (isset($messages['required'])) {
        $html .= ' data-msg-required="' . e($messages['required']) . '"';
    }
    if (isset($messages['invalid'])) {
        $html .= ' data-msg-invalid="' . e($messages['invalid']) . '"';
    }
    return $html;
}

/* Inline error linked to its field through aria-describedby. */
function error_span(array $errors, string $name): string
{
    $content = '';
    if (isset($errors[$name])) {
        $content = '<span class="sr-text">Errore: </span>' . e($errors[$name]);
    }
    return '<span id="error-' . e(field_id($name)) . '" class="error">' . $content . '</span>';
}

/* Error summary placed at the top of a form after a failed submission. Each item
   links to the invalid field. Nothing is printed when there are no errors: main.js
   creates the same box when it finds errors before the form is sent. */
function error_summary(array $errors, string $headingTag = 'h2', bool $focus = true): string
{
    $count = count($errors);
    if ($count === 0) {
        return '';
    }
    $title = $count === 1 ? 'Il modulo contiene 1 errore' : 'Il modulo contiene ' . $count . ' errori';

    $html = '<div class="error-summary" id="riepilogo-errori" tabindex="-1"' . ($focus ? ' data-focus-on-load="true"' : '');
    $html .= '><' . $headingTag . ' class="error-summary-title">' . e($title) . '</' . $headingTag . '><ul>';
    foreach ($errors as $name => $message) {
        $html .= '<li><a href="#' . e(field_id((string) $name)) . '">' . e((string) $message) . '</a></li>';
    }
    return $html . '</ul></div>';
}
