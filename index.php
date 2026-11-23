<?php
/* XAMPP entry point. The website lives in src/, the same web root used by Docker,
   so every page and link works identically in both environments. */

header('Location: src/index.php', true, 302);
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="utf-8" />
  <title>Atelier Tang</title>
</head>
<body>
  <p><a href="src/index.php">Vai al sito di Atelier Tang</a></p>
</body>
</html>
