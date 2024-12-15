<?php
session_start();
include('includes/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $connection_id = $_POST['connection_id'];

    // Mettre à jour le statut de la connexion
    $sql_update = "UPDATE connections SET status='accepted' WHERE user_id='$connection_id' AND connection_id='$user_id'";
    if ($conn->query($sql_update) === TRUE) {
        // Ajouter la relation dans l'autre sens
        $sql_insert = "INSERT INTO connections (user_id, connection_id, status) VALUES ('$user_id', '$connection_id', 'accepted')";
        $conn->query($sql_insert);

        header("Location: network.php?success=1");
    } else {
        echo "Erreur : " . $conn->error;
    }
}
?>

<?php
session_start();
include('includes/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $connection_id = $_POST['connection_id'];

    // Accepter la demande
    $sql_update = "UPDATE connections SET status = 'accepted' WHERE user_id = '$connection_id' AND connection_id = '$user_id'";
    if ($conn->query($sql_update) === TRUE) {
        // Ajouter la relation dans l'autre sens
        $sql_insert = "INSERT INTO connections (user_id, connection_id, status) VALUES ('$user_id', '$connection_id', 'accepted')";
        $conn->query($sql_insert);

        header("Location: network.php?success=1");
    } else {
        echo "Erreur : " . $conn->error;
    }
}
?>
