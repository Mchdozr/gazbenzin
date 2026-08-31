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

function sendNoCacheHeaders(): void
{
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
    header('Expires: 0');
    header('Surrogate-Control: no-store');
    header('CDN-Cache-Control: no-store');
    header('Cloudflare-CDN-Cache-Control: no-store');
    header('X-Accel-Expires: 0');
}

function defaultPrices(): array
{
    return [
        'diesel' => 2.399,
        'e10' => 2.299,
        'e5' => 2.359,
        'superPlus' => 2.669,
        'adBlue' => 1.999,
        'updatedAt' => null,
    ];
}

function readPrices(): array
{
    $defaults = defaultPrices();
    $file = pricesFile();
    clearstatcache(true, $file);
    if (!is_file($file)) {
        return $defaults;
    }

    $data = json_decode((string) file_get_contents($file), true);
    if (!is_array($data)) {
        return $defaults;
    }

    return [
        'diesel' => isset($data['diesel']) ? (float) $data['diesel'] : $defaults['diesel'],
        'e10' => isset($data['e10']) ? (float) $data['e10'] : $defaults['e10'],
        'e5' => isset($data['e5']) ? (float) $data['e5'] : $defaults['e5'],
        'superPlus' => isset($data['superPlus']) ? (float) $data['superPlus'] : $defaults['superPlus'],
        'adBlue' => isset($data['adBlue']) ? (float) $data['adBlue'] : $defaults['adBlue'],
        'updatedAt' => $data['updatedAt'] ?? null,
    ];
}

function savePrices(float $diesel, float $e10, float $e5, float $superPlus, float $adBlue): array
{
    $payload = [
        'diesel' => round($diesel, 3),
        'e10' => round($e10, 3),
        'e5' => round($e5, 3),
        'superPlus' => round($superPlus, 3),
        'adBlue' => round($adBlue, 3),
        'updatedAt' => date('c'),
        'country' => 'DE',
    ];
    file_put_contents(pricesFile(), json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    return $payload;
}
