<?php
// Inclure le fichier de connexion à la base de données
session_start();
require "connexion/traitement_connexion.php";
function connexionbdd()
{
    global $pdo;
    return $pdo;
}

$db = connexionbdd();

// Vérifier si l'ID du bon d'intervention à modifier est passé dans l'URL
if (isset($_GET['bi_id'])) {
    // Récupérer l'ID du bon d'intervention depuis l'URL
    $bi_id = $_GET['bi_id'];

    // Préparer la requête SQL pour récupérer les données du bon d'intervention
    $query = $db->prepare("SELECT * FROM bi WHERE bi_id = ?");
    $query->execute([$bi_id]);
    $bi_data = $query->fetch(PDO::FETCH_ASSOC);

    // Vérifier si des données ont été trouvées pour l'ID donné
    if ($bi_data) {
        // Récupérer les données à pré-remplir
        $selectedIntervention = $bi_data['bi_intervention']; // Changed from 'selectedIntervention'
        $nbPieces = $bi_data['bi_nbpiece']; // Changed from 'nbPieces'
        $prixUnitaire = $bi_data['bi_prixunit']; // Changed from 'prixUnitaire'

        // Utiliser ces données pour pré-remplir les champs du formulaire
        ?>
        <!-- Votre code HTML existant jusqu'à la section du formulaire -->
        <!-- Par exemple, pour pré-remplir le champ de l'intervention -->
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                // Sélectionner l'option correspondant à l'intervention dans la liste déroulante
                document.getElementById('selectedIntervention').value = "<?php echo $selectedIntervention; ?>";
                // Pré-remplir le nombre de pièces
                document.getElementById('nbpiece_1').value = "<?php echo $nbPieces; ?>";
                // Pré-remplir le prix unitaire
                document.getElementById('prixunit_1').value = "<?php echo $prixUnitaire; ?>";
                // Répéter ce processus pour chaque champ à pré-remplir
            });
        </script>
        <?php
    }
}

