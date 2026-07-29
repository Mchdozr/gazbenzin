<?php
declare(strict_types=1);

require __DIR__ . '/../includes/auth.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$raw = file_get_contents('php://input');
$data = json_decode((string) $raw, true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON']);
    exit;
}

$parse = static function ($value): ?float {
    if ($value === null || $value === '') {
        return null;
    }
    if (is_string($value)) {
        $value = str_replace(',', '.', trim($value));
    }
    if (!is_numeric($value)) {
        return null;
    }
    $n = (float) $value;
    if ($n < 0 || $n > 99.999) {
        return null;
    }
    return $n;
};

$e5 = $parse($data['e5'] ?? null);
$e10 = $parse($data['e10'] ?? null);
$diesel = $parse($data['diesel'] ?? null);

if ($e5 === null || $e10 === null || $diesel === null) {
    http_response_code(400);
    echo json_encode(['error' => 'Ungültige Preise']);
    exit;
}

$payload = savePrices($e5, $e10, $diesel);

echo json_encode([
    'ok' => true,
    'e5' => $payload['e5'],
    'e10' => $payload['e10'],
    'diesel' => $payload['diesel'],
    'updatedAt' => $payload['updatedAt'],
], JSON_UNESCAPED_UNICODE);
