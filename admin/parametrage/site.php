<?php
session_start();
if (!isset($_SESSION['id_direction']) || !isset($_SESSION['id_service'])) {
    header('Location: direction.php');
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'archive');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';
$sites = [];

// Récupérer les sites existants pour le service
$id_service = $_SESSION['id_service'];
$result = $conn->query("SELECT id_site, code_site, intitule FROM Site WHERE id_direction = {$_SESSION['id_direction']} AND id_service = $id_service");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $sites[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['existing_site'])) {
        // Sélectionner un site existant
        $_SESSION['id_site'] = $_POST['existing_site'];
        header('Location: societe.php');
    } else {
        // Ajouter un nouveau site
        $code_site = $_POST['code_site'];
        $intitule = $_POST['intitule'];
        $sql = "INSERT INTO Site (code_site, intitule, id_direction, id_service) VALUES ('$code_site', '$intitule', {$_SESSION['id_direction']}, $id_service)";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['id_site'] = $conn->insert_id;
            header('Location: societe.php');
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
    <title>Ajouter Site</title>
    <link rel="stylesheet" href="../style/para.css">
</head>
<body>
    <!-- Indicateurs de progression -->
    <div class="progress-container">
        <div class="progress-step completed">1</div>
        <div class="progress-step completed">2</div>
        <div class="progress-step active">3</div>
        <div class="progress-step">4</div>
    </div>
    
    <?php if ($message): ?>
        <div class="success-alert">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
    <div>
        <label for="existing_site">Sélectionner un Site Existant:</label>
        <select id="existing_site" name="existing_site">
            <option value="">-- Sélectionner --</option>
            <?php foreach ($sites as $site): ?>
                <option value="<?php echo htmlspecialchars($site['id_site']); ?>">
                    <?php echo htmlspecialchars($site['code_site']) . ' - ' . htmlspecialchars($site['intitule']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <br><br><br>
    
    <div>
        <label for="code_site">Code du Site:</label>
        <input type="number" id="code_site" name="code_site" >
    </div>
    <div>
        <label for="intitule">Intitulé du Site:</label>
        <input type="text" id="intitule" name="intitule" x>
    </div>
    <button type="submit">Suivant</button>
</form>

</body>
</html>
