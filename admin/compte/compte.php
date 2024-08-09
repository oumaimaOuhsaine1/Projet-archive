<?php
$conn = new mysqli('localhost', 'root', '', 'archive');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_admin'])) {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $conn->query("INSERT INTO admin (username, password) VALUES ('$username', '$password')");
    } elseif (isset($_POST['edit_admin'])) {
        $id = $_POST['id'];
        $username = $_POST['username'];
        $conn->query("UPDATE admin SET username='$username' WHERE id='$id'");
    } elseif (isset($_POST['reset_password'])) {
        $id = $_POST['id'];
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $conn->query("UPDATE admin SET password='$new_password' WHERE id='$id'");
    }
}

$admins = $conn->query("SELECT * FROM admin");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des administrateurs</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="container">
    <br>
<a href="../index.php" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Retour</a>

    <h1 class="mt-5">Gestion des administrateurs</h1> </br>
    <button class="btn btn-success" data-toggle="modal" data-target="#addModal"><i class="fas fa-plus"></i> Ajouter un administrateur</button> </br></br>

    <table class="table mt-3" id="adminTable">
        <thead>
            <tr>
                <th>Nom d'utilisateur</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($admin = $admins->fetch_assoc()): ?>
                <tr>
                    <td><?= $admin['username'] ?></td>
                    <td>
                        <button class="btn btn-primary btn-edit" data-id="<?= $admin['id'] ?>" data-username="<?= $admin['username'] ?>"><i class="fas fa-edit"></i> Modifier</button>
                        <button class="btn btn-warning btn-reset" data-id="<?= $admin['id'] ?>"><i class="fas fa-key"></i> Réinitialiser le mot de passe</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal d'ajout -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Ajouter un administrateur</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>   
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="username">Nom d'utilisateur</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary" name="add_admin">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de modification -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Modifier l'administrateur</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="form-group">
                        <label for="edit-username">Nom d'utilisateur</label>
                        <input type="text" class="form-control" name="username" id="edit-username" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary" name="edit_admin">Modifier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de réinitialisation -->
<div class="modal fade" id="resetModal" tabindex="-1" role="dialog" aria-labelledby="resetModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="resetModalLabel">Réinitialiser le mot de passe</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="reset-id">
                    <div class="form-group">
                        <label for="new-password">Nouveau mot de passe</label>
                        <input type="password" class="form-control" name="new_password" id="new-password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary" name="reset_password">Réinitialiser</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#adminTable').DataTable();

        $('.btn-edit').click(function() {
            var id = $(this).data('id');
            var username = $(this).data('username');
            $('#edit-id').val(id);
            $('#edit-username').val(username);
            $('#editModal').modal('show');
        });

        $('.btn-reset').click(function() {
            var id = $(this).data('id');
            $('#reset-id').val(id);
            $('#resetModal').modal('show');
        });
    });
</script>
</body>
</html>
