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

$id_document = $_GET['id'];
$id_boite = $_GET['id_boite'];

$sql = "SELECT * FROM document WHERE id_document = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_document);
$stmt->execute();
$document = $stmt->get_result()->fetch_assoc();

// Récupérer les services
$sql_services = "SELECT id_service, intitule FROM service";
$services = $conn->query($sql_services);

// Récupérer les sites
$sql_sites = "SELECT id_site, intitule FROM site";
$sites = $conn->query($sql_sites);

// Récupérer les sociétés
$sql_societes = "SELECT id_societe, intitule FROM societe";
$societes = $conn->query($sql_societes);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type_document = $_POST['type_document'];
    $id_service = $_POST['id_service'];
    $id_site = $_POST['id_site'];
    $id_societe = $_POST['id_societe'];
    $informations_completes = $_POST['informations_completes'];
    $annee = $_POST['annee'];
    $responsable_classement = $_POST['responsable_classement'];

    $sql = "UPDATE document SET type_document = ?, id_service = ?, id_site = ?, id_societe = ?, informations_completes = ?, annee = ?, responsable_classement = ? WHERE id_document = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siissisi", $type_document, $id_service, $id_site, $id_societe, $informations_completes, $annee, $responsable_classement, $id_document);
    
    if ($stmt->execute()) {
        if ($_FILES['file']['error'] == 0) {
            $file = $_FILES['file'];
            $fileName = basename($file['name']);
            $filePath = 'uploads/' . $fileName;

            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                $sql = "UPDATE document SET file_path = ? WHERE id_document = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $filePath, $id_document);
                $stmt->execute();
            } else {
                echo "Erreur lors du déplacement du fichier.";
            }
        }

        header("Location: document.php?id_boite=$id_boite");
        exit();
    } else {
        echo "Erreur: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Modifier un document</h2>
        <form action="modif_document.php?id=<?php echo $id_document; ?>&id_boite=<?php echo $id_boite; ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="type_document">Type de Document:</label>
                <input type="text" class="form-control" id="type_document" name="type_document" value="<?php echo htmlspecialchars($document['type_document']); ?>" required>
            </div>
            <div class="form-group">
                <label for="id_service">Service:</label>
                <select class="form-control" id="id_service" name="id_service" required>
                    <option value="">Sélectionner un service</option>
                    <?php while($service = $services->fetch_assoc()): ?>
                        <option value="<?php echo $service['id_service']; ?>" <?php echo ($service['id_service'] == $document['id_service']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($service['intitule']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="id_site">Site:</label>
                <select class="form-control" id="id_site" name="id_site" required>
                    <option value="">Sélectionner un site</option>
                    <?php while($site = $sites->fetch_assoc()): ?>
                        <option value="<?php echo $site['id_site']; ?>" <?php echo ($site['id_site'] == $document['id_site']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($site['intitule']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="id_societe">Société:</label>
                <select class="form-control" id="id_societe" name="id_societe" required>
                    <option value="">Sélectionner une société</option>
                    <?php while($societe = $societes->fetch_assoc()): ?>
                        <option value="<?php echo $societe['id_societe']; ?>" <?php echo ($societe['id_societe'] == $document['id_societe']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($societe['intitule']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="informations_completes">Informations Complètes:</label>
                <textarea class="form-control" id="informations_completes" name="informations_completes" required><?php echo htmlspecialchars($document['informations_completes']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="annee">Année:</label>
                <input type="number" class="form-control" id="annee" name="annee" value="<?php echo htmlspecialchars($document['annee']); ?>" required>
            </div>
            <div class="form-group">
                <label for="responsable_classement">Responsable du Classement:</label>
                <input type="text" class="form-control" id="responsable_classement" name="responsable_classement" value="<?php echo htmlspecialchars($document['responsable_classement']); ?>" required>
            </div>
            <div class="form-group">
                <label for="file">Sélectionner un fichier (laisser vide pour ne pas changer):</label>
                <input type="file" class="form-control" id="file" name="file">
            </div>
            <input type="hidden" name="id_document" value="<?php echo $id_document; ?>">
            <input type="hidden" name="id_boite" value="<?php echo $id_boite; ?>">
            <input type="hidden" name="action" value="modifier">
            <button type="submit" class="btn btn-primary">Modifier</button>
        </form>
    </div>
</body>
</html>
