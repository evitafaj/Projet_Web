<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('includes/db_connect.php');

$user_id = $_SESSION['user_id'];
$search_results = [];

// Charger les informations de l'utilisateur connecté ou d'un autre utilisateur
if (isset($_GET['user_id']) && $_GET['user_id'] != $user_id) {
    $other_user_id = $_GET['user_id'];
    $sql_user = "SELECT * FROM users WHERE id='$other_user_id'";
    $result_user = $conn->query($sql_user);
    $user = $result_user->fetch_assoc();

    // Vérifier la connexion avec l'utilisateur visité
    $sql_check_connection = "
        SELECT * FROM connections
        WHERE (user_id='$user_id' AND connection_id='$other_user_id')
        OR (user_id='$other_user_id' AND connection_id='$user_id')
    ";
    $result_check_connection = $conn->query($sql_check_connection);
} else {
    $sql_user = "SELECT * FROM users WHERE id='$user_id'";
    $result_user = $conn->query($sql_user);
    $user = $result_user->fetch_assoc();
}

// Récupérer les formations
$sql_education = "SELECT * FROM education WHERE user_id='" . $user['id'] . "' ORDER BY end_date DESC";
$result_education = $conn->query($sql_education);

// Récupérer les projets
$sql_projects = "SELECT * FROM projects WHERE user_id='" . $user['id'] . "'";
$result_projects = $conn->query($sql_projects);

