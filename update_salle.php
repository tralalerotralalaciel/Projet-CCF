<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    echo json_encode(['success' => false, 'error' => 'Non autorisé']);
    exit;
}

require 'db.php';

if (!isset($_POST['id_salle'])) {
    echo json_encode(['success' => false, 'error' => 'ID manquant']);
    exit;
}

$id_salle = (int) $_POST['id_salle'];

// Récupération état actuel
$stmt = $pdo->prepare("SELECT etat FROM salle WHERE id_salle = ?");
$stmt->execute([$id_salle]);
$salle = $stmt->fetch();

if (!$salle) {
    echo json_encode(['success' => false, 'error' => 'Salle introuvable']);
    exit;
}

// Inversion état
$newEtat = $salle['etat'] == 1 ? 0 : 1;

// Mise à jour
$stmt = $pdo->prepare("UPDATE salle SET etat = ? WHERE id_salle = ?");
$stmt->execute([$newEtat, $id_salle]);

echo json_encode([
    'success' => true,
    'etat' => $newEtat
]);
