<?php
session_start();
include 'connexionBD.php';

// Définition des valeurs statiques
$membre_id = 7;
$bi_technicien = 1;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $db = connexionbdd();

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

        // Message de succès
        $_SESSION['success_message'] = "Les données ont été ajoutées avec succès dans la base de données.";
    } catch (PDOException $e) {
        // Message d'erreur en cas d'échec
        $_SESSION['error_message'] = "Erreur lors de l'insertion des données : " . $e->getMessage();
    }
}

// Redirection vers bi_form.php après traitement
header("Location: bi_form.php");
exit;
?>
