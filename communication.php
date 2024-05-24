<!DOCTYPE html>
<html lang="fr" >
<head>
    <meta charset="utf-8" />
    <title>A2MI | Communication</title>
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

    <link href="assets/plugins/lightbox2/dist/css/lightbox.css" rel="stylesheet" />

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
                <li class="breadcrumb-item active">Communication</li>
            </ol>
            <!-- END breadcrumb -->
            <!-- BEGIN page-header -->
            <h1 class="page-header">Tableaux des informations de la page Communication<small></small></h1>
            <!-- END page-header -->
            <!-- BEGIN row -->
            <div class="row">
                <!-- BEGIN col-12 -->
                <div class="col-xl-12">
                    <div class="panel panel-inverse">
                        <!-- BEGIN panel-heading -->
                        <div class="panel-heading">
                            <h4 class="panel-title">Liste des projets</h4>
                        </div>
                        <!-- END panel-heading -->
                        <!-- BEGIN panel-body -->
                        <div class="panel-body">
                            <table id="data-table-combine" class="table table-striped table-bordered align-middle">
                                <thead>
                                <tr>
                                    <th width="1%" class="text-nowrap">#</th>
                                    <th class="text-nowrap w-20px">Active</th>
                                    <th class="text-nowrap w-50px">Logo</th>
                                    <th class="text-nowrap w-50px">Titre</th>
                                    <th class="text-nowrap w-200px">Images</th>
                                    <th width="1%"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($allcommunications as $communication) : ?>
                                    <tr>
                                        <td><?= $communication->site_post_id ?></td>
                                        <td>
                                            <?php if ($communication->site_post_active >= 1) : ?>
                                                <i class="fas fa-lg fa-fw me-10px text-green fa-check"></i>
                                            <?php else : ?>
                                                <i class="fas fa-lg fa-fw me-10px text-red fa-xmark"></i>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($communication->site_post_photo)) : ?>
                                                <img src="img/logo/<?= $communication->site_post_photo ?>" class="rounded h-40px" />
                                            <?php else : ?>
                                                <img src="img/placeholder.jpg" class="rounded h-30px" />
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $communication->site_post_title ?></td>
                                        <td>
                                            <?php
                                            $req = $pdo->prepare("SELECT * FROM site_img WHERE site_img_id_post = '$communication->site_post_id'");
                                            $req->execute();
                                            $allcomimg = $req->fetchAll(PDO::FETCH_OBJ);
                                            ?>
                                            <?php foreach ($allcomimg as $comimg) : ?>
                                            <a href="img/com/<?= $comimg->site_img_photo ?>" data-lightbox="<?= $comimg->site_img_id_post ?>">
                                                <img src="img/com/<?= $comimg->site_img_photo ?>" class="rounded h-40px m-1" alt="<?= $comimg->site_img_title ?>" />
                                            </a>
                                            <?php endforeach; ?>
                                        </td>
                                        <td nowrap>
                                            <a href="#" class="btn btn-sm btn-warning w-40px me-1"><i class="fas fa-lg fa-fw me-10px fa-pen-to-square"></i></a>
                                            <a href="#" class="btn btn-sm btn-warning w-40px me-1"><i class="fas fa-lg
                                            fa-fw me-10px fa-images"></i></a>
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
    <script src="assets/plugins/isotope-layout/dist/isotope.pkgd.min.js"></script>
    <script src="assets/plugins/lightbox2/dist/js/lightbox.min.js"></script>
    <script src="assets/js/demo/gallery.demo.js"></script>

    <!-- ================== END page-js ================== -->

    </body>
<?php else : ?>
    <?php require_once('login.php'); ?>
<?php endif; ?>
</html>