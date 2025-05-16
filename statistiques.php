<?php
session_start();
require_once 'db.php';

// Nombre total de projets
$nb_total = $db->query("SELECT COUNT(*) FROM projets")->fetchColumn();

// Projets par statut
$query_statut = $db->query("
    SELECT statut, COUNT(*) AS total
    FROM projets
    GROUP BY statut
");
$stats_statut = $query_statut->fetchAll(PDO::FETCH_ASSOC);

// Projets par encadrant
$query_encadrant = $db->query("
    SELECT e.nom, COUNT(p.id) AS total
    FROM projets p
    JOIN encadrant e ON p.encadrant_id = e.id
    GROUP BY e.nom
");
$stats_encadrant = $query_encadrant->fetchAll(PDO::FETCH_ASSOC);

// Projets par filière (en lisant la description)
$filieres = ['genie informatique', 'genie industriel', 'genie électrique', 'Mécatronique', 'Réseaux', 'BIEE'];
$stats_filiere = [];
foreach ($filieres as $filiere) {
    $stmt = $db->prepare("SELECT COUNT(*) FROM projets WHERE description LIKE ?");
    $stmt->execute(["%$filiere%"]);
    $stats_filiere[] = ['nom' => $filiere, 'total' => $stmt->fetchColumn()];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Statistiques - Admin</title>
    <link rel="stylesheet" href="etudiants.css">
    <style>
        .stat-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .stat-table th, .stat-table td {
            padding: 10px;
            border-bottom: 1px solid #e0e0e0;
            text-align: left;
        }
        .stat-table th {
            background-color: #f9f9f9;
            color: #2c3e50;
        }
        .stat-icon {
            font-size: 26px;
            margin-right: 10px;
        }
        .no-data {
            font-style: italic;
            color: #888;
        }
    </style>
</head>
<body>
<div class="top-bar">
    <div class="logo">
        <img src="logo_ensa.png" alt="ENSA Logo">
    </div>
    <div class="top-icons">
        <a href="#" target="_blank"><img src="ig-icon.png" alt="Instagram"></a>
        <a href="#" target="_blank"><img src="link-icone.png" alt="Lien utile"></a>
        <div class="dropdown">
            <button class="dropbtn">&#9776;</button>
            <div class="dropdown-content">
            <a href="index.php">Accueil</a>
            <a href="dashboard.php">Projets</a>
            <a href="statistiques.php">Statistiques</a>
            <a href="gestion_utilisateurs.php">Utilisateurs</a>
            <a href="logout.php">Déconnexion</a>
            </div>
        </div>
    </div>
</div>

<header>
    <h1> Statistiques des projets étudiants</h1>
</header>

<div class="container">

    <div class="project-card">
        <h2><span class="stat-icon">📊</span>Nombre total de projets</h2>
        <p><strong><?= $nb_total ?></strong> projets enregistrés dans le système.</p>
    </div>

    <div class="project-card">
        <h2><span class="stat-icon">📂</span>Projets par statut</h2>
        <?php if (count($stats_statut) > 0): ?>
            <table class="stat-table">
                <thead><tr><th>Statut</th><th>Nombre</th></tr></thead>
                <tbody>
                    <?php foreach ($stats_statut as $row): ?>
                        <tr><td><?= htmlspecialchars(ucfirst($row['statut'])) ?></td><td><?= $row['total'] ?></td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">Aucun projet classé par statut.</p>
        <?php endif; ?>
    </div>

    
    <div class="project-card">
        <h2><span class="stat-icon">🗓️</span>Projets par année</h2>
        <?php
        $annees = ['1ère année', '2ème année', '3ème année', '4ème année', '5ème année'];
        $stats_annee = [];
        foreach ($annees as $an) {
            $stmt = $db->prepare("SELECT COUNT(*) FROM projets WHERE description LIKE ?");
            $stmt->execute(["%$an%"]);
            $stats_annee[] = ['nom' => $an, 'total' => $stmt->fetchColumn()];
        }
        ?>
        <table class="stat-table">
            <thead><tr><th>Année</th><th>Nombre</th></tr></thead>
            <tbody>
                <?php foreach ($stats_annee as $row): ?>
                    <tr><td><?= htmlspecialchars($row['nom']) ?></td><td><?= $row['total'] ?></td></tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="project-card">
        <h2><span class="stat-icon">🎓</span>Projets par filière</h2>
        <?php if (count($stats_filiere) > 0): ?>
            <table class="stat-table">
                <thead><tr><th>Filière</th><th>Nombre</th></tr></thead>
                <tbody>
                    <?php foreach ($stats_filiere as $row): ?>
                        <tr><td><?= htmlspecialchars(ucfirst($row['nom'])) ?></td><td><?= $row['total'] ?></td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">Aucune donnée par filière.</p>
        <?php endif; ?>
    </div>

    <div class="project-card">
        <h2><span class="stat-icon">👨‍🏫</span>Projets par encadrant</h2>
        <?php if (count($stats_encadrant) > 0): ?>
            <table class="stat-table">
                <thead><tr><th>Encadrant</th><th>Nombre</th></tr></thead>
                <tbody>
                    <?php foreach ($stats_encadrant as $row): ?>
                        <tr><td><?= htmlspecialchars($row['nom']) ?></td><td><?= $row['total'] ?></td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">Aucun projet associé à un encadrant.</p>
        <?php endif; ?>
    </div>

</div>

<script>
document.querySelector(".dropbtn").addEventListener("click", function () {
    document.querySelector(".dropdown-content").classList.toggle("show");
});
window.addEventListener("click", function (e) {
    if (!e.target.matches('.dropbtn')) {
        document.querySelector(".dropdown-content").classList.remove("show");
    }
});
</script>
</body>
</html>

