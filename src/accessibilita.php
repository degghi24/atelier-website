<?php
/* Atelier Tang - accessibility statement, including the list of access keys. */

declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

$site = site_info();

/* Access keys used in header.php and footer.php. */
$accessKeys = [
    '1' => 'Pagina iniziale',
    '2' => 'Vai al contenuto principale',
    '3' => 'Servizi',
    '4' => 'Cerca nel sito',
    '5' => 'Prenota una consulenza',
    '6' => 'Area riservata',
    '8' => 'Informativa sulla privacy',
    '9' => 'Contatti',
    '0' => 'Dichiarazione di accessibilità',
];

$page = page_meta('accessibilita', [
    'nav' => '',
    'footer' => 'accessibilita',
    'breadcrumb' => [['label' => 'Dichiarazione di accessibilità']],
]);
require __DIR__ . '/includes/header.php';
?>
    <div class="section">
      <div class="container doc-layout">
        <nav class="toc" aria-labelledby="toc-title">
          <h2 id="toc-title" class="toc-title">In questa pagina</h2>
          <ol>
            <li><a href="#riferimenti">Riferimenti</a></li>
            <li><a href="#conformita">Stato di conformità</a></li>
            <li><a href="#cura">Cosa abbiamo curato</a></li>
            <li><a href="#tasti-rapidi">Tasti di scelta rapida</a></li>
            <li><a href="#limiti">Contenuti non accessibili</a></li>
            <li><a href="#redazione">Redazione della dichiarazione</a></li>
            <li><a href="#segnalazioni">Segnala un problema</a></li>
          </ol>
        </nav>

        <div class="prose">
          <h2 id="riferimenti">Riferimenti</h2>
          <p>
            Il sito è stato progettato seguendo le Linee guida <abbr lang="en" title="Web Content Accessibility Guidelines">WCAG</abbr> 2.1
            livello <abbr title="doppia A">AA</abbr>, richiamate dalla norma europea <abbr title="Norma Europea">EN</abbr> 301 549,
            dalla Legge 9 gennaio 2004, <abbr title="numero">n.</abbr> 4 (Legge Stanca) e dalle Linee guida sull'accessibilità degli strumenti informatici di
            <abbr title="Agenzia per l'Italia Digitale">AgID</abbr>.
          </p>

          <h2 id="conformita">Stato di conformità</h2>
          <p>
            In base a un'autovalutazione, il sito è <strong>parzialmente conforme</strong> ai requisiti
            delle <abbr lang="en" title="Web Content Accessibility Guidelines">WCAG</abbr> 2.1 livello <abbr title="doppia A">AA</abbr>:
            i contenuti elencati nella sezione <a href="#limiti">Contenuti non accessibili</a> non sono ancora del tutto accessibili.
          </p>

          <h2 id="cura">Cosa abbiamo curato</h2>
          <ul>
            <li>Contrasto del testo di almeno 4,5:1, colori diversi per i link già visitati e indicatore del focus sempre visibile, anche sugli sfondi scuri.</li>
            <li>Caratteri senza grazie e interlinea ampia: i testi usano <span lang="en">Atkinson Hyperlegible Next</span>, progettato per chi ha difficoltà visive.</li>
            <li>Navigazione completa da tastiera, con collegamento per saltare al contenuto, percorso di navigazione, ricerca nel sito e tasti di scelta rapida.</li>
            <li>Struttura dei titoli, punti di riferimento e lingua delle parole straniere indicati nel codice.</li>
            <li>Moduli divisi in gruppi di campi, con etichette visibili, riepilogo degli errori e messaggi che spiegano come correggerli, validati anche dal server.</li>
            <li>Testo ridimensionabile fino al 200% e impaginazione leggibile fino a 320 pixel di larghezza.</li>
            <li>Il sito funziona anche senza JavaScript, senza fogli di stile e con i browser testuali, e ha una versione dedicata alla stampa.</li>
          </ul>

          <h2 id="tasti-rapidi">Tasti di scelta rapida</h2>
          <p>Le funzioni principali del sito si raggiungono con un tasto numerico. Il tasto della pagina in cui ti trovi non è attivo.</p>
          <table class="key-table">
            <caption>Tasti di scelta rapida del sito</caption>
            <thead>
              <tr>
                <th scope="col" id="col-tasto">Tasto</th>
                <th scope="col" id="col-funzione">Funzione</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <td colspan="2">
                  Premi il tasto insieme ad Alt in Chrome ed Edge su Windows, insieme ad Alt e Maiusc in Firefox,
                  insieme a <abbr title="Control">Ctrl</abbr> e Opzione su Mac.
                </td>
              </tr>
            </tfoot>
            <tbody>
<?php foreach ($accessKeys as $key => $function): ?>
              <tr>
                <th scope="row" id="tasto-<?= e((string) $key) ?>" headers="col-tasto"><?= e((string) $key) ?></th>
                <td headers="col-funzione tasto-<?= e((string) $key) ?>"><?= e($function) ?></td>
              </tr>
<?php endforeach; ?>
            </tbody>
          </table>

          <h2 id="limiti">Contenuti non accessibili</h2>
          <ul>
            <li>Il selettore della data usa il controllo nativo del browser, il cui supporto alle tecnologie assistive varia tra browser.</li>
            <li>Il campionario dei tessuti mostra riproduzioni grafiche delle trame al posto di fotografie.</li>
          </ul>

          <h2 id="redazione">Redazione della dichiarazione</h2>
          <p>
            Dichiarazione redatta il <time datetime="2026-11-23">23 novembre 2026</time> tramite autovalutazione:
            verifica manuale con tastiera e lettore di schermo, controllo del contrasto dei colori e prove a diverse larghezze e livelli di zoom.
          </p>

          <h2 id="segnalazioni">Segnala un problema</h2>
          <p>
            Se trovi un contenuto che non riesci a usare, scrivici a
            <a href="mailto:<?= e($site['email']) ?>"><?= e($site['email']) ?></a> o chiamaci allo
            <a href="tel:<?= e($site['phone_link']) ?>"><?= e($site['phone_label']) ?></a>: ti risponderemo entro 30 giorni e, nel frattempo,
            ti forniremo le informazioni in un formato alternativo.
          </p>
        </div>
      </div>
    </div>
<?php require __DIR__ . '/includes/footer.php'; ?>
