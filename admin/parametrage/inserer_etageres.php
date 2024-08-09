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
    $id_rayonnage = $_POST['id_rayonnage'];
    $rayonnage_num = $_POST['rayonnage_num'];

    foreach ($id_rayonnage as $index => $id_ray) {
        $num_etageres = $_POST['num_etageres_' . $rayonnage_num[$index]];

        for ($j = 1; $j <= $num_etageres; $j++) {
            $longueur_etagere = $_POST['longueur_etagere_' . $rayonnage_num[$index] . '_' . $j];
            $largeur_etagere = $_POST['largeur_etagere_' . $rayonnage_num[$index] . '_' . $j];
            $profondeur_etagere = $_POST['profondeur_etagere_' . $rayonnage_num[$index] . '_' . $j];

            $sql_etagere = "INSERT INTO etagere (id_rayonnage, numero, longueur, largeur, profondeur) VALUES (?, ?, ?, ?, ?)";
            $stmt_etagere = $conn->prepare($sql_etagere);
            $stmt_etagere->bind_param("iiddi", $id_ray, $j, $longueur_etagere, $largeur_etagere, $profondeur_etagere);

            if (!$stmt_etagere->execute()) {
                die("Erreur lors de l'insertion de l'étagère: " . $stmt_etagere->error);
            }
        }
    }

    echo "<div id='success-message' style='color: green; font-size: 20px; font-weight: bold;'>Insertion réussie des rayonnages et étagères.</div>";
    echo "<script>
        setTimeout(function() {
            window.location.href = '../index.php';
        }, 2000); // 2 seconds
    </script>";
} else {
    die("Accès direct non autorisé.");
}

$conn->close();
?>
