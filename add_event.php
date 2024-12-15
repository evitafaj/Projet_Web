<?php
session_start();
include('includes/db_connect.php');

// Vérifier si l'utilisateur est un administrateur
if ($_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $event_date = $conn->real_escape_string($_POST['event_date']);
    $organizer = $conn->real_escape_string($_POST['organizer']);

    $sql_insert = "INSERT INTO events (title, description, event_date, organizer) VALUES ('$title', '$description', '$event_date', '$organizer')";
    if ($conn->query($sql_insert) === TRUE) {
        header("Location: notifications.php?success=1");
    } else {
        echo "Erreur : " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Événement - ECE In</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <?php include('includes/header.php'); ?>

    <div class="container mt-5">
        <h2>Ajouter un Événement</h2>
        <form action="add_event.php" method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Titre de l'événement</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label for="event_date" class="form-label">Date de l'événement</label>
                <input type="date" class="form-control" id="event_date" name="event_date" required>
            </div>
            <div class="mb-3">
                <label for="organizer" class="form-label">Organisateur</label>
                <input type="text" class="form-control" id="organizer" name="organizer">
            </div>
            <button type="submit" class="btn btn-primary">Publier l'événement</button>
        </form>
    </div>

    <?php include('includes/footer.php'); ?>
</body>
</html>
