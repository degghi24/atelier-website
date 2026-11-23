<?php
/* Atelier Tang - services and frequently asked questions. */

declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

$page = page_meta('servizi', ['nav' => 'servizi', 'breadcrumb' => [['label' => 'Servizi']]]);
require __DIR__ . '/includes/header.php';
?>
    <section id="cosa-realizziamo" class="section" aria-labelledby="services-title">
      <div class="container">
        <h2 id="services-title" class="section-title">Cosa realizziamo</h2>
        <p class="section-intro">I tempi sono indicativi e partono dalla consulenza: li confermiamo insieme in base al modello e al tessuto.</p>
        <ul class="service-list">
<?php foreach (services() as $key => $service): ?>
          <li id="servizio-<?= e($key) ?>" class="service">
            <h3><?= e($service['name']) ?></h3>
            <p><?= e($service['description']) ?></p>
            <dl class="service-details">
<?php foreach ($service['details'] as $label => $value): ?>
              <div>
                <dt><?= e($label) ?></dt>
                <dd><?= e($value) ?></dd>
              </div>
<?php endforeach; ?>
            </dl>
          </li>
<?php endforeach; ?>
        </ul>
      </div>
    </section>

    <section id="domande-frequenti" class="section section-secondary" aria-labelledby="faq-title">
      <div class="container text-column">
        <h2 id="faq-title" class="section-title">Domande frequenti</h2>
<?php foreach (faqs() as $index => $faq): ?>
        <details id="domanda-<?= e((string) ($index + 1)) ?>">
          <summary><?= e($faq['question']) ?></summary>
          <p><?= e($faq['answer']) ?></p>
        </details>
<?php endforeach; ?>
        <p class="faq-more">Non trovi la risposta? <a href="contatti.php">Contattaci</a> o chiedi durante la consulenza.</p>
      </div>
    </section>
<?php require __DIR__ . '/includes/cta.php'; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
