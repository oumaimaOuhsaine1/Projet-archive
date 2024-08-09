
  // ------------------js compte--------------
  $(document).ready(function() {
    $('#adminTable').DataTable();

    // Modal pour ajouter un admin
    var addAdminModal = document.getElementById("addAdminModal");
    var addAdminBtn = document.getElementById("addAdminBtn");
    var addAdminSpan = document.getElementsByClassName("close")[0];

    addAdminBtn.onclick = function() {
        addAdminModal.style.display = "block";
    }

    addAdminSpan.onclick = function() {
        addAdminModal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == addAdminModal) {
            addAdminModal.style.display = "none";
        }
    }

    // Modal pour éditer un admin
    var editAdminModal = document.getElementById("editAdminModal");
    var closeModalSpan = document.getElementsByClassName("close")[1];

    $(document).on('click', '.editBtn', function() {
        var id = $(this).data('id');
        var username = $(this).data('username');
        var role = $(this).data('role');

        $('#editUserId').val(id);
        $('#editUsername').val(username);
        $('#editRole').val(role);
        editAdminModal.style.display = "block";
    });

    closeModalSpan.onclick = function() {
        editAdminModal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == editAdminModal) {
            editAdminModal.style.display = "none";
        }
    }

    $(document).on('click', '.deleteBtn', function() {
      var userId = $(this).data('id');
      console.log('ID utilisateur:', userId);
  
      if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
          $.ajax({
              url: '../compte/compte.php',
              type: 'POST',
              data: { delete_id: userId },
              success: function(response) {
                  console.log('Réponse du serveur:', response);
                  if (response === 'success') {
                      alert('Utilisateur supprimé avec succès');
                      location.reload();  // Recharge la page pour mettre à jour la liste
                  } else {
                      alert('Erreur lors de la suppression de l\'utilisateur');
                  }
              },
              error: function(xhr, status, error) {
                  console.error('Erreur AJAX:', status, error);
              }
          });
      }
  });
  
  
  
  
});

// ---------------script de boite ------------------------------
$(document).ready(function() {
    $('#boiteTable').DataTable();
});

function updateDates() {
    const dateCreation = document.getElementById('date_creation').value;
    const dureeConservation = document.getElementById('duree_conservation').value;

    if (dateCreation) {
        document.getElementById('date_debut').value = dateCreation;
    }

    if (dateCreation && dureeConservation) {
        const dateDebut = new Date(dateCreation);
        const dateFin = new Date(dateDebut.setFullYear(dateDebut.getFullYear() + parseInt(dureeConservation)));
        document.getElementById('date_fin').value = dateFin.toISOString().split('T')[0];
    }
}



