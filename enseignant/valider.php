<?php
session_start();
require_once 'connexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_projet = $_POST['id_projet'];
    $remarque = $_POST['remarque'] ?? null;
    $note = $_POST['note'] ?? null;

    // Vérification
    if (!$id_projet || $note === null) {
        die("Paramètres manquants.");
    }

    // Pour ce test : on simule que l'enseignant connecté est l'utilisateur ID = 3
    $id_enseignant = 3;

    try {
        // 1. Mettre à jour le projet
        $stmt = $pdo->prepare("
            UPDATE projets 
            SET statut = 'valide', remarque = :remarque, note = :note 
            WHERE id = :id_projet
        ");
        $stmt->execute([
            'remarque' => $remarque,
            'note' => $note,
            'id_projet' => $id_projet
        ]);

        // 2. Ajouter dans la table des évaluations
        $stmt2 = $pdo->prepare("
            INSERT INTO evaluations (id_projet, id_enseignant, remarque, note) 
            VALUES (:id_projet, :id_enseignant, :remarque, :note)
        ");
        $stmt2->execute([
            'id_projet' => $id_projet,
            'id_enseignant' => $id_enseignant,
            'remarque' => $remarque,
            'note' => $note
        ]);

        // 3. Redirection
        header("Location: projets.php?success=1");
        exit;
    } catch (PDOException $e) {
        die("Erreur lors de la validation : " . $e->getMessage());
    }
}
?>
