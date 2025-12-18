# index.php
```php
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
```
