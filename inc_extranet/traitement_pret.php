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

// Vérifier si les données du formulaire ont été soumises
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db = connexionbdd();

    // Ajouter la récupération des informations du technicien
    $query_technicien = "SELECT users_name, users_firstname FROM users WHERE users_id = :technicien_id";
    $stmt_technicien = $db->prepare($query_technicien);
    $stmt_technicien->bindParam(':technicien_id', $technicien_id);
    $stmt_technicien->execute();
    $technicien_info = $stmt_technicien->fetch(PDO::FETCH_ASSOC);

    if ($technicien_info) {
        $technicien_nom = $technicien_info['users_name'];
        $technicien_prenom = $technicien_info['users_firstname'];
    } else {
        echo "Erreur : Impossible de récupérer les informations du technicien.";
        exit();
    }

    // Récupérer les informations du client
    $users_id = $_POST['client_id'];
    $query_client = "SELECT users_name, users_firstname, users_mail FROM users WHERE users_id = :users_id";
    $stmt_client = $db->prepare($query_client);
    $stmt_client->bindParam(':users_id', $users_id);
    $stmt_client->execute();
    $client_info = $stmt_client->fetch(PDO::FETCH_ASSOC);

    // Vérification si les informations du client ont été récupérées avec succès
    if (!$client_info) {
        echo "Erreur : Impossible de récupérer les informations du client.";
        exit();
    }

    // Récupérer les données du formulaire
    $pret_materiel  = $_POST["pret_materiel"];
    $valeurMat      = $_POST["valeurMat"];
    $pret_caution   = isset($_POST["pret_caution"]) ? $_POST["pret_caution"] : 0; // Si non défini, définir à 0
    $pret_mode      = $_POST["pret_mode"];
    $pret_datein    = date('Y-m-d', strtotime(str_replace('/', '-', $_POST["pret_datein"])));
    $pret_dateout   = date('Y-m-d', strtotime(str_replace('/', '-', $_POST["pret_dateout"])));
    $commentaire    = $_POST["commentaire"];

    // Récupérer l'ID du technicien connecté
    $pret_technicien = $_SESSION['id'];

    // Valeur par défaut pour l'attribut "rappel" et "pret_etat"
    $rappel = 1;
    $pret_etat = 1;

    // Préparer la requête SQL pour insérer les données du prêt
    $sql = "INSERT INTO pret (pret_materiel, pret_caution, pret_mode, pret_datein, pret_dateout, users_id, pret_technicien, commentaire, valeurMat, rappel, pret_etat) 
            VALUES (:pret_materiel, :pret_caution, :pret_mode, :pret_datein, :pret_dateout, :users_id, :pret_technicien, :commentaire, :valeurMat, :rappel, :pret_etat)";

    $stmt = $db->prepare($sql);

    // Exécuter la requête avec les valeurs des paramètres
    $stmt->bindParam(':pret_materiel', $pret_materiel);
    $stmt->bindParam(':pret_caution', $pret_caution);
    $stmt->bindParam(':pret_mode', $pret_mode);
    $stmt->bindParam(':pret_datein', $pret_datein);
    $stmt->bindParam(':pret_dateout', $pret_dateout);
    $stmt->bindParam(':users_id', $users_id);
    $stmt->bindParam(':pret_technicien', $pret_technicien);
    $stmt->bindParam(':commentaire', $commentaire);
    $stmt->bindParam(':valeurMat', $valeurMat);
    $stmt->bindParam(':rappel', $rappel);
    $stmt->bindParam(':pret_etat', $pret_etat);

    if ($stmt->execute()) {
        $new_pret_id = $db->lastInsertId();

        // Insertion dans la table pret_save
        $sql_save = "INSERT INTO pret_save (pret_id, created_by, date_creation) 
                     VALUES (:pret_id, :created_by, NOW())";

        $stmt_save = $db->prepare($sql_save);
        $stmt_save->bindParam(':pret_id', $new_pret_id);
        $stmt_save->bindParam(':created_by', $pret_technicien);
        $stmt_save->execute();
        // Récupérer l'ID du prêt nouvellement inséré
        $new_pret_id = $db->lastInsertId();

        // Récupérer les détails du prêt nouvellement inséré
        $query_new_pret = "SELECT pret_materiel, pret_caution, pret_mode, pret_datein, pret_dateout, users_id, pret_technicien, commentaire, valeurMat 
                           FROM pret 
                           WHERE pret_id = :pret_id";
        $stmt_new_pret = $db->prepare($query_new_pret);
        $stmt_new_pret->bindParam(':pret_id', $new_pret_id);
        $stmt_new_pret->execute();
        $new_pret_info = $stmt_new_pret->fetch(PDO::FETCH_ASSOC);

        sendPretCreationEmail($users_id, $client_info['users_mail'], $client_info['users_name'], $client_info['users_firstname'], $technicien_email, $technicien_nom, $technicien_prenom, $new_pret_info, $pret_etat, true);
        sendPretCreationEmail($users_id, $client_info['users_mail'], $client_info['users_name'], $client_info['users_firstname'], $technicien_email, $technicien_nom, $technicien_prenom, $new_pret_info, $pret_etat, false);

        // Rediriger vers une page de succès ou afficher un message de succès
        header("Location: ../list_pret.php");
        exit();
    } else {
        // Gestion des erreurs SQL
        $errorInfo = $stmt->errorInfo();
        echo "Erreur SQL : " . $errorInfo[2];
    }
} else {
    // Si les données du formulaire n'ont pas été soumises, rediriger vers une page d'erreur
    header("Location: erreur.php");
    exit();
}

