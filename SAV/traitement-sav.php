<?php
require_once '../connexionBD.php';


require 'C:\wamp64\www\A2MI2024\extranetA2MI\vendor\autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Initialisez des variables pour compter les succès et les échecs
$success_count = 0;
$error_count = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $connexion = connexionbdd();

        // Récupération de l'ID du client depuis le formulaire
        $membre_id = $_POST['client_id'];
        $query_client = "SELECT membre_nom, membre_prenom, membre_mail FROM membres WHERE membre_id = :membre_id";
        $stmt_client = $connexion->prepare($query_client);
        $stmt_client->bindParam(':membre_id', $membre_id);
        $stmt_client->execute();
        $client_info = $stmt_client->fetch(PDO::FETCH_ASSOC);
        // Récupération de l'ID du technicien en charge depuis la session
        // À remplacer par la gestion de sessions
        $sav_technicien_id = 744; // ID du technicien en charge (à remplacer par la gestion de sessions)

        // Récupération des informations du technicien
        $query_technicien = "SELECT membre_nom, membre_prenom, membre_mail FROM membres WHERE membre_id = :sav_technicien_id";
        $stmt_technicien = $connexion->prepare($query_technicien);
        $stmt_technicien->bindParam(':sav_technicien_id', $sav_technicien_id);
        $stmt_technicien->execute();
        $technicien_info = $stmt_technicien->fetch(PDO::FETCH_ASSOC);

        $technicien_nom = $technicien_info['membre_nom'];
        $technicien_prenom = $technicien_info['membre_prenom'];
        $technicien_email = $technicien_info['membre_mail'];

        // Récupération des données du formulaire
        $probleme = $_POST['probleme'];
        $mot_de_passe = $_POST['mot_de_passe'];
        $type_materiel = $_POST['type_materiel'];
        $accessoires = $_POST['accessoires'];
        $etat_intitule = $_POST['etat']; // Changement ici, on récupère le libellé de l'état
        $sous_garantie = $_POST['sous_garantie'];
        $date_recu = $_POST['date_recu']; // Nouvelle ligne pour récupérer la date de réception
        $date_livraison = $_POST['date_livraison']; // Nouvelle ligne pour récupérer la date de livraison

        // Récupération des totaux HT depuis le formulaire
        $prix_materiel_ht = $_POST['total_materiel_ht'];
        $prix_main_oeuvre_ht = $_POST['total_service_ht'];

        // Calcul des totaux TTC
        $tva_prix_materiel_ht = $prix_materiel_ht * 0.2;
        $prix_materiel_ttc = $prix_materiel_ht + $tva_prix_materiel_ht;

        $tva_prix_main_oeuvre_ht = $prix_main_oeuvre_ht * 0.2;
        $prix_main_oeuvre_ttc = $prix_main_oeuvre_ht + $tva_prix_main_oeuvre_ht;
        $prix_total_ttc = $prix_main_oeuvre_ttc + $prix_materiel_ttc;
        //facture réglée
        // Récupération de la valeur de la case à cocher "Facture réglée"
        $facture_reglee = isset($_POST['facture_reglee']) ? 'oui' : 'non';

        // Gestion de l'envoi de la facture
        $envoi_facture = '';

        if (isset($_POST['envoie_facture_mail']) && isset($_POST['envoie_facture_courrier'])) {
            // Les deux cases sont cochées
            $envoi_facture = 'mail,courrier';
        } elseif (isset($_POST['envoie_facture_mail'])) {
            // Seule la case mail est cochée
            $envoi_facture = 'mail';
        } elseif (isset($_POST['envoie_facture_courrier'])) {
            // Seule la case courrier est cochée
            $envoi_facture = 'courrier';
        }

        // Récupérer l'ID de l'état à partir de son libellé
        $query_etat = "SELECT id_etat_sav FROM sav_etats WHERE etat_intitule = :etat_intitule";
        $stmt_etat = $connexion->prepare($query_etat);
        $stmt_etat->bindParam(':etat_intitule', $etat_intitule);
        $stmt_etat->execute();
        $etat_row = $stmt_etat->fetch(PDO::FETCH_ASSOC);
        $etat_id = $etat_row['id_etat_sav'];

        // Enregistrement dans la base de données
        $sql = "INSERT INTO sav (membre_id, sav_accessoire, sav_datein, sav_dateout, sav_envoi, sav_etat, sav_etats, sav_forfait, sav_garantie, sav_maindoeuvreht, sav_maindoeuvrettc, sav_mdpclient, sav_probleme, sav_regle, sav_tarifmaterielht, sav_tarifmaterielttc, sav_typemateriel, sav_technicien) 
