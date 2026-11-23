<?php
/* Atelier Tang - logout. Accepts only POST requests with a valid CSRF token. */

declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

if (!is_post() || !csrf_valid()) {
    redirect(current_user() !== null ? 'area.php' : 'login.php');
}

/* Clear the session data and renew its id; a new CSRF token is created on the next page. */
$_SESSION = [];
session_regenerate_id(true);
flash('successo', 'Sei uscito dall\'area riservata.');
redirect('login.php');
