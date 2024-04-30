<?php

session_start();

// Vérification si l'utilisateur est connecté et si son type est différent de "client"
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_mail']) || $_SESSION['user_type'] === 'client') {
    // Si l'utilisateur n'est pas connecté ou est un client, redirigez-le ou affichez un message d'erreur
    header("Location: ../connexion.php");
    exit;
}

// Maintenant, l'utilisateur est connecté et n'est pas un client, donc affichez le formulaire
$client_id = $_GET['client_id']; //la on recupere l'id passé dans l'url
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de création d'un SAV client</title>
    <!-- Inclure Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<?php include('../navbar.php'); ?>

<div class="container">
    <h2 class="text-center mb-4">Formulaire de création d'un SAV client</h2>
    <form action="traitement-sav.php" method="post">
        <!-- Champ caché pour passer l'ID du client -->
        <input type="hidden" name="client_id" value="<?php echo $client_id; ?>">
        <input type="hidden" name="client_id" value="<?php echo $client_id; ?>">

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
                    <input type="checkbox" name="envoie_facture_mail" id="envoie_facture_mail" class="form-check-input">
                    <label for="envoie_facture_mail" class="form-check-label"> Mail</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="envoie_facture_courrier" id="envoie_facture_courrier" class="form-check-input">
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

        <div id="services">
            <div class="service">
                <label for="tarif_service">Tarif service HT :</label>

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

                <input size="9" type="text" name="prix_unité_service[]" placeholder="Prix unitaire" required/>
                <!-- Champ pour afficher le total de ce service -->
                <div class="form-group">
                    <label>Prix Total HT Service : </label>
                    <input type="text" class="form-control total_service_ht" value="0" readonly>
                </div>
            </div>
        </div>
        <button type="button" id="add_service" class="btn btn-primary">Ajouter un service</button>
        <button type="button" id="remove_service" class="btn btn-danger">Supprimer le dernier service</button>

        <!-- Div pour le matériel -->
        <div id="materiels">
            <div class="materiel">
                <label for="tarif">Tarif matériel HT :</label>
                <input size="30" type="text" name="materiel[]" placeholder="Nom du matériel" >
                <input size="5" type="text" name="nombre[]" placeholder="Nombre" >
                <input size="9" type="text" name="prix_unité[]" placeholder="Prix unitaire" >
                <!-- Champ pour afficher le total de ce matériel -->
                <div class="form-group">
                    <label>Prix Total HT Matériel : </label>
                    <input type="text" class="form-control total_materiel_ht" value="0" readonly>
                </div>
            </div>
        </div>


        <button type="button" id="add_materiel" class="btn btn-primary">Ajouter un matériel</button>
        <button type="button" id="remove_materiel" class="btn btn-danger">Supprimer le dernier matériel</button>
        <!-- Champ caché pour stocker le total HT du matériel -->
        <input type="hidden" name="total_materiel_ht" id="total_materiel_ht" value="0">
        <!-- Champ caché pour stocker le total HT des services -->
        <input type="hidden" name="total_service_ht" id="total_service_ht" value="0">

        <div class="form-group">
            <label for="date_recu">Date de réception* :</label>
            <input type="date" id="date_recu" name="date_recu" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="date_livraison">Date de livraison* :</label>
            <input type="date" id="date_livraison" name="date_livraison" class="form-control" required>
            <span id="date_error" style="color: red; display: none;">La date de réception ne peut pas être postérieure à la date de livraison.</span>
        </div>


        <div class="form-group">
            <label for="etat">État* :</label>
            <select name="etat" id="etat" class="form-control" required>
                <option value="receptionne">Réceptionné</option>
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

        <div class="form-group">
            <label>Total HT : </label>
            <input type="text" class="form-control" id="total_ht" value="0" readonly>
        </div>
        <div class="form-group">
            <label>TVA : </label>
            <input type="text" class="form-control" id="tva" value="0" readonly>
        </div>
        <div class="form-group">
            <label>Prix Total TTC : </label>
            <input type="text" class="form-control" id="prix_total_ttc" value="0" readonly>
        </div>

        <button type="submit" class="btn btn-success">Valider</button>
    </form>
</div>

