<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "archive";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer les boîtes
$sql = "SELECT b.*, t.type_boite, s.intitule as societe_intitule, si.intitule as site_intitule, se.intitule as service_intitule, d.intitule as direction_intitule
        FROM boite b
        LEFT JOIN typeboite t ON b.id_type_boite = t.id_type_boite
        LEFT JOIN societe s ON b.id_societe = s.id_societe
        LEFT JOIN site si ON b.id_site = si.id_site
        LEFT JOIN service se ON b.id_service = se.id_service
        LEFT JOIN direction d ON b.id_direction = d.id_direction";
$boites = $conn->query($sql);

if (!$boites) {
    die("Erreur dans la requête SQL: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Boîtes</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
    <style>
        .card {
            margin: 10px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
            position: relative;
        }
        .card img {
            width: 85px;
            height: 85px;
            display: block;
            margin: 0 auto;
        }
        .card h5 {
            color: #007bff;
            margin-bottom: 10px;
        }
        .card p {
            margin-bottom: 5px;
        }
        .card small {
            color: #6c757d;
        }
        .archived-label {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: green;
            color: white;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Gestion des Boîtes</h1>
        <div class="d-flex justify-content-between mb-3">
            <button class="btn btn-custom" onclick="window.location.href='ajout_dossier.php'">
                <i class="bi bi-plus-circle"></i> Ajouter une Boîte
            </button>
            <input type="text" id="search" class="form-control" placeholder="Recherche...">
        </div>
        <div class="row" id="boiteContainer">
            <?php if ($boites->num_rows > 0): ?>
                <?php while($boite = $boites->fetch_assoc()): ?>
                    <div class="col-md-3 boite-card" data-nom="<?php echo htmlspecialchars($boite["nom_boite"]); ?>" data-type="<?php echo htmlspecialchars($boite["type_boite"]); ?>">
                        <div class="card">
                            <?php if ($boite['id_etagere']): ?>
                                <span class="archived-label">Archivé</span>
                            <?php endif; ?>
                            <h5><?php echo $boite["type_boite"]; ?></h5>
                            <a href="../documents/document.php?id_boite=<?php echo $boite['id_boite']; ?>">
                                <img src="../../img/folder_img.png" alt="Folder Icon">
                            </a>
                            <p><?php echo $boite["nom_boite"]; ?></p>
                            <button class="btn btn-warning btn-sm" onclick="window.location.href='modif_dossier.php?id=<?php echo $boite['id_boite']; ?>'">
                                <i class="bi bi-pencil-square"></i> Modifier
                            </button>
                            <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#viewBoiteModal"
                                data-nom="<?php echo htmlspecialchars($boite["nom_boite"]); ?>"
                                data-cotation="<?php echo htmlspecialchars($boite["libelle_cotation"]); ?>"
                                data-creation="<?php echo htmlspecialchars($boite["date_creation"]); ?>"
                                data-admin="<?php echo htmlspecialchars($boite["nom_admin"]); ?>"
                                data-conservation="<?php echo htmlspecialchars($boite["duree_conservation"]); ?>"
                                data-debut="<?php echo htmlspecialchars($boite["date_debut"]); ?>"
                                data-fin="<?php echo htmlspecialchars($boite["date_fin"]); ?>"
                                data-societe="<?php echo htmlspecialchars($boite["societe_intitule"]); ?>"
                                data-site="<?php echo htmlspecialchars($boite["site_intitule"]); ?>"
                                data-service="<?php echo htmlspecialchars($boite["service_intitule"]); ?>"
                                data-direction="<?php echo htmlspecialchars($boite["direction_intitule"]); ?>"
                                data-typeboite="<?php echo htmlspecialchars($boite["type_boite"]); ?>">
                                <i class="bi bi-eye"></i> Vue
                            </button>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Aucune boîte trouvée</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal pour afficher une boîte -->
    <div class="modal fade" id="viewBoiteModal" tabindex="-1" role="dialog" aria-labelledby="viewBoiteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewBoiteModalLabel">Informations sur la Boîte</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Nom Boîte:</strong> <span id="view_nom_boite"></span></p>
                    <p><strong>Libellé Cotation:</strong> <span id="view_libelle_cotation"></span></p>
                    <p><strong>Date Création:</strong> <span id="view_date_creation"></span></p>
                    <p><strong>Nom Admin:</strong> <span id="view_nom_admin"></span></p>
                    <p><strong>Durée Conservation:</strong> <span id="view_duree_conservation"></span></p>
                    <p><strong>Date Début:</strong> <span id="view_date_debut"></span></p>
                    <p><strong>Date Fin:</strong> <span id="view_date_fin"></span></p>
                    <p><strong>Société:</strong> <span id="view_societe"></span></p>
                    <p><strong>Site:</strong> <span id="view_site"></span></p>
                    <p><strong>Service:</strong> <span id="view_service"></span></p>
                    <p><strong>Direction:</strong> <span id="view_direction"></span></p>
                    <p><strong>Type de Boîte:</strong> <span id="view_type_boite"></span></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Filtrer les cartes en fonction de la recherche
            $('#search').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#boiteContainer .boite-card').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            $('#viewBoiteModal').on('show.bs.modal', function(e) {
                var button = $(e.relatedTarget);
                var modal = $(this);
                modal.find('#view_nom_boite').text(button.data('nom'));
                modal.find('#view_libelle_cotation').text(button.data('cotation'));
                modal.find('#view_date_creation').text(button.data('creation'));
                modal.find('#view_nom_admin').text(button.data('admin'));
                modal.find('#view_duree_conservation').text(button.data('conservation'));
                modal.find('#view_date_debut').text(button.data('debut'));
                modal.find('#view_date_fin').text(button.data('fin'));
                modal.find('#view_societe').text(button.data('societe'));
                modal.find('#view_site').text(button.data('site'));
                modal.find('#view_service').text(button.data('service'));
                modal.find('#view_direction').text(button.data('direction'));
                modal.find('#view_type_boite').text(button.data('typeboite'));
            });
        });
    </script>
</body>
</html>
