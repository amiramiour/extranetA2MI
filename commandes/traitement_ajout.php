<?php
require_once '../config.php';

include "../gestion_session.php";

include '../ConnexionBD.php';
$db = connexionbdd();

require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$success_count = 0;
$error_count = 0;

//var_dump($_POST['client_id']);

if (isset($_GET['id'])) {
    $id_client = $_GET['id'];
}
else  {
    $id_client = $_POST['client_id'];
}

//récupérer les informations du client
$query = $db->prepare("SELECT membre_nom, membre_prenom, membre_mail FROM membres WHERE membre_id = :id_client");
$query->bindParam(':id_client', $id_client, PDO::PARAM_INT);
$query->execute();
$client = $query->fetch(PDO::FETCH_ASSOC);

//récupérer les information du technicien qui a effectué la commande
$id_technicien = $_SESSION['user_id'];
//var_dump($id_technicien);
$query = $db->prepare("SELECT membre_nom, membre_prenom, membre_mail FROM membres WHERE membre_id = :id_technicien");
$query->bindParam(':id_technicien', $id_technicien, PDO::PARAM_INT);
$query->execute();
$technicien = $query->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomC = $_POST['nomC'];
    
    $etatC = $_POST['etatC'];
    $totalHT = $_POST['totalHT'];
    $totalTTC = $_POST['totalTTC'];
    $totalMarge = $_POST['totalMarge'];
    $designation = $_POST['designation'];
    $type = $_POST['type'];

    $date_livraison = $_POST['dateP'];
    $date_souhait = $_POST['dateS'];

    $dateP = strtotime($date_livraison);
    $dateS = strtotime($date_souhait);
    $dateActuelle = time();

    $query = $db->prepare("SELECT cmd_devis_etat FROM cmd_devis_etats WHERE id_etat_cmd_devis = :etatC");
    $query->bindParam(':etatC', $etatC, PDO::PARAM_INT);
    $query->execute();
    $etat_commande = $query->fetchColumn();

    if ($type == '2') { //devis
        $photos = $_FILES['photos'];
        //var_dump($photos);
        
        $commentaire = $_POST['commentaire'];

        $stmt = $db->prepare("INSERT INTO commande_devis (cmd_devis_reference, cmd_devis_designation, cmd_devis_datein, cmd_devis_dateout, 
                        membre_id, cmd_devis_prixventettc, cmd_devis_technicien, cmd_devis_dateSouhait, cmd_devis_etat, 
                        cmd_devis_prixHT, cmd_devis_margeT, type_cmd_devis, commentaire) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?,?)");
        $stmt->execute([$nomC, $designation, $dateActuelle, $dateP, $id_client, $totalTTC,$id_technicien, 
                        $dateS, $etatC, $totalHT, $totalMarge, $type, $commentaire]);

        $id_commande_devis = $db->lastInsertId();

        
        // On vérifie d'abord si des photos ont été ajoutées
        if (!empty($photos['name'])) {
            foreach ($photos['name'] as $key => $photo_name) {
                // Chemin temporaire du fichier
                $file_tmp = $photos['tmp_name'][$key];

                // Générer un nom de fichier unique en utilisant la date, l'heure, min, sec et l'index du fichier
                $current_datetime = date("Y-m-d_H-i-s");
                $file_extension = pathinfo($photo_name, PATHINFO_EXTENSION); // Extension du fichier
                $new_filename = "image_" . $current_datetime . "_" . $key . "." . $file_extension;
                
                // Chemin de destination
                $file_destination = DEVIS_IMAGE_PATH . $new_filename;
                
                // Déplacer le fichier téléchargé vers le dossier de destination
                if (move_uploaded_file($file_tmp, $file_destination)) {
                    // Insertion de l'image dans la base de données
                    $stmt = $db->prepare("INSERT INTO photos_devis (photo, id_devis) VALUES (?, ?)");
                    $stmt->execute([$new_filename, $id_commande_devis]);
                }
            }
        }


        //On insère les produits dans devis_produit
        foreach ($_POST['dynamic'] as $produit) {
            $reference = $produit[0];
            $designation = $produit[1];
            $fournisseur = $produit[2];
            $paHT = $produit[3];
            $marge = $produit[4];
            $pvHT = $produit[5];
            $pvTTC = $produit[6];
            $etatProduit = $produit[8];
            //var_dump($produit);

            $stmt = $db->prepare("INSERT INTO devis_produit (reference, designation, paHT, marge, pvHT, pvTTC, id_devis, fournisseur, etat) VALUES (?,?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$reference, $designation, $paHT, $marge, $pvHT, $pvTTC, $id_commande_devis, $fournisseur, $etatProduit]);

        }
 
    }else{ //commande
        $stmt = $db->prepare("INSERT INTO commande_devis (cmd_devis_reference, cmd_devis_designation, cmd_devis_datein, cmd_devis_dateout, 
                        membre_id, cmd_devis_prixventettc, cmd_devis_technicien, cmd_devis_dateSouhait, cmd_devis_etat, 
                        cmd_devis_prixHT, cmd_devis_margeT, type_cmd_devis) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?)");
        $stmt->execute([$nomC, $designation, $dateActuelle, $dateP, $id_client, $totalTTC,$id_technicien, 
                        $dateS, $etatC, $totalHT, $totalMarge, $type]);

        $id_commande_devis = $db->lastInsertId();

        //On insère les produits dans commande_produit
        foreach ($_POST['dynamic'] as $produit) {
            $reference = $produit[0];
            $designation = $produit[1];
            $fournisseur = $produit[2];
            $paHT = $produit[3];
            $marge = $produit[4];
            $pvHT = $produit[5];
            $pvTTC = $produit[6];
            $etatProduit = $produit[8];

            //var_dump($produit);

            $stmt = $db->prepare("INSERT INTO commande_produit (reference, designation, paHT, marge, pvHT, pvTTC, etat, id_commande, fournisseur) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$reference, $designation, $paHT, $marge, $pvHT, $pvTTC, $etatProduit, $id_commande_devis, $fournisseur]);
        }
    }

    $stmt = $db->prepare("INSERT INTO sauvgarde_etat_info_commande 
                           (commande_id, sauvgarde_etat, created_by, date_creation, updated_by, date_update)
                           VALUES
                           (:commande_id, :sauvegarde_etat, :id_technicienC , NOW(), :id_technicienU, NOW())");

    $stmt->bindValue(':commande_id', $id_commande_devis);
    $stmt->bindValue(':sauvegarde_etat', $etatC);
    $stmt->bindValue(':id_technicienC', $id_technicien);
    $stmt->bindValue(':id_technicienU', $id_technicien);

    $stmt->execute();

    // Envoi de l'email au technicien 
    /*if(sendEmail($client['membre_nom'], $client['membre_prenom'], $totalTTC, $technicien['membre_mail'], $technicien['membre_nom'], $technicien['membre_prenom'], date('d/m/Y',$dateP) , date('d/m/Y'), date('d/m/Y', $dateS) , $etat_commande, $type)) {
        $success_count++;
        echo('Email envoyé avec succès');
    } else {
        $error_count++;
    }*/

    header("Location: ../profile/profile_client.php?id=".$id_client);
    exit();

}

    // Envoi de l'email au client
