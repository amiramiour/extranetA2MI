<?php
include "../gestion_session.php";

// Maintenant, l'utilisateur est connecté et n'est pas un client, donc affichez le formulaire
$client_id = $_GET['id']; //la on recupere l'id passé dans l'url
?>
<!DOCTYPE html>


<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de prêt</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
</head>
<body>
<?php include '../navbar.php'; ?>

<div class="container mt-5">
    <h2>Formulaire de prêt</h2>
    <form action="traitement_pret.php" method="POST">
        <input type="hidden" name="client_id" value="<?php echo $client_id; ?>">
        <div class="form-group">
            <label for="pret_materiel">Prêt matériel *</label>
            <input type="text" class="form-control" id="pret_materiel" name="pret_materiel" required>
        </div>
        <div class="form-group">
            <label for="valeurMat">Valeur du matériel *</label>
            <input type="number" class="form-control" id="valeurMat" name="valeurMat" required>
        </div>
        <div class="form-group">
            <label for="pret_caution">Caution</label>
            <input type="number" class="form-control" id="pret_caution" name="pret_caution" value="0">
        </div>
        <div class="form-group">
            <label for="pret_mode">Mode de paiement *</label>
            <select class="form-control" id="pret_mode" name="pret_mode" required>
                <option value="cheque">Chèque</option>
            </select>
        </div>
        <div class="form-group">
            <label for="pret_datein">Date de prêt *</label>
            <div class="input-group">
                <input type="text" class="form-control datepicker" id="pret_datein" name="pret_datein" required>
                <div class="input-group-append">
                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="pret_dateout">Date de retour *</label>
            <div class="input-group">
                <input type="text" class="form-control datepicker" id="pret_dateout" name="pret_dateout" required>
                <div class="input-group-append">
                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="commentaire">Commentaire</label>
            <textarea class="form-control" id="commentaire" name="commentaire" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Valider</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.fr.min.js"></script>
<script>
    $(document).ready(function(){
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            language: 'fr'
        });
    });
</script>
</body>
</html>