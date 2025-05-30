<?php 
session_start();
require_once 'cnx.php';

$annees = ['1ère année', '2ème année', '3ème année', '4ème année', '5ème année'];
$filières = [
    'genie informatique' => 'info',
    'genie industriel' => 'indus',
    'genie électrique' => 'electrique',
    'Mécatronique' => 'mecatronique',
    'Réseaux' => 'reseau',
    'BIEE' => 'batiments'
];

function getProjets($annee = null, $filiere = null) {
    global $db;
    $sql = "SELECT p.id, p.titre, p.description, p.date_creation AS annee, p.statut,
                   CONCAT(et.prenom, ' ', et.nom) AS etudiant, 
                   enc.nom AS encadrant
            FROM projets p
            JOIN etudiants et ON p.etudiant_id = et.id
            JOIN encadrant enc ON p.encadrant_id = enc.id
            WHERE 1=1";

    $params = [];
    if ($annee) {
        $sql .= " AND p.description LIKE :annee";
        $params[':annee'] = '%' . $annee . '%';
    }
    if ($filiere) {
        $sql .= " AND p.description LIKE :filiere";
        $params[':filiere'] = '%' . $filiere . '%';
    }

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord - Projets</title>
    <link rel="stylesheet" href="css/etudiants.css">
    <style>
        
    .filiere-grid {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
        margin-top: 20px;
    }
    .filiere-card {
        width: 250px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        text-align: center;
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .filiere-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
    }
    .filiere-card img {
        width: 100%;
        height: auto;
        object-fit: cover;
        border-bottom: 1px solid #eee;
    }
    .filiere-card h3 {
        margin: 15px 0 5px;
        color: #002c84;
        font-size: 18px;
    }
    .filiere-card p {
        color: #666;
        font-size: 14px;
        margin-bottom: 15px;
    }
    .accordion-btn {
        width: 100%;
        padding: 12px 20px;
        text-align: left;
        font-size: 16px;
        background-color: #002c84;
        color: white;
        border: none;
        cursor: pointer;
        outline: none;
        transition: background-color 0.3s;
        margin-top: 10px;
        border-radius: 6px;
    }
    .accordion-btn:hover {
        background-color: #0040c1;
    }
    .panel {
        display: none;
        padding: 10px 15px;
        background-color: #f9f9f9;
        border-left: 3px solid #002c84;
        border-radius: 4px;
        margin-bottom: 10px;
    }
    .project-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        padding: 15px;
        margin-top: 15px;
    }
    .project-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .project-title {
        font-size: 18px;
        font-weight: bold;
    }
    .project-meta span {
        display: block;
        font-size: 14px;
        color: #555;
    }
    .project-description {
        margin-top: 10px;
        font-size: 14px;
        color: #333;
    }
    .export-actions {
    text-align: right;
    margin-bottom: 20px;
}

.export-actions {
    text-align: right;
    margin-top: 40px;
    margin-bottom: 20px;
}

.btn-export {
    padding: 10px 18px;
    font-size: 14px;
    border-radius: 6px;
    text-decoration: none;
   
    margin-right: 37px;
    font-weight: bold;
    display: inline-block;
}

.btn-pdf {
    background-color: #d35400;
    color: white;
}

.btn-excel {
    background-color: #27ae60;
    color: white;
}

.btn-pdf:hover {
    background-color: #b84300;
}

.btn-excel:hover {
    background-color: #1e944b;
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
    <h1> Projets des étudiants</h1>
</header>

<div class="container">


    <?php foreach ($annees as $annee): ?>
        <button class="accordion-btn" onclick="togglePanel('panel<?= md5($annee) ?>')"><?= $annee ?></button>
        <div class="panel" id="panel<?= md5($annee) ?>">
            <?php if (in_array($annee, ['3ème année', '4ème année', '5ème année'])): ?>
                <div class="filiere-grid">
                    <?php foreach ($filières as $filiere => $icon): ?>
                        <div class="filiere-card" onclick="togglePanel('<?= md5($annee . $filiere) ?>')">
                            <img src="icons/<?= $icon ?>.jpg" alt="<?= $icon ?>">
                            <h3><?= $filiere ?></h3>
                            <p>Voir les projets</p>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php foreach ($filières as $filiere => $icon): ?>
                    <div class="panel" id="<?= md5($annee . $filiere) ?>">
                        <?php $projets = getProjets($annee, $filiere); ?>
                        <?php if (count($projets) > 0): ?>
                            <?php foreach ($projets as $p): ?>
                                <div class="project-card">
                                    <div class="project-header">
                                        <h3 class="project-title"><?= htmlspecialchars($p['titre']) ?></h3>
                                        <span class="project-status <?= $p['statut'] === 'valide' ? 'status-validated' : ($p['statut'] === 'refuse' ? 'status-pending' : '') ?>">
                                            <?= ucfirst($p['statut']) ?>
                                        </span>
                                    </div>
                                    <div class="project-meta">
                                        <span><strong>Étudiant :</strong> <?= $p['etudiant'] ?></span>
                                        <span><strong>Encadrant :</strong> <?= $p['encadrant'] ?></span>
                                        <span><strong>Année :</strong> <?= $p['annee'] ?></span>
                                    </div>
                                    <p class="project-description"><?= htmlspecialchars($p['description']) ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Aucun projet trouvé.</p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <?php $projets = getProjets($annee, null); ?>
                <?php if (count($projets) > 0): ?>
                    <?php foreach ($projets as $p): ?>
                        <div class="project-card">
                            <div class="project-header">
                                <h3 class="project-title"><?= htmlspecialchars($p['titre']) ?></h3>
                                <span class="project-status <?= $p['statut'] === 'valide' ? 'status-validated' : ($p['statut'] === 'refuse' ? 'status-pending' : '') ?>">
                                    <?= ucfirst($p['statut']) ?>
                                </span>
                            </div>
                            <div class="project-meta">
                                <span><strong>Étudiant :</strong> <?= $p['etudiant'] ?></span>
                                <span><strong>Encadrant :</strong> <?= $p['encadrant'] ?></span>
                                <span><strong>Année :</strong> <?= $p['annee'] ?></span>
                            </div>
                            <p class="project-description"><?= htmlspecialchars($p['description']) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun projet trouvé.</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
<div class="export-actions">
    <a href="export_pdf.php" class="btn-export btn-pdf">📄 Exporter tout en PDF</a>
    <a href="export_excel.php" class="btn-export btn-excel">📥 Exporter tout en Excel</a>
</div>
<script>
function togglePanel(id) {
    const panel = document.getElementById(id);
    panel.style.display = panel.style.display === "block" ? "none" : "block";
}
</script>

</body>
</html>

<style>
.dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    background-color: white;
    min-width: 180px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    z-index: 1001;
    border-radius: 6px;
    overflow: hidden;
    border: 1px solid #e0e0e0;
}
.dropdown-content a {
    color: #333;
    padding: 12px 20px;
    display: block;
    text-decoration: none;
    font-size: 15px;
    transition: all 0.3s ease;
    border-bottom: 1px solid #f0f0f0;
}
.dropdown-content a:last-child {
    border-bottom: none;
}
.dropdown-content a:hover {
    background-color: #f8f8f8;
    color: rgb(0, 44, 132);
}
.show {
    display: block;
}
</style>

<script>
document.querySelector(".dropbtn").addEventListener("click", function () {
    document.querySelector(".dropdown-content").classList.toggle("show");
});
window.addEventListener("click", function (e) {
    if (!e.target.matches('.dropbtn')) {
        document.querySelectorAll(".dropdown-content").forEach(drop => drop.classList.remove("show"));
    }
});
</script>



