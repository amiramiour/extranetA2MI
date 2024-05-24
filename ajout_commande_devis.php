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
    <?php require_once('includes/_requests.php'); ?>
    <?php $id=$_GET['id']; ?>

    <!-- BEGIN #content -->
    <div id="content" class="app-content">
        <!-- BEGIN breadcrumb -->
        <ol class="breadcrumb float-xl-end">
            <li class="breadcrumb-item"><a href="javascript:;">Affiche</a></li>
            <li class="breadcrumb-item active">Formulaire</li>
        </ol>
        <!-- END breadcrumb -->
        <!-- BEGIN page-header -->
        <h1 class="page-header">Formulaire de création <small>Commande / Devis</small></h1>

        <!-- END page-header -->
        <!-- BEGIN row -->
        <div class="row">
            <!-- BEGIN col-6 -->
            <div class="col-xl-12">
                <!-- BEGIN panel -->
                <div class="panel panel-inverse" data-sortable-id="form-validation-1">
                <form class="form-horizontal" data-parsley-validate="true" name="demo-form" action="inc_extranet/traitement_ajout_cmd_devis.php?id=<?=$id?>" method="post" enctype="multipart/form-data">

                    <!-- BEGIN panel-heading -->
                    <div class="panel-heading">
                        <div class="col-lg-8">
                            <select class="form-select" id="select-required" name="type" data-parsley-required="true" onchange="afficherChampsSupplementaires()">
                                <option value="1">Commande</option>
                                <option value="2">Devis</option>
                            </select>
                        </div>                    
                    </div>
                    <!-- END panel-heading -->
                    <!-- BEGIN panel-body -->
                    <div class="panel-body">
                            <div id="materiels">
                                <div id="materiel" class="mb-4">
                                    <div class="row mb-3"> 
                                        <div class="col-lg"> 
                                            <label class="col-form-label form-label" for="reference">Reference * :</label>
                                            <input class="form-control" type="text" id="reference" name="dynamic['0'][]" data-parsley-required="true" />
                                        </div>
                                        
                                        <div class="col-lg"> 
                                            <label class="col-form-label form-label" for="designation">Désignation * :</label>
                                            <input class="form-control" type="text" id="designation" name="dynamic['0'][]" data-parsley-required="true" />
                                        </div>

                                        <div class="col-lg">
                                            <label class="col-form-label form-label">Fournisseur</label>
                                            <select class="form-select" id="select-required" name="dynamic['0'][]" data-parsley-required="true">
                                                <?php foreach($allsuppliers as $supplier) { ?>
                                                    <option value="<?php echo $supplier->fournisseurs_id ?>"><?php echo $supplier->fournisseurs_name ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div> 

                                    <div class="row mb-3"> 
                                        <div class="col-lg"> 
                                            <label class="form-label" for="paHT">Pa HT* :</label>
                                            <input class="form-control" type="text" id="paHT" name="dynamic['0'][]" data-parsley-required="true" onblur="calcul()"/>
                                        </div>

                                        <div class="col-lg"> 
                                            <label class="form-label" for="marge">Marge* :</label>
                                            <input class="form-control" type="text" id="marge" name="dynamic['0'][]" data-parsley-required="true" onblur="calcul()"/>
                                        </div>

                                        <div class="col-lg"> 
                                            <label class="form-label" for="pvHT">Pv HT* :</label>
                                            <input class="form-control" type="text" id="pvHT" name="dynamic['0'][]" data-parsley-required="true" readonly />
                                        </div>

                                        <div class="col-lg"> 
                                            <label class="form-label" for="pvTTC">Pv TTC* :</label>
                                            <input class="form-control" type="text" id="pvTTC" name="dynamic['0'][]" data-parsley-required="true" readonly/>
                                        </div>

                                        <div class="col-lg">
                                            <label class="form-label" for="margeM">Montant marge :</label>
                                            <input class="form-control" type="text" id="margeM" name="dynamic['0'][]" readonly/>
                                        </div>
                                    </div> 

                                    <div class="row mb-3">
                                        <div class="col-lg-4"> 
                                            <label class="form-label" for="quantiteDemandee">Quantité demandée* :</label>
                                            <input class="form-control" type="number" id="quantiteDemandee" value='1' name="dynamic['0'][]" data-parsley-required="true" onblur="calcul()"/>
                                        </div>

                                        <!--<div class="col-lg-4">
                                            <label class="form-label" for="quantiteRecue">Quantité reçue* :</label>
                                            <input class="form-control" type="number" id="quantiteRecue" value="0" name="dynamic['0'][]" readonly/>
                                        </div>-->
                                        
                                        <div id="lesChoix">
                                            <div id="choixArrondi">
                                        </div>
                                    </div>

                                    <div class="row mb-3 mt-3">
                                        <div class="col-lg-2">
                                            <label class="form-label">Statut produit* :</label>
                                        </div>

                                        <div class="col-lg-4"> 
                                            <select class="form-select" id="select-required" name="dynamic['0'][]" data-parsley-required="true">
                                                <option value="commande">Commandé</option>
                                                <option value="recu">Reçu</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 mt-3">
                                <div class="col-lg-4">
                                    <button type="button" class="btn btn-danger" onclick="supprimerMateriel()">Enlever un matériel</button>
                                </div>
                                <div class="col-lg-4">
                                    <button type="button" class="btn btn-success" onclick="ajouterMateriel()">Ajouter un matériel</button>
                                </div>
                            </div>
                                
                            <div id="champsDevis" style="display: none;">
                                <div class="form-group row mb-3">
                                    <label class="col-lg-4 col-form-label form-label" for="commentaire">Commentaire* :</label>
                                    <div class="col-lg-8">
                                        <textarea class="form-control" id="commentaire" name="commentaire" rows="4" data-parsley-minlength="20" data-parsley-maxlength="100"></textarea>
                                    </div>
                                </div>

                                <div class="form-group row mb-3" id="photos">
                                    <label class="form-label col-form-label col-lg-4" for="picture">Prendre une photo :</label>
                                    <div class="col-lg-8">
                                        <input class="form-control" type="file" id="picture" accept="image/* , .pdf"capture="environment" name="photos[]" multiple/>
                                    </div>
                                </div>

                                <div class="form-group mb-3 mt-3">
                                    <div class="col-lg-8">
                                        <button type="button" class="btn btn-success" onclick="ajouterPhoto()">Ajouter une photo</button>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3"> 
                                <div class="col-lg"> 
                                    <label class="col-form-label form-label" for="reference">Reference * :</label>
                                    <input class="form-control" type="text" id="reference" name="nomC" data-parsley-required="true" />
                                </div>
                                        
                                <div class="col-lg"> 
                                    <label class="col-form-label form-label" for="designation">Désignation * :</label>
                                    <input class="form-control" type="text" id="designation" name="designation" data-parsley-required="true" />
                                </div>

                                <div class="col-lg">
                                    <label class="col-form-label form-label">Statut* :</label>
                                    <select class="form-select" id="etatC" name="etatC" data-parsley-required="true"></select>
                                </div>
                            </div> 
                            
                            <div class="form-group row mb-3">
                                <label class="form-label col-form-label col-lg-4">Date de livraison souhaitée* :</label>
                                <div class="col-lg-4">
                                    <input type="date" class="form-control"  name="dateS" data-parsley-required="true"/>
                                </div>
                            </div>
                            
                            <div class="form-group row mb-3">
                                <label class="form-label col-form-label col-lg-4">Date de livraison prévue* :</label>
                                <div class="col-lg-4">
                                    <input type="date" class="form-control"  name="dateP" data-parsley-required="true"/>
                                </div>
                            </div>
                                                
                            <div class="row mb-3"> 
                                <div class="col-lg"> 
                                    <label class="form-label" for="paHT">Total HT :</label>
                                    <input class="form-control" type="text" id="pHTT" name="totalHT" data-parsley-required="true" readonly/>
                                </div>

                                <div class="col-lg"> 
                                    <label class="form-label" for="marge">Total TTC :</label>
                                    <input class="form-control" type="text" id="pvTTCT" name="totalTTC" data-parsley-required="true" readonly/>
                                </div>

                                <div class="col-lg"> 
                                    <label class="form-label" for="pvHT">Total marge :</label>
                                    <input class="form-control" type="text" id="margeT" name="totalMarge" data-parsley-required="true" readonly />
                                </div>  
                            </div> 
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label form-label">&nbsp;</label>
                                <div class="col-lg-8">
                                    <button type="submit" class="btn btn-primary">Créer</button>
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
        function afficherChampsSupplementaires() {
            var type = document.querySelector('select[name="type"]').value;
            var champsDevis = document.getElementById('champsDevis');
            var commentaire = document.getElementById('commentaire');
            //var quantiteRecue = document.getElementById('quantiteRecue');
            //var quantiteRecueLabel = document.querySelector('label[for="quantiteRecue"]');
            var etatC = document.getElementById('etatC');

            // Réinitialiser les champs supplémentaires
            commentaire.value = '';
            //quantiteRecue.value = '';
            etatC.innerHTML = '';

            if (type === '2') {
                champsDevis.style.display = 'block';
                commentaire.required = true;
                //quantiteRecue.style.display = 'none';
                //quantiteRecue.required = false;
                //quantiteRecueLabel.style.display = 'none';

                // Ajouter les options pour les états d'un devis
                <?php foreach($etats as $etat) { ?>
                    if(<?php echo $etat->id_etat_cmd_devis ?> == 2 || <?php echo $etat->id_etat_cmd_devis ?> == 6) {
                        var option = document.createElement('option');
                        option.value = "<?php echo $etat->id_etat_cmd_devis ?>";
                        option.text = "<?php echo $etat->cmd_devis_etat ?>";
                        etatC.add(option);
                    }
                <?php } ?>
                
            } else { 
                champsDevis.style.display = 'none';
                commentaire.required = false;
                //quantiteRecue.style.display = 'block';
                //quantiteRecue.required = true; 
                //quantiteRecueLabel.style.display = 'block';  

                // Ajouter les options pour les états d'une commande
                <?php foreach($etats as $etat) { ?>
                    var option = document.createElement('option');
                    option.value = "<?php echo $etat->id_etat_cmd_devis ?>";
                    option.text = "<?php echo $etat->cmd_devis_etat ?>";
                    etatC.add(option);
                <?php } ?>
            }
        }

        document.querySelector('select[name="type"]').addEventListener('change', afficherChampsSupplementaires);
        afficherChampsSupplementaires();

        var TVA = <?php echo TVA; ?>;
        function calcul(){
            var i = document.querySelectorAll('.materiel').length;
            var paHT = Number(document.getElementById("paHT").value);
            var marge = Number(document.getElementById("marge").value);

            var quantiteDemandee = Number(document.getElementById("quantiteDemandee").value);

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
            var multiplier = 100;
    
            var pvHT = Number(paHT+(paHT*(marge/100)));
            pvTTC = Math.round(pvTTC * multiplier) / multiplier;
            document.getElementById("pvHT").value = (pvHT * quantiteDemandee).toFixed(2);
    
            var pvTTC = Number(pvHT+(pvHT * TVA));
            pvTTC = Math.round(pvTTC * multiplier) / multiplier;
            document.getElementById("pvTTC").value = (pvTTC * quantiteDemandee).toFixed(2);
    
            var margeM = Number(pvHT-paHT);
            margeM = Math.round(margeM * multiplier) / multiplier;
            document.getElementById("margeM").value = (margeM * quantiteDemandee).toFixed(2);  
    
            var totalMarge = margeM * quantiteDemandee;
            var totalHT = pvHT * quantiteDemandee;
            var totalTTC = pvTTC * quantiteDemandee;
    
            //simplification au centième près
    
            var totalMarge = Math.round(totalMarge * multiplier) / multiplier;
    
            //simplification au centième près
            var totalHT = Math.round(totalHT * multiplier) / multiplier;
    
            //simplification au centième près
            var totalTTC = Math.round(totalTTC * multiplier) / multiplier;
            document.getElementById('margeT').value=totalMarge;
            document.getElementById("pHTT").value=totalHT;
            document.getElementById("pvTTCT").value=totalTTC;

            if(document.getElementById("pvHT").value>10) {
                var arrondiBas = 10*Math.floor(document.getElementById("pvHT").value/10);
            }
            else {
                var arrondiBas = Math.floor(document.getElementById("pvHT").value);
            }
            var html = '<div class="form-group row mt-4">';
            html += '<label class="form-label col-form-label col-lg-4" for="choixArrondiSelect">Choisir l\'arrondi du PvHT</label>';
            html += '<div class="col-lg-4">';
            html += '<select class="form-select" id="choixArrondiSelect" name="arrondi" onchange="choixArrondi()" required>';
            html += '<option value="' + document.getElementById("pvHT").value + '">' + document.getElementById("pvHT").value + '</option>';
            html += '<option value="' + (arrondiBas + 5) + '">' + (arrondiBas + 5) + '</option>';
            html += '<option value="' + (arrondiBas + 10) + '">' + (arrondiBas + 10) + '</option>';
            html += '<option value="' + (arrondiBas + 15) + '">' + (arrondiBas + 15) + '</option>';
            html += '<option value="' + (arrondiBas + 20) + '">' + (arrondiBas + 20) + '</option>';
            html += '</select>';
            html += '</div>';
            html += '</div>';

            document.getElementById('lesChoix').innerHTML = html;
            recalculerTotaux(i);
        }
        function choixArrondi() {
            var i = document.querySelectorAll('.materiel').length;
            var multiplier = 100;
            var choix = document.getElementById("choixArrondiSelect").value;
            choix = Number(choix);
            document.getElementById("pvHT").value = choix;
            var pvTTC = Number(choix+(choix * TVA)); 
            pvTTC = Number(pvTTC);
            document.getElementById("pvTTC").value = pvTTC;
            var paHT = document.getElementById("paHT").value;    
            var margeM = Number(choix-paHT);
            document.getElementById("margeM").value = margeM;
            var totalMarge = document.getElementById("margeM").value;
            var totalMarge = Math.round(totalMarge * multiplier) / multiplier;
            document.getElementById("margeT").value=totalMarge;
            var totalHT = document.getElementById("pvHT").value;
            var totalHT = Math.round(totalHT * multiplier) / multiplier;
            document.getElementById("pHTT").value=totalHT;
            var totalTTC = document.getElementById("pvTTC").value;
            var totalTTC = Math.round(totalTTC * multiplier) / multiplier;
            document.getElementById("pvTTCT").value=totalTTC;
            recalculerTotaux(i);
        }

        function ajouterMateriel(){
            var i = document.querySelectorAll('.materiel').length;

            var type = document.querySelector('select[name="type"]').value;
            console.log(type);
        
            i++;

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
            html += '<input class="form-control" type="text" id="paHTs' + i + '" name="dynamic['+i+'][]" data-parsley-required="true" onblur="calculerenplus(' + i + ')"/>';
            html += '</div>';
            html += '<div class="col-lg"> ';
            html += '<label class="form-label" for="marge">Marge* :</label>';
            html += '<input class="form-control" type="text" id="marges' + i + '" name="dynamic['+i+'][]" data-parsley-required="true" onblur="calculerenplus(' + i + ')"/>';
            html += '</div>';
            html += '<div class="col-lg"> ';
            html += '<label class="form-label" for="pvHT">Pv HT* :</label>';
            html += '<input class="form-control" type="text" id="pvHTs' + i + '" name="dynamic['+i+'][]" data-parsley-required="true" readonly />';
            html += '</div>';
            html += '<div class="col-lg"> ';
            html += '<label class="form-label" for="pvTTC">Pv TTC* :</label>';
            html += '<input class="form-control" type="text" id="pvTTCs' + i + '" name="dynamic['+i+'][]" data-parsley-required="true" readonly/>';
            html += '</div>';
            html += '<div class="col-lg">';
            html += '<label class="form-label" for="margeM">Montant marge :</label>';
            html += '<input class="form-control" type="text" id="margeM' + i + '" name="dynamic['+i+'][]" readonly/>';
            html += '</div>';
            html += '</div> ';
            html += '<div class="row mb-3">';
            html += '<div class="col-lg-4"> ';
            html += '<label class="form-label" for="quantiteDemandee">Quantité demandée* :</label>';
            html += '<input class="form-control" type="number" id="quantiteDemandees' + i +'" value="1" name="dynamic['+i+'][]" data-parsley-required="true" onblur="calculerenplus(' + i + ')"/>';
            html += '</div>';

            /*if(type === '1'){
                html += '<div class="col-lg-4">';
                html += '<label class="form-label" for="quantiteRecue">Quantité reçue* :</label>';
                html += '<input class="form-control" type="number" id="quantiteRecue' + i + '" value="0" name="dynamic['+i+'][]" readonly/>';
                html += '</div>';
            }*/
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

        function calculerenplus(i) {
            var paHTs = "paHTs" + i;
            var marges = "marges" + i;
            var pvHTs = "pvHTs" + i;
            var pvTTCs = "pvTTCs" + i;
            var margeM = "margeM" + i;
            var quantiteDemandees = "quantiteDemandees" + i;
            var multiplier = 100;
            /**var totalMarge = document.getElementById("margeT").value;
            var totalMarge = Math.round(totalMarge * multiplier) / multiplier;  
        
            var totalHT = document.getElementById("pHTT").value;
            var totalHT = Math.round(totalHT * multiplier) / multiplier;
        
            var totalTTC = document.getElementById("pvTTCT").value;
            var totalTTC = Math.round(totalTTC * multiplier) / multiplier;**/
            var paHT = Number(document.getElementById(paHTs).value);
            var marge = Number(document.getElementById(marges).value);

            var quantiteDemandee = Number(document.getElementById(quantiteDemandees).value);

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
            pvHT = Math.round(pvHT * multiplier) / multiplier;
            document.getElementById(pvHTs).value = (pvHT * quantiteDemandee).toFixed(2);

            var pvTTC = Number(pvHT + (pvHT * TVA));
            pvTTC = Math.round(pvTTC * multiplier) / multiplier;
            document.getElementById(pvTTCs).value = (pvTTC * quantiteDemandee).toFixed(2);

            var margeMt = Number(pvHT - paHT);
            margeMt = Math.round(margeMt * multiplier) / multiplier;
            document.getElementById(margeM).value = (margeMt * quantiteDemandee).toFixed(2);

            if (document.getElementById(pvHTs).value > 10) {
                var arrondiBas = 10 * Math.floor(document.getElementById(pvHTs).value / 10);
            } else {
                var arrondiBas = Math.floor(document.getElementById(pvHTs).value);
            }
            /*var html = '<div id="choixArrondi' + i + '">Choisir l\'arrondi du PvHT ';
            html += '<select name="arrondi" onchange="choixArrondiPlus(' + i + ')" id="choixArrondiSelect' + i + '" required>';
            html += '<option value="' + document.getElementById(pvHTs).value + '">' + document.getElementById(pvHTs).value + '</option>';
            html += '<option value="' + (arrondiBas + 5) + '">' + (arrondiBas + 5) + '</option>';
            html += '<option value="' + (arrondiBas + 10) + '">' + (arrondiBas + 10) + '</option>';
            html += '<option value="' + (arrondiBas + 15) + '">' + (arrondiBas + 15) + '</option>';
            html += '<option value="' + (arrondiBas + 20) + '">' + (arrondiBas + 20) + '</option>';
            html += '</select> €</div>';*/
            var html = '<div class="form-group row mt-4">';
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
            /*totalMarge += margeMt;
            totalHT += pvHT;
            totalTTC += pvTTC;
            document.getElementById('margeT').value = Math.round(totalMarge * multiplier) / multiplier;
            document.getElementById('pHTT').value = Math.round(totalHT * multiplier) / multiplier;
            document.getElementById('pvTTCT').value = Math.round(totalTTC * multiplier) / multiplier;*/
        }

        function choixArrondiPlus(i){
            var multiplier = 100;
            var choix = document.getElementById("choixArrondiSelect" + i).value;
            choix = Number(choix);
            document.getElementById("pvHTs" + i).value = choix;
            
            var pvTTC = Number(choix + (choix * TVA));
            pvTTC = Number(pvTTC);
            document.getElementById("pvTTCs" + i).value = pvTTC;
            var paHT = document.getElementById("paHTs" + i).value;
            var margeM = Number(choix - paHT);
            document.getElementById("margeM" + i).value = margeM;
            /*var totalMarge = document.getElementById("margeM").value;
            var totalMarge = Math.round(totalMarge * multiplier) / multiplier;  
        
            var totalHT = document.getElementById("pvHT").value;
            var totalHT = Math.round(totalHT * multiplier) / multiplier;
        
            var totalTTC = document.getElementById("pvTTC").value;
            var totalTTC = Math.round(totalTTC * multiplier) / multiplier;
            totalMarge += margeM;
            totalHT += paHT;
            totalTTC += pvTTC;
            document.getElementById("margeT").value = totalMarge;
            document.getElementById("pHTT").value = totalHT;
            document.getElementById("pvTTCT").value = totalTTC;*/
            recalculerTotaux(i);
        }

        function recalculerTotaux(i) {
            
            var multiplier = 100;
            if (document.getElementById("margeM") && !isNaN(parseFloat(document.getElementById("margeM").value))) {
                var totalMarge = parseFloat(document.getElementById("margeM").value);
            }else 
            {
                var totalMarge = 0;
            }
            var totalMarge = Math.round(totalMarge * multiplier) / multiplier;
        
            var totalHT = document.getElementById("pvHT").value;
            var totalHT = Math.round(totalHT * multiplier) / multiplier;
        
            var totalTTC = document.getElementById("pvTTC").value;
            var totalTTC = Math.round(totalTTC * multiplier) / multiplier;

            console.log("La fonction recalculerTotaux est en train d'être exécutée.");
    
            for (j = 1; j <= i; j++) {
                //CALCULE TOTAL DE LA MARGE
                var margeTotal = "margeM" + j;
                var sommeHT = document.getElementById(margeTotal);
                if (sommeHT && !isNaN(parseFloat(sommeHT.value))) {
                    totalMarge += parseFloat(sommeHT.value);
                }
                //CALCULE TOTAL DE LA HT
                var horstaxeTotal = "pvHTs" + j;
                var horstaxeSomme = document.getElementById(horstaxeTotal);
                console.log(parseFloat(horstaxeSomme.value));
                if (horstaxeSomme && !isNaN(parseFloat(horstaxeSomme.value))) {
                    totalHT += parseFloat(horstaxeSomme.value);
                }
                //CALCULE TOTAL DE LA TTC
                var ttcTotal = "pvTTCs" + j;
                var ttcSomme = document.getElementById(ttcTotal);
                if (ttcSomme && !isNaN(parseFloat(ttcSomme.value))) {
                    totalTTC += parseFloat(ttcSomme.value);
                }

                //simplification au centième près
                totalMarge = Math.round(totalMarge * multiplier) / multiplier;
                totalHT = Math.round(totalHT * multiplier) / multiplier;
                totalTTC = Math.round(totalTTC * multiplier) / multiplier;
            }
            document.getElementById("margeT").value = totalMarge;
            document.getElementById("pHTT").value = totalHT;
            document.getElementById("pvTTCT").value = totalTTC;
            console.log("La fonction recalculerTotaux a fini d'être exécutée."); 
        }

        function supprimerProduit(){
            var materiels = document.querySelectorAll('.materiel');
            if (materiels.length > 0) {
                materiels[materiels.length - 1].remove();
                recalculerTotaux(materiels.length - 1);
            }else{
                alert("Vous ne pouvez pas supprimer le dernier produit.");
            }
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