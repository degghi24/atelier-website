<?php
/* Atelier Tang - cta.php
   Closing call to action shared by the content pages, so every page ends the same way. */

if (!defined('APP_ROOT')) {
    http_response_code(404);
    exit;
}
?>
    <section class="cta" aria-labelledby="cta-title">
      <div class="container cta-content">
        <div>
          <h2 id="cta-title">Hai in mente un capo?</h2>
          <p>Raccontacelo in una consulenza: guardiamo insieme modelli e tessuti e ricevi un preventivo chiaro, senza impegno.</p>
        </div>
        <p class="cta-actions"><a class="btn btn-cta" href="index.php#prenota">Prenota una consulenza</a></p>
      </div>
    </section>
