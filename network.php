<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('includes/db_connect.php');

$user_id = $_SESSION['user_id'];

// Récupérer les connexions acceptées
$sql_connections = "
    SELECT users.id, users.first_name, users.last_name, users.profile_picture, users.role
    FROM connections
    JOIN users ON connections.connection_id = users.id
    WHERE connections.user_id = '$user_id' AND connections.status = 'accepted'
";
$result_connections = $conn->query($sql_connections);

// Récupérer les amis d'amis
$sql_friends_of_friends = "
    SELECT DISTINCT u.id, u.first_name, u.last_name, u.profile_picture, u.role
    FROM connections c1
    JOIN connections c2 ON c1.connection_id = c2.user_id
    JOIN users u ON c2.connection_id = u.id
    WHERE c1.user_id = '$user_id' AND c1.status = 'accepted' AND c2.status = 'accepted' AND u.id != '$user_id'
";
$result_friends_of_friends = $conn->query($sql_friends_of_friends);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Réseau - ECE In</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <?php include('includes/header.php'); ?>
<!-- Contenu de la page -->
<?php include('includes/footer.php'); ?>

    <div class="container mt-5">
        <h2>Mon Réseau</h2>

        <!-- Liste des connexions -->
        <h3>Mes Connexions</h3>
        <div class="row">
            <?php if ($result_connections->num_rows > 0): ?>
                <?php while ($connection = $result_connections->fetch_assoc()): ?>
                    <div class="col-md-4 text-center mb-3">
                        <a href="profile.php?user_id=<?php echo $connection['id']; ?>">
                            <img src="<?php echo $connection['profile_picture'] ?: 'images/default-profile.png'; ?>" 
                                 alt="Photo de <?php echo htmlspecialchars($connection['first_name']); ?>" 
                                 class="rounded-circle" style="width: 100px; height: 100px;">
                        </a>
                        <p>
                            <strong><?php echo htmlspecialchars($connection['first_name'] . ' ' . $connection['last_name']); ?></strong><br>
                            <?php echo htmlspecialchars($connection['role']); ?>
                        </p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Vous n'avez pas encore de connexions.</p>
            <?php endif; ?>
        </div>

        <h3>Demandes en attente</h3>
<div class="row">
    <?php
    $sql_pending_requests = "
        SELECT users.id, users.first_name, users.last_name, users.profile_picture
        FROM connections
        JOIN users ON connections.user_id = users.id
        WHERE connections.connection_id = '$user_id' AND connections.status = 'pending'
    ";
    $result_pending = $conn->query($sql_pending_requests);

    if ($result_pending->num_rows > 0) {
        while ($request = $result_pending->fetch_assoc()) {
            echo '
                <div class="col-md-4 text-center">
                    <a href="profile.php?user_id=' . $request['id'] . '">
                        <img src="' . ($request['profile_picture'] ?: 'images/default-profile.png') . '" 
                             alt="Photo de ' . htmlspecialchars($request['first_name']) . '" 
                             class="rounded-circle" style="width: 100px; height: 100px;">
                    </a>
                    <p>' . htmlspecialchars($request['first_name'] . ' ' . $request['last_name']) . '</p>
                    <form action="accept_request.php" method="POST" style="display: inline;">
                        <input type="hidden" name="connection_id" value="' . $request['id'] . '">
                        <button type="submit" class="btn btn-success btn-sm">Accepter</button>
                    </form>
                    <form action="reject_request.php" method="POST" style="display: inline;">
                        <input type="hidden" name="connection_id" value="' . $request['id'] . '">
                        <button type="submit" class="btn btn-danger btn-sm">Refuser</button>
                    </form>
                </div>
            ';
        }
    } else {
        echo '<p>Aucune demande en attente.</p>';
    }
    ?>
</div>


        <!-- Liste des amis d'amis -->
        <h3>Amis de vos amis</h3>
        <div class="row">
            <?php if ($result_friends_of_friends->num_rows > 0): ?>
                <?php while ($friend = $result_friends_of_friends->fetch_assoc()): ?>
                    <div class="col-md-4 text-center mb-3">
                        <a href="profile.php?user_id=<?php echo $friend['id']; ?>">
                            <img src="<?php echo $friend['profile_picture'] ?: 'images/default-profile.png'; ?>" 
                                 alt="Photo de <?php echo htmlspecialchars($friend['first_name']); ?>" 
                                 class="rounded-circle" style="width: 100px; height: 100px;">
                        </a>
                        <p>
                            <strong><?php echo htmlspecialchars($friend['first_name'] . ' ' . $friend['last_name']); ?></strong><br>
                            <?php echo htmlspecialchars($friend['role']); ?>
                        </p>
                        <form action="send_request.php" method="POST">
                            <input type="hidden" name="connection_id" value="<?php echo $friend['id']; ?>">
                            <button type="submit" class="btn btn-primary btn-sm">Demander une connexion</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Aucun ami d'amis trouvé.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
