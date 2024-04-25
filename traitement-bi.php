<?php

session_start();
include 'connexionBD.php';

require 'C:\wamp64\www\A2MI2024\extranetA2MI\vendor\autoload.php';

// Utilisation de la classe PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Vérifier si l'ID du membre est passé dans l'URL
if (isset($_GET['membre_id'])) {
    // Récupérer l'ID du membre depuis l'URL
    $membre_id = $_GET['membre_id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            $db = connexionbdd();

            $bi_technicien = 745; // Valeur statique pour le moment
            $technicien_email = "amiouramirtahar@gmail.com";

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

            // Insertion dans la table `bi` (Bon d'intervention)
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

            // Insertion dans la table `intervention`
            $query_intervention = $db->prepare("INSERT INTO intervention (inter_intervention, inter_nbpiece, inter_prixunit, inter_total, bi_id) 
VALUES (:selectedIntervention, :nbPieces, :prixUnitaire , :total, :bi_id)");

            $query_intervention->bindParam(':selectedIntervention', $selectedIntervention);
            $query_intervention->bindParam(':nbPieces', $nbPieces);
            $query_intervention->bindParam(':prixUnitaire', $prixUnitaire);
            $query_intervention->bindParam(':total', $total);
            $query_intervention->bindParam(':bi_id', $bi_id);


            $success_count=0; // Incrémentez le compteur de succès
            $error_count=0;
            $query_intervention->execute();

            // Envoi de l'e-mail de confirmation
            if (sendBiCreationEmail($membre_id, $selectedIntervention, $technicien_email, $total, $facturation,true)) {
                $success_count++; // Incrémentez le compteur de succès
            } else {
                $error_count++; // Incrémentez le compteur d'erreurs
            }
            if (sendBiCreationEmail(745, $selectedIntervention, $technicien_email, $total, $facturation,false)) {
                $success_count++; // Incrémentez le compteur de succès
            } else {
                $error_count++; // Incrémentez le compteur d'erreurs
            }

            if ($success_count > 0 && $error_count == 0) {
                echo "Enregistrement du SAV effectué avec succès. Les e-mails ont été envoyés avec succès.";
            } elseif ($success_count > 0 && $error_count > 0) {
                echo "Enregistrement du SAV effectué avec succès. Certains e-mails ont été envoyés avec succès, mais il y a eu des erreurs lors de l'envoi de certains e-mails.";
            } else {
                echo "Enregistrement du SAV effectué, mais aucun e-mail n'a été envoyé. Veuillez vérifier les erreurs.";
            }


            // Redirection vers bonIntervention.php après traitement
            header("Location: bonIntervention.php");
            exit;
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        } finally {
            $connexion = null;
        }
    } else {
        // Redirection vers une page d'erreur si la méthode de requête n'est pas POST
        exit;
    }
}

function sendBiCreationEmail($membre_id, $selectedIntervention, $technicien_email, $total, $facturation,$is_client) {
    // Récupérer l'adresse e-mail du client depuis la base de données
    $connexion = connexionbdd();
    $query = $connexion->prepare("SELECT membre_mail, membre_prenom, membre_nom FROM membres WHERE membre_id = ?");
    $query->execute([$membre_id]);
    $result = $query->fetch(PDO::FETCH_ASSOC);
    $client_email = $result['membre_mail'];
    $prenom = $result['membre_prenom'];
    $nom = $result['membre_nom'];

    // Composez le contenu de l'e-mail
    $subject = "Création d'un Bon d'intervention";
    $body = "Bonjour,\n\n";
    if ($is_client) {

        $body .= "Un Bon d'intervention a été créé pour vous :\n\n";
        $body .= "Intervention : $selectedIntervention\n\n";
        $body .= "Prix total : $total euros\n";
        $body .= "Technicien en charge : $technicien_email\n";
        $body .= "Date de facturation : $facturation\n";
        $body .= "Cordialement,\nVotre société";}
    else{
        $body .= "Un Bon d'intervention a été créé pour $prenom $nom :\n\n";
        $body .= "Intervention : $selectedIntervention\n\n";
        $body .= "Prix total : $total euros\n";
        $body .= "Technicien en charge : $technicien_email\n";
        $body .= "Date de facturation : $facturation\n";
        $body .= "Cordialement,\nVotre société";}



    try {
        $mail = new PHPMailer(true);

        // Paramètres du serveur SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'masdouarania02@gmail.com';  // Adresse email de l'expéditeur
        $mail->Password = 'wmeffiafffoqvkvl';           // Mot de passe de l'expéditeur
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Destinataires
        $mail->setFrom('masdouarania02@gmail.com', 'Votre société');
        $mail->addAddress($client_email);    // Adresse e-mail du client

        // Contenu de l'e-mail
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body = $body;

        // Envoi de l'e-mail
        $mail->send();

        // Succès de l'envoi
        return true;
    } catch (Exception $e) {
        // Erreur lors de l'envoi de l'e-mail
        return false;
    }
}
