<?php
session_start();

// Vider les données de session
$_SESSION = array();

// Supprimer le cookie de session si activé
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Détruire la session
session_destroy();

// Redirection vers la page de connexion ou accueil
header("Location: index.php"); // modifie par index.php si besoin
exit;
?>
