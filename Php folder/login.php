<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Lien vers vos styles -->
</head>
<body>
    <h2>Connexion</h2>
    <form action="login_process.php" method="POST">
        <label for="username">Nom d'utilisateur ou Email :</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Se connecter</button>
    </form>
    <p>Pas encore inscrit ? <a href="register.php">Créez un compte</a></p>
</body>
</html>
