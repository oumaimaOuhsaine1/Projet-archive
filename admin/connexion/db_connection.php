<?php
$servername = "localhost"; 
$username_db = "root"; 
$password_db = ""; 
$database = "archive";

$conn = new mysqli($servername, $username_db, $password_db, $database);

if ($conn->connect_error) {
    die("Échec de la connexion à la base de données: " . $conn->connect_error);
}
?>
