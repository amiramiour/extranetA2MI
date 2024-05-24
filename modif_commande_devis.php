<!DOCTYPE html>
<html lang="fr" >
<head>
    <meta charset="utf-8" />
    <title>A2MI | Formulaire</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />

    <!-- ================== BEGIN core-css ================== -->
    <link href="assets/css/vendor.min.css" rel="stylesheet" />
    <link href="assets/css/transparent/app.min.css" rel="stylesheet" />
    <link href="assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" />
    <link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
    <link href="assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css" rel="stylesheet" />
    <link href="assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" />
    <link href="assets/plugins/ion-rangeslider/css/ion.rangeSlider.min.css" rel="stylesheet" />
    <link href="assets/plugins/tag-it/css/jquery.tagit.css" rel="stylesheet" />
    <link href="assets/plugins/spectrum-colorpicker2/dist/spectrum.min.css" rel="stylesheet" />
    <link href="assets/plugins/select-picker/dist/picker.min.css" rel="stylesheet" />

    <link href="assets/css/custom.css" rel="stylesheet" />
    <!-- ================== END core-css ================== -->

    <style>
        /* Styles pour la boîte modale */
        .modal {
            display: none; /* Masqué par défaut */
            position: fixed; /* Reste en place */
            z-index: 1; /* Au-dessus des autres éléments */
            padding-top: 60px; /* Emplacement de la boîte */
            left: 0;
            top: 0;
            width: 100%; /* Largeur complète */
            height: 100%; /* Hauteur complète */
            overflow: auto; /* Scroll si nécessaire */
            background-color: rgb(0,0,0); /* Fond noir */
            background-color: rgba(0,0,0,0.9); /* Fond avec opacité */
        }
        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
        }
        .modal-content img {
            width: 100%;
        }
        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<?php
