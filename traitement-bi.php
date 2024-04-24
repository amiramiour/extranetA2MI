<?php

session_start();
include 'connexionBD.php';

// Vérifier si l'ID du membre est passé dans l'URL
if (isset($_GET['membre_id'])) {
    // Récupérer l'ID du membre depuis l'URL
    $membre_id = $_GET['membre_id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            $db = connexionbdd();

            $bi_technicien = 1; // Valeur statique pour le moment

            $facturer = isset($_POST['facturer']) ? 'oui' : 'non';
            $garantie = isset($_POST['garantie']) ? 'oui' : 'non';
            $contrat = isset($_POST['contrat']) ? 'oui' : 'non';
            $service = isset($_POST['service_personne']) ? 'oui' : 'non';
            $regle = isset($_POST['regle']) ? 'oui' : 'non';

            $envoi = '';
            if (isset($_POST['envoie_courrier']) && isset($_POST['envoie_mail'])) {
                $envoi = 'courrier et mail';
            } elseif (isset($_POST['envoie_courrier'])) {
                $envoi = 'courrier';
            } elseif (isset($_POST['envoie_mail'])) {
                $envoi = 'mail';
            }

            $facturation = $_POST['facturation'];
            $dateFacturation = ($facturation === 'differee') ? $_POST['date_differee'] : null;
            $paiement = $_POST['paiement'];
            $heureArrive = $_POST['heure_arrive'];
            $heureDepart = $_POST['heure_depart'];
            $commentaire = $_POST['commentaire'];

            $query = $db->prepare("INSERT INTO bi (membre_id, bi_technicien, bi_facture, bi_garantie, bi_contrat, bi_service, bi_envoi, bi_facturation, bi_datefacturation, bi_paiement, bi_datein, bi_heurearrive, bi_heuredepart, bi_commentaire, bi_regle) 
            VALUES (:membre_id, :bi_technicien, :facturer, :garantie, :contrat, :service, :envoi, :facturation, :dateFacturation, :paiement, UNIX_TIMESTAMP(), :heureArrive, :heureDepart, :commentaire, :regle)");

            $query->bindParam(':membre_id', $membre_id);
            $query->bindParam(':bi_technicien', $bi_technicien);
            $query->bindParam(':facturer', $facturer);
            $query->bindParam(':garantie', $garantie);
            $query->bindParam(':contrat', $contrat);
            $query->bindParam(':service', $service);
            $query->bindParam(':envoi', $envoi);
            $query->bindParam(':facturation', $facturation);
            $query->bindParam(':dateFacturation', $dateFacturation);
            $query->bindParam(':paiement', $paiement);
            $query->bindParam(':heureArrive', $heureArrive);
            $query->bindParam(':heureDepart', $heureDepart);
            $query->bindParam(':commentaire', $commentaire);
            $query->bindParam(':regle', $regle);

            $query->execute();

            // Récupérer l'ID du bon d'intervention inséré
            $bi_id = $db->lastInsertId();

            // Maintenant, insérons les données dans la table `intervention`
            $selectedIntervention = $_POST['selectedIntervention'];
            $nbPieces = $_POST['nb_pieces'];
            $prixUnitaire = $_POST['prixUn'];

            $total = $nbPieces * $prixUnitaire; // Calculer le total

            $query_intervention = $db->prepare("INSERT INTO intervention (inter_intervention, inter_nbpiece, inter_prixunit, inter_total, bi_id) 
VALUES (:selectedIntervention, :nbPieces, :prixUnitaire , :total, :bi_id)");

            $query_intervention->bindParam(':selectedIntervention', $selectedIntervention);
            $query_intervention->bindParam(':nbPieces', $nbPieces);
            $query_intervention->bindParam(':prixUnitaire', $prixUnitaire);
            $query_intervention->bindParam(':total', $total);
            $query_intervention->bindParam(':bi_id', $bi_id);

            $query_intervention->execute();

            // Vérifier si l'insertion dans la table `intervention` s'est bien passée
            if ($query_intervention->rowCount() > 0) {
                // Message de succès
                $_SESSION['success_message'] = "Les données ont été ajoutées avec succès dans la base de données.";
            } else {
                // Message d'erreur si l'insertion a échoué
                $_SESSION['error_message'] = "Erreur lors de l'insertion dans la table intervention : Aucune ligne insérée.";
            }

            // Redirection vers bonIntervention.php après traitement
            header("Location: bonIntervention.php");
            exit;
        } catch (PDOException $e) {
            // Message d'erreur en cas d'échec
            $_SESSION['error_message'] = "Erreur lors de l'insertion des données : " . $e->getMessage();
        }
    }
} else {
    // Redirection vers une page d'erreur si l'ID du membre n'est pas présent dans l'URL
    header("Location: erreur.php");
    exit;
}