<?php
/* Atelier Tang - a featured garment and the fabric sample book. */

declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

$page = page_meta('realizzazioni', ['nav' => 'realizzazioni', 'breadcrumb' => [['label' => 'Realizzazioni']]]);
require __DIR__ . '/includes/header.php';
?>
    <section id="smoking-blu-notte" class="section" aria-labelledby="featured-title">
      <div class="container feature">
        <figure class="feature-figure">
          <picture>
            <source type="image/webp"
                    srcset="img/smoking-blu-notte-640.webp 640w, img/smoking-blu-notte-1024.webp 1024w"
                    sizes="(min-width: 48em) 40vw, 100vw" />
            <img class="photo" src="img/smoking-blu-notte-1024.jpg"
                 alt="Giacca da smoking blu notte con revers in raso su un manichino, tra gli scaffali di tessuti"
                 width="1024" height="1280" />
          </picture>
        </figure>
        <div class="feature-text">
          <h2 id="featured-title" class="section-title">Smoking blu notte</h2>
          <p>Un capo da cerimonia pensato per un matrimonio serale: giacca monopetto con un bottone, revers a lancia rivestiti in raso e pantalone con banda laterale.</p>
          <dl class="spec-list">
            <div><dt>Tessuto</dt><dd>Lana fresca blu notte, revers in raso di seta</dd></div>
            <div><dt>Lavorazione</dt><dd>Asole e impunture cucite a mano</dd></div>
            <div><dt>Tempi</dt><dd>Sette settimane, con tre prove</dd></div>
          </dl>
          <p><a class="btn btn-secondary" href="servizi.php#servizio-ceremony">Scopri il servizio per la cerimonia</a></p>
        </div>
      </div>
    </section>

    <section id="campionario" class="section section-secondary" aria-labelledby="fabrics-title">
      <div class="container">
        <h2 id="fabrics-title" class="section-title">Campionario dei tessuti</h2>
        <p class="section-intro">Alcuni dei tessuti che puoi toccare con mano in laboratorio. I riquadri riproducono la trama, non sono fotografie.</p>
        <ul class="fabric-list">
<?php foreach (fabrics() as $key => $fabric): ?>
          <li id="tessuto-<?= e($key) ?>" class="fabric">
            <span class="swatch swatch-<?= e($key) ?>" aria-hidden="true"></span>
            <h3><?= e($fabric['name']) ?></h3>
            <dl class="fabric-details">
              <div><dt>Composizione</dt><dd><?= e($fabric['composition']) ?></dd></div>
              <div><dt>Stagione</dt><dd><?= e($fabric['season']) ?></dd></div>
              <div><dt>Ideale per</dt><dd><?= e($fabric['use']) ?></dd></div>
            </dl>
          </li>
<?php endforeach; ?>
        </ul>
      </div>
    </section>
<?php require __DIR__ . '/includes/cta.php'; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
