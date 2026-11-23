<?php
/* Atelier Tang - home page with the booking form. The form is processed by prenota.php. */

declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

$user = current_user();
$form = take_form('booking');
$errors = $form['errors'];
$old = $form['old'];
if ($old === [] && $user !== null) {
    /* Pre-fill the contact details of a logged-in user. */
    $old = ['full_name' => $user['nome'], 'email' => $user['email']];
}
$flash = take_flash();
$site = site_info();

$page = page_meta('home', ['nav' => 'home', 'breadcrumb' => []]);
require __DIR__ . '/includes/header.php';
?>
    <section class="hero" aria-labelledby="hero-title">
      <div class="container hero-content">
        <div class="hero-text">
          <h1 id="hero-title"><?= e($page['heading']) ?></h1>
          <p class="lead"><?= e($page['lead']) ?></p>
          <p class="hero-actions">
            <a class="btn btn-primary" href="#prenota">Prenota una consulenza</a>
            <a class="btn btn-secondary" href="servizi.php">Scopri i servizi</a>
          </p>
          <ul class="hero-facts">
            <li class="fact-days">Su appuntamento, dal martedì al sabato</li>
            <li class="fact-time">Da 4 a 6 settimane per un abito, con due o tre prove</li>
            <li class="fact-place">Nel centro storico di Padova</li>
          </ul>
        </div>
        <figure class="hero-figure">
          <picture>
            <source type="image/webp"
                    srcset="img/smoking-blu-notte-640.webp 640w, img/smoking-blu-notte-1024.webp 1024w"
                    sizes="(min-width: 48em) 40vw, 100vw" />
            <img class="photo" src="img/smoking-blu-notte-1024.jpg"
                 alt="Giacca da smoking blu notte con revers in raso su un manichino, nel laboratorio della sartoria"
                 width="1024" height="1280" fetchpriority="high" />
          </picture>
          <figcaption>Smoking blu notte con revers in raso, rifinito a mano nel nostro laboratorio.</figcaption>
        </figure>
      </div>
    </section>

    <section id="esplora" class="section section-secondary explore" aria-labelledby="explore-title">
      <div class="container">
        <h2 id="explore-title" class="section-title">Esplora l'atelier</h2>
        <p class="section-intro">Tutto quello che serve per arrivare preparato alla prima consulenza.</p>
        <ul class="explore-list">
          <li class="explore-item">
            <h3><a href="atelier.php">L'atelier e il metodo</a></h3>
            <p>Il laboratorio e le cinque fasi che portano dalla consulenza al capo finito.</p>
          </li>
          <li class="explore-item">
            <h3><a href="servizi.php">Servizi sartoriali</a></h3>
            <p>Abiti, camicie, cerimonia, riparazioni e consulenza sui tessuti, con tempi e numero di prove.</p>
          </li>
          <li class="explore-item">
            <h3><a href="realizzazioni.php">Realizzazioni e tessuti</a></h3>
            <p>Un capo realizzato nel laboratorio e il campionario dei tessuti tra cui scegliere.</p>
          </li>
          <li class="explore-item">
            <h3><a href="contatti.php">Contatti e orari</a></h3>
            <p>Indirizzo, recapiti, orari di apertura e come raggiungere il laboratorio.</p>
          </li>
        </ul>
      </div>
    </section>

    <section id="prenota" class="section" aria-labelledby="prenota-title">
      <div class="container booking-layout">
        <div class="booking-aside">
          <h2 id="prenota-title" class="section-title">Prenota una consulenza</h2>
          <p>
            Compila il modulo: ti ricontatteremo entro due giorni lavorativi per fissare l'appuntamento.
            I campi contrassegnati con l'asterisco (*) sono obbligatori.
          </p>
<?php if ($user !== null): ?>
          <p>
            Hai effettuato l'accesso come <strong><?= e($user['username']) ?></strong>: troverai la richiesta
            nella tua <a href="area.php">area riservata</a>, dove potrai modificarla o eliminarla.
          </p>
