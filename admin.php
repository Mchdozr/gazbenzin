<?php
declare(strict_types=1);

require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/fuels.php';

if (!isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$prices = readPrices();
$fuels = fuelDefinitions();

require __DIR__ . '/includes/admin-view.php';
