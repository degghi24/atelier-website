<?php
/* Atelier Tang - reserved area.
   Users see their own requests; administrators see every request, can filter
   them by status and update the status. */

declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

$user = require_login();
$isAdmin = is_admin();
$statuses = booking_statuses();

/* ---------- Status update (administrators only) ---------- */
if (is_post()) {
    $filterAfterPost = post_string('filtro');
    $back = 'area.php' . (array_key_exists($filterAfterPost, $statuses) ? '?stato=' . rawurlencode($filterAfterPost) : '');

    if (!$isAdmin) {
        redirect('area.php');
    }
    if (!csrf_valid()) {
        flash('errore', 'La sessione è scaduta. Ripeti l\'operazione.');
        redirect($back);
    }

    $id = request_id();
    $status = post_string('stato');
    if ($id === 0 || !array_key_exists($status, $statuses)) {
        flash('errore', 'Richiesta non valida. Ripeti l\'operazione.');
        redirect($back);
    }

    try {
        $statement = db()->prepare('UPDATE prenotazioni SET stato = :stato WHERE id = :id');
        $statement->execute([':stato' => $status, ':id' => $id]);
        if ($statement->rowCount() === 0) {
            flash('info', 'Nessuna modifica: la richiesta aveva già questo stato oppure non esiste più.');
        } else {
            flash('successo', 'Stato aggiornato: la richiesta ora è "' . $statuses[$status] . '".');
        }
    } catch (PDOException $exception) {
        error_log('Atelier Tang - status not updated: ' . $exception->getMessage());
        flash('errore', 'Non è stato possibile aggiornare lo stato per un problema tecnico. Riprova più tardi.');
    }
    redirect($back);
}

/* ---------- Request list ---------- */
$filter = isset($_GET['stato']) && is_string($_GET['stato']) && array_key_exists($_GET['stato'], $statuses) ? $_GET['stato'] : '';
$flash = take_flash();
$requests = [];
$loadError = false;

try {
    if ($isAdmin) {
        $sql = 'SELECT p.*, s.nome AS servizio_nome, u.username
                FROM prenotazioni p
                JOIN servizi s ON s.codice = p.servizio
                LEFT JOIN utenti u ON u.id = p.utente_id';
        $params = [];
        if ($filter !== '') {
            $sql .= ' WHERE p.stato = :stato';
            $params[':stato'] = $filter;
        }
        $sql .= ' ORDER BY p.data_preferita ASC, p.id ASC';
    } else {
        $sql = 'SELECT p.*, s.nome AS servizio_nome
                FROM prenotazioni p
                JOIN servizi s ON s.codice = p.servizio
                WHERE p.utente_id = :utente
                ORDER BY p.data_preferita ASC, p.id ASC';
        $params = [':utente' => $user['id']];
    }
    $statement = db()->prepare($sql);
    $statement->execute($params);
    $requests = $statement->fetchAll();
} catch (PDOException $exception) {
    error_log('Atelier Tang - requests not loaded: ' . $exception->getMessage());
    $loadError = true;
}

$count = count($requests);

$page = [
    'id' => 'area',
    'nav' => 'area',
    'title' => 'Area riservata',
    'heading' => 'Area riservata',
    'lead' => $isAdmin
        ? 'Gestisci le richieste di appuntamento: filtrale per stato, aggiornale o eliminale.'
        : 'Qui trovi le richieste di appuntamento inviate dopo l\'accesso e il loro stato.',
    'description' => 'Consulta, modifica o elimina le tue richieste di appuntamento con Atelier Tang.',
    'keywords' => 'area riservata, richieste, prenotazioni sartoria',
    'breadcrumb' => [['label' => 'Area riservata']],
    'noindex' => true,
];
require __DIR__ . '/includes/header.php';
?>
    <div class="section">
      <div class="container">
        <?= render_flash($flash) ?>

        <div class="account-info">
          <p>
            Hai effettuato l'accesso come <strong><?= e($user['username']) ?></strong><?= $isAdmin ? ', con permessi di amministrazione' : '' ?>.
          </p>
          <form class="action-form" action="logout.php" method="post">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-secondary">Esci</button>
          </form>
        </div>

        <h2 id="requests-title" class="section-title"><?= $isAdmin ? 'Tutte le richieste' : 'Le tue richieste' ?></h2>

<?php if ($isAdmin): ?>
        <form class="filter-form" action="area.php" method="get">
          <fieldset>
          <legend>Filtra le richieste per stato</legend>
          <p class="filter-field">
            <label for="filtro-stato">Mostra</label>
            <select id="filtro-stato" name="stato">
              <option value="">Tutte le richieste</option>
