<?php
session_start();
include "connexion/traitement_connexion.php";
// Fonction de connexion à la base de données
function connexionbdd()
{
    global $pdo;
    return $pdo;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'supprimer' && isset($_POST['bi_id'])) {
    // Récupérer l'ID du bon d'intervention à supprimer
    $bi_id = $_POST['bi_id'];

    try {
        $db = connexionbdd();

        // Mettre à jour l'attribut bi_active dans la base de données
        $update_query = $db->prepare("UPDATE bi SET bi_active = 0 WHERE bi_id = ?");
        $update_query->execute([$bi_id]);

        // Redirection vers bonIntervention.php après la suppression
        header('Location: list_bi.php');
        exit;
    } catch (PDOException $e) {
        echo "Erreur lors de la suppression du bon d'intervention : " . $e->getMessage();
    }
}

try {
    $db = connexionbdd();

    $query = $db->prepare("SELECT bi.*, 
                                users.users_id AS client_id,
                                users.users_name AS client_nom, 
                                users.users_firstname AS client_prenom, 
                                technicien.users_name AS technicien_nom, 
                                technicien.users_firstname AS technicien_prenom 
                        FROM bi 
                        INNER JOIN users ON bi.users_id = users.users_id 
                        INNER JOIN users AS technicien ON bi.bi_technicien = technicien.users_id 
                        WHERE bi.bi_active = ?
                        ORDER BY bi.bi_id DESC"); // Tri par ID décroissant



    $query->execute([1]);
    $resultats = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur lors de la récupération des données : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Bons d'Intervention</title>
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
                <li class="breadcrumb-item active">Bons d'Intervention</li>
            </ol>
            <!-- END breadcrumb -->
            <!-- BEGIN page-header -->
            <h1 class="page-header">Liste des Bons d'Intervention <small></small></h1>
            <!-- END page-header -->
            <!-- BEGIN panel -->
            <div class="panel panel-inverse">
                <!-- BEGIN panel-heading -->
                <div class="panel-heading">
                    <h4 class="panel-title">Liste des Bons d'Intervention</h4>
                </div>
                <!-- END panel-heading -->
                <!-- BEGIN panel-body -->
                <div class="panel-body">
                    <table id="data-table-default" class="table table-striped table-bordered align-middle">
                        <thead>
                        <tr>
                            <th class="text-nowrap">ID</th>
                            <th class="text-nowrap">Client</th>
                            <th class="text-nowrap">Active</th>
                            <th class="text-nowrap">Technicien</th>
                            <th class="text-nowrap">Facture</th>
                            <th class="text-nowrap">Garantie</th>
                            <th class="text-nowrap">Contrat</th>
                            <th class="text-nowrap">Service</th>
                            <th class="text-nowrap">Envoi</th>
                            <th class="text-nowrap">Facturation</th>
                            <th class="text-nowrap">Date de Facturation</th>
                            <th class="text-nowrap">Paiement</th>
                            <th class="text-nowrap">Date de Création</th>
                            <th class="text-nowrap">Heure d'Arrivée</th>
                            <th class="text-nowrap">Heure de Départ</th>
                            <th class="text-nowrap">Commentaire</th>
                            <th class="text-nowrap">Réglé</th>
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <th class="text-nowrap">Actions</th>
                            <?php endif; ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($resultats as $bi): ?>
                            <tr>
                                <td><a href="fiche_bi.php?bi_id=<?= $bi['bi_id'] ?>"><?= $bi['bi_id'] ?></a></td>
                                <td>
                                    <a href="profile.php?id=<?= $bi['client_id'] ?>"><?= $bi['client_nom'] . ' ' . $bi['client_prenom'] ?></a>
                                </td>
                                <td><?= $bi['bi_active'] ?></td>
                                <td><?= $bi['technicien_nom'] . ' ' . $bi['technicien_prenom']; ?></td>
                                <td><?= $bi['bi_facture'] ?></td>
                                <td><?= $bi['bi_garantie'] ?></td>
                                <td><?= $bi['bi_contrat'] ?></td>
                                <td><?= $bi['bi_service'] ?></td>
                                <td><?= $bi['bi_envoi'] ?></td>
                                <td><?= $bi['bi_facturation'] ?></td>
                                <td><?= $bi['bi_datefacturation'] ?></td>
                                <td><?= $bi['bi_paiement'] ?></td>
                                <td><?= date('d/m/Y H:i:s', $bi['bi_datein']) ?></td>
                                <td><?= $bi['bi_heurearrive'] ?></td>
                                <td><?= $bi['bi_heuredepart'] ?></td>
                                <td><?= $bi['bi_commentaire'] ?></td>
                                <td><?= $bi['bi_regle'] ?></td>
                                <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <td>
                                        <a href="modification_bi.php?bi_id=<?= $bi['bi_id'] ?>" class="btn btn-primary">Modifier</a>
                                        <form action="list_bi.php" method="post" style="display: inline;">
                                            <input type="hidden" name="action" value="supprimer">
                                            <input type="hidden" name="bi_id" value="<?= $bi['bi_id'] ?>">
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce bon d\'intervention ?')">Supprimer</button>
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
    <script src="assets/js/demo/table-manage-default.demo.js"></script>
    <!-- ================== END page-js ================== -->
    </body>
<?php else: ?>
    <div class="container">
        <h1 class="mt-5">Accès refusé</h1>
        <p>Vous n'avez pas les permissions nécessaires pour accéder à cette page.</p>
    </div>
<?php endif; ?>
</html>
