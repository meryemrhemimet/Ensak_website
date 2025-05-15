<?php
session_start();
require_once 'connexion.php';

// Simulation de session pour test (à remplacer par la vraie authentification)
// if (!isset($_SESSION['enseignant_nom'])) {
//     $_SESSION['enseignant_nom'] = 'Pr.benali'; /*remplacer la session par le nom de prof*/
// }

// // Récupération du nom de l’enseignant
// $nom = $_SESSION['enseignant_nom'];

// la vrai connexion
/*
// Sécurité : vérifier que l'utilisateur est connecté et est enseignant
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'enseignant') {
    header("Location: login.php");
    exit;
}

// Récupération des infos de l’enseignant connecté
$id_user = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT nom, prenom FROM users WHERE id = :id");
$stmt->execute(['id' => $id_user]);
$user = $stmt->fetch();

if (!$user) {
    die("Utilisateur non trouvé.");
}

$nom_complet = $user['prenom'] . ' ' . $user['nom'];
*/











// simulation de connexion
if (!isset($_SESSION['user_id'])) {
    // On prend n'importe quel enseignant depuis la base (ex : premier trouvé)
    $stmt = $pdo->prepare("SELECT id FROM users WHERE role = 'enseignant' LIMIT 1");
    $stmt->execute();
    $enseignant = $stmt->fetch();

    if ($enseignant) {
        $_SESSION['user_id'] = $enseignant['id'];
        $_SESSION['role'] = 'enseignant';
    } else {
        die("Aucun enseignant trouvé dans la base.");
    }
}

$id_user = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT nom, prenom FROM users WHERE id = :id");
$stmt->execute(['id' => $id_user]);
$user = $stmt->fetch();

$nom_complet = $user['prenom'] . ' ' . $user['nom'];




// Requête pour les statistiques
$totalQuery = $pdo->query("SELECT COUNT(*) FROM projets");
$total = $totalQuery->fetchColumn();

$attenteQuery = $pdo->query("SELECT COUNT(*) FROM projets WHERE statut = 'en_attente'");
$en_attente = $attenteQuery->fetchColumn();

$valideQuery = $pdo->query("SELECT COUNT(*) FROM projets WHERE statut = 'validé'");
$valides = $valideQuery->fetchColumn();

$refuseQuery = $pdo->query("SELECT COUNT(*) FROM projets WHERE statut = 'refusé'");
$refuses = $refuseQuery->fetchColumn();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Enseignant</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dashboard.css">

</head>
<body>

<nav class="py-2 px-3 d-flex justify-content-between align-items-center border-bottom">
     <div class="logo">
            <a href="https://ensa.uit.ac.ma/" target="_blank" rel="noopener noreferrer">
                <img src="images/ensaklogo.png" alt="Logo ENSA Kénitra">
            </a>
        </div>
    <div>
    <div class="top-icons">
            <a href="https://www.instagram.com/ensak.official" target="_blank"><img src="images/ig-icon.png" alt="Instagram"></a>
            <a href="https://www.linkedin.com/company/ensa-kenitra-official?originalSubdomain=ma" target="_blank"><img src="images/link-icone.png" alt="LinkedIn"></a>
            <div class="dropdown">
                <button class="dropbtn">☰</button>
                <div class="dropdown-content">
                    <a href="dashboard.php">Mon Profil</a>
                    <a href="logout.php">Déconnexion</a>
                </div>
            </div>
        </div>
       
    </div>
</nav>

<div class="top-nav py-2 px-4" style="display: flex; justify-content: space-between; align-items: center;">
    <h3 style="margin: 0; flex-grow: 1; text-align: center;"><b>Espace Enseignant</b></h3>
</div>

<div class="container mt-4">
    <h2 class="mb-4">Bienvenue, <?= htmlspecialchars($nom_complet) ?> !</h2>

    <div class="row">
        <div class="col-md-3">
            <div class="stat-card card-total text-center">
                <h5>Total Projets</h5>
                <h2><?= $total ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card card-attente text-center">
                <h5>En Attente</h5>
                <h2><?= $en_attente ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card card-valide text-center">
                <h5>Validés</h5>
                <h2><?= $valides ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card card-refuse text-center">
                <h5>Refusés</h5>
                <h2><?= $refuses ?></h2>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex gap-3">
        <a href="projets.php" class="btn btn-detail">Voir tous les projets</a>
        
        
    </div>
</div>
<script>
document.querySelector(".dropbtn").addEventListener("click", function() {
    document.querySelector(".dropdown-content").classList.toggle("show");
});

window.addEventListener("click", function(event) {
    if (!event.target.matches('.dropbtn')) {
        const dropdowns = document.querySelectorAll(".dropdown-content");
        dropdowns.forEach(function(dropdown) {
            dropdown.classList.remove('show');
        });
    }
});
</script>

</body>
</html>
