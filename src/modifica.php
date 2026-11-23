<?php
/* Atelier Tang - edit a booking request from the reserved area. */

declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

$user = require_login();
$request = find_manageable_request(request_id(), $user);
$id = (int) $request['id'];

if (!is_admin() && $request['stato'] !== 'in_attesa') {
    flash('info', 'Puoi modificare solo le richieste in attesa di conferma. Per altri cambiamenti contattaci.');
    redirect('area.php');
}

$fields = ['service', 'preferred_date', 'phone', 'message'];
$errors = [];
$flash = null;
$old = [
    'service' => (string) $request['servizio'],
    'preferred_date' => (string) $request['data_preferita'],
    'phone' => (string) ($request['telefono'] ?? ''),
    'message' => (string) $request['messaggio'],
];

if (is_post()) {
    $old = [];
    foreach ($fields as $field) {
        $old[$field] = post_string($field);
    }

    if (!csrf_valid()) {
        $flash = ['type' => 'errore', 'message' => 'La sessione è scaduta. Controlla i dati e salva di nuovo.'];
    } else {
        list($data, $errors) = validate_fields($fields, $old);
        if ($errors === []) {
            try {
                $statement = db()->prepare(
                    'UPDATE prenotazioni
                     SET servizio = :servizio, data_preferita = :data_preferita, telefono = :telefono, messaggio = :messaggio
                     WHERE id = :id'
                );
                $statement->execute([
                    ':servizio' => $data['service'],
                    ':data_preferita' => $data['preferred_date'],
                    ':telefono' => $data['phone'] !== '' ? $data['phone'] : null,
                    ':messaggio' => $data['message'],
                    ':id' => $id,
                ]);
                flash('successo', 'Modifiche salvate: la richiesta ora è per ' . format_date_it($data['preferred_date']) . '.');
                redirect('area.php');
            } catch (PDOException $exception) {
                error_log('Atelier Tang - request not updated: ' . $exception->getMessage());
                $flash = ['type' => 'errore', 'message' => 'Non è stato possibile salvare le modifiche per un problema tecnico. Riprova più tardi.'];
            }
        }
    }
}

$page = [
    'id' => 'modifica',
    'nav' => 'area',
    'title' => 'Modifica la richiesta',
    'heading' => 'Modifica la richiesta',
    'description' => 'Modifica una richiesta di appuntamento inviata ad Atelier Tang.',
    'keywords' => 'modifica prenotazione, area riservata, sartoria',
    'breadcrumb' => [['label' => 'Area riservata', 'href' => 'area.php'], ['label' => 'Modifica la richiesta']],
    'noindex' => true,
];
require __DIR__ . '/includes/header.php';
?>
    <div class="section">
      <div class="container text-column">
        <p>
          Richiesta di <strong><?= e((string) $request['nome']) ?></strong>, inviata
          <time datetime="<?= e(substr((string) $request['creata_il'], 0, 10)) ?>"><?= e(format_date_it((string) $request['creata_il'])) ?></time>.
          I campi contrassegnati con l'asterisco (*) sono obbligatori.
        </p>
        <?= render_flash($flash, 'esito', $errors === []) ?>

        <form class="data-form" action="modifica.php" method="post" data-validate="true" data-summary-heading="h2">
          <?= error_summary($errors) ?>
          <?= csrf_field() ?>
          <input type="hidden" name="id" value="<?= e((string) $id) ?>" />

          <fieldset>
          <legend>Dati della richiesta</legend>

          <p class="field">
            <label for="service">Servizio *</label>
            <select id="service" name="service" required="required"
                    aria-describedby="error-service"<?= invalid_attr($errors, 'service') ?><?= msg_attrs('service') ?>>
              <option value="">Scegli un servizio</option>
<?php foreach (services() as $key => $service): ?>
              <option value="<?= e($key) ?>"<?= ($old['service'] ?? '') === $key ? ' selected="selected"' : '' ?>><?= e($service['name']) ?></option>
<?php endforeach; ?>
            </select>
            <?= error_span($errors, 'service') ?>
          </p>

          <p class="field">
            <label for="preferred-date">Data preferita *</label>
            <input type="date" id="preferred-date" name="preferred_date" required="required"
                   min="<?= e(booking_min_date()) ?>" max="<?= e(booking_max_date()) ?>"
                   value="<?= old_value($old, 'preferred_date') ?>"
                   aria-describedby="hint-preferred-date error-preferred-date"<?= invalid_attr($errors, 'preferred_date') ?><?= msg_attrs('preferred_date') ?> />
            <span id="hint-preferred-date" class="hint">Riceviamo dal martedì al sabato: ti confermeremo noi l'orario.</span>
            <?= error_span($errors, 'preferred_date') ?>
          </p>

          <p class="field">
            <label for="phone">Telefono</label>
            <input type="tel" id="phone" name="phone" maxlength="20" pattern="<?= e(PHONE_PATTERN) ?>"
                   autocomplete="tel" value="<?= old_value($old, 'phone') ?>"
                   aria-describedby="hint-phone error-phone"<?= invalid_attr($errors, 'phone') ?><?= msg_attrs('phone') ?> />
            <span id="hint-phone" class="hint">Facoltativo. Solo numeri, spazi e il segno +.</span>
            <?= error_span($errors, 'phone') ?>
          </p>

          <p class="field">
            <label for="message">Messaggio *</label>
            <textarea id="message" name="message" rows="5" cols="40" required="required"
                      minlength="10" maxlength="1000"
                      aria-describedby="hint-message error-message"<?= invalid_attr($errors, 'message') ?><?= msg_attrs('message') ?>><?= old_value($old, 'message') ?></textarea>
            <span id="hint-message" class="hint">Da 10 a 1000 caratteri.</span>
            <?= error_span($errors, 'message') ?>
          </p>
          </fieldset>

          <p class="form-actions">
            <button type="submit" class="btn btn-primary">Salva le modifiche</button>
            <a class="btn btn-secondary" href="area.php">Annulla</a>
          </p>
        </form>
      </div>
    </div>
<?php require __DIR__ . '/includes/footer.php'; ?>
