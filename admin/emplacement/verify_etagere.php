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

$id_etagere = isset($_GET['id_etagere']) ? (int)$_GET['id_etagere'] : 0;
$longueur_boite = isset($_GET['longueur_boite']) ? (float)$_GET['longueur_boite'] : 0;
$largeur_boite = isset($_GET['largeur_boite']) ? (float)$_GET['largeur_boite'] : 0;

$sql = "SELECT longueur, largeur, espace_disponible FROM etagere WHERE id_etagere = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $id_etagere);
if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

$result = $stmt->get_result();
$etagere = $result->fetch_assoc();

$longueur_etagere = $etagere['longueur'];
$largeur_etagere = $etagere['largeur'];

if (is_null($etagere['espace_disponible'])) {
    $espace_disponible = $longueur_etagere * $largeur_etagere;
    $sql_update = "UPDATE etagere SET espace_disponible = ? WHERE id_etagere = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("di", $espace_disponible, $id_etagere);
    $stmt_update->execute();
    $stmt_update->close();
} else {
    $espace_disponible = $etagere['espace_disponible'];
}

$espace_boite = $longueur_boite * $largeur_boite;

if ($espace_boite <= $espace_disponible) {
    echo 'success';
} else {
    echo 'Espace insuffisant sur l\'étagère';
}

$stmt->close();
$conn->close();
?>
