<?php
declare(strict_types=1);

require __DIR__ . '/../includes/auth.php';

header('Content-Type: application/json; charset=utf-8');
sendNoCacheHeaders();

$prices = readPrices();

echo json_encode([
    'diesel' => $prices['diesel'],
    'e10' => $prices['e10'],
    'e5' => $prices['e5'],
    'superPlus' => $prices['superPlus'],
    'adBlue' => $prices['adBlue'],
    'updatedAt' => $prices['updatedAt'],
    'serverTime' => date('c'),
    'source' => 'manual',
    'country' => 'DE',
], JSON_UNESCAPED_UNICODE);
