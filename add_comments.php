<?php
session_start();


require_once 'cnx.php';

$connexion = new Connexion();
$pdo = $connexion->getConnexion();

if (!$pdo) {
    die("Erreur: Impossible de se connecter à la base de données.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_projet = isset($_POST['id_projet']) ? intval($_POST['id_projet']) : 0;
    $contenu = isset($_POST['contenu']) ? htmlspecialchars($_POST['contenu']) : '';

    if (empty($contenu) || $id_projet <= 0) {
        die("Le commentaire ne peut pas être vide et le projet doit être valide.");
    }

    /*
    // Identifier l'utilisateur connecté et son type
    if (isset($_SESSION['id_etudiants'])) {
        $auteur_id = $_SESSION['id_etudiants'];
        $auteur_type = 'etudiant';
    } elseif (isset($_SESSION['id_professeur'])) {
        $auteur_id = $_SESSION['id_professeur'];
        $auteur_type = 'professeur';
    } elseif (isset($_SESSION['id_encadrant'])) {
        $auteur_id = $_SESSION['id_encadrant'];
        $auteur_type = 'encadrant';
    } else {
        die("Utilisateur non identifié.");
    }
    */
    
    $auteur_id = 1; 
    $auteur_type = 'etudiant';
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO commentaires 
            (id_projet, contenu, date_commentaire, auteur_type, auteur_id) 
            VALUES (:id_projet, :contenu, NOW(), :auteur_type, :auteur_id)
        ");

        $stmt->execute([
            'id_projet' => $id_projet,
            'contenu' => $contenu,
            'auteur_type' => $auteur_type,
            'auteur_id' => $auteur_id
        ]);

        header("Location: view.php?id=" . $id_projet);
        exit();
    } catch (PDOException $e) {
        die("Erreur lors de l'ajout du commentaire : " . $e->getMessage());
    }
} else {
    header("Location: view.php");
    exit();
}

?>