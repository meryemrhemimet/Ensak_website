<?php
session_start();
require_once 'connexion.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Projet non spécifié.");
}

$id_projet = $_GET['id'];

// Récupérer les détails du projet
$query = "
SELECT 
    projets.*, 
    modules.nom_module, 
    modules.semestre,
    users.nom AS nom_etudiant, 
    users.prenom AS prenom_etudiant
FROM projets
JOIN etudiants ON projets.id_etudiant = etudiants.id
JOIN users ON etudiants.id = users.id
JOIN modules ON projets.id_module = modules.id
WHERE projets.id = :id_projet
";

$stmt = $pdo->prepare($query);
$stmt->execute(['id_projet' => $id_projet]);
$projet = $stmt->fetch();

if (!$projet) {
    die("Projet introuvable.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du projet</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/details.css">

</head>
<body>

<!-- Top navigation -->
<div class="top-nav py-2 px-4" style="display: flex; justify-content: space-between; align-items: center;">
        <div class="logo">
            <a href="https://ensa.uit.ac.ma/" target="_blank" rel="noopener noreferrer">
                <img src="images/ensaklogo.png" alt="Logo ENSA Kénitra">
            </a>
        </div >
    <a class="retour" href="projets.php" style="white-space: nowrap;">Retour à la liste</a>
</div>

<div class="container mt-4">
    <h2 class="mb-4"><b>Détails du projet</b></h2>
<div class="form-section">

    <div class="carte-projet">
       <h3><?= htmlspecialchars($projet['titre']) ?></h3>
       <div class="badges">
            <span class="badge bg-secondary"><?= htmlspecialchars($projet['nom_module']) ?></span>
            <span class="badge bg-secondary"><?= htmlspecialchars($projet['type']) ?></span>
            <span class="badge <?= $projet['statut'] === 'valide' ? 'bg-success' : ($projet['statut'] === 'refuse' ? 'bg-danger' : 'bg-warning') ?>">
                <?= ucfirst($projet['statut']) ?>
            </span>
        </div><hr>

        <div>
            <p class="section-titre">Description</p>
            <p><?= nl2br(htmlspecialchars($projet['description'])) ?></p><hr>

            <p class="section-titre">Informations sur le projet</p>
            <p><strong>Étudiant :</strong><?= htmlspecialchars($projet['prenom_etudiant'] . ' ' . $projet['nom_etudiant']) ?></p>
            <p><strong>Année scolaire :</strong> <?= htmlspecialchars($projet['annee_scolaire']) ?></p>
            <p><strong>Semestre :</strong> <?= htmlspecialchars($projet['semestre']) ?></p><hr>

            <p class="section-titre">Fichiers</p>
            <div class="fichier">
            <?php if ($projet['fichier_rapport']) : ?>
                <i>📄</i><a href="<?= $projet['fichier_rapport'] ?>" target="_blank">Rapport</a><br>
            <?php endif; ?>
            <?php if ($projet['fichier_code']) : ?>
                <i>💻</i><a href="<?= $projet['fichier_code'] ?>" target="_blank">Code source</a><br>
            <?php endif; ?>
            <?php if ($projet['fichier_diapo']) : ?>
                <i>📊</i><a href="<?= $projet['fichier_diapo'] ?>" target="_blank">Diaporama</a><br>
            <?php endif; ?>
            <?php if (!$projet['fichier_rapport'] && !$projet['fichier_code'] && !$projet['fichier_diapo']) : ?>
                <em>Aucun fichier soumis.</em>
            <?php endif; ?>
            </div>
    </div><br><hr>





        <form method="post" action="valider.php">
            <input type="hidden" name="id_projet" value="<?= $projet['id'] ?>">
            <div class="mb-3">
                <label for="remarque" class="form-label">Remarque :</label>
                <textarea name="remarque" id="remarque" class="form-control" rows="4"><?= htmlspecialchars($projet['remarque'] ?? '') ?></textarea>
            </div>
            <div class="mb-3">
                <label for="note" class="form-label">Note (/20) :</label>
                <input type="number" name="note" id="note" class="form-control" value="<?= htmlspecialchars($projet['note'] ?? '') ?>" min="0" max="20" step="0.5">
            </div>
            <button type="submit" class="btn btn-success">Valider le projet</button>
        </form>

        <form method="post" action="refuser.php" class="mt-3">
            <input type="hidden" name="id_projet" value="<?= $projet['id'] ?>">
            <div class="mb-3">
                <label for="remarque_refus" class="form-label">Motif du refus :</label>
                <textarea name="remarque" id="remarque_refus" class="form-control" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-danger">Refuser le projet</button>
        </form>
    </div>
</div>

</body>
</html>
