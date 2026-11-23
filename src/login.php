<?php
/* Atelier Tang - login to the reserved area. */

declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

if (current_user() !== null) {
    redirect('area.php');
}

$errors = [];
$old = [];
$flash = null;

if (is_post()) {
    $old['username'] = trim(post_string('username'));
    $password = post_string('password');
    $messages = field_messages();

    if (!csrf_valid()) {
        $flash = ['type' => 'errore', 'message' => 'La sessione è scaduta. Inserisci di nuovo le credenziali.'];
    } else {
        if ($old['username'] === '') {
            $errors['username'] = $messages['username']['required'];
        }
        if ($password === '') {
            $errors['password'] = $messages['password']['required'];
        }
    }

    if ($flash === null && $errors === []) {
        try {
            $statement = db()->prepare('SELECT id, username, nome, email, password_hash, ruolo FROM utenti WHERE username = :username');
            $statement->execute([':username' => $old['username']]);
            $account = $statement->fetch();

            if ($account !== false && password_verify($password, $account['password_hash'])) {
                log_in($account);
                flash('successo', 'Ciao ' . $account['nome'] . ', hai effettuato l\'accesso.');
                redirect('area.php');
            }
            /* The same message for a wrong username or password does not reveal which accounts exist. */
            $errors['username'] = 'Nome utente o password non corretti: controlla di averli scritti bene, facendo attenzione a maiuscole e minuscole.';
        } catch (PDOException $exception) {
            error_log('Atelier Tang - login failed: ' . $exception->getMessage());
            $flash = ['type' => 'errore', 'message' => 'L\'accesso non è disponibile per un problema tecnico. Riprova più tardi.'];
        }
    }
} else {
    $flash = take_flash();
}

$page = [
    'id' => 'login',
    'nav' => 'area',
    'title' => 'Accedi all\'area riservata',
    'heading' => 'Accedi all\'area riservata',
    'lead' => 'Consulta lo stato delle tue richieste di appuntamento, modificale o eliminale.',
    'description' => 'Accedi all\'area riservata di Atelier Tang per consultare, modificare o eliminare le tue richieste di appuntamento.',
    'keywords' => 'area riservata, accesso, login, prenotazioni sartoria',
    'breadcrumb' => [['label' => 'Area riservata']],
    'noindex' => true,
];
require __DIR__ . '/includes/header.php';
?>
    <div class="section">
      <div class="container auth-layout">
        <div class="auth-main">
        <?= render_flash($flash, 'esito', $errors === []) ?>

        <form class="data-form" action="login.php" method="post" data-validate="true" data-summary-heading="h2">
          <?= error_summary($errors) ?>
          <?= csrf_field() ?>

          <fieldset>
          <legend>Credenziali di accesso</legend>

          <p class="field">
            <label for="username">Nome utente</label>
            <input type="text" id="username" name="username" required="required" maxlength="30"
                   autocomplete="username" autocapitalize="none" spellcheck="false" value="<?= old_value($old, 'username') ?>"
                   aria-describedby="error-username"<?= invalid_attr($errors, 'username') ?> data-msg-required="<?= e(field_messages()['username']['required']) ?>" />
            <?= error_span($errors, 'username') ?>
          </p>

          <p class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required="required" maxlength="72"
                   autocomplete="current-password"
                   aria-describedby="error-password"<?= invalid_attr($errors, 'password') ?> data-msg-required="<?= e(field_messages()['password']['required']) ?>" />
            <?= error_span($errors, 'password') ?>
          </p>
          </fieldset>

          <p class="form-actions">
            <button type="submit" class="btn btn-primary">Accedi</button>
          </p>
        </form>
        </div>

        <aside class="auth-aside" aria-labelledby="aside-title">
          <h2 id="aside-title" class="aside-title">Cosa puoi fare dall'area riservata</h2>
          <ul class="benefit-list">
            <li>Vedere lo stato di ogni richiesta di appuntamento</li>
            <li>Modificare data, servizio e messaggio finché la richiesta è in attesa</li>
            <li>Eliminare una richiesta quando non ti serve più</li>
          </ul>
          <p>Non hai ancora un account? <a href="registrazione.php">Registrati</a>: bastano pochi dati.</p>
        </aside>
      </div>
    </div>
<?php require __DIR__ . '/includes/footer.php'; ?>