<?php foreach ($statuses as $key => $label): ?>
              <option value="<?= e($key) ?>"<?= $filter === $key ? ' selected="selected"' : '' ?>><?= e($label) ?></option>
<?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-secondary">Applica il filtro</button>
          </p>
          </fieldset>
        </form>
<?php else: ?>
        <p class="section-intro">Puoi modificare una richiesta finché è in attesa di conferma.</p>
<?php endif; ?>

<?php if ($loadError): ?>
        <p class="flash flash-errore">Non è stato possibile caricare le richieste per un problema tecnico. Riprova più tardi.</p>
<?php elseif ($count === 0): ?>
        <p>
<?php if ($isAdmin): ?>
          Nessuna richiesta<?= $filter !== '' ? ' con stato "' . e($statuses[$filter]) . '"' : '' ?>.
<?php else: ?>
          Non hai ancora inviato richieste. <a href="index.php#prenota">Prenota una consulenza</a>.
<?php endif; ?>
        </p>
<?php else: ?>
        <p class="results-count"><?= $count === 1 ? '1 richiesta' : e((string) $count) . ' richieste' ?></p>
        <ul class="request-list">
<?php foreach ($requests as $request): ?>
<?php
    $id = (int) $request['id'];
    $serviceName = (string) $request['servizio_nome'];
    $dateLabel = format_date_it((string) $request['data_preferita']);
    $context = $serviceName . ' del ' . $dateLabel;
    $canEdit = $isAdmin || $request['stato'] === 'in_attesa';
?>
          <li class="request">
            <h3><?= e($serviceName) ?></h3>
            <p class="status status-<?= e((string) $request['stato']) ?>">Stato: <?= e($statuses[$request['stato']] ?? (string) $request['stato']) ?></p>
            <dl class="request-details">
              <dt>Data preferita</dt>
              <dd><time datetime="<?= e((string) $request['data_preferita']) ?>"><?= e($dateLabel) ?></time></dd>
<?php if ($isAdmin): ?>
              <dt>Cliente</dt>
              <dd><?= e((string) $request['nome']) ?> <?= $request['username'] !== null ? '(account ' . e((string) $request['username']) . ')' : '(senza account)' ?></dd>
              <dt>Email</dt>
              <dd><a href="<?= e('mailto:' . $request['email']) ?>"><?= e((string) $request['email']) ?></a></dd>
<?php endif; ?>
              <dt>Telefono</dt>
              <dd><?= $request['telefono'] !== null ? e((string) $request['telefono']) : 'Non indicato' ?></dd>
              <dt>Messaggio</dt>
              <dd class="request-message"><?= nl2br(e((string) $request['messaggio'])) ?></dd>
              <dt>Inviata il</dt>
              <dd><time datetime="<?= e(substr((string) $request['creata_il'], 0, 10)) ?>"><?= e(format_date_it((string) $request['creata_il'])) ?></time></dd>
            </dl>
            <div class="request-actions">
<?php if ($isAdmin): ?>
              <form class="action-form status-form" action="area.php" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= e((string) $id) ?>" />
                <input type="hidden" name="filtro" value="<?= e($filter) ?>" />
                <fieldset>
                <legend class="sr-text">Stato della richiesta <?= e($context) ?></legend>
                <label for="stato-<?= e((string) $id) ?>">Cambia stato</label>
                <select id="stato-<?= e((string) $id) ?>" name="stato">
<?php foreach ($statuses as $key => $label): ?>
                  <option value="<?= e($key) ?>"<?= $request['stato'] === $key ? ' selected="selected"' : '' ?>><?= e($label) ?></option>
<?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-secondary">Aggiorna<span class="sr-text"> lo stato della richiesta <?= e($context) ?></span></button>
                </fieldset>
              </form>
<?php endif; ?>
<?php if ($canEdit): ?>
              <a class="btn btn-secondary" href="modifica.php?id=<?= e((string) $id) ?>">Modifica<span class="sr-text"> la richiesta <?= e($context) ?></span></a>
<?php endif; ?>
              <a class="btn btn-secondary" href="elimina.php?id=<?= e((string) $id) ?>">Elimina<span class="sr-text"> la richiesta <?= e($context) ?></span></a>
            </div>
<?php if (!$canEdit): ?>
            <p class="hint">Per cambiare una richiesta già <?= $request['stato'] === 'confermata' ? 'confermata' : 'annullata' ?>, <a href="contatti.php">contattaci</a>.</p>
<?php endif; ?>
          </li>
<?php endforeach; ?>
        </ul>
<?php endif; ?>
      </div>
    </div>
<?php require __DIR__ . '/includes/footer.php'; ?>