<?php else: ?>
          <p>
            Vuoi seguire le tue richieste dal sito? <a href="login.php">Accedi</a> oppure
            <a href="registrazione.php">crea un account</a> prima di inviare il modulo:
            potrai vederle, modificarle o eliminarle dall'area riservata.
          </p>
<?php endif; ?>
          <ul class="contact-list">
            <li class="contact-phone"><span>Preferisci telefonare? <a href="tel:<?= e($site['phone_link']) ?>"><?= e($site['phone_label']) ?></a></span></li>
            <li class="contact-hours"><span>Aperti dal martedì al sabato: consulta gli <a href="contatti.php#orari">orari completi</a>.</span></li>
          </ul>
        </div>

        <div>
          <?= render_flash($flash, 'esito', $errors === []) ?>
          <form id="booking-form" class="data-form" action="prenota.php" method="post" data-validate="true" data-summary-heading="h3">
            <?= error_summary($errors, 'h3') ?>
            <?= csrf_field() ?>

            <fieldset>
              <legend>I tuoi dati</legend>

              <p class="field">
                <label for="full-name">Nome e cognome *</label>
                <input type="text" id="full-name" name="full_name" required="required" minlength="2" maxlength="80"
                       pattern="<?= e(NAME_PATTERN) ?>" autocomplete="name" value="<?= old_value($old, 'full_name') ?>"
                       aria-describedby="error-full-name"<?= invalid_attr($errors, 'full_name') ?><?= msg_attrs('full_name') ?> />
                <?= error_span($errors, 'full_name') ?>
              </p>

              <p class="field">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required="required" maxlength="120"
                       autocomplete="email" value="<?= old_value($old, 'email') ?>"
                       aria-describedby="error-email"<?= invalid_attr($errors, 'email') ?><?= msg_attrs('email') ?> />
                <?= error_span($errors, 'email') ?>
              </p>

              <p class="field">
                <label for="phone">Telefono</label>
                <input type="tel" id="phone" name="phone" maxlength="20" pattern="<?= e(PHONE_PATTERN) ?>"
                       autocomplete="tel" value="<?= old_value($old, 'phone') ?>"
                       aria-describedby="hint-phone error-phone"<?= invalid_attr($errors, 'phone') ?><?= msg_attrs('phone') ?> />
                <span id="hint-phone" class="hint">Facoltativo. Solo numeri, spazi e il segno +.</span>
                <?= error_span($errors, 'phone') ?>
              </p>
            </fieldset>

            <fieldset>
              <legend>La tua richiesta</legend>

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
                <label for="message">Messaggio *</label>
                <textarea id="message" name="message" rows="5" cols="40" required="required"
                          minlength="10" maxlength="1000"
                          aria-describedby="hint-message error-message"<?= invalid_attr($errors, 'message') ?><?= msg_attrs('message') ?>><?= old_value($old, 'message') ?></textarea>
                <span id="hint-message" class="hint">Racconta cosa desideri: capo, occasione, tempi. Da 10 a 1000 caratteri.</span>
                <?= error_span($errors, 'message') ?>
              </p>

              <p class="field field-check">
                <input type="checkbox" id="privacy-consent" name="privacy_consent" value="1" required="required"
                       <?= ($old['privacy_consent'] ?? '') === '1' ? 'checked="checked" ' : '' ?>aria-describedby="hint-privacy-consent error-privacy-consent"<?= invalid_attr($errors, 'privacy_consent') ?><?= msg_attrs('privacy_consent') ?> />
                <label for="privacy-consent">Acconsento al trattamento dei miei dati personali per ricevere una risposta. *</label>
                <span id="hint-privacy-consent" class="hint">
                  Usiamo i dati solo per rispondere alla richiesta. Dettagli nell'<a href="privacy.php">informativa sulla privacy</a>.
                </span>
                <?= error_span($errors, 'privacy_consent') ?>
              </p>
            </fieldset>

            <p class="form-actions">
              <button type="submit" class="btn btn-primary">Invia la richiesta</button>
            </p>
          </form>
        </div>
      </div>
    </section>
<?php require __DIR__ . '/includes/footer.php'; ?>
