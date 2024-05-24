<?php
// Inclure le fichier de connexion à la base de données
session_start();
require "connexion/traitement_connexion.php";

function connexionbdd()
{
    global $pdo;
    return $pdo;
}

// Maintenant, l'utilisateur est connecté et n'est pas un client, donc affichez le formulaire
$client_id = $_GET['id']; // On récupère l'id passé dans l'URL
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de prêt</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>

    <!-- ================== BEGIN core-css ================== -->

    <link href="assets/css/vendor.min.css" rel="stylesheet"/>
    <link href="assets/css/transparent/app.min.css" rel="stylesheet"/>
    <link href="assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet"/>
    <link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet"/>
    <link href="assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css" rel="stylesheet"/>
    <link href="assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet"/>
    <link href="assets/plugins/ion-rangeslider/css/ion.rangeSlider.min.css" rel="stylesheet"/>
    <link href="assets/plugins/tag-it/css/jquery.tagit.css" rel="stylesheet"/>
    <link href="assets/plugins/spectrum-colorpicker2/dist/spectrum.min.css" rel="stylesheet"/>
    <link href="assets/plugins/select-picker/dist/picker.min.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">


    <!-- ================== END core-css ================== -->
</head>

<?php
require_once('includes/_functions.php');
require_once('connexion/traitement_connexion.php');
if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin' && isset($_SESSION['mail'])) :
?>
<body class="app app-header-fixed app-sidebar-fixed app-content-full-height">
<!-- BEGIN page-cover -->
<div class="app-cover"></div>
<!-- END page-cover -->

<!-- BEGIN #loader -->
<div id="loader" class="app-loader">
    <span class="spinner"></span>
</div>
<!-- END #loader -->

<!-- BEGIN #app -->
<div id="app">
    <?php require_once('includes/header.php'); ?>
    <?php require_once('includes/sidebar.php'); ?>
    <?php require_once('includes/_requests.php'); ?>

    <!-- BEGIN #content -->
    <div id="content" class="app-content container mt-5">
        <h2>Formulaire de prêt</h2>
        <form action="inc_extranet/traitement_pret.php" method="POST">
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
                    <input type="text" class="form-control" id="pret_datein" name="pret_datein"
                           value="<?php echo date('d/m/Y'); ?>" readonly required>
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
    <!-- END #content -->
    <?php require_once('includes/footer.php'); ?>

    <!-- BEGIN scroll-top-btn -->
    <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top" data-toggle="scroll-to-top"><i
                class="fa fa-angle-up"></i></a>
    <!-- END scroll-top-btn -->
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.fr.min.js"></script>
<script src="assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>

<script>
    $(document).ready(function(){
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            language: 'fr'
        });
    });
</script>


<!-- END #app -->

<!-- ================== BEGIN core-js ================== -->
<script src="assets/js/vendor.min.js"></script>
<script src="assets/js/app.min.js"></script>
<!-- ================== END core-js ================== -->

<!-- ================== BEGIN page-js ================== -->
<script src="assets/plugins/parsleyjs/dist/parsley.min.js"></script>
<script src="assets/plugins/moment/min/moment.min.js"></script>
<script src="assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="assets/plugins/select2/dist/js/select2.min.js"></script>
<script src="assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
<script src="assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js"></script>
<script src="assets/plugins/tag-it/js/tag-it.min.js"></script>
<script src="assets/plugins/spectrum-colorpicker2/dist/spectrum.min.js"></script>
<script src="assets/plugins/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
<script src="assets/plugins/select-picker/dist/picker.min.js"></script>
<script src="assets/js/custom.js"></script>


<!-- ================== END page-js ================== -->
</body>
</html>
<?php else : ?>
    <script>
        window.location.href = 'connexion.php';
    </script>
<?php endif; ?>
