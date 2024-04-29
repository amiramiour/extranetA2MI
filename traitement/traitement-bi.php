<?php

session_start();
include '../ConnexionBD.php';

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


            $query_client = "SELECT membre_nom, membre_prenom, membre_mail FROM membres WHERE membre_id = :membre_id";
            $stmt_client = $db->prepare($query_client);
            $stmt_client->bindParam(':membre_id', $membre_id);
            $stmt_client->execute();
            $client_info = $stmt_client->fetch(PDO::FETCH_ASSOC);

            $bi_technicien = 745; // Valeur statique pour le moment

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

            // Récupération des informations du technicien
            $query_technicien = "SELECT membre_nom, membre_prenom, membre_mail FROM membres WHERE membre_id = :bi_technicien";
            $stmt_technicien = $db->prepare($query_technicien);
            $stmt_technicien->bindParam(':bi_technicien', $bi_technicien);
            $stmt_technicien->execute();
            $technicien_info = $stmt_technicien->fetch(PDO::FETCH_ASSOC);

            $technicien_nom = $technicien_info['membre_nom'];
            $technicien_prenom = $technicien_info['membre_prenom'];
            $technicien_email = $technicien_info['membre_mail'];


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
            if (sendBiCreationEmail($membre_id, $selectedIntervention,$technicien_email, $total, $client_info['membre_nom'], $client_info['membre_prenom'], $technicien_nom, $technicien_prenom, true)) {
                $success_count++; // Incrémentez le compteur de succès
            } else {
                $error_count++; // Incrémentez le compteur d'erreurs
            }
            if (sendBiCreationEmail(745, $selectedIntervention,$technicien_email, $total, $client_info['membre_nom'], $client_info['membre_prenom'], $technicien_nom, $technicien_prenom, false)) {
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
            header("Location: /bi/bonIntervention.php");
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

function sendBiCreationEmail($membre_id, $selectedIntervention,$technicien_email, $total, $client_nom, $client_prenom, $technicien_nom, $technicien_prenom, $is_client) {
    // Récupérer l'adresse e-mail du client depuis la base de données
    $connexion = connexionbdd();
    $query = $connexion->prepare("SELECT membre_mail, bi_datein FROM membres INNER JOIN bi ON membres.membre_id = bi.membre_id WHERE membres.membre_id = ?");
    $query->execute([$membre_id]);
    $result = $query->fetch(PDO::FETCH_ASSOC);

    $client_email = $result['membre_mail'];
    $bi_datein = date('d/m/Y');

    // Composez le contenu de l'e-mail
    $subject = "=?UTF-8?B?" . base64_encode("Création d'un Bon d'intervention") . "?="; // Encodage du sujet
    $body = "Bonjour,\n\n";
    if ($is_client) {
        $body .= "Un Bon d'intervention a été créé pour vous :\n\n";
        $body .= "Intervention : $selectedIntervention\n";
        $body .= "Date de création du bon : $bi_datein\n"; // Ajout de la date de création du bon
        $body .= "Prix total : $total euros\n";
        $body .= "Technicien en charge : $technicien_prenom $technicien_nom\n";
        $body .= "Cordialement,\nVotre société";
    } else {
        $body .= "Un Bon d'intervention a été créé pour $client_nom $client_prenom :\n\n";
        $body .= "Intervention : $selectedIntervention\n\n";
        $body .= "Date de création du bon : $bi_datein\n\n"; // Ajout de la date de création du bon
        $body .= "Prix total : $total euros\n\n";
        $body .= "Technicien en charge : $technicien_prenom $technicien_nom\n\n";
        $body .= "Cordialement,\n\nVotre société";
    }

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
        if ($is_client) {
            $mail->addAddress($client_email);    // Adresse e-mail du client
        } else {
            $mail->addAddress($technicien_email);    // Adresse e-mail du technicien
        }


        // Contenu de l'e-mail
        $mail->isHTML(false);
        $mail->CharSet = 'UTF-8'; // Spécification de l'encodage
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