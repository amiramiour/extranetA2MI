<?php

session_start();

include('../ConnexionBD.php');

//faire apparaitre la navbar uniquement à l'admin et sousadmin
if($_SESSION['user_type'] === 'admin' || $_SESSION['user_type'] === 'sousadmin'){
    include('../navbar.php');
}

// Vérification de l'ID du client dans l'URL
if(isset($_GET['id'])) {
    $client_id = $_GET['id'];
} elseif(isset($_SESSION['user_id'])) { // Si l'ID n'est pas dans l'URL, on vérifie s'il est dans la session
    $client_id = $_SESSION['user_id'];
}else {
    echo "ID du client non spécifié.";
    exit();
}

try {
    // Connexion à la base de données
    $db = connexionbdd();

    // Requête SQL pour récupérer le nom et le prénom du client en fonction de son ID
    $query = "SELECT membre_nom, membre_prenom FROM membres WHERE membre_id = :id";

    // Préparation de la requête SQL
    $stmt = $db->prepare($query);

    // Liaison des valeurs des paramètres de requête
    $stmt->bindParam(':id', $client_id, PDO::PARAM_INT);

    // Exécution de la requête
    $stmt->execute();

    // Récupération du résultat
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérification si le client existe
    if($client) {
        // Affichage du nom et du prénom du client
        echo "<div class='container mt-3'>";
        echo "<h1>Profil de : " . $client['membre_nom'] . " " . $client['membre_prenom'] . "</h1>";

        // Boutons de création
        if($_SESSION['user_type'] === 'admin' || $_SESSION['user_type'] === 'sousadmin'){
            echo "<a href='../SAV/sav-formulaire.php?client_id=$client_id' class='btn btn-primary'>Créer SAV</a>";
            echo "<br>";
            echo "<a href='../bi/bi_form.php?membre_id=$client_id' class='btn btn-primary'>Créer BI</a>";
            echo "<br>";
            echo "<a href='../commandes/ajouter_commande.php?id=$client_id' class='btn btn-primary'>Créer Commande</a>";
            echo "<br>";
            echo "<a href='../pret/pret.php?id=$client_id'class='btn btn-primary'>Créer Pret</a>";
            echo "<br>";
        }

        // Requête SQL pour récupérer les SAV du client
        $query_sav = "SELECT sav_id, sav_accessoire, sav_datein, sav_dateout, sav_envoi, sav_etat, sav_forfait, sav_garantie, sav_maindoeuvreht, sav_maindoeuvrettc, sav_mdpclient, sav_probleme, sav_regle, sav_tarifmaterielht, sav_tarifmaterielttc, sav_technicien, sav_typemateriel, sav_etats.etat_intitule
                          FROM sav
                          LEFT JOIN sav_etats ON sav.sav_etats = sav_etats.id_etat_sav
                          WHERE sav.membre_id = :id AND sav.active = 1";

        // Préparation de la requête SQL pour les SAV
        $stmt_sav = $db->prepare($query_sav);
        $stmt_sav->bindParam(':id', $client_id, PDO::PARAM_INT);
        $stmt_sav->execute();
        $savs = $stmt_sav->fetchAll(PDO::FETCH_ASSOC);

        // Vérification si des SAV sont disponibles
        if(!empty($savs)) {
            // Affichage des SAV
            echo "<h2>Suivi des SAV</h2>";
            echo "<div class='row' id='savs'>";
            foreach($savs as $sav) {
                echo "<div class='col-md-4'>";
                echo "<div class='card mb-3'>";
                echo "<div class='card-body'>";
                echo "<h5 class='card-title'>SAV n° " . $sav['sav_id'] . "</h5>";
                echo "<p>Date de création : " . date('d/m/Y', strtotime($sav['sav_datein'])) . "</p>";
                echo "<p>État : " . $sav['etat_intitule'] . "</p>";
                // Bouton pour modifier le SAV
                if($_SESSION['user_type'] === 'admin' || $_SESSION['user_type'] === 'sousadmin'){
                    echo "<a href='../SAV/modifier-sav.php?sav_id=" . $sav['sav_id'] . "' class='btn btn-primary custom-btn'>Modifier</a>";
                }
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p>Aucun SAV trouvé pour ce client.</p>";
        }

        // Affichage des bons d'intervention du client
        $query_bi = "SELECT bi_id, bi_datein, bi_facturation, bi_heurearrive, bi_heuredepart
                         FROM bi
                         WHERE membre_id = :id AND bi_active = 1";

        // Préparation de la requête SQL pour les bons d'intervention
        $stmt_bi = $db->prepare($query_bi);
        $stmt_bi->bindParam(':id', $client_id, PDO::PARAM_INT);
        $stmt_bi->execute();
        $bons_intervention = $stmt_bi->fetchAll(PDO::FETCH_ASSOC);

        // Vérification si des bons d'intervention sont disponibles
        if(!empty($bons_intervention)) {
            // Affichage des bons d'intervention
            echo "<h2>Suivi des Bons d'Intervention</h2>";
            echo "<div class='row' id='bons-intervention'>";
            foreach($bons_intervention as $bi) {
                echo "<div class='col-md-4'>";
                echo "<div class='card mb-3'>";
                echo "<div class='card-body'>";
                echo "<h5 class='card-title'>BI n° " . $bi['bi_id'] . "</h5>";
                echo "<p>Date de création : " . date('d/m/Y', $bi['bi_datein']) . "</p>";
                echo "<p>Facturation : " . $bi['bi_facturation'] . "</p>";
                echo "<p>Heure d'arrivée : " . $bi['bi_heurearrive'] . "</p>";
                echo "<p>Heure de départ : " . $bi['bi_heuredepart'] . "</p>";
                // Bouton pour modifier le bon d'intervention
                if ($_SESSION['user_type'] === 'admin' || $_SESSION['user_type'] === 'sousadmin') {
                    echo "<a href='../bi/modification_bi.php?bi_id=" . $bi['bi_id'] . "' class='btn btn-primary custom-btn'>Modifier</a>";
                    echo "<button type='button' class='btn btn-danger btn-supprimer' data-bi-id='" . $bi['bi_id'] . "'>Supprimer</button>"; // Correction
                }

                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p>Aucun bon d'intervention trouvé pour ce client.</p>";
        }

        // Requête SQL pour récupérer les commandes du client
        $query_commandes = "SELECT cmd_id, cmd_reference, cmd_designation, cmd_datein, cmd_dateout, cmd_prixventettc, commande_etat
                                FROM commande
                                JOIN commande_etats ON commande.cmd_etat = commande_etats.id_etat_cmd
                                WHERE membre_id = :id_client";
        $stmt_commandes = $db->prepare($query_commandes);
        $stmt_commandes->bindParam(':id_client', $client_id, PDO::PARAM_INT);
        $stmt_commandes->execute();
        $commandes = $stmt_commandes->fetchAll(PDO::FETCH_ASSOC);

        // Requête SQL pour récupérer les prêts du client
        $query_prets = "
    SELECT pret_id, pret_materiel, valeurMat, pret_caution, pret_mode, pret_datein, pret_dateout, commentaire, technicien.membre_nom AS technicien_nom, technicien.membre_prenom AS technicien_prenom
    FROM pret
    JOIN membres AS technicien ON pret.pret_technicien = technicien.membre_id
    WHERE pret.pret_active = 1 AND pret.membre_id = :id";


        // Préparation de la requête SQL pour les prêts
        $stmt_prets = $db->prepare($query_prets);
        $stmt_prets->bindParam(':id', $client_id, PDO::PARAM_INT);
        $stmt_prets->execute();
        $prets = $stmt_prets->fetchAll(PDO::FETCH_ASSOC);

// Vérification si des prêts sont disponibles
        if (!empty($prets)) {
            // Affichage des prêts
            echo "<h2>Prêts en cours</h2>";
            echo "<div class='row'>";
            foreach ($prets as $pret) {
                echo "<div class='col-md-6'>";
                echo "<div class='card mb-3'>";
                echo "<div class='card-body'>";
                echo "<h5 class='card-title'>Matériel : " . $pret['pret_materiel'] . "</h5>";
                echo "<p class='card-text'>Valeur du matériel : " . $pret['valeurMat'] . "€</p>";
                echo "<p class='card-text'>Caution : " . $pret['pret_caution'] . "€</p>";
                echo "<p class='card-text'>Mode de paiement : " . $pret['pret_mode'] . "</p>";
                echo "<p class='card-text'>Date de prêt : " . date('d/m/Y', $pret['pret_datein']) . "</p>";
                echo "<p class='card-text'>Date de retour : " . date('d/m/Y', $pret['pret_dateout']) . "</p>";
                echo "<p class='card-text'>Commentaire : " . $pret['commentaire'] . "</p>";
                echo "<p class='card-text'>Technicien : " . $pret['technicien_nom'] . " " . $pret['technicien_prenom'] . "</p>";
                echo "<a href='/pret/modifier_pret.php?id=" . $pret['pret_id'] . "' class='btn btn-primary'>Modifier</a>";
                echo "<a href='../pret/supprimer_pret.php?id=" . $pret['pret_id'] . "' class='btn btn-primary'>Supprimer</a>";

                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p>Aucun prêt en cours pour ce client.</p>";
        }



        // Vérification si des commandes sont disponibles
        if(!empty($commandes)) {
            // Affichage des commandes
            echo "<h2>Historique des Commandes</h2>";
            echo "<div class='row' id='commandes'>";
            foreach($commandes as $commande) {
                echo "<div class='col-md-4'>";
                echo "<div class='card mb-3'>";
                echo "<div class='card-body bg-info'>";
                echo "<h5 class='card-title'>Commande n° " . $commande['cmd_id'] . "</h5>";
                echo "<p>Date de création : " . date('d/m/Y', strtotime($commande['cmd_datein'])) . "</p>";
                echo "<p>État : " . $commande['commande_etat'] . "</p>";
                // Bouton pour modifier la commande
                if($_SESSION['user_type'] === 'admin' || $_SESSION['user_type'] === 'sousadmin'){
                    echo "<a href='../commandes/modifier_commande.php?id=" . $commande['cmd_id'] . "' class='btn btn-primary custom-btn'>Modifier</a>";
                }
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p>Aucune commande trouvée pour ce client.</p>";
        }

        echo "</div>"; // Fin du container
    } else {
        echo "Aucun client trouvé avec cet ID.";
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
if(isset($_POST['action']) && $_POST['action'] === 'supprimer' && isset($_POST['bi_id'])) {
    // Récupérer l'ID du bon d'intervention à supprimer
    $bi_id = $_POST['bi_id'];

    // Mettre à jour l'attribut bi_active dans la base de données
    $update_query = $db->prepare("UPDATE bi SET bi_active = 0 WHERE bi_id = ?");
    $update_query->execute([$bi_id]);

    // Répondre avec un message indiquant que la suppression a été effectuée
    echo "Le bon d'intervention a été supprimé avec succès.";
    exit; // Arrêter le script après la réponse AJAX
}


?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Client</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
<a href="../connexion/deconnexion.php" class="btn btn-danger">Déconnexion</a>
<!-- Inclure le fichier JavaScript de Bootstrap à la fin du corps -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Inclure jQuery pour les requêtes AJAX -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    // Ecouteur d'événement sur les boutons "Supprimer"
    $(".btn-supprimer").click(function() {
        // Récupérer l'ID du bon d'intervention à supprimer
        var bi_id = $(this).data("bi-id");
        // Demander confirmation à l'utilisateur
        if (confirm("Êtes-vous sûr de vouloir supprimer ce bon d'intervention ?")) {
            // Supprimer l'élément de la page HTML
            $(this).closest(".col-md-4").remove();
            // Envoyer une requête AJAX pour supprimer le bon d'intervention de la base de données
            $.ajax({
                url: "",
                type: "POST",
                data: { action: 'supprimer', bi_id: bi_id },
                success: function(response) {
                    // Afficher uniquement le message de succès
                    alert("La suppression a été un succès.");
                }
            });
        }
    });
</script>

</body>
</html>