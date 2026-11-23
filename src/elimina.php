<?php
/* Atelier Tang - delete a booking request after an explicit confirmation
   (WCAG 2.1 success criterion 3.3.4, error prevention). */

declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

$user = require_login();
$request = find_manageable_request(request_id(), $user);
$id = (int) $request['id'];
$serviceName = (string) $request['servizio_nome'];
$dateLabel = format_date_it((string) $request['data_preferita']);

if (is_post()) {
    if (!csrf_valid()) {
        flash('errore', 'La sessione è scaduta. Ripeti l\'operazione.');
        redirect('area.php');
    }
    try {
        $statement = db()->prepare('DELETE FROM prenotazioni WHERE id = :id');
        $statement->execute([':id' => $id]);
        flash('successo', 'Hai eliminato la richiesta "' . $serviceName . '" del ' . $dateLabel . '.');
    } catch (PDOException $exception) {
        error_log('Atelier Tang - request not deleted: ' . $exception->getMessage());
        flash('errore', 'Non è stato possibile eliminare la richiesta per un problema tecnico. Riprova più tardi.');
    }
    redirect('area.php');
}

$page = [
    'id' => 'elimina',
    'nav' => 'area',
    'title' => 'Elimina la richiesta',
    'heading' => 'Vuoi eliminare questa richiesta?',
    'description' => 'Conferma l\'eliminazione di una richiesta di appuntamento.',
    'keywords' => 'elimina prenotazione, area riservata, sartoria',
    'breadcrumb' => [['label' => 'Area riservata', 'href' => 'area.php'], ['label' => 'Elimina la richiesta']],
    'noindex' => true,
];
require __DIR__ . '/includes/header.php';
?>
    <div class="section">
      <div class="container text-column">
        <dl class="request-details request-summary">
          <dt>Servizio</dt>
          <dd><?= e($serviceName) ?></dd>
          <dt>Data preferita</dt>
          <dd><time datetime="<?= e((string) $request['data_preferita']) ?>"><?= e($dateLabel) ?></time></dd>
          <dt>Nome</dt>
          <dd><?= e((string) $request['nome']) ?></dd>
        </dl>
        <p><strong>L'eliminazione è definitiva</strong>: la richiesta non potrà essere recuperata.</p>

        <form class="action-form form-actions" action="elimina.php" method="post">
          <?= csrf_field() ?>
          <input type="hidden" name="id" value="<?= e((string) $id) ?>" />
          <button type="submit" class="btn btn-primary">Sì, elimina la richiesta</button>
          <a class="btn btn-secondary" href="area.php">No, torna all'area riservata</a>
        </form>
      </div>
    </div>
<?php require __DIR__ . '/includes/footer.php'; ?>
