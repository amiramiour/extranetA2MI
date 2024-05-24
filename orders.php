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
                    <h4 class="panel-title">Liste des commandes</h4>
                </div>
                <!-- END panel-heading -->
                <!-- BEGIN panel-body -->
                <div class="panel-body">
                    <table id="data-table-default" class="table table-striped table-bordered align-middle">
                        <thead>
                        <tr>
                            <th width="1%"></th>
                            <th class="text-nowrap">Commande N°</th>
                            <th class="text-nowrap">Client</th>
                            <th class="text-nowrap">Prix HT</th>
                            <th class="text-nowrap">Prix TTC</th>
                            <th class="text-nowrap">Articles différents</th>
                            <th class="text-nowrap">Quantité totale</th>
                            <th class="text-nowrap">Statut</th>
                            <th class="text-nowrap">Date Création</th>
                            <th class="text-nowrap">Date Validation</th>
                            <th class="text-nowrap">Date Envoi</th>
                            <th class="text-nowrap">Adr. Livraison</th>
                            <th class="text-nowrap">Adr. Compl.</th>
                            <th class="text-nowrap">Code Postal</th>
                            <th class="text-nowrap">Ville</th>
                            <th class="text-nowrap">Mobile</th>
                            <th class="text-nowrap">Mail</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($resumOrders as $resumOrder) : ?>

                            <tr class="odd gradeX">
                                <td width="1%" class="fw-bold text-white"><?= $resumOrder->shop_commande_id ?></td>
                                <td>
                                    <a href="profile.php?id=<?= $resumOrder->users_id ?>" class="fs-14px fw-bolder">
                                        A2MI-<?= str_pad($resumOrder->shop_commande_id, 5, "0", STR_PAD_LEFT) ?>
                                    </a>
                                </td>
                                <td><?= $resumOrder->users_name ?> <?= $resumOrder->users_firstname ?></td>
                                <td><?= number_format($resumOrder->shop_commande_prix_total / (1 + 20/100),2) ?></td>
                                <td><?= number_format($resumOrder->shop_commande_prix_total,2) ?></td>
                                <td><?= $resumOrder->CPTE ?></td>
                                <td><?= $resumOrder->QTE ?></td>
                                <td>
                                    <?php if($resumOrder->shop_commande_statut_id == 1 ): ?>
                                        <span class="badge border border-warning text-warning px-2 pt-5px pb-5px rounded fs-13px d-inline-flex align-items-center"><i class="fa fa-clock fs-15px fa-fw me-5px"></i> En cours</span>
                                    <?php endif; ?>
                                    <?php if($resumOrder->shop_commande_statut_id == 2 ): ?>
                                        <span class="badge border border-danger text-danger px-2 pt-5px pb-5px rounded fs-13px d-inline-flex align-items-center"><i class="fa fa-circle-xmark fs-15px fa-fw me-5px"></i> Annulée</span>
                                    <?php endif; ?>
                                    <?php if($resumOrder->shop_commande_statut_id == 3 ): ?>
                                        <span class="badge border border-info text-info px-2 pt-5px pb-5px rounded fs-13px d-inline-flex align-items-center"><i class="fa fa-thumbs-up fs-15px fa-fw me-5px"></i> Validée</span>
                                    <?php endif; ?>
                                    <?php if($resumOrder->shop_commande_statut_id == 4 ): ?>
                                        <span class="badge border border-success text-success px-2 pt-5px pb-5px rounded fs-13px d-inline-flex align-items-center"><i class="fa fa-circle-check fs-15px fa-fw me-5px"></i> Envoyée</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $resumOrder->shop_commande_date ?></td>
                                <td><?= $resumOrder->shop_commande_date_validation ?></td>
                                <td><?= $resumOrder->shop_commande_date_envoie ?></td>

                                <td><?= $resumOrder->shop_commande_address ?></td>
                                <td><?= $resumOrder->shop_commande_address_compl ?></td>
                                <td><?= $resumOrder->shop_commande_postcode ?></td>
                                <td><?= $resumOrder->shop_commande_city ?></td>
                                <td><?= $resumOrder->shop_commande_mobile ?></td>
                                <td><?= $resumOrder->shop_commande_mail ?></td>
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
    </body>
<?php else : ?>
    <?php require_once('login.php'); ?>
<?php endif; ?>
</html>