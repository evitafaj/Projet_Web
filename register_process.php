<?php
include 'db_connection.php'; // Inclure le fichier de connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hacher le mot de passe
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insérer l'utilisateur dans la base de données
    $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword,
        ]);
        echo "<p>Inscription réussie ! <a href='login.php'>Connectez-vous ici</a>.</p>";
    } catch (PDOException $e) {
        if ($e->errorInfo[1] === 1062) { // 1062 = erreur de duplication
            echo "<p>Nom d'utilisateur ou email déjà pris.</p>";
        } else {
            echo "<p>Erreur : " . $e->getMessage() . "</p>";
        }
    }
}
?>
