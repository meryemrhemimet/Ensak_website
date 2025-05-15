<?php
// connexion.php

$host = 'localhost';
$dbname = 'projet'; // nom de ta base de données
$username = 'root'; // change si tu as un autre utilisateur
$password = '';     // mot de passe, vide par défaut sous XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Pour activer les erreurs PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
