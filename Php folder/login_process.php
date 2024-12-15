<?php
session_start(); // Démarre une session pour suivre l'utilisateur
include 'db_connection.php'; // Inclure le fichier de connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Rechercher l'utilisateur par nom d'utilisateur ou email
    $sql = "SELECT * FROM users WHERE username = :username OR email = :username LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Connexion réussie, créer une session utilisateur
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: dashboard.php'); // Redirige vers une page d'accueil
        exit;
    } else {
        // Échec de connexion
        echo "<p>Nom d'utilisateur ou mot de passe incorrect.</p>";
    }
}
?>
