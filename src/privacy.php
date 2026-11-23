<?php
/* Atelier Tang - privacy notice (art. 13 GDPR). */

declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

$site = site_info();

$page = page_meta('privacy', [
    'nav' => '',
    'footer' => 'privacy',
    'breadcrumb' => [['label' => 'Informativa sulla privacy']],
]);
require __DIR__ . '/includes/header.php';
?>
    <div class="section">
      <div class="container doc-layout">
        <nav class="toc" aria-labelledby="toc-title">
          <h2 id="toc-title" class="toc-title">In questa pagina</h2>
          <ol>
            <li><a href="#titolare">Titolare del trattamento</a></li>
            <li><a href="#dati">Quali dati raccogliamo</a></li>
            <li><a href="#finalita">Perché li usiamo</a></li>
            <li><a href="#conservazione">Per quanto tempo</a></li>
            <li><a href="#cookie">Cookie</a></li>
            <li><a href="#diritti">I tuoi diritti</a></li>
          </ol>
        </nav>

        <div class="prose">
          <p>
            Informativa resa ai sensi dell'articolo 13 del Regolamento (<abbr title="Unione Europea">UE</abbr>) 2016/679
            (<abbr lang="en" title="General Data Protection Regulation">GDPR</abbr>).
            I dati del titolare sono di esempio, perché il sito è un progetto didattico.
          </p>

          <h2 id="titolare">Titolare del trattamento</h2>
          <p><?= e($site['name']) ?>, <?= e($site['street']) ?>, <?= e($site['city']) ?>, email <a href="mailto:<?= e($site['email']) ?>"><?= e($site['email']) ?></a>.</p>

          <h2 id="dati">Quali dati raccogliamo</h2>
          <ul>
            <li>Con il modulo di prenotazione: nome e cognome, email, telefono (facoltativo), servizio, data preferita e messaggio.</li>
            <li>Con la registrazione all'area riservata: nome utente, nome, email e password, salvata solo in forma cifrata.</li>
          </ul>

          <h2 id="finalita">Perché li usiamo</h2>
          <p>
            Usiamo i dati solo per rispondere alla richiesta di appuntamento e per permetterti di consultarla,
            modificarla o eliminarla dall'area riservata. La base giuridica è l'esecuzione di misure precontrattuali
            richieste da te e il tuo consenso. Non inviamo pubblicità e non cediamo i dati a terzi.
          </p>

          <h2 id="conservazione">Per quanto tempo</h2>
          <p>Conserviamo le richieste per 24 mesi dall'ultimo contatto. Puoi eliminarle prima in qualsiasi momento dall'area riservata.</p>

          <h2 id="cookie">Cookie</h2>
          <p>
            Il sito usa un solo cookie tecnico di sessione, necessario per il modulo e per l'accesso all'area riservata.
            Viene cancellato alla chiusura del browser e non richiede consenso. Non usiamo cookie di profilazione né strumenti di analisi,
            e i caratteri tipografici sono ospitati sul nostro server, senza richieste a servizi esterni.
          </p>

          <h2 id="diritti">I tuoi diritti</h2>
          <p>
            Puoi chiedere in ogni momento di accedere ai tuoi dati, correggerli, cancellarli, limitarne il trattamento
            o riceverli in un formato leggibile, scrivendo all'indirizzo email del titolare. Puoi anche proporre reclamo al
            Garante per la protezione dei dati personali.
          </p>
        </div>
      </div>
    </div>
<?php require __DIR__ . '/includes/footer.php'; ?>
