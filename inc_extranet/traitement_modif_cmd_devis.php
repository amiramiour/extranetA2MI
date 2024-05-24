<?php 
session_start();
require_once('../includes/_functions.php');
require_once('../connexion/config.php');
require_once('../connexion/traitement_connexion.php');

require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dompdf\Dompdf;
use Dompdf\Options;

$idCommande = $_GET['idCommande'];
$idClient = $_GET['idClient'];
$photos_path = array();

//récupérer les informations du client
$query = $pdo->prepare("SELECT * FROM users WHERE users_id = :id_client");
$query->bindParam(':id_client', $idClient, PDO::PARAM_INT);
$query->execute();
$client = $query->fetch(PDO::FETCH_ASSOC);

//récupérer les information du technicien qui a effectué la commande
$id_technicien = $_SESSION['id'];
$query = $pdo->prepare("SELECT users_name, users_firstname, users_mail FROM users WHERE users_id = :id_technicien");
$query->bindParam(':id_technicien', $id_technicien, PDO::PARAM_INT);
$query->execute();
$technicien = $query->fetch(PDO::FETCH_ASSOC);

//on récupère le type de la commande 
$query = $pdo->prepare("SELECT type_cmd_devis FROM cmd_devis WHERE cmd_devis_id = :idCommande");
$query->bindParam(':idCommande', $idCommande, PDO::PARAM_INT);
$query->execute();
$type_cmd_devis = $query->fetchColumn();


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

    $query = $pdo->prepare("SELECT cmd_devis_etat FROM cmd_devis_etats WHERE id_etat_cmd_devis = :etatC");
    $query->bindParam(':etatC', $etatC, PDO::PARAM_INT);
    $query->execute();
    $etat_commande = $query->fetchColumn();

    if ($type_cmd_devis == 1){

        $stmt = $pdo->prepare("UPDATE cmd_devis 
                                    SET cmd_devis_reference = :nomC, 
                                        cmd_devis_designation = :designation, 
                                        cmd_devis_dateout = :dateP, 
                                        users_id = :idClient, 
                                        cmd_devis_prixventettc = :totalTTC,
                                        cmd_devis_technicien = :id_technicien,
                                        cmd_devis_dateSouhait = :dateS, 
                                        cmd_devis_etat = :etatC, 
                                        cmd_devis_prixHT = :totalHT, 
                                        cmd_devis_margeT = :totalMarge 
                                    WHERE cmd_devis_id = :idCommande");

        $stmt->bindValue(':nomC', $nomC);
        $stmt->bindValue(':designation', $designation);
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

        foreach ($_POST['dynamic'] as $produit) {
            $reference = $produit[0];
            $designation = $produit[1];
            $fournisseur = $produit[2];

            $paHT = $produit[3];
            $marge = $produit[4];
            $pvHT = $produit[5];
            $pvTTC = $produit[6];

            $quantiteDemandee = $produit[8];
            $quantiteRecue = $produit[9];

            $etatProduit = $produit[10];
    
            //var_dump($produit);
            
            //On recup les produits de la commande à partir du formulaire, si le produit existe déjà : il sera mis à jour, sinon il sera ajouté
            $stmt = $pdo->prepare("SELECT * 
                                        FROM cmd_produit 
                                        WHERE reference = :reference 
                                        AND id_commande = :idCommande");
            $stmt->bindValue(':reference', $reference);
            $stmt->bindValue(':idCommande', $idCommande);
            $stmt->execute();
            $produitExiste = $stmt->fetch();
    
            if ($produitExiste) {
                //echo "produit existe";
                $stmt = $pdo->prepare("UPDATE cmd_produit
                                           SET reference    = :reference, 
                                               designation  = :designation, 
                                               paHT         = :paHT, 
                                               marge        = :marge, 
                                               pvHT         = :pvHT, 
                                               pvTTC        = :pvTTC, 
                                               etat         = :etatProduit, 
                                               id_commande  = :idCommande,
                                               fournisseur  = :fournisseur,
                                               quantite_demandee = :quantiteDemandee,
                                               quantite_recue   = :quantiteRecue
                                           WHERE reference  = :reference 
                                           AND id_commande  = :idCommande");
    
                $stmt->bindValue(':reference', $reference);
                $stmt->bindValue(':designation', $designation);
                $stmt->bindValue(':paHT', $paHT);
                $stmt->bindValue(':marge', $marge);
                $stmt->bindValue(':pvHT', $pvHT);
                $stmt->bindValue(':pvTTC', $pvTTC);
                $stmt->bindValue(':etatProduit', $etatProduit);
                $stmt->bindValue(':idCommande', $idCommande);
                $stmt->bindValue(':fournisseur', $fournisseur);
                $stmt->bindValue(':quantiteDemandee', $quantiteDemandee);
                $stmt->bindValue(':quantiteRecue', $quantiteRecue);
    
                $stmt->execute();
            } else {
                $stmt = $pdo->prepare("INSERT INTO cmd_produit (reference, designation, paHT, marge, pvHT, pvTTC, etat, id_commande, fournisseur, quantite_demandee, quantite_recue) 
                                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)");
                $stmt->execute([$reference, $designation, $paHT, $marge, $pvHT, $pvTTC, $etatProduit, $idCommande, $fournisseur, $quantiteDemandee, $quantiteDemandee]);
            }
        }
    }else{ //Devis
        $commentaire = $_POST['commentaire'];

        $stmt = $pdo->prepare("UPDATE cmd_devis 
                                    SET cmd_devis_reference     = :nomC, 
                                        cmd_devis_designation   = :designation, 
                                        cmd_devis_dateout       = :dateP, 
                                        users_id                = :idClient, 
                                        cmd_devis_prixventettc  = :totalTTC,
                                        cmd_devis_technicien    = :id_technicien,
                                        cmd_devis_dateSouhait   = :dateS, 
                                        cmd_devis_etat          = :etatC, 
                                        cmd_devis_prixHT        = :totalHT, 
                                        cmd_devis_margeT        = :totalMarge,
                                        commentaire             = :commentaire
                                    WHERE cmd_devis_id = :idCommande");

        $stmt->bindValue(':nomC', $nomC);
        $stmt->bindValue(':designation', $designation);
        $stmt->bindValue(':dateP', $dateP);
        $stmt->bindValue(':idClient', $idClient);
        $stmt->bindValue(':totalTTC', $totalTTC);
        $stmt->bindValue(':id_technicien', $id_technicien);
        $stmt->bindValue(':dateS', $dateS);
        $stmt->bindValue(':etatC', $etatC);
        $stmt->bindValue(':totalHT', $totalHT);
        $stmt->bindValue(':totalMarge', $totalMarge);
        $stmt->bindValue(':commentaire', $commentaire);
        $stmt->bindValue(':idCommande', $idCommande);

        $stmt->execute();

        foreach ($_POST['dynamic'] as $produit) {
            $reference = $produit[0];
            $designation = $produit[1];
            $fournisseur = $produit[2];

            $paHT = $produit[3];
            $marge = $produit[4];
            $pvHT = $produit[5];
            $pvTTC = $produit[6];

            $quantiteDemandee = $produit[8];
            $etatProduit = $produit[9];
    
            //var_dump($produit);
    
            $stmt = $pdo->prepare("SELECT * 
                                        FROM devis_produit 
                                        WHERE reference = :reference 
                                        AND id_devis = :idCommande");
            $stmt->bindValue(':reference', $reference);
            $stmt->bindValue(':idCommande', $idCommande);
            $stmt->execute();
            $produitExiste = $stmt->fetch();
    
            if ($produitExiste) {
                //echo "produit existe";
                $stmt = $pdo->prepare("UPDATE devis_produit 
                                       SET reference = :reference, 
                                           designation = :designation, 
                                           paHT = :paHT, 
                                           marge = :marge, 
                                           pvHT = :pvHT, 
                                           pvTTC = :pvTTC, 
                                           etat = :etatProduit, 
                                           id_devis = :idCommande,
                                           fournisseur = :fournisseur,
                                           quantite_demandee = :quantiteDemandee
                                       WHERE reference = :reference AND id_devis = :idCommande");
    
                $stmt->bindValue(':reference', $reference);
                $stmt->bindValue(':designation', $designation);
                $stmt->bindValue(':paHT', $paHT);
                $stmt->bindValue(':marge', $marge);
                $stmt->bindValue(':pvHT', $pvHT);
                $stmt->bindValue(':pvTTC', $pvTTC);
                $stmt->bindValue(':etatProduit', $etatProduit);
                $stmt->bindValue(':idCommande', $idCommande);
                $stmt->bindValue(':fournisseur', $fournisseur);
                $stmt->bindValue(':quantiteDemandee', $quantiteDemandee);
    
                $stmt->execute();
            } else {
                $stmt = $pdo->prepare("INSERT INTO devis_produit (reference, designation, paHT, marge, pvHT, pvTTC, etat, id_devis, fournisseur, quantite_demandee) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?)");
                $stmt->execute([$reference, $designation, $paHT, $marge, $pvHT, $pvTTC, $etatProduit, $idCommande, $fournisseur, $quantiteDemandee]);
            }
        }
        if(!empty($_FILES['photos'])){
            $photos = $_FILES['photos'];
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
                    $file_destination = '../img/images_devis/' . $new_filename;
                    
                    // Déplacer le fichier téléchargé vers le dossier de destination
                    if (move_uploaded_file($file_tmp, $file_destination)) {
                        // Insertion de l'image dans la base de données
                        $stmt = $pdo->prepare("INSERT INTO devis_photo (photo, id_devis) VALUES (?, ?)");
                        $stmt->execute([$new_filename, $idCommande]);
                        $photos_path[] = $file_destination;
                    }
                }
            }
        }

    }

    $stmt = $pdo->prepare("SELECT cmd_devis_datein, cmd_devis_technicien FROM cmd_devis WHERE cmd_devis_id = :commande_id");
    $stmt->bindValue(':commande_id', $idCommande);
    $stmt->execute();
    $commande = $stmt->fetch(PDO::FETCH_ASSOC);

    
    // Récupération des valeurs de la commande
    $cmd_datein = $commande['cmd_devis_datein'];
    $cmd_technicien = $commande['cmd_devis_technicien'];

    // Préparation de la requête d'insertion
    $stmt = $pdo->prepare("INSERT INTO cmd_etat_info_save 
                        (commande_id, sauvgarde_etat, created_by, date_creation, updated_by, date_update)
                        VALUES
                        (:commande_id, :sauvegarde_etat, :created_by, :date_creation, :updated_by, NOW())");

    // Bind des valeurs
    $stmt->bindValue(':commande_id', $idCommande);
    $stmt->bindValue(':sauvegarde_etat', $etatC);
    $stmt->bindValue(':created_by', $cmd_technicien);
    $stmt->bindValue(':date_creation', date('Y-m-d H:i:s', $cmd_datein));
    $stmt->bindValue(':updated_by', $id_technicien);

    // Exécution de la requête
    $stmt->execute();

    // Générer le PDF de la commande / devis
    
    $options = new Options();
    $options->set('defaultFont', 'Courier');
    $options->set('isRemoteEnabled', true); 
    $dompdf = new Dompdf($options);

    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>A2MI | Fiche</title>
        <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #333333;
        }
        .invoice {
            padding: 20px;
            background: white;
            border-radius: 5px;
            margin: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .invoice-company {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 35px;
            color: #cc0066;
            text-align: center;
            position: relative;
            border-radius: 5px;
            padding: 20px;
        }
        .invoice-company img {
            position: absolute;
            top: -10px;
            left: 10px;
            width: 50px;
            height: 50px;
        }
        
        .invoice-number {
            font-size: 18px;
            position: absolute;
            top: -10px;
            right: 10px;
            background-color: #cc0066;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .invoice-header > div {
            flex-basis: 30%;
        }
        .invoice-from,
        .invoice-to {
            border: 2px solid #cc0066;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .section-header {
            font-size: 18px;
            color: #cc0066;
            margin-bottom: 10px;
        }
        .invoice-content {
            margin-top: 20px;
        }
        .table-invoice {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            margin-left: auto;
            margin-right: auto;
        }
        .table-invoice th {
            background-color: #cc0066;
            color: white;
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .table-invoice td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .invoice-footer {
            text-align: center;
            margin-top: 20px;
        }
        .invoice-footer p {
            margin: 0;
        }
        .invoice-footer .fw-bold {
            font-weight: bold;
        }
        
        .price-grid .price-label {
            text-align: left;
            font-weight: bold;
        }
        .price-grid .price-value {
            text-align: right;
            font-weight: bold;
        }
    </style>
    </head>
    <body>
        <div class="invoice">
            <div class="invoice-company row">
                <img src="http://localhost/Extranet-root/admin/img/logo/a2milogo.png" alt="Logo A2MI">
                <?php if ($type_cmd_devis == '1'): ?>
                    <div class="invoice-number">Commande n° <?= $idCommande ?></div>
                <?php else: ?>
                    <div class="invoice-number">Devis n° <?= $idCommande ?></div>
                <?php endif; ?>
            </div>

            <div class="invoice-header row">
                    <div class="invoice-from col-md-6">
                        <div class="section-header">De</div>
                        <address>
                            <strong>A2MI-Informatique</strong><br>
                            10-14 rue Jean Perrin<br>
                            17000 La Rochelle<br>
                            Tél : 09 51 52 42 86
                        </address>
                    </div>
                    <div class="invoice-to col-md-6">
                        <div class="section-header">À</div>
                        <address>
                            <strong><?= $client['users_name'] . ' ' . $client['users_firstname'] ?></strong><br>
                            <?= $client['users_address'] ?><br>
                            <?php if (!empty($client['users_address_compl'])): ?>
                                <?= $client['users_address_compl'] ?><br>
                            <?php endif; ?>
                            <?= $client['users_postcode'] . ' ' . $client['users_city'] ?><br>
                            Tél: <?= $client['users_mobile'] ?><br>
                        </address>
                    </div>
                <div class="invoice-dates">
                    <div class="section-header"></div>
                    <div class="invoice-date">
                        <strong>Date de livraison :</strong>
                        <span><?= date('Y/m/d', $dateP) ?></span>
                    </div>
                    <div class="invoice-date">
                        <strong>Date de livraison souhaitée :</strong>
                        <span><?= date('Y/m/d', $dateS) ?></span>
                    </div>
                    <div class="invoice-date">
                        <strong>État :</strong>
                        <span><?= $etat_commande ?></span>
                    </div>
                </div>
            </div>
                
            <div class="invoice-content">
                <div class="table-responsive">
                    <table class="table table-invoice">
                        <thead>
                        <tr>
                            <th>Référence</th>
                            <th>pvHT</th>
                            <th>pvTTC</th>
                            <th>Statut</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($_POST['dynamic'] as $produit): ?>
                            <tr>
                                <td><?= $produit[0] ?></span> </td>
                                <td><?= $produit[5] ?></td>
                                <td><?= $produit[6] ?></td>
                                <?php if ($type_cmd_devis == 1): ?>
                                    <td><?= $produit[10] == "commande" ? "Commandé" : "Reçu" ?></td>
                                <?php else: ?>
                                    <td><?= $produit[9] == "commande" ? "Commandé" : "Reçu" ?></td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="m-3">
                    <strong>Total de la commande</strong>
                
                    <div class="invoice-price">
                        <div class="price-grid">
                            <div class="price-label">Prix de vente HT</div>
                            <div class="price-value"><?= $totalHT ?> €</div>
                        </div>

                        <div class="price-grid">
                            <div class="price-label">TVA</div>
                            <div class="price-value">20 %</div>
                        </div>

                        <div class="price-grid">
                            <div class="price-label">Prix TTC</div>
                            <div class="price-value"><?= $totalTTC ?> €</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="invoice-footer">
                <p class="text-center mb-5px fw-bold">
                    MERCI DE NOUS FAIRE CONFIANCE
                </p>
                <p class="text-center">
                    <span class="me-10px"><i class="fa fa-fw fa-lg fa-globe"></i> https://www.a2mi-info.com</span>
                    <span class="me-10px"><i class="fa fa-fw fa-lg fa-phone-volume"></i> 09 51 52 42 86</span>
                    <span class="me-10px"><i class="fa fa-fw fa-lg fa-envelope"></i> contact@a2mi-info.fr</span>
                </p>
            </div>
        </div>
    </body>
    </html>
    <?php
    $html = ob_get_clean();

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $pdf = $dompdf->output();
    
    if($type_cmd_devis == 1 && $etatC == 4){ //si c'est une commande et qu'elle est à livrer 
        sendEmail($type_cmd_devis,$client["users_mail"],$client['users_name'], $client['users_firstname'], $totalTTC, $technicien['users_mail'], $technicien['users_name'], $technicien['users_firstname'], date('Y/m/d',$dateP) , date('Y/m/d'), date('Y/m/d', $dateS) , $etat_commande, $pdf, true);
    }else{
        if($type_cmd_devis == 2){
            sendEmail($type_cmd_devis,$client["users_mail"],$client['users_name'], $client['users_firstname'], $totalTTC, $technicien['users_mail'], $technicien['users_name'], $technicien['users_firstname'], date('Y/m/d',$dateP) , date('Y/m/d'), date('Y/m/d', $dateS) , $etat_commande, $pdf, true, $photos_path);
        }
    }

    //envoi du mail au technicien
    sendEmail($type_cmd_devis,$client["users_mail"],$client['users_name'], $client['users_firstname'], $totalTTC, $technicien['users_mail'], $technicien['users_name'], $technicien['users_firstname'], date('Y/m/d',$dateP) , date('Y/m/d'), date('Y/m/d', $dateS) , $etat_commande, $pdf, false,$photos_path);

    header("Location: ../profile.php?id=".$idClient);
}
function sendEmail($type, $mail_client,$client_nom, $client_prenom,$pvTTC, $technicien_email,$technicien_nom,$technicien_prenom, $date_livraison, $date_creation,$dateSouhait,$etat_commande, $pdf, $isClient,$photos_path = null) {
    if($type == 1){
        if($isClient){ //on lui envoie un mail pour lui dire que sa commande lui sera livrée 

            $subject = "=?UTF-8?B?" . base64_encode("Livraison de votre commande") . "?="; // Encodage du sujet
            
            $body = "Bonjour $client_nom $client_prenom,\n\n";
            $body .= "Votre commande vous sera livré\n\n";
            $body .= "Prix total TTC : $pvTTC €\n\n";
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
            $body .= "Prix total TTC : $pvTTC €\n\n";
            $body .= "Technicien : $technicien_nom $technicien_prenom \n\n";
            $body .= "Date de création : $date_creation \n\n";
            $body .= "Date de livraison : $date_livraison \n\n";
            $body .= "Date de livraison souhaitée : $dateSouhait \n\n";
            $body .= "Etat de la commande : $etat_commande \n\n";
            $body .= "Cordialement,\n\n";
            $body .= "A2MI";
        }
    }else{ //c'est un devis on envoi un mail au client et au Technicien
        if($isClient){
            $subject = "=?UTF-8?B?" . base64_encode("Modification de votre devis") . "?="; // Encodage du sujet
            
            $body = "Bonjour $client_nom $client_prenom,\n\n";
            $body .= "Votre devis a été modifié\n\n";
            $body .= "Prix total TTC : $pvTTC €\n\n";
            $body .= "Technicien : $technicien_nom $technicien_prenom \n\n";
            $body .= "Date de création : $date_creation \n\n";
            $body .= "Etat du devis : $etat_commande \n\n";
            $body .= "Cordialement,\n\n";
            $body .= "A2MI";
        }else{
            $subject = "=?UTF-8?B?" . base64_encode("Modification du devis") . "?="; // Encodage du sujet
            
            $body = "Bonjour,\n\n";
            $body .= "Le devis de : $client_nom $client_prenom a été modifié\n\n";
            $body .= "Prix total TTC : $pvTTC €\n\n";
            $body .= "Technicien : $technicien_nom $technicien_prenom \n\n";
            $body .= "Date de création : $date_creation \n\n";
            $body .= "Date de livraison souhaitée : $dateSouhait \n\n";
            $body .= "Etat du devis : $etat_commande \n\n";
            $body .= "Cordialement,\n\n";
            $body .= "A2MI";
        }
    }
    try {
        // Paramètres du serveur SMTP
        $mail = new PHPMailer(true);
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

        if ($photos_path !== null) {
            foreach ($photos_path as $path) {
                $mail->addAttachment($path);
            }
        }

        if ($pdf !== null) {
            $mail->addStringAttachment($pdf, 'facture.pdf');
        }

        $mail->addAttachment(CGV_PATH);
        $mail->send();
        echo("email envoyé avec succès");
        return true;
    } catch (Exception $e) {
        echo "Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}";
        return false;
    }

}
?>