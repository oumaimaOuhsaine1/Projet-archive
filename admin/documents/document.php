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

$sql = "SELECT d.*, s.intitule as service_intitule, si.intitule as site_intitule, so.intitule as societe_intitule
        FROM document d
        LEFT JOIN service s ON d.id_service = s.id_service
        LEFT JOIN site si ON d.id_site = si.id_site
        LEFT JOIN societe so ON d.id_societe = so.id_societe
        WHERE d.id_boite = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $id_boite);
if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

$documents = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Documents</title>
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
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Gestion des Documents</h1>
        <div class="d-flex justify-content-between mb-3">
            <button class="btn btn-custom" onclick="window.location.href='ajout_document.php?id_boite=<?php echo $id_boite; ?>'">
                <i class="bi bi-plus-circle"></i> Ajouter un Document
            </button>
        </div>
        <div class="row" id="documentContainer">
            <?php if ($documents && $documents->num_rows > 0): ?>
                <?php while($document = $documents->fetch_assoc()): ?>
                    <div class="col-md-3 document-card" data-id="<?php echo $document['id_document']; ?>" data-nom="<?php echo htmlspecialchars($document["type_document"]); ?>" data-service="<?php echo htmlspecialchars($document["service_intitule"]); ?>" data-site="<?php echo htmlspecialchars($document["site_intitule"]); ?>" data-societe="<?php echo htmlspecialchars($document["societe_intitule"]); ?>" data-infos="<?php echo htmlspecialchars($document["informations_completes"]); ?>" data-annee="<?php echo htmlspecialchars($document["annee"]); ?>" data-responsable="<?php echo htmlspecialchars($document["responsable_classement"]); ?>" data-filepath="<?php echo htmlspecialchars($document["file_path"]); ?>">
                        <div class="card">
                            <h5><?php echo $document["type_document"]; ?></h5>
                            <img src="../../img/document_img.png" alt="Document Icon">
                            <p><?php echo $document["type_document"]; ?></p>
                            <small><?php echo $document["annee"]; ?></small>
                            <button class="btn btn-warning btn-sm" onclick="event.stopPropagation(); window.location.href='modif_document.php?id=<?php echo $document['id_document']; ?>&id_boite=<?php echo $id_boite; ?>'">
                                <i class="bi bi-pencil-square"></i> Modifier
                            </button>
                            <button class="btn btn-info btn-sm view-document-btn" data-id="<?php echo $document['id_document']; ?>">
                                <i class="bi bi-eye"></i> Vue
                            </button>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Aucun document trouvé</p>
            <?php endif; ?>
        </div>
        <a href="../emplacement/select_location.php?id_boite=<?php echo $id_boite; ?>" class="btn btn-primary mt-3">Next</a>
    </div>

    <!-- Modal pour afficher un document -->
    <div class="modal fade" id="viewDocumentModal" tabindex="-1" role="dialog" aria-labelledby="viewDocumentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewDocumentModalLabel">Informations sur le Document</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Type de Document:</strong> <span id="view_type_document"></span></p>
                    <p><strong>Service:</strong> <span id="view_service"></span></p>
                    <p><strong>Site:</strong> <span id="view_site"></span></p>
                    <p><strong>Société:</strong> <span id="view_societe"></span></p>
                    <p><strong>Informations Complètes:</strong> <span id="view_informations_completes"></span></p>
                    <p><strong>Année:</strong> <span id="view_annee"></span></p>
                    <p><strong>Responsable du Classement:</strong> <span id="view_responsable_classement"></span></p>
                    <p><strong>Fichier:</strong> <a id="view_file_path" href="#" target="_blank">Voir le fichier</a></p>
                </div>
            </div>
        </div>
    </div>
   
    <script>
        $(document).ready(function() {
            $('.view-document-btn').on('click', function() {
                var button = $(this);
                var id = button.data('id');

                // Faire une requête AJAX pour obtenir les données du document en fonction de l'id
                $.ajax({
                    url: 'get_document_details.php', // Vous devez créer ce fichier pour gérer la requête AJAX
                    method: 'POST',
                    data: { id_document: id },
                    success: function(response) {
                        var document = JSON.parse(response);

                        $('#viewDocumentModal #view_type_document').text(document.type_document);
                        $('#viewDocumentModal #view_service').text(document.service_intitule);
                        $('#viewDocumentModal #view_site').text(document.site_intitule);
                        $('#viewDocumentModal #view_societe').text(document.societe_intitule);
                        $('#viewDocumentModal #view_informations_completes').text(document.informations_completes);
                        $('#viewDocumentModal #view_annee').text(document.annee);
                        $('#viewDocumentModal #view_responsable_classement').text(document.responsable_classement);
                        $('#viewDocumentModal #view_file_path').attr('href', document.file_path).text('Voir le fichier');

                        $('#viewDocumentModal').modal('show');
                    },
                    error: function() {
                        alert('Erreur lors de la récupération des données du document.');
                    }
                });
            });
        });
    </script>
</body>
</html>
