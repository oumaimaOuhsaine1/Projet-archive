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

// Récupérer les données des autres tables
$societes = $conn->query("SELECT id_societe, nom_societe FROM societe");
$sites = $conn->query("SELECT id_site, nom_site FROM site");
$directions = $conn->query("SELECT id_direction, nom_direction FROM direction");
$services = $conn->query("SELECT id_service, nom_service FROM service");

// Récupérer les données de la table boite
$sql = "SELECT * FROM boite";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Boîtes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        body {
    font-family: Arial, sans-serif;
}

h1 {
    text-align: center;
}

button {
    display: block;
    margin: 20px auto;
}

table {
    width: 100%;
    margin-top: 20px;
    border-collapse: collapse;
}

table, th, td {
    border: 1px solid black;
}

th, td {
    padding: 10px;
    text-align: center;
}

.card {
    text-align: center;
}

.card img {
    width: 100px;
    height: 100px;
    margin: 0 auto;
}

.card-title {
    font-size: 1.2em;
}

.card-text {
    font-size: 0.9em;
}

    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Gestion des Boîtes</h1>
        <button id="addBoxBtn" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addBoxModal">Ajouter une Boîte</button>

        <div class="row">
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="col-md-3">
                    <div class="card mb-3">
                        <img src="folder_icon.png" class="card-img-top" alt="Folder Icon">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['nom_boite']; ?></h5>
                            <p class="card-text"><?php echo $row['libelle_cotation']; ?></p>
                            <p class="card-text"><small class="text-muted"><?php echo $row['date_creation']; ?></small></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Modal d'ajout de boîte -->
        <div class="modal fade" id="addBoxModal" tabindex="-1" aria-labelledby="addBoxModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addBoxModalLabel">Ajouter une Boîte</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="addBoxForm" action="boite.php" method="post">
                            <div class="form-group">
                                <label for="nom_boite">Nom Boîte</label>
                                <input type="text" class="form-control" id="nom_boite" name="nom_boite">
                            </div>
                            <div class="form-group">
                                <label for="libelle_cotation">Libellé Cotation</label>
                                <input type="text" class="form-control" id="libelle_cotation" name="libelle_cotation">
                            </div>
                            <div class="form-group">
                                <label for="date_creation">Date Création</label>
                                <input type="date" class="form-control" id="date_creation" name="date_creation">
                            </div>
                            <div class="form-group">
                                <label for="nom_admin">Nom Admin</label>
                                <input type="text" class="form-control" id="nom_admin" name="nom_admin">
                            </div>
                            <div class="form-group">
                                <label for="duree_conservation">Durée Conservation</label>
                                <input type="number" class="form-control" id="duree_conservation" name="duree_conservation">
                            </div>
                            <div class="form-group">
                                <label for="date_debut">Date Début</label>
                                <input type="date" class="form-control" id="date_debut" name="date_debut">
                            </div>
                            <div class="form-group">
                                <label for="date_fin">Date Fin</label>
                                <input type="date" class="form-control" id="date_fin" name="date_fin">
                            </div>
                            <div class="form-group">
                                <label for="longueur">Longueur</label>
                                <input type="number" step="0.01" class="form-control" id="longueur" name="longueur">
                            </div>
                            <div class="form-group">
                                <label for="largeur">Largeur</label>
                                <input type="number" step="0.01" class="form-control" id="largeur" name="largeur">
                            </div>
                            <div class="form-group">
                                <label for="id_societe">Société</label>
                                <select class="form-control" id="id_societe" name="id_societe">
                                    <?php while($row = $societes->fetch_assoc()): ?>
                                        <option value="<?php echo $row['id_societe']; ?>"><?php echo $row['nom_societe']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="id_site">Site</label>
                                <select class="form-control" id="id_site" name="id_site">
                                    <?php while($row = $sites->fetch_assoc()): ?>
                                        <option value="<?php echo $row['id_site']; ?>"><?php echo $row['nom_site']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="id_service">Service</label>
                                <select class="form-control" id="id_service" name="id_service">
                                    <?php while($row = $services->fetch_assoc()): ?>
                                        <option value="<?php echo $row['id_service']; ?>"><?php echo $row['nom_service']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="id_direction">Direction</label>
                                <select class="form-control" id="id_direction" name="id_direction">
                                    <?php while($row = $directions->fetch_assoc()): ?>
                                        <option value="<?php echo $row['id_direction']; ?>"><?php echo $row['nom_direction']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary" name="submit">Ajouter</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="script.js"></script>
</body>
</html>

<?php
// Gestion de l'ajout de boîte
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom_boite = $_POST['nom_boite'];
    $libelle_cotation = $_POST['libelle_cotation'];
    $date_creation = $_POST['date_creation'];
    $nom_admin = $_POST['nom_admin'];
    $duree_conservation = $_POST['duree_conservation'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $longueur = $_POST['longueur'];
    $largeur = $_POST['largeur'];
    $id_societe = $_POST['id_societe'];
    $id_site = $_POST['id_site'];
    $id_service = $_POST['id_service'];
    $id_direction = $_POST['id_direction'];

    $sql = "INSERT INTO boite (nom_boite, libelle_cotation, date_creation, nom_admin, duree_conservation, date_debut, date_fin, longueur, largeur, id_societe, id_site, id_service, id_direction)
            VALUES ('$nom_boite', '$libelle_cotation', '$date_creation', '$nom_admin', '$duree_conservation', '$date_debut', '$date_fin', '$longueur', '$largeur', '$id_societe', '$id_site', '$id_service', '$id_direction')";

    if ($conn->query($sql) === TRUE) {
        echo "Nouvelle boîte ajoutée avec succès";
    } else {
        echo "Erreur: " . $sql . "<br>" . $conn->error;
    }
}
$conn->close();
?>
