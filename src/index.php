<?php
declare(strict_types=1);
session_start();

/* Create a CSRF token for the booking form. */
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

/* Values returned after the booking request is validated. */
$flash = $_SESSION['flash'] ?? null; // ['type' => 'success'|'error', 'message' => '...']
$errors = $_SESSION['errors'] ?? []; // ['full_name' => 'message', ...]
$oldValues = $_SESSION['old_values'] ?? [];
unset($_SESSION['flash'], $_SESSION['errors'], $_SESSION['old_values']);

$isLoggedIn = isset($_SESSION['user']);

/* Escape dynamic output and render form state safely. */
function escapeHtml(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function fieldValue(array $values, string $key): string
{
    return escapeHtml($values[$key] ?? '');
}
function fieldError(array $errors, string $key): string
{
    return isset($errors[$key]) ? escapeHtml($errors[$key]) : '';
}
function invalidAttribute(array $errors, string $key): string
{
    return isset($errors[$key]) ? ' aria-invalid="true"' : '';
}

/* Reusable service options. */
$services = [
    'suit' => ['Abiti e giacche su misura', 'Completi, giacche e pantaloni disegnati sulla tua figura, con rifiniture e fodere a scelta.'],
    'shirt' => ['Camicie su misura', 'Colli, polsini e tessuti scelti insieme. Vestibilità precisa, cuciture pulite.'],
    'ceremony' => ['Abiti da cerimonia e da sposo', 'Per matrimoni ed eventi importanti, con tempi pianificati e più prove abito.'],
    'womenswear' => ['Abiti da donna', 'Tailleur, abiti e pantaloni su misura, dal modello al capo finito.'],
    'alteration' => ['Modifiche e riparazioni', 'Orli, strette, cambi di cerniera e rinnovo di capi che vuoi continuare a indossare.'],
    'fabric-consultation' => ['Consulenza sui tessuti', 'Ti aiutiamo a scegliere peso, trama e colore in base a stagione, uso e budget.'],
];
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Atelier Tang | Sartoria artigianale su misura a Padova</title>
  <meta name="description" content="Atelier Tang a Padova: sartoria artigianale su misura per abiti, giacche, camicie e abiti da cerimonia. Prenota una consulenza e una prova." />
  <meta name="keywords" content="sartoria Padova, abiti su misura, sartoria artigianale, abito da sposo, riparazioni sartoriali, atelier Padova" />
  <link rel="stylesheet" href="css/style.css?v=atelier-navy-9" />
  <script src="js/main.js" defer="defer"></script>
</head>

<body id="top">

  <a class="skip-link" href="#main-content">Vai al contenuto principale</a>

  <header class="site-header">
    <div class="container header-inner">
      <a class="logo" href="index.php" aria-label="Atelier Tang, torna alla home">
        <img src="img/logo-simbolo-negativo.svg" alt="" width="56" height="56" />
        <span class="logo-copy">
          <span class="logo-title">Atelier Tang</span>
        </span>
      </a>
      <span class="logo-subtitle">Sartoria su misura</span>

      <button class="nav-toggle" type="button" aria-label="Menu principale" aria-expanded="false" aria-controls="primary-menu">
        <span class="menu-icon" aria-hidden="true"><span></span><span></span><span></span></span>
        <span>Menu</span>
      </button>

      <nav id="primary-menu" class="main-nav" aria-label="Navigazione principale">
        <ul>
          <li><a href="index.php" aria-current="page">Home</a></li>
          <li><a href="html/atelier.html">L'atelier</a></li>
          <li><a href="html/servizi.html">Servizi</a></li>
          <li><a href="html/realizzazioni.html">Realizzazioni</a></li>
          <li><a href="html/contatti.html">Contatti</a></li>
          <li><a href="index.php#booking">Prenota</a></li>
<?php if ($isLoggedIn): ?>
          <li><a class="nav-login" href="area.php">Le mie richieste</a></li>
          <li><a href="logout.php">Esci</a></li>
<?php else: ?>
          <li><a class="nav-login" href="login.php">Area riservata</a></li>
<?php endif; ?>
        </ul>
      </nav>
    </div>
  </header>

  <main id="main-content">

    <section class="hero" aria-labelledby="hero-title">
      <div class="container hero-inner">
        <div class="hero-text">
          <h1 id="hero-title">Atelier Tang, sartoria artigianale a Padova</h1>
          <p class="hero-lead">
            Ogni capo nasce da una misura, da un tessuto scelto con cura e da molte ore di lavoro a mano.
            Abiti, giacche, camicie e capi da cerimonia cuciti su di te, nel cuore di Padova.
          </p>
          <p class="hero-actions">
            <a class="btn btn-primary" href="#booking">Prenota una consulenza</a>
            <a class="btn btn-secondary" href="html/servizi.html">Scopri i servizi</a>
          </p>
        </div>
        <figure class="hero-figure">
          <img class="hero-cloth" src="img/midnight-navy-tuxedo-atelier.png"
               alt="Smoking uomo blu notte con dettagli in raso, esposto su un manichino nell'atelier"
               width="1024" height="1536" />
          <figcaption>Smoking uomo blu notte, realizzato con cura sartoriale e dettagli in raso</figcaption>
        </figure>
      </div>
    </section>

    <section class="section" aria-labelledby="explore-title">
      <div class="container">
        <h2 id="explore-title">Esplora Atelier Tang</h2>
        <div class="grid services-grid">
          <article class="service">
            <h3><a href="html/atelier.html">L'atelier e il metodo</a></h3>
            <p>Scopri il laboratorio e le fasi di realizzazione di un capo su misura.</p>
          </article>
          <article class="service">
            <h3><a href="html/servizi.html">Servizi sartoriali</a></h3>
            <p>Abiti, camicie, cerimonia, riparazioni e consulenza sui tessuti.</p>
          </article>
          <article class="service">
            <h3><a href="html/realizzazioni.html">Capi e tessuti</a></h3>
            <p>Guarda alcuni esempi di stili e materiali per il tuo progetto.</p>
          </article>
          <article class="service">
            <h3><a href="html/contatti.html">Contatti e orari</a></h3>
            <p>Consulta i recapiti e le informazioni per venirci a trovare.</p>
          </article>
        </div>
      </div>
    </section>

    <section id="booking" class="section section-alt" aria-labelledby="booking-title">
      <div class="container two-col">
        <div>
          <h2 id="booking-title">Prenota una consulenza</h2>
          <p>
            Compila il modulo: ti ricontatteremo entro due giorni lavorativi per fissare l'appuntamento.
            I campi contrassegnati con asterisco sono obbligatori.
          </p>
          <p>
            Dopo l'accesso all'<a href="login.php">area riservata</a> potrai vedere,
            modificare o cancellare le tue richieste.
          </p>
        </div>

        <form id="booking-form" action="php/prenota.php" method="post">
<?php if (is_array($flash)): ?>
          <p class="flash flash-<?= escapeHtml($flash['type'] ?? 'error') ?>" role="status"><?= escapeHtml($flash['message'] ?? '') ?></p>
<?php endif; ?>
          <input type="hidden" name="csrf" value="<?= escapeHtml($_SESSION['csrf']) ?>" />

          <fieldset>
            <legend>I tuoi dati</legend>

            <p class="field">
              <label for="full-name">Nome e cognome *</label>
              <input type="text" id="full-name" name="full_name" required="required" minlength="2" maxlength="80"
                     pattern="[A-Za-zÀ-ÖØ-öø-ÿ' .\-]+"
                     autocomplete="name" value="<?= fieldValue($oldValues, 'full_name') ?>"
                     aria-describedby="error-full-name"<?= invalidAttribute($errors, 'full_name') ?> />
              <span id="error-full-name" class="error" role="alert"><?= fieldError($errors, 'full_name') ?></span>
            </p>

            <p class="field">
              <label for="email">Email *</label>
              <input type="email" id="email" name="email" required="required" maxlength="120"
                     autocomplete="email" value="<?= fieldValue($oldValues, 'email') ?>"
                     aria-describedby="error-email"<?= invalidAttribute($errors, 'email') ?> />
              <span id="error-email" class="error" role="alert"><?= fieldError($errors, 'email') ?></span>
            </p>

            <p class="field">
              <label for="phone">Telefono</label>
              <input type="tel" id="phone" name="phone" maxlength="20"
                     autocomplete="tel" pattern="[0-9+ ]{6,20}" value="<?= fieldValue($oldValues, 'phone') ?>"
                     aria-describedby="help-phone error-phone"<?= invalidAttribute($errors, 'phone') ?> />
              <span id="help-phone" class="hint">Solo numeri, spazi e il segno +.</span>
              <span id="error-phone" class="error" role="alert"><?= fieldError($errors, 'phone') ?></span>
            </p>
          </fieldset>

          <fieldset>
            <legend>La tua richiesta</legend>

            <p class="field">
              <label for="service">Servizio *</label>
              <select id="service" name="service" required="required"
                      aria-describedby="error-service"<?= invalidAttribute($errors, 'service') ?>>
                <option value="">Scegli un servizio</option>
<?php foreach ($services as $serviceKey => $service): ?>
                <option value="<?= escapeHtml($serviceKey) ?>"<?= (($oldValues['service'] ?? '') === $serviceKey) ? ' selected="selected"' : '' ?>><?= escapeHtml($service[0]) ?></option>
<?php endforeach; ?>
              </select>
              <span id="error-service" class="error" role="alert"><?= fieldError($errors, 'service') ?></span>
            </p>

            <p class="field">
              <label for="preferred-date">Data preferita *</label>
              <input type="date" id="preferred-date" name="preferred_date" required="required" min="<?= escapeHtml(date('Y-m-d')) ?>"
                     value="<?= fieldValue($oldValues, 'preferred_date') ?>"
                     aria-describedby="error-preferred-date"<?= invalidAttribute($errors, 'preferred_date') ?> />
              <span id="error-preferred-date" class="error" role="alert"><?= fieldError($errors, 'preferred_date') ?></span>
            </p>

            <p class="field">
              <label for="message">Messaggio *</label>
              <textarea id="message" name="message" rows="5" cols="40" required="required"
                        minlength="10" maxlength="1000"
                        aria-describedby="help-message error-message"<?= invalidAttribute($errors, 'message') ?>><?= fieldValue($oldValues, 'message') ?></textarea>
              <span id="help-message" class="hint">Racconta cosa desideri: capo, occasione, tempi. Da 10 a 1000 caratteri.</span>
              <span id="error-message" class="error" role="alert"><?= fieldError($errors, 'message') ?></span>
            </p>

            <p class="field field-check">
              <input type="checkbox" id="privacy-consent" name="privacy_consent" value="1" required="required"
                     aria-describedby="error-privacy-consent"<?= invalidAttribute($errors, 'privacy_consent') ?> />
              <label for="privacy-consent">Acconsento al trattamento dei dati per essere ricontattato. *</label>
              <span id="error-privacy-consent" class="error" role="alert"><?= fieldError($errors, 'privacy_consent') ?></span>
            </p>
          </fieldset>

          <p>
            <button type="submit" class="btn btn-primary">Invia la richiesta</button>
            <button type="reset" class="btn btn-secondary">Annulla</button>
          </p>
        </form>
      </div>
    </section>

  </main>

  <footer class="site-footer">
    <div class="container footer-inner">
      <p class="footer-brand">Atelier Tang, sartoria artigianale a Padova</p>
      <nav aria-label="Link del sito">
        <ul>
          <li><a href="index.php#top">Torna su</a></li>
          <li><a href="html/atelier.html">L'atelier</a></li>
          <li><a href="html/servizi.html">Servizi</a></li>
          <li><a href="html/realizzazioni.html">Realizzazioni</a></li>
          <li><a href="html/contatti.html">Contatti</a></li>
          <li><a href="login.php">Area riservata</a></li>
        </ul>
      </nav>
      <p><small>&#169; <?= escapeHtml(date('Y')) ?> Atelier Tang. Progetto di Tecnologie Web, Università di Padova.</small></p>
    </div>
  </footer>

</body>
</html>