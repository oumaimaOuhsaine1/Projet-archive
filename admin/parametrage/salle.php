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

// Récupérer les informations des salles, rayonnages, étagères et colonnes
$sql = "SELECT s.id_salle, s.localisation, s.id_site,
               COUNT(DISTINCT r.id_rayonnage) AS num_rayonnages,
               COUNT(DISTINCT e.id_etagere) AS num_etageres,
               COUNT(DISTINCT c.id_colonne) AS num_colonnes
        FROM salle s
        LEFT JOIN rayonnage r ON s.id_salle = r.id_salle
        LEFT JOIN etagere e ON r.id_rayonnage = e.id_rayonnage
        LEFT JOIN colonne c ON e.id_etagere = c.id_etagere
        GROUP BY s.id_salle, s.localisation, s.id_site
        ORDER BY s.localisation";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Récupérer les sites
$sql_sites = "SELECT id_site, intitule FROM site";
$sites_result = $conn->query($sql_sites);
$sites = [];
if ($sites_result->num_rows > 0) {
    while($row = $sites_result->fetch_assoc()) {
        $sites[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Salles</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h3>Gestion des Salles</h3>
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addSalleModal">Ajouter une Salle</button>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Salle</th>
                    <th>Nombre de Rayonnages</th>
                    <th>Nombre d'Étagères</th>
                    <th>Nombre de Colonnes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['localisation']); ?></td>
                        <td><?php echo htmlspecialchars($row['num_rayonnages']); ?></td>
                        <td><?php echo htmlspecialchars($row['num_etageres']); ?></td>
                        <td><?php echo htmlspecialchars($row['num_colonnes']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal pour ajouter une salle -->
    <div class="modal fade" id="addSalleModal" tabindex="-1" role="dialog" aria-labelledby="addSalleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSalleModalLabel">Ajouter une Salle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="rayonnage.php" method="post">
                        <div class="form-group">
                            <label for="localisation">Localisation:</label>
                            <input type="text" class="form-control" id="localisation" name="localisation" required>
                        </div>
                        <div class="form-group">
                            <label for="id_site">Site:</label>
                            <select class="form-control" id="id_site" name="id_site" required>
                                <option value="">Sélectionner un site</option>
                                <?php foreach ($sites as $site): ?>
                                    <option value="<?php echo $site['id_site']; ?>"><?php echo htmlspecialchars($site['intitule']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="num_rayonnages">Nombre de Rayonnages:</label>
                            <input type="number" class="form-control" id="num_rayonnages" name="num_rayonnages" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Suivant</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
