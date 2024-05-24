<!DOCTYPE html>
<html lang="fr" >
<head>
	<meta charset="utf-8" />
	<title>A2MI | Profil Utilisateur</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />

	<!-- ================== BEGIN core-css ================== -->
	<link href="assets/css/vendor.min.css" rel="stylesheet" />
	<link href="assets/css/transparent/app.min.css" rel="stylesheet" />
    <link href="assets/css/custom.css" rel="stylesheet" />
	<!-- ================== END core-css ================== -->

	<!-- ================== BEGIN page-css ================== -->
	<link href="assets/plugins/superbox/superbox.min.css" rel="stylesheet" />
	<link href="assets/plugins/lity/dist/lity.min.css" rel="stylesheet" />
	<!-- ================== END page-css ================== -->
</head>
<?php
session_start();
require_once('includes/_functions.php');
require_once('connexion/traitement_connexion.php');
if (isset($_SESSION['role']) AND isset($_SESSION['mail'])):
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
        <?php require_once('includes/_requests_profile.php'); ?>
        <?php require_once('includes/_requests.php'); ?>

		<!-- BEGIN #content -->
		<div id="content" class="app-content p-0">
			<!-- BEGIN profile -->
			<div class="profile">
				<div class="profile-header">
					<!-- BEGIN profile-header-cover -->
					<div class="profile-header-cover"></div>
					<!-- END profile-header-cover -->
					<!-- BEGIN profile-header-content -->
					<div class="profile-header-content">
						<!-- BEGIN profile-header-img -->
						<div class="profile-header-img">
							<img src="assets/img/user/user-12.jpg" alt="" />
						</div>
						<!-- END profile-header-img -->
						<!-- BEGIN profile-header-info -->
						<div class="profile-header-info">
							<h4 class="mt-0 mb-1"><?= $firstname ?> <?= $name ?></h4>
                            <?php if (isset($role) AND $role != 'admin' AND isset($mail)) : ?>
                            <p class="mb-2"><?= $role ?></p>
                            <?php endif; ?>
							<!--<a href="Modifier_profile.php" class="btn btn-xs btn-yellow">Modifier Profil</a>-->
						</div>
						<!-- END profile-header-info -->
					</div>
					<!-- END profile-header-content -->
					<!-- BEGIN profile-header-tab -->
					<ul class="profile-header-tab nav nav-tabs">
                        <li class="nav-item"><a href="#profile-about" class="nav-link active" data-bs-toggle="tab">PROFIL</a></li>
                        <li class="nav-item"><a href="#profile-orders" class="nav-link" data-bs-toggle="tab">COMMANDES/DEVIS</a></li>
						<li class="nav-item"><a href="#profile-sales" class="nav-link" data-bs-toggle="tab">COMMANDES/SHOP</a></li>
                        <?php if (isset($_SESSION['role']) AND $_SESSION['role'] != 'client' AND isset($_SESSION['mail'])): ?>
                        <li class="nav-item"><apetit href="#profile-post" class="nav-link" data-bs-toggle="tab">HISTORIQUE</apetit></li>
                        <!--<li class="nav-item"><a href="#profile-cr" class="nav-link" data-bs-toggle="tab">CAH-RES</a></li>-->
                        <?php endif; ?>
					</ul>
					<!-- END profile-header-tab -->
				</div>
			</div>
			<!-- END profile -->
			<!-- BEGIN profile-content -->
			<div class="profile-content">
				<!-- BEGIN tab-content -->
				<div class="tab-content p-0">
                    <!-- BEGIN #profile-about tab -->
                    <div class="tab-pane fade show active" id="profile-about">
                        <!-- BEGIN table -->
                        <div class="table-responsive form-inline">
                            <table class="table table-profile align-middle">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>
                                        <h4><?= $firstname ?> <?= $name ?>
                                            <?php if (isset($_SESSION['role']) AND $_SESSION['role'] != 'client' AND isset($_SESSION['mail'])): ?>
                                                <small><?= $role ?></small>
                                            <?php endif; ?>
                                        </h4>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="highlight">
                                    <td class="field">Entreprise</td>
                                    <td><a href="" class="text-decoration-none fw-bold pink"><?= $enterprise ?></a></td>
                                </tr>
                                <tr class="divider">
                                    <td colspan="2"></td>
                                </tr>
                                <?php if (isset($_SESSION['role']) AND $_SESSION['role'] != 'client' AND isset($_SESSION['mail'])): ?>
                                <tr>
                                    <td class="field">ID Client</td>
                                    <td><i class="fa fa-user-shield fa-fw me-5px pink"></i><?= $id ?></td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <td class="field">Email</td>
                                    <td><i class="fa fa-at fa-fw me-5px pink"></i><?= $mail ?></td>
                                </tr>
                                <tr>
                                    <td class="field">Mobile</td>
                                    <td><i class="fa fa-mobile fa-fw me-5px pink"></i><?= $mobile ?></td>
                                </tr>
                                <tr>
                                    <td class="field">Téléphone</td>
                                    <td><i class="fa fa-phone fa-fw me-5px pink"></i><?= $phone ?></td>
                                </tr>
                                <tr>
                                    <td class="field">Adresse</td>
                                    <td><?= $address ?></td>
                                </tr>
                                <tr>
                                    <td class="field">Code Postal</td>
                                    <td><?= $postcode ?></td>
                                </tr>
                                <tr>
                                    <td class="field">Ville</td>
                                    <td><?= $city ?></td>
                                </tr>
                                <tr>
                                    <td class="field">Date d'inscription</td>
                                    <td><?= $inscription ?></td>
                                </tr>
                                <tr class="divider">
                                    <td colspan="2"></td>
                                </tr>
                                <?php
                                // Assume $user_id contains the ID of the user
                                $users_id = $_GET['id'];
                                ?>
                                <?php if (isset($_SESSION['role']) AND $_SESSION['role'] != 'client' AND isset($_SESSION['mail'])): ?>
                                <tr class="highlight">
                                    <td class="field pink">ACTIONS</td>
                                    <td class="">
                                        <a href="ajout_bi.php?id=<?= $users_id ?>"><button type="button" class="btn btn-indigo w-150px m-1">Bon d'Intervention</button></a>
                                        <a href=""><button type="submit" class="btn btn-indigo w-150px m-1">SAV</button></a>
                                        <a href="ajout_pret.php?id=<?= $users_id ?>"><button type="submit" class="btn btn-indigo w-150px m-1">Prêt</button></a>
                                        <a href=""><button type="submit" class="btn btn-indigo w-150px m-1">Commande/Devis</button></a>
                                    </td>
                                </tr>
                                <tr class="divider">
                                    <td colspan="2"></td>
                                </tr>
                                <tr>
                                    <td class="field">Technicien</td>
                                    <td><i class="fa fa-user fa-fw me-5px pink"></i><?= $techFirstname ?> <?= $techName ?></td>
                                </tr>
                                    <tr>
                                        <td class="field">ID Technicien</td>
                                        <td><i class="fa fa-user-shield fa-fw me-5px pink"></i><?= $techId ?></td>
                                    </tr>
                                <tr>
                                    <td class="field">Email</td>
                                    <td><i class="fa fa-at fa-fw me-5px pink"></i><?= $techMail ?></td>
                                </tr>
                                <tr>
                                    <td class="field">Mobile</td>
                                    <td><i class="fa fa-mobile fa-fw me-5px pink"></i><?= $techMobile ?></td>
                                </tr>
                                <tr>
                                    <td class="field">Téléphone</td>
                                    <td><i class="fa fa-phone fa-fw me-5px pink"></i><?= $techPhone ?></td>
                                </tr>

                                <tr class="divider">
                                    <td colspan="2"></td>
                                </tr>
                                <tr class="highlight">
                                    <td class="field">&nbsp;</td>
                                    <td class="">
                                        <button type="submit" class="btn btn-primary w-150px m-1">Modifier Profil</button>
                                        <button type="submit" class="btn btn-primary w-150px m-1">Modifier Mot de passe</button>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <tr class="field">
                                    <td class="field">&nbsp;</td>
                                    <td class="">
                                        <a href="img/conditions-generales-de-vente-2023.pdf" target="_blank">
                                            <button class="btn btn-info w-230px m-1">
                                                Conditions générales de vente
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- END table -->
                    </div>
                    <!-- END #profile-about tab -->
                    <?php if (isset($_SESSION['role']) AND $_SESSION['role'] != 'client' AND isset($_SESSION['mail'])): ?>
					<!-- BEGIN #profile-post tab -->
					<div class="tab-pane fade" id="profile-post">
						<!-- BEGIN timeline -->
						<div class="timeline">
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

                            // ID du client à partir duquel vous souhaitez récupérer les bons d'intervention
                            $client_id = $_GET['id']; // Assurez-vous de valider et sécuriser cette valeur

                            // Requête SQL pour récupérer les données nécessaires
                            $sql = "SELECT bi.bi_id, bi.bi_datein AS date_creation, bi.bi_date AS date_fin, 
           bi.bi_technicien AS technicien_id, 
           intervention.inter_intervention AS intervention_name,
           CONCAT(users.users_firstname, ' ', users.users_name) AS technicien_nom_prenom
    FROM bi
    INNER JOIN intervention ON bi.bi_id = intervention.bi_id
    INNER JOIN users ON bi.bi_technicien = users.users_id
    WHERE bi.users_id = :client_id
    ORDER BY bi.bi_date DESC"; // Tri par date de fin décroissante

                            // Préparer la déclaration SQL
                            $stmt = $db->prepare($sql);

                            // Liaison des paramètres
                            $stmt->bindParam(':client_id', $client_id, PDO::PARAM_INT);

                            // Exécuter la requête
                            $stmt->execute();

                            // Récupérer les résultats
                            $bi_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>

                            <!-- Affichage des données dans la structure de la timeline -->
                            <?php foreach ($bi_data as $bi) : ?>
                                <!-- BEGIN timeline-item -->
                                <div class="timeline-item">
                                    <!-- Wrap the entire content in a link to fiche_bi.php -->
                                    <a href="fiche_bi.php?bi_id=<?php echo $bi['bi_id']; ?>" class="timeline-link" style="text-decoration: none; color: inherit;">
                                        <!-- BEGIN timeline-time -->
                                        <div class="timeline-time">
                                            <span class="date"><?php echo date('Y-m-d', ($bi['date_creation'])); ?></span>
                                            <span class="time">BI</span>
                                        </div>
                                        <!-- END timeline-time -->
                                        <!-- BEGIN timeline-icon -->
                                        <div class="timeline-icon">
                                            &nbsp;
                                        </div>
                                        <!-- END timeline-icon -->
                                        <!-- BEGIN timeline-content -->
                                        <div class="timeline-content">
                                            <!-- BEGIN timeline-header -->
                                            <div class="timeline-header">
                                                <div class="userimage"><img src="assets/img/user/user-3.jpg" alt="" /></div>
                                                <div class="username">
                                                    <div>Intervention <i class="fa fa-check-circle text-green ms-1"></i> (ID: <?php echo $bi['bi_id']; ?>)</div>
                                                    <div class="text-muted fs-12px"><?php echo $bi['technicien_nom_prenom']; ?></div>
                                                </div>
                                            </div>
                                            <!-- END timeline-header -->
                                            <!-- BEGIN timeline-body -->
                                            <div class="timeline-body">
                                                <!-- timeline-post -->
                                                <div class="lead mb-3"><?php echo $bi['intervention_name']; ?></div>
                                                <!-- timeline-action -->
                                                <hr class="my-10px" />
                                                <div class="d-flex align-items-center fw-bold">
                                                    <div class="flex-fill text-decoration-none text-center text-gray-400">
                                                        Date de fin : <?php echo date('Y-m-d', ($bi['date_fin'])); ?>
                                                    </div>
                                                </div>
                                                <hr class="mt-10px mb-3" />
                                            </div>
                                            <!-- END timeline-body -->
                                        </div>
                                        <!-- END timeline-content -->
                                    </a>
                                </div>
                                <!-- END timeline-item -->
                            <?php endforeach; ?>




                            <!-- BEGIN timeline-item -->
                            <div class="timeline-item">
                                <!-- BEGIN timeline-time -->
                                <div class="timeline-time">
                                    <span class="date">24 / 02 / 2021</span>
                                    <span class="time">SAV</span>
                                </div>
                                <!-- END timeline-time -->
                                <!-- BEGIN timeline-icon -->
                                <div class="timeline-icon">
                                    <a href="javascript:;">&nbsp;</a>
                                </div>
                                <!-- END timeline-icon -->
                                <!-- BEGIN timeline-content -->
                                <div class="timeline-content bg-teal-900">
                                    <!-- BEGIN timeline-header -->
                                    <div class="timeline-header">
                                        <div class="userimage"><img src="assets/img/user/user-3.jpg" alt="" /></div>
                                        <div class="username">
                                            <div>SAV<i class="fa fa-check-circle text-green ms-1"></i></div>
                                            <div class="text-muted fs-12px">Nom technicien</div>
                                        </div>
                                    </div>
                                    <!-- END timeline-header -->
                                    <!-- BEGIN timeline-body -->
                                    <div class="timeline-body">
                                        <!-- timeline-post -->
                                        <div class="lead mb-3">Commentaire</div>
                                        <!-- timeline-action -->
                                        <hr class="my-10px" />
                                        <div class="d-flex align-items-center fw-bold">
                                            <div class="flex-fill text-decoration-none text-center text-gray-400">
                                                Petite description rapide de ce qui a été fait
                                            </div>
                                        </div>
                                        <hr class="mt-10px mb-3" />
                                    </div>
                                    <!-- END timeline-body -->
                                </div>
                                <!-- END timeline-content -->
                            </div>
                            <!-- END timeline-item -->
                            <!-- BEGIN timeline-item -->
                            <div class="timeline-item">
                                <!-- BEGIN timeline-time -->
                                <div class="timeline-time">
                                    <span class="date">24 / 02 / 2021</span>
                                    <span class="time">BI</span>
                                </div>
                                <!-- END timeline-time -->
                                <!-- BEGIN timeline-icon -->
                                <div class="timeline-icon">
                                    <a href="javascript:;">&nbsp;</a>
                                </div>
                                <!-- END timeline-icon -->
                                <!-- BEGIN timeline-content -->
                                <div class="timeline-content bg-indigo-900">
                                    <!-- BEGIN timeline-header -->
                                    <div class="timeline-header">
                                        <div class="userimage"><img src="assets/img/user/user-3.jpg" alt="" /></div>
                                        <div class="username">
                                            <div>Intervention<i class="fa fa-check-circle text-green ms-1"></i></div>
                                            <div class="text-muted fs-12px">Nom technicien</div>
                                        </div>
                                    </div>
                                    <!-- END timeline-header -->
                                    <!-- BEGIN timeline-body -->
                                    <div class="timeline-body">
                                        <!-- timeline-post -->
                                        <div class="lead mb-3">Commentaire</div>
                                        <!-- timeline-action -->
                                        <hr class="my-10px" />
                                        <div class="d-flex align-items-center fw-bold">
                                            <div class="flex-fill text-decoration-none text-center text-gray-400">
                                                Petite description rapide de ce qui a été fait
                                            </div>
                                        </div>
                                        <hr class="mt-10px mb-3" />
                                    </div>
                                    <!-- END timeline-body -->
                                </div>
                                <!-- END timeline-content -->
                            </div>
                            <!-- END timeline-item -->
						</div>
						<!-- END timeline -->
					</div>
                    <!-- END #profile-post tab -->
                    <?php endif; ?>
                    <!-- BEGIN #profile-sales tab -->
                    <div class="tab-pane fade" id="profile-orders">
                        <?php foreach ($resumOrders as $resumOrder) : ?>
                            <?php if($resumOrder->shop_commande_user_id == $_GET['id']) : ?>
                                <!-- BEGIN timeline -->
                                <div class="timeline">
                                    <!-- BEGIN timeline-item -->
                                    <div class="timeline-item">
                                        <!-- BEGIN timeline-time -->
                                        <div class="timeline-time">
                                            <span class="date"><?= $resumOrder->shop_commande_date ?></span>
                                        </div>
                                        <!-- END timeline-time -->
                                        <!-- BEGIN timeline-icon -->
                                        <div class="timeline-icon">
                                            <a href="javascript:;">&nbsp;</a>
                                        </div>
                                        <!-- END timeline-icon -->
                                        <!-- BEGIN timeline-content -->
                                        <div class="timeline-content invoice">
                                            <!-- BEGIN timeline-header -->
                                            <div class="timeline-header check-size">
                                                <?php if($resumOrder->shop_commande_date_envoie != NULL ) : ?>
                                                    <div class="userimage ">
                                                        <i class="fa fa-check-circle text-green ms-1"></i>
                                                    </div>
                                                    <div class="username check-size">
                                                        <a href="#">
                                                            <div>Commande n° A2MI-<?= str_pad($resumOrder->shop_commande_id, 5, "0", STR_PAD_LEFT) ?>
                                                                envoyée</div>
                                                        </a>
                                                        <div class="text-muted fs-12px">Le : <?= $resumOrder->shop_commande_date_envoie ?></div>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if($resumOrder->shop_commande_date_validation != NULL ): ?>
                                                    <div class="userimage ">
                                                        <i class="fa fa-thumbs-up text-info ms-1"></i>
                                                    </div>
                                                    <div class="username check-size">
                                                        <a href="#">
                                                            <div>Commande n° A2MI-<?= str_pad($resumOrder->shop_commande_id, 5, "0", STR_PAD_LEFT) ?>
                                                                validée</div>
                                                        </a>
                                                        <div class="text-muted fs-12px">Validée le:
                                                            <?= $resumOrder->shop_commande_date_validation ?></div>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if($resumOrder->shop_commande_date != NULL ): ?>
                                                    <div class="userimage ">
                                                        <i class="fa fa-clock text-orange ms-1"></i>
                                                    </div>
                                                    <div class="username check-size">
                                                        <a href="#">
                                                            <div>Commande n° A2MI-<?= str_pad($resumOrder->shop_commande_id, 5, "0", STR_PAD_LEFT) ?>
                                                                en attente</div>
                                                        </a>
                                                        <div class="text-muted fs-12px">Enregistrée le: <?= $resumOrder->shop_commande_date ?></div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <!-- END timeline-header -->
                                            <!-- BEGIN timeline-body -->
                                            <div class="timeline-body">
                                                <!-- BEGIN invoice-content -->
                                                <div class="invoice-content">
                                                    <!-- BEGIN table-responsive -->
                                                    <div class="table-responsive">
                                                        <table class="table table-invoice">
                                                            <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Articles</th>
                                                                <th class="text-center" width="15%">HT</th>
                                                                <th class="text-end" width="15%">TTC</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            $idCommande = $resumOrder->shop_commande_id;
                                                            $req = $pdo->prepare("SELECT * 
                                                                                    FROM shop_ligne_commande  AS A
                                                                                    INNER JOIN shop_produit AS B ON B.shop_produit_id = A.shop_ligne_commande_produit_id                                                                                
                                                                                    INNER JOIN shop_image AS C ON C.shop_image_produit_id = B.shop_produit_id 
                                                                                    AND shop_ligne_commande_num_commande = '$idCommande'");
                                                            $req->execute();
                                                            $nbrcomprods = $req->fetchAll(PDO::FETCH_OBJ);
                                                            ?>
                                                            <?php foreach ($nbrcomprods as $nbrcomprod) : ?>
                                                                <tr>
                                                                    <td width="20%"><img src="../admin/img/products/<?=
                                                                        $nbrcomprod->shop_image_original ?>"></td>
                                                                    <td>
                                                                <span class="text-white fw-700">
                                                                    <?= $nbrcomprod->shop_produit_nom ?>
                                                                </span><br />
                                                                        <small class="text-gray-400">
                                                                            <?= nl2br($nbrcomprod->shop_produit_description) ?>
                                                                        </small>
                                                                    </td>
                                                                    <td class="text-center">€ <?= number_format($nbrcomprod->shop_produit_prix_total / (1 + 20/100),2) ?></td>
                                                                    <td class="text-end">€ <?= $nbrcomprod->shop_produit_prix_total ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <!-- END table-responsive -->
                                                    <!-- BEGIN invoice-price -->
                                                    <div class="invoice-price">
                                                        <div class="invoice-price-left">
                                                            <div class="invoice-price-row">
                                                                <div class="sub-price">
                                                                    <small>HT</small>
                                                                    <span class="text-white">€
                                                                <?= number_format($resumOrder->shop_commande_prix_total / (1 + 20/100),2) ?>
                                                                </span>
                                                                </div>
                                                                <div class="sub-price">
                                                                    <i class="fa fa-plus text-muted"></i>
                                                                </div>
                                                                <div class="sub-price">
                                                                    <small>TVA</small>
                                                                    <span class="text-white">€
                                                                    <?= number_format($resumOrder->shop_commande_prix_total - $resumOrder->shop_commande_prix_total / (1 + 20/100),2) ?>
                                                                </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="invoice-price-right">
                                                            <small>TOTAL</small>
                                                            <span class="fw-bold">€ <?= $resumOrder->shop_commande_prix_total ?></span>
                                                        </div>
                                                    </div>
                                                    <!-- END invoice-price -->
                                                </div>
                                                <!-- END invoice-content -->

                                            </div>
                                            <!-- END timeline-body -->
                                        </div>
                                        <!-- END timeline-content -->
                                    </div>
                                    <!-- END timeline-item -->
                                </div>
                                <!-- END timeline -->
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <!-- END #profile-sales tab -->
                    <!-- BEGIN #profile-sales tab -->
                    <div class="tab-pane fade" id="profile-sales">
                        <?php foreach ($resumOrders as $resumOrder) : ?>
                            <?php if($resumOrder->shop_commande_user_id == $_GET['id']) : ?>
                            <!-- BEGIN timeline -->
                            <div class="timeline">
                                <!-- BEGIN timeline-item -->
                                <div class="timeline-item">
                                    <!-- BEGIN timeline-time -->
                                    <div class="timeline-time">
                                        <span class="date"><?= $resumOrder->shop_commande_date ?></span>
                                    </div>
                                    <!-- END timeline-time -->
                                    <!-- BEGIN timeline-icon -->
                                    <div class="timeline-icon">
                                        <a href="javascript:;">&nbsp;</a>
                                    </div>
                                    <!-- END timeline-icon -->
                                    <!-- BEGIN timeline-content -->
                                    <div class="timeline-content invoice">
                                        <!-- BEGIN timeline-header -->
                                        <div class="timeline-header check-size">
                                            <?php if($resumOrder->shop_commande_date_envoie != NULL ) : ?>
                                            <div class="userimage ">
                                                <i class="fa fa-check-circle text-green ms-1"></i>
                                            </div>
                                            <div class="username check-size">
                                                <a href="#">
                                                <div>Commande n° A2MI-<?= str_pad($resumOrder->shop_commande_id, 5, "0", STR_PAD_LEFT) ?>
                                                    envoyée</div>
                                                </a>
                                                <div class="text-muted fs-12px">Le : <?= $resumOrder->shop_commande_date_envoie ?></div>
                                            </div>
                                            <?php endif; ?>
                                            <?php if($resumOrder->shop_commande_date_validation != NULL ): ?>
                                            <div class="userimage ">
                                                <i class="fa fa-thumbs-up text-info ms-1"></i>
                                            </div>
                                            <div class="username check-size">
                                                <a href="#">
                                                <div>Commande n° A2MI-<?= str_pad($resumOrder->shop_commande_id, 5, "0", STR_PAD_LEFT) ?>
                                                    validée</div>
                                                </a>
                                                <div class="text-muted fs-12px">Validée le:
                                                    <?= $resumOrder->shop_commande_date_validation ?></div>
                                            </div>
                                            <?php endif; ?>
                                            <?php if($resumOrder->shop_commande_date != NULL ): ?>
                                            <div class="userimage ">
                                                <i class="fa fa-clock text-orange ms-1"></i>
                                            </div>
                                            <div class="username check-size">
                                                <a href="#">
                                                <div>Commande n° A2MI-<?= str_pad($resumOrder->shop_commande_id, 5, "0", STR_PAD_LEFT) ?>
                                                    en attente</div>
                                                </a>
                                                <div class="text-muted fs-12px">Enregistrée le: <?= $resumOrder->shop_commande_date ?></div>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <!-- END timeline-header -->
                                        <!-- BEGIN timeline-body -->
                                        <div class="timeline-body">
                                            <!-- BEGIN invoice-content -->
                                            <div class="invoice-content">
                                                <!-- BEGIN table-responsive -->
                                                <div class="table-responsive">
                                                    <table class="table table-invoice">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Articles</th>
                                                            <th class="text-center" width="15%">HT</th>
                                                            <th class="text-end" width="15%">TTC</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        $idCommande = $resumOrder->shop_commande_id;
                                                        $req = $pdo->prepare("SELECT * 
                                                                                    FROM shop_ligne_commande  AS A
                                                                                    INNER JOIN shop_produit AS B ON B.shop_produit_id = A.shop_ligne_commande_produit_id                                                                                
                                                                                    INNER JOIN shop_image AS C ON C.shop_image_produit_id = B.shop_produit_id 
                                                                                    AND shop_ligne_commande_num_commande = '$idCommande'");
                                                        $req->execute();
                                                        $nbrcomprods = $req->fetchAll(PDO::FETCH_OBJ);
                                                        ?>
                                                        <?php foreach ($nbrcomprods as $nbrcomprod) : ?>
                                                        <tr>
                                                            <td width="20%"><img src="../admin/img/products/<?=
                                                                $nbrcomprod->shop_image_original ?>"></td>
                                                            <td>
                                                                <span class="text-white fw-700">
                                                                    <?= $nbrcomprod->shop_produit_nom ?>
                                                                </span><br />
                                                                <small class="text-gray-400">
                                                                    <?= nl2br($nbrcomprod->shop_produit_description) ?>
                                                                </small>
                                                            </td>
                                                            <td class="text-center">€ <?= number_format($nbrcomprod->shop_produit_prix_total / (1 + 20/100),2) ?></td>
                                                            <td class="text-end">€ <?= $nbrcomprod->shop_produit_prix_total ?></td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- END table-responsive -->
                                                <!-- BEGIN invoice-price -->
                                                <div class="invoice-price">
                                                    <div class="invoice-price-left">
                                                        <div class="invoice-price-row">
                                                            <div class="sub-price">
                                                                <small>HT</small>
                                                                <span class="text-white">€
                                                                <?= number_format($resumOrder->shop_commande_prix_total / (1 + 20/100),2) ?>
                                                                </span>
                                                            </div>
                                                            <div class="sub-price">
                                                                <i class="fa fa-plus text-muted"></i>
                                                            </div>
                                                            <div class="sub-price">
                                                                <small>TVA</small>
                                                                <span class="text-white">€
                                                                    <?= number_format($resumOrder->shop_commande_prix_total - $resumOrder->shop_commande_prix_total / (1 + 20/100),2) ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="invoice-price-right">
                                                        <small>TOTAL</small>
                                                        <span class="fw-bold">€ <?= $resumOrder->shop_commande_prix_total ?></span>
                                                    </div>
                                                </div>
                                                <!-- END invoice-price -->
                                            </div>
                                            <!-- END invoice-content -->

                                        </div>
                                        <!-- END timeline-body -->
                                    </div>
                                    <!-- END timeline-content -->
                                </div>
                                <!-- END timeline-item -->
                            </div>
                            <!-- END timeline -->
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <!-- END #profile-sales tab -->
                    <?php if (isset($_SESSION['role']) AND $_SESSION['role'] != 'client' AND isset($_SESSION['mail'])): ?>
                    <!-- BEGIN #profile-cr tab -->
					<div class="tab-pane fade" id="profile-cr">
						<h4 class="mb-3">Friend List (14)</h4>
						<!-- BEGIN row -->
						<div class="row gx-1">
							<!-- BEGIN col-6 -->
							<div class="col-xl-3 col-lg-6 mb-1">
								<div class="p-2 d-flex align-items-center card flex-row border-0 rounded">
									<a href="javascript:;">
										<img src="assets/img/user/user-1.jpg" alt="" class="rounded" width="64" />
									</a>
									<div class="flex-1 ps-3">
										<b class="text-white">James Pittman</b>
									</div>
									<div>
										<a href="javascript:;" class="btn border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center bg-none" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-h fa-lg"></i></a>
										<div class="dropdown-menu dropdown-menu-end">
											<a href="javascript:;" class="dropdown-item">Action 1</a>
											<a href="javascript:;" class="dropdown-item">Action 2</a>
											<a href="javascript:;" class="dropdown-item">Action 3</a>
											<div class="dropdown-divider"></div>
											<a href="javascript:;" class="dropdown-item">Action 4</a>
										</div>
									</div>
								</div>
							</div>
							<!-- END col-6 -->
							<!-- BEGIN col-6 -->
							<div class="col-xl-3 col-lg-6 mb-1">
								<div class="p-2 d-flex align-items-center card flex-row border-0 rounded">
									<a href="javascript:;">
										<img src="assets/img/user/user-2.jpg" alt="" class="rounded" width="64" />
									</a>
									<div class="flex-1 ps-3">
										<b class="text-white">Mitchell Ashcroft</b>
									</div>
									<div>
										<a href="javascript:;" class="btn border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center bg-none" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-h fa-lg"></i></a>
										<div class="dropdown-menu dropdown-menu-end">
											<a href="javascript:;" class="dropdown-item">Action 1</a>
											<a href="javascript:;" class="dropdown-item">Action 2</a>
											<a href="javascript:;" class="dropdown-item">Action 3</a>
											<div class="dropdown-divider"></div>
											<a href="javascript:;" class="dropdown-item">Action 4</a>
										</div>
									</div>
								</div>
							</div>
							<!-- END col-6 -->
							<!-- BEGIN col-6 -->
							<div class="col-xl-3 col-lg-6 mb-1">
								<div class="p-2 d-flex align-items-center card flex-row border-0 rounded">
									<a href="javascript:;">
										<img src="assets/img/user/user-3.jpg" alt="" class="rounded" width="64" />
									</a>
									<div class="flex-1 ps-3">
										<b class="text-white">Ella Cabena</b>
									</div>
									<div>
										<a href="javascript:;" class="btn border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center bg-none" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-h fa-lg"></i></a>
										<div class="dropdown-menu dropdown-menu-end">
											<a href="javascript:;" class="dropdown-item">Action 1</a>
											<a href="javascript:;" class="dropdown-item">Action 2</a>
											<a href="javascript:;" class="dropdown-item">Action 3</a>
											<div class="dropdown-divider"></div>
											<a href="javascript:;" class="dropdown-item">Action 4</a>
										</div>
									</div>
								</div>
							</div>
							<!-- END col-6 -->
							<!-- BEGIN col-6 -->
							<div class="col-xl-3 col-lg-6 mb-1">
								<div class="p-2 d-flex align-items-center card flex-row border-0 rounded">
									<a href="javascript:;">
										<img src="assets/img/user/user-4.jpg" alt="" class="rounded" width="64" />
									</a>
									<div class="flex-1 ps-3">
										<b class="text-white">Declan Dyson</b>
									</div>
									<div>
										<a href="javascript:;" class="btn border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center bg-none" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-h fa-lg"></i></a>
										<div class="dropdown-menu dropdown-menu-end">
											<a href="javascript:;" class="dropdown-item">Action 1</a>
											<a href="javascript:;" class="dropdown-item">Action 2</a>
											<a href="javascript:;" class="dropdown-item">Action 3</a>
											<div class="dropdown-divider"></div>
											<a href="javascript:;" class="dropdown-item">Action 4</a>
										</div>
									</div>
								</div>
							</div>
							<!-- END col-6 -->
							<!-- BEGIN col-6 -->
							<div class="col-xl-3 col-lg-6 mb-1">
								<div class="p-2 d-flex align-items-center card flex-row border-0 rounded">
									<a href="javascript:;">
										<img src="assets/img/user/user-5.jpg" alt="" class="rounded" width="64" />
									</a>
									<div class="flex-1 ps-3">
										<b class="text-white">George Seyler</b>
									</div>
									<div>
										<a href="javascript:;" class="btn border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center bg-none" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-h fa-lg"></i></a>
										<div class="dropdown-menu dropdown-menu-end">
											<a href="javascript:;" class="dropdown-item">Action 1</a>
											<a href="javascript:;" class="dropdown-item">Action 2</a>
											<a href="javascript:;" class="dropdown-item">Action 3</a>
											<div class="dropdown-divider"></div>
											<a href="javascript:;" class="dropdown-item">Action 4</a>
										</div>
									</div>
								</div>
							</div>
							<!-- END col-6 -->
							<!-- BEGIN col-6 -->
							<div class="col-xl-3 col-lg-6 mb-1">
								<div class="p-2 d-flex align-items-center card flex-row border-0 rounded">
									<a href="javascript:;">
										<img src="assets/img/user/user-6.jpg" alt="" class="rounded" width="64" />
									</a>
									<div class="flex-1 ps-3">
										<b class="text-white">Patrick Musgrove</b>
									</div>
									<div>
										<a href="javascript:;" class="btn border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center bg-none" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-h fa-lg"></i></a>
										<div class="dropdown-menu dropdown-menu-end">
											<a href="javascript:;" class="dropdown-item">Action 1</a>
											<a href="javascript:;" class="dropdown-item">Action 2</a>
											<a href="javascript:;" class="dropdown-item">Action 3</a>
											<div class="dropdown-divider"></div>
											<a href="javascript:;" class="dropdown-item">Action 4</a>
										</div>
									</div>
								</div>
							</div>
							<!-- END col-6 -->
							<!-- BEGIN col-6 -->
							<div class="col-xl-3 col-lg-6 mb-1">
								<div class="p-2 d-flex align-items-center card flex-row border-0 rounded">
									<a href="javascript:;">
										<img src="assets/img/user/user-7.jpg" alt="" class="rounded" width="64" />
									</a>
									<div class="flex-1 ps-3">
										<b class="text-white">Taj Connal</b>
									</div>
									<div>
										<a href="javascript:;" class="btn border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center bg-none" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-h fa-lg"></i></a>
										<div class="dropdown-menu dropdown-menu-end">
											<a href="javascript:;" class="dropdown-item">Action 1</a>
											<a href="javascript:;" class="dropdown-item">Action 2</a>
											<a href="javascript:;" class="dropdown-item">Action 3</a>
											<div class="dropdown-divider"></div>
											<a href="javascript:;" class="dropdown-item">Action 4</a>
										</div>
									</div>
								</div>
							</div>
							<!-- END col-6 -->
							<!-- BEGIN col-6 -->
							<div class="col-xl-3 col-lg-6 mb-1">
								<div class="p-2 d-flex align-items-center card flex-row border-0 rounded">
									<a href="javascript:;">
										<img src="assets/img/user/user-8.jpg" alt="" class="rounded" width="64" />
									</a>
									<div class="flex-1 ps-3">
										<b class="text-white">Laura Pollock</b>
									</div>
									<div>
										<a href="javascript:;" class="btn border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center bg-none" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-h fa-lg"></i></a>
										<div class="dropdown-menu dropdown-menu-end">
											<a href="javascript:;" class="dropdown-item">Action 1</a>
											<a href="javascript:;" class="dropdown-item">Action 2</a>
											<a href="javascript:;" class="dropdown-item">Action 3</a>
											<div class="dropdown-divider"></div>
											<a href="javascript:;" class="dropdown-item">Action 4</a>
										</div>
									</div>
								</div>
							</div>
							<!-- END col-6 -->
							<!-- BEGIN col-6 -->
							<div class="col-xl-3 col-lg-6 mb-1">
								<div class="p-2 d-flex align-items-center card flex-row border-0 rounded">
									<a href="javascript:;">
										<img src="assets/img/user/user-9.jpg" alt="" class="rounded" width="64" />
									</a>
									<div class="flex-1 ps-3">
										<b class="text-white">Dakota Mannix</b>
									</div>
									<div>
										<a href="javascript:;" class="btn border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center bg-none" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-h fa-lg"></i></a>
										<div class="dropdown-menu dropdown-menu-end">
											<a href="javascript:;" class="dropdown-item">Action 1</a>
											<a href="javascript:;" class="dropdown-item">Action 2</a>
											<a href="javascript:;" class="dropdown-item">Action 3</a>
											<div class="dropdown-divider"></div>
											<a href="javascript:;" class="dropdown-item">Action 4</a>
										</div>
									</div>
								</div>
							</div>
							<!-- END col-6 -->
							<!-- BEGIN col-6 -->
							<div class="col-xl-3 col-lg-6 mb-1">
								<div class="p-2 d-flex align-items-center card flex-row border-0 rounded">
									<a href="javascript:;">
										<img src="assets/img/user/user-10.jpg" alt="" class="rounded" width="64" />
									</a>
									<div class="flex-1 ps-3">
										<b class="text-white">Timothy Woolley</b>
									</div>
									<div>
										<a href="javascript:;" class="btn border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center bg-none" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-h fa-lg"></i></a>
										<div class="dropdown-menu dropdown-menu-end">
											<a href="javascript:;" class="dropdown-item">Action 1</a>
											<a href="javascript:;" class="dropdown-item">Action 2</a>
											<a href="javascript:;" class="dropdown-item">Action 3</a>
											<div class="dropdown-divider"></div>
											<a href="javascript:;" class="dropdown-item">Action 4</a>
										</div>
									</div>
								</div>
							</div>
							<!-- END col-6 -->
							<!-- BEGIN col-6 -->
							<div class="col-xl-3 col-lg-6 mb-1">
								<div class="p-2 d-flex align-items-center card flex-row border-0 rounded">
									<a href="javascript:;">
										<img src="assets/img/user/user-11.jpg" alt="" class="rounded" width="64" />
									</a>
									<div class="flex-1 ps-3">
										<b class="text-white">Benjamin Congreve</b>
									</div>
									<div>
										<a href="javascript:;" class="btn border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center bg-none" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-h fa-lg"></i></a>
										<div class="dropdown-menu dropdown-menu-end">
											<a href="javascript:;" class="dropdown-item">Action 1</a>
											<a href="javascript:;" class="dropdown-item">Action 2</a>
											<a href="javascript:;" class="dropdown-item">Action 3</a>
											<div class="dropdown-divider"></div>
											<a href="javascript:;" class="dropdown-item">Action 4</a>
										</div>
									</div>
								</div>
							</div>
							<!-- END col-6 -->
							<!-- BEGIN col-6 -->
							<div class="col-xl-3 col-lg-6 mb-1">
								<div class="p-2 d-flex align-items-center card flex-row border-0 rounded">
									<a href="javascript:;">
										<img src="assets/img/user/user-12.jpg" alt="" class="rounded" width="64" />
									</a>
									<div class="flex-1 ps-3">
										<b class="text-white">Mariam Maddock</b>
									</div>
									<div>
										<a href="javascript:;" class="btn border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center bg-none" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-h fa-lg"></i></a>
										<div class="dropdown-menu dropdown-menu-end">
											<a href="javascript:;" class="dropdown-item">Action 1</a>
											<a href="javascript:;" class="dropdown-item">Action 2</a>
											<a href="javascript:;" class="dropdown-item">Action 3</a>
											<div class="dropdown-divider"></div>
											<a href="javascript:;" class="dropdown-item">Action 4</a>
										</div>
									</div>
								</div>
							</div>
							<!-- END col-6 -->
							<!-- BEGIN col-6 -->
							<div class="col-xl-3 col-lg-6 mb-1">
								<div class="p-2 d-flex align-items-center card flex-row border-0 rounded">
									<a href="javascript:;">
										<img src="assets/img/user/user-13.jpg" alt="" class="rounded" width="64" />
									</a>
									<div class="flex-1 ps-3">
										<b class="text-white">Blake Gerrald</b>
									</div>
									<div>
										<a href="javascript:;" class="btn border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center bg-none" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-h fa-lg"></i></a>
										<div class="dropdown-menu dropdown-menu-end">
											<a href="javascript:;" class="dropdown-item">Action 1</a>
											<a href="javascript:;" class="dropdown-item">Action 2</a>
											<a href="javascript:;" class="dropdown-item">Action 3</a>
											<div class="dropdown-divider"></div>
											<a href="javascript:;" class="dropdown-item">Action 4</a>
										</div>
									</div>
								</div>
							</div>
							<!-- END col-6 -->
							<!-- BEGIN col-6 -->
							<div class="col-xl-3 col-lg-6 mb-1">
								<div class="p-2 d-flex align-items-center card flex-row border-0 rounded">
									<a href="javascript:;">
										<img src="assets/img/user/user-14.jpg" alt="" class="rounded" width="64" />
									</a>
									<div class="flex-1 ps-3">
										<b class="text-white">Gabrielle Bunton</b>
									</div>
									<div>
										<a href="javascript:;" class="btn border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center bg-none" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-h fa-lg"></i></a>
										<div class="dropdown-menu dropdown-menu-end">
											<a href="javascript:;" class="dropdown-item">Action 1</a>
											<a href="javascript:;" class="dropdown-item">Action 2</a>
											<a href="javascript:;" class="dropdown-item">Action 3</a>
											<div class="dropdown-divider"></div>
											<a href="javascript:;" class="dropdown-item">Action 4</a>
										</div>
									</div>
								</div>
							</div>
							<!-- END col-6 -->
						</div>
						<!-- END row -->
					</div>
					<!-- END #profile-cr tab -->
                    <?php endif; ?>
				</div>
				<!-- END tab-content -->
			</div>
			<!-- END profile-content -->
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
	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false"></script>
	<script src="assets/plugins/superbox/jquery.superbox.min.js"></script>
	<script src="assets/plugins/lity/dist/lity.min.js"></script>
	<script src="assets/js/demo/profile.demo.js"></script>
	<!-- ================== END page-js ================== -->
</body>
<?php else : ?>
    <?php require_once('login.php'); ?>
<?php endif; ?>
</html>