<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('includes/db_connect.php');

$user_id = $_SESSION['user_id'];

// Récupérer les événements actifs
$sql_events = "SELECT * FROM events WHERE is_active = TRUE ORDER BY event_date ASC";
$result_events = $conn->query($sql_events);

// Récupérer les notifications personnelles
$sql_notifications = "SELECT * FROM notifications WHERE user_id = '$user_id' ORDER BY created_at DESC";
$result_notifications = $conn->query($sql_notifications);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - ECE In</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <?php include('includes/header.php'); ?>

    <div class="container mt-5">
        <h2>Notifications</h2>
        <p>Suivez les événements et les activités des membres de votre réseau.</p>

        <!-- Section Événements -->
        <h3 class="mt-4">Événements à venir</h3>
        <?php if ($result_events->num_rows > 0): ?>
            <ul class="list-group mb-4">
                <?php while ($event = $result_events->fetch_assoc()): ?>
                    <li class="list-group-item">
                        <h5><?php echo htmlspecialchars($event['title']); ?></h5>
                        <p><?php echo htmlspecialchars($event['description']); ?></p>
                        <p><strong>Date :</strong> <?php echo htmlspecialchars($event['event_date']); ?></p>
                        <p><strong>Organisateur :</strong> <?php echo htmlspecialchars($event['organizer']); ?></p>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>Aucun événement à venir.</p>
        <?php endif; ?>

        <!-- Section Notifications personnelles -->
        <h3 class="mt-4">Notifications personnelles</h3>
        <?php if ($result_notifications->num_rows > 0): ?>
            <ul class="list-group">
                <?php while ($notification = $result_notifications->fetch_assoc()): ?>
                    <li class="list-group-item <?php echo $notification['is_read'] ? '' : 'list-group-item-warning'; ?>">
                        <p><?php echo htmlspecialchars($notification['content']); ?></p>
                        <p class="text-muted"><small>Reçue le : <?php echo htmlspecialchars($notification['created_at']); ?></small></p>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>Pas de nouvelles notifications.</p>
        <?php endif; ?>
    </div>

    <?php include('includes/footer.php'); ?>
</body>
</html>
