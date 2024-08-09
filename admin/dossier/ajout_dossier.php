<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "archive";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$successMessage = "";

// Ajouter une boîte
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nom_boite'])) {
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

    $date_debut = $date_creation;
    $date_fin = date('Y-m-d', strtotime("$date_creation + $duree_conservation years"));

    $sql = "INSERT INTO boite (nom_boite, libelle_cotation, date_creation, nom_admin, duree_conservation, date_debut, date_fin, id_societe, id_site, id_service, id_direction, id_type_boite) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Erreur de préparation de la requête: " . $conn->error);
    }

    $stmt->bind_param("ssssissiiiii", $nom_boite, $libelle_cotation, $date_creation, $nom_admin, $duree_conservation, $date_debut, $date_fin, $id_societe, $id_site, $id_service, $id_direction, $id_type_boite);

    if ($stmt->execute()) {
        $successMessage = "Nouvelle boîte ajoutée avec succès";
    } else {
        echo "Erreur: " . $stmt->error;
    }
    $stmt->close();
}

$type_boites = $conn->query("SELECT * FROM TypeBoite");

$societes = $conn->query("SELECT id_societe, intitule FROM societe");
$sites = $conn->query("SELECT id_site, intitule FROM site");
$services = $conn->query("SELECT id_service, intitule FROM service");
$directions = $conn->query("SELECT id_direction, intitule FROM direction");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Boîte</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
    <style>
        .btn-custom {
            background-color: #171717;
            color: #fff;
        }
        .btn-custom:hover {
            background-color: #333;
            color: #fff;
        }
        .success-message {
            display: none;
            margin-top: 20px;
        }
    </style>
    <script>
        $(document).ready(function() {
            <?php if ($successMessage): ?>
                $('.success-message').text("<?php echo $successMessage; ?>").show();
                setTimeout(function() {
                    window.location.href = "dossier.php";
                }, 3000);
            <?php endif; ?>
            
            $('#date_creation').on('change', function() {
                var duree = parseInt($('#duree_conservation').val());
                var dateDebut = new Date($(this).val());
                dateDebut.setFullYear(dateDebut.getFullYear() + duree);
                $('#date_fin').val(dateDebut.toISOString().split('T')[0]);
            });

µ            $('#duree_conservation').on('change', function() {
                var duree = parseInt($(this).val());
                var dateDebut = new Date($('#date_creation').val());
                dateDebut.setFullYear(dateDebut.getFullYear() + duree);
                $('#date_fin').val(dateDebut.toISOString().split('T')[0]);
            });
        });
    </script>
</head>
<body>
    <div class="container mt-5">
        <h1>Ajouter une Boîte</h1>
        <?php if ($successMessage): ?>
            <div class="alert alert-success success-message"><?php echo $successMessage; ?></div>
        <?php endif; ?>
        <form id="addBoiteForm" method="post" action="">
            <div class="form-group">
                <label for="nom_boite">Nom Boîte:</label>
                <input type="text" class="form-control" id="nom_boite" name="nom_boite" required>
            </div>
            <div class="form-group">
                <label for="id_type_boite">Type de Boîte:</label>
                <select class="form-control" id="id_type_boite" name="id_type_boite" required>
                    <option value="" disabled selected>Choisir un type de boîte</option>
                    <?php while($row = $type_boites->fetch_assoc()): ?>
                        <option value="<?php echo $row['id_type_boite']; ?>"><?php echo $row['type_boite']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="libelle_cotation">Libellé Cotation:</label>
                <input type="text" class="form-control" id="libelle_cotation" name="libelle_cotation" required>
            </div>
            <div class="form-group">
                <label for="date_creation">Date Création:</label>
                <input type="date" class="form-control" id="date_creation" name="date_creation" required>
            </div>
            <div class="form-group">
                <label for="nom_admin">Nom Admin:</label>
                <input type="text" class="form-control" id="nom_admin" name="nom_admin" required>
            </div>
            <div class="form-group">
                <label for="duree_conservation">Durée Conservation (années):</label>
                <input type="number" class="form-control" id="duree_conservation" name="duree_conservation" required>
            </div>
            <div class="form-group">
                <label for="id_direction">Direction:</label>
                <select class="form-control" id="id_direction" name="id_direction" required>
                    <option value="" disabled selected>Choisir une direction</option>
                    <?php while($row = $directions->fetch_assoc()): ?>
                        <option value="<?php echo $row['id_direction']; ?>"><?php echo $row['intitule']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="id_site">Site:</label>
                <select class="form-control" id="id_site" name="id_site" required>
                    <option value="" disabled selected>Choisir un site</option>
                    <?php while($row = $sites->fetch_assoc()): ?>
                        <option value="<?php echo $row['id_site']; ?>"><?php echo $row['intitule']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="id_service">Service:</label>
                <select class="form-control" id="id_service" name="id_service" required>
                    <option value="" disabled selected>Choisir un service</option>
                    <?php while($row = $services->fetch_assoc()): ?>
                        <option value="<?php echo $row['id_service']; ?>"><?php echo $row['intitule']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="id_societe">Société:</label>
                <select class="form-control" id="id_societe" name="id_societe" required>
                    <option value="" disabled selected>Choisir une société</option>
                    <?php while($row = $societes->fetch_assoc()): ?>
                        <option value="<?php echo $row['id_societe']; ?>"><?php echo $row['intitule']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-custom">Ajouter Boîte</button>
        </form>
    </div>
</body>
</html>
