<?php
session_start();
include('includes/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $connection_id = $_POST['connection_id'];

    // Vérifier si la connexion existe déjà
    $sql_check = "SELECT * FROM connections WHERE user_id='$user_id' AND connection_id='$connection_id'";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows == 0) {
        // Ajouter une nouvelle demande
        $sql_insert = "INSERT INTO connections (user_id, connection_id, status) VALUES ('$user_id', '$connection_id', 'pending')";
        if ($conn->query($sql_insert) === TRUE) {
            header("Location: network.php?success=1");
        } else {
            echo "Erreur : " . $conn->error;
        }
    } else {
        header("Location: network.php?error=1");
    }
}
?>

<?php
session_start();
include('includes/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $connection_id = $_POST['connection_id'];

    // Vérifier si une connexion existe déjà
    $sql_check = "SELECT * FROM connections WHERE (user_id = '$user_id' AND connection_id = '$connection_id') OR (user_id = '$connection_id' AND connection_id = '$user_id')";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows == 0) {
        // Ajouter une nouvelle demande
        $sql_insert = "INSERT INTO connections (user_id, connection_id, status) VALUES ('$user_id', '$connection_id', 'pending')";
        if ($conn->query($sql_insert) === TRUE) {
            header("Location: search_friends.php?success=1");
        } else {
            echo "Erreur : " . $conn->error;
        }
    } else {
        header("Location: search_friends.php?error=1");
    }
}
?>
