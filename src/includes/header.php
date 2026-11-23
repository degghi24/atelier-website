<?php
/* Atelier Tang - header.php
   Opening markup shared by every page: document head, skip link, site header,
   main navigation, breadcrumb, site search and the page heading. Each page
   defines $page before including it, usually with page_meta():

   $page = page_meta('servizi', [
       'nav' => 'servizi',                        // active menu item, or '' for none
       'breadcrumb' => [['label' => 'Servizi']],  // trail after Home, last item is the current page
   ]);

   Keys: id, title (document title), heading (h1), lead, description, keywords,
   nav, footer, breadcrumb, noindex (true for private pages), search_query.

   The markup follows the XML syntax (HTML5 polyglot): every element is closed,
   attributes are quoted and boolean attributes are written in full. */

if (!defined('APP_ROOT')) {
    http_response_code(404);
    exit;
}

$navCurrent = $page['nav'] ?? '';
$isHome = $navCurrent === 'home';
$breadcrumb = $page['breadcrumb'] ?? [];
$fullTitle = $isHome ? 'Atelier Tang | Sartoria artigianale su misura a Padova' : $page['title'] . ' | Atelier Tang';

/* Labels are trusted markup: "Home" is marked as an English word for screen readers.
   Access keys follow the numeric scheme listed in the accessibility statement. */
$navItems = [
    'home' => ['href' => 'index.php', 'label' => '<span lang="en">Home</span>', 'key' => '1'],
    'atelier' => ['href' => 'atelier.php', 'label' => 'L\'atelier', 'key' => ''],
    'servizi' => ['href' => 'servizi.php', 'label' => 'Servizi', 'key' => '3'],
    'realizzazioni' => ['href' => 'realizzazioni.php', 'label' => 'Realizzazioni', 'key' => ''],
    'contatti' => ['href' => 'contatti.php', 'label' => 'Contatti', 'key' => '9'],
    'prenota' => ['href' => $isHome ? '#prenota' : 'index.php#prenota', 'label' => 'Prenota', 'key' => '5'],
    'area' => ['href' => current_user() !== null ? 'area.php' : 'login.php', 'label' => 'Area riservata', 'key' => '6'],
];
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="it" xml:lang="it">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= e($fullTitle) ?></title>
  <meta name="description" content="<?= e($page['description']) ?>" />
  <meta name="keywords" content="<?= e($page['keywords']) ?>" />
<?php if (!empty($page['noindex'])): ?>
  <meta name="robots" content="noindex, nofollow" />
<?php endif; ?>
  <meta name="theme-color" content="#14213d" />
  <link rel="icon" href="img/favicon.svg" type="image/svg+xml" />
  <link rel="preload" href="fonts/atkinson-next-latin.woff2" as="font" type="font/woff2" crossorigin="anonymous" />
  <link rel="preload" href="fonts/jost-latin.woff2" as="font" type="font/woff2" crossorigin="anonymous" />
  <link rel="stylesheet" href="<?= asset('css/style.css') ?>" media="screen" />
  <link rel="stylesheet" href="<?= asset('css/mobile.css') ?>" media="screen and (max-width: 47.99em)" />
  <link rel="stylesheet" href="<?= asset('css/print.css') ?>" media="print" />
  <script src="<?= asset('js/main.js') ?>"></script>
</head>
<body id="top" class="page-<?= e($page['id'] ?? 'generica') ?>">
  <a class="skip-link" href="#main-content" accesskey="2">Vai al contenuto principale</a>

  <header class="site-header">
    <div class="container header-content">
<?php if ($isHome): ?>
      <span class="brand">
<?php else: ?>
      <a class="brand" href="index.php">
<?php endif; ?>
        <img class="brand-mark" src="img/logo-simbolo-negativo.svg" alt="" width="48" height="48" />
        <span class="brand-text">
          <span class="brand-name">Atelier Tang</span>
          <span class="brand-tagline">Sartoria su misura</span>
        </span>
<?php if ($isHome): ?>
      </span>
<?php else: ?>
        <span class="sr-text">: torna alla pagina iniziale</span>
      </a>
<?php endif; ?>

      <nav id="primary-menu" class="main-nav" aria-label="Menu principale">
        <ul>
<?php foreach ($navItems as $key => $item): ?>
          <li<?= $key === 'area' ? ' class="nav-area"' : '' ?>>
<?php if ($key === $navCurrent): ?>
            <span class="nav-current" aria-current="page"><?= $item['label'] ?></span>
<?php else: ?>
            <a href="<?= e($item['href']) ?>"<?= $item['key'] !== '' ? ' accesskey="' . e($item['key']) . '"' : '' ?>><?= $item['label'] ?></a>
<?php endif; ?>
          </li>
<?php endforeach; ?>
        </ul>
      </nav>
    </div>
  </header>

  <div class="page-tools">
    <div class="container page-tools-content">
      <nav class="breadcrumb" aria-labelledby="breadcrumb-label">
        <p id="breadcrumb-label">Ti trovi in:</p>
        <ol>
<?php if ($breadcrumb === []): ?>
          <li><span aria-current="page" lang="en">Home</span></li>
<?php else: ?>
          <li><a href="index.php" lang="en">Home</a></li>
<?php foreach ($breadcrumb as $index => $crumb): ?>
<?php if ($index === count($breadcrumb) - 1): ?>
          <li><span aria-current="page"><?= e($crumb['label']) ?></span></li>
<?php else: ?>
          <li><a href="<?= e($crumb['href']) ?>"><?= e($crumb['label']) ?></a></li>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
        </ol>
      </nav>

      <form class="site-search" action="cerca.php" method="get" role="search">
        <fieldset>
          <legend class="sr-text">Ricerca nel sito</legend>
          <label for="ricerca">Cerca nel sito</label>
          <input type="search" id="ricerca" name="q" maxlength="100" accesskey="4"
                 value="<?= e($page['search_query'] ?? '') ?>" />
          <button type="submit" class="btn btn-secondary">Cerca</button>
        </fieldset>
      </form>
    </div>
  </div>

  <main id="main-content" tabindex="-1">
<?php if (!$isHome && !empty($page['heading'])): ?>
    <div class="page-head">
      <div class="container">
        <h1><?= e($page['heading']) ?></h1>
<?php if (!empty($page['lead'])): ?>
        <p class="lead"><?= e($page['lead']) ?></p>
<?php endif; ?>
      </div>
    </div>
<?php endif; ?>
