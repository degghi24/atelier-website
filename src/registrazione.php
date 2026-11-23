<?php
/* Atelier Tang - account registration. */

declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

if (current_user() !== null) {
    redirect('area.php');
}

$fields = ['username', 'full_name', 'email', 'password', 'password_confirm'];
$errors = [];
$old = [];
$flash = null;

if (is_post()) {
    $input = [];
    foreach ($fields as $field) {
        $input[$field] = post_string($field);
    }
    /* Passwords are never written back into the page. */
    $old = array_diff_key($input, ['password' => true, 'password_confirm' => true]);

    if (!csrf_valid()) {
        $flash = ['type' => 'errore', 'message' => 'La sessione è scaduta. Controlla i dati e invia di nuovo il modulo.'];
    } else {
        list($data, $errors) = validate_fields($fields, $input);
    }

    if ($flash === null && $errors === []) {
        try {
            $pdo = db();
            $statement = $pdo->prepare('SELECT username, email FROM utenti WHERE username = :username OR email = :email');
            $statement->execute([':username' => $data['username'], ':email' => $data['email']]);
            foreach ($statement->fetchAll() as $existing) {
                if (mb_strtolower($existing['username']) === mb_strtolower($data['username'])) {
                    $errors['username'] = 'Questo nome utente è già in uso: scegline un altro.';
                }
                if (mb_strtolower($existing['email']) === mb_strtolower($data['email'])) {
                    $errors['email'] = 'Esiste già un account con questa email. Prova ad accedere.';
                }
            }

            if ($errors === []) {
                $statement = $pdo->prepare(
                    'INSERT INTO utenti (username, nome, email, password_hash) VALUES (:username, :nome, :email, :password_hash)'
                );
                $statement->execute([
                    ':username' => $data['username'],
                    ':nome' => $data['full_name'],
                    ':email' => $data['email'],
                    ':password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
                ]);
                log_in([
                    'id' => $pdo->lastInsertId(),
                    'username' => $data['username'],
                    'nome' => $data['full_name'],
                    'email' => $data['email'],
                    'ruolo' => 'user',
                ]);
                flash('successo', 'Ciao ' . $data['full_name'] . ', il tuo account è pronto.');
                redirect('area.php');
            }
        } catch (PDOException $exception) {
            error_log('Atelier Tang - registration failed: ' . $exception->getMessage());
            $flash = ['type' => 'errore', 'message' => 'Non è stato possibile creare l\'account per un problema tecnico. Riprova più tardi.'];
        }
    }
}

$page = [
    'id' => 'registrazione',
    'nav' => 'area',
    'title' => 'Crea un account',
    'heading' => 'Crea un account',
    'lead' => 'Con un account puoi seguire le richieste di appuntamento inviate dopo l\'accesso.',
    'description' => 'Registrati all\'area riservata di Atelier Tang per seguire le tue richieste di appuntamento.',
    'keywords' => 'registrazione, crea account, area riservata, sartoria Padova',
    'breadcrumb' => [['label' => 'Area riservata', 'href' => 'login.php'], ['label' => 'Crea un account']],
    'noindex' => true,
];
require __DIR__ . '/includes/header.php';
?>
    <div class="section">
      <div class="container auth-layout">
        <div class="auth-main">
        <p>Tutti i campi sono obbligatori.</p>
        <?= render_flash($flash, 'esito', $errors === []) ?>

        <form class="data-form" action="registrazione.php" method="post" data-validate="true" data-summary-heading="h2">
          <?= error_summary($errors) ?>
          <?= csrf_field() ?>

          <fieldset>
          <legend>Dati dell'account</legend>

          <p class="field">
            <label for="username">Nome utente</label>
            <input type="text" id="username" name="username" required="required" minlength="3" maxlength="30"
                   pattern="<?= e(USERNAME_PATTERN) ?>" autocomplete="username" autocapitalize="none" spellcheck="false"
                   value="<?= old_value($old, 'username') ?>"
                   aria-describedby="hint-username error-username"<?= invalid_attr($errors, 'username') ?><?= msg_attrs('username') ?> />
            <span id="hint-username" class="hint">Da 3 a 30 caratteri: lettere senza accenti, numeri, punto, trattino o trattino basso.</span>
            <?= error_span($errors, 'username') ?>
          </p>

          <p class="field">
            <label for="full-name">Nome e cognome</label>
            <input type="text" id="full-name" name="full_name" required="required" minlength="2" maxlength="80"
                   pattern="<?= e(NAME_PATTERN) ?>" autocomplete="name" value="<?= old_value($old, 'full_name') ?>"
                   aria-describedby="error-full-name"<?= invalid_attr($errors, 'full_name') ?><?= msg_attrs('full_name') ?> />
            <?= error_span($errors, 'full_name') ?>
          </p>

          <p class="field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required="required" maxlength="120"
                   autocomplete="email" value="<?= old_value($old, 'email') ?>"
                   aria-describedby="error-email"<?= invalid_attr($errors, 'email') ?><?= msg_attrs('email') ?> />
            <?= error_span($errors, 'email') ?>
          </p>
          </fieldset>

          <fieldset>
          <legend>Scegli la password</legend>

          <p class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required="required" minlength="8" maxlength="72"
                   autocomplete="new-password"
                   aria-describedby="hint-password error-password"<?= invalid_attr($errors, 'password') ?><?= msg_attrs('password') ?> />
            <span id="hint-password" class="hint">Almeno 8 caratteri. Puoi incollarla da un gestore di password.</span>
            <?= error_span($errors, 'password') ?>
          </p>

          <p class="field">
            <label for="password-confirm">Ripeti la password</label>
            <input type="password" id="password-confirm" name="password_confirm" required="required" maxlength="72"
                   autocomplete="new-password" data-match="password"
                   aria-describedby="error-password-confirm"<?= invalid_attr($errors, 'password_confirm') ?><?= msg_attrs('password_confirm') ?> />
            <?= error_span($errors, 'password_confirm') ?>
          </p>
          </fieldset>

          <p class="form-actions">
            <button type="submit" class="btn btn-primary">Crea l'account</button>
          </p>
        </form>
        </div>

        <aside class="auth-aside" aria-labelledby="aside-title">
          <h2 id="aside-title" class="aside-title">Perché creare un account</h2>
          <ul class="benefit-list">
            <li>Le richieste inviate dopo l'accesso restano sempre consultabili</li>
            <li>Puoi cambiare data, servizio e messaggio finché la richiesta è in attesa</li>
            <li>Nome ed email vengono già compilati nel modulo di prenotazione</li>
          </ul>
          <p>Hai già un account? <a href="login.php">Accedi</a>.</p>
        </aside>
      </div>
    </div>
<?php require __DIR__ . '/includes/footer.php'; ?>
