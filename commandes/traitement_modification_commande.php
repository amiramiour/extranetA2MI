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


$idCommande = $_GET['idcommande'];
$idClient = $_GET['idclient'];

//récupérer les informations du client
$query = $pdo->prepare("SELECT membre_nom, membre_prenom, membre_mail FROM membres WHERE membre_id = :id_client");
$query->bindParam(':id_client', $idClient, PDO::PARAM_INT);
$query->execute();
$client = $query->fetch(PDO::FETCH_ASSOC);

//récupérer les information du technicien qui a effectué la commande
$id_technicien = $_SESSION['user_id']; // à remplacer par l'id du technicien connecté (session)
$query = $pdo->prepare("SELECT membre_nom, membre_prenom, membre_mail FROM membres WHERE membre_id = :id_technicien");
$query->bindParam(':id_technicien', $id_technicien, PDO::PARAM_INT);
$query->execute();
$technicien = $query->fetch(PDO::FETCH_ASSOC);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomC = $_POST['nomC'];
    $designation = $_POST['designationC'];
    $dateP = strtotime($_POST['dateP']);
    $dateS = strtotime($_POST['dateS']);
    $etatC = $_POST['etatC']; 
    $totalHT = $_POST['totalHT'];
    $totalTTC = $_POST['totalTTC'];
    $totalMarge = $_POST['totalMarge'];

    $dateActuelle = time();

    $query = $pdo->prepare("SELECT commande_etat FROM commande_etats WHERE id_etat_cmd = :etatC");
    $query->bindParam(':etatC', $etatC, PDO::PARAM_INT);
    $query->execute();
    $etat_commande = $query->fetchColumn();

    $stmt = $pdo->prepare("UPDATE commande 
                           SET cmd_reference = :nomC, 
                               cmd_designation = :designation, 
                               cmd_datein = :dateActuelle, 
                               cmd_dateout = :dateP, 
                               membre_id = :idClient, 
                               cmd_prixventettc = :totalTTC,
                               cmd_livreur = :id_technicien,
                               cmd_dateSouhait = :dateS, 
                               cmd_etat = :etatC, 
                               cmd_prixHT = :totalHT, 
                               cmd_margeT = :totalMarge 
                           WHERE cmd_id = :idCommande");

    $stmt->bindValue(':nomC', $nomC);
    $stmt->bindValue(':designation', $designation);
    $stmt->bindValue(':dateActuelle', $dateActuelle);
    $stmt->bindValue(':dateP', $dateP);
    $stmt->bindValue(':idClient', $idClient);
    $stmt->bindValue(':totalTTC', $totalTTC);
    $stmt->bindValue(':id_technicien', $id_technicien);
    $stmt->bindValue(':dateS', $dateS);
    $stmt->bindValue(':etatC', $etatC);
    $stmt->bindValue(':totalHT', $totalHT);
    $stmt->bindValue(':totalMarge', $totalMarge);
    $stmt->bindValue(':idCommande', $idCommande);

    $stmt->execute();

    $stmt = $pdo->prepare("SELECT cmd_datein, cmd_livreur FROM commande WHERE cmd_id = :commande_id");
    $stmt->bindValue(':commande_id', $idCommande);
    $stmt->execute();
    $commande = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si la commande existe
    if ($commande) {
        // Récupération des valeurs de la commande
        $cmd_datein = $commande['cmd_datein'];
        $cmd_livreur = $commande['cmd_livreur'];

        // Préparation de la requête d'insertion
        $stmt = $pdo->prepare("INSERT INTO sauvgarde_etat_info_commande 
                           (commande_id, sauvgarde_etat, created_by, date_creation, updated_by, date_update)
                           VALUES
                           (:commande_id, :sauvegarde_etat, :created_by, :date_creation, 1, NOW())");

        // Bind des valeurs
        $stmt->bindValue(':commande_id', $idCommande);
        $stmt->bindValue(':sauvegarde_etat', $etatC);
        $stmt->bindValue(':created_by', $cmd_livreur); // Utilisation de cmd_livreur pour created_by
        $stmt->bindValue(':date_creation', date('Y-m-d H:i:s', $cmd_datein));

        // Exécution de la requête
        $stmt->execute();
    } else {
        // Gérer le cas où la commande n'existe pas
        echo "La commande avec l'ID $idCommande n'existe pas.";
    }

    //récupérer les produits de la commande à partir du formulaire, si le produit existe déjà : il sera mis à jour, sinon il sera ajouté
    foreach ($_POST['dynamic'] as $produit) {
        $reference = $produit[0];
        $designation = $produit[1];
        $fournisseur = $produit[2];
        $paHT = $produit[3];

        $marge = $produit[4];
        $pvHT = $produit[5];

        $pvTTC = $produit[6];
        $etatProduit = $produit[8];

        var_dump($produit);
        

         //on récupère l'id du fournisseur
         /*$stmt = $pdo->prepare("SELECT DISTINCT idFournisseur FROM fournisseur WHERE nomFournisseur = :fournisseur");
         $stmt->bindValue(':fournisseur', $fournisseur);
         $stmt->execute();
         $idFournisseur = $stmt->fetchColumn();*/


        $stmt = $pdo->prepare("SELECT * FROM commande_produit WHERE reference = :reference AND id_commande = :idCommande");
        $stmt->bindValue(':reference', $reference);
        $stmt->bindValue(':idCommande', $idCommande);
        $stmt->execute();
        $produitExiste = $stmt->fetch();

        if ($produitExiste) {
            //echo "produit existe";
            $stmt = $pdo->prepare("UPDATE commande_produit 
                                   SET reference = :reference, 
                                       designation = :designation, 
                                       paHT = :paHT, 
                                       marge = :marge, 
                                       pvHT = :pvHT, 
                                       pvTTC = :pvTTC, 
                                       etat = :etatProduit, 
                                       id_commande = :idCommande,
                                        fournisseur = :fournisseur
                                   WHERE reference = :reference AND id_commande = :idCommande");

            $stmt->bindValue(':reference', $reference);
            $stmt->bindValue(':designation', $designation);
            $stmt->bindValue(':paHT', $paHT);
            $stmt->bindValue(':marge', $marge);
            $stmt->bindValue(':pvHT', $pvHT);
            $stmt->bindValue(':pvTTC', $pvTTC);
            $stmt->bindValue(':etatProduit', $etatProduit);
            $stmt->bindValue(':idCommande', $idCommande);
            $stmt->bindValue(':fournisseur', $fournisseur);

            $stmt->execute();
        } else {
            $stmt = $pdo->prepare("INSERT INTO commande_produit (reference, designation, paHT, marge, pvHT, pvTTC, etat, id_commande, fournisseur) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$reference, $designation, $paHT, $marge, $pvHT, $pvTTC, $etatProduit, $idCommande, $fournisseur]);
        }
    }

    if($etatC == '4') { //si la commande est livrée on envoie un mail au client 
        sendEmail($idClient,$client["membre_mail"],$client['membre_nom'], $client['membre_prenom'], $totalTTC, $technicien['membre_mail'], $technicien['membre_nom'], $technicien['membre_prenom'], date('d/m/Y',$dateP) , date('d/m/Y'), date('d/m/Y', $dateS) , $etat_commande,true);
    }

    sendEmail($idClient,$client["membre_mail"],$client['membre_nom'], $client['membre_prenom'], $totalTTC, $technicien['membre_mail'], $technicien['membre_nom'], $technicien['membre_prenom'], date('d/m/Y',$dateP) , date('d/m/Y'), date('d/m/Y', $dateS) , $etat_commande,false);

    header("Location: commandes_client.php?id=$idClient");
}
function sendEmail($idClient, $mail_client,$client_nom, $client_prenom,$pvTTC, $technicien_email,$technicien_nom,$technicien_prenom, $date_livraison, $date_creation,$dateSouhait,$etat_commande,$isClient) {
    
    if($isClient){ //on lui envoie un mail pour lui dire que sa commande a été livré
        $subject = "=?UTF-8?B?" . base64_encode("Livraison de votre commande") . "?="; // Encodage du sujet
        
        $body = "Bonjour $client_nom $client_prenom,\n\n";
        $body .= "Votre commande vous sera livrée\n\n";
        $body .= "Prix total TTC : $pvTTC   €\n\n";
        $body .= "Technicien : $technicien_nom $technicien_prenom \n\n";
        $body .= "Date de création : $date_creation \n\n";
        $body .= "Date de livraison : $date_livraison \n\n";
        $body .= "Date de livraison souhaitée : $dateSouhait \n\n";
        $body .= "Etat de la commande : $etat_commande \n\n";
        $body .= "Cordialement,\n\n";
        $body .= "A2MI";

    }else{
        $subject = "=?UTF-8?B?" . base64_encode("Modification de la commande") . "?="; // Encodage du sujet
        
        $body = "Bonjour,\n\n";
        $body .= "La commande de : $client_nom $client_prenom a été modifiée\n\n";
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
        if ($isClient) {
            $mail->addAddress($mail_client);    // Adresse e-mail du client
        } else {
            $mail->addAddress($technicien_email);    // Adresse e-mail du technicien
        }

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