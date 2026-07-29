<?php
declare(strict_types=1);

require __DIR__ . '/../includes/auth.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'GET' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$ctx = stream_context_create([
    'http' => [
        'method' => 'GET',
        'timeout' => 20,
        'header' => "User-Agent: Mozilla/5.0 (compatible; KraftstoffWidget/1.0)\r\nAccept: application/json\r\n",
        'follow_location' => 1,
    ],
    'ssl' => [
        'verify_peer' => true,
        'verify_peer_name' => true,
    ],
]);

$json = @file_get_contents('https://www.benzinpreis-aktuell.de/api.v2.php?data=nationwide', false, $ctx);

if ($json === false) {
    http_response_code(502);
    echo json_encode(['error' => 'Preise nicht verfügbar']);
    exit;
}

$data = json_decode($json, true);

if (!is_array($data) || !isset($data['super'], $data['e10'], $data['diesel'])) {
    http_response_code(502);
    echo json_encode(['error' => 'Ungültige Preisdaten']);
    exit;
}

echo json_encode([
    'ok' => true,
    'e5' => (float) $data['super'],
    'e10' => (float) $data['e10'],
    'diesel' => (float) $data['diesel'],
    'sourceDate' => $data['date'] ?? null,
    'source' => 'benzinpreis-aktuell',
    'country' => 'DE',
], JSON_UNESCAPED_UNICODE);