function sendEmail($client_nom, $client_prenom,$pvTTC, $technicien_email,$technicien_nom,$technicien_prenom, $date_livraison, $date_creation,$dateSouhait,$etat_commande, $type) {
    if($type == '2'){
        $subject = "=?UTF-8?B?" . base64_encode("Création d'un nouveau devis") . "?="; // Encodage du sujet 

        $body = "Bonjour,\n\n";
        $body .= "Un nouveau devis a été créé pour : $client_nom $client_prenom \n\n";
        $body .= "Prix total TTC : $pvTTC   €\n\n";
        $body .= "Technicien : $technicien_nom $technicien_prenom \n\n";
        $body .= "Date de création : $date_creation \n\n";
        $body .= "Cordialement,\n\n";
        $body .= "A2MI";
    }else{
        $subject = "=?UTF-8?B?" . base64_encode("Création d'une nouvelle commande") . "?="; // Encodage du sujet
    
        $body = "Bonjour,\n\n";
        $body .= "Une nouvelle commande a été créé pour : $client_nom $client_prenom \n\n";
        $body .= "Prix total TTC : $pvTTC   €\n\n";
        $body .= "Technicien : $technicien_nom $technicien_prenom \n\n";
        $body .= "Date de création : $date_creation \n\n";
        $body .= "Date de livraison : $date_livraison \n\n";
        $body .= "Date de livraison souhaitée : $dateSouhait \n\n";
        $body .= "Etat de la commande : $etat_commande \n\n";
        $body .= "Cordialement,\n\n";
        $body .= "A2MI";
    }

    try {
        $mail = new PHPMailer(true);

        // Paramètres du serveur SMTP
        $mail->isSMTP();

        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;  // Adresse email de l'expéditeur
        $mail->Password = SMTP_PASSWORD;           // Mot de passe de l'expéditeur
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;

        // Destinataire
        $mail->setFrom(SENDER_EMAIL, SENDER_NAME);  
        $mail->addAddress($technicien_email); 

        // Contenu de l'email
        $mail->CharSet = 'UTF-8'; // Spécification de l'encodage
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->addAttachment('../conditions-generales-de-vente-2024.pdf');
        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}";
        return false;
    }

}


?>