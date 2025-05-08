<?php
session_start();
require_once 'cnx.php';

// Debug: Activer les erreurs
ini_set('display_errors', 1);
error_reporting(E_ALL);

// FORCEZ L'ID POUR TEST (à supprimer après)
$_SESSION['id_etudiants'] = 1;

// 1. Vérifier l'authentification
if (!isset($_SESSION['id_etudiants'])) {
    header('HTTP/1.0 403 Forbidden');
    die("Accès refusé: Non connecté");
}

// 2. Vérifier les paramètres
if (!isset($_GET['file']) || !isset($_GET['id'])) {
    header('HTTP/1.0 400 Bad Request');
    die("Paramètres manquants");
}

// 3. Nettoyer les entrées
$file_name = basename($_GET['file']); // Sécurité contre les chemins relatifs
$projet_id = (int)$_GET['id'];

// 4. Chemin complet (adaptez selon votre structure)
$base_path = __DIR__.'/uploads/';
$chemin_complet = $base_path.$file_name;

// Debug: Afficher le chemin
error_log("Tentative de téléchargement: ".$chemin_complet);

// 5. Vérifier l'existence du fichier
if (!file_exists($chemin_complet)) {
    header('HTTP/1.0 404 Not Found');
    die("Fichier non trouvé: ".$file_name);
}

try {
    // 6. Vérifier les droits en base (optionnel pour test)
    $db = new Connexion();
    $pdo = $db->getConnexion();
    
    // 7. En-têtes pour le téléchargement
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="'.$file_name.'"');
    header('Content-Length: ' . filesize($chemin_complet));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    
    // 8. Nettoyer les buffers
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    // 9. Envoyer le fichier
    readfile($chemin_complet);
    exit;
    
} catch (Exception $e) {
    error_log("Erreur: ".$e->getMessage());
    header('HTTP/1.0 500 Internal Server Error');
    die("Erreur serveur");
}
?>