$users_id = $_GET['id'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire Bon d'Intervention Client</title>
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
<?php
require_once('includes/_functions.php');
require('connexion/traitement_connexion.php');

if ($_SESSION['role'] == 'admin'):
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
                <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                <li class="breadcrumb-item active">Bons d'Intervention</li>
            </ol>
            <!-- END breadcrumb -->
            <!-- BEGIN page-header -->
            <h1 class="page-header">Liste des Bons d'Intervention <small></small></h1>
            <!-- END page-header -->
            <!-- BEGIN panel -->
            <div class="panel panel-inverse">
                <!-- BEGIN panel-heading -->
                <div class="panel-heading">
                    <h4 class="panel-title">Liste des Bons d'Intervention</h4>
                </div>
                <!-- END panel-heading -->

                <div id="contenu" class="container mt-3">
                    <h1>Formulaire de création d'un Bon d'Intervention client</h1>
                    <?php
                    // Affichage du message de succès
                    if (isset($_SESSION['success_message'])) {
                        echo '<div class="alert alert-success" role="alert">' . $_SESSION['success_message'] . '</div>';
                        unset($_SESSION['success_message']);
                    }
                    // Affichage du message d'erreur
                    if (isset($_SESSION['error_message'])) {
                        echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error_message'] . '</div>';
                        unset($_SESSION['error_message']);
                    }
                    ?>
                    <form method="post" action="inc_extranet/traitement-bi.php?id=<?php echo $users_id; ?>" name="BI">
                        <fieldset>
                            <!-- Bouton Ajouter -->
                            <button type="button" class="btn btn-primary custom-btn" id="btnAjouter"
                                    onclick="ajouterChamp()">Ajouter
                            </button>
                            <legend>BI client <small>* champs obligatoires</small></legend>
                            <div id="interventions" style="flex-direction: column;">
                                <div class="intervention">
                                    <div class="intervention" style="display: flex; align-items: center;">
                                        <label for="selectedIntervention" class="center-text" style="width: 250px;">Intervention
                                            *</label>
                                        <input type="text" id="selectedIntervention" name="selectedIntervention[]"
                                               class="form-control" list="interventionList" required>
                                    </div>
                                    <datalist id="interventionList">
                                        <option value="Forfait intervention sur site PC">
                                        <option value="Forfait intervention sur site MAC">
                                        <option value="Forfait assistance à domicile PC">
                                        <option value="Forfait assistance à domicile MAC">
                                        <option value="Formation à domicile 1H30 PC">
                                        <option value="Formation à domicile 1H30 MAC">
                                        <option value="Formation à domicile 2H PC">
                                        <option value="Formation à domicile 2H MAC">
                                        <option value="Forfait éradication de virus">
                                        <option value="Forfait 1H supplémentaire">
                                        <option value="Forfait 2H supplémentaires">
                                    </datalist>
                                    <div class="intervention" style="display: flex; align-items: center;">
                                        <label for="nbpiece_1" class="center-text" style="margin-right: 30px;">Pièces
                                            *</label>
                                        <input class="field" type="number" name="nb_pieces[]" id="nbpiece_1" size="5"
                                               placeholder="Pièces" pattern="\d+" step="1"
                                               onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                               style="width: 100px; margin-right: 20px;" required min="0"/>
                                        <label for="prixunit_1" class="center-text" style="margin-right: 5px;">Prix
                                            Unitaire HT *</label>
                                        <input class="field prix-unitaire" type="text" name="prixUn[]" id="prixunit_1"
                                               size="9" placeholder="Prix unitaire HT" pattern="[0-9]+([\.,][0-9]+)?"
                                               onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode == 44 || event.charCode == 46"
                                               style="width: 150px;" required/>
                                    </div>
                                    <button type="button" class="btn btn-danger" style="margin-left: 10px;"
                                            onclick="supprimerChamp(this)">X
                                    </button>
                                </div>
                            </div>

                            <input type="checkbox" name="facturer" value="oui"> Facturer<br/>
                            <input type="checkbox" name="garantie" value="oui"> Garantie<br/>
                            <input type="checkbox" name="contrat" value="oui"> Contrat/Pack<br/>
                            <input type="checkbox" name="service_personne" value="oui"> Service à la personne<br/>
                            <input type="checkbox" name="regle" value="oui"> Réglé<br/><br/>

                            <label>Envoie facture *</label
                            ><br/>
                            <input type="checkbox" name="envoie_courrier" value="oui"
                                   onchange="toggleAdresse('adresse')"/> Courrier<br/>
                            <input type="checkbox" name="envoie_mail" value="oui"
                                   onchange="toggleAdresse('adresse_email')"/> Mail<br/>
                            ( Vous pouvez cocher les deux cases ci-dessus pour facture via Courrier ET Mail )<br/><br/>

                            <label>Facturation *</label><br/>
                            <input type="radio" name="facturation" value="immediate" onchange="toggleDateField()"
                                   required> Immédiate<br/>
                            <input type="radio" name="facturation" value="differee" onchange="toggleDateField()">
                            Différée<br/>
                            <input type="date" name="date_differee" id="date_differee" style="display: none;">

                            <label for="date_intervention">Date Intervention *</label>
                            <input type="date" name="date_intervention" id="date_intervention" required><br/><br/>


                            <label>Type de paiement *</label><br/>
                            <input type="radio" name="paiement" value="payplug" required> PayPlug<br/>
                            <input type="radio" name="paiement" value="cb" required> CB<br/>
                            <input type="radio" name="paiement" value="cheque" required> Chèque<br/>
                            <input type="radio" name="paiement" value="espece" required> Espèces<br/>
                            <input type="radio" name="paiement" value="virement" required> Virement<br/><br/>

                            <label for="heure_arrive">Heure d'arrivée *</label>
                            <input type="text" name="heure_arrive" required><br/>

                            <label for="heure_depart">Heure de départ *</label>
                            <input type="text" name="heure_depart" required><br/><br/>

                            <label for="commentaire">Laisser un commentaire</label><br/>
                            <textarea name="commentaire" rows="4" cols="50"></textarea><br/><br/>
                            <div id="alerteChampsVides" style="color: red;"></div>
                            <input class="createButton btn btn-primary custom-btn" type="submit" value="Créer"/>
                        </fieldset>
                    </form>
                    <!-- END #content -->
                    <!-- END row -->
                    <?php require_once('includes/footer.php'); ?>

                    <!-- BEGIN scroll-top-btn -->
                    <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top"
                       data-toggle="scroll-to-top"><i class="fa fa-angle-up"></i></a>
                    <!-- END scroll-top-btn -->
                </div>
            </div>

            <style>
                .intervention {
                    display: inline-flex; /* Modifier cette ligne */
                    margin-right: 10px; /* Ajouter cette ligne */
                }

            </style>

            <script>
                // Fonction pour activer ou désactiver les champs en fonction de l'état de la case "Contrat/Pack"
                function toggleContratPack() {
                    var contratPackCheckbox = document.querySelector('input[name="contrat"]');
                    var envoieFactureFields = document.querySelectorAll('input[name="adresse"], input[name="adresse_email"], input[name="facturation"], input[name="date_differee"], input[name="paiement"]');

                    // Si la case "Contrat/Pack" est cochée
                    if (contratPackCheckbox.checked) {
                        // Désactiver les champs "Envoi Facture", "Facturation" et "Type de paiement"
                        envoieFactureFields.forEach(function (field) {
                            field.disabled = true;
                            field.removeAttribute('required');
                        });
                    } else {
                        // Activer les champs "Envoi Facture", "Facturation" et "Type de paiement"
                        envoieFactureFields.forEach(function (field) {
                            field.disabled = false;
                            field.setAttribute('required', true);
                        });
                    }
                }

                // Ajouter un écouteur d'événements pour la case "Contrat/Pack"
                document.querySelector('input[name="contrat"]').addEventListener('change', toggleContratPack);

                // Appeler la fonction au chargement de la page pour vérifier son état initial
                toggleContratPack();

                // liste deéroulante
                function showDropdown() {
                    var inputField = document.getElementById("selectedIntervention");
                    var dropdownMenu = document.getElementById("interventionList");

                    var rect = inputField.getBoundingClientRect();
                    dropdownMenu.style.top = (rect.bottom + window.scrollY) + "px";
                    dropdownMenu.style.left = rect.left + "px";
                    dropdownMenu.style.display = "block";
                }

                // selection de la liste déroulante
                function selectIntervention(intervention) {
                    var inputField = document.getElementById("selectedIntervention");
                    inputField.value = intervention;
                    var dropdownMenu = document.getElementById("interventionList");
                    dropdownMenu.style.display = "none";
                }

                document.addEventListener("click", function (event) {
                    var dropdownMenu = document.getElementById("interventionList");
                    var inputField = document.getElementById("selectedIntervention");
                    if (event.target !== inputField && !inputField.contains(event.target)) {
                        dropdownMenu.style.display = "none";
                    }
                });
                // Ajouter un champ d'intervention
                var compteurChamps = 1; // Initialiser le compteur de champs

                function ajouterChamp() {
                    var clone = document.querySelector('.intervention').cloneNode(true); // Cloner le champ existant
                    // Réinitialiser les valeurs des champs clonés
                    clone.querySelectorAll('input').forEach(function (input) {
                        input.value = '';
                    });
                    // Ajouter le champ cloné à la liste des interventions
                    document.getElementById('interventions').appendChild(clone);

                    // Ajouter une marge entre les champs clonés
                    clone.querySelectorAll('.intervention').forEach(function (intervention) {
                        intervention.style.marginTop = '10px'; // Ajouter une marge de 10px entre chaque champ cloné
                    });

                    // Ajouter un écouteur d'événements sur le bouton "Supprimer" du champ ajouté
                    var supprimerBtn = clone.querySelector('.btn-danger');
                    supprimerBtn.style.marginTop = '10px'; // Ajouter une marge de 5px au bouton "Supprimer"

                    supprimerBtn.addEventListener('click', function () {
                        supprimerChamp(this);
                        // Mettre à jour les informations et effectuer les calculs
                        afficherResultat();
                    });

                    // Mettre à jour les informations et effectuer les calculs
                    afficherResultat();
                }


                // Supprimer un champ d'intervention
                function supprimerChamp(button) {
                    var interventionsDiv = document.getElementById('interventions');
                    var interventionDiv = button.parentElement;
                    var parentDiv = interventionDiv.parentElement;

                    // Vérifier s'il y a plus d'un champ avant de permettre la suppression
                    if (interventionsDiv.children.length >= 1) {
                        parentDiv.removeChild(interventionDiv);
                    } else {
                        alert("Vous ne pouvez pas supprimer ce champ.");
                    }
                }

                //Ajout d'un champ pour Courrier et Mail
                function toggleAdresse(id) {
                    var champAdresse = document.getElementById(id);

                    if (champAdresse.style.display === "none") {
                        champAdresse.style.display = "block";
                    } else {
                        champAdresse.style.display = "none";
                    }
                }

                //Ajout d'un champ de date et récupération de la date d'aujourd'hui
                function toggleDateField() {
                    var champDate = document.getElementById("date_differee");
                    var facturationType = document.querySelector('input[name="facturation"]:checked').value;

                    if (facturationType === "differee") {
                        champDate.style.display = "block";
                        champDate.required = true;
                    } else {
                        champDate.style.display = "none";
                        champDate.required = false;
                        // Si l'option immédiate est sélectionnée, remplissez automatiquement le champ de la date avec la date d'aujourd'hui
                        if (facturationType === "immediate") {
                            var today = new Date();
                            var formattedDate = today.toISOString().substr(0, 10);
                            champDate.value = formattedDate;
                        }
                    }
                }

            </script>

            <!-- END #app -->

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
<?php else: ?>
    <div class="container">
        <h1 class="mt-5">Accès refusé</h1>
        <p>Vous n'avez pas les permissions nécessaires pour accéder à cette page.</p>
    </div>
<?php endif; ?>
</html>
