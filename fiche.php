<!DOCTYPE html>
<html lang="fr" >
<head>
    <meta charset="utf-8" />
    <title>A2MI | Fiche</title>
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
if (isset($_SESSION['role']) AND $_SESSION['role'] == 'admin' AND isset($_SESSION['mail'])): ?>

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
        <ol class="breadcrumb hidden-print float-xl-end">
            <li class="breadcrumb-item"><a href="javascript:;">Accueil</a></li>
            <li class="breadcrumb-item active">Fiche</li>
        </ol>
        <!-- END breadcrumb -->
        <!-- BEGIN page-header -->
        <h1 class="page-header hidden-print">Fiche <small>(texte doit correspondre au format, commande, devis ...)</small></h1>
        <!-- END page-header -->
        <!-- BEGIN invoice -->
        <div class="invoice">
            <!-- BEGIN invoice-company -->
            <div class="invoice-company">
                Commande/Devis/SAV/BI ou Prêt n° ...
                <span class="float-end hidden-print">
                    <a href="javascript:;" class="btn btn-sm btn-white mb-10px"><i class="fa fa-file-pdf t-plus-1 text-danger fa-fw fa-lg"></i> Export as PDF</a>
                </span>
            </div>
            <!-- END invoice-company -->
            <!-- BEGIN invoice-header -->
            <div class="invoice-header">
                <div class="invoice-from">
                    <small>De</small>
                    <address class="mt-5px mb-5px">
                        <strong class="text-white">A2MI-Informatique</strong><br />
                        10-14 rue Jean Perrin<br />
                        17000 La Rochelle<br />
                        Tél : 09 51 52 42 86
                </div>
                <div class="invoice-to">
                    <small>à</small>
                    <address class="mt-5px mb-5px">
                        <strong class="text-white">Company Name</strong><br />
                        Street Address<br />
                        City, Zip Code<br />
                        Phone: (123) 456-7890
                    </address>
                </div>
                <div class="invoice-date">
                    <small>Invoice / July period</small>
                    <div class="date text-white mt-5px">August 3,2021</div>
                    <div class="invoice-detail">
                        #0000123DSS<br />
                        Services Product
                    </div>
                </div>
            </div>
            <!-- END invoice-header -->
            <!-- BEGIN invoice-content -->
            <div class="invoice-content">
                <!-- BEGIN table-responsive -->
                <div class="table-responsive">
                    <table class="table table-invoice">
                        <thead>
                        <tr>
                            <th>TASK DESCRIPTION</th>
                            <th class="text-center" width="10%">RATE</th>
                            <th class="text-center" width="10%">RATE</th>
                            <th class="text-center" width="10%">RATE</th>
                            <th class="text-center" width="10%">RATE</th>
                            <th class="text-center" width="10%">HOURS</th>
                            <th class="text-end" width="20%">LINE TOTAL</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <span class="text-white">Website design &amp; development</span><br />
                                <small>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed id sagittis arcu.</small>
                            </td>
                            <td class="text-center">$50.00</td>
                            <td class="text-center">$50.00</td>
                            <td class="text-center">$50.00</td>
                            <td class="text-center">$50.00</td>
                            <td class="text-center">50</td>
                            <td class="text-end">$2,500.00</td>
                        </tr>
                        <tr>
                            <td>
                                <span class="text-white">Branding</span><br />
                                <small>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed id sagittis arcu.</small>
                            </td>
                            <td class="text-center">$50.00</td>
                            <td class="text-center">$50.00</td>
                            <td class="text-center">$50.00</td>
                            <td class="text-center">$50.00</td>
                            <td class="text-center">40</td>
                            <td class="text-end">$2,000.00</td>
                        </tr>
                        <tr>
                            <td>
                                <span class="text-white">Redesign Service</span><br />
                                <small>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed id sagittis arcu.</small>
                            </td>
                            <td class="text-center">$50.00</td>
                            <td class="text-center">$50.00</td>
                            <td class="text-center">$50.00</td>
                            <td class="text-center">$50.00</td>
                            <td class="text-center">50</td>
                            <td class="text-end">$2,500.00</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <!-- END table-responsive -->
                <div class="m-3">
                    Zone de texte disponible pour toute information supplémentaire.
                </div>
                <!-- BEGIN invoice-price -->
                <div class="invoice-price">
                    <div class="invoice-price-left">
                        <div class="invoice-price-row">
                            <div class="sub-price">
                                <small>Prix HT</small>
                                <span class="text-white">$4,500.00</span>
                            </div>
                            <div class="sub-price">
                                <i class="fa fa-plus text-muted"></i>
                            </div>
                            <div class="sub-price">
                                <small>TVA</small>
                                <span class="text-white">$108.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="invoice-price-right">
                        <small>Prix TTC</small> <span class="fw-bold">$4508.00</span>
                    </div>
                </div>
                <!-- END invoice-price -->
            </div>
            <!-- END invoice-content -->
            <!-- BEGIN invoice-note -->
            <div class="invoice-note">
                * Make all cheques payable to [Your Company Name]<br />
                * Payment is due within 30 days<br />
                * If you have any questions concerning this invoice, contact  [Name, Phone Number, Email]
            </div>
            <!-- END invoice-note -->
            <!-- BEGIN invoice-footer -->
            <div class="invoice-footer">
                <p class="text-center mb-5px fw-bold">
                    MERCI DE NOUS FAIRE CONFIANCE
                </p>
                <p class="text-center">
                    <span class="me-10px"><i class="fa fa-fw fa-lg fa-globe"></i> https://wwww.a2mi-info.com</span>
                    <span class="me-10px"><i class="fa fa-fw fa-lg fa-phone-volume"></i> 09 51 52 42 86</span>
                    <span class="me-10px"><i class="fa fa-fw fa-lg fa-envelope"></i> contact@a2mi-info.fr</span>
                </p>
            </div>
            <!-- END invoice-footer -->
        </div>
        <!-- END invoice -->

        <!-- BEGIN row -->
        <div class="row mt-5">
            <!-- BEGIN col-12 -->
            <div class="col-xl-12">
                <div class="panel panel-inverse">
                    <!-- BEGIN panel-heading -->
                    <div class="panel-heading">
                        <h4 class="panel-title">Liste des actions</h4>
                    </div>
                    <!-- END panel-heading -->
                    <!-- BEGIN panel-body -->
                    <div class="panel-body">
                        <table id="data-table-combine" class="table table-striped table-bordered align-middle">
                            <thead>
                            <tr>
                                <th class="text-nowrap" width="1%">colonne</th>
                                <th class="text-nowrap w-150px">colonne</th>
                                <th class="text-nowrap w-100px">colonne</th>
                                <th class="text-nowrap w-200px">colonne</th>
                                <th class="text-nowrap w-200px">colonne</th>
                                <th class="text-nowrap w-150px">colonne</th>
                                <th class="text-nowrap w-75px">colonne</th>
                                <th class="text-nowrap w-75px">colonne</th>
                                <th class="text-nowrap w-200px">colonne</th>
                                <th class="text-nowrap w-50px">colonne</th>
                                <th class="text-nowrap w-50px">colonne</th>
                                <th class="text-nowrap w-50px">colonne</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr class="odd gradeX">
                                    <td width="1%">colonne</td>
                                    <td>colonne</td>
                                    <td>colonne</td>
                                    <td>colonne</td>
                                    <td>colonne</td>
                                    <td>colonne</td>
                                    <td>colonne</td>
                                    <td>colonne</td>
                                    <td>colonne</td>
                                    <td>colonne</td>
                                    <td>colonne</td>
                                    <td>colonne</td>
                                </tr>
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