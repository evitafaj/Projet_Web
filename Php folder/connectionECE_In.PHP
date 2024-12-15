<?php
// Paramètres de connexion
$host = 'localhost'; // Serveur
$port = '8888';      // Port par défaut
$dbName = 'ECE_In'; // Nom de la base de données
$username = 'root';  // Nom d'utilisateur (par défaut dans MAMP)
$password = 'root';  // Mot de passe (par défaut dans MAMP)

try {
    // Création de la connexion PDO
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbName", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Activer les erreurs PDO
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Mode de récupération
    echo "Connexion réussie à la base de données.";
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