// Fonction pour envoyer un e-mail de création de prêt
function sendPretCreationEmail($users_id, $client_email, $client_nom, $client_prenom, $technicien_email, $technicien_nom, $technicien_prenom, $pret_info, $pret_etat, $is_client) {
    try {
        // Composez le contenu de l'e-mail
        $subject = "Création de prêt - Notification";
        $body = "Bonjour,\n\n";
        $body .= "Un nouveau prêt a été créé ";
        if ($is_client) {
            $body .= "pour vous :\n\n";
            $body .= "Technicien responsable : $technicien_nom $technicien_prenom\n\n";
            $body .= "État du prêt : ";
            if ($pret_etat == 1) {
                $body .= "En cours\n";
            } else {
                $body .= "Terminé\n";
            }
            $body .= "Détails du prêt :\n";
            $body .= "Matériel : {$pret_info['pret_materiel']}\n";
            $body .= "Valeur du matériel : {$pret_info['valeurMat']}\n";
            $body .= "Caution : {$pret_info['pret_caution']}\n";
            $body .= "Mode : {$pret_info['pret_mode']}\n";
            $body .= "Date du début : " . date('d/m/Y', strtotime($pret_info['pret_datein'])) . "\n";
            $body .= "Date de fin : " . date('d/m/Y', strtotime($pret_info['pret_dateout'])) . "\n";
            $body .= "Commentaire : {$pret_info['commentaire']}\n\n";
        } else {
            $body .= "pour le client $client_nom $client_prenom :\n\n";
            $body .= "État du prêt : ";
            if ($pret_etat == 1) {
                $body .= "En cours\n";
            } else {
                $body .= "Terminé\n";
            }
            $body .= "Détails du prêt :\n";
            $body .= "Matériel : {$pret_info['pret_materiel']}\n";
            $body .= "Valeur du matériel : {$pret_info['valeurMat']}\n";
            $body .= "Caution : {$pret_info['pret_caution']}\n";
            $body .= "Mode : {$pret_info['pret_mode']}\n";
            $body .= "Date du début : " . date('d/m/Y', strtotime($pret_info['pret_datein'])) . "\n";
            $body .= "Date de fin : " . date('d/m/Y', strtotime($pret_info['pret_dateout'])) . "\n";
            $body .= "Commentaire : {$pret_info['commentaire']}\n\n";
        }
        $body .= "Cordialement,\nA2MI INFORMATIQUE";

        // Instancier un nouvel objet PHPMailer
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