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
    $membre_id = $_POST['client_id'];
    $query_client = "SELECT membre_nom, membre_prenom, membre_mail FROM membres WHERE membre_id = :membre_id";
    $stmt_client = $db->prepare($query_client);
    $stmt_client->bindParam(':membre_id', $membre_id);
    $stmt_client->execute();
    $client_info = $stmt_client->fetch(PDO::FETCH_ASSOC);
    // Récupération de l'ID du technicien en charge depuis la session
    // À remplacer par la gestion de sessions
// Récupérer l'ID de l'utilisateur connecté depuis la session
    $pret_technicien_id = $_SESSION['user_id'];

    // Récupération des informations du technicien
    $query_technicien = "SELECT membre_nom, membre_prenom, membre_mail FROM membres WHERE membre_id = :pret_technicien_id";
    $stmt_technicien = $db->prepare($query_technicien);
    $stmt_technicien->bindParam(':pret_technicien_id', $pret_technicien_id);
    $stmt_technicien->execute();
    $technicien_info = $stmt_technicien->fetch(PDO::FETCH_ASSOC);

    // Vérification si les informations du technicien ont été récupérées avec succès
    if ($technicien_info) {
        // Assignation du nom et du prénom du technicien
        $technicien_nom = $technicien_info['membre_nom'];
        $technicien_prenom = $technicien_info['membre_prenom'];
    } else {
        // Gestion de l'erreur si les informations du technicien ne sont pas disponibles
        echo "Erreur : Impossible de récupérer les informations du technicien.";
        exit();
    }

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

    sendPretCreationEmail($membre_id, $client_info['membre_mail'], $client_info['membre_nom'], $client_info['membre_prenom'], $technicien_email, $technicien_nom, $technicien_prenom, $new_pret_info, true);
    sendPretCreationEmail($membre_id, $client_info['membre_mail'], $client_info['membre_nom'], $client_info['membre_prenom'], $technicien_email, $technicien_nom, $technicien_prenom, $new_pret_info, false);



    // Rediriger vers une page de succès ou afficher un message de succès
    header("Location: liste_prets.php");
    exit();
} else {
    // Si les données du formulaire n'ont pas été soumises, rediriger vers une page d'erreur
    header("Location: erreur.php");
    exit();
}

// Fonction pour envoyer un e-mail de création de prêt
function sendPretCreationEmail($membre_id, $client_email, $client_nom, $client_prenom, $technicien_email, $technicien_nom, $technicien_prenom, $pret_info, $is_client) {
    try {
        // Composez le contenu de l'e-mail
        $subject = "Création de prêt - Notification";
        $body = "Bonjour,\n\n";
        $body .= "Un nouveau prêt a été créé ";
        if ($is_client) {
            $body .= "pour vous :\n\n";
            $body .= "Technicien responsable : $technicien_nom $technicien_prenom\n\n";
            $body .= "Détails du prêt :\n";
            $body .= "Matériel : {$pret_info['pret_materiel']}\n";
            $body .= "Valeur du matériel : {$pret_info['valeurMat']}\n";
            $body .= "Caution : {$pret_info['pret_caution']}\n";
            $body .= "Mode : {$pret_info['pret_mode']}\n";
            $body .= "Date de fin : " . date('d/m/Y', $pret_info['pret_dateout']) . "\n";
            $body .= "Commentaire : {$pret_info['commentaire']}\n\n";
        } else {
            $body .= "pour le client $client_nom $client_prenom :\n\n";
            $body .= "Détails du prêt :\n";
            $body .= "Matériel : {$pret_info['pret_materiel']}\n";
            $body .= "Valeur du matériel : {$pret_info['valeurMat']}\n";
            $body .= "Caution : {$pret_info['pret_caution']}\n";
            $body .= "Mode : {$pret_info['pret_mode']}\n";
            $body .= "Date de fin : " . date('d/m/Y', $pret_info['pret_dateout']) . "\n";
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