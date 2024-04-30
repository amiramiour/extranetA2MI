<?php
require_once '../config.php';
session_start();
// Vérifier si l'utilisateur est connecté et est un technicien
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_mail'])  || $_SESSION['user_type'] === 'client') {
    // Si l'utilisateur n'est pas connecté ou est un client, redirigez-le ou affichez un message d'erreur
    header("Location: ../connexion.php");
    exit;
}

include '../ConnexionBD.php';
$pdo = connexionbdd();

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
$query = $pdo->prepare("SELECT membre_nom, membre_prenom, membre_mail FROM membres WHERE membre_id = :id_client");
$query->bindParam(':id_client', $id_client, PDO::PARAM_INT);
$query->execute();
$client = $query->fetch(PDO::FETCH_ASSOC);

//récupérer les information du technicien qui a effectué la commande
//id du technicien connecté (session)
$id_technicien = $_SESSION['user_id'];
$query = $pdo->prepare("SELECT membre_nom, membre_prenom, membre_mail FROM membres WHERE membre_id = :id_technicien");
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

    $date_livraison = $_POST['dateP'];
    $date_souhait = $_POST['dateS'];

    $dateP = strtotime($date_livraison);
    $dateS = strtotime($date_souhait);

    $query = $pdo->prepare("SELECT commande_etat FROM commande_etats WHERE id_etat_cmd = :etatC");
    $query->bindParam(':etatC', $etatC, PDO::PARAM_INT);
    $query->execute();
    $etat_commande = $query->fetchColumn();

    $dateActuelle = time();
    
    $stmt = $pdo->prepare("INSERT INTO commande (cmd_reference, cmd_designation, cmd_datein, cmd_dateout, 
                        membre_id, cmd_prixventettc, cmd_livreur, cmd_dateSouhait, cmd_etat, 
                        cmd_prixHT, cmd_margeT) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)");
    $stmt->execute([$nomC, $designation, $dateActuelle, $dateP, $id_client, $totalTTC,$id_technicien, 
                    $dateS, $etatC, $totalHT, $totalMarge]);
    
    $id_commande = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO sauvgarde_etat_info_commande 
                           (commande_id, sauvgarde_etat, created_by, date_creation, updated_by, date_update)
                           VALUES
                           (:commande_id, :sauvegarde_etat, 1, NOW(), 1, NOW())");

    $stmt->bindValue(':commande_id', $id_commande);
    $stmt->bindValue(':sauvegarde_etat', $etatC);

    $stmt->execute();

    foreach ($_POST['dynamic'] as $produit) {
        $reference = $produit[0];
        $designation = $produit[1];
        $fournisseur = $produit[2];
        $paHT = $produit[3];
        $marge = $produit[4];
        $pvHT = $produit[5];
        $pvTTC = $produit[6];
        $etatProduit = $produit[8];

        $stmt = $pdo->prepare("INSERT INTO commande_produit (reference, designation, paHT, marge, pvHT, pvTTC, etat, id_commande, fournisseur) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$reference, $designation, $paHT, $marge, $pvHT, $pvTTC, $etatProduit, $id_commande, $fournisseur]);

    }

    
    /*header("Location: commandes_client.php?id=$id_client");
    exit();*/
    
    //echo "Commande ajoutée avec succès";

    // Envoi de l'email au technicien 
    if(sendEmail($client['membre_nom'], $client['membre_prenom'], $totalTTC, $technicien['membre_mail'], $technicien['membre_nom'], $technicien['membre_prenom'], date('d/m/Y',$dateP) , date('d/m/Y'), date('d/m/Y', $dateS) , $etat_commande)) {
        $success_count++;
        echo('Email envoyé avec succès');
    } else {
        $error_count++;
    }

    header("Location: commandes_client.php?id=$id_client");
    exit();

}

    // Envoi de l'email au client
function sendEmail($client_nom, $client_prenom,$pvTTC, $technicien_email,$technicien_nom,$technicien_prenom, $date_livraison, $date_creation,$dateSouhait,$etat_commande) {
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

        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}";
        return false;
    }

}


?>