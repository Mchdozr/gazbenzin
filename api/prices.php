<?php
declare(strict_types=1);

require __DIR__ . '/../includes/auth.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$prices = readPrices();

echo json_encode([
    'e5' => $prices['e5'],
    'e10' => $prices['e10'],
    'diesel' => $prices['diesel'],
    'updatedAt' => $prices['updatedAt'],
    'source' => 'manual',
    'country' => 'DE',
], JSON_UNESCAPED_UNICODE);
