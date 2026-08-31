<?php
declare(strict_types=1);

header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');

require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/fuels.php';

$prices = readPrices();
$fuels = fuelDefinitions();

require __DIR__ . '/includes/admin-view.php';
