<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ECE In</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <header class="bg-primary text-white p-3">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-dark">
                <a class="navbar-brand" href="profile.php">ECE In</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="profile.php">Profil</a></li>
                        <li class="nav-item"><a class="nav-link" href="network.php">Mon Réseau</a></li>
                        <li class="nav-item"><a class="nav-link" href="notifications.php">Notifications</a></li>
                        <li class="nav-item"><a class="nav-link" href="jobs.php">Emplois</a></li>
                        <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Déconnexion</a></li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>
    <div class="container mt-4">
