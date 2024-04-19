<!-- Creation SAV Contenu-->
<?php
/*
if ($_SESSION['membre_type'] == 'client') {
header('Location: index.php');
exit();
}
$_SESSION['id_admin_connecte'] = $_SESSION['membre_id'];

$id = 1;*/?>

    <!-- Inclure Bootstrap CSS -->

<div class="container">
    <h2 class="text-center mb-4">Formulaire de création d'un SAV client</h2>
    <form action="../traitement-sav.php" method="post">
        <div class="form-group">
            <label for="probleme">Problème* :</label>
            <textarea id="probleme" name="probleme" rows="4" class="form-control" required></textarea>
        </div>

        <div class="form-group">
            <label for="mot_de_passe">Mot de passe client :</label>
            <input type="text" id="mot_de_passe" name="mot_de_passe" class="form-control">
        </div>

        <div class="form-group">
            <div class="form-check">
                <input type="checkbox" name="facture_reglee" id="facture_reglee" class="form-check-input">
                <label for="facture_reglee" class="form-check-label">Facture réglée</label>
            </div>
            <div class="form-group">
                <label class="form-check-label">Envoie Facture :</label>
                <div class="form-check">
                    <input type="checkbox" name="envoie_facture" id="envoie_facture_mail" class="form-check-input">
                    <label for="envoie_facture_mail" class="form-check-label"> Mail</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="envoie_facture" id="envoie_facture_courrier" class="form-check-input">
                    <label for="envoie_facture_courrier" class="form-check-label"> Courrier</label>
                </div>
            </div>

        </div>

        <div class="form-group">
            <label>Type de matériel* :</label><br>
            <div class="form-check form-check-inline">
                <input type="radio" name="type_materiel" value="PC" id="pc" class="form-check-input">
                <label for="pc" class="form-check-label">PC</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="radio" name="type_materiel" value="Laptop" id="laptop" class="form-check-input">
                <label for="laptop" class="form-check-label">Laptop</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="radio" name="type_materiel" value="Tablette" id="tablette" class="form-check-input">
                <label for="tablette" class="form-check-label">Tablette</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="radio" name="type_materiel" value="Téléphone" id="telephone" class="form-check-input">
                <label for="telephone" class="form-check-label">Téléphone</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="radio" name="type_materiel" value="Imprimante" id="imprimante" class="form-check-input">
                <label for="imprimante" class="form-check-label">Imprimante</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="radio" name="type_materiel" value="Périphérique" id="peripherique" class="form-check-input">
                <label for="peripherique" class="form-check-label">Périphérique</label>
            </div>
        </div>

        <div class="form-group">
            <label for="accessoires">Accessoires :</label>
            <textarea id="accessoires" name="accessoires" rows="2" class="form-control"></textarea>
        </div>
        <div id="main-oeuvre">
            <div class="main-oeuvre">
                <label for="tarif_main_oeuvre">Tarif main d'oeuvre HT :</label>

                <select name="type_service[]" class="form-control">
                    <option value="eradication_virus">Éradication de virus</option>
                    <option value="nettoyage">Nettoyage</option>
                    <option value="Recuperation_de_données">Recuperation de données</option>
                    <option value="Format_reinstallation">Format Reinstallation</option>
                    <option value="Format_reinstallation sans sauvgarde">Format Reinstallation sans sauvgarde</option>
                    <option value="Main d'oeuvre atelier 1h">Main d'oeuvre atelier 1h</option>
                    <option value="Diagnostic">Diagnostic</option>

                </select>

                <input size="5" type="text" name="nombre_heures[]" placeholder="Nombre" required/>

                <input size="9" type="text" name="prix_unité_main_oeuvre[]" placeholder="Prix unitaire" required/>
            </div>
        </div>
        <button type="button" id="add_main_oeuvre" class="btn btn-primary">Ajouter un service</button>
        <button type="button" id="remove_main_oeuvre" class="btn btn-danger">Supprimer le dernier service</button>


        <div id="materiels">
            <div class="materiel">
                <label for="tarif">Tarif matériel HT </label>

                <input size="30"  type="text" name="materiel[]" placeholder="Nom du matériel" required/>

                <input size="5" type="text" name="nombre[]" placeholder="Nombre" required/>

                <input  size="9" type="text" name="prix_unité[]" placeholder="Prix unitaire" required/>
            </div>
        </div>

        <button type="button" id="add" class="btn btn-primary">Ajouter un matériel</button>
        <button type="button" id="remove" class="btn btn-danger">Supprimer le dernier matériel</button>
        <div class="form-group">
            <label for="date_livraison">Date de livraison* :</label>
            <input type="date" id="date_livraison" name="date_livraison" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="date_recu">date_recu* :</label>
            <input type="date" id="date_recu" name="date_recu" class="form-control" required>
        </div>



        <div class="form-group">
            <label for="etat">État* :</label>
            <select name="etat" id="etat" class="form-control" required>
                <option value="receptionne">Réceptionné</option>
                <option value="atelier">En atelier</option>
                <option value="termine">Terminé</option>
            </select>
        </div>

        <div class="form-group">
            <label>Sous garantie* :</label>
            <select name="sous_garantie" class="form-control">
                <option value="oui">Oui</option>
                <option value="non">Non</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Valider</button>
    </form>
