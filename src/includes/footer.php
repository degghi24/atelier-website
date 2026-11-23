<?php
/* Atelier Tang - footer.php
   Closing markup shared by every page. Expects the same $page array as header.php. */

if (!defined('APP_ROOT')) {
    http_response_code(404);
    exit;
}

$site = site_info();
$footerCurrent = $page['footer'] ?? ($page['nav'] ?? '');
/* Contacts already has access key 9 in the main menu, so the footer link has none. */
$footerLinks = [
    'contatti' => ['href' => 'contatti.php', 'label' => 'Contatti e orari', 'key' => ''],
    'privacy' => ['href' => 'privacy.php', 'label' => 'Informativa sulla privacy', 'key' => '8'],
    'accessibilita' => ['href' => 'accessibilita.php', 'label' => 'Dichiarazione di accessibilità', 'key' => '0'],
];
?>
  </main>

  <footer class="site-footer">
    <div class="container footer-content">
      <div class="footer-about">
        <p class="footer-brand">Atelier Tang</p>
        <p>Sartoria artigianale su misura nel centro di Padova. Riceviamo su appuntamento.</p>
        <address>
          <?= e($site['street']) ?>, <?= e($site['city']) ?><br />
          <abbr title="Telefono">Tel.</abbr> <a href="tel:<?= e($site['phone_link']) ?>"><?= e($site['phone_label']) ?></a><br />
          Email <a href="mailto:<?= e($site['email']) ?>"><?= e($site['email']) ?></a>
        </address>
      </div>

      <div class="footer-hours">
        <h2 class="footer-title">Orari</h2>
        <dl>
<?php foreach (opening_hours() as $day => $hours): ?>
          <dt><?= e($day) ?></dt>
          <dd><?= e($hours) ?></dd>
<?php endforeach; ?>
        </dl>
      </div>

      <nav class="footer-links" aria-labelledby="footer-links-title">
        <h2 class="footer-title" id="footer-links-title">Link utili</h2>
        <ul>
<?php foreach ($footerLinks as $key => $link): ?>
<?php if ($key === $footerCurrent): ?>
          <li><span aria-current="page"><?= e($link['label']) ?></span></li>
<?php else: ?>
          <li><a href="<?= e($link['href']) ?>"<?= $link['key'] !== '' ? ' accesskey="' . e($link['key']) . '"' : '' ?>><?= e($link['label']) ?></a></li>
<?php endif; ?>
<?php endforeach; ?>
          <li><a href="#top">Torna all'inizio della pagina</a></li>
        </ul>
      </nav>
    </div>
    <div class="container footer-legal">
      <p><small>&#169; <?= e(date('Y')) ?> Atelier Tang. Progetto didattico di Tecnologie Web, Università degli Studi di Padova.</small></p>
    </div>
  </footer>
</body>
</html>
