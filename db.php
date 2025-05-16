<?php
// /config/db.php

$host = 'localhost';           // ou 127.0.0.1
$dbname = 'projet';  // nom de la base
$username = 'root';           // utilisateur MySQL
$password = '';               // mot de passe MySQL (à adapter)

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
