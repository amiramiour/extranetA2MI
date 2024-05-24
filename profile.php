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
        <?php require_once('includes/_requests_profile_cmd_devis.php'); ?>

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
                        <li class="nav-item"><a href="#profile-orders" class="nav-link" data-bs-toggle="tab">COMMANDES</a></li>
						<li class="nav-item"><a href="#profile-sales" class="nav-link" data-bs-toggle="tab">DEVIS</a></li>
                        <?php if (isset($_SESSION['role']) AND $_SESSION['role'] != 'client' AND isset($_SESSION['mail'])): ?>
                        <li class="nav-item"><a href="#profile-post" class="nav-link" data-bs-toggle="tab">HISTORIQUE</a></li>
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

                                <?php if (isset($_SESSION['role']) AND $_SESSION['role'] != 'client' AND isset($_SESSION['mail'])): ?>
                                <tr class="highlight">
                                    <td class="field pink">ACTIONS</td>
                                    <td class="">
                                        <a href=""><button type="submit" class="btn btn-indigo w-150px m-1">Bon d'Intervention</button></a>
                                        <a href=""><button type="submit" class="btn btn-indigo w-150px m-1">SAV</button></a>
                                        <a href=""><button type="submit" class="btn btn-indigo w-150px m-1">Prêt</button></a>
                                        <a href="ajout_commande_devis.php?id=<?=$id?>"><button type="submit" class="btn btn-indigo w-150px m-1">Commande/Devis</button></a>
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
							<!-- BEGIN timeline-item -->
							<div class="timeline-item">
								<!-- BEGIN timeline-time -->
								<div class="timeline-time">
									<span class="date">24 / 02 / 2021</span>
									<span class="time">Prêt</span>
								</div>
								<!-- END timeline-time -->
								<!-- BEGIN timeline-icon -->
								<div class="timeline-icon">
									<a href="javascript:;">&nbsp;</a>
								</div>
								<!-- END timeline-icon -->
								<!-- BEGIN timeline-content -->
								<div class="timeline-content">
									<!-- BEGIN timeline-header -->
									<div class="timeline-header">
										<div class="userimage"><img src="assets/img/user/user-3.jpg" alt="" /></div>
										<div class="username">
											<div>Prêt<i class="fa fa-check-circle text-green ms-1"></i></div>
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
                    <!-- BEGIN #profile-orders tab -->
                    <div class="tab-pane fade" id="profile-orders">
                        <?php if(!empty($commandes_client)) : ?>
                        <?php foreach ($commandes_client as $commande) : ?>
                            <?php if($commande['users_id'] == $_GET['id']) : ?>
                                <!-- BEGIN timeline -->
                                <div class="timeline">
                                    <!-- BEGIN timeline-item -->
                                    <div class="timeline-item">
                                        <!-- BEGIN timeline-time -->
                                        <div class="timeline-time">
                                            <span class="date"><?= date('Y-m-d' ,$commande['cmd_devis_datein']) ?></span>
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
                                                <?php if($commande['sauvgarde_etat'] == 1): ?>
                                                    <div class="userimage ">
                                                        <i class="fa fa-thumbs-up text-info ms-1"></i>
                                                    </div>
                                                    <div class="username check-size">
                                                        <a href="fiche_commande.php?id=<?=$commande['cmd_devis_id']?>">
                                                            <div>Commande n° A2MI-<?= $commande['cmd_devis_id'] ?>
                                                                Commandée</div>
                                                        </a>
                                                        <div class="text-muted fs-12px">Le:
                                                            <?= $commande['date_creation'] ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if( $commande['sauvgarde_etat'] == 2): ?>
                                                    <div class="userimage ">
                                                        <i class="fa fa-clock text-orange ms-1"></i>
                                                    </div>
                                                    <div class="username check-size">
                                                        <a href="#">
                                                            <div>Commande n° A2MI-<?= $commande['cmd_devis_id'] ?>
                                                                en attente</div>
                                                        </a>
                                                        <div class="text-muted fs-12px">Le: <?= $commande['date_update'] ?></div>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if($commande['sauvgarde_etat'] == 3) : ?>
                                                    <div class="userimage ">
                                                        <i class="fa fa-check-circle text-green ms-1"></i>
                                                    </div>
                                                    <div class="username check-size">
                                                        <a href="#">
                                                            <div>Commande n° A2MI-<?= $commande['cmd_devis_id'] ?>
                                                                reçue</div>
                                                        </a>
                                                        <div class="text-muted fs-12px">Le : <?= $commande['date_update'] ?></div>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if($commande['sauvgarde_etat'] == 4) : ?>
                                                    <div class="userimage ">
                                                        <i class="fa fa-check-circle text-green ms-1"></i>
                                                    </div>
                                                    <div class="username check-size">
                                                        <a href="#">
                                                            <div>Commande n° A2MI-<?= $commande['cmd_devis_id'] ?>
                                                                à livrer</div>
                                                        </a>
                                                        <div class="text-muted fs-12px">Le : <?= $commande['date_update'] ?></div>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if($commande['sauvgarde_etat'] == 5) : ?>
                                                    <div class="userimage ">
                                                        <i class="fa fa-check-circle text-green ms-1"></i>
                                                    </div>
                                                    <div class="username check-size">
                                                        <a href="#">
                                                            <div>Commande n° A2MI-<?= $commande['cmd_devis_id'] ?>
                                                                livrée</div>
                                                        </a>
                                                        <div class="text-muted fs-12px">Le : <?= $commande['date_update'] ?></div>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if($commande['sauvgarde_etat'] == 6) : ?>
                                                    <div class="userimage ">
                                                        <i class="fa fa-check-circle text-green ms-1"></i>
                                                    </div>
                                                    <div class="username check-size">
                                                        <a href="#">
                                                            <div>Commande n° A2MI-<?= $commande['cmd_devis_id'] ?>
                                                                terminée</div>
                                                        </a>
                                                        <div class="text-muted fs-12px">Le : <?= $commande['date_update'] ?></div>
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
                                                                <th>Articles</th>
                                                                <th class="text-center" width="15%">HT</th>
                                                                <th class="text-end" width="15%">TTC</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            $idCommande = $commande['cmd_devis_id'];
                                                            $req = $pdo->prepare("SELECT * 
                                                                                    FROM cmd_produit 
                                                                                    WHERE id_commande = :id");
                                                            $req->execute(array(":id" => $idCommande));
                                                            $produits_commande = $req->fetchAll(PDO::FETCH_ASSOC);
                                                            ?>
                                                            <?php foreach ($produits_commande as $produit) : ?>
                                                                <tr>
                                                                    <td>
                                                                        <span class="text-white fw-700"><?= $produit['reference'] ?></span><br />
                                                                        <small class="text-gray-400">
                                                                            <?= $produit['designation'] ?>
                                                                        </small>
                                                                    </td>
                                                                    <td class="text-center"><?= $produit['paHT'] ?> €</td>
                                                                    <td class="text-end"> <?= $produit['pvTTC'] ?> €</td>
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
                                                                    <span class="text-white">
                                                                <?= $commande['cmd_devis_prixHT'] ?> €
                                                                </span>
                                                                </div>
                                                                <div class="sub-price">
                                                                    <i class="fa fa-plus text-muted"></i>
                                                                </div>
                                                                <div class="sub-price">
                                                                    <small>TVA</small>
                                                                    <span class="text-white">20 %
                                                                    
                                                                </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="invoice-price-right">
                                                            <small>TOTAL</small>
                                                            <span class="fw-bold"> <?= $commande['cmd_devis_prixventettc'] ?> €</span>
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
                        <?php else : ?>
                            <div class="alert alert-info" role="alert">
                                <h4 class="alert-heading">Aucun historique des commandes</h4>
                                <p>Il n'y a aucune commande pour le moment.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!-- END #profile-sales tab -->
                    <!-- BEGIN #profile-sales tab -->
                    <div class="tab-pane fade" id="profile-sales">
                        <?php if(!empty($devis_client)) : ?>
                        <?php foreach ($devis_client as $devis) : ?>
                            <?php if($devis['users_id'] == $_GET['id']) : ?>
                                <!-- BEGIN timeline -->
                                <div class="timeline">
                                    <!-- BEGIN timeline-item -->
                                    <div class="timeline-item">
                                        <!-- BEGIN timeline-time -->
                                        <div class="timeline-time">
                                            <span class="date"><?= date('Y-m-d', $devis['cmd_devis_datein']) ?></span>
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
                                                <?php if( $devis['sauvgarde_etat'] == 2): ?>
                                                    <div class="userimage ">
                                                        <i class="fa fa-clock text-orange ms-1"></i>
                                                    </div>
                                                    <div class="username check-size">
                                                        <a href="fiche_devis.php?id=<?=$devis['cmd_devis_id']?>">
                                                            <div>Devis n° A2MI-<?= $devis['cmd_devis_id'] ?>
                                                                en attente</div>
                                                        </a>
                                                        <div class="text-muted fs-12px">Le: <?= $devis['date_update'] ?></div>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <?php if($devis['sauvgarde_etat'] == 6) : ?>
                                                    <div class="userimage ">
                                                        <i class="fa fa-check-circle text-green ms-1"></i>
                                                    </div>
                                                    <div class="username check-size">
                                                        <a href="fiche_devis.php?id=<?=$devis['cmd_devis_id']?>">
                                                            <div>Devis n° A2MI-<?= $devis['cmd_devis_id'] ?>
                                                                terminé</div>
                                                        </a>
                                                        <div class="text-muted fs-12px">Le : <?= $devis['date_update'] ?></div>
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
                                                                <th>Articles</th>
                                                                <th class="text-center" width="15%">HT</th>
                                                                <th class="text-end" width="15%">TTC</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            $idDevis = $devis['cmd_devis_id'];
                                                            $req = $pdo->prepare("SELECT * 
                                                                                    FROM devis_produit 
                                                                                    WHERE id_devis = :id");
                                                            $req->execute(array(":id" => $idDevis));
                                                            $produits_devis = $req->fetchAll(PDO::FETCH_ASSOC);
                                                            ?>
                                                            <?php foreach ($produits_devis as $produit) : ?>
                                                                <tr>
                                                                    <td>
                                                                        <span class="text-white fw-700"><?= $produit['reference'] ?></span><br />
                                                                        <small class="text-gray-400">
                                                                            <?= $produit['designation'] ?>
                                                                        </small>
                                                                    </td>
                                                                    <td class="text-center"><?= $produit['paHT'] ?> €</td>
                                                                    <td class="text-end"> <?= $produit['pvTTC'] ?> €</td>
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
                                                                    <span class="text-white">
                                                                <?= $devis['cmd_devis_prixHT'] ?> €
                                                                </span>
                                                                </div>
                                                                <div class="sub-price">
                                                                    <i class="fa fa-plus text-muted"></i>
                                                                </div>
                                                                <div class="sub-price">
                                                                    <small>devis</small>
                                                                    <span class="text-white">20 %</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="invoice-price-right">
                                                            <small>TOTAL</small>
                                                            <span class="fw-bold"> <?= $devis['cmd_devis_prixventettc'] ?> €</span>
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
                        <?php else : ?>
                            <div class="alert alert-info" role="alert">
                                <h4 class="alert-heading">Aucun historique des devis</h4>
                                <p>Il n'y a aucun devis pour le moment.</p>
                            </div>
                        <?php endif; ?>
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