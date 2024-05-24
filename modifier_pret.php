<?php
// Inclure le fichier de connexion à la base de données
session_start();
require "connexion/traitement_connexion.php";

function connexionbdd()
{
    global $pdo;
    return $pdo;
}

// Vérifier si l'identifiant du prêt à modifier est spécifié dans l'URL
if (isset($_GET['id'])) {
    $pret_id = $_GET['id'];
} else {
    // Rediriger vers une page d'erreur si l'identifiant du prêt n'est pas spécifié
    header("Location: erreur.php");
    exit();
}

// Inclure la connexion à la base de données

try {
    // Etablir la connexion à la base de données
    $db = connexionbdd();

    // Requête SQL pour récupérer les détails du prêt à modifier
    $query = "SELECT pret_caution, pret_mode, pret_dateout, commentaire, pret_etat FROM pret WHERE pret_id = :id";

    // Préparer la requête SQL
    $stmt = $db->prepare($query);

    // Liaison des valeurs des paramètres de requête
    $stmt->bindParam(':id', $pret_id, PDO::PARAM_INT);

    // Exécution de la requête
    $stmt->execute();

    // Récupération du résultat
    $pret = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si le prêt existe
    if ($pret) {
        // Afficher le formulaire de modification avec les champs pré-remplis
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8"/>
            <title>A2MI | Modifier Prêt</title>
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
                <ol class="breadcrumb float-xl-end">
                    <li class="breadcrumb-item"><a href="javascript:;">Prêt</a></li>
                    <li class="breadcrumb-item active">Modifier Prêt</li>
                </ol>
                <h1 class="page-header">Modifier Prêt</h1>

                <div class="row">
                    <div class="col-xl-6">
                        <div class="panel panel-inverse" data-sortable-id="form-validation-1">
                            <div class="panel-heading">
                                <h4 class="panel-title">Modifier les détails du prêt</h4>
                            </div>
                            <div class="panel-body">
                                <form action="./inc_extranet/traitement_modification_pret.php" method="post"
                                      class="form-horizontal"
                                      data-parsley-validate="true" name="demo-form">
                                    <div class="form-group row mb-3">
                                        <label class="col-lg-4 col-form-label form-label" for="caution">Caution:</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" id="caution" name="caution"
                                                   value="<?php echo $pret['pret_caution']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label class="col-lg-4 col-form-label form-label" for="mode_paiement">Mode de
                                            paiement:</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" id="mode_paiement"
                                                   name="mode_paiement" value="Chèque" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label class="col-lg-4 col-form-label form-label" for="date_rendu">Date de
                                            rendu:</label>
                                        <div class="col-lg-8">
                                            <input type="date" class="form-control" id="date_rendu" name="date_rendu"
                                                   value="<?php echo $pret['pret_dateout']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label class="col-lg-4 col-form-label form-label" for="etat">État:</label>
                                        <div class="col-lg-8">
                                            <select class="form-control" id="etat" name="etat" required>
                                                <option value="1" <?php if ($pret['pret_etat'] == 1) echo "selected"; ?>>
                                                    En cours
                                                </option>
                                                <option value="2" <?php if ($pret['pret_etat'] == 2) echo "selected"; ?>>
                                                    Terminé
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label class="col-lg-4 col-form-label form-label"
                                               for="commentaire">Commentaire:</label>
                                        <div class="col-lg-8">
                                            <textarea class="form-control" id="commentaire" name="commentaire" rows="4"
                                                      required><?php echo $pret['commentaire']; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <div class="col-lg-8 offset-lg-4">
                                            <button type="submit" class="btn btn-primary">Enregistrer les
                                                modifications
                                            </button>
                                            <input type="hidden" name="pret_id" value="<?php echo $pret_id; ?>">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <?php require_once('includes/footer.php'); ?>
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
        <script src="assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
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
        <?php
    } else {
        // Afficher un message d'erreur si le prêt n'est pas trouvé
        echo "Aucun prêt trouvé avec cet identifiant.";
    }
} catch (PDOException $e) {
    // Afficher un message d'erreur en cas d'erreur PDO
    echo "Erreur : " . $e->getMessage();
}
?>
