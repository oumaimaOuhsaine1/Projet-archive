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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_boite'])) {
    $id_boite = $_POST['id_boite'];
    $nom_boite = $_POST['nom_boite'];
    $libelle_cotation = $_POST['libelle_cotation'];
    $date_creation = $_POST['date_creation'];
    $nom_admin = $_POST['nom_admin'];
    $duree_conservation = $_POST['duree_conservation'];
    $id_societe = $_POST['id_societe'];
    $id_site = $_POST['id_site'];
    $id_service = $_POST['id_service'];
    $id_direction = $_POST['id_direction'];
    $id_type_boite = $_POST['id_type_boite'];

    $sql = "UPDATE Boite SET 
                nom_boite = ?, 
                libelle_cotation = ?, 
                date_creation = ?, 
                nom_admin = ?, 
                duree_conservation = ?, 
                id_societe = ?, 
                id_site = ?, 
                id_service = ?, 
                id_direction = ?, 
                id_type_boite = ?
            WHERE id_boite = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Erreur de préparation de la requête: " . $conn->error);
    }

    $stmt->bind_param("ssssiiiiiii", 
        $nom_boite, 
        $libelle_cotation, 
        $date_creation, 
        $nom_admin, 
        $duree_conservation, 
        $id_societe, 
        $id_site, 
        $id_service, 
        $id_direction, 
        $id_type_boite, 
        $id_boite
    );

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Boîte modifiée avec succès']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }
    $stmt->close();
    $conn->close();
    exit();
} else {
    $id_boite = $_GET['id'];
    $sql = "SELECT b.*, t.type_boite, s.intitule as societe_intitule, si.intitule as site_intitule, se.intitule as service_intitule, d.intitule as direction_intitule
            FROM Boite b
            LEFT JOIN TypeBoite t ON b.id_type_boite = t.id_type_boite
            LEFT JOIN societe s ON b.id_societe = s.id_societe
            LEFT JOIN site si ON b.id_site = si.id_site
            LEFT JOIN service se ON b.id_service = se.id_service
            LEFT JOIN direction d ON b.id_direction = d.id_direction
            WHERE b.id_boite = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_boite);
    $stmt->execute();
    $result = $stmt->get_result();
    $boite = $result->fetch_assoc();

    $societes = $conn->query("SELECT id_societe, intitule FROM societe");
    $sites = $conn->query("SELECT id_site, intitule FROM site");
    $services = $conn->query("SELECT id_service, intitule FROM service");
    $directions = $conn->query("SELECT id_direction, intitule FROM direction");

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier la Boîte</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Modifier la Boîte</h1>
        <div id="alert-message" class="alert" role="alert" style="display: none;"></div>
        <form id="editBoiteForm" method="post">
            <input type="hidden" name="id_boite" value="<?php echo htmlspecialchars($boite['id_boite']); ?>">
            <div class="form-group">
                <label for="nom_boite">Nom Boîte:</label>
                <input type="text" class="form-control" id="nom_boite" name="nom_boite" value="<?php echo htmlspecialchars($boite['nom_boite']); ?>" required>
            </div>
            <div class="form-group">
                <label for="libelle_cotation">Libellé Cotation:</label>
                <input type="text" class="form-control" id="libelle_cotation" name="libelle_cotation" value="<?php echo htmlspecialchars($boite['libelle_cotation']); ?>" required>
            </div>
            <div class="form-group">
                <label for="date_creation">Date Création:</label>
                <input type="date" class="form-control" id="date_creation" name="date_creation" value="<?php echo htmlspecialchars($boite['date_creation']); ?>" required>
            </div>
            <div class="form-group">
                <label for="nom_admin">Nom Admin:</label>
                <input type="text" class="form-control" id="nom_admin" name="nom_admin" value="<?php echo htmlspecialchars($boite['nom_admin']); ?>" required>
            </div>
            <div class="form-group">
                <label for="duree_conservation">Durée Conservation (années):</label>
                <input type="number" class="form-control" id="duree_conservation" name="duree_conservation" value="<?php echo htmlspecialchars($boite['duree_conservation']); ?>" required>
            </div>
            <div class="form-group">
                <label for="id_societe">Société:</label>
                <select class="form-control" id="id_societe" name="id_societe" required>
                    <?php while ($societe = $societes->fetch_assoc()): ?>
                        <option value="<?php echo $societe['id_societe']; ?>" <?php if ($boite['id_societe'] == $societe['id_societe']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($societe['intitule']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="id_site">Site:</label>
                <select class="form-control" id="id_site" name="id_site" required>
                    <?php while ($site = $sites->fetch_assoc()): ?>
                        <option value="<?php echo $site['id_site']; ?>" <?php if ($boite['id_site'] == $site['id_site']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($site['intitule']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="id_service">Service:</label>
                <select class="form-control" id="id_service" name="id_service" required>
                    <?php while ($service = $services->fetch_assoc()): ?>
                        <option value="<?php echo $service['id_service']; ?>" <?php if ($boite['id_service'] == $service['id_service']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($service['intitule']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="id_direction">Direction:</label>
                <select class="form-control" id="id_direction" name="id_direction" required>
                    <?php while ($direction = $directions->fetch_assoc()): ?>
                        <option value="<?php echo $direction['id_direction']; ?>" <?php if ($boite['id_direction'] == $direction['id_direction']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($direction['intitule']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="id_type_boite">Type de Boîte:</label>
                <input type="text" class="form-control" id="id_type_boite" name="id_type_boite" value="<?php echo htmlspecialchars($boite['id_type_boite']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#editBoiteForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: '',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#alert-message').removeClass('alert-danger').addClass('alert-success').text(response.message).show();
                            setTimeout(function() {
                                window.location.href = 'dossier.php';
                            }, 2000);
                        } else {
                            $('#alert-message').removeClass('alert-success').addClass('alert-danger').text(response.message).show();
                        }
                    },
                    error: function() {
                        $('#alert-message').removeClass('alert-success').addClass('alert-danger').text('Une erreur est survenue lors de la modification de la boîte.').show();
                    }
                });
            });
        });
    </script>
</body>
</html>
