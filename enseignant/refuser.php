<?php
session_start();
require_once 'connexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_projet = $_POST['id_projet'];
    $remarque = $_POST['remarque'] ?? null;

    if (!$id_projet) {
        die("Projet non spécifié.");
    }

    // Exemple statique : ID enseignant
    $id_enseignant = 3; // remplace par $_SESSION['id'] si disponible

    try {
        // 1. Mettre à jour le projet (seulement statut et remarque, PAS de note)
        $stmt = $pdo->prepare("
            UPDATE projets 
            SET statut = 'refuse', remarque = :remarque, note = NULL
            WHERE id = :id_projet
        ");
        $stmt->execute([
            'remarque' => $remarque,
            'id_projet' => $id_projet
        ]);

        // 2. Ajouter dans la table evaluations (note = NULL)
        $stmt2 = $pdo->prepare("
            INSERT INTO evaluations (id_projet, id_enseignant, remarque, note) 
            VALUES (:id_projet, :id_enseignant, :remarque, NULL)
        ");
        $stmt2->execute([
            'id_projet' => $id_projet,
            'id_enseignant' => $id_enseignant,
            'remarque' => $remarque
        ]);

        // 3. Redirection avec succès
        header("Location: projets.php?refuse=1");
        exit;
    } catch (PDOException $e) {
        die("Erreur lors du refus : " . $e->getMessage());
    }
}
?>

