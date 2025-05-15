<?php
session_start();
require_once 'connexion.php';

// Simulation session enseignant
// if (!isset($_SESSION['enseignant_nom'])) {
//     $_SESSION['enseignant_nom'] = 'Pr. Benali';
// }

// $nom = $_SESSION['enseignant_nom'];

// la vrai connexion
/*
session_start();
require_once 'connexion.php';

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


// ⚠️ SIMULATION TEMPORAIRE D'UNE CONNEXION D'ENSEIGNANT
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



// Traitement des filtres
$module = $_GET['module'] ?? '';
$annee = $_GET['annee'] ?? '';
$statut = $_GET['statut'] ?? '';

$query = "
SELECT 
    projets.id,
    projets.titre,
    projets.annee_scolaire,
    projets.statut,
    modules.nom_module,
    modules.semestre,
    users.nom AS nom_etudiant,
    users.prenom AS prenom_etudiant
FROM projets
JOIN etudiants ON projets.id_etudiant = etudiants.id
JOIN users ON etudiants.id = users.id
JOIN modules ON projets.id_module = modules.id
WHERE 1=1
";

$params = [];

if ($module) {
    $query .= " AND modules.nom_module LIKE :module";
    $params['module'] = "%$module%";
}
if ($annee) {
    $query .= " AND projets.annee_scolaire = :annee";
    $params['annee'] = $annee;
}
if ($statut) {
    $query .= " AND projets.statut = :statut";
    $params['statut'] = $statut;
}


$stmt = $pdo->prepare($query);
$stmt->execute($params);
$projets = $stmt->fetchAll();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des projets</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/projets.css">

</head>
<body>
<!-- Barre supérieure --> 
<div class="top-nav py-2 px-4" style="display: flex; justify-content: space-between; align-items: center;">
    <div class="logo">
        <a href="https://ensa.uit.ac.ma/" target="_blank" rel="noopener noreferrer">
                <img src="images/ensaklogo.png" alt="Logo ENSA Kénitra">
        </a>
    </div >
    <a class="retour" href="dashboard.php" style="white-space: nowrap;">Retour à l'accueil</a>
</div>


<div class="container mt-4">
    <h2 class="mb-4"><b>Liste des projets étudiants</b></h2>

    <form method="get" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="module" class="form-control" placeholder="Filtrer par module" value="<?= htmlspecialchars($module) ?>">
        </div>
        <div class="col-md-3">
            <input type="text" name="annee" class="form-control" placeholder="Filtrer par année" value="<?= htmlspecialchars($annee) ?>">
        </div>
        <div class="col-md-3">
            <select name="statut" class="form-select">
                <option value="">Tous les statuts</option>
                <option value="en attente" <?= $statut == 'en attente' ? 'selected' : '' ?>>En attente</option>
                <option value="validé" <?= $statut == 'validé' ? 'selected' : '' ?>>Validé</option>
                <option value="refusé" <?= $statut == 'refusé' ? 'selected' : '' ?>>Refusé</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100 custom-blue">Appliquer les filtres</button>
        </div>
    </form>
    
    <table class="table table-bordered table-striped">
        <thead class="">
            <tr>
                <th>Titre du projet</th>
                <th>Étudiant</th>
                <th>Module</th>
                <th>Semestre</th>
                <th>Année</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($projets) > 0): ?>
                <?php foreach ($projets as $projet): ?>
                    <tr>
                        <td><?= htmlspecialchars($projet['titre']) ?></td>
                        <td><?= htmlspecialchars($projet['prenom_etudiant'] . ' ' . $projet['nom_etudiant']) ?></td>
                        <td><?= htmlspecialchars($projet['nom_module']) ?></td>
                        <td><?= htmlspecialchars($projet['semestre']) ?></td>
                        <td><?= htmlspecialchars($projet['annee_scolaire']) ?></td>
                        <td>
                            <span class="statut <?= str_replace(' ', '-', $projet['statut']) ?>">
                                <?= ucfirst($projet['statut']) ?>
                            </span>
                        </td>
                        <td>
                            <a href="projet_details.php?id=<?= $projet['id'] ?>"  class="btn btn-detail">Voir détails</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center">Aucun projet trouvé.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>


</div>

</body>
</html>
