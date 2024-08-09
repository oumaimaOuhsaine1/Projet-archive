<?php
session_start();
if (!isset($_SESSION['id_site'])) {
    header('Location: direction.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Connexion à la base de données
    $conn = new mysqli('localhost', 'root', '', 'archive');
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $intitule = $_POST['intitule'];
    $id_site = $_SESSION['id_site'];
    $sql = "INSERT INTO Societe (intitule, id_site) VALUES ('$intitule', $id_site)";
    
    if ($conn->query($sql) === TRUE) {
        unset($_SESSION['id_direction']);
        unset($_SESSION['id_service']);
        unset($_SESSION['id_site']);
        echo "Insertion réussie!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ajouter Société</title>
    <link rel="stylesheet" href="../style/para.css">

</head>
<body>
      <!-- Indicateurs de progression -->
      <div class="progress-container">
        <div class="progress-step completed">1</div>
        <div class="progress-step completed">2</div>
        <div class="progress-step completed">3</div>
        <div class="progress-step completed">4</div>
    </div>
    <?php if ($message): ?>
        <div class="success-alert">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    <form method="POST" action="">
        <label for="intitule">Intitulé de la Société:</label>
        <input type="text" id="intitule" name="intitule" required>
        <button type="submit">Terminer</button>
    </form>
</body>
</html>
