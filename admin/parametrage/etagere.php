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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_salle = $_POST['id_salle'];
    $num_rayonnages = count($_POST['longueur']);
    $longueurs = $_POST['longueur'];
    $largeurs = $_POST['largeur'];
    $num_etageres = $_POST['num_etageres'];

    $rayonnages = [];

    for ($i = 0; $i < $num_rayonnages; $i++) {
        $longueur = $longueurs[$i];
        $largeur = $largeurs[$i];
        $numero = $i + 1;

        $sql = "INSERT INTO rayonnage (id_salle, numero, longueur, largeur) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iidd", $id_salle, $numero, $longueur, $largeur);

        if (!$stmt->execute()) {
            die("Erreur: " . $stmt->error);
        }
        $id_rayonnage = $stmt->insert_id;

        $rayonnages[] = array('id_rayonnage' => $id_rayonnage, 'rayonnage_num' => $numero, 'num_etageres' => $num_etageres[$i]);
    }
} else {
    die("Accès direct non autorisé.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter des Étagères</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Ajouter des Étagères</h1>
        <form action="inserer_etageres.php" method="post">
            <?php foreach ($rayonnages as $rayonnage): ?>
                <h3>Rayonnage <?php echo $rayonnage['rayonnage_num']; ?></h3>
                <input type="hidden" name="id_rayonnage[]" value="<?php echo $rayonnage['id_rayonnage']; ?>">
                <input type="hidden" name="rayonnage_num[]" value="<?php echo $rayonnage['rayonnage_num']; ?>">
                <input type="hidden" name="num_etageres_<?php echo $rayonnage['rayonnage_num']; ?>" value="<?php echo $rayonnage['num_etageres']; ?>">
                <?php for ($j = 1; $j <= $rayonnage['num_etageres']; $j++): ?>
                    <h4>Étagère <?php echo $j; ?></h4>
                    <div class="form-group">
                        <label for="longueur_etagere_<?php echo $rayonnage['rayonnage_num']; ?>_<?php echo $j; ?>">Longueur Étagère:</label>
                        <input type="text" class="form-control" id="longueur_etagere_<?php echo $rayonnage['rayonnage_num']; ?>_<?php echo $j; ?>" name="longueur_etagere_<?php echo $rayonnage['rayonnage_num']; ?>_<?php echo $j; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="largeur_etagere_<?php echo $rayonnage['rayonnage_num']; ?>_<?php echo $j; ?>">Largeur Étagère:</label>
                        <input type="text" class="form-control" id="largeur_etagere_<?php echo $rayonnage['rayonnage_num']; ?>_<?php echo $j; ?>" name="largeur_etagere_<?php echo $rayonnage['rayonnage_num']; ?>_<?php echo $j; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="profondeur_etagere_<?php echo $rayonnage['rayonnage_num']; ?>_<?php echo $j; ?>">Profondeur Étagère:</label>
                        <input type="text" class="form-control" id="profondeur_etagere_<?php echo $rayonnage['rayonnage_num']; ?>_<?php echo $j; ?>" name="profondeur_etagere_<?php echo $rayonnage['rayonnage_num']; ?>_<?php echo $j; ?>" required>
                    </div>
                <?php endfor; ?>
            <?php endforeach; ?>
            <button type="submit" class="btn btn-primary">Suivant</button>
        </form>
    </div>
</body>
</html>
