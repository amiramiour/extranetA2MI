<?php
session_start();
require "connexion/traitement_connexion.php";
function connexionbdd()
{
    global $pdo;
    return $pdo;
}

$db = connexionbdd();

if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin' && isset($_SESSION['mail'])) {
    if (isset($_GET['bi_id'])) {
        $bi_id = $_GET['bi_id'];

        //recuperation du client
        $query_client = "SELECT u.users_name, u.users_firstname FROM pret p INNER JOIN users u ON p.users_id = u.users_id WHERE p.pret_id = ?";

        // Récupérer les détails du BI
        $sql_bi = "SELECT * FROM bi WHERE bi_id = ?";
        $stmt_bi = $db->prepare($sql_bi);
        $stmt_bi->bindParam(1, $bi_id);
        $stmt_bi->execute();
        $bi = $stmt_bi->fetch(PDO::FETCH_ASSOC);
        if ($bi) {
            // Récupérer les détails du client
            $sql_client = "SELECT * FROM users WHERE users_id = ?";
            $stmt_client = $db->prepare($sql_client);
            $stmt_client->bindParam(1, $bi['users_id']);
            $stmt_client->execute();
            $client = $stmt_client->fetch(PDO::FETCH_ASSOC);

            // Récupérer les détails des tâches d'intervention
            $sql_taches = "SELECT * FROM intervention WHERE bi_id = ?";
            $stmt_taches = $db->prepare($sql_taches);
            $stmt_taches->bindParam(1, $bi['bi_id']);
            $stmt_taches->execute();
            $taches = $stmt_taches->fetchAll(PDO::FETCH_ASSOC);

            // Récupérer les détails du technicien associé au bon d'intervention
            $sql_technicien = "SELECT * FROM users WHERE users_id = ?";
            $stmt_technicien = $db->prepare($sql_technicien);
            $stmt_technicien->bindParam(1, $bi['bi_technicien']);
            $stmt_technicien->execute();
            $technicien = $stmt_technicien->fetch(PDO::FETCH_ASSOC);

            if (!$taches) {
                die("Aucune tâche d'intervention trouvée pour le bon d'intervention ID $bi_id");
            }
        } else {
            die("Aucun bon d'intervention trouvé avec l'ID $bi_id");
        }
    } else {
        // Rediriger vers la liste des bons d'intervention si aucun ID n'est fourni
        header('Location: list_bi.php');
        exit;
    }
} else {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas autorisé
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <title>A2MI | Fiche BI</title>
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
    <div id="content" class="app-content">
        <!-- BEGIN breadcrumb -->
        <ol class="breadcrumb hidden-print float-xl-end">
            <li class="breadcrumb-item"><a href="javascript:;">Accueil</a></li>
            <li class="breadcrumb-item active">Fiche BI</li>
        </ol>
        <!-- END breadcrumb -->
        <!-- BEGIN page-header -->
        <h1 class="page-header hidden-print">Fiche Bon d'Intervention</h1>
        <!-- END page-header -->
        <!-- BEGIN invoice -->
        <div class="invoice">
            <!-- BEGIN invoice-company -->
            <div class="invoice-company">
                Bon d'intervention n° <?= htmlspecialchars($bi['bi_id']) ?>
                <span class="float-end hidden-print">
                    <a href="generer_bi.php?bi_id=<?= $bi_id ?>" class="btn btn-sm btn-white mb-10px">
                        <i class="fa fa-file-pdf t-plus-1 text-danger fa-fw fa-lg"></i> Exporter en PDF
                    </a>
                </span>
            </div>
            <!-- END invoice-company -->
            <!-- BEGIN invoice-header -->
            <div class="invoice-header">
                <div class="invoice-from">
                    <small>De</small>
                    <address class="mt-5px mb-5px">
                        <strong class="text-white">A2MI-Informatique</strong><br/>
                        10-14 rue Jean Perrin<br/>
                        17000 La Rochelle<br/>
                        Tél : 09 51 52 42 86
                    </address>
                </div>
                <div class="invoice-to">
                    <small>À</small>
                    <address class="mt-5px mb-5px">
                        <strong class="text-white"><?= htmlspecialchars($client['users_name']) ?></strong>
                        <strong class="text-white"><?= htmlspecialchars($client['users_firstname']) ?></strong>

                        <br/>
                        <?= htmlspecialchars($client['users_address']) ?><br/>
                        <?= htmlspecialchars($client['users_city']) ?>
                        , <?= htmlspecialchars($client['users_postcode']) ?><br/>
                        Tél : <?= htmlspecialchars($client['users_mobile']) ?>
                    </address>
                </div>
                <div class="invoice-date">
                    <small>BI Date</small>
                    <div class="date text-white mt-5px"><?= htmlspecialchars(date('Y-m-d', $bi['bi_date'])) ?></div>
                    <div class="invoice-detail">
                        <small>Bi id :</small>
                        <?= htmlspecialchars($bi['bi_id']) ?><br/>
                    </div>
                </div>
            </div>
            <!-- END invoice-header -->


            <div class="m-3">
                <?= htmlspecialchars($bi['bi_commentaire']) ?>
            </div>
            <!-- BEGIN invoice-price -->
            <div class="invoice-price">
                <div class="invoice-price-left">
                    <?php
                    $prix_total_global = 0; // Initialise le prix total global
                    foreach ($taches as $tache) :
                        $prix_total_piece = $tache['inter_nbpiece'] * $tache['inter_prixunit']; // Calcule le prix total de la pièce
                        $prix_total_global += $prix_total_piece; // Ajoute le prix total de la pièce au prix total global
                        ?>
                        <div class="invoice-price-row">
                            <div class="sub-price">
                                <small><?= htmlspecialchars($tache['inter_nbpiece']) ?> pièce(s)</small>
                                <ul>
                                    <li>
                                        <span class="text-white">Prix/pièce : <?= htmlspecialchars($tache['inter_prixunit']) ?>€</span>
                                    </li>
                                    <li>
                                        <span class="text-white">Prix total : <?= htmlspecialchars($prix_total_piece) ?>€</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="invoice-price-right">
                    <small>Prix total global</small>
                    <span class="fw-bold"><?= htmlspecialchars($prix_total_global) ?>€</span>
                </div>
            </div>
            <!-- END invoice-price -->
            <!-- BEGIN row -->
            <div class="row mt-5">
                <!-- BEGIN col-12 -->
                <div class="col-xl-12">
                    <div class="panel panel-inverse">
                        <!-- BEGIN panel-heading -->
                        <div class="panel-heading">
                            <h4 class="panel-title">Liste des bons d'intervention</h4>
                        </div>
                        <!-- END panel-heading -->
                        <!-- BEGIN panel-body -->
                        <div class="panel-body">
                            <table class="table table-striped table-bordered align-middle">
                                <thead>
                                <tr>
                                    <th class="text-nowrap">N° Bon d'intervention</th>
                                    <th class="text-nowrap">Client</th>
                                    <th class="text-nowrap">Date</th>
                                    <th class="text-nowrap">Facturé</th>
                                    <th class="text-nowrap">Garantie</th>
                                    <th class="text-nowrap">Contrat</th>
                                    <th class="text-nowrap">Service</th>
                                    <th class="text-nowrap">Envoi</th>
                                    <th class="text-nowrap">Facturation</th>
                                    <th class="text-nowrap">Date Facturation</th>
                                    <th class="text-nowrap">Paiement</th>
                                    <th class="text-nowrap">Heure d'arrivée</th>
                                    <th class="text-nowrap">Heure de départ</th>
                                    <th class="text-nowrap">Technicien</th>
                                    <th class="text-nowrap">Commentaire</th>
                                    <th class="text-nowrap">Règle</th>
                                    <th class="text-nowrap">Active</th>
                                    <?php if ($_SESSION['role'] === 'admin'): ?>
                                        <th class="text-nowrap">Actions</th>
                                    <?php endif; ?>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><?= htmlspecialchars($bi['bi_id']) ?></td>
                                    <td>
                                        <a href="profile.php?id=<?= $client['users_id'] ?>">
                                            <?= htmlspecialchars($client['users_name']) ?> <?= htmlspecialchars($client['users_firstname']) ?>
                                        </a>
                                    </td>
                                    <td><?= htmlspecialchars(date('Y-m-d', $bi['bi_date'])) ?></td>
                                    <td><?= htmlspecialchars($bi['bi_facture']) ?></td>
                                    <td><?= htmlspecialchars($bi['bi_garantie']) ?></td>
                                    <td><?= htmlspecialchars($bi['bi_contrat']) ?></td>
                                    <td><?= htmlspecialchars($bi['bi_service']) ?></td>
                                    <td><?= htmlspecialchars($bi['bi_envoi']) ?></td>
                                    <td><?= htmlspecialchars($bi['bi_facturation']) ?></td>
                                    <td><?= htmlspecialchars(date('Y-m-d', $bi['bi_datefacturation'])) ?></td>
                                    <td><?= htmlspecialchars($bi['bi_paiement']) ?></td>
                                    <td><?= htmlspecialchars($bi['bi_heurearrive']) ?></td>
                                    <td><?= htmlspecialchars($bi['bi_heuredepart']) ?></td>
                                    <td><?= htmlspecialchars($technicien['users_name']) . ' ' . htmlspecialchars($technicien['users_firstname']) ?></td>
                                    <td><?= htmlspecialchars($bi['bi_commentaire']) ?></td>
                                    <td><?= htmlspecialchars($bi['bi_regle']) ?></td>
                                    <td><?= htmlspecialchars($bi['bi_active']) ?></td>
                                    <?php if ($_SESSION['role'] === 'admin'): ?>
                                        <td>
                                            <a href="modification_bi.php?bi_id=<?= $bi['bi_id'] ?>"
                                               class="btn btn-primary">Modifier</a>
                                            <form action="list_bi.php" method="post" style="display: inline;">
                                                <input type="hidden" name="action" value="supprimer">
                                                <input type="hidden" name="bi_id" value="<?= $bi['bi_id'] ?>">
                                                <button type="submit" class="btn btn-danger"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce bon d\'intervention ?')">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </td>
                                    <?php endif; ?>
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


        </div>
        <!-- END invoice-content -->
        <!-- BEGIN invoice-note -->
        <div class="invoice-note">
            * Veuillez libeller tous les chèques à l'ordre de A2MI<br/>
            * Le paiement est dû dans les 30 jours<br/>
            * Si vous avez des questions concernant cette facture, contactez [Nom, Téléphone, Email]
        </div>
        <!-- END invoice-note -->
        <!-- BEGIN invoice-footer -->
        <div class="invoice-footer">
            <p class="text-center mb-5px fw-bold">
                MERCI DE VOTRE CONFIANCE
            </p>
            <p class="text-center">
                <span class="me-10px"><i class="fa fa-fw fa-lg fa-globe"></i> www.a2mi-informatique.fr</span>
                <span class="me-10px"><i class="fa fa-fw fa-lg fa-phone-volume"></i> T:09 51 52 42 86</span>
                <span class="me-10px"><i class="fa fa-fw fa-lg fa-envelope"></i> contact@a2mi-informatique.fr</span>
            </p>
        </div>
        <!-- END invoice-footer -->
    </div>
    <!-- END invoice -->
</div>
<!-- END #content -->

</div>
<!-- END #app -->
<!-- ================== BEGIN core-js ================== -->
<script src="assets/js/vendor.min.js"></script>
<script src="assets/js/app.min.js"></script>
<!-- ================== END core-js ================== -->
</body>
</html>