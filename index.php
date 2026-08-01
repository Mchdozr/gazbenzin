<?php
declare(strict_types=1);

require __DIR__ . '/includes/auth.php';

$error = '';

if (isLoggedIn()) {
    header('Location: admin.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim((string) ($_POST['username'] ?? ''));
    $pass = (string) ($_POST['password'] ?? '');
    if (attemptLogin($user, $pass)) {
        header('Location: admin.php');
        exit;
    }
    $error = 'Benutzername oder Passwort falsch.';
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=416, height=624, initial-scale=1" />
  <title>Anmeldung</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@500;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/style.css" />
</head>
<body>
  <main class="widget login-widget">
    <form class="login-card" method="post" action="index.php" autocomplete="off">
      <h1 class="login-title">Anmeldung</h1>
      <?php if ($error !== ''): ?>
        <p class="login-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
      <?php endif; ?>
      <label class="login-label" for="username">Benutzername</label>
      <input class="login-input" id="username" name="username" type="text" required autofocus />
      <label class="login-label" for="password">Passwort</label>
      <input class="login-input" id="password" name="password" type="password" required />
      <button class="login-btn" type="submit">Einloggen</button>
    </form>
  </main>
</body>
</html>
