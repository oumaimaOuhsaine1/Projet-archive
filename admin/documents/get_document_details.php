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

$id_document = isset($_POST['id_document']) ? (int)$_POST['id_document'] : 0;

$sql = "SELECT d.*, s.intitule as service_intitule, si.intitule as site_intitule, so.intitule as societe_intitule
        FROM document d
        LEFT JOIN service s ON d.id_service = s.id_service
        LEFT JOIN site si ON d.id_site = si.id_site
        LEFT JOIN societe so ON d.id_societe = so.id_societe
        WHERE d.id_document = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $id_document);
if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

$result = $stmt->get_result();
$document = $result->fetch_assoc();

echo json_encode($document);
?>
