<?php
// frontend/layout/header.php
// Includi questo file in cima a ogni pagina protetta

// Avvia la sessione se non è già attiva
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. RIATTIVATO: Controllo sicurezza
// Impedisce errori "Undefined index" nelle pagine successive
// In frontend/layout/header.php
if (!isset($_SESSION['user_id'])) {
    // Usiamo ../ per salire di una cartella (da frontend/ a project/) 
    // e poi entriamo in backend/login/
    header('Location: ../backend/login/registration.php');
    exit;
}

// Dati utente dalla sessione
$sessionUser = [
    'id'            => $_SESSION['user_id'],
    'username'      => $_SESSION['username']      ?? 'utente',
    'avatar_url'    => $_SESSION['avatar_url']    ?? null,
    'notifications' => $_SESSION['notifications'] ?? 0,
];

// Pagina corrente (per evidenziare il nav attivo)
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gustagram<?= isset($pageTitle) ? ' — ' . htmlspecialchars($pageTitle) : '' ?></title>

    <link rel="stylesheet" href="/frontend/assets/css/main.css">
    <link rel="stylesheet" href="/frontend/assets/css/layout.css">
    <?php if (isset($pageCSS)): ?>
        <link rel="stylesheet" href="/frontend/assets/css/<?= htmlspecialchars($pageCSS) ?>">
    <?php endif; ?>

    <link rel="icon" type="image/png" href="/logo/logo_social-media.png">
</head>
<body>

<nav class="topnav">
    <a href="/frontend/feed.php" class="topnav__logo">
        <div class="topnav__logo-icon">
            <img src="/logo/logo_social-media.png" alt="Gustagram logo">
        </div>
        <span class="topnav__logo-text">Gustagram</span>
    </a>

    <div class="topnav__actions">
        <a href="/frontend/search.php" class="topnav__btn" aria-label="Cerca">🔍</a>

        <a href="/frontend/notifications.php" class="topnav__btn" aria-label="Notifiche">
            🔔
            <?php if ($sessionUser['notifications'] > 0): ?>
                <span class="badge"><?= min($sessionUser['notifications'], 99) ?></span>
            <?php endif; ?>
        </a>

        <a href="/frontend/profile.php?user=<?= urlencode($sessionUser['username']) ?>"
           class="topnav__btn" aria-label="Profilo">
            <?php if ($sessionUser['avatar_url']): ?>
                <img src="<?= htmlspecialchars($sessionUser['avatar_url']) ?>"
                     alt="avatar"
                     style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
            <?php else: ?>
                <span style="font-size:18px">👤</span>
            <?php endif; ?>
        </a>
    </div>
</nav>