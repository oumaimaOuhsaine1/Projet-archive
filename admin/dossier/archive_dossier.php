<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "archive";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id_boite = isset($_POST['id_boite']) ? (int)$_POST['id_boite'] : 0;

if ($id_boite) {
    $sql = "UPDATE Boite SET archived = 1 WHERE id_boite = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_boite);
    if ($stmt->execute()) {
        echo "Boîte archivée avec succès.";
    } else {
        echo "Erreur lors de l'archivage de la boîte: " . $conn->error;
    }
}
?>
