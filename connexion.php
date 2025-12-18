<?php
session_start();
require 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sécurisation basique des entrées
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Récupération de l'admin
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    // Vérification simple (mot de passe en clair)
    if ($admin && $password === $admin['password']) {
        $_SESSION['admin'] = true;
        session_regenerate_id(true);
        header('Location: index.php');
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

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post">
        <label for="username">Identifiant</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Se connecter</button>
    </form>
<br>
<center><a href="index.php" id="return">Voir les valeurs sans connexion</a></center>

    <p class="info">Accès réservé à l’administrateur</p>
</div>

</body>
</html>
