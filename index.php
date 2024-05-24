<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <title>A2MI | Index</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>

    <!-- ================== BEGIN core-css ================== -->
    <link href="assets/css/vendor.min.css" rel="stylesheet"/>
    <link href="assets/css/transparent/app.min.css" rel="stylesheet"/>
    <link href="assets/css/custom.css" rel="stylesheet"/>
    <!-- ================== END core-css ================== -->

    <!-- ================== BEGIN page-css ================== -->
    <link href="assets/plugins/jvectormap-next/jquery-jvectormap.css" rel="stylesheet"/>
    <link href="assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css" rel="stylesheet"/>

    <!-- CSS pour notification -->
    <!--<link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />-->
    <!-- ================== END page-css ================== -->
</head>
<?php
session_start();
require_once('includes/_functions.php');
require_once('connexion/traitement_connexion.php');
if (isset($_SESSION['role']) and $_SESSION['role'] == 'admin' and isset($_SESSION['mail'])) { ?>
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

        <!-- BEGIN #content -->
        <div id="content" class="app-content d-flex flex-column p-0">
            <!-- BEGIN content-container -->
            <div class="app-content-padding flex-grow-1 overflow-hidden" data-scrollbar="true" data-height="100%">
                <!-- BEGIN breadcrumb -->
                <ol class="breadcrumb float-xl-end">
                    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
                    <li class="breadcrumb-item"><a href="javascript:;">Page Options</a></li>
                    <li class="breadcrumb-item active">Page with Footer</li>
                </ol>
                <!-- END breadcrumb -->
                <!-- BEGIN page-header -->
                <h1 class="page-header">Page d'accueil <small>A2MI-Informatique</small></h1>
                <!-- END page-header -->

                <!-- BEGIN row -->
                <div class="row">
                    <!-- BEGIN col-3 -->
                    <div class="col-xl-2 col-sm-6">
                        <div class="widget widget-stats bg-gradient-lime-dgray">
                            <div class="stats-icon stats-icon-lg"><i class="fa fa-globe fa-fw"></i></div>
                            <div class="stats-content">
                                <div class="stats-title">TODAY'S VISITS</div>
                                <div class="stats-number">7,842,900</div>
                                <div class="stats-progress progress">
                                    <div class="progress-bar" style="width: 70.1%;"></div>
                                </div>
                                <div class="stats-desc">Better than last week (70.1%)</div>
                            </div>
                            <div class="stats-link">
                                <a href="javascript:;">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- END col-3 -->
                    <!-- BEGIN col-3 -->
                    <div class="col-xl-2 col-sm-6">
                        <div class="widget widget-stats bg-gradient-cyan-dgray">
                            <div class="stats-icon stats-icon-lg"><i class="fa fa-dollar-sign fa-fw"></i></div>
                            <div class="stats-content">
                                <div class="stats-title">TODAY'S PROFIT</div>
                                <div class="stats-number">180,200</div>
                                <div class="stats-progress progress">
                                    <div class="progress-bar" style="width: 40.5%;"></div>
                                </div>
                                <div class="stats-desc">Better than last week (40.5%)</div>
                            </div>
                            <div class="stats-link">
                                <a href="javascript:;">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- END col-3 -->
                    <!-- BEGIN col-3 -->
                    <div class="col-xl-2 col-sm-6">
                        <div class="widget widget-stats bg-gradient-indigo-dgray">
                            <div class="stats-icon stats-icon-lg"><i class="fa fa-archive fa-fw"></i></div>
                            <div class="stats-content">
                                <div class="stats-title">NEW ORDERS</div>
                                <div class="stats-number">38,900</div>
                                <div class="stats-progress progress">
                                    <div class="progress-bar" style="width: 76.3%;"></div>
                                </div>
                                <div class="stats-desc">Better than last week (76.3%)</div>
                            </div>
                            <div class="stats-link">
                                <a href="javascript:;">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- END col-3 -->
                    <!-- BEGIN col-3 -->
                    <div class="col-xl-2 col-sm-6">
                        <div class="widget widget-stats bg-gradient-pink-dgray">
                            <div class="stats-icon stats-icon-lg"><i class="fa fa-comment-alt fa-fw"></i></div>
                            <div class="stats-content">
                                <div class="stats-title">NEW COMMENTS</div>
                                <div class="stats-number">3,988</div>
                                <div class="stats-progress progress">
                                    <div class="progress-bar" style="width: 54.9%;"></div>
                                </div>
                                <div class="stats-desc">Better than last week (54.9%)</div>
                            </div>
                            <div class="stats-link">
                                <a href="javascript:;">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- END col-3 -->
                    <!-- BEGIN col-3 -->
                    <div class="col-xl-2 col-sm-6">
                        <div class="widget widget-stats bg-gradient-red-dgray">
                            <div class="stats-icon stats-icon-lg"><i class="fa fa-desktop fa-fw"></i></div>
                            <div class="stats-content">
                                <div class="stats-title">TODAY'S VISITS</div>
                                <div class="stats-number">7,842,900</div>
                                <div class="stats-progress progress">
                                    <div class="progress-bar" style="width: 70.1%;"></div>
                                </div>
                                <div class="stats-desc">Better than last week (70.1%)</div>
                            </div>
                            <div class="stats-link">
                                <a href="javascript:;">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- END col-3 -->
                    <!-- BEGIN col-3 -->
                    <div class="col-xl-2 col-sm-6">
                        <div class="widget widget-stats bg-gradient-orange-dgray">
                            <div class="stats-icon stats-icon-lg"><i class="fa fa-users fa-fw"></i></div>
                            <div class="stats-content">
                                <div class="stats-title">NEW ORDERS</div>
                                <div class="stats-number">38,900</div>
                                <div class="stats-progress progress">
                                    <div class="progress-bar" style="width: 76.3%;"></div>
                                </div>
                                <div class="stats-desc">Better than last week (76.3%)</div>
                            </div>
                            <div class="stats-link">
                                <a href="javascript:;">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- END col-3 -->
                </div>
                <!-- END row -->

                <!-- BEGIN row -->
                <div class="row">
                    <!-- BEGIN col-8 -->
                    <div class="col-xl-8">
                        <!-- BEGIN tabs -->
                        <ul class="nav nav-tabs nav-tabs-inverse nav-justified" data-sortable-id="index-2">
                            <li class="nav-item">
                                <a href="#purchase" data-bs-toggle="tab" class="nav-link active">
                                    <i class="fa fa-camera fa-lg me-5px"></i>
                                    <span class="d-none d-md-inline">BI</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#purchase2" data-bs-toggle="tab" class="nav-link">
                                    <i class="fa fa-archive fa-lg me-5px"></i>
                                    <span class="d-none d-md-inline">SAV</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#purchase3" data-bs-toggle="tab" class="nav-link">
                                    <i class="fa fa-envelope fa-lg me-5px"></i>
                                    <span class="d-none d-md-inline">Prêts</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#purchase4" data-bs-toggle="tab" class="nav-link">
                                    <i class="fa fa-envelope fa-lg me-5px"></i>
                                    <span class="d-none d-md-inline">Commandes/Devis</span>
                                </a>
                            </li>
                        </ul>
                        <?php
                        // Inclure la connexion à la base de données
                        require_once 'connexion/config.php';
                        include "connexion/traitement_connexion.php";

                        function connexionbdd()
                        {
                            global $pdo;
                            return $pdo;
                        }

                        // Etablir la connexion à la base de données
                        $db = connexionbdd();

                        // Requête SQL pour récupérer les données nécessaires
                        // Requête SQL pour récupérer les données nécessaires (triées par date décroissante et limitées aux 30 derniers jours)
                        $sql = "SELECT bi.bi_id, bi.bi_date, intervention.inter_intervention AS intervention, 
           ROUND(intervention.inter_total * 1.20, 2) AS prixtotal_tva, 
           CONCAT(client.users_firstname, ' ', client.users_name) AS client,
           client.users_id AS client_id,
           CONCAT(technicien.users_firstname, ' ', technicien.users_name) AS technicien
    FROM bi
    INNER JOIN intervention ON bi.bi_id = intervention.bi_id
    INNER JOIN users AS client ON bi.users_id = client.users_id
    INNER JOIN users AS technicien ON bi.bi_technicien = technicien.users_id
    WHERE bi.bi_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    ORDER BY bi.bi_id DESC"; // Tri par ID décroissant


                        // Préparer la déclaration SQL
                        $stmt = $db->prepare($sql);

                        // Exécuter la requête
                        $stmt->execute();

                        // Récupérer les résultats
                        $bi_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <div class="tab-content panel rounded-0 rounded-bottom mb-20px" data-sortable-id="index-3">
                            <div class="tab-pane fade active show" id="purchase" role="tabpanel">
                                <!-- BEGIN BI -->
                                <div class="tab-content panel rounded-0 rounded-bottom mb-20px" data-sortable-id="index-3">
                                    <div class="tab-pane fade active show" id="purchase" role="tabpanel">
                                        <div class="h-500px" data-scrollbar="true">
                                            <table class="table table-panel mb-0">
                                                <thead>
                                                <tr>
                                                    <th class="text-white">ID</th>
                                                    <th class="text-white">Date</th>
                                                    <th class="hidden-sm text-center">Intervention</th>
                                                    <th>Prix Total avec TVA</th>
                                                    <th>Client</th>
                                                    <th>Technicien</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach ($bi_data as $bi) : ?>
                                                    <tr>
                                                        <td class="fw-bold text-muted text-white">
                                                            <a href="fiche_bi.php?bi_id=<?php echo $bi['bi_id']; ?>" class="text-white"><?php echo $bi['bi_id']; ?></a>
                                                        </td>
                                                        <td class="hidden-sm text"><?php echo date('d/m/Y', $bi['bi_date']); ?></td>
                                                        <td class="hidden-sm text-center"><?php echo $bi['intervention']; ?></td>
                                                        <td class="text-nowrap"><?php echo $bi['prixtotal_tva']; ?></td>
                                                        <td>
                                                            <a href="profile.php?id=<?= $bi['client_id'] ?>">
                                                                <?= $bi['client'] ?>
                                                            </a>
                                                        </td>


                                                        <td class="text-nowrap"><?php echo $bi['technicien']; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- end bi -->
                            </div>
                            <div class="tab-pane fade" id="purchase2">
                                <div class="h-500px" data-scrollbar="true">
                                    <table class="table table-panel mb-0">
                                        <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th class="hidden-sm text-center">Product</th>
                                            <th></th>
                                            <th>Amount</th>
                                            <th>User</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="fw-bold text-muted">13/02/2021</td>
                                            <td class="hidden-sm text-center">
                                                <a href="javascript:;">
                                                    <img src="assets/img/product/product-1.png" alt="" width="32px"/>
                                                </a>
                                            </td>
                                            <td class="text-nowrap">
                                                <h6><a href="javascript:;" class="text-white text-decoration-none">Nunc
                                                        eleifend lorem eu velit eleifend, <br/>eget faucibus nibh
                                                        placerat.</a></h6>
                                            </td>
                                            <td class="text-blue fw-bold">$349.00</td>
                                            <td class="text-nowrap"><a href="javascript:;"
                                                                       class="text-white text-decoration-none">Derick
                                                    Wong</a></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">13/02/2021</td>
                                            <td class="hidden-sm text-center">
                                                <a href="javascript:;">
                                                    <img src="assets/img/product/product-2.png" alt="" width="32px"/>
                                                </a>
                                            </td>
                                            <td class="text-nowrap">
                                                <h6><a href="javascript:;" class="text-white text-decoration-none">Nunc
                                                        eleifend lorem eu velit eleifend, <br/>eget faucibus nibh
                                                        placerat.</a></h6>
                                            </td>
                                            <td class="text-blue fw-bold">$399.00</td>
                                            <td class="text-nowrap"><a href="javascript:;"
                                                                       class="text-white text-decoration-none">Derick
                                                    Wong</a></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">13/02/2021</td>
                                            <td class="hidden-sm text-center">
                                                <a href="javascript:;">
                                                    <img src="assets/img/product/product-3.png" alt="" width="32px"/>
                                                </a>
                                            </td>
                                            <td class="text-nowrap">
                                                <h6><a href="javascript:;" class="text-white text-decoration-none">Nunc
                                                        eleifend lorem eu velit eleifend, <br/>eget faucibus nibh
                                                        placerat.</a></h6>
                                            </td>
                                            <td class="text-blue fw-bold">$499.00</td>
                                            <td class="text-nowrap"><a href="javascript:;"
                                                                       class="text-white text-decoration-none">Derick
                                                    Wong</a></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">13/02/2021</td>
                                            <td class="hidden-sm text-center">
                                                <a href="javascript:;">
                                                    <img src="assets/img/product/product-4.png" alt="" width="32px"/>
                                                </a>
                                            </td>
                                            <td class="text-nowrap">
                                                <h6><a href="javascript:;" class="text-white text-decoration-none">Nunc
                                                        eleifend lorem eu velit eleifend, <br/>eget faucibus nibh
                                                        placerat.</a></h6>
                                            </td>
                                            <td class="text-blue fw-bold">$230.00</td>
                                            <td class="text-nowrap"><a href="javascript:;"
                                                                       class="text-white text-decoration-none">Derick
                                                    Wong</a></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">13/02/2021</td>
                                            <td class="hidden-sm text-center">
                                                <a href="javascript:;">
                                                    <img src="assets/img/product/product-5.png" alt="" width="32px"/>
                                                </a>
                                            </td>
                                            <td class="text-nowrap">
                                                <h6><a href="javascript:;" class="text-white text-decoration-none">Nunc
                                                        eleifend lorem eu velit eleifend, <br/>eget faucibus nibh
                                                        placerat.</a></h6>
                                            </td>
                                            <td class="text-blue fw-bold">$500.00</td>
                                            <td class="text-nowrap"><a href="javascript:;"
                                                                       class="text-white text-decoration-none">Derick
                                                    Wong</a></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="purchase3">
                                <div class="h-500px" data-scrollbar="true">
                                    <table class="table table-panel mb-0">
                                        <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th class="hidden-sm text-center">Product</th>
                                            <th></th>
                                            <th>Amount</th>
                                            <th>User</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="fw-bold text-muted">13/02/2021</td>
                                            <td class="hidden-sm text-center">
                                                <a href="javascript:;">
                                                    <img src="assets/img/product/product-1.png" alt="" width="32px"/>
                                                </a>
                                            </td>
                                            <td class="text-nowrap">
                                                <h6><a href="javascript:;" class="text-white text-decoration-none">Nunc
                                                        eleifend lorem eu velit eleifend, <br/>eget faucibus nibh
                                                        placerat.</a></h6>
                                            </td>
                                            <td class="text-blue fw-bold">$349.00</td>
                                            <td class="text-nowrap"><a href="javascript:;"
                                                                       class="text-white text-decoration-none">Derick
                                                    Wong</a></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">13/02/2021</td>
                                            <td class="hidden-sm text-center">
                                                <a href="javascript:;">
                                                    <img src="assets/img/product/product-2.png" alt="" width="32px"/>
                                                </a>
                                            </td>
                                            <td class="text-nowrap">
                                                <h6><a href="javascript:;" class="text-white text-decoration-none">Nunc
                                                        eleifend lorem eu velit eleifend, <br/>eget faucibus nibh
                                                        placerat.</a></h6>
                                            </td>
                                            <td class="text-blue fw-bold">$399.00</td>
                                            <td class="text-nowrap"><a href="javascript:;"
                                                                       class="text-white text-decoration-none">Derick
                                                    Wong</a></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">13/02/2021</td>
                                            <td class="hidden-sm text-center">
                                                <a href="javascript:;">
                                                    <img src="assets/img/product/product-3.png" alt="" width="32px"/>
                                                </a>
                                            </td>
                                            <td class="text-nowrap">
                                                <h6><a href="javascript:;" class="text-white text-decoration-none">Nunc
                                                        eleifend lorem eu velit eleifend, <br/>eget faucibus nibh
                                                        placerat.</a></h6>
                                            </td>
                                            <td class="text-blue fw-bold">$499.00</td>
                                            <td class="text-nowrap"><a href="javascript:;"
                                                                       class="text-white text-decoration-none">Derick
                                                    Wong</a></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">13/02/2021</td>
                                            <td class="hidden-sm text-center">
                                                <a href="javascript:;">
                                                    <img src="assets/img/product/product-4.png" alt="" width="32px"/>
                                                </a>
                                            </td>
                                            <td class="text-nowrap">
                                                <h6><a href="javascript:;" class="text-white text-decoration-none">Nunc
                                                        eleifend lorem eu velit eleifend, <br/>eget faucibus nibh
                                                        placerat.</a></h6>
                                            </td>
                                            <td class="text-blue fw-bold">$230.00</td>
                                            <td class="text-nowrap"><a href="javascript:;"
                                                                       class="text-white text-decoration-none">Derick
                                                    Wong</a></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">13/02/2021</td>
                                            <td class="hidden-sm text-center">
                                                <a href="javascript:;">
                                                    <img src="assets/img/product/product-5.png" alt="" width="32px"/>
                                                </a>
                                            </td>
                                            <td class="text-nowrap">
                                                <h6><a href="javascript:;" class="text-white text-decoration-none">Nunc
                                                        eleifend lorem eu velit eleifend, <br/>eget faucibus nibh
                                                        placerat.</a></h6>
                                            </td>
                                            <td class="text-blue fw-bold">$500.00</td>
                                            <td class="text-nowrap"><a href="javascript:;"
                                                                       class="text-white text-decoration-none">Derick
                                                    Wong</a></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="purchase4">
                                <div class="h-500px" data-scrollbar="true">
                                    <table class="table table-panel mb-0">
                                        <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th class="hidden-sm text-center">Product</th>
                                            <th></th>
                                            <th>Amount</th>
                                            <th>User</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="fw-bold text-muted">13/02/2021</td>
                                            <td class="hidden-sm text-center">
                                                <a href="javascript:;">
                                                    <img src="assets/img/product/product-1.png" alt="" width="32px"/>
                                                </a>
                                            </td>
                                            <td class="text-nowrap">
                                                <h6><a href="javascript:;" class="text-white text-decoration-none">Nunc
                                                        eleifend lorem eu velit eleifend, <br/>eget faucibus nibh
                                                        placerat.</a></h6>
                                            </td>
                                            <td class="text-blue fw-bold">$349.00</td>
                                            <td class="text-nowrap"><a href="javascript:;"
                                                                       class="text-white text-decoration-none">Derick
                                                    Wong</a></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">13/02/2021</td>
                                            <td class="hidden-sm text-center">
                                                <a href="javascript:;">
                                                    <img src="assets/img/product/product-2.png" alt="" width="32px"/>
                                                </a>
                                            </td>
                                            <td class="text-nowrap">
                                                <h6><a href="javascript:;" class="text-white text-decoration-none">Nunc
                                                        eleifend lorem eu velit eleifend, <br/>eget faucibus nibh
                                                        placerat.</a></h6>
                                            </td>
                                            <td class="text-blue fw-bold">$399.00</td>
                                            <td class="text-nowrap"><a href="javascript:;"
                                                                       class="text-white text-decoration-none">Derick
                                                    Wong</a></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">13/02/2021</td>
                                            <td class="hidden-sm text-center">
                                                <a href="javascript:;">
                                                    <img src="assets/img/product/product-3.png" alt="" width="32px"/>
                                                </a>
                                            </td>
                                            <td class="text-nowrap">
                                                <h6><a href="javascript:;" class="text-white text-decoration-none">Nunc
                                                        eleifend lorem eu velit eleifend, <br/>eget faucibus nibh
                                                        placerat.</a></h6>
                                            </td>
                                            <td class="text-blue fw-bold">$499.00</td>
                                            <td class="text-nowrap"><a href="javascript:;"
                                                                       class="text-white text-decoration-none">Derick
                                                    Wong</a></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">13/02/2021</td>
                                            <td class="hidden-sm text-center">
                                                <a href="javascript:;">
                                                    <img src="assets/img/product/product-4.png" alt="" width="32px"/>
                                                </a>
                                            </td>
                                            <td class="text-nowrap">
                                                <h6><a href="javascript:;" class="text-white text-decoration-none">Nunc
                                                        eleifend lorem eu velit eleifend, <br/>eget faucibus nibh
                                                        placerat.</a></h6>
                                            </td>
                                            <td class="text-blue fw-bold">$230.00</td>
                                            <td class="text-nowrap"><a href="javascript:;"
                                                                       class="text-white text-decoration-none">Derick
                                                    Wong</a></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">13/02/2021</td>
                                            <td class="hidden-sm text-center">
                                                <a href="javascript:;">
                                                    <img src="assets/img/product/product-5.png" alt="" width="32px"/>
                                                </a>
                                            </td>
                                            <td class="text-nowrap">
                                                <h6><a href="javascript:;" class="text-white text-decoration-none">Nunc
                                                        eleifend lorem eu velit eleifend, <br/>eget faucibus nibh
                                                        placerat.</a></h6>
                                            </td>
                                            <td class="text-blue fw-bold">$500.00</td>
                                            <td class="text-nowrap"><a href="javascript:;"
                                                                       class="text-white text-decoration-none">Derick
                                                    Wong</a></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- END tabs -->
                    </div>
                    <!-- END col-8 -->

                    <!-- BEGIN col-4 -->
                    <div class="col-xl-4">
                        <!-- BEGIN panel -->
                        <div class="panel panel-inverse" data-sortable-id="index-8">
                            <div class="panel-heading">
                                <h4 class="panel-title">Todo List</h4>
                                <div class="panel-heading-btn">
                                    <a href="javascript:;" class="btn btn-xs btn-icon btn-default"
                                       data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
                                    <a href="javascript:;" class="btn btn-xs btn-icon btn-success"
                                       data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
                                    <a href="javascript:;" class="btn btn-xs btn-icon btn-warning"
                                       data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
                                    <a href="javascript:;" class="btn btn-xs btn-icon btn-danger"
                                       data-toggle="panel-remove"><i class="fa fa-times"></i></a>
                                </div>
                            </div>
                            <div class="panel-body p-0">
                                <div class="todolist">
                                    <div class="todolist-item active">
                                        <div class="todolist-input">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="todolist1"
                                                       data-change="todolist" checked/>
                                            </div>
                                        </div>
                                        <label class="todolist-label" for="todolist1">Donec vehicula pretium nisl, id
                                            lacinia nisl tincidunt id.</label>
                                    </div>
                                    <div class="todolist-item">
                                        <div class="todolist-input">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="todolist2"
                                                       data-change="todolist"/>
                                            </div>
                                        </div>
                                        <label class="todolist-label" for="todolist2">Duis a ullamcorper massa.</label>
                                    </div>
                                    <div class="todolist-item">
                                        <div class="todolist-input">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="todolist3"
                                                       data-change="todolist"/>
                                            </div>
                                        </div>
                                        <label class="todolist-label" for="todolist3">Phasellus bibendum, odio nec
                                            vestibulum ullamcorper.</label>
                                    </div>
                                    <div class="todolist-item">
                                        <div class="todolist-input">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="todolist4"
                                                       data-change="todolist"/>
                                            </div>
                                        </div>
                                        <label class="todolist-label" for="todolist4">Duis pharetra mi sit amet dictum
                                            congue.</label>
                                    </div>
                                    <div class="todolist-item">
                                        <div class="todolist-input">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="todolist5"
                                                       data-change="todolist"/>
                                            </div>
                                        </div>
                                        <label class="todolist-label" for="todolist5">Duis pharetra mi sit amet dictum
                                            congue.</label>
                                    </div>
                                    <div class="todolist-item">
                                        <div class="todolist-input">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="todolist6"
                                                       data-change="todolist"/>
                                            </div>
                                        </div>
                                        <label class="todolist-label" for="todolist6">Phasellus bibendum, odio nec
                                            vestibulum ullamcorper.</label>
                                    </div>
                                    <div class="todolist-item">
                                        <div class="todolist-input">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="todolist7"
                                                       data-change="todolist"/>
                                            </div>
                                        </div>
                                        <label class="todolist-label" for="todolist7">Donec vehicula pretium nisl, id
                                            lacinia nisl tincidunt id.</label>
                                    </div>
                                    <div class="todolist-item">
                                        <div class="todolist-input">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="todolist5"
                                                       data-change="todolist"/>
                                            </div>
                                        </div>
                                        <label class="todolist-label" for="todolist5">Duis pharetra mi sit amet dictum
                                            congue.</label>
                                    </div>
                                    <div class="todolist-item">
                                        <div class="todolist-input">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="todolist6"
                                                       data-change="todolist"/>
                                            </div>
                                        </div>
                                        <label class="todolist-label" for="todolist6">Phasellus bibendum, odio nec
                                            vestibulum ullamcorper.</label>
                                    </div>
                                    <div class="todolist-item">
                                        <div class="todolist-input">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="todolist7"
                                                       data-change="todolist"/>
                                            </div>
                                        </div>
                                        <label class="todolist-label" for="todolist7">Donec vehicula pretium nisl, id
                                            lacinia nisl tincidunt id.</label>
                                    </div>
                                    <div class="todolist-item">
                                        <div class="todolist-input">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="todolist5"
                                                       data-change="todolist"/>
                                            </div>
                                        </div>
                                        <label class="todolist-label" for="todolist5">Duis pharetra mi sit amet dictum
                                            congue.</label>
                                    </div>
                                    <div class="todolist-item">
                                        <div class="todolist-input">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="todolist6"
                                                       data-change="todolist"/>
                                            </div>
                                        </div>
                                        <label class="todolist-label" for="todolist6">Phasellus bibendum, odio nec
                                            vestibulum ullamcorper.</label>
                                    </div>
                                    <div class="todolist-item">
                                        <div class="todolist-input">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="todolist7"
                                                       data-change="todolist"/>
                                            </div>
                                        </div>
                                        <label class="todolist-label" for="todolist7">Donec vehicula pretium nisl, id
                                            lacinia nisl tincidunt id.</label>
                                    </div>
                                    <div class="todolist-item">
                                        <div class="todolist-input">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="todolist5"
                                                       data-change="todolist"/>
                                            </div>
                                        </div>
                                        <label class="todolist-label" for="todolist5">Duis pharetra mi sit amet dictum
                                            congue.</label>
                                    </div>
                                    <div class="todolist-item">
                                        <div class="todolist-input">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="todolist6"
                                                       data-change="todolist"/>
                                            </div>
                                        </div>
                                        <label class="todolist-label" for="todolist6">Phasellus bibendum, odio nec
                                            vestibulum ullamcorper.</label>
                                    </div>
                                    <div class="todolist-item">
                                        <div class="todolist-input">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="todolist7"
                                                       data-change="todolist"/>
                                            </div>
                                        </div>
                                        <label class="todolist-label" for="todolist7">Donec vehicula pretium nisl, id
                                            lacinia nisl tincidunt id.</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END panel -->


                    </div>
                    <!-- END col-4 -->
                </div>
                <!-- END row -->

                <!-- BEGIN panel -->
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title">Installation Settings</h4>
                    </div>
                    <div class="panel-body">
                        <p>
                            Add the <code>.app-content-full-height</code> css class to <code>.app</code> container for
                            full height page element.
                            <br/>
                            <b>If</b> you only need the footer to be fixed at the bottom for <b>minimum max page
                                height</b>, you are not required to add the <code>data-scrollbar="true"</code> and
                            <code>data-height="100%"</code>;
                        </p>
                    </div>
                </div>
                <!-- END panel -->
            </div>
            <!-- END content-container -->

            <?php require_once('includes/footer.php'); ?>

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
    <script src="assets/plugins/@highlightjs/cdn-assets/highlight.min.js"></script>
    <script src="assets/js/demo/render.highlight.js"></script>
    <!-- ================== END page-js ================== -->
    </body>
<?php } else { ?>
    <?php require_once('login.php'); ?>
<?php } ?>
</html>