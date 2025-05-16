<?php
session_start();
require_once 'db.php';
// require_once 'auth/check_admin.php'; // active si tu veux restreindre l'accès

$utilisateurs = $db->query("SELECT id, nom, prenom, email FROM etudiants ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">


<head>
    <meta charset="UTF-8">
    <title>Gestion des étudiants</title>
    <link rel="stylesheet" href="etudiants.css">
    <style>
        .user-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .user-table th, .user-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }
        .user-table th {
            background-color: #f9f9f9;
            color: #2c3e50;
        }
        .btn-role {
            background-color: #f39c12;
            color: white;
        }
        .btn-role:hover {
            background-color: #e67e22;
        }
        .btn-delete {
            background-color: #e74c3c;
            color: white;
        }
        .btn-delete:hover {
            background-color: #c0392b;
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
    <h1> Gestion des utilisateurs</h1>
</header>

<div class="container">
    <div class="project-card">
        <h2>📋 Liste des utilisateurs</h2>
        <?php if (count($utilisateurs) > 0): ?>
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($utilisateurs as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['nom']) ?></td>
                            <td><?= htmlspecialchars($user['prenom']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td>
                                <a href="modifier.php?id=<?= $user['id'] ?>" class="btn btn-edit">✏️ Modifier</a>
                                <a href="supprimer.php?id=<?= $user['id'] ?>" class="btn btn-delete" onclick="return confirm('Confirmer la suppression ?');">🗑️ Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">Aucun étudiant enregistré.</p>
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

