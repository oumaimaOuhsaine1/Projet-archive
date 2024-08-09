<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "archive";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT b.nom_boite, r.id_rayonnage, IFNULL(e.numero, 'N/A') AS numero_etagere, s.localisation AS salle, r.numero AS numero_rayonnage
        FROM boite b
        LEFT JOIN etagere e ON b.id_etagere = e.id_etagere
        LEFT JOIN rayonnage r ON e.id_rayonnage = r.id_rayonnage
        LEFT JOIN salle s ON r.id_salle = s.id_salle
        WHERE b.archived = 1";
$result = $conn->query($sql);

if (!$result) {
    die("Erreur dans la requête SQL: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Disponibilité des Boîtes</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
    <style>
        .table-container {
            margin-top: 20px;
        }
        .search-container {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Disponibilité des Boîtes</h1>
        <div class="search-container">
            <input type="text" id="search" class="form-control" placeholder="Recherche...">
        </div>
        <div class="table-container">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nom Boîte</th>
                        <th>Salle</th>
                        <th>Rayonnage</th>
                        <th>Étagère</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row["nom_boite"]); ?></td>
                                <td><?php echo htmlspecialchars($row["salle"]); ?></td>
                                <td><?php echo "R" . htmlspecialchars($row["numero_rayonnage"]); ?></td>
                                <td><?php echo "E" . htmlspecialchars($row["numero_etagere"]); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">Aucune boîte archivée trouvée</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#search').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#tableBody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
    </script>
</body>
</html>