session_start();
require_once('includes/_functions.php');
require_once('connexion/traitement_connexion.php');
require_once('connexion/config.php');
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
    <?php require_once('includes/_requests_cmd_devis.php'); ?>

    <!-- BEGIN #content -->
    <div id="content" class="app-content">
        <!-- BEGIN breadcrumb -->
        <ol class="breadcrumb float-xl-end">
            <li class="breadcrumb-item"><a href="javascript:;">Affiche</a></li>
            <li class="breadcrumb-item active">Formulaire</li>
        </ol>
        <!-- END breadcrumb -->
        <!-- BEGIN page-header -->
        <h1 class="page-header">Formulaire de modification <small>Commande / Devis</small></h1>

        <!-- END page-header -->
        <!-- BEGIN row -->
        <div class="row">
            <!-- BEGIN col-6 -->
            <div class="col-xl-12">
                <!-- BEGIN panel -->
                <div class="panel panel-inverse" data-sortable-id="form-validation-1">
                <form class="form-horizontal" data-parsley-validate="true" name="demo-form" action="inc_extranet/traitement_modif_cmd_devis.php?idCommande=<?=$idCommande?>&idClient=<?=$commande['users_id']?>" method="post" enctype="multipart/form-data">
                    
                    <!-- BEGIN panel-heading -->
                    <div class="panel-heading">
                        <div class="col-lg-8">
                        </div>                    
                    </div>
                    <!-- END panel-heading -->
                    <!-- BEGIN panel-body -->
                    <div class="panel-body">
                        <div id="materiels">
                            <?php
                            $i = 0;
                            foreach ($produits as $produit) { $i++;?>
                                <div id="materiel" class="mb-4">
                                    <div class="row mb-3"> 
                                        <div class="col-lg"> 
                                            <label class="col-form-label form-label" for="reference">Reference * :</label>
                                            <input class="form-control" type="text" id="reference" name="dynamic['<?=$i?>'][]" data-parsley-required="true" value="<?=$produit['reference']?>"/>
                                        </div>
                                        
                                        <div class="col-lg"> 
                                            <label class="col-form-label form-label" for="designation">Désignation * :</label>
                                            <input class="form-control" type="text" id="designation" name="dynamic['<?=$i?>'][]" data-parsley-required="true" value="<?=$produit['designation']?>"/>
                                        </div>

                                        <div class="col-lg">
                                            <label class="col-form-label form-label">Fournisseur</label>
                                            <select class="form-select" id="select-required" name="dynamic['<?=$i?>'][]" data-parsley-required="true">
                                                <?php foreach($allsuppliers as $supplier) { ?>
                                                    <option value="<?php echo $supplier->fournisseurs_id ?>" <?php if ($supplier->fournisseurs_id == $produit['fournisseur']) { echo 'selected'; } ?>><?php echo $supplier->fournisseurs_name ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div> 

                                    <div class="row mb-3"> 
                                        <div class="col-lg"> 
                                            <label class="form-label form-label">Pa HT* :</label>
                                            <input class="form-control" type="text" id="paHT<?=$i?>" name="dynamic['<?=$i?>'][]" data-parsley-required="true" value="<?=$produit['paHT']?>" onblur="calculerenplus(<?php echo($i)?>)" required/>
                                        </div>
                                        <div class="col-lg">
                                            <label class="form-label form-label">Marge* :</label>
                                            <input class="form-control" type="text" id="marge<?=$i?>" name="dynamic['<?=$i?>'][]" value="<?=$produit['marge']?>" onblur="calculerenplus(<?php echo($i)?>)" required/>
                                        </div>
                                        <div class="col-lg">
                                            <label class="form-label form-label">Pv HT* :</label>
                                            <input class="form-control" type="text" id="pvHT<?=$i?>" name="dynamic['<?=$i?>'][]" value="<?=$produit['pvHT']?>" readonly required/>
                                        </div>
                                        <div class="col-lg">
                                            <label class="form-label form-label">Pv TTC* :</label>
                                            <input class="form-control" type="text" id="pvTTC<?=$i?>" name="dynamic['<?=$i?>'][]" value="<?=$produit['pvTTC']?>" readonly required/>
                                        </div>
                                        <div class="col-lg">
                                            <label class="form-label form-label">Montant marge :</label>
                                            <input class="form-control" type="text" id="margeM<?=$i?>" name="dynamic['<?=$i?>'][]" value="<?=$produit['pvHT'] - $produit['paHT']?>" readonly required/>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <?php if($commande['type_cmd_devis'] == 1) { ?>
                                            <div class="col-lg-4"> 
                                                <label class="form-label" for="quantiteDemandee">Quantité demandée* :</label>
                                                <input class="form-control" type="number" id="quantite_demandees<?=$i?>" name="dynamic['<?=$i?>'][]" value="<?=$produit['quantite_demandee']?>" data-parsley-required="true" readonly/>
                                            </div>
                                        <?php }else{ ?>
                                            <div class="col-lg-4"> 
                                                <label class="form-label" for="quantiteDemandee">Quantité demandée* :</label>
                                                <input class="form-control" type="number" id="quantite_demandees<?=$i?>" name="dynamic['<?=$i?>'][]" value="<?=$produit['quantite_demandee']?>" onblur="calculerenplus(<?php echo($i)?>)" data-parsley-required="true"/>
                                            </div>
                                        <?php } ?>
                                        
                                        <?php if($commande['type_cmd_devis'] == 1) { ?>
                                            <div class="col-lg-4">
                                                <label class="form-label" for="quantiteRecue">Quantité reçue* :</label>
                                                <input class="form-control" type="number" name="dynamic['<?php echo($i) ?>'][]" value="<?=$produit['quantite_recue'] ?>" onblur="calculerenplus(<?php echo($i)?>)" id="quantite_recues<?=$i?>" onblur="calcul()" required/>
                                            </div>
                                        <?php } ?>
                                        
                                        <div id="lesChoix">
                                            <div id="choixArrondi<?=$i?>">
                                        </div>
                                    </div>

                                    <div class="row mb-3 mt-3">
                                            <div class="col-lg-2">
                                                <label class="form-label">Statut produit* :</label>
                                            </div>

                                            <div class="col-lg-4"> 
                                                <select class="form-select" id="select-etat-produit" name="dynamic['<?php echo($i) ?>'][]" data-parsley-required="true">
                                                    <option value="commande"<?php if ($produit['etat'] === 'commande') echo 'selected'; ?>>Commandé</option>
                                                    <option value="recu"<?php if ($produit['etat'] === 'recu') echo 'selected'; ?>>Reçu</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                            <?php } ?>
                        </div>

                        <div class="row mb-3 mt-3">
                            <div class="col-lg-4">
                                <button type="button" class="btn btn-danger" onclick="supprimerMateriel()">Enlever un matériel</button>
                            </div>
                            <div class="col-lg-4">
                                <button type="button" class="btn btn-success" onclick="ajouterMateriel()">Ajouter un matériel</button>
                            </div>
                        </div>

                        <?php if($commande['type_cmd_devis'] == 2) { ?>
                            <div id="champsDevis">
                                <div class="form-group row mb-3">
                                    <label class="col-lg-4 col-form-label form-label" for="commentaire">Commentaire* :</label>
                                    <div class="col-lg-8">
                                        <textarea class="form-control" id="commentaire" name="commentaire" rows="4" data-parsley-minlength="20" data-parsley-maxlength="100"><?=$commande['commentaire']?></textarea>

                                    </div>
                                </div>

                                <div class="form-group column mb-3" id="photos">
                                    <?php foreach($photos as $i=>$photo){ ?>
                                        <div class="col-lg-4">
                                            <?php if (pathinfo($photo['photo'], PATHINFO_EXTENSION) === 'pdf') { ?>
                                                <a href="img/images_devis/<?=$photo['photo']?>" download><?=basename($photo['photo']);?></a>
                                            <?php } else { ?>
                                                <a href="javascript:void(0);" onclick="openModal('<?= 'img/images_devis/' . $photo['photo'] ?>')">
                                                    <img src="<?= 'img/images_devis/' . $photo['photo']?>" alt="photo" style="width: 100px; height: 100px;">
                                                </a>                 
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                    <!-- La boîte modale -->
                                    <div id="myModal" class="modal">
                                        <span class="close" onclick="closeModal()">&times;</span>
                                        <div class="modal-content">
                                            <img id="modalImage" src="">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3 mt-3">
                                    <div class="col-lg-8">
                                        <button type="button" class="btn btn-success" onclick="ajouterPhoto()">Ajouter une photo</button>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="row mb-3"> 
                            <div class="col-lg"> 
                                <label class="col-form-label form-label" for="reference">Reference * :</label>
                                <input class="form-control" type="text" id="reference" name="nomC" data-parsley-required="true" value="<?=$commande['cmd_devis_reference']?>"/>
                            </div>
                                    
                            <div class="col-lg"> 
                                <label class="col-form-label form-label" for="designation">Désignation * :</label>
                                <input class="form-control" type="text" id="designation" name="designationC" data-parsley-required="true" Value="<?= $commande['cmd_devis_designation']?>"/>
                            </div>

                            <div class="col-lg">
                                <label class="col-form-label form-label">Statut* :</label>
                                <select class="form-select" id="etatC" name="etatC" data-parsley-required="true">
                                    <option value="<?= $commande['id_etat_cmd_devis'] ?>" selected><?= $commande['cmd_devis_etat'] ?></option>
                                    <?php foreach ($etats as $etat) { ?>
                                        <?php if ($commande['type_cmd_devis'] == 1 && $etat->cmd_devis_etat !== $commande['cmd_devis_etat']) { ?>
                                            <option value="<?= $etat->id_etat_cmd_devis ?>"><?= $etat->cmd_devis_etat ?></option>
                                        <?php } elseif ($commande['type_cmd_devis'] != 1 && !in_array($etat->id_etat_cmd_devis, [1, 3, 4, 5]) && $etat->cmd_devis_etat !== $commande['cmd_devis_etat']) { ?>
                                            <option value="<?= $etat->id_etat_cmd_devis ?>"><?= $etat->cmd_devis_etat ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div> 
                        
                        <div class="form-group row mb-3">
                            <label class="form-label col-form-label col-lg-4">Date de livraison souhaitée* :</label>
                            <div class="col-lg-4">
                                <input type="date" class="form-control"  name="dateS" data-parsley-required="true" value="<?php echo date('Y-m-d', $commande['cmd_devis_dateSouhait']) ?>"/>
                            </div>
                        </div>
                        
                        <div class="form-group row mb-3">
                            <label class="form-label col-form-label col-lg-4">Date de livraison prévue* :</label>
                            <div class="col-lg-4">
                                <input type="date" class="form-control"  name="dateP" data-parsley-required="true" value="<?= date('Y-m-d', $commande['cmd_devis_dateout']) ?>"/>
                            </div>
                        </div>
                                            
                        <div class="row mb-3"> 
                            <div class="col-lg"> 
                                <label class="form-label" for="paHT">Total HT :</label>
                                <input class="form-control" type="text" id="pvHTT" name="totalHT" data-parsley-required="true" value="<?php echo $commande['cmd_devis_prixHT'] ?>" readonly/>
                            </div>

                            <div class="col-lg"> 
                                <label class="form-label" for="marge">Total TTC :</label>
                                <input class="form-control" type="text" id="pvTTCT" name="totalTTC" data-parsley-required="true" value="<?php echo $commande['cmd_devis_prixventettc'] ?>"readonly/>
                            </div>

                            <div class="col-lg"> 
                                <label class="form-label" for="pvHT">Total marge :</label>
                                <input class="form-control" type="text" id="margeT" name="totalMarge" data-parsley-required="true" readonly value="<?php echo $commande['cmd_devis_margeT'] ?>"/>
                            </div>  
                        </div> 
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label form-label">&nbsp;</label>
                            <div class="col-lg-8">
                                <button type="submit" class="btn btn-plrimary">Modifier</button>
                            </div>
                        </div>
                    </div>
                </form>
                    </div>
                    <!-- END panel-body -->
                </div>
                <!-- END panel -->
            </div>
            <!-- END col-6 -->
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
<script>
        var TVA = <?php echo TVA; ?>;
        function calculerenplus(i) {
            var type = <?php echo $commande['type_cmd_devis'] ?>;
            //console.log(type);
            var paHTs = "paHT" + i;
            var marges = "marge" + i;
            var pvHTs = "pvHT" + i;
            var pvTTCs = "pvTTC" + i;
            var margeM = "margeM" + i;
            var quantite_recues = "quantite_recues" + i;
            var quantite_demandees = "quantite_demandees" + i;
            
            var multiplier = 100;

            var paHT = Number(document.getElementById(paHTs).value);
            var marge = Number(document.getElementById(marges).value);

            var quantite_demandee = Number(document.getElementById(quantite_demandees).value);

            if(type == 1){
            var quantite_recue = Number(document.getElementById(quantite_recues).value);
                if(quantite_recue > quantite_demandee){
                    alert("La quantité reçue ne peut pas être supérieure à la quantité demandée.");
                    return;
                }
            }

            // Validation des prix avec deux chiffres après la virgule ou le point
            if (!/^(\d+(,\d{1,2})?|\d+(\.\d{1,2})?)$/.test(paHT) || !/^(\d+(,\d{1,2})?|\d+(\.\d{1,2})?)$/.test(marge)) {
                alert("Les prix doivent être des nombres avec au plus deux chiffres après le point, sans caractères spéciaux.");
                return;
            }

            // Empêcher les nombres négatifs
            if (parseFloat(paHT) < 0 || parseFloat(marge) < 0) {
                alert("Les prix ne peuvent pas être négatifs.");
                return;
            }

            // Empêcher les marges supérieures à 100%
            if (parseFloat(marge) > 100) {
                alert("La marge ne peut pas être supérieure à 100%.");
                return;
            }

            if(type == 1){ //si c'est une commande on multiplie par la quantité reçue
                var pvHT = Number(paHT + (paHT * (marge / 100)));
                pvHT = Math.round(pvHT * quantite_recue * multiplier) / multiplier;
                document.getElementById(pvHTs).value = pvHT;

                var pvTTC = Number(pvHT + (pvHT * TVA));
                pvTTC = Math.round(pvTTC * multiplier) / multiplier;
                document.getElementById(pvTTCs).value = pvTTC ;

                var margeMt = Number(pvHT - paHT * quantite_recue);
                margeMt = Math.round(margeMt * multiplier) / multiplier;
                document.getElementById(margeM).value = margeMt;
            }else{ //sinon (devis) on multiplie par la quantité demandée

                if( quantite_demandee < 1){
                    alert("La quantité reçue ne peut pas être inférieure à 0");
                    return;
                }

                var pvHT = Number(paHT + (paHT * (marge / 100)));
                pvHT = Math.round(pvHT * quantite_demandee * multiplier) / multiplier;
                document.getElementById(pvHTs).value = pvHT;

                var pvTTC = Number(pvHT + (pvHT * TVA));
                pvTTC = Math.round(pvTTC * multiplier) / multiplier;
                document.getElementById(pvTTCs).value = pvTTC;

                var margeMt = Number(pvHT - paHT * quantite_demandee);
                margeMt = Math.round(margeMt * multiplier) / multiplier;
                document.getElementById(margeM).value = margeMt;
            }

            if (document.getElementById(pvHTs).value > 10) {
                var arrondiBas = 10 * Math.floor(document.getElementById(pvHTs).value / 10);
            } else {
                var arrondiBas = Math.floor(document.getElementById(pvHTs).value);
            }

            var html = '<div class="form-group row mt-4" id="choixArrondi' + i + '">';
            html += '<label class="form-label col-form-label col-lg-4" for="choixArrondiSelect">Choisir l\'arrondi du PvHT</label>';
            html += '<div class="col-lg-4">';
            html += '<select class="form-select" id="choixArrondiSelect' + i + '" name="arrondi" onchange="choixArrondiPlus(' + i + ')" required>';
            html += '<option value="' + document.getElementById(pvHTs).value + '">' + document.getElementById(pvHTs).value + '</option>';
            html += '<option value="' + (arrondiBas + 5) + '">' + (arrondiBas + 5) + '</option>';
            html += '<option value="' + (arrondiBas + 10) + '">' + (arrondiBas + 10) + '</option>';
            html += '<option value="' + (arrondiBas + 15) + '">' + (arrondiBas + 15) + '</option>';
            html += '<option value="' + (arrondiBas + 20) + '">' + (arrondiBas + 20) + '</option>';
            html += '</select>';
            html += '</div>';
            html += '</div>';

            document.getElementById('choixArrondi' + i).innerHTML = html;

            recalculerTotaux(i);
        }

        function choixArrondiPlus(i){
            var type = <?php echo $commande['type_cmd_devis'] ?>;

            if(type == 1){ //si c'est une commande : on récupère la valeur de quantité reçue
                var quantite_recues = "quantite_recues" + i;
                var quantite_recue = Number(document.getElementById(quantite_recues).value);

                var multiplier = 100;

                var choix = document.getElementById("choixArrondiSelect" + i).value;
                choix = Number(choix);
                document.getElementById("pvHT" + i).value = choix;
                
                var pvTTC = Number(choix + (choix * TVA));
                pvTTC = Number(pvTTC);
                document.getElementById("pvTTC" + i).value = pvTTC;

                var paHT = document.getElementById("paHT" + i).value;

                var margeM = Number(choix - paHT * quantite_recue);
                document.getElementById("margeM" + i).value = margeM;

            }else{ //si c'est un devis : on a juste la quantité demandée
                var quantite_demandees = "quantite_demandees" + i;
                var quantite_demandee = Number(document.getElementById(quantite_demandees).value);

                var multiplier = 100;
                var choix = document.getElementById("choixArrondiSelect" + i).value;
                choix = Number(choix);
                document.getElementById("pvHT" + i).value = choix;
                
                var pvTTC = Number(choix + (choix * TVA));
                pvTTC = Number(pvTTC);
                document.getElementById("pvTTC" + i).value = pvTTC;

                var paHT = document.getElementById("paHT" + i).value;

                var margeM = Number(choix - paHT * quantite_demandee);
                document.getElementById("margeM" + i).value = margeM;
            }

            recalculerTotaux(i);
        }

        function choixArrondi(i){
            var quantite_demandees = "quantite_demandees" + i;
            var quantite_demandee = Number(document.getElementById(quantite_demandees).value);

            var multiplier = 100;
            var choix = document.getElementById("choixArrondiSelect" + i).value;
            choix = Number(choix);
            document.getElementById("pvHT" + i).value = choix;
            
            var pvTTC = Number(choix + (choix * TVA));
            pvTTC = Number(pvTTC);
            document.getElementById("pvTTC" + i).value = pvTTC;

            var paHT = document.getElementById("paHT" + i).value;

            var margeM = Number(choix - paHT * quantite_demandee);
            document.getElementById("margeM" + i).value = margeM;
            recalculerTotaux(i);
        }

        function recalculerTotaux(i) {
            
            var multiplier = 100;
            var totalMarge = 0;
            var totalHT = 0;
            var totalTTC = 0;
    
            for (j = 1; j <= i; j++) {
                //CALCULE TOTAL DE LA MARGE
                console.log("je suis dans la boucle");
                var margeTotal = "margeM" + j;
                var sommeHT = document.getElementById(margeTotal);
                if (sommeHT && !isNaN(parseFloat(sommeHT.value))) {
                    totalMarge += parseFloat(sommeHT.value);
                }

                //CALCULE TOTAL DE LA HT
                var horstaxeTotal = "pvHT" + j;
                var horstaxeSomme = document.getElementById(horstaxeTotal);
                if (horstaxeSomme && !isNaN(parseFloat(horstaxeSomme.value))) {
                    totalHT += parseFloat(horstaxeSomme.value);
                }

                //CALCULE TOTAL DE LA TTC
                var ttcTotal = "pvTTC" + j;
                var ttcSomme = document.getElementById(ttcTotal);
                if (ttcSomme && !isNaN(parseFloat(ttcSomme.value))) {
                    totalTTC += parseFloat(ttcSomme.value);
                }
                //simplification au centième près
                totalMarge = Math.round(totalMarge * multiplier) / multiplier;
                totalHT = Math.round(totalHT * multiplier) / multiplier;
                totalTTC = Math.round(totalTTC * multiplier) / multiplier;
            }

            console.log("totalMarge = "+totalMarge);
            console.log("totalHT = "+totalHT);
            console.log("totalTTC = "+totalTTC);

            document.getElementById("margeT").value = totalMarge;
            document.getElementById("pvHTT").value = totalHT;
            document.getElementById("pvTTCT").value = totalTTC;
            console.log("La fonction recalculerTotaux a fini d'être exécutée."); 
        }

        function ajouterMateriel(){
            var i = document.querySelectorAll('.materiel').length;        
            i = i+2;
            console.log(i);

            var html = '<div id="materiel" class="materiel card mb-3">';
            html += '<div class="row mb-3"> ';
            html += '<div class="col-lg"> ';
            html += '<label class="col-form-label form-label" for="reference">Reference * :</label>';
            html += '<input class="form-control" type="text" id="reference' + i + '" name="dynamic['+i+'][]" data-parsley-required="true" />';
            html += '</div>';
            html += '<div class="col-lg"> ';
            html += '<label class="col-form-label form-label" for="designation">Désignation * :</label>';
            html += '<input class="form-control" type="text" id="designation' + i + '" name="dynamic['+i+'][]" data-parsley-required="true" />';
            html += '</div>';
            html += '<div class="col-lg">';
            html += '<label class="col-form-label form-label">Fournisseur</label>';
            html += '<select class="form-select" id="select-required" name="dynamic['+i+'][]" data-parsley-required="true">';
            <?php foreach($allsuppliers as $supplier) { ?>
                html += '<option value="<?php echo $supplier->fournisseurs_id ?>"><?php echo $supplier->fournisseurs_name ?></option>';
            <?php } ?>
            html += '</select>';
            html += '</div>';
            html += '</div> ';
            html += '<div class="row mb-3"> ';
            html += '<div class="col-lg"> ';
            html += '<label class="form-label" for="paHT">Pa HT* :</label>';
            html += '<input class="form-control" type="text" id="paHT' + i + '" name="dynamic['+i+'][]" data-parsley-required="true" onblur="calcul(' + i + ')"/>';
            html += '</div>';
            html += '<div class="col-lg"> ';
            html += '<label class="form-label" for="marge">Marge* :</label>';
            html += '<input class="form-control" type="text" id="marge' + i + '" name="dynamic['+i+'][]" data-parsley-required="true" onblur="calcul(' + i + ')"/>';
            html += '</div>';
            html += '<div class="col-lg"> ';
            html += '<label class="form-label" for="pvHT">Pv HT* :</label>';
            html += '<input class="form-control" type="text" id="pvHT' + i + '" name="dynamic['+i+'][]" data-parsley-required="true" readonly />';
            html += '</div>';
            html += '<div class="col-lg"> ';
            html += '<label class="form-label" for="pvTTC">Pv TTC* :</label>';
            html += '<input class="form-control" type="text" id="pvTTC' + i + '" name="dynamic['+i+'][]" data-parsley-required="true" readonly/>';
            html += '</div>';
            html += '<div class="col-lg">';
            html += '<label class="form-label" for="margeM">Montant marge :</label>';
            html += '<input class="form-control" type="text" id="margeM' + i + '" name="dynamic['+i+'][]" readonly/>';
            html += '</div>';
            html += '</div> ';
            html += '<div class="row mb-3">';
            html += '<div class="col-lg-4"> ';
            html += '<label class="form-label" for="quantiteDemandee">Quantité demandée* :</label>';
            html += '<input class="form-control" type="number" id="quantite_demandees' + i +'" value="1" name="dynamic['+i+'][]" data-parsley-required="true" onblur="calcul('+i+')" value="1"/>';
            html += '</div>';

            if (<?=$commande['type_cmd_devis']?> == 1){
                html += '<div class="col-lg-4">';
                html += '<label class="form-label" for="quantiteRecue">Quantité reçue* :</label>';
                html += '<input class="form-control" type="number" id="quantiteRecue' + i + '" value="0" name="dynamic['+i+'][]" readonly/>';
                html += '</div>';
            }
            html += '<div id="choixArrondi' + i + '"></div>';
            html += '<div class="row mb-3 mt-3">';
            html += '<div class="col-lg-2">';
            html += '<label class="form-label">Statut produit* :</label>';
            html += '</div>';
            html += '<div class="col-lg-4"> ';
            html += '<select class="form-select" id="select-required" name="dynamic['+i+'][]" data-parsley-required="true">';
            html += '<option value="commande">Commandé</option>';
            html += '<option value="recu">Reçu</option>';
            html += '</select>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            document.getElementById('materiel').insertAdjacentHTML('beforeend', html);
        }

        function calcul(i) {
            var paHTs = "paHT" + i;
            var marges = "marge" + i;
            var pvHTs = "pvHT" + i;
            var pvTTCs = "pvTTC" + i;
            var margeM = "margeM" + i;
            var quantite_demandees = "quantite_demandees" + i;
            
            var multiplier = 100;

            var paHT = Number(document.getElementById(paHTs).value);
            //console.log("paHT = " + paHT);

            var marge = Number(document.getElementById(marges).value);
            //console.log("marge = " + marge);

            var quantiteDemandee = Number(document.getElementById(quantite_demandees).value);

            // Validation de la quantité demandée : elle doit etre supérieure à 0
            if (quantiteDemandee < 1) {
                alert("La quantité demandée doit être supérieure à 0.");
                return;
            }

            // Validation des prix avec deux chiffres après la virgule ou le point
            if (!/^(\d+(,\d{1,2})?|\d+(\.\d{1,2})?)$/.test(paHT) || !/^(\d+(,\d{1,2})?|\d+(\.\d{1,2})?)$/.test(marge)) {
                alert("Les prix doivent être des nombres avec au plus deux chiffres après le point, sans caractères spéciaux.");
                return;
            }

            // Empêcher les nombres négatifs
            if (parseFloat(paHT) < 0 || parseFloat(marge) < 0) {
                alert("Les prix ne peuvent pas être négatifs.");
                return;
            }

            // Empêcher les marges supérieures à 100%
            if (parseFloat(marge) > 100) {
                alert("La marge ne peut pas être supérieure à 100%.");
                return;
            }

            var pvHT = Number(paHT + (paHT * (marge / 100)));
            pvHT = Math.round(pvHT * quantiteDemandee * multiplier) / multiplier;
            document.getElementById(pvHTs).value = pvHT;

            var pvTTC = Number(pvHT + (pvHT * TVA));
            pvTTC = Math.round(pvTTC * multiplier) / multiplier;
            document.getElementById(pvTTCs).value = pvTTC;

            var margeMt = Number(pvHT - paHT * quantiteDemandee);
            margeMt = Math.round(margeMt  * multiplier) / multiplier;
            document.getElementById(margeM).value = margeMt;

            if (document.getElementById(pvHTs).value > 10) {
                var arrondiBas = 10 * Math.floor(document.getElementById(pvHTs).value / 10);
            } else {
                var arrondiBas = Math.floor(document.getElementById(pvHTs).value);
            }
            var html = '<div class="form-group row mt-4" id="choixArrondi' + i + '">';
            html += '<label class="form-label col-form-label col-lg-4" for="choixArrondiSelect">Choisir l\'arrondi du PvHT</label>';
            html += '<div class="col-lg-4">';
            html += '<select class="form-select" id="choixArrondiSelect' + i + '" name="arrondi" onchange="choixArrondi(' + i + ')" required>';
            html += '<option value="' + document.getElementById(pvHTs).value + '">' + document.getElementById(pvHTs).value + '</option>';
            html += '<option value="' + (arrondiBas + 5) + '">' + (arrondiBas + 5) + '</option>';
            html += '<option value="' + (arrondiBas + 10) + '">' + (arrondiBas + 10) + '</option>';
            html += '<option value="' + (arrondiBas + 15) + '">' + (arrondiBas + 15) + '</option>';
            html += '<option value="' + (arrondiBas + 20) + '">' + (arrondiBas + 20) + '</option>';
            html += '</select>';
            html += '</div>';
            html += '</div>';

            document.getElementById('choixArrondi' + i).innerHTML = html;
            recalculerTotaux(i);

        }

        function supprimerMateriel(){
            var materiels = document.querySelectorAll('.materiel');
            if (materiels.length > 0) {
                materiels[materiels.length - 1].remove();
                recalculerTotaux(materiels.length - 1);
            }else{
                alert("Vous ne pouvez pas supprimer le dernier produit.");
            }
        }

        function ajouterPhoto() {
            var divPhotos = document.getElementById('photos');
            var nouveauChamp = document.createElement('div');
            nouveauChamp.className = 'form-group row mb-3 mt-3';
            nouveauChamp.innerHTML = '<div class="col-lg-8"><input class="form-control" type="file" id="picture" accept="image/* , .pdf"capture="environment" name="photos[]" multiple/></div>';
            divPhotos.appendChild(nouveauChamp);
        }

        // Ouvre la boîte modale et affiche l'image cliquée
        function openModal(src) {
            document.getElementById('myModal').style.display = 'block';
            document.getElementById('modalImage').src = src;
        }

        // Ferme la boîte modale
        function closeModal() {
            document.getElementById('myModal').style.display = 'none';
        }

</script>


<!-- ================== BEGIN core-js ================== -->
<script src="assets/js/vendor.min.js"></script>
<script src="assets/js/app.min.js"></script>
<!-- ================== END core-js ================== -->

<!-- ================== BEGIN page-js ================== -->
<script src="assets/plugins/parsleyjs/dist/parsley.min.js"></script>
<script src="assets/plugins/moment/min/moment.min.js"></script>
<script src="assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="assets/plugins/select2/dist/js/select2.min.js"></script>
<script src="assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
<script src="assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
<script src="assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js"></script>
<script src="assets/plugins/jquery.maskedinput/src/jquery.maskedinput.js"></script>
<script src="assets/plugins/jquery-migrate/dist/jquery-migrate.min.js"></script>
<script src="assets/plugins/tag-it/js/tag-it.min.js"></script>
<script src="assets/plugins/clipboard/dist/clipboard.min.js"></script>
<script src="assets/plugins/spectrum-colorpicker2/dist/spectrum.min.js"></script>
<script src="assets/plugins/select-picker/dist/picker.min.js"></script>
<script src="assets/js/demo/form-plugins.demo.js"></script>
<script src="assets/plugins/@highlightjs/cdn-assets/highlight.min.js"></script>
<script src="assets/js/demo/render.highlight.js"></script>
<!-- ================== END page-js ================== -->
</body>
<?php else : ?>
    <?php require_once('login.php'); ?>
<?php endif; ?>
</html>