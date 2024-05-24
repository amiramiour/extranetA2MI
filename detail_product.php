<!DOCTYPE html>
<html lang="fr" >
<head>
    <meta charset="utf-8" />
    <title>A2MI | Détail Produit</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />

    <!-- ================== BEGIN core-css ================== -->
    <link href="assets/css/vendor.min.css" rel="stylesheet" />
    <link href="assets/css/transparent/app.min.css" rel="stylesheet" />

    <link href="assets/css/custom.css" rel="stylesheet" />
    <!-- ================== END core-css ================== -->
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
<div id="app" class="app app-header-fixed app-sidebar-fixed">
    <?php require_once('includes/header.php'); ?>
    <?php require_once('includes/sidebar.php'); ?>

    <!-- BEGIN #content -->
    <div id="content" class="app-content">
        <!-- BEGIN breadcrumb -->
        <ol class="breadcrumb float-xl-end">
            <li class="breadcrumb-item"><a href="javascript:;">Accueil</a></li>
            <li class="breadcrumb-item active">Détail Produit</li>
        </ol>
        <!-- END breadcrumb -->
        <!-- BEGIN page-header -->
        <h1 class="page-header">Détail du produit<small></small></h1>
        <!-- END page-header -->
        <!-- BEGIN row -->
        <div class="row">
            <!-- BEGIN col-12 -->
            <div class="col-md-12">

                <!-- BEGIN filter-pagination -->
                <div class="d-block d-md-flex align-items-center mb-3">
                    <!-- BEGIN pagination -->
                    <div class="ms-auto d-none d-lg-block">
                        <ul class="pagination mb-0">
                            <li class="page-item disabled"><a href="javascript:;" class="page-link">«</a></li>
                            <li class="page-item active"><a href="javascript:;" class="page-link">1</a></li>
                            <li class="page-item"><a href="javascript:;" class="page-link">2</a></li>
                            <li class="page-item"><a href="javascript:;" class="page-link">3</a></li>
                            <li class="page-item"><a href="javascript:;" class="page-link">4</a></li>
                            <li class="page-item"><a href="javascript:;" class="page-link">5</a></li>
                            <li class="page-item"><a href="javascript:;" class="page-link">6</a></li>
                            <li class="page-item"><a href="javascript:;" class="page-link">7</a></li>
                            <li class="page-item"><a href="javascript:;" class="page-link">»</a></li>
                        </ul>
                    </div>
                    <!-- END pagination -->
                </div>
                <!-- END filter-pagination -->

                <!-- BEGIN result-list -->
                <div class="result-list">
                    <div class="result-item">
                        <a href="#" class="result-image" style="background-image: url(assets/img/gallery/gallery-51.jpg)"></a>
                        <div class="result-info">
                            <h4 class="title"><a href="javascript:;">Commande du * Date de création *.</a></h4>
                            <p class="location">* Nom du client *</p>
                            <p>Produits achetés (sous forme de liste)</p>
                            <ul>
                                <li>Lorem ipsum dolor sit amet</li>
                                <li>Nulla volutpat aliquam velit</li>
                            </ul>
                            <div class="btn-row">
                                <a href="javascript:;" data-toggle="tooltip" data-container="body" data-title="Tasks"><i class="fa fa-fw fa-tasks"></i></a> <!-- Lien vers la page de l'acheteur /client -->
                                <a href="javascript:;" data-toggle="tooltip" data-container="body" data-title="Configuration"><i class="fa fa-fw fa-cog"></i></a> <!-- Lien vers un formulaire pour éditer le fait qu'elle ait été envoyée -->
                                <a href="javascript:;" data-toggle="tooltip" data-container="body" data-title="Users"><i class="fa fa-fw fa-user"></i></a> <!-- Lien vers la page de l'acheteur /client -->
                            </div>
                        </div>
                        <div class="result-price"> $92,101
                            <small>* Date envoi * <!-- Si date envoi, l'afficher, sinon laisser vide -->
                                <div class="userimage check-size">
                                    <i class="fa fa-check-circle text-green ms-1"></i>
                                    <i class="fa fa-clock text-orange ms-1"></i>
                                </div>
                            </small>
                        </div>
                    </div>
                    <div class="result-item">
                        <a href="#" class="result-image" style="background-image: url(assets/img/gallery/gallery-52.jpg)"></a>
                        <div class="result-info">
                            <h4 class="title"><a href="javascript:;">Commande du * Date de création *.</a></h4>
                            <p class="location">* Nom du client *</p>
                            <p>Produits achetés (sous forme de liste)</p>
                            <ul>
                                <li>Lorem ipsum dolor sit amet</li>
                                <li>Nulla volutpat aliquam velit</li>
                            </ul>
                            <div class="btn-row">
                                <a href="javascript:;" data-toggle="tooltip" data-container="body" data-title="Tasks"><i class="fa fa-fw fa-tasks"></i></a> <!-- Lien vers la page de l'acheteur /client -->
                                <a href="javascript:;" data-toggle="tooltip" data-container="body" data-title="Configuration"><i class="fa fa-fw fa-cog"></i></a> <!-- Lien vers un formulaire pour éditer le fait qu'elle ait été envoyée -->
                                <a href="javascript:;" data-toggle="tooltip" data-container="body" data-title="Users"><i class="fa fa-fw fa-user"></i></a> <!-- Lien vers la page de l'acheteur /client -->
                            </div>
                        </div>
                        <div class="result-price"> $92,101
                            <small>* Date envoi * <!-- Si date envoi, l'afficher, sinon laisser vide -->
                                <div class="userimage check-size">
                                    <i class="fa fa-check-circle text-green ms-1"></i>
                                    <i class="fa fa-clock text-orange ms-1"></i>
                                </div>
                            </small>
                        </div>
                    </div>
                </div>
                <!-- END result-list -->

                <!-- BEGIN pagination -->
                <div class="d-flex mt-20px">
                    <ul class="pagination ms-auto me-auto me-lg-0">
                        <li class="page-item disabled"><a href="javascript:;" class="page-link">«</a></li>
                        <li class="page-item active"><a href="javascript:;" class="page-link">1</a></li>
                        <li class="page-item"><a href="javascript:;" class="page-link">2</a></li>
                        <li class="page-item"><a href="javascript:;" class="page-link">3</a></li>
                        <li class="page-item"><a href="javascript:;" class="page-link">4</a></li>
                        <li class="page-item"><a href="javascript:;" class="page-link">5</a></li>
                        <li class="page-item"><a href="javascript:;" class="page-link">6</a></li>
                        <li class="page-item"><a href="javascript:;" class="page-link">7</a></li>
                        <li class="page-item"><a href="javascript:;" class="page-link">»</a></li>
                    </ul>
                </div>
                <!-- END pagination -->
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
</body>
<?php else : ?>
    <?php require_once('login.php'); ?>
<?php endif; ?>
</html>