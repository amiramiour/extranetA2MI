<?php
// Inclure le fichier de connexion à la base de données
include 'connexionBD.php';
$db = connexionbdd();


// Vérifier si l'ID du bon d'intervention à modifier est passé dans l'URL
if(isset($_GET['bi_id'])) {
    // Récupérer l'ID du bon d'intervention depuis l'URL
    $bi_id = $_GET['bi_id'];

    // Préparer la requête SQL pour récupérer les données du bon d'intervention
    $query = $db->prepare("SELECT * FROM bi WHERE bi_id = ?");
    $query->execute([$bi_id]);
    $bi_data = $query->fetch(PDO::FETCH_ASSOC);

    // Vérifier si des données ont été trouvées pour l'ID donné
    if($bi_data) {
        // Récupérer les données à pré-remplir
        $selectedIntervention = $bi_data['selectedIntervention'];
        $nbPieces = $bi_data['nbPieces'];
        $prixUnitaire = $bi_data['prixUnitaire'];
        // Répéter ce processus pour chaque champ que vous souhaitez pré-remplir

        // Utiliser ces données pour pré-remplir les champs du formulaire
        ?>
        <!-- Votre code HTML existant jusqu'à la section du formulaire -->
        <!-- Par exemple, pour pré-remplir le champ de l'intervention -->
        <script>
            document.addEventListener("DOMContentLoaded", function() {
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

$membre_id = $_GET['membre_id'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire Bon d'Intervention Client</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<!-- Inclure le navbar -->
<?php include('navbar.php'); ?>

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
    <form method="post" action="traitement-bi.php?membre_id=<?php echo $membre_id; ?>" name="BI">
        <input type="hidden" name="bi_id" value="<?php echo $bi_id; ?>">
        <fieldset>
            <!-- Bouton Ajouter -->
            <button type="button" class="btn btn-primary custom-btn" id="btnAjouter" onclick="ajouterChamp()">Ajouter</button>
            <legend>BI client <small>* champs obligatoires</small></legend>
            <div id="interventions" style="flex-direction: column;">
                <div class="intervention">
                    <label for="selectedIntervention" style="width: 250px;">Intervention *</label>
                    <select id="selectedIntervention" class="form-control" onclick="showDropdown()" style="flex: 1; width: 300px;" required>
                        <option value="" disabled selected>Sélectionner une intervention</option>
                        <option value="Forfait intervention sur site PC">Forfait intervention sur site PC</option>
                        <option value="Forfait intervention sur site MAC">Forfait intervention sur site MAC</option>
                        <option value="Forfait assistance à domicile PC">Forfait assistance à domicile PC</option>
                        <option value="Forfait assistance à domicile MAC">Forfait assistance à domicile MAC</option>
                        <option value="Formation à domicile 1H30 PC">Formation à domicile 1H30 PC</option>
                        <option value="Formation à domicile 1H30 MAC">Formation à domicile 1H30 MAC</option>
                        <option value="Formation à domicile 2H PC">Formation à domicile 2H PC</option>
                        <option value="Formation à domicile 2H MAC">Formation à domicile 2H MAC</option>
                        <option value="Forfait éradication de virus">Forfait éradication de virus</option>
                        <option value="Forfait 1H supplémentaire">Forfait 1H supplémentaire</option>
                        <option value="Forfait 2H supplémentaires">Forfait 2H supplémentaires</option>
                    </select>
                    <label for="nbpiece_1" style="margin-right: 5px;">/ Pièces *</label>
                    <input class="field" type="number" name="dynamic[1][]" id="nbpiece_1" size="5" placeholder="Pièces" pattern="\d+" step="1" onkeypress="return event.charCode >= 48 && event.charCode <= 57" style="width: 100px; margin-right: 5px;" required min="0"/>
                    <label for="prixunit_1" style="margin-right: 5px;">/ Prix unitaire HT *</label>
                    <input class="field prix-unitaire" type="text" name="dynamic[1][]" id="prixunit_1" size="9" placeholder="Prix" pattern="[0-9]+([\.,][0-9]+)?" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode == 44 || event.charCode == 46" style="width: 150px;" required/>
                    <button type="button" class="btn btn-danger" onclick="supprimerChamp(this)">X</button>
                </div>
            </div>


            <input type="checkbox" name="facturer" value="oui"> Facturer<br/>
            <input type="checkbox" name="garantie" value="oui"> Garantie<br/>
            <input type="checkbox" name="contrat" value="oui"> Contrat/Pack<br/>
            <input type="checkbox" name="service_personne" value="oui"> Service à la personne<br/>
            <input type="checkbox" name="regle" value="oui"> Réglé<br/><br/>

            <label>Envoie facture *</label
            ><br/>
            <input type="checkbox" name="envoie_courrier" value="oui" onchange="toggleAdresse('adresse')" /> Courrier<br/>
            <input type="text" name="adresse" id="adresse" placeholder="Entrer votre adresse" size="60" style="display: none;">
            <input type="checkbox" name="envoie_mail" value="oui" onchange="toggleAdresse('adresse_email')" /> Mail<br/>
            <input type="email" name="adresse_email"  id="adresse_email" placeholder="Entrer votre adresse mail" size="25" style="display: none;">
            ( Vous pouvez cocher les deux cases ci-dessus pour facture via Courrier ET Mail )<br/><br/>

            <label>Facturation *</label><br/>
            <input type="radio" name="facturation" value="immediate" onchange="toggleDateField()" required> Immédiate<br/>
            <input type="radio" name="facturation" value="differee" onchange="toggleDateField()"> Différée<br/>
            <input type="date" name="date_differee" id="date_differee" style="display: none;">


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

</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function selectIntervention(intervention) {
        document.getElementById('selectedIntervention').value = intervention;
    }

    function afficherResultat() {
        var facturer = document.querySelector('input[name="facturer"]:checked') ? "oui" : "non";
        var garantie = document.querySelector('input[name="garantie"]:checked') ? "oui" : "non";
        var contrat = document.querySelector('input[name="contrat"]:checked') ? "oui" : "non";
        var servicePersonne = document.querySelector('input[name="service_personne"]:checked') ? "oui" : "non";
        var regle = document.querySelector('input[name="regle"]:checked') ? "oui" : "non";
        var envoieCourrier = document.querySelector('input[name="envoie_courrier"]:checked') ? "courrier" : "";
        var envoieMail = document.querySelector('input[name="envoie_mail"]:checked') ? "mail" : "";
        var envoiePar = "";

        if (envoieCourrier && envoieMail) {
            envoiePar = "Courrier et Mail";
        } else {
            envoiePar = envoieCourrier || envoieMail;
        }

        var facturation = document.querySelector('input[name="facturation"]:checked').value;
        var dateFacturation = document.getElementById("date_differee").value;
        var paiement = document.querySelector('input[name="paiement"]:checked').value;
        var heureArrivee = document.getElementsByName("heure_arrive")[0].value;
        var heureDepart = document.getElementsByName("heure_depart")[0].value;
        var commentaire = document.getElementsByName("commentaire")[0].value;

        console.log("BI n° unique : " + generateUniqueID());
        console.log("Facturer : " + facturer);
        console.log("Garantie : " + garantie);
        console.log("Contrat/Pack : " + contrat);
        console.log("Service à la personne : " + servicePersonne);
        console.log("Facture réglée : " + regle);
        console.log("Envoyer facture par : " + envoiePar);
        console.log("Facturation : " + facturation);
        console.log("Date de facturation : " + dateFacturation);
        console.log("Type de paiement : " + paiement);
        console.log("Heure d'arrivée : " + heureArrivee);
        console.log("Heure de départ : " + heureDepart);
        console.log("Commentaire : " + commentaire);

        var interventions = document.querySelectorAll('.intervention');
        var totalPrixHT = 0;
        var totalPieces = 0;

        interventions.forEach(function(intervention) {
            var selectedIntervention = intervention.querySelector('select');
            var nbPiecesInput = intervention.querySelector('[name^="dynamic"]');
            var prixUnitaireInput = intervention.querySelector('.prix-unitaire');

            if (selectedIntervention && nbPiecesInput && prixUnitaireInput) {
                var nbPieces = parseInt(nbPiecesInput.value);
                var prixUnitaire = parseFloat(prixUnitaireInput.value);
                var prixTotal = nbPieces * prixUnitaire;

                console.log("\nIntervention : " + selectedIntervention.value);
                console.log("Nb pièces : " + nbPieces + " | Prix/pièce : " + prixUnitaire.toFixed(2) + "€ | Prix total : " + prixTotal.toFixed(2) + "€");

                totalPieces += nbPieces;
                totalPrixHT += prixTotal;
            }
        });

        var tauxTVA = 0.20; // Supposons une TVA de 20%
        var montantTVA = totalPrixHT * tauxTVA;
        var totalPrixTTC = totalPrixHT + montantTVA;

        console.log("\nTotal des pièces : " + totalPieces);
        console.log("Coût total HT : " + totalPrixHT.toFixed(2) + "€");
        console.log("Montant de la TVA : " + montantTVA.toFixed(2) + "€");
        console.log("Coût total TTC : " + totalPrixTTC.toFixed(2) + "€");
    }

    // Ajouter un écouteur d'événement pour le bouton "Ajouter"
    document.getElementById('btnAjouter').addEventListener('click', function() {
        // Appeler la fonction afficherResultat pour inclure les nouvelles interventions
        afficherResultat();
    });

    // Fonction pour générer un ID unique pour chaque Bon d'Intervention
    function generateUniqueID() {
        return Math.floor(Math.random() * 1000); // Génère un nombre aléatoire entre 0 et 999
    }

    // Fonction appelée lorsque le formulaire est soumis
    // Fonction appelée lorsque le formulaire est soumis
    function handleSubmit(event) {
        // Empêcher le comportement par défaut du formulaire
        event.preventDefault();

        // Effectuer les actions et les calculs
        afficherResultat();
        // Soumettre le formulaire
        document.forms["BI"].submit();
    }

    // Ajout d'un écouteur d'événement pour le formulaire
    document.addEventListener("DOMContentLoaded", function() {
        var form = document.querySelector('form[name="BI"]');
        form.addEventListener("submit", handleSubmit);
    });



</script>

<script>
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
    document.addEventListener("click", function(event) {
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
        clone.querySelectorAll('input').forEach(function(input) {
            input.value = '';
        });
        // Ajouter le champ cloné à la liste des interventions
        document.getElementById('interventions').appendChild(clone);

        // Ajouter un écouteur d'événements sur le bouton "Supprimer" du champ ajouté
        var supprimerBtn = clone.querySelector('.btn-danger');
        supprimerBtn.addEventListener('click', function() {
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
        if (interventionsDiv.children.length > 1) {
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



</body>
</html>
