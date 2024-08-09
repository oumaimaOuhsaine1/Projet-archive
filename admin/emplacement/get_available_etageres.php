<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "archive";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id_salle = isset($_GET['id_salle']) ? (int)$_GET['id_salle'] : 0;
$longueur_boite = isset($_GET['longueur_boite']) ? (float)$_GET['longueur_boite'] : 0;
$largeur_boite = isset($_GET['largeur_boite']) ? (float)$_GET['largeur_boite'] : 0;

$sql = "SELECT e.id_etagere, e.longueur, e.largeur, e.profondeur, e.id_rayonnage, r.id_salle
        FROM etagere e
        JOIN rayonnage r ON e.id_rayonnage = r.id_rayonnage
        WHERE r.id_salle = ? AND e.disponibilite = 1 AND e.longueur >= ? AND e.largeur >= ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("idd", $id_salle, $longueur_boite, $largeur_boite);
if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

$result = $stmt->get_result();
$etageres = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($etageres);

$stmt->close();
$conn->close();
?>
