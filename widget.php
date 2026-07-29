<?php
declare(strict_types=1);

require __DIR__ . '/includes/auth.php';

if (!isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$prices = readPrices();
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Kraftstoffpreise</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@500;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/style.css" />
</head>
<body class="admin-page">
  <main class="widget" aria-live="polite">
    <header class="brand-bar">
      <p class="brand-name" aria-label="LTC Tankstelle">
        <span class="brand-line brand-line--ltc">
          <span style="--i:0">L</span><span style="--i:1">T</span><span style="--i:2">C</span>
        </span>
        <span class="brand-line brand-line--sub">
          <span style="--i:3">T</span><span style="--i:4">a</span><span style="--i:5">n</span><span style="--i:6">k</span><span style="--i:7">s</span><span style="--i:8">t</span><span style="--i:9">e</span><span style="--i:10">l</span><span style="--i:11">l</span><span style="--i:12">e</span>
        </span> 
      </p>
    </header>
    <article class="card" data-key="e5">
      <p class="type">Super E5</p>
      <span class="rule" aria-hidden="true"></span>
      <p class="price">
        <input class="value-input" name="e5" type="text" inputmode="decimal"
               value="<?= htmlspecialchars(number_format($prices['e5'], 3, ',', ''), ENT_QUOTES, 'UTF-8') ?>" />
        <span class="currency">€</span>
      </p>
    </article>
    <article class="card" data-key="e10">
      <p class="type">Super E10</p>
      <span class="rule" aria-hidden="true"></span>
      <p class="price">
        <input class="value-input" name="e10" type="text" inputmode="decimal"
               value="<?= htmlspecialchars(number_format($prices['e10'], 3, ',', ''), ENT_QUOTES, 'UTF-8') ?>" />
        <span class="currency">€</span>
      </p>
    </article>
    <article class="card" data-key="diesel">
      <p class="type">Diesel</p>
      <span class="rule" aria-hidden="true"></span>
      <p class="price">
        <input class="value-input" name="diesel" type="text" inputmode="decimal"
               value="<?= htmlspecialchars(number_format($prices['diesel'], 3, ',', ''), ENT_QUOTES, 'UTF-8') ?>" />
        <span class="currency">€</span>
      </p>
    </article>
    <div class="footer-bar">
      <p class="datetime" id="datetime">--.--.----          --:--:--</p>
    </div>
  </main>

  <aside class="admin-toolbar" aria-label="Admin">
    <div class="admin-actions">
      <button type="button" class="save-btn" id="saveBtn">Speichern</button>
      <a class="logout-link" href="logout.php">Abmelden</a>
    </div>
    <button type="button" class="fetch-btn" id="fetchBtn">Aktuelle Preise laden</button>
    <p class="save-msg" id="saveMsg" hidden></p>
  </aside>

  <script src="assets/app.js"></script>
</body>
</html>
