<?php
// Inclure le fichier de connexion à la base de données
session_start();
require "connexion/traitement_connexion.php";

function connexionbdd()
{
    global $pdo;
    return $pdo;
}

// Vérifier si l'identifiant du bon d'intervention est passé dans l'URL
if (isset($_GET['bi_id'])) {
    $db = connexionbdd();
    // Récupérer l'identifiant du bon d'intervention depuis l'URL
    $bi_id = $_GET['bi_id'];

    // Préparer la requête SQL pour récupérer les détails du bon d'intervention
    $query = $db->prepare("SELECT * FROM bi WHERE bi_id = ?");
    $query->execute([$bi_id]);
    $bi = $query->fetch();

    // Vérifier si les détails du bon d'intervention sont disponibles
    if (isset($bi) && !empty($bi)) {
        // Récupérer d'autres détails du bon d'intervention
        $date = date('d/m/Y', strtotime($bi['bi_datein'])); // Formater la date
        // Ajouter d'autres détails du bon d'intervention ici
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire Bon d'Intervention Client</title>
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
    <link href="assets/css/custom.css" rel="stylesheet"/>
    <!-- ================== END core-css ================== -->
</head>
<?php
require_once('includes/_functions.php');
require_once('connexion/traitement_connexion.php');
if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin' && isset($_SESSION['mail'])) :
?>
<body>
<div class="app-cover"></div>
<div id="loader" class="app-loader">
    <span class="spinner"></span>
</div>
<div id="app" class="app app-header-fixed app-sidebar-fixed app-content-full-height">
    <?php require_once('includes/header.php'); ?>
    <?php require_once('includes/sidebar.php'); ?>
    <div id="content" class="app-content">
        <div class="container">
            <!-- Affichage des détails du bon d'intervention -->
            <div class="card mt-5">
                <div class="card-body">
                    <h5 class="card-title">Détails du bon d'intervention</h5>
                    <p class="card-text">ID du bon d'intervention : <?php echo $bi_id; ?></p>
                    <p class="card-text">Date du bon d'intervention
                        : <?php echo date('d/m/Y', ($bi['bi_datein'])); ?></p>
                </div>
            </div>

            <div class="card mt-5">
                <div class="card-body">
                    <h5 class="panel-title" style="background-color: black; color: white; padding: 10px;">Modifier le bon d'intervention</h5>
                    <!-- Le reste du contenu de la carte -->
                    <form action="./inc_extranet/traitement_modif_bi.php" method="post">
                        <input type="hidden" name="bi_id" value="<?php echo $bi_id; ?>">

                        <!-- Champs de modification -->
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="facturer" id="facturer">
                            <label class="form-check-label" for="facturer">Facturer</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="garantie" id="garantie">
                            <label class="form-check-label" for="garantie">Garantie</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="contrat_pack" id="contrat_pack">
                            <label class="form-check-label" for="contrat_pack">Contrat/Pack</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="service_personne"
                                   id="service_personne">
                            <label class="form-check-label" for="service_personne">Service à la personne</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="regle" id="regle">
                            <label class="form-check-label" for="regle">Réglé</label>
                        </div>

                        <div class="form-group">
                            <label for="facturation">Facturation *</label>
                            <select class="form-control" id="facturation" name="facturation">
                                <option value="immediate">Immédiate</option>
                                <option value="differee">Différée</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="commentaire">Laisser un commentaire</label>
                            <textarea class="form-control" id="commentaire" name="commentaire" rows="3"></textarea>
                        </div>

                        <!-- Bouton "Valider" -->
                        <button type="submit" class="btn btn-primary">Valider</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- ================== BEGIN core-js ================== -->
    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.min.js"></script>
    <!-- ================== END core-js ================== -->

    <!-- ================== BEGIN page-js ================== -->
    <script src="assets/plugins/parsleyjs/dist/parsley.min.js"></script>
    <script src="assets/plugins/moment/min/moment.min.js"></script>
    <script src="assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="assets/plugins/select2/dist/js/select2.min.js"></script>
    <script src="assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js">
    <script src="assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
    <script src="assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js"></script>
    <script src="assets/plugins/jquery.maskedinput/src/jquery.maskedinput.js"></script>
    <script src="assets/plugins/jquery-migrate/dist/jquery-migrate.min.js"></script>
    <script src="assets/plugins/tag-it/js/tag-it.min.js"></script>
    <script src="assets/plugins/clipboard/dist/clipboard.min.js"></script>
    <script src="assets/plugins/spectrum-colorpicker2/dist/spectrum.min.js"></script>
    <script src="assets/plugins/select-picker/dist/picker.min.js"></script>
    <script src="assets/js/demo/form-plugins.demo.js"></script>
    <script src="assets/plugins/@highlightjs/cdn-assets/highlight.min.js"></script>
    <script src="assets/js/demo/render.highlight.js"></script>
    <!-- ================== END page-js ================== -->
</body>
</html>
<?php else : ?>
    <script>
        window.location.href = 'connexion.php';
    </script>
<?php endif; ?>
