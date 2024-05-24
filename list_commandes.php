
<!DOCTYPE html>
<html lang="fr" >
<head>
    <meta charset="utf-8" />
    <title>A2MI | Commandes</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />

    <!-- ================== BEGIN core-css ================== -->
    <link href="assets/css/vendor.min.css" rel="stylesheet" />
    <link href="assets/css/transparent/app.min.css" rel="stylesheet" />
    <!-- ================== END core-css ================== -->

    <!-- ================== BEGIN page-css ================== -->
    <link href="assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" />
    <link href="assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet" />
    <link href="assets/plugins/datatables.net-colreorder-bs5/css/colReorder.bootstrap5.min.css" rel="stylesheet" />
    <link href="assets/plugins/datatables.net-keytable-bs5/css/keyTable.bootstrap5.min.css" rel="stylesheet" />
    <link href="assets/plugins/datatables.net-rowreorder-bs5/css/rowReorder.bootstrap5.min.css" rel="stylesheet" />
    <link href="assets/plugins/datatables.net-select-bs5/css/select.bootstrap5.min.css" rel="stylesheet" />

    <link href="assets/css/custom.css" rel="stylesheet" />
    <!-- ================== END page-css ================== -->
</head>
<?php
session_start();
require_once('includes/_functions.php');
require_once('connexion/traitement_connexion.php');
if (isset($_SESSION['role']) AND $_SESSION['role'] == 'admin' AND isset($_SESSION['mail'])):
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
                <li class="breadcrumb-item active">Commandes</li>
            </ol>
            <!-- END breadcrumb -->
            <!-- BEGIN page-header -->
            <h1 class="page-header">Liste des commandes<small></small></h1>
            <!-- END page-header -->
            <!-- BEGIN panel -->
            <div class="panel panel-inverse">
                <!-- BEGIN panel-heading -->
                <div class="panel-heading">
                    <h4 class="panel-title">Filtrer les commandes</h4>
                </div>
                <!-- END panel-heading -->
                <!-- BEGIN panel-body -->
                <div class="panel-body">

                    <table id="data-table-default" class="table table-striped table-bordered align-middle" name="table-commandes">
                        <thead>
                        <tr>
                            <th class="text-nowrap">Commande N°</th>
                            <th class="text-nowrap">Référence</th>
                            <th class="text-nowrap">Client</th>
                            <th class="text-nowrap">Entreprise</th>
                            <!--<th class="text-nowrap">Prix HT</th>-->
                            <th class="text-nowrap">Prix TTC</th>
                            <!--<th class="text-nowrap">Articles différents</th>
                            <th class="text-nowrap">Quantité totale demandée</th>-->
                            <th class="text-nowrap">Statut</th>
                            <!--<th class="text-nowrap">Date de création</th>-->
                            <th class="text-nowrap">Date de livraison</th>
                            <!--<th class="text-nowrap">Date souhaitée</th>-->
                            <th class="text_nowrap">Actions</th>
                            <!--<th class="text-nowrap">Adr. Livraison</th>
                            <th class="text-nowrap">Adr. Compl.</th>
                            <th class="text-nowrap">Code Postal</th>
                            <th class="text-nowrap">Ville</th>
                            <th class="text-nowrap">Mobile</th>
                            <th class="text-nowrap">Mail</th>-->
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($commandes as $commande) : ?>

                            <tr class="odd gradeX">
                                <td width="1%" class="fw-bold text-white"><?= $commande['cmd_devis_id'] ?></td>
                                <td><?= $commande['cmd_devis_reference'] ?></td>
                                <td><a href="profile.php?id=<?= $commande['users_id'] ?>"><?= $commande['nom_client'] . ' ' . $commande['client_prenom'] ?></a></td>
                                <td><?= $commande['users_entreprise'] ?></td>
                                <!--<td><?= $commande['cmd_devis_prixHT'] ?></td>-->
                                <td><?= $commande['cmd_devis_prixventettc'] ?></td>
                                <!--<td><?= $commande['nombre_produits'] ?></td>
                                <td><?= $commande['quantite_totale_demandee'] ?></td>-->

                                <td><?= $commande['cmd_devis_etat'] ?></td>

                                <!--<td><?= date('Y/m/d', $commande['cmd_devis_datein']) ?></td>-->
                                <td><?= date('Y/m/d', $commande['cmd_devis_dateout']) ?></td>
                                <!--<?php if ($commande['cmd_devis_dateSouhait'] != 0) : ?>
                                    <td><?= date('Y/m/d', $commande['cmd_devis_dateSouhait']) ?></td>
                                <?php else : ?>
                                    <td></td>
                                <?php endif; ?>-->
                                <td>
                                    <a href="fiche_commande.php?id=<?=$commande['cmd_devis_id']?>" class="btn btn-sm btn-primary w-100px mb-2">Détails</a>
                                    <a href="modif_commande_devis.php?id=<?= $commande['cmd_devis_id'] ?>" class="btn btn-sm btn-warning w-100px mb-2">Modifier</a>
                                    <a href="#modal-alert" class="btn btn-sm btn-danger w-100px" data-bs-toggle="modal" data-id="<?= $commande['cmd_devis_id'] ?>" onclick="setDeleteCommandId(this)">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <!-- END panel-body -->

            </div>
            <!-- END panel -->
            <?php require_once('includes/footer.php'); ?>
        </div>
        <!-- END #content -->

        <!-- #modal-alert -->
		<div class="modal fade" id="modal-alert">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Alerte suppression</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <form method="POST" id="deleteForm">
                        <div class="modal-body">
                            <div class="alert alert-danger">
                                <h5><i class="fa fa-info-circle"></i> Confirmer la suppression</h5>
                                <p>Êtes-vous sûr de vouloir supprimer cette commande ?</span></p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Annuler</a>
                            <button type="submit" name="delete" value="delete" class="btn btn-danger">Supprimer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- BEGIN scroll-top-btn -->
        <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top" data-toggle="scroll-to-top"><i class="fa fa-angle-up"></i></a>
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
    <script src="assets/js/demo/table-manage-default.demo.js"></script>
    <script src="assets/plugins/@highlightjs/cdn-assets/highlight.min.js"></script>
    <script src="assets/js/demo/render.highlight.js"></script>
    <!-- ================== END page-js ================== -->
    <script>
        function setDeleteCommandId(button) {
            var commandId = button.getAttribute('data-id');
            var form = document.getElementById('deleteForm');
            form.action = 'inc_extranet/traitement_suppression_cmd_devis.php?id=' + commandId;
            document.getElementById('modal-command-id').innerText = commandId; // Optional: To display ID in modal
        }
    </script>
    </body>
<?php else : ?>
    <?php require_once('login.php'); ?>
<?php endif; ?>
</html>