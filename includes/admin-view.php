<?php
declare(strict_types=1);

/** @var array $prices */
/** @var array $fuels */

$assetV = '20260801h';
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Kraftstoffpreise Admin</title>
  <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate" />
  <meta http-equiv="Pragma" content="no-cache" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@500;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/style.css?v=<?= htmlspecialchars($assetV, ENT_QUOTES, 'UTF-8') ?>" />
</head>
<body class="admin-page">
  <div class="led-panel" aria-label="LED Anzeige 416x624">
    <main class="widget" aria-live="polite">
      <header class="brand-bar">
        <p class="brand-name" aria-label="LTC Tankstelle"><?= brandLettersHtml() ?></p>
      </header>
      <?php foreach ($fuels as $fuel):
        $parts = formatPriceParts((float) $prices[$fuel['key']]);
      ?>
      <article class="card" data-key="<?= htmlspecialchars($fuel['key'], ENT_QUOTES, 'UTF-8') ?>">
        <p class="type"><?= htmlspecialchars($fuel['label'], ENT_QUOTES, 'UTF-8') ?></p>
        <p class="price">
          <span class="price-view" aria-hidden="true">
            <span class="price-main"><?= htmlspecialchars($parts['main'], ENT_QUOTES, 'UTF-8') ?></span><span class="price-sup"><?= htmlspecialchars($parts['sup'], ENT_QUOTES, 'UTF-8') ?></span>
          </span>
          <input class="value-input" name="<?= htmlspecialchars($fuel['key'], ENT_QUOTES, 'UTF-8') ?>" type="text" inputmode="decimal"
                 value="<?= htmlspecialchars($parts['full'], ENT_QUOTES, 'UTF-8') ?>" />
          <span class="currency">€</span>
        </p>
      </article>
      <?php endforeach; ?>
      <div class="footer-bar">
        <p class="datetime" id="datetime">--.--.---- --:--:--</p>
      </div>
    </main>
  </div>

  <aside class="admin-toolbar" aria-label="Admin">
    <button type="button" class="save-btn" id="saveBtn">Speichern</button>
    <button type="button" class="fetch-btn" id="fetchBtn">Aktuelle Preise laden</button>
    <p class="save-msg" id="saveMsg" hidden></p>
  </aside>

  <script src="assets/app.js?v=<?= htmlspecialchars($assetV, ENT_QUOTES, 'UTF-8') ?>"></script>
</body>
</html>
