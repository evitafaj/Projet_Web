<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('includes/db_connect.php');

// Récupérer les emplois disponibles
$sql_jobs = "SELECT * FROM jobs ORDER BY created_at DESC";
$result_jobs = $conn->query($sql_jobs);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emplois - ECE In</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <?php include('includes/header.php'); ?>

    <div class="container mt-5">
        <h2>Emplois Disponibles</h2>
        <p>Trouvez les dernières opportunités professionnelles à l'ECE, chez Omnes Education, et ses partenaires.</p>

        <?php if ($result_jobs->num_rows > 0): ?>
            <div class="row">
                <?php while ($job = $result_jobs->fetch_assoc()): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($job['title']); ?></h5>
                                <p class="card-text">
                                    <strong>Entreprise :</strong> <?php echo htmlspecialchars($job['company']); ?><br>
                                    <strong>Localisation :</strong> <?php echo htmlspecialchars($job['location']); ?><br>
                                    <strong>Type :</strong> <?php echo htmlspecialchars($job['job_type']); ?><br>
                                    <strong>Salaire :</strong> <?php echo htmlspecialchars($job['salary'] ?: 'Non spécifié'); ?>
                                </p>
                                <p class="card-text"><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
                                <small class="text-muted">Posté le : <?php echo date('d/m/Y', strtotime($job['created_at'])); ?></small>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>Aucune offre d'emploi disponible pour le moment.</p>
        <?php endif; ?>
    </div>

    <!-- Formulaire de recherche et filtres -->
<form action="jobs.php" method="GET" class="mb-4">
    <div class="row">
        <div class="col-md-4">
            <input type="text" name="query" class="form-control" placeholder="Rechercher un emploi..." value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>">
        </div>
        <div class="col-md-4">
            <select name="job_type" class="form-control">
                <option value="">Tous les types</option>
                <option value="CDI" <?php echo (isset($_GET['job_type']) && $_GET['job_type'] == 'CDI') ? 'selected' : ''; ?>>CDI</option>
                <option value="CDD" <?php echo (isset($_GET['job_type']) && $_GET['job_type'] == 'CDD') ? 'selected' : ''; ?>>CDD</option>
                <option value="Stage" <?php echo (isset($_GET['job_type']) && $_GET['job_type'] == 'Stage') ? 'selected' : ''; ?>>Stage</option>
                <option value="Apprentissage" <?php echo (isset($_GET['job_type']) && $_GET['job_type'] == 'Apprentissage') ? 'selected' : ''; ?>>Apprentissage</option>
            </select>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary w-100">Rechercher</button>
        </div>
    </div>
</form>

<?php
// Récupérer les emplois en fonction des filtres
$where = [];
if (isset($_GET['query']) && !empty($_GET['query'])) {
    $query = $conn->real_escape_string($_GET['query']);
    $where[] = "(title LIKE '%$query%' OR company LIKE '%$query%' OR location LIKE '%$query%')";
}
if (isset($_GET['job_type']) && !empty($_GET['job_type'])) {
    $job_type = $conn->real_escape_string($_GET['job_type']);
    $where[] = "job_type = '$job_type'";
}
$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';
$sql_jobs = "SELECT * FROM jobs $where_sql ORDER BY created_at DESC";
$result_jobs = $conn->query($sql_jobs);
?>


    <?php include('includes/footer.php'); ?>
</body>
</html>
