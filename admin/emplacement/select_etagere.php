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

$id_boite = isset($_GET['id_boite']) ? (int)$_GET['id_boite'] : 0;
$id_etagere = isset($_GET['id_etagere']) ? (int)$_GET['id_etagere'] : 0;

if ($id_boite && $id_etagere) {
    // Récupérer les dimensions de la boîte
    $sql = "SELECT tb.longueur, tb.largeur
            FROM boite b
            JOIN typeboite tb ON b.id_type_boite = tb.id_type_boite
            WHERE b.id_boite = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $id_boite);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $boite = $result->fetch_assoc();
    $longueur_boite = $boite['longueur'];
    $largeur_boite = $boite['largeur'];
    $espace_boite = $longueur_boite * $largeur_boite;

    $stmt->close();

    // Mettre à jour l'étagère dans la table boîte et définir archived à 1
    $sql = "UPDATE boite SET id_etagere = ?, archived = 1 WHERE id_boite = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ii", $id_etagere, $id_boite);
    if ($stmt->execute()) {
        // Mettre à jour l'espace disponible sur l'étagère
        $sql = "UPDATE etagere SET espace_disponible = IFNULL(espace_disponible, longueur * largeur) - ? WHERE id_etagere = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("di", $espace_boite, $id_etagere);
        if ($stmt->execute()) {
            echo 'success';
        } else {
            echo 'Erreur: ' . $stmt->error;
        }
    } else {
        echo 'Erreur: ' . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
