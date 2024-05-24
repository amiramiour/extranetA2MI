<!DOCTYPE html>
<html lang="fr" >
<head>
    <meta charset="utf-8" />
    <title>A2MI | Liste Fournisseurs</title>
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
            <li class="breadcrumb-item"><a href="javascript:;">Accueil</a></li>
            <li class="breadcrumb-item active">Liste Fournisseurs</li>
        </ol>
        <!-- END breadcrumb -->
        <!-- BEGIN page-header -->
        <h1 class="page-header">Liste des fournisseurs d'A2MI <small></small></h1>
        <!-- END page-header -->
        <!-- BEGIN row -->
        <div class="row">
            <!-- BEGIN col-12 -->
            <div class="col-xl-6">
                <div class="col-md-12 mb-2">
                    <a href="" class="btn btn-success btn-lg m-b-5">Ajouter un fournisseur</i></a>
                </div>
                <div class="panel panel-inverse">
                    <!-- BEGIN panel-heading -->
                    <div class="panel-heading">
                        <h4 class="panel-title">Liste des fournisseurs</h4>
                    </div>
                    <!-- END panel-heading -->
                    <!-- BEGIN panel-body -->
                    <div class="panel-body">
                        <table id="data-table-combine" class="table table-striped table-bordered align-middle">
                            <thead>
                            <tr>
                                <th width="1%" class="text-nowrap">N° Fournisseur</th>
                                <th class="text-nowrap w-20px">Active</th>
                                <th class="text-nowrap w-200px">Nom</th>
                                <th class="text-nowrap w-20px">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($allsuppliers as $fournisseur) : ?>
                                <tr class="odd gradeX">
                                    <td><?= $fournisseur->fournisseurs_id ?></td>
                                    <td>
                                        <?php if ($fournisseur->fournisseurs_active >= 1) : ?>
                                            <i class="fas fa-lg fa-fw me-10px text-green fa-check"></i>
                                        <?php else : ?>
                                            <i class="fas fa-lg fa-fw me-10px text-red fa-xmark"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $fournisseur->fournisseurs_name ?></td>
                                    <td nowrap>
                                        <a href="#" class="btn btn-sm btn-warning w-40px me-1">
                                            <i class="fas fa-lg fa-fw me-10px fa-pen-to-square"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- END panel-body -->
                </div>
                <!-- END panel -->
            </div>
            <!-- END col-12 -->
        </div>
        <!-- END row -->
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
<script src="assets/plugins/datatables.net-colreorder/js/dataTables.colReorder.min.js"></script>
<script src="assets/plugins/datatables.net-colreorder-bs5/js/colReorder.bootstrap5.min.js"></script>
<script src="assets/plugins/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="assets/plugins/datatables.net-keytable-bs5/js/keyTable.bootstrap5.min.js"></script>
<script src="assets/plugins/datatables.net-rowreorder/js/dataTables.rowReorder.min.js"></script>
<script src="assets/plugins/datatables.net-rowreorder-bs5/js/rowReorder.bootstrap5.min.js"></script>
<script src="assets/plugins/datatables.net-select/js/dataTables.select.min.js"></script>
<script src="assets/plugins/datatables.net-select-bs5/js/select.bootstrap5.min.js"></script>
<script src="assets/plugins/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="assets/plugins/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
<script src="assets/plugins/datatables.net-buttons/js/buttons.colVis.min.js"></script>
<script src="assets/plugins/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="assets/plugins/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="assets/plugins/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="assets/plugins/pdfmake/build/pdfmake.min.js"></script>
<script src="assets/plugins/pdfmake/build/vfs_fonts.js"></script>
<script src="assets/plugins/jszip/dist/jszip.min.js"></script>
<script src="assets/js/demo/table-manage-combine.demo.js"></script>
<script src="assets/plugins/@highlightjs/cdn-assets/highlight.min.js"></script>
<script src="assets/js/demo/render.highlight.js"></script>

<!-- ================== END page-js ================== -->

</body>
<?php else : ?>
    <?php require_once('login.php'); ?>
<?php endif; ?>
</html>