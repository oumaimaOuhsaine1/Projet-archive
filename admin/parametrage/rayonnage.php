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

// Vérifier si les données de la salle ont été soumises
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $localisation = $_POST['localisation'];
    $id_site = $_POST['id_site'];
    $num_rayonnages = $_POST['num_rayonnages'];

    // Insérer les informations de la salle dans la base de données
    $sql = "INSERT INTO salle (localisation, id_site) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $localisation, $id_site);

    if ($stmt->execute()) {
        $id_salle = $stmt->insert_id;
    } else {
        die("Erreur: " . $stmt->error);
    }
} else {
    die("Accès direct non autorisé.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter des Rayonnages</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Ajouter des Rayonnages</h1>
        <form action="etagere.php" method="post">
            <input type="hidden" name="id_salle" value="<?php echo $id_salle; ?>">
            <?php for ($i = 1; $i <= $num_rayonnages; $i++): ?>
                <h3>Rayonnage <?php echo $i; ?></h3>
                <div class="form-group">
                    <label for="longueur_<?php echo $i; ?>">Longueur:</label>
                    <input type="text" class="form-control" id="longueur_<?php echo $i; ?>" name="longueur[]">
                </div>
                <div class="form-group">
                    <label for="largeur_<?php echo $i; ?>">Largeur:</label>
                    <input type="text" class="form-control" id="largeur_<?php echo $i; ?>" name="largeur[]">
                </div>
                <div class="form-group">
                    <label for="num_etageres_<?php echo $i; ?>">Nombre d'Étagères:</label>
                    <input type="number" class="form-control" id="num_etageres_<?php echo $i; ?>" name="num_etageres[]">
                </div>
            <?php endfor; ?>
            <button type="submit" class="btn btn-primary">Suivant</button>
        </form>
    </div>
</body>
</html>
