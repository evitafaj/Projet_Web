<?php
session_start();
include('includes/db_connect.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$search_results = [];

// Si une recherche est soumise
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_query = $conn->real_escape_string($_GET['search']);
    
    // Rechercher les utilisateurs qui ne sont pas encore amis et exclure l'utilisateur actuel
    $sql = "
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
    $result = $conn->query($sql);
    $search_results = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chercher des amis - ECE In</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <?php include('includes/header.php'); ?>

    <h2>Chercher des amis</h2>

    <!-- Barre de recherche -->
    <form action="search_friends.php" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Rechercher des amis..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" required>
            <button type="submit" class="btn btn-primary">Rechercher</button>
        </div>
    </form>

    <!-- Résultats de recherche -->
    <div class="row">
        <?php if (!empty($search_results)): ?>
            <?php foreach ($search_results as $user): ?>
                <div class="col-md-4 text-center mb-3">
                    <a href="profile.php?user_id=<?php echo $user['id']; ?>">
                        <img src="<?php echo $user['profile_picture'] ?: 'images/default-profile.png'; ?>" 
                             alt="Photo de <?php echo htmlspecialchars($user['first_name']); ?>" 
                             class="rounded-circle" style="width: 100px; height: 100px;">
                    </a>
                    <p>
                        <strong><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></strong><br>
                        <?php echo htmlspecialchars($user['role']); ?>
                    </p>
                    <form action="send_request.php" method="POST">
                        <input type="hidden" name="connection_id" value="<?php echo $user['id']; ?>">
                        <button type="submit" class="btn btn-primary btn-sm">Ajouter comme ami</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun utilisateur trouvé pour cette recherche.</p>
        <?php endif; ?>
    </div>

    <?php include('includes/footer.php'); ?>
</body>
</html>
