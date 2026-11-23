<?php
/* Atelier Tang - the atelier and the working method. */

declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

$page = page_meta('atelier', ['nav' => 'atelier', 'breadcrumb' => [['label' => 'L\'atelier']]]);
require __DIR__ . '/includes/header.php';
?>
    <section id="laboratorio" class="section" aria-labelledby="lab-title">
      <div class="container lab-layout">
        <div>
          <h2 id="lab-title" class="section-title">Il laboratorio</h2>
          <p>Selezioniamo lane, lini, sete e cotoni da tessitori italiani ed europei. Il modello viene disegnato sulle tue misure, imbastito e provato più volte, fino a quando veste come deve.</p>
          <p>Ogni capo è seguito dalla stessa persona dall'inizio alla fine: chi prende le misure taglia anche il tessuto e ti guida nelle prove.</p>
          <ul class="values">
            <li>Taglio e cucitura eseguiti a mano</li>
            <li>Tessuti di qualità, anche a campione</li>
            <li>Modifiche e riparazioni su capi di ogni provenienza</li>
            <li>Laboratorio al piano terra, senza gradini all'ingresso</li>
          </ul>
        </div>
        <figure class="swatch-figure">
          <span class="swatch swatch-lino" aria-hidden="true"></span>
          <figcaption>Una trama di lino naturale, tra i tessuti disponibili in campionatura.</figcaption>
        </figure>
      </div>
    </section>

    <section id="come-lavoriamo" class="section section-secondary" aria-labelledby="process-title">
      <div class="container">
        <h2 id="process-title" class="section-title">Come lavoriamo</h2>
        <p class="section-intro">Le cinque fasi di un capo su misura. Per un abito servono in media da quattro a sei settimane.</p>
        <ol class="steps">
<?php foreach (atelier_steps() as $index => $step): ?>
          <li id="fase-<?= e((string) ($index + 1)) ?>">
            <h3><?= e($step['title']) ?></h3>
            <p><?= e($step['text']) ?></p>
            <p class="step-time"><?= e($step['time']) ?></p>
          </li>
<?php endforeach; ?>
        </ol>
      </div>
    </section>
<?php require __DIR__ . '/includes/cta.php'; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
