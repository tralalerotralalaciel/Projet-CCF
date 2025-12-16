<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: connexion.php");
    exit;
}
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

<div class="admin-info">
    Connecté : <strong>Administrateur</strong>
</div>

<div class="admin-layout">

    <div class="rooms-panel">
        <h3>Salles</h3>
        <div class="room available selected" onclick="selectRoom(this, 'Salle A')"><span>Salle A</span><div class="actions" onclick="event.stopPropagation()">✏️</div></div>
        <div class="room occupied" onclick="selectRoom(this, 'Salle B')"><span>Salle B</span><div class="actions" onclick="event.stopPropagation()">✏️</div></div>
        <div class="room available" onclick="selectRoom(this, 'Salle C')"><span>Salle C</span><div class="actions" onclick="event.stopPropagation()">✏️</div></div>
        <div class="room occupied" onclick="selectRoom(this, 'Salle D')"><span>Salle D</span><div class="actions" onclick="event.stopPropagation()">✏️</div></div>
    </div>

    <div class="graph-panel">
        <h3 id="graph-title">Salle A - réservations par semaine</h3>
        <canvas id="roomChart"></canvas>
    </div>

</div>

<a href="logout.php" class="logout">Se déconnecter</a>

<script>
const ctx = document.getElementById('roomChart');
let chart;

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
    document.getElementById('graph-title').innerText = room + " - réservations par semaine";

    const data = {
        labels: ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'],
        datasets: [{
            label: 'Nombre de réservations',
            data: roomData[room],
            backgroundColor: '#007BFF'
        }]
    };

    if(chart) chart.destroy();
    chart = new Chart(ctx, { type: 'bar', data });
}

// Affiche le graphique initial
showGraph('Salle A');
</script>

</body>
</html>
