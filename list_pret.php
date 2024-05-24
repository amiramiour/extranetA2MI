<?php
// Inclusion du fichier de connexion à la base de données
session_start();
include "connexion/traitement_connexion.php";
// Fonction de connexion à la base de données
function connexionbdd()
{
    global $pdo;
    return $pdo;
}

// Connexion à la base de données
$db = connexionbdd();

// Vérifiez si une requête POST a été soumise pour la suppression d'un prêt
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'supprimer' && isset($_POST['pret_id'])) {
    // Récupérez l'ID du prêt à supprimer
    $pret_id = $_POST['pret_id'];

    // Exécutez une requête SQL pour mettre à jour l'attribut pret_active du prêt à 0
    $query = "UPDATE pret SET pret_active = 0 WHERE pret_id = :pret_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':pret_id', $pret_id, PDO::PARAM_INT);
    $stmt->execute();

    // Redirigez l'utilisateur vers la même page après la suppression
    header("Location: {$_SERVER['PHP_SELF']}");
    exit;
}

// Requête SQL pour récupérer les données des prêts avec les noms et prénoms des membres
$query = "
    SELECT p.pret_id, p.pret_materiel, p.pret_caution, p.pret_datein, p.pret_dateout, p.users_id, m.users_name, m.users_firstname, pe.etat_intitule, p.commentaire
    FROM pret p
    INNER JOIN users m ON p.users_id = m.users_id
    INNER JOIN pret_etat pe ON p.pret_etat = pe.id_etat_pret
    WHERE p.pret_active = 1
    ORDER BY p.pret_id DESC";

// Préparation et exécution de la requête
$stmt = $db->query($query);
$prets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des prêts</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>

    <!-- ================== BEGIN core-css ================== -->
    <link href="assets/css/vendor.min.css" rel="stylesheet"/>
    <link href="assets/css/transparent/app.min.css" rel="stylesheet"/>
    <!-- ================== END core-css ================== -->

    <!-- ================== BEGIN page-css ================== -->
    <link href="assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet"/>
    <link href="assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet"/>
    <link href="assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet"/>
    <link href="assets/plugins/datatables.net-colreorder-bs5/css/colReorder.bootstrap5.min.css" rel="stylesheet"/>
    <link href="assets/plugins/datatables.net-keytable-bs5/css/keyTable.bootstrap5.min.css" rel="stylesheet"/>
    <link href="assets/plugins/datatables.net-rowreorder-bs5/css/rowReorder.bootstrap5.min.css" rel="stylesheet"/>
    <link href="assets/plugins/datatables.net-select-bs5/css/select.bootstrap5.min.css" rel="stylesheet"/>

    <link href="assets/css/custom.css" rel="stylesheet"/>
    <!-- ================== END page-css ================== -->

