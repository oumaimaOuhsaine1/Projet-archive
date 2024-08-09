<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "archive";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT r.id_rayonnage, r.numero AS numero_rayonnage, s.localisation AS salle, 
               e.id_etagere, e.numero AS numero_etagere, e.longueur, e.largeur, 
               e.espace_disponible, e.disponibilite
        FROM rayonnage r
        LEFT JOIN etagere e ON r.id_rayonnage = e.id_rayonnage
        LEFT JOIN salle s ON r.id_salle = s.id_salle
        ORDER BY r.id_rayonnage, e.id_etagere";
$result = $conn->query($sql);

if (!$result) {
    die("Erreur dans la requête SQL: " . $conn->error);
}

$rayonnages = [];
while ($row = $result->fetch_assoc()) {
    $rayonnages[$row['id_rayonnage']]['salle'] = $row['salle'];
    $rayonnages[$row['id_rayonnage']]['numero_rayonnage'] = $row['numero_rayonnage'];
    $rayonnages[$row['id_rayonnage']]['etagere'][] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Disponibilité des Rayonnages et Étagères</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .disponible {
            background-color: green;
            color: white;
        }
        .indisponible {
            background-color: red;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Disponibilité des Rayonnages et Étagères</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Salle</th>
                    <th>Rayonnage</th>
                    <th>Étagère</th>
                    <th>Dimensions (L * l)</th>
                    <th>Espace Disponible</th>
                    <th>Disponibilité (%)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rayonnages as $rayonnage): ?>
                    <?php foreach ($rayonnage['etagere'] as $etagere): ?>
                        <?php 
                        $total_space = $etagere['longueur'] * $etagere['largeur'];
                        $available_space = $etagere['espace_disponible'];
                        $percentage_available = ($available_space / $total_space) * 100;
                        ?>
                        <tr class="<?php echo $etagere['disponibilite'] ? 'disponible' : 'indisponible'; ?>">
                            <td><?php echo htmlspecialchars($rayonnage['salle']); ?></td>
                            <td>R<?php echo htmlspecialchars($rayonnage['numero_rayonnage']); ?></td>
                            <td>E<?php echo htmlspecialchars($etagere['numero_etagere']); ?></td>
                            <td><?php echo htmlspecialchars($etagere['longueur']) . ' * ' . htmlspecialchars($etagere['largeur']); ?></td>
                            <td><?php echo htmlspecialchars($etagere['espace_disponible']); ?></td>
                            <td><?php echo round($percentage_available, 2); ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
