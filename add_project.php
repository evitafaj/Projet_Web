<?php
include('includes/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);

    $sql = "INSERT INTO projects (user_id, title, description)
            VALUES ('$user_id', '$title', '$description')";

    if ($conn->query($sql) === TRUE) {
        header("Location: profile.php");
    } else {
        echo "Erreur : " . $conn->error;
    }
}
?>
