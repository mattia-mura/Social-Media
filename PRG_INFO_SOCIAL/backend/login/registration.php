<!-- php
// backend/login/register.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/Database.php';
$pdo = Database::getInstance()->getConnection();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($email) && !empty($password)) {
        // Controlla se username o email esistono già
        $check = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $check->execute([$username, $email]);
        
        if ($check->rowCount() > 0) {
            $error = "Username o Email già in uso.";
        } else {
            // Hash della password per sicurezza
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            
            // Inserimento nuovo utente
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, avatar_url) VALUES (?, ?, ?, 'default_avatar.png')");
            
            if ($stmt->execute([$username, $email, $hashedPassword])) {
                // Login automatico dopo la registrazione
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['username'] = $username;
                $_SESSION['avatar_url'] = 'default_avatar.png';
                
                header('Location: /frontend/feed.php');
                exit;
            } else {
                $error = "Errore durante la registrazione. Riprova.";
            }
        }
    } else {
        $error = "Tutti i campi sono obbligatori.";
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gustagram — Registrati</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styleLogin.css">
</head>
<body>

    <div class="register-container">
        <img src="../../logo/logo_social-media.png" alt="Logo" class="logo-reg">
        <h2>Crea un account</h2>
        <p class="subtitle">Inizia a condividere i tuoi piatti preferiti</p>

        php if ($error): ?>
            <div class="error-msg">= htmlspecialchars($error) </div>
        php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Scegli un username" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="latua@email.com" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Minimo 6 caratteri" required>
            </div>
            <button type="submit" class="btn-reg">Registrati</button>
        </form>

        <p class="footer-link">
            Hai già un account? <a href="login.php">Accedi</a>
        </p>
    </div>

</body>
</html> -->
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/Database.php';
$pdo = Database::getInstance()->getConnection();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($email) && !empty($password)) {
        $check = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $check->execute([$username, $email]);
        
        if ($check->rowCount() > 0) {
            $error = "Username o Email già in uso.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, avatar_url) VALUES (?, ?, ?, 'default_avatar.png')");
            
            if ($stmt->execute([$username, $email, $hashedPassword])) {
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['username'] = $username;
                $_SESSION['avatar_url'] = 'default_avatar.png';
                
                header('Location: ../../frontend/feed.php');
                exit;
            } else {
                $error = "Errore durante la registrazione.";
            }
        }
    } else {
        $error = "Tutti i campi sono obbligatori.";
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gustagram — Registrati</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styleLogin.css">
</head>
<body>
    <div class="login-container">
        <img src="../../logo/logo_social-media.png" alt="Logo" class="logo-login">
        <h2>Crea account</h2>
        <p class="subtitle">Inizia la tua avventura culinaria</p>

        <?php if ($error): ?>
            <div class="error-box"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Scegli un username" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="latua@email.com" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Minimo 6 caratteri" required>
            </div>
            <button type="submit" class="btn-primary">Registrati</button>
        </form>

        <p class="footer-text">
            Hai già un account? <a href="login.php">Accedi</a>
        </p>
    </div>
</body>
</html>
<!-- quando uno crea account di default img=>default_avatr.png-->