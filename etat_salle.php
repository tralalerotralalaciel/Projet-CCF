<?php
require 'db.php'; // connexion à la BDD

// Récupérer le JSON du capteur
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Vérifier les données
if (!isset($data['etat'], $data['nom'])) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Donnees manquantes"
    ]);
    exit;
}

$etat = (int)$data['etat']; // 0 = libre, 1 = occupée
$nom  = trim($data['nom']);

try {
    // Vérifier si la salle existe
    $stmt = $pdo->prepare("SELECT id_salle FROM salle WHERE nom = ?");
    $stmt->execute([$nom]);
    $salle = $stmt->fetch();

    if ($salle) {
        // Salle existante → mettre à jour l'état
        $stmt = $pdo->prepare("UPDATE salle SET etat = ? WHERE id_salle = ?");
        $stmt->execute([$etat, $salle['id_salle']]);
        $idSalle = $salle['id_salle'];
    } else {
        // Salle nouvelle → création
        $stmt = $pdo->prepare("INSERT INTO salle (nom, etat) VALUES (?, ?)");
        $stmt->execute([$nom, $etat]);
        $idSalle = $pdo->lastInsertId();
    }

    // Enregistrer dans l'historique avec l'état
    $stmt = $pdo->prepare("INSERT INTO historique (id_salle, date_heure, etat) VALUES (?, NOW(), ?)");
    $stmt->execute([$idSalle, $etat]);

    // Réponse JSON
    echo json_encode([
        "status" => "success",
        "salle" => $nom,
        "etat" => $etat ? "occupee" : "libre"
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Erreur serveur"
    ]);
}
