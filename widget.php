<?php
declare(strict_types=1);

require __DIR__ . '/includes/auth.php';

if (!isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$prices = readPrices();

$fuels = [
    ['key' => 'diesel', 'label' => 'Diesel'],
    ['key' => 'e10', 'label' => 'Super E10'],
    ['key' => 'e5', 'label' => 'Super E5'],
    ['key' => 'superPlus', 'label' => 'Super Plus'],
    ['key' => 'adBlue', 'label' => 'AdBlue'],
];

function formatPriceParts(float $value): array
{
    $raw = number_format($value, 3, ',', '');
    return [
        'full' => $raw,
        'main' => substr($raw, 0, -1),
        'sup' => substr($raw, -1),
    ];
}
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
  <div class="led-panel" aria-label="LED Anzeige 416x624">
    <main class="widget" aria-live="polite">
      <header class="brand-bar">
        <p class="brand-name" aria-label="LTC Tankstelle">
          <span class="brand-line brand-line--ltc">
            <span style="--i:0">L</span><span style="--i:1">T</span><span style="--i:2">C</span>
          </span>
          <span class="brand-line brand-line--sub">
            <span style="--i:3">T</span><span style="--i:4">A</span><span style="--i:5">N</span><span style="--i:6">K</span><span style="--i:7">S</span><span style="--i:8">T</span><span style="--i:9">E</span><span style="--i:10">L</span><span style="--i:11">L</span><span style="--i:12">E</span>
          </span>
        </p>
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
        <p class="datetime" id="datetime">--.--.----          --:--:--</p>
      </div>
    </main>
  </div>

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
