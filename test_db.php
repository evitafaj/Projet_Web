<?php
include('includes/db_connect.php');

if ($conn) {
    echo "Connexion réussie à la base de données.";
} else {
    echo "Erreur : " . $conn->connect_error;
}
?>

