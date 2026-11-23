<?php
/* Atelier Tang - config.php
   Database settings. Docker passes them as environment variables (see compose.yaml);
   XAMPP uses the defaults below. To override them, for example on the university
   server, create includes/config.local.php (ignored by Git) returning an array:

   <?php return ['db_name' => 'mio_database', 'db_user' => 'mio_utente', 'db_password' => '...'];
*/

declare(strict_types=1);

if (!defined('APP_ROOT')) {
    http_response_code(404);
    exit;
}

$settings = [
    'db_host' => getenv('DB_HOST') ?: 'localhost',
    'db_port' => (int) (getenv('DB_PORT') ?: 3306),
    'db_name' => getenv('DB_NAME') ?: 'atelier_tang',
    'db_user' => getenv('DB_USER') ?: 'root',
    'db_password' => getenv('DB_PASSWORD') !== false ? (string) getenv('DB_PASSWORD') : '',
];

$localConfig = __DIR__ . '/config.local.php';
if (is_file($localConfig)) {
    $overrides = require $localConfig;
    if (is_array($overrides)) {
        $settings = array_merge($settings, $overrides);
    }
}

return $settings;
