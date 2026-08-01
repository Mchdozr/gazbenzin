<?php
declare(strict_types=1);

// Admin page with LED preview + Speichern / Aktuelle Preise laden.
// This file must NEVER redirect to display.php.
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');

require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/fuels.php';

if (!isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$prices = readPrices();
$fuels = fuelDefinitions();

require __DIR__ . '/includes/admin-view.php';