<!-- Inclure jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Votre script JavaScript ici -->
<script>
    $(document).ready(function(){

        // ajoute un service
        var i = $('.service').length;

        $('#add_service').click(function() {
            var $html = '<div class="service">';
            $html += '<label for="tarif_service">Tarif service HT :</label>';

            $html += '<select name="type_service[]" class="form-control">';
            $html += '<option value="eradication_virus"> Éradication de virus</option>';
            $html += '<option value="nettoyage">Nettoyage</option>';
            $html += '<option value="Recuperation_de_données">Recuperation de données</option>';
            $html += ' <option value="Format_reinstallation">Format Reinstallation</option>';
            $html += '<option value="Format_reinstallation sans sauvgarde">Format Reinstallation sans sauvgarde</option>';
            $html += '<option value="Main doeuvre atelier 1h">Main doeuvre atelier 1h</option>';
            $html += '<option value="Diagnostic">Diagnostic</option>';
            $html += '</select>';

            $html += '<input size="5" type="text" name="nombre_heures[]" placeholder="Nombre d\'heures" required/>';
            $html += '<input size="9" type="text" name="prix_unité_service[]" placeholder="Prix unitaire" required/>';
            // Champ pour afficher le total de ce service
            $html += '<div class="form-group">';
            $html += '<label>Prix Total HT Service : </label>';
            $html += '<input type="text" class="form-control total_service_ht" value="0" readonly>';
            $html += '</div>';
            $html += '</div>';

            $($html).fadeIn('slow').appendTo('#services');
            i++;
            updateTotal(); // Appel de la fonction updateTotal() après avoir ajouté un service
        });

        // enleve un service sauf si il n'en reste qu'un
        $('#remove_service').click(function() {
            if( i >= 2 ) {
                $('.service').last().remove();
                i--;
                updateTotal(); // Appel de la fonction updateTotal() après avoir supprimé un service
            }
        });

        // ajoute un matériel
        var j = $('.materiel').length;

        $('#add_materiel').click(function() {
            var $html = '<div class="materiel">';
            $html += '<label for="tarif">Tarif matériel HT :</label>';
            $html += '<input size="30" type="text" name="materiel[]" placeholder="Nom du matériel">';
            $html += '<input size="5" type="text" name="nombre[]" placeholder="Nombre">';
            $html += '<input size="9" type="text" name="prix_unité[]" placeholder="Prix unitaire">';
            // Champ pour afficher le total de ce matériel
            $html += '<div class="form-group">';
            $html += '<label>Prix Total HT Matériel : </label>';
            $html += '<input type="text" class="form-control total_materiel_ht" value="0" readonly>';
            $html += '</div>';
            $html += '</div>';

            $($html).fadeIn('slow').appendTo('#materiels');
            j++;
            updateTotal(); // Appel de la fonction updateTotal() après avoir ajouté un matériel
        });

        // enleve un matériel sauf si il n'en reste qu'un
        $('#remove_materiel').click(function() {
            if( j >= 2 ) {
                $('.materiel').last().remove();
                j--;
                updateTotal(); // Appel de la fonction updateTotal() après avoir supprimé un matériel
            }
        });

        // Fonction pour mettre à jour les totaux
        function updateTotal() {
            var total_ht = 0;
            var total_ht_materiel = 0;
            var total_ht_service = 0;

            // Calcul du total pour les services
            $('.service').each(function() {
                var nombre_heures = parseFloat($(this).find('input[name^="nombre_heures"]').val() || 0);
                var prix_unitaire_service = parseFloat($(this).find('input[name^="prix_unité_service"]').val() || 0);
                var total_service = nombre_heures * prix_unitaire_service;
                total_ht += total_service;
                total_ht_service += total_service; // Ajoutez ceci pour le total des services
                $(this).find('.total_service_ht').val(total_service.toFixed(2));
            });
            $('#total_service_ht').val(total_ht_service.toFixed(2)); // Mettez à jour le total HT des services

            // Calcul du total pour le matériel
            $('.materiel').each(function() {
                var nombre = parseFloat($(this).find('input[name^="nombre"]').val() || 0);
                var prix_unitaire = parseFloat($(this).find('input[name^="prix_unité"]').val() || 0);
                var total_materiel = nombre * prix_unitaire;
                total_ht += total_materiel;
                total_ht_materiel += total_materiel; // Ajoutez ceci pour le total du matériel
                $(this).find('.total_materiel_ht').val(total_materiel.toFixed(2));
            });
            $('#total_materiel_ht').val(total_ht_materiel.toFixed(2)); // Mettez à jour le total HT du matériel
            var tva = total_ht * 0.2;
            var prix_total_ttc = total_ht + tva;

            // Mise à jour des champs dans le formulaire
            $('#total_ht').val(total_ht.toFixed(2));
            $('#tva').val(tva.toFixed(2));
            $('#prix_total_ttc').val(prix_total_ttc.toFixed(2));
        }

        // Appeler la fonction pour la première fois pour afficher les totaux initiaux
        updateTotal();

        // Écouteurs d'événements pour mettre à jour les totaux lorsqu'il y a un changement
        $(document).on('change', 'input[name^="nombre_heures"], input[name^="prix_unité_service"], input[name^="nombre"], input[name^="prix_unité"]', function() {
            updateTotal();
        });

        function checkDates() {
            var date_recu = new Date($('#date_recu').val());
            var date_livraison = new Date($('#date_livraison').val());
            if (date_recu > date_livraison) {
                $('#date_error').show();
                return false;
            } else {
                $('#date_error').hide();
                return true;
            }
        }

        // Ajoutez un écouteur d'événements pour vérifier les dates lorsqu'elles changent
        $('#date_recu, #date_livraison').change(function() {
            checkDates();
        });

        // Empêchez le formulaire de soumettre si les dates ne sont pas valides
        $('form').submit(function() {
            return checkDates();
        });
    });

</script>

<!-- Inclure Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>