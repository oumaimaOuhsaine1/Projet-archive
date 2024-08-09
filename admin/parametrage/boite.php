<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "archive";

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ajouter un type de boîte
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['type_boite'])) {
    $type_boite = $_POST['type_boite'];
    $longueur = $_POST['longueur'];
    $largeur = $_POST['largeur'];

    $sql = "INSERT INTO TypeBoite (type_boite, longueur, largeur) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdd", $type_boite, $longueur, $largeur);

    if ($stmt->execute()) {
        $message = "Nouveau type de boîte ajouté avec succès";
        $alert_class = "alert-success";
    } else {
        $message = "Erreur: " . $stmt->error;
        $alert_class = "alert-danger";
    }
    $stmt->close();
}

// Modifier un type de boîte
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_id'])) {
    $id = $_POST['edit_id'];
    $type_boite = $_POST['edit_type_boite'];
    $longueur = $_POST['edit_longueur'];
    $largeur = $_POST['edit_largeur'];

    $sql = "UPDATE TypeBoite SET type_boite = ?, longueur = ?, largeur = ? WHERE id_type_boite = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sddi", $type_boite, $longueur, $largeur, $id);

    if ($stmt->execute()) {
        $message = "Type de boîte modifié avec succès";
        $alert_class = "alert-success";
    } else {
        $message = "Erreur: " . $stmt->error;
        $alert_class = "alert-danger";
    }
    $stmt->close();
}

// Récupérer les types de boîtes
$sql = "SELECT * FROM TypeBoite";
$result = $conn->query($sql);

// Récupérer les données pour le formulaire de modification
$data = null;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM TypeBoite WHERE id_type_boite = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Types de Boîtes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
           .btn-custom {
            background-color: #171717;
            color: #fff;
        }
        .btn-custom:hover {
            background-color: #333;
            color: #fff;
        }
        .alert {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            display: none; 
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Types de Boîtes</h1>
        <?php if (isset($message)) : ?>
            <div class="alert <?php echo $alert_class; ?>" id="alertMessage"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <button class="btn btn-custom mb-3" data-toggle="modal" data-target="#addTypeModal">
    <i class="bi bi-plus-circle"></i> 
</button>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Type de Boîte</th>
                    <th>Longueur</th>
                    <th>Largeur</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row["type_boite"]); ?></td>
                            <td><?php echo htmlspecialchars($row["longueur"]); ?></td>
                            <td><?php echo htmlspecialchars($row["largeur"]); ?></td>
                            <td>
                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editTypeModal" 
        data-id="<?php echo $row["id_type_boite"]; ?>"
        data-type="<?php echo htmlspecialchars($row["type_boite"]); ?>"
        data-longueur="<?php echo htmlspecialchars($row["longueur"]); ?>"
        data-largeur="<?php echo htmlspecialchars($row["largeur"]); ?>">
    <i class="bi bi-pencil-square"></i> 
</button>

                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5">Aucun type de boîte trouvé</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal pour ajouter un type de boîte -->
    <div class="modal fade" id="addTypeModal" tabindex="-1" role="dialog" aria-labelledby="addTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTypeModalLabel">Ajouter un Type de Boîte</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="type_boite">Type de Boîte:</label>
                            <input type="text" class="form-control" id="type_boite" name="type_boite" required>
                        </div>
                        <div class="form-group">
                            <label for="longueur">Longueur:</label>
                            <input type="number" step="0.01" class="form-control" id="longueur" name="longueur" required>
                        </div>
                        <div class="form-group">
                            <label for="largeur">Largeur:</label>
                            <input type="number" step="0.01" class="form-control" id="largeur" name="largeur" required>
                        </div>
                        <button type="submit" class="btn btn-custom">Ajouter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour modifier un type de boîte -->
    <div class="modal fade" id="editTypeModal" tabindex="-1" role="dialog" aria-labelledby="editTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTypeModalLabel">Modifier un Type de Boîte</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        <input type="hidden" id="edit_id" name="edit_id">
                        <div class="form-group">
                            <label for="edit_type_boite">Type de Boîte:</label>
                            <input type="text" class="form-control" id="edit_type_boite" name="edit_type_boite" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_longueur">Longueur:</label>
                            <input type="number" step="0.01" class="form-control" id="edit_longueur" name="edit_longueur" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_largeur">Largeur:</label>
                            <input type="number" step="0.01" class="form-control" id="edit_largeur" name="edit_largeur" required>
                        </div>
                        <button type="submit" class="btn btn-custom">Modifier</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Afficher le message pendant 3 secondes
            var alert = $('#alertMessage');
            if (alert.length) {
                alert.fadeIn().delay(3000).fadeOut();
            }

            // Remplir les champs du modal de modification
            $('#editTypeModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var type = button.data('type');
                var longueur = button.data('longueur');
                var largeur = button.data('largeur');

                var modal = $(this);
                modal.find('#edit_id').val(id);
                modal.find('#edit_type_boite').val(type);
                modal.find('#edit_longueur').val(longueur);
                modal.find('#edit_largeur').val(largeur);
            });
        });
    </script>
</body>
</html>