VALUES (:membre_id, :accessoires, :date_recu, :date_livraison, :envoi_facture, 744, :etat_id, :forfait, :garantie, :maindoeuvreht, :maindoeuvrettc, :mdpclient, :probleme, :facture_reglee, :tarifmaterielht, :tarifmaterielttc, :typemateriel, :sav_technicien_id)";

        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':membre_id', $membre_id);
        $stmt->bindParam(':sav_technicien_id', $sav_technicien_id);
        $stmt->bindParam(':accessoires', $accessoires);
        $stmt->bindParam(':etat_id', $etat_id); // Utilisation de l'ID de l'état
        $stmt->bindParam(':date_recu', $date_recu);
        $stmt->bindParam(':date_livraison', $date_livraison);
        $stmt->bindValue(':forfait', '0');
        $stmt->bindParam(':garantie', $sous_garantie);
        $stmt->bindParam(':maindoeuvreht', $prix_main_oeuvre_ht);
        $stmt->bindParam(':maindoeuvrettc', $prix_main_oeuvre_ttc);
        $stmt->bindParam(':mdpclient', $mot_de_passe);
        $stmt->bindParam(':probleme', $probleme);
        $stmt->bindParam(':tarifmaterielht', $prix_materiel_ht);
        $stmt->bindParam(':tarifmaterielttc', $prix_materiel_ttc);
        $stmt->bindParam(':typemateriel', $type_materiel);
        $stmt->bindParam(':facture_reglee', $facture_reglee);
        $stmt->bindParam(':envoi_facture', $envoi_facture);

        $stmt->execute();

// Récupérer l'ID du SAV nouvellement inséré
        $sav_id = $connexion->lastInsertId();

// Enregistrer l'historique de sauvegarde
        try {
            $query_insert_sav_history = "INSERT INTO sauvgarde_etat_info (sav_id, sauvgarde_etat, sauvgarde_avancement, date_creation, date_update, created_by, updated_by) 
    VALUES (:sav_id, :sauvgarde_etat, 'Création de SAV', NOW(), NOW(), 1, 744)";
            $stmt_insert_sav_history = $connexion->prepare($query_insert_sav_history);
            $stmt_insert_sav_history->bindParam(':sav_id', $sav_id, PDO::PARAM_INT);
            $stmt_insert_sav_history->bindParam(':sauvgarde_etat', $etat_id, PDO::PARAM_INT);
            $stmt_insert_sav_history->execute();
        } catch (PDOException $e) {
            // Gérer les erreurs
            $error = "Erreur : " . $e->getMessage();
        }


        // Envoi de l'e-mail de confirmation au client
        if (sendSAVCreationEmail($membre_id, $prix_total_ttc, $technicien_email, null, null, $technicien_nom, $technicien_prenom, $date_recu, $etat_intitule, true)) {
            $success_count++; // Incrémentez le compteur de succès
        } else {
            $error_count++; // Incrémentez le compteur d'erreurs
        }

// Envoi de l'e-mail de confirmation au technicien
        if (sendSAVCreationEmail($membre_id, $prix_total_ttc, $technicien_email, $client_info['membre_nom'], $client_info['membre_prenom'], $technicien_nom, $technicien_prenom, $date_recu, $etat_intitule, false)) {
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
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    } finally {
        $connexion = null;
    }
} else {
    header('Location: ../index.php');
    exit();
}

// Fonction pour envoyer un e-mail de confirmation
function sendSAVCreationEmail($membre_id, $prix_total_ttc, $technicien_email, $client_nom, $client_prenom, $technicien_nom, $technicien_prenom, $date_recu, $etat_intitule, $is_client) {
    // Récupérer l'adresse e-mail du client depuis la base de données
    $connexion = connexionbdd();
    $query = $connexion->prepare("SELECT membre_mail FROM membres WHERE membre_id = ?");
    $query->execute([$membre_id]);
    $client_info = $query->fetch(PDO::FETCH_ASSOC);

    // Composez le contenu de l'e-mail
    $subject = "=?UTF-8?B?" . base64_encode("Création d'un SAV") . "?="; // Encodage du sujet
    $body = "Bonjour,\n\n";
    if ($is_client) {
        $body .= "Un SAV a été créé pour vous avec les détails suivants :\n\n";
        $body .= "Prix total : $prix_total_ttc euros\n";
        $body .= "Technicien en charge : $technicien_nom $technicien_prenom\n";
        $body .= "Date de création : $date_recu\n";
        $body .= "État : $etat_intitule\n\n";
    } else {
        $body .= "Un SAV a été créé pour : $client_nom $client_prenom avec les détails suivants :\n\n";
        $body .= "Prix total : $prix_total_ttc euros\n";
        $body .= "Technicien en charge : $technicien_nom $technicien_prenom\n";
        $body .= "Date de création : $date_recu\n";
        $body .= "État : $etat_intitule\n\n";
    }
    $body .= "Cordialement,\nVotre société";

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
        $mail->setFrom('masdouarania02@gmail.com', 'A2MI informatique');
        if ($is_client) {
            $mail->addAddress($client_info['membre_mail']);    // Adresse e-mail du client
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
        echo "Erreur lors de l'envoi de l'e-mail : {$mail->ErrorInfo}";
        return false;
    }
}

?>
