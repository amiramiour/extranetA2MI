<?php
session_start();
require_once '../connexion/config.php';
include "../connexion/traitement_connexion.php";

// Inclure la classe PHPMailer
require '../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function connexionbdd()
{
    global $pdo;
    return $pdo;
}

// Récupération de l'ID et de l'adresse e-mail du technicien à partir de la session
$technicien_id = $_SESSION['id'];
$technicien_email = $_SESSION['mail'];

// Récupération de l'ID du client depuis l'URL
$users_id = $_GET['id'];

    // Vérifier si la méthode de requête est POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            $db = connexionbdd();


// Récupération du nom et du prénom du technicien à partir de son ID
            $query_technicien_info = "SELECT users_name, users_firstname FROM users WHERE users_id = :technicien_id";
            $stmt_technicien_info = $db->prepare($query_technicien_info);
            $stmt_technicien_info->bindParam(':technicien_id', $technicien_id);
            $stmt_technicien_info->execute();
            $technicien_info = $stmt_technicien_info->fetch(PDO::FETCH_ASSOC);

// Vérification si les informations du technicien ont été récupérées avec succès
            if ($technicien_info) {
                // Assignation du nom et du prénom du technicien
                $technicien_nom = $technicien_info['users_name'];
                $technicien_prenom = $technicien_info['users_firstname'];
            } else {
                // Gestion de l'erreur si les informations du technicien ne sont pas disponibles
                echo "Erreur : Impossible de récupérer les informations du technicien.";
                exit();
            }



            // Récupération du nom, prénom et e-mail du client à partir de son ID
            $query_client_info = "SELECT users_name, users_firstname, users_mail FROM users WHERE users_id = :users_id";
            $stmt_client_info = $db->prepare($query_client_info);
            $stmt_client_info->bindParam(':users_id', $users_id);
            $stmt_client_info->execute();
            $client_info = $stmt_client_info->fetch(PDO::FETCH_ASSOC);



            // Traiter les données du formulaire
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

            // Récupération de la date de facturation si elle est différée
            $facturation = $_POST['facturation'];
            $dateFacturation = null;

            if ($facturation === 'differee' && isset($_POST['date_differee'])) {
                // Récupérer la date depuis le formulaire
                $date_differee = $_POST['date_differee'];
                // Convertir la date en timestamp UNIX
                $dateFacturation = strtotime($date_differee);
            }


            $paiement = $_POST['paiement'];
            $heureArrive = $_POST['heure_arrive'];
            $heureDepart = $_POST['heure_depart'];
            $commentaire = $_POST['commentaire'];

            // Récupération de la date d'intervention depuis le formulaire
            $dateIntervention = $_POST['date_intervention'];
            //UNIX TIMESTAMP
            $dateInterventionFormat = strtotime($dateIntervention);

            // Insertion dans la table `bi` (Bon d'intervention)
            $query = $db->prepare("INSERT INTO bi (users_id, bi_technicien, bi_facture, bi_garantie, bi_contrat, bi_service, bi_envoi, bi_facturation, bi_datefacturation, bi_paiement, bi_datein, bi_heurearrive, bi_heuredepart, bi_commentaire, bi_regle, bi_date) 
            VALUES (:users_id, :bi_technicien, :facturer, :garantie, :contrat, :service, :envoi, :facturation, :dateFacturation, :paiement, UNIX_TIMESTAMP(), :heureArrive, :heureDepart, :commentaire, :regle, :dateIntervention)");

            $query->bindParam(':users_id', $users_id);
            $query->bindParam(':bi_technicien', $technicien_id);
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
            $query->bindParam(':dateIntervention', $dateInterventionFormat);


            $query->execute();

            // Récupérer l'ID du bon d'intervention inséré
            $bi_id = $db->lastInsertId();

            // Récupération des détails du BI
            // Récupérer les informations du bon d'intervention après insertion
            $query_bi_details = $db->prepare("SELECT * FROM bi WHERE bi_id = :bi_id");
            $query_bi_details->bindParam(':bi_id', $bi_id);
            $query_bi_details->execute();
            $bi_details = $query_bi_details->fetch(PDO::FETCH_ASSOC);


            // Insérer les données dans la table `intervention`
            $selectedIntervention = $_POST['selectedIntervention'];
            $nbPieces = $_POST['nb_pieces'];
            $prixUnitaire = $_POST['prixUn'];


            $count = count($selectedIntervention); // Le nombre d'interventions ajoutées dynamiquement
            for ($i = 0; $i < $count; $i++) {
                $selectedInterventionValue = $selectedIntervention[$i];
                $nb_piece = $nbPieces[$i];
                $prix_un = $prixUnitaire[$i];

                $total = $nb_piece * $prix_un; // Calculer le total

                if ($paiement === 'cheque') {
                    $total = $total + 1;
                }

                // Insertion dans la table `intervention`
                $query_intervention = $db->prepare("INSERT INTO intervention (inter_intervention, inter_nbpiece, inter_prixunit, inter_total, bi_id) 
        VALUES (:selectedIntervention, :nb_piece, :prix_un, :total, :bi_id)");

                // Liaison des paramètres pour chaque intervention
                $query_intervention->bindParam(':selectedIntervention', $selectedInterventionValue);
                $query_intervention->bindParam(':nb_piece', $nb_piece);
                $query_intervention->bindParam(':prix_un', $prix_un);
                $query_intervention->bindParam(':total', $total);
                $query_intervention->bindParam(':bi_id', $bi_id);

                $query_intervention->execute();

                // Récupérer toutes les interventions liées à ce bon d'intervention
                $query_all_interventions = $db->prepare("SELECT * FROM intervention WHERE bi_id = :bi_id");
                $query_all_interventions->bindParam(':bi_id', $bi_id);
                $query_all_interventions->execute();
                $all_interventions = $query_all_interventions->fetchAll(PDO::FETCH_ASSOC);

                // Vous pouvez également envoyer un e-mail pour chaque intervention ici, si nécessaire
            }

            // Compteur de succès et d'erreurs
            $success_count = 0;
            $error_count = 0;

            // Vérifier si l'option "envoie_mail" est cochée
            $envoie_mail = isset($_POST['envoie_mail']);

            // Envoi d'e-mails de confirmation au client et au technicien
            // Si l'option "envoie_courrier" est cochée, envoi à la fois par courrier et par e-mail
            if ($envoie_mail) {
                // Envoyer l'e-mail uniquement si la case "mail" est cochée
                if (sendBiCreationEmail($users_id, $selectedIntervention, $technicien_email, $total, $client_info['users_name'], $client_info['users_firstname'], $bi_details, $all_interventions, true, $technicien_nom, $technicien_prenom)) {
                    $success_count++;
                } else {
                    $error_count++;
                }
            }

            // Toujours envoyer un e-mail au technicien
            if (sendBiCreationEmail($technicien_id, $selectedIntervention, $technicien_email, $total, $client_info['users_name'], $client_info['users_firstname'], $bi_details, $all_interventions, false, $technicien_nom, $technicien_prenom)) {
                $success_count++;
            } else {
                $error_count++;
            }

            // Affichage du résultat
            if ($success_count > 0 && $error_count == 0) {
                echo "Enregistrement du BI effectué avec succès. Les e-mails ont été envoyés avec succès.";
            } elseif ($success_count > 0 && $error_count > 0) {
                echo "Enregistrement du BI effectué avec succès. Certains e-mails ont été envoyés avec succès, mais il y a eu des erreurs lors de l'envoi de certains e-mails.";
            } else {
                echo "Enregistrement du BI effectué, mais aucun e-mail n'a été envoyé. Veuillez vérifier les erreurs.";
            }

            // Redirection vers bonIntervention.php après traitement
            header("Location: ../list_bi.php");
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


// Fonction pour envoyer un e-mail de création de bon d'intervention
function sendBiCreationEmail($users_id, $selectedIntervention, $technicien_email, $total, $client_nom, $client_prenom, $bi_details, $all_interventions, $is_client, $technicien_nom, $technicien_prenom){
    // Récupérer l'adresse e-mail du client depuis la base de données
    $db = connexionbdd();
    $query = $db->prepare("SELECT users_mail, bi_datein 
                                FROM users 
                                INNER JOIN bi ON users.users_id = bi.users_id 
                                WHERE users.users_id = ?");
    $query->execute([$users_id]);
    $result = $query->fetch(PDO::FETCH_ASSOC);

// Composition du contenu de l'e-mail

    $client_email = $result['users_mail'];
    $bi_datein = date('d/m/Y');

// Initialisation des totaux HT et TTC
    $totalHT = 0;
    $totalTTC = 0;

    // Composition du contenu de l'e-mail
    $subject = "=?UTF-8?B?" . base64_encode("Création d'un Bon d'intervention") . "?=";
    $body = "Bonjour,\n\n";
    if ($is_client) {
        $body .= "Un Bon d'intervention a été créé pour vous :\n\n";
        $body .= "Technicien responsable : $technicien_nom $technicien_prenom\n\n";
        $body .= "Details du bon d'intervention : \n";
        $body .= "BI n° unique : " . $bi_details['bi_id'] . "\n";
        $body .= "Facturer : " . ucfirst($bi_details['bi_facture']) . "\n";
        $body .= "Garantie : " . ucfirst($bi_details['bi_garantie']) . "\n";
        $body .= "Contrat/Pack : " . ucfirst($bi_details['bi_contrat']) . "\n";
        $body .= "Service à la personne : " . ucfirst($bi_details['bi_service']) . "\n";
        $body .= "Envoyer facture par : " . ucfirst($bi_details['bi_envoi']) . "\n";
        $body .= "Facturation : " . $bi_details['bi_facturation'] . ", le : " . date('Y/m/d', $bi_details['bi_datefacturation'] ).  "\n";
        $body .= "Date d'intervention : " . date('Y/m/d', $bi_details['bi_date']) .  "\n";
        $body .= "Type de paiement : " . ucfirst($bi_details['bi_paiement']) . "\n";
        $body .= "Date d'entrée : " . date('Y/m/d', $bi_details['bi_datein']) . "\n";
        $body .= "Heure d'arrivée : " . $bi_details['bi_heurearrive'] . "\n";
        $body .= "Heure de départ : " . $bi_details['bi_heuredepart'] . "\n";
        $body .= "Commentaire : " . $bi_details['bi_commentaire'] . "\n\n";
        $body .= "Interventions :\n";

        // Boucle pour calculer les totaux HT et TTC pour chaque intervention
        foreach ($all_interventions as $intervention) {
            $body .= "Intervention : " . $intervention['inter_intervention'] . "\n";
            $body .= "Nb pièces : " . $intervention['inter_nbpiece'] . " | Prix/pièce : " . number_format($intervention['inter_prixunit'], 2) . "€ | Prix total : " . number_format($intervention['inter_total'], 2) . "€\n\n";

            // Calculer le coût total HT
            $totalHT += $intervention['inter_total'];

            // Calculer le coût total TTC
            $tauxTVA = 0.20; // Exemple : taux de TVA à 20%
            $totalTTC += $intervention['inter_total'] * (1 + $tauxTVA);
        }

        // Afficher les totaux HT et TTC
        $body .= "Total HT : " . number_format($totalHT, 2) . "€\n";
        $body .= "Total TTC : " . number_format($totalTTC, 2) . "€\n\n";
        $body .= "Cordialement,\nA2MI INFORMATIQUE";


    } else {
        $body .= "Un Bon d'intervention a été créé pour $client_nom $client_prenom :\n\n";
        $body .= "Details du bon d'intervention : \n";
        $body .= "BI n° unique : " . $bi_details['bi_id'] . "\n";
        $body .= "Facturer : " . ucfirst($bi_details['bi_facture']) . "\n";
        $body .= "Garantie : " . ucfirst($bi_details['bi_garantie']) . "\n";
        $body .= "Contrat/Pack : " . ucfirst($bi_details['bi_contrat']) . "\n";
        $body .= "Service à la personne : " . ucfirst($bi_details['bi_service']) . "\n";
        $body .= "Envoyer facture par : " . ucfirst($bi_details['bi_envoi']) . "\n";
        $body .= "Facturation : " . $bi_details['bi_facturation'] . ", le : " . date('Y/m/d', $bi_details['bi_datefacturation'] ).  "\n";
        $body .= "Date d'intervention : " . date('Y/m/d', $bi_details['bi_date']) .  "\n";
        $body .= "Type de paiement : " . ucfirst($bi_details['bi_paiement']) . "\n";
        $body .= "Date d'entrée : " . date('Y/m/d', $bi_details['bi_datein']) . "\n";
        $body .= "Heure d'arrivée : " . $bi_details['bi_heurearrive'] . "\n";
        $body .= "Heure de départ : " . $bi_details['bi_heuredepart'] . "\n";
        $body .= "Commentaire : " . $bi_details['bi_commentaire'] . "\n\n";
        $body .= "Interventions :\n";
        // Boucle pour calculer les totaux HT et TTC pour chaque intervention
        foreach ($all_interventions as $intervention) {
            $body .= "Intervention : " . $intervention['inter_intervention'] . "\n";
            $body .= "Nb pièces : " . $intervention['inter_nbpiece'] . " | Prix/pièce : " . number_format($intervention['inter_prixunit'], 2) . "€ | Prix total : " . number_format($intervention['inter_total'], 2) . "€\n\n";

            // Calculer le coût total HT
            $totalHT += $intervention['inter_total'];

            // Calculer le coût total TTC
            $tauxTVA = 0.20; // Exemple : taux de TVA à 20%
            $totalTTC += $intervention['inter_total'] * (1 + $tauxTVA);
        }

        // Afficher les totaux HT et TTC
        $body .= "Total HT : " . number_format($totalHT, 2) . "€\n";
        $body .= "Total TTC : " . number_format($totalTTC, 2) . "€\n\n";
        $body .= "Cordialement,\nA2MI INFORMATIQUE";
    }

    try {
        $mail = new PHPMailer(true);

        // Paramètres du serveur SMTP
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;  // Adresse email de l'expéditeur
        $mail->Password = SMTP_PASSWORD;  // Mot de passe de l'expéditeur
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;

        // Destinataires
        $mail->setFrom(SENDER_EMAIL, SENDER_NAME);
        if ($is_client) {
            $mail->addAddress($client_email);    // Adresse e-mail du client
        } else {
            $mail->addAddress($technicien_email);    // Adresse e-mail du technicien
        }

        // Contenu de l'e-mail
        $mail->isHTML(false);
        $mail->CharSet = 'UTF-8';
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
?>
