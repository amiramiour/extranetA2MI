<?php
session_start(); // Démarrer la session si ce n'est pas déjà fait

// Inclure la connexion à la base de données
include('../ConnexionBD.php');
require_once '../config.php';


// Inclure la classe PHPMailer
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Vérifier si l'utilisateur est connecté en tant qu'administrateur ou sous-administrateur
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_mail']) || ($_SESSION['user_type'] !== 'admin' && $_SESSION['user_type'] !== 'sousadmin')) {
    // Redirection vers une page d'erreur si l'utilisateur n'est pas connecté en tant qu'admin ou sous-admin
    header('Location: ../index.php');
    exit();
}

// Récupération de l'ID et de l'adresse e-mail du technicien à partir de la session
$technicien_id = $_SESSION['user_id'];
$technicien_email = $_SESSION['user_mail'];

// Vérifier si les données du formulaire ont été soumises
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db = connexionbdd();
    // Récupérer les informations du client
    $query_client = "SELECT membre_nom, membre_prenom, membre_mail FROM membres WHERE membre_id = :membre_id";
    $stmt_client = $db->prepare($query_client);
    $stmt_client->bindParam(':membre_id', $membre_id);
    $stmt_client->execute();
    $client_info = $stmt_client->fetch(PDO::FETCH_ASSOC);

    // Récupérer les données du formulaire
    $pret_materiel = $_POST["pret_materiel"];
    $valeurMat = $_POST["valeurMat"];
    $pret_caution = isset($_POST["pret_caution"]) ? $_POST["pret_caution"] : 0; // Si non défini, définir à 0
    $pret_mode = $_POST["pret_mode"];

    // Convertir les dates en timestamp Unix
    $pret_datein = strtotime(str_replace('/', '-', $_POST["pret_datein"]));
    $pret_dateout = strtotime(str_replace('/', '-', $_POST["pret_dateout"]));

    $commentaire = $_POST["commentaire"];
    $membre_id = $_POST['client_id'];

    // Récupérer l'ID du technicien connecté
    $pret_technicien = $_SESSION['user_id'];

    // Valeur par défaut pour l'attribut "rappel"
    $rappel = 1;

    // Valeur par défaut pour l'attribut "pret_etat"
    $pret_etat = 1;

    // Etablir la connexion à la base de données

    // Préparer la requête SQL pour insérer les données du prêt
    $sql = "INSERT INTO pret (pret_materiel, pret_caution, pret_mode, pret_datein, pret_dateout, membre_id, pret_technicien, commentaire, valeurMat, rappel, pret_etat) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Préparer la déclaration SQL
    $stmt = $db->prepare($sql);

    // Exécuter la requête avec les valeurs des paramètres
    $stmt->execute([$pret_materiel, $pret_caution, $pret_mode, $pret_datein, $pret_dateout, $membre_id, $pret_technicien, $commentaire, $valeurMat, $rappel, $pret_etat]);

    // Récupérer l'ID du prêt nouvellement inséré
    $new_pret_id = $db->lastInsertId();

    // Récupérer les détails du prêt nouvellement inséré
    $query_new_pret = "SELECT pret_materiel, pret_caution, pret_mode, pret_datein, pret_dateout, membre_id, pret_technicien, commentaire, valeurMat FROM pret WHERE pret_id = :pret_id";
    $stmt_new_pret = $db->prepare($query_new_pret);
    $stmt_new_pret->bindParam(':pret_id', $new_pret_id);
    $stmt_new_pret->execute();
    $new_pret_info = $stmt_new_pret->fetch(PDO::FETCH_ASSOC);

    // Compteur de succès et d'erreurs
    $success_count = 0;
    $error_count = 0;

    // Envoi d'e-mails de confirmation au client et au technicien
    if (sendPretCreationEmail($technicien_id, $technicien_email, $client_info['membre_nom'], $client_info['membre_prenom'], true, $new_pret_info)) {
        $success_count++;
    } else {
        $error_count++;
    }
    if (sendPretCreationEmail($membre_id, $technicien_email, $client_info['membre_nom'], $client_info['membre_prenom'], false, $new_pret_info)) {
        $success_count++;
    } else {
        $error_count++;
    }

    // Rediriger vers une page de succès ou afficher un message de succès
    header("Location: liste_prets.php");
    exit();
} else {
    // Si les données du formulaire n'ont pas été soumises, rediriger vers une page d'erreur
    header("Location: erreur.php");
    exit();
}

// Fonction pour envoyer un e-mail de création de prêt
function sendPretCreationEmail($membre_id, $technicien_email, $client_nom, $client_prenom, $is_technicien, $pret_info) {
    try {
        $connexion = connexionbdd();
        // Requête SQL pour récupérer les informations du client à partir de la table pret
        $query = $connexion->prepare("SELECT membres.membre_mail, pret_datein, pret_dateout, pret_materiel, commentaire FROM membres INNER JOIN pret ON membres.membre_id = pret.membre_id WHERE membres.membre_id = ?");
        $query->execute([$membre_id]);

        // Récupération des résultats
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $client_email = $result['membre_mail'];
        echo "Client Email: " . $client_email . "<br>"; // Débogage
        echo "Technicien Email: " . $technicien_email . "<br>"; // Débogage

        // Récupérer les informations du prêt nouvellement inséré
        $pret_materiel = $pret_info['pret_materiel'];
        $pret_caution = $pret_info['pret_caution'];
        $pret_mode = $pret_info['pret_mode'];
        $pret_datein = date('d/m/Y', $pret_info['pret_datein']);
        $pret_dateout = date('d/m/Y', $pret_info['pret_dateout']);
        $commentaire = $pret_info['commentaire'];
        $valeurMat = $pret_info['valeurMat'];

        // Composition du contenu de l'e-mail
        $subject = "Création de prêt - Notification";
        $body = "Bonjour,\n\n";

        if ($is_technicien) {
            $body .= "Un nouveau prêt a été créé pour le client $client_nom $client_prenom.\n\n";
        } else {
            $body .= "Un nouveau prêt a été créé pour vous.\n\n";
        }

        $body .= "Détails du prêt :\n";
        $body .= "Matériel : $pret_materiel\n";
        $body .= "Valeur du matériel : $valeurMat\n";
        $body .= "Caution : $pret_caution\n";
        $body .= "Mode : $pret_mode\n";
        $body .= "Date de début : $pret_datein\n";
        $body .= "Date de fin : $pret_dateout\n";
        $body .= "Commentaire : $commentaire\n\n";
        $body .= "Cordialement,\nVotre société";

        // Instancier un nouvel objet PHPMailer
        $mail = new PHPMailer(true);

        // Paramètres du serveur SMTP
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;  // Adresse email de l'expéditeur
        $mail->Password = SMTP_PASSWORD;           // Mot de passe de l'expéditeur
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;

        // Destinataires
        $mail->setFrom(SENDER_EMAIL, SENDER_NAME);
        $mail->addAddress($client_email);    // Adresse e-mail du client
        if ($is_technicien) {
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
        echo "Erreur lors de l'envoi de l'e-mail: " . $mail->ErrorInfo; // Débogage
        return false;
    }
}

?>