</div>


<!-- Inclure jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Votre script JavaScript ici -->
<script>
    $(document).ready(function(){

        // ajoute une intervention
        var i = $('.materiel').length;

        $('#add').click(function() {

            var $html = '<div class="materiel">';
            $html += '<label class="float">Tarif matériel HT </label>';
            $html += '<input type="text" name="dynamic['+(i+1)+'][]" id="materiel_'+(i+1)+'" placeholder="Matériel" size="30" required/>   ';
            $html += '<input type="text" name="dynamic['+(i+1)+'][]"  size="5" placeholder="Nombre" required/>   ';
            $html += '<input type="text" name="dynamic['+(i+1)+'][]" size="9" placeholder="Prix unité" required/><br/>';
            $html += '</div>';

            $($html).fadeIn('slow').appendTo('#materiels');
            i++;
        });

        // enleve une intervention sauf si il n'en reste qu'une
        $('#remove').click(function() {
            if( i >= 2 ) {
                $('.materiel').last().remove();
                i--;
            }
        });
        var j = $('.main-oeuvre').length;

        $('#add_main_oeuvre').click(function() {

            var $html = '<div class="main-oeuvre">';
            $html += '<label for="tarif_main_oeuvre">Tarif main d\'oeuvre HT :</label>';

            $html += '<select name="type_service[]" class="form-control">';
            $html += '<option value="eradication_virus"> Éradication de virus</option>';
            $html += '<option value="nettoyage">Nettoyage</option>';
            $html += '<option value="Recuperation_de_données">Recuperation de données</option>';
            $html += ' <option value="Format_reinstallation">Format Reinstallation</option>';
            $html += '<option value="Format_reinstallation sans sauvgarde">Format Reinstallation sans sauvgarde</option>';
            $html += '<option value="Main doeuvre atelier 1h">Main doeuvre atelier 1h</option>';
            $html += '<option value="Diagnostic">Diagnostic</option>';



            // Ajoutez d'autres options au besoin
            $html += '</select>';

            $html += '<input size="5" type="text" name="nombre_heures[]" placeholder="Nombre d\'heures" required/>';

            $html += '<input size="9" type="text" name="prix_unité_main_oeuvre[]" placeholder="Prix unitaire" required/>';

            $html += '</div>';

            $($html).fadeIn('slow').appendTo('#main-oeuvre');
            j++;
        });

        // Supprime un ensemble de champs pour la main d'oeuvre
        $('#remove_main_oeuvre').click(function() {
            if( j >= 2 ) {
                $('.main-oeuvre').last().remove();
                j--;
            }
        });
    });
</script>





