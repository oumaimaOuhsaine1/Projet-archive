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

// Vérifier si les données des colonnes ont été soumises
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_etageres = $_POST['id_etagere'];
    $longueurs = $_POST['longueur'];
    $largeurs = $_POST['largeur'];

    // Insérer les informations des colonnes dans la base de données
    for ($i = 0; $i < count($id_etageres); $i++) {
        $id_etagere = $id_etageres[$i];
        $longueur = $longueurs[$i];
        $largeur = $largeurs[$i];

        $sql = "INSERT INTO colonne (id_etagere, longueur, largeur) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iid", $id_etagere, $longueur, $largeur);

        if (!$stmt->execute()) {
            die("Erreur: " . $stmt->error);
        }
    }

    // Rediriger vers la page des salles après l'insertion
    header("Location: salle.php");
    exit();
} else {
    die("Accès direct non autorisé.");
}
?>
