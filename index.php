<?php
session_start();
if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    header("Location: connexion.php");
    exit;
}

require 'db.php';

// Récupération des salles depuis la table salle
$stmt = $pdo->query("SELECT * FROM salle");
$salles = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Administration</title>
<link rel="stylesheet" href="style.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="admin-body">

<div class="admin-info">Connecté : <strong>Administrateur</strong></div>

<div class="admin-layout">
    <div class="rooms-panel">
        <h3>Salles</h3>

        <?php foreach ($salles as $salle): ?>
            <div class="room <?= htmlspecialchars($salle['etat']) ?>"
                 onclick="selectRoom(this, '<?= htmlspecialchars($salle['nom']) ?>')">

                <span><?= htmlspecialchars($salle['nom']) ?></span>
                <div class="actions">✏️</div>
            </div>
        <?php endforeach; ?>

    </div>

    <div class="graph-panel">
        <h3 id="graph-title">Sélectionnez une salle</h3>
        <canvas id="roomChart"></canvas>
    </div>
</div>

<a href="logout.php" class="logout">Se déconnecter</a>

<script>
const ctx = document.getElementById('roomChart');
let chart;

// Données simulées (prototype)
const roomData = {
    'Salle A': [2, 3, 6, 1, 4],
    'Salle B': [1, 5, 2, 4, 3],
    'Salle C': [3, 2, 1, 5, 4],
    'Salle D': [0, 2, 3, 1, 2]
};

function selectRoom(element, room) {
    document.querySelectorAll('.room').forEach(r => r.classList.remove('selected'));
    element.classList.add('selected');
    showGraph(room);
}

function showGraph(room) {
    document.getElementById('graph-title').innerText =
        room + " - occupation hebdomadaire";

    const data = {
        labels: ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'],
        datasets: [{
            label: 'Nombre d’occupations',
            data: roomData[room] || [],
            backgroundColor: '#007BFF'
        }]
    };

    if (chart) chart.destroy();
    chart = new Chart(ctx, { type: 'bar', data });
}
</script>

</body>
</html>
