<?php
/* Atelier Tang - site search results. The form is in the shared header (header.php). */

declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

$query = isset($_GET['q']) && is_string($_GET['q']) ? trim($_GET['q']) : '';
$query = mb_substr(preg_replace('/\s+/u', ' ', $query) ?? '', 0, 100);
$terms = $query === '' ? [] : search_terms($query);
$results = $terms === [] ? [] : search_site($terms);
$count = count($results);

if ($query === '') {
    $lead = 'Scrivi una o più parole nel campo «Cerca nel sito» e premi il pulsante Cerca: troverai servizi, tessuti, orari e le altre informazioni del sito.';
} elseif ($terms === []) {
    $lead = 'La ricerca «' . $query . '» non contiene parole utili: scrivi almeno una parola di due o più lettere, ad esempio «abito».';
} elseif ($count === 0) {
    $lead = 'Nessun risultato per «' . $query . '»: prova con un\'altra parola o con una parola più breve.';
} else {
    $lead = ($count === 1 ? '1 risultato' : $count . ' risultati') . ' per «' . $query . '».';
}

$page = [
    'id' => 'cerca',
    'nav' => '',
    'title' => $query === '' ? 'Cerca nel sito' : 'Risultati per «' . $query . '»',
    'heading' => 'Cerca nel sito',
    'lead' => $lead,
    'description' => 'Cerca servizi, tessuti, orari e informazioni nel sito di Atelier Tang.',
    'keywords' => 'ricerca, cerca, Atelier Tang',
    'breadcrumb' => [['label' => 'Ricerca']],
    'noindex' => true,
    'search_query' => $query,
];
require __DIR__ . '/includes/header.php';
?>
    <div class="section">
      <div class="container text-column">
<?php if ($count > 0): ?>
        <ol class="search-results">
<?php foreach ($results as $result): ?>
          <li class="search-result">
            <h2><a href="<?= e($result['url']) ?>"><?= highlight_terms($result['title'], $terms) ?></a></h2>
            <p class="result-page">Pagina: <?= e($result['page']) ?></p>
            <p><?= highlight_terms($result['text'], $terms) ?></p>
          </li>
<?php endforeach; ?>
        </ol>
<?php else: ?>
        <h2 class="section-title">Da dove iniziare</h2>
        <ul class="search-suggestions">
          <li><a href="servizi.php">Servizi sartoriali</a>: abiti, camicie, cerimonia e riparazioni, con tempi e prove</li>
          <li><a href="realizzazioni.php">Realizzazioni e tessuti</a>: lo smoking blu notte e il campionario</li>
          <li><a href="contatti.php">Contatti e orari</a>: indirizzo, telefono e orari di apertura</li>
          <li><a href="index.php#prenota">Prenota una consulenza</a>: il modulo per chiedere un appuntamento</li>
        </ul>
<?php endif; ?>
      </div>
    </div>
<?php require __DIR__ . '/includes/footer.php'; ?>
