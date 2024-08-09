<?php
session_start();
if (!isset($_SESSION['id_direction'])) {
    header('Location: direction.php');
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'archive');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';
$services = [];

// Récupérer les services existants pour la direction
$id_direction = $_SESSION['id_direction'];
$result = $conn->query("SELECT id_service, intitule FROM Service WHERE id_direction = $id_direction");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['existing_service'])) {
        // Sélectionner un service existant
        $_SESSION['id_service'] = $_POST['existing_service'];
        header('Location: site.php');
    } else {
        // Ajouter un nouveau service
        $intitule = $_POST['intitule'];
        $sql = "INSERT INTO Service (intitule, id_direction) VALUES ('$intitule', $id_direction)";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['id_service'] = $conn->insert_id;
            header('Location: site.php');
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ajouter Service</title>
    <link rel="stylesheet" href="../style/para.css">
</head>
<body>
    <!-- Indicateurs de progression -->
    <div class="progress-container">
        <div class="progress-step completed">1</div>
        <div class="progress-step active">2</div>
        <div class="progress-step">3</div>
        <div class="progress-step">4</div>
    </div>
    
    <?php if ($message): ?>
        <div class="success-alert">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="existing_service">Sélectionner un Service Existant:</label>
        <select id="existing_service" name="existing_service">
            <option value="">-- Sélectionner --</option>
            <?php foreach ($services as $service): ?>
                <option value="<?php echo $service['id_service']; ?>"><?php echo htmlspecialchars($service['intitule']); ?></option>
            <?php endforeach; ?>
        </select>
        <p>Ou</p>
        <label for="intitule">Ajouter un Nouveau Service:</label>
        <input type="text" id="intitule" name="intitule">
        <button type="submit">Suivant</button>
    </form>
</body>
</html>
