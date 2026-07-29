<?php
declare(strict_types=1);

require __DIR__ . '/includes/auth.php';
logout();
header('Location: index.php');
exit;
