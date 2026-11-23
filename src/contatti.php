<?php
/* Atelier Tang - contacts, opening hours and directions. */

declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

$site = site_info();

/* Builds the id of a table row header from the day label, e.g. "giorno-lunedi". */
function day_id(string $day): string
{
    return 'giorno-' . trim((string) preg_replace('/[^a-z0-9]+/', '-', normalize_text($day)), '-');
}

$page = page_meta('contatti', ['nav' => 'contatti', 'breadcrumb' => [['label' => 'Contatti']]]);
require __DIR__ . '/includes/header.php';
?>
    <section class="section" aria-labelledby="contact-title">
      <div class="container contact-layout">
        <div id="recapiti" class="contact-card">
          <h2 id="contact-title" class="section-title">Recapiti</h2>
          <address>
            <ul class="contact-list">
              <li class="contact-address"><span><?= e($site['name']) ?><br /><?= e($site['street']) ?>, <?= e($site['city']) ?> (<abbr title="Padova">PD</abbr>)</span></li>
              <li class="contact-phone"><span><abbr title="Telefono">Tel.</abbr> <a href="tel:<?= e($site['phone_link']) ?>"><?= e($site['phone_label']) ?></a></span></li>
              <li class="contact-email"><span>Email <a href="mailto:<?= e($site['email']) ?>"><?= e($site['email']) ?></a></span></li>
            </ul>
          </address>
          <p class="hint">Indirizzo, recapiti e orari sono dati di esempio del progetto didattico.</p>
        </div>

        <div id="orari" class="contact-card">
          <table class="hours">
            <caption>Orari di apertura</caption>
            <thead>
              <tr>
                <th scope="col" id="col-giorno">Giorno</th>
                <th scope="col" id="col-orario">Orario</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <td colspan="2">Si riceve su appuntamento: prenota dal sito o telefonaci.</td>
              </tr>
            </tfoot>
            <tbody>
<?php foreach (opening_hours() as $day => $hours): ?>
              <tr>
                <th scope="row" id="<?= e(day_id($day)) ?>" headers="col-giorno"><?= e($day) ?></th>
                <td headers="col-orario <?= e(day_id($day)) ?>"><?= e($hours) ?></td>
              </tr>
<?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <section id="come-raggiungerci" class="section section-secondary" aria-labelledby="directions-title">
      <div class="container">
        <h2 id="directions-title" class="section-title">Come raggiungerci</h2>
        <ul class="directions-list">
          <li class="by-foot">
            <h3>A piedi</h3>
            <p>Il laboratorio è a pochi minuti dal centro storico, in una via pedonale.</p>
          </li>
          <li class="by-transit">
            <h3>Con i mezzi pubblici</h3>
            <p>Tram e autobus urbani fermano a meno di cinque minuti a piedi.</p>
          </li>
          <li class="access-info">
            <h3>Accesso</h3>
            <p>Ingresso al piano terra senza gradini e sala prove ampia, adatta anche a chi usa la sedia a rotelle.</p>
          </li>
        </ul>
      </div>
    </section>
<?php require __DIR__ . '/includes/cta.php'; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