// Si une recherche d'amis est effectuée
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_query = $conn->real_escape_string($_GET['search']);
    $sql_search = "
        SELECT u.id, u.first_name, u.last_name, u.profile_picture, u.role
        FROM users u
        WHERE u.id != '$user_id'
          AND (u.first_name LIKE '%$search_query%' OR u.last_name LIKE '%$search_query%' OR u.role LIKE '%$search_query%')
          AND u.id NOT IN (
              SELECT connection_id FROM connections WHERE user_id = '$user_id'
              UNION
              SELECT user_id FROM connections WHERE connection_id = '$user_id'
          )
    ";
    $result_search = $conn->query($sql_search);
    $search_results = $result_search->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - ECE In</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <?php include('includes/header.php'); ?>

    <div class="container mt-5">
        <?php if (isset($_GET['user_id']) && $_GET['user_id'] != $user_id): ?>
            <!-- Profil d'un autre utilisateur -->
            <h2>Profil de <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h2>
            <p>Email : <?php echo htmlspecialchars($user['email']); ?></p>
            <p>Rôle : <?php echo htmlspecialchars($user['role']); ?></p>

            <?php if ($result_check_connection->num_rows == 0): ?>
                <form action="send_request.php" method="POST">
                    <input type="hidden" name="connection_id" value="<?php echo $user['id']; ?>">
                    <button type="submit" class="btn btn-primary">Demander une connexion</button>
                </form>
            <?php elseif ($result_check_connection->fetch_assoc()['status'] === 'pending'): ?>
                <p>Demande de connexion en attente.</p>
            <?php else: ?>
                <p>Vous êtes déjà connecté à cet utilisateur.</p>
            <?php endif; ?>
        <?php else: ?>
            <!-- Profil de l'utilisateur connecté -->
            <h2>Bienvenue, <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?> !</h2>
            <ul>
                <li>Email : <?php echo htmlspecialchars($user['email']); ?></li>
                <li>Rôle : <?php echo htmlspecialchars($user['role']); ?></li>
            </ul>

            <!-- Section Formations -->
            <h3>Formations</h3>
            <?php if ($result_education->num_rows > 0): ?>
                <ul>
                    <?php while ($education = $result_education->fetch_assoc()): ?>
                        <li><strong><?php echo htmlspecialchars($education['degree']); ?></strong> à <?php echo htmlspecialchars($education['institution']); ?> (<?php echo $education['start_date']; ?> - <?php echo $education['end_date']; ?>)</li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>Aucune formation ajoutée.</p>
            <?php endif; ?>

            <form action="add_education.php" method="POST" class="mt-3">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <h4>Ajouter une formation</h4>
                <div class="mb-3">
                    <label for="institution" class="form-label">Institution</label>
                    <input type="text" class="form-control" id="institution" name="institution" required>
                </div>
                <div class="mb-3">
                    <label for="degree" class="form-label">Diplôme</label>
                    <input type="text" class="form-control" id="degree" name="degree" required>
                </div>
                <div class="mb-3">
                    <label for="start_date" class="form-label">Date de début</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                </div>
                <div class="mb-3">
                    <label for="end_date" class="form-label">Date de fin</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" required>
                </div>
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </form>

            <!-- Section Projets -->
            <h3>Projets</h3>
            <?php if ($result_projects->num_rows > 0): ?>
                <ul>
                    <?php while ($project = $result_projects->fetch_assoc()): ?>
                        <li><strong><?php echo htmlspecialchars($project['title']); ?></strong>: <?php echo htmlspecialchars($project['description']); ?></li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>Aucun projet ajouté.</p>
            <?php endif; ?>

            <form action="add_project.php" method="POST" class="mt-3">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <h4>Ajouter un projet</h4>
                <div class="mb-3">
                    <label for="title" class="form-label">Titre du projet</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </form>

            <!-- Chercher des amis -->
            <h3 class="mt-5">Chercher des amis</h3>
            <form action="profile.php" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher des amis..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" required>
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                </div>
            </form>

            <?php if (!empty($search_results)): ?>
                <div class="row">
                    <?php foreach ($search_results as $result): ?>
                        <div class="col-md-4 text-center mb-3">
                            <a href="profile.php?user_id=<?php echo $result['id']; ?>">
                                <img src="<?php echo $result['profile_picture'] ?: 'images/default-profile.png'; ?>" 
                                     alt="Photo de <?php echo htmlspecialchars($result['first_name']); ?>" 
                                     class="rounded-circle" style="width: 100px; height: 100px;">
                            </a>
                            <p>
                                <strong><?php echo htmlspecialchars($result['first_name'] . ' ' . $result['last_name']); ?></strong><br>
                                <?php echo htmlspecialchars($result['role']); ?>
                            </p>
                            <form action="send_request.php" method="POST">
                                <input type="hidden" name="connection_id" value="<?php echo $result['id']; ?>">
                                <button type="submit" class="btn btn-primary btn-sm">Ajouter comme ami</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php elseif (isset($_GET['search'])): ?>
                <p>Aucun utilisateur trouvé pour cette recherche.</p>
            <?php endif; ?>

            <!-- Connexions -->
            <h3>Mes Connexions</h3>
            <div class="row">
                <?php
                $sql_connections = "
                    SELECT users.id, users.first_name, users.last_name, users.profile_picture
                    FROM connections
                    JOIN users ON connections.connection_id = users.id
                    WHERE connections.user_id = '$user_id' AND connections.status = 'accepted'
                ";
                $result_connections = $conn->query($sql_connections);

                if ($result_connections->num_rows > 0): ?>
                    <?php while ($connection = $result_connections->fetch_assoc()): ?>
                        <div class="col-md-3 text-center">
                            <a href="profile.php?user_id=<?php echo $connection['id']; ?>">
                                <img src="<?php echo $connection['profile_picture'] ?: 'images/default-profile.png'; ?>" 
                                     alt="Photo de <?php echo htmlspecialchars($connection['first_name']); ?>" 
                                     class="rounded-circle" style="width: 80px; height: 80px;">
                            </a>
                            <p><?php echo htmlspecialchars($connection['first_name'] . ' ' . $connection['last_name']); ?></p>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Aucune connexion pour le moment.</p>
                <?php endif; ?>
            </div>
            <a href="network.php" class="btn btn-primary mt-3">Voir mon réseau complet</a>
        <?php endif; ?>
    </div>

    <?php include('includes/footer.php'); ?>
</body>
</html>
