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

$id_boite = isset($_GET['id_boite']) ? (int)$_GET['id_boite'] : 0;

// Initialiser les dimensions de la boîte
$longueur_boite = 0;
$largeur_boite = 0;

// Récupérer les dimensions de la boîte depuis typeboite
if ($id_boite) {
    $sql = "SELECT tb.longueur, tb.largeur
            FROM boite b
            JOIN typeboite tb ON b.id_type_boite = tb.id_type_boite
            WHERE b.id_boite = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $id_boite);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $boite = $result->fetch_assoc();

    $longueur_boite = $boite['longueur'];
    $largeur_boite = $boite['largeur'];
}

// Récupérer les salles disponibles
$sql = "SELECT id_salle, localisation FROM salle";
$result = $conn->query($sql);
$salles = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Emplacements Disponibles</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Emplacements Disponibles</h1>
        <form id="salle-form">
            <div class="form-group">
                <label for="salle-select">Sélectionnez une salle :</label>
                <select class="form-control" id="salle-select" name="id_salle">
                    <option value="">Sélectionnez une salle</option>
                    <?php foreach ($salles as $salle): ?>
                        <option value="<?php echo $salle['id_salle']; ?>"><?php echo $salle['localisation']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
        <div id="etagere-container" class="row"></div>
        <a href="document.php?id_boite=<?php echo $id_boite; ?>" class="btn btn-secondary mt-3">Retour</a>
    </div>

    <script>
        $(document).ready(function() {
            $('#salle-select').change(function() {
                const idSalle = $(this).val();
                if (idSalle) {
                    fetchEtageres(idSalle);
                }
            });
        });

        function fetchEtageres(idSalle) {
            const longueurBoite = <?php echo $longueur_boite; ?>;
            const largeurBoite = <?php echo $largeur_boite; ?>;
            const idBoite = <?php echo $id_boite; ?>;

            $.ajax({
                url: 'get_available_etageres.php',
                method: 'GET',
                data: {
                    id_salle: idSalle,
                    longueur_boite: longueurBoite,
                    largeur_boite: largeurBoite
                },
                success: function(response) {
                    const etageres = JSON.parse(response);
                    const container = $('#etagere-container');
                    container.empty();

                    if (etageres.length === 0) {
                        container.html('<p>Aucune étagère disponible.</p>');
                    } else {
                        etageres.forEach(etagere => {
                            const etagereElement = `
                                <div class="col-md-3">
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Étagère ID: ${etagere.id_etagere}</h5>
                                            <p class="card-text">Dimensions: ${etagere.longueur} x ${etagere.largeur}</p>
                                            <p class="card-text">Profondeur: ${etagere.profondeur}</p>
                                            <p class="card-text">Rayonnage ID: ${etagere.id_rayonnage}</p>
                                            <p class="card-text">Salle ID: ${etagere.id_salle}</p>
                                            <button class="btn btn-success" onclick="selectEtagere(${etagere.id_etagere}, ${idBoite})">Sélectionner</button>
                                        </div>
                                    </div>
                                </div>
                            `;
                            container.append(etagereElement);
                        });
                    }
                },
                error: function() {
                    alert('Erreur lors de la récupération des étagères disponibles.');
                }
            });
        }

        function selectEtagere(idEtagere, idBoite) {
            const longueurBoite = <?php echo $longueur_boite; ?>;
            const largeurBoite = <?php echo $largeur_boite; ?>;

            $.ajax({
                url: 'verify_etagere.php',
                method: 'GET',
                data: {
                    id_etagere: idEtagere,
                    longueur_boite: longueurBoite,
                    largeur_boite: largeurBoite
                },
                success: function(response) {
                    if (response.trim() === 'success') {
                        $.ajax({
                            url: 'select_etagere.php',
                            method: 'GET',
                            data: {
                                id_boite: idBoite,
                                id_etagere: idEtagere
                            },
                            success: function(response) {
                                if (response.trim() === 'success') {
                                    alert('Étagère sélectionnée avec succès');
                                    window.location.href = `../dossier/dossier.php?id_boite=${idBoite}`;
                                } else {
                                    alert('Erreur lors de la sélection de l\'étagère');
                                }
                            }
                        });
                    } else {
                        alert('Espace insuffisant sur l\'étagère');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Erreur lors de la vérification de l\'espace disponible sur l\'étagère');
                }
            });
        }
    </script>
</body>
</html>
