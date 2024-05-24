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
    if (isset($_GET['pret_id'])) {
        $pret_id = $_GET['pret_id'];

        // Requête SQL pour récupérer les détails du prêt
        $query = "SELECT * FROM pret WHERE pret_id = ?";
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $pret_id);
        $stmt->execute();
        $pret = $stmt->fetch(PDO::FETCH_ASSOC);

        // Requête SQL pour récupérer les détails du client
        $query_client = "SELECT * FROM users WHERE users_id = ?";
        $stmt_client = $db->prepare($query_client);
        $stmt_client->bindParam(1, $pret['users_id']);
        $stmt_client->execute();
        $client = $stmt_client->fetch(PDO::FETCH_ASSOC);

        // Récupérer les détails du technicien associé au prêt
        $sql_technicien = "SELECT users_name, users_firstname FROM users WHERE users_id = ?";
        $stmt_technicien = $db->prepare($sql_technicien);
        $stmt_technicien->bindParam(1, $pret['pret_technicien']);
        $stmt_technicien->execute();
        $technicien = $stmt_technicien->fetch(PDO::FETCH_ASSOC);


        $query = "
    SELECT 
        p.*, 
        e.etat_intitule 
    FROM 
        pret p
    INNER JOIN 
        pret_etat e ON p.pret_etat = e.id_etat_pret
    WHERE 
        p.pret_id = ?";
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $pret_id);
        $stmt->execute();
        $pret = $stmt->fetch(PDO::FETCH_ASSOC);


        // Traitement du formulaire de suppression du prêt
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'supprimer' && isset($_POST['pret_id'])) {
            $pret_id = $_POST['pret_id'];

            // Requête SQL pour mettre à jour l'attribut pret_active du prêt à 0
            $query_delete = "UPDATE pret SET pret_active = 0 WHERE pret_id = ?";
            $stmt_delete = $db->prepare($query_delete);
            $stmt_delete->bindParam(1, $pret_id);
            $stmt_delete->execute();

            $query_client = "SELECT u.users_name, u.users_firstname FROM pret p INNER JOIN users u ON p.users_id = u.users_id WHERE p.pret_id = ?";

            // Redirection vers la même page après suppression
            header("Location: list_pret.php");
            exit;
        }

        // Si le prêt existe, affichez ses détails
        if ($pret) {
            // Afficher les détails du prêt ici dans votre HTML
            // Par exemple, vous pouvez accéder aux détails comme $pret['nom_colonne']
        } else {
            // Si aucun prêt n'est trouvé avec cet ID, affichez un message d'erreur
            echo "Aucun prêt trouvé avec l'ID $pret_id";
        }
    } else {
        // Rediriger vers la liste des prêts si aucun ID n'est fourni
        header('Location: list_pret.php');
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
    <title>A2MI | Fiche Prêt</title>
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
            <li class="breadcrumb-item active">Fiche Prêt</li>
        </ol>
        <!-- END breadcrumb -->
        <!-- BEGIN page-header -->
        <h1 class="page-header hidden-print">Fiche Prêt</h1>
        <!-- END page-header -->
        <!-- BEGIN invoice -->
        <div class="invoice">
            <!-- BEGIN invoice-company -->
            <div class="invoice-company">
                Prêt n° <?= htmlspecialchars($pret['pret_id']) ?>
                <span class="float-end hidden-print">
        <a href="generer_pret.php?pret_id=<?= htmlspecialchars($pret['pret_id']) ?>"
           class="btn btn-sm btn-white mb-10px">
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
                    <small>Date de début</small> <!-- Modification ici pour afficher la date de début du prêt -->
                    <div class="date text-white mt-5px"><?= htmlspecialchars(date('Y-m-d', strtotime($pret['pret_datein']))) ?></div>
                    <!-- Modification ici pour afficher la date de début du prêt -->
                    <div class="invoice-detail">
                        <small>Prêt ID :</small>
                        <?= htmlspecialchars($pret['pret_id']) ?><br/>
                    </div>
                </div>
            </div>
            <!-- END invoice-header -->

            <!-- BEGIN row -->
            <div class="row mt-5">
                <!-- BEGIN col-12 -->
                <div class="col-xl-12">
                    <div class="panel panel-inverse">
                        <!-- BEGIN panel-heading -->
                        <div class="panel-heading">
                            <h4 class="panel-title">Détails du prêt</h4>
                        </div>
                        <!-- END panel-heading -->
                        <!-- BEGIN panel-body -->
                        <div class="panel-body">
                            <table class="table table-striped table-bordered align-middle">
                                <thead>
                                <tr>
                                    <th class="text-nowrap">N° Prêt</th>
                                    <th class="text-nowrap">Client</th>
                                    <th class="text-nowrap">Matériel</th>
                                    <th class="text-nowrap">Caution</th>
                                    <th class="text-nowrap">Mode</th>
                                    <th class="text-nowrap">Date de début</th>
                                    <th class="text-nowrap">Date de retour</th>
                                    <th class="text-nowrap">Technicien</th>
                                    <th class="text-nowrap">Commentaire</th>
                                    <th class="text-nowrap">État</th>
                                    <th class="text-nowrap">Active</th>
                                    <?php if ($_SESSION['role'] === 'admin'): ?>
                                        <th class="text-nowrap">Actions</th>
                                    <?php endif; ?>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><?= htmlspecialchars($pret['pret_id']) ?></td>
                                    <td>
                                        <a href="profile.php?id=<?= $client['users_id'] ?>">
                                            <?= htmlspecialchars($client['users_name']) ?> <?= htmlspecialchars($client['users_firstname']) ?>
                                        </a>
                                    </td>
                                    <td><?= htmlspecialchars($pret['pret_materiel']) ?></td>
                                    <td><?= htmlspecialchars($pret['pret_caution']) ?></td>
                                    <td><?= htmlspecialchars($pret['pret_mode']) ?></td>
                                    <td><?= htmlspecialchars(date('d/m/Y', strtotime($pret['pret_datein']))) ?></td>
                                    <td><?= htmlspecialchars(date('d/m/Y', strtotime($pret['pret_dateout']))) ?></td>
                                    <td><?= htmlspecialchars($technicien['users_name'] . ' ' . $technicien['users_firstname']) ?></td>
                                    <td><?= htmlspecialchars($pret['commentaire']) ?></td>
                                    <td><?= htmlspecialchars($pret['etat_intitule']) ?></td>
                                    <td><?= htmlspecialchars($pret['pret_active']) ?></td>
                                    <?php if ($_SESSION['role'] === 'admin'): ?>
                                        <td>
                                            <a href="modifier_pret.php?id=<?= $pret['pret_id'] ?>"
                                               class="btn btn-primary">Modifier</a>
                                            <form action="" method="post" style="display: inline;"
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce prêt ?');">
                                                <input type="hidden" name="action" value="supprimer">
                                                <input type="hidden" name="pret_id" value="<?= $pret['pret_id'] ?>">
                                                <button type="submit" class="btn btn-danger">Supprimer</button>
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
