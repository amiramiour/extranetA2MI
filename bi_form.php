<?php
global $db;
session_start();
header('Content-type: text/html; charset=utf-8');
include('connexionBD.php');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire Bon d'Intervention Client</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<!-- Inclure le navbar -->
<?php include('navbar.php'); ?>

<div id="contenu" class="container mt-3">
    <h1>Formulaire de création d'un Bon d'Intervention client</h1>
    <form method="post" name="SAV">
        <fieldset>
            <legend>BI client <small>* champs obligatoires</small></legend>
            <div id="interventions">
                <div class="intervention">
                    <label for="selectedIntervention">Intervention/Pièces/Prix unitaire HT * </label>
                    <input class="field" type="text" name="dynamic[1][]" id="selectedIntervention" onclick="showDropdown()" placeholder="Sélectionner une intervention" size="30" list="list_inter" required/> /
                    <input class="field" type="text" name="dynamic[1][]" id="nbpiece_1" size="5" placeholder="Pièces" required /> /
                    <input class="field" type="text" name="dynamic[1][]" id="prixunit_1" size="9" placeholder="Prix" required/>
                    <div id="interventionList" class="dropdown-menu" style="display: none;">
                        <a class="dropdown-item" href="#" onclick="selectIntervention('Forfait intervention sur site PC')">Forfait intervention sur site PC</a>
                        <a class="dropdown-item" href="#" onclick="selectIntervention('Forfait intervention sur site MAC')">Forfait intervention sur site MAC</a>
                        <a class="dropdown-item" href="#" onclick="selectIntervention('Forfait assistance à domicile PC')">Forfait assistance à domicile PC</a>
                        <a class="dropdown-item" href="#" onclick="selectIntervention('Forfait assistance à domicile MAC')">Forfait assistance à domicile MAC</a>
                        <a class="dropdown-item" href="#" onclick="selectIntervention('Formation à domicile 1H30 PC')">Formation à domicile 1H30 PC</a>
                        <a class="dropdown-item" href="#" onclick="selectIntervention('Formation à domicile 1H30 MAC')">Formation à domicile 1H30 MAC</a>
                        <a class="dropdown-item" href="#" onclick="selectIntervention('Formation à domicile 2H PC')">Formation à domicile 2H PC</a>
                        <a class="dropdown-item" href="#" onclick="selectIntervention('Formation à domicile 2H MAC')">Formation à domicile 2H MAC</a>
                        <a class="dropdown-item" href="#" onclick="selectIntervention('Forfait éradication de virus')">Forfait éradication de virus</a>
                        <a class="dropdown-item" href="#" onclick="selectIntervention('Forfait 1H supplémentaire')">Forfait 1H supplémentaire</a>
                        <a class="dropdown-item" href="#" onclick="selectIntervention('Forfait 2H supplémentaires')">Forfait 2H supplémentaires</a>
                    </div>
                </div>

            </div>
        <label>Facturer</label><br/>
            <input type="checkbox" name="facturer" value="oui"> Facturer<br/>
            <input type="checkbox" name="garantie" value="oui"> Garantie<br/>
            <input type="checkbox" name="contrat" value="oui"> Contrat/Pack<br/>
            <input type="checkbox" name="service_personne" value="oui"> Service à la personne<br/>
            <input type="checkbox" name="regle" value="oui"> Réglé<br/><br/>

            <label>Envoie facture *</label><br/>
            <input type="checkbox" name="envoie_courrier" value="oui"> Courrier<br/>
            <input type="checkbox" name="envoie_mail" value="oui"> Mail<br/><br/>
            ( Vous pouvez cocher les deux cases ci-dessus pour facture via Courrier ET Mail )<br/><br/>

            <label>Facturation *</label><br/>
            <input type="radio" name="facturation" value="immediate" required> Immédiate<br/>
            <input type="radio" name="facturation" value="differee" required> Différée<br/><br/>

            <label>Type de paiement *</label><br/>
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

            <input class="createButton btn btn-primary" type="submit" value="Créer" />
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
</script>

<script>
    function showDropdown() {
        var inputField = document.getElementById("selectedIntervention");
        var dropdownMenu = document.getElementById("interventionList");

        var rect = inputField.getBoundingClientRect();
        dropdownMenu.style.top = (rect.bottom + window.scrollY) + "px";
        dropdownMenu.style.left = rect.left + "px";
        dropdownMenu.style.display = "block";
    }

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
</script>


</body>
</html>
