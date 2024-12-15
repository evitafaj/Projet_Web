<?php
include('includes/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $institution = $conn->real_escape_string($_POST['institution']);
    $degree = $conn->real_escape_string($_POST['degree']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $sql = "INSERT INTO education (user_id, institution, degree, start_date, end_date)
            VALUES ('$user_id', '$institution', '$degree', '$start_date', '$end_date')";

    if ($conn->query($sql) === TRUE) {
        header("Location: profile.php");
    } else {
        echo "Erreur : " . $conn->error;
    }
}
?>
