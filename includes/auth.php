<?php
declare(strict_types=1);

session_start();

const AUTH_USER = 'admin';
const AUTH_PASS = 'admin';

function isLoggedIn(): bool
{
    return !empty($_SESSION['logged_in']);
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        http_response_code(401);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
}

function attemptLogin(string $user, string $pass): bool
{
    if ($user === AUTH_USER && $pass === AUTH_PASS) {
        $_SESSION['logged_in'] = true;
        return true;
    }
    return false;
}

function logout(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

function pricesFile(): string
{
    return __DIR__ . '/../api/data.json';
}

function readPrices(): array
{
    $file = pricesFile();
    if (is_file($file)) {
        $data = json_decode((string) file_get_contents($file), true);
        if (is_array($data) && isset($data['e5'], $data['e10'], $data['diesel'])) {
            return [
                'e5' => (float) $data['e5'],
                'e10' => (float) $data['e10'],
                'diesel' => (float) $data['diesel'],
                'updatedAt' => $data['updatedAt'] ?? null,
            ];
        }
    }

    return [
        'e5' => 2.209,
        'e10' => 2.151,
        'diesel' => 2.203,
        'updatedAt' => null,
    ];
}

function savePrices(float $e5, float $e10, float $diesel): array
{
    $payload = [
        'e5' => round($e5, 3),
        'e10' => round($e10, 3),
        'diesel' => round($diesel, 3),
        'updatedAt' => date('c'),
        'country' => 'DE',
    ];
    file_put_contents(pricesFile(), json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    return $payload;
}
