<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'archive');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';
$directions = [];

// Récupérer les directions existantes
$result = $conn->query("SELECT id_direction, intitule FROM Direction");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $directions[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['existing_direction'])) {
        // Sélectionner une direction existante
        $_SESSION['id_direction'] = $_POST['existing_direction'];
        header('Location: service.php');
    } else {
        // Ajouter une nouvelle direction
        $intitule = $_POST['intitule'];
        $sql = "INSERT INTO Direction (intitule) VALUES ('$intitule')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['id_direction'] = $conn->insert_id;
            header('Location: service.php');
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
    <title>Ajouter Direction</title>
    <link rel="stylesheet" href="../style/para.css">
</head>
<body>
    <!-- Indicateurs de progression -->
    <div class="progress-container">
        <div class="progress-step active">1</div>
        <div class="progress-step">2</div>
        <div class="progress-step">3</div>
        <div class="progress-step">4</div>
    </div>
    
    <?php if ($message): ?>
        <div class="success-alert">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="existing_direction">Sélectionner une Direction Existante:</label>
        <select id="existing_direction" name="existing_direction">
            <option value="">-- Sélectionner --</option>
            <?php foreach ($directions as $direction): ?>
                <option value="<?php echo $direction['id_direction']; ?>"><?php echo htmlspecialchars($direction['intitule']); ?></option>
            <?php endforeach; ?>
        </select>
        <p>Ou</p>
        <label for="intitule">Ajouter une Nouvelle Direction:</label>
        <input type="text" id="intitule" name="intitule">
        <button type="submit">Suivant</button>
    </form>
</body>
</html>
