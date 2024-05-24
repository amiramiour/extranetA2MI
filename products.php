<!DOCTYPE html>
<html lang="fr" >
<head>
    <meta charset="utf-8" />
    <title>A2MI | Produits</title>
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
    <link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />

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
<div id="app" class="app app-header-fixed app-sidebar-fixed">
    <?php require_once('includes/header.php'); ?>
    <?php require_once('includes/sidebar.php'); ?>
    <?php require_once('includes/_requests.php'); ?>

    <!-- BEGIN #content -->
    <div id="content" class="app-content">
        <!--<div class="row">
            <div class="col-md-6">
                <div data-src="pctgn-cdcfe999"><script src="https://pictogon.com/js/pictogon.min.js"></script></div>
            </div>
        </div>-->


        <!-- BEGIN breadcrumb -->
        <ol class="breadcrumb float-xl-end">
            <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
            <li class="breadcrumb-item active">Liste des produits</li>
        </ol>
        <!-- END breadcrumb -->
        <!-- BEGIN page-header -->
        <h1 class="page-header">Liste des produits<small></small></h1>
        <!-- END page-header -->
        <!-- BEGIN panel -->
        <div class="panel panel-inverse">
            <!-- BEGIN panel-heading -->
            <div class="panel-heading">
                <h4 class="panel-title">Liste des produits de la boutique</h4>
            </div>
            <!-- END panel-heading -->
            <!-- BEGIN panel-body -->
            <div class="panel-body">
                <table id="data-table-default" class="table table-striped table-bordered align-middle">
                    <thead>
                    <tr>
                        <th width="1%"></th>
                        <th class="text-nowrap">Nom du produit</th>
                        <th class="text-nowrap">Images</th>
                        <th class="text-nowrap">Texte</th>
                        <th class="text-nowrap">Détails techniques</th>
                        <th width="1%" class="text-nowrap">Quantité</th>
                        <th width="1%" class="text-nowrap">Prix HT</th>
                        <th width="1%" class="text-nowrap">Prix Total</th>
                        <th width="1%" class="text-nowrap">TVA</th>
                        <th class="text-nowrap">Catégorie</th>
                        <th class="text-nowrap">Etat</th>
                        <th class="text-nowrap">Date Fin Garantie</th>
                        <th class="text-nowrap">Date Mise en Ligne</th>
                        <th class="text-nowrap">Poids</th>
                        <th class="text-nowrap">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($allproducts as $product) : ?>
                    <tr class="odd gradeA">
                        <td class="fw-bold text-white"><?= $product->shop_produit_id ?></td>
                        <td><?= $product->shop_produit_nom ?></td>
                        <td>
                            <?php
                            $req = $pdo->prepare("SELECT * FROM shop_image WHERE shop_image_produit_id = $product->shop_produit_id");
                            $req->execute();
                            $allprodimg = $req->fetchAll(PDO::FETCH_OBJ);
                            ?>
                            <?php foreach ($allprodimg as $prodimg) : ?>
                            <img src="img/products/<?= $prodimg->shop_image_miniature ?>" class="rounded h-30px m-1" alt="<?= $prodimg->shop_image_nom ?>" />
                            <?php endforeach; ?>
                        </td>
                        <td><?= nl2br($product->shop_produit_texte) ?></td>
                        <td><?= nl2br($product->shop_produit_description) ?></td>
                        <td><?= $product->shop_produit_quantite ?></td>
                        <td><?= $product->shop_produit_prix ?></td>
                        <td><?= $product->shop_produit_prix_total ?></td>
                        <td><?= $product->shop_produit_tva ?></td>
                        <td><?= $product->shop_categorie_nom ?></td>
                        <td><?= $product->shop_produit_etat ?></td>
                        <td><?= $product->shop_produit_garantie ?></td>
                        <td><?= date_create($product->shop_produit_date)->format('Y-m-d') ?></td>
                        <td><?= $product->shop_produit_poids ?></td>
                        <td><a data-bs-target="#modal-infos" class="btn btn-sm btn-warning w-100px"
                               data-bs-toggle="modal" data-id="<?= $product->shop_produit_id ?>">Modifier</a>
                            <a data-bs-target="#modal-photos" class="btn btn-sm btn-info w-100px"
                               data-bs-toggle="modal" data-id="<?= $product->shop_produit_id ?>">Photos</a>
                        </td>
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

    <?php foreach ($allproducts as $product) : ?>
    <!-- #modal-dialog -->
    <div class="modal fade" id="modal-infos">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Modifier le produit</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-primary" data-bs-dismiss="modal">Fermer</a>
                    <a href="javascript:;" class="btn btn-info">Valider</a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- #modal-alert -->
    <div class="modal fade" id="modal-photos">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ajout de photos</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">

                    <h5>Ajouter des images (seuls les fichiers .jpg / .jpeg / .png / .gif seront enregistrés)<br>
                        En fonction de la taille des fichiers, ne pas déposer plus de 10 images en même temps (au delà de 20 mo).
                    </h5>

                    <div class="row">
                        <div class="col-md-12">
                            <form id="upload" method="POST" enctype="multipart/form-data">
                                <div id="drop">
                                    Glisser les images ici <br><br> ou <br>
                                    <a>Chercher</a>
                                    <input type="file" id="photo" name="photo[]" multiple class="form-control" aria-label="Photo">
                                </div>
                                <ul>
                                    <!-- The file uploads will be shown here -->
                                </ul>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-primary" data-bs-dismiss="modal">Fermer</a>
                    <a href="javascript:;" class="btn btn-info" data-bs-dismiss="modal">Valider</a>
                </div>
            </div>
        </div>
    </div>

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
<script src="assets/plugins/gritter/js/jquery.gritter.js"></script>
<script src="assets/plugins/sweetalert/dist/sweetalert.min.js"></script>
<script src="assets/js/demo/ui-modal-notification.demo.js"></script>
<script src="assets/plugins/@highlightjs/cdn-assets/highlight.min.js"></script>
<script src="assets/js/demo/render.highlight.js"></script>
<!-- ================== END page-js ================== -->

<!-- JavaScript Includes -->
<script src="assets/plugins/dd/js/jquery.knob.js"></script>

<!-- jQuery File Upload Dependencies -->
<script src="assets/plugins/dd/js/jquery.ui.widget.js"></script>
<script src="assets/plugins/dd/js/jquery.iframe-transport.js"></script>
<script src="assets/plugins/dd/js/jquery.fileupload.js"></script>

<!-- Our main JS file -->
<script src="assets/plugins/dd/js/script.js"></script>

<script>
    function setId(id){
        $('#id').val(id);
    }

    $('#modal-infos').on('show.bs.modal', function (e) {
        var rowid = $(e.relatedTarget).data('id');
        $.ajax({
            type : 'POST',
            url : 'includes/_datamodal.php',
            data :  'rowid='+ rowid,
            success : function(data){
                $('.modal-body').html(data);
            }
        });
    });
</script>

</body>
<?php else : ?>
<?php require_once('login.php'); ?>
<?php endif; ?>
</html>