</head>
<?php
require_once('includes/_functions.php');
require_once('connexion/traitement_connexion.php');
if ($_SESSION['role'] == 'admin'):
    ?>
    <body>
    <!-- BEGIN page-cover -->
    <div class="app-cover"></div>
    <!-- END page-cover -->

    <!-- BEGIN #loader -->
    <div id="loader" class="app-loader">
        <span class="spinner"></span>
    </div>
    <!-- END #loader -->

    <!-- BEGIN #app -->
    <div id="app" class="app app-header-fixed app-sidebar-fixed app-content-full-height">
        <?php require_once('includes/header.php'); ?>
        <?php require_once('includes/sidebar.php'); ?>
        <?php require_once('includes/_requests.php'); ?>

        <!-- BEGIN #content -->
        <div id="content" class="app-content">
            <!-- BEGIN breadcrumb -->
            <ol class="breadcrumb float-xl-end">
                <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                <li class="breadcrumb-item active">Prêts</li>
            </ol>
            <!-- END breadcrumb -->
            <!-- BEGIN page-header -->
            <h1 class="page-header">Liste des Prêts <small></small></h1>
            <!-- END page-header -->
            <!-- BEGIN panel -->
            <div class="panel panel-inverse">
                <!-- BEGIN panel-heading -->
                <div class="panel-heading">
                    <h4 class="panel-title">Liste des Prêts</h4>
                </div>
                <!-- END panel-heading -->
                <!-- BEGIN panel-body -->
                <div class="panel-body">
                    <table id="data-table-default" class="table table-striped table-bordered align-middle">
                        <thead>
                        <tr>
                            <th class="text-nowrap">ID</th>
                            <th class="text-nowrap">Emprunteur</th>
                            <th class="text-nowrap">État</th>
                            <th class="text-nowrap">Date de début</th>
                            <th class="text-nowrap">Date de retour</th>
                            <th class="text-nowrap">Caution</th>
                            <th class="text-nowrap">Commentaire</th>
                            <th class="text-nowrap">Matériel</th>
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <th class="text-nowrap">Actions</th>
                            <?php endif; ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($prets as $pret) : ?>
                            <tr>
                                <td>
                                    <a href="fiche_pret.php?pret_id=<?= $pret['pret_id'] ?>">
                                        <?= $pret['pret_id'] ?>
                                    </a>
                                </td>
                                <td>
                                    <a href="profile.php?id=<?= $pret['users_id'] ?>"><?= $pret['users_name'] . ' ' . $pret['users_firstname'] ?></a>
                                </td>
                                <td><?= $pret['etat_intitule'] ?></td>
                                <td><?= date('d/m/Y', strtotime($pret['pret_datein'])) ?></td>
                                <td><?= date('d/m/Y', strtotime($pret['pret_dateout'])) ?></td>
                                <td><?= $pret['pret_caution'] ?></td>
                                <td><?= $pret['commentaire'] ?></td>
                                <td><?= $pret['pret_materiel'] ?></td>
                                <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <td>
                                        <a href="modifier_pret.php?id=<?= $pret['pret_id'] ?>" class="btn btn-primary">Modifier</a>
                                        <form action="" method="post" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce prêt ?');">
                                            <input type="hidden" name="action" value="supprimer">
                                            <input type="hidden" name="pret_id" value="<?= $pret['pret_id'] ?>">
                                            <button type="submit" class="btn btn-danger">Supprimer</button>
                                        </form>

                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <!-- END panel-body -->
            </div>
            <!-- END panel -->
        </div>
        <!-- END #content -->

        <!-- BEGIN scroll-top-btn -->
        <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top"
           data-toggle="scroll-to-top"><i class="fa fa-angle-up"></i></a>
        <!-- END scroll-top-btn -->
    </div>
    <!-- END #app -->

    <!-- ================== BEGIN core-js ================== -->
    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.min.js"></script>
    <!-- ================== END core-js ================== -->

    <!-- ================== BEGIN page-js ================== -->
    <script src="assets/plugins/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
    <script src="assets/plugins/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="assets/plugins/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
    <script src="assets/plugins/datatables.net-buttons/js/buttons.colVis.min.js"></script>
    <script src="assets/plugins/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="assets/plugins/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="assets/plugins/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="assets/plugins/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="assets/plugins/datatables.net-colreorder/js/dataTables.colReorder.min.js"></script>
    <script src="assets/plugins/datatables.net-rowreorder/js/dataTables.rowReorder.min.js"></script>
    <script src="assets/plugins/datatables.net-select/js/dataTables.select.min.js"></script>
    <script src="assets/plugins/select2/dist/js/select2.min.js"></script>
    <script src="assets/plugins/datatables.net-fixedcolumns/js/dataTables.fixedColumns.min.js"></script>
    <script src="assets/plugins/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="assets/js/tables/table_liste_bi.js"></script>
    <!-- ================== END page-js ================== -->
    </body>
<?php
else:
    header('Location: ../erreurs/403.php');
endif;
?>
</html>
