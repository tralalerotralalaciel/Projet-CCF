<?php
session_start();
require 'db.php';

$isAdmin = isset($_SESSION['admin']) && $_SESSION['admin'] === true;

/* =====================
   TRAITEMENT ADMIN
===================== */
if ($isAdmin && $_SERVER['REQUEST_METHOD'] === 'POST') {

    // UPDATE
    if (isset($_POST['update'])) {
        $stmt = $pdo->prepare(
            "UPDATE salle SET nom = ?, etat = ? WHERE id_salle = ?"
        );
        $stmt->execute([
            trim($_POST['nom']),
            (int)$_POST['etat'],
            (int)$_POST['id_salle']
        ]);

        $stmt = $pdo->prepare(
            "INSERT INTO historique (id_salle, date_heure)
             VALUES (?, NOW())"
        );
        $stmt->execute([(int)$_POST['id_salle']]);

        header('Location: index.php');
        exit;
    }

    // DELETE
    if (isset($_POST['delete'])) {
        $stmt = $pdo->prepare("DELETE FROM salle WHERE id_salle = ?");
        $stmt->execute([(int)$_POST['id_salle']]);
        header('Location: index.php');
        exit;
    }

    // CREATE ou UPDATE par nom
    if (isset($_POST['create'])) {
        $nom  = trim($_POST['nom']);
        $etat = (int)$_POST['etat'];

        $stmt = $pdo->prepare("SELECT id_salle FROM salle WHERE nom = ?");
        $stmt->execute([$nom]);
        $exist = $stmt->fetch();

        if ($exist) {
            $stmt = $pdo->prepare(
                "UPDATE salle SET etat = ? WHERE id_salle = ?"
            );
            $stmt->execute([$etat, $exist['id_salle']]);
            $idSalle = $exist['id_salle'];
        } else {
            $stmt = $pdo->prepare(
                "INSERT INTO salle (nom, etat) VALUES (?, ?)"
            );
            $stmt->execute([$nom, $etat]);
            $idSalle = $pdo->lastInsertId();
        }

        $stmt = $pdo->prepare(
            "INSERT INTO historique (id_salle, date_heure)
             VALUES (?, NOW())"
        );
        $stmt->execute([$idSalle]);

        header('Location: index.php');
        exit;
    }
}

/* =====================
   SALLE À ÉDITER
===================== */
$editSalle = null;
if ($isAdmin && isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM salle WHERE id_salle = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $editSalle = $stmt->fetch();
}

/* =====================
   LISTE DES SALLES
===================== */
$stmt = $pdo->query("SELECT * FROM salle ORDER BY nom");
$salles = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Occupation des salles</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="top-bar">
    <?php if ($isAdmin): ?>
        Administrateur |
        <a href="logout.php">Déconnexion</a>
    <?php else: ?>
        Visiteur |
        <a href="connexion.php">Connexion admin</a>
    <?php endif; ?>
</div>

<div class="container">
    <h2>État des salles</h2>

    <div class="rooms-grid">
        <?php foreach ($salles as $s): ?>
            <div class="room <?= $s['etat'] ? 'occupied' : 'available' ?>">
                <div>
                    <strong><?= htmlspecialchars($s['nom']) ?></strong><br>
                    <small><?= $s['etat'] ? 'Occupée' : 'Libre' ?></small>
                </div>
                <?php if ($isAdmin): ?>
                    <a href="?edit=<?= $s['id_salle'] ?>" class="edit">✏️</a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php if ($editSalle): ?>
<div class="container">
    <h3>Modifier la salle</h3>
    <form method="post">
        <input type="hidden" name="id_salle" value="<?= $editSalle['id_salle'] ?>">

        <input type="text" name="nom"
               value="<?= htmlspecialchars($editSalle['nom']) ?>" required>

        <select name="etat">
            <option value="0" <?= $editSalle['etat']==0?'selected':'' ?>>Libre</option>
            <option value="1" <?= $editSalle['etat']==1?'selected':'' ?>>Occupée</option>
        </select>

        <button name="update">Mettre à jour</button>
        <button name="delete"
            onclick="return confirm('Supprimer cette salle ?')">
            Supprimer
        </button>
    </form>
</div>
<?php endif; ?>

<?php if ($isAdmin): ?>
<div class="container">
    <h3>Ajouter une salle</h3>
    <form method="post">
        <input type="text" name="nom" placeholder="Nom de la salle" required>
        <select name="etat">
            <option value="0">Libre</option>
            <option value="1">Occupée</option>
        </select>
        <button name="create">Ajouter</button>
    </form>
</div>
<?php endif; ?>

</body>
</html>
