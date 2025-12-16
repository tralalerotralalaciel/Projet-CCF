<?php
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Identifiants de test
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin'] = true;
        header('Location: index.php'); // Redirige vers l’admin
        exit;
    } else {
        $error = "Identifiant ou mot de passe incorrect";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Connexion Administrateur</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="login-container">
    <h2>Connexion Administrateur</h2>

    <?php if($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="post">
        <label for="username">Identifiant</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Se connecter</button>
    </form>

    
    <p class="info">Accès réservé à l'administrateur</p>
</div>
</body>
</html>
