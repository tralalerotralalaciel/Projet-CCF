<?php
session_start();
if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    header("Location: connexion.php");
    exit;
}

require 'db.php';

$stmt = $pdo->query("SELECT * FROM salle");
$salles = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Administration</title>
<link rel="stylesheet" href="style.css">
</head>
<body class="admin-body">

<div class="admin-info">Connecté : <strong>Administrateur</strong></div>

<div class="rooms-panel">
    <h3>Liste des Salles</h3>
    <?php foreach ($salles as $salle): ?>
        <div class="room <?= htmlspecialchars($salle['etat']) ?>">
            <span><?= htmlspecialchars($salle['nom']) ?></span>
            <div class="actions">✏️</div>
        </div>
    <?php endforeach; ?>
</div>

<a href="logout.php" class="logout">Se déconnecter</a>

</body>
</html>
