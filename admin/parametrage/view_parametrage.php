<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "archive";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer les sites
$sql_sites = "SELECT id_site, intitule FROM site";
$sites = $conn->query($sql_sites);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Salle</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Ajouter une Salle</h1>
        <form action="rayonnage.php" method="post">
            <div class="form-group">
                <label for="localisation">Localisation:</label>
                <input type="text" class="form-control" id="localisation" name="localisation" required>
            </div>
            <div class="form-group">
                <label for="id_site">Site:</label>
                <select class="form-control" id="id_site" name="id_site" required>
                    <option value="">Sélectionner un site</option>
                    <?php while($site = $sites->fetch_assoc()): ?>
                        <option value="<?php echo $site['id_site']; ?>"><?php echo htmlspecialchars($site['intitule']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="num_rayonnages">Nombre de Rayonnages:</label>
                <input type="number" class="form-control" id="num_rayonnages" name="num_rayonnages" required>
            </div>
            <button type="submit" class="btn btn-primary">Suivant</button>
        </form>
    </div>
</body>
</html>
