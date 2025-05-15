<?php
session_start();
session_unset();   // Supprime toutes les variables de session
session_destroy(); // Détruit la session

// Redirige vers la page de connexion ou d'accueil
header("Location: login.php"); // tu peux remplacer par index.php ou autre
exit;
?>