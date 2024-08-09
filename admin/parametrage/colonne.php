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

// Vérifier si les données des étagères ont été soumises
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_rayonnages = $_POST['id_rayonnage'];
    $longueurs = $_POST['longueur'];
    $largeurs = $_POST['largeur'];
    $profondeurs = $_POST['profondeur'];
    $num_colonnes = $_POST['num_colonnes'];

    // Insérer les informations des étagères dans la base de données
    for ($i = 0; $i < count($id_rayonnages); $i++) {
        $id_rayonnage = $id_rayonnages[$i];
        $longueur = $longueurs[$i];
        $largeur = $largeurs[$i];
        $profondeur = $profondeurs[$i];

        $sql = "INSERT INTO etagere (id_rayonnage, longueur, largeur, profondeur) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iidd", $id_rayonnage, $longueur, $largeur, $profondeur);

        if (!$stmt->execute()) {
            die("Erreur: " . $stmt->error);
        }
        $id_etagere = $stmt->insert_id;

        // Préparer les formulaires pour les colonnes
        for ($j = 0; $j < $num_colonnes[$i]; $j++) {
            $etageres[] = array('id_etagere' => $id_etagere, 'etagere_num' => $i + 1, 'colonne_num' => $j + 1);
        }
    }
} else {
    die("Accès direct non autorisé.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter des Colonnes</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Ajouter des Colonnes</h1>
        <form action="save_colonnes.php" method="post">
            <?php foreach ($etageres as $etagere): ?>
                <h3>Étagère <?php echo $etagere['etagere_num']; ?> - Colonne <?php echo $etagere['colonne_num']; ?></h3>
                <input type="hidden" name="id_etagere[]" value="<?php echo $etagere['id_etagere']; ?>">
                <div class="form-group">
                    <label for="longueur_<?php echo $etagere['etagere_num']; ?>_<?php echo $etagere['colonne_num']; ?>">Longueur:</label>
                    <input type="text" class="form-control" id="longueur_<?php echo $etagere['etagere_num']; ?>_<?php echo $etagere['colonne_num']; ?>" name="longueur[]">
                </div>
                <div class="form-group">
                    <label for="largeur_<?php echo $etagere['etagere_num']; ?>_<?php echo $etagere['colonne_num']; ?>">Largeur:</label>
                    <input type="text" class="form-control" id="largeur_<?php echo $etagere['etagere_num']; ?>_<?php echo $etagere['colonne_num']; ?>" name="largeur[]">
                </div>
            <?php endforeach; ?>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>
</body>
</html>
