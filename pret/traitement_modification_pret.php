<?php
// Inclure la classe PHPMailer
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

require_once '../config.php';

// Vérifier si l'utilisateur est connecté et est un technicien
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_mail']) || ($_SESSION['user_type'] !== 'admin' && $_SESSION['user_type'] !== 'sousadmin')) {
    // Si l'utilisateur n'est pas connecté en tant qu'admin ou sous-admin, redirigez-le ou affichez un message d'erreur
    header("Location: ../connexion.php");
    exit;
}

$technicien_id = $_SESSION['user_id'];

// Vérifier si le formulaire a été soumis et si l'identifiant du prêt est défini
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pret_id'])) {
    $pret_id = $_POST['pret_id'];
    $caution = $_POST['caution'];
    $date_rendu = $_POST['date_rendu'];
    $commentaire = $_POST['commentaire'];

    // Convertir la date au format YYYY-MM-DD
    $date_rendu = strtotime(str_replace('/', '-', $_POST["date_rendu"]));

    // Nouvelle variable pour récupérer l'état choisi
    $etat = $_POST['etat'];

    // Inclure la connexion à la base de données
    include('../ConnexionBD.php');

    try {
        // Etablir la connexion à la base de données
        $db = connexionbdd();

        // Requête SQL pour mettre à jour les détails du prêt, y compris l'état
        $query = "UPDATE pret SET pret_caution = :caution, pret_dateout = :date_rendu, pret_etat = :etat, commentaire = :commentaire WHERE pret_id = :pret_id";

        // Préparer la requête SQL
        $stmt = $db->prepare($query);

        // Liaison des valeurs des paramètres de requête
        $stmt->bindParam(':caution', $caution, PDO::PARAM_STR);
        $stmt->bindParam(':date_rendu', $date_rendu, PDO::PARAM_STR);
        $stmt->bindParam(':etat', $etat, PDO::PARAM_INT); // Ajouter l'état ici
        $stmt->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
        $stmt->bindParam(':pret_id', $pret_id, PDO::PARAM_INT);

        // Exécution de la requête
        if ($stmt->execute()) {
            // Récupérer les informations du prêt concerné depuis la base de données
            $query_pret = "SELECT * FROM pret WHERE pret_id = :pret_id";
            $stmt_pret = $db->prepare($query_pret);
            $stmt_pret->bindParam(':pret_id', $pret_id, PDO::PARAM_INT);
            $stmt_pret->execute();
            $pret_info_db = $stmt_pret->fetch(PDO::FETCH_ASSOC);

            // Assurez-vous que la requête a retourné des résultats
            if ($pret_info_db) {
                // Utilisez les données de la base de données pour compléter les informations du prêt
                $pret_info = [
                    'pret_materiel' => $pret_info_db['pret_materiel'],
                    'valeurMat' => $pret_info_db['valeurMat'],
                    'pret_caution' => $pret_info_db['pret_caution'],
                    'pret_mode' => $pret_info_db['pret_mode'],
                    'pret_datein' => $pret_info_db['pret_datein'],
                    'pret_dateout' => $pret_info_db['pret_dateout'],
                    'commentaire' => $pret_info_db['commentaire']
                ];

                // Récupérer les informations du technicien
                $query_technicien = "SELECT membre_nom, membre_prenom, membre_mail FROM membres WHERE membre_id = :pret_technicien_id";
                $stmt_technicien = $db->prepare($query_technicien);
                $stmt_technicien->bindParam(':pret_technicien_id', $pret_info_db['pret_technicien'], PDO::PARAM_INT);
                $stmt_technicien->execute();
                $technicien_info = $stmt_technicien->fetch(PDO::FETCH_ASSOC);

                $technicien_nom = $technicien_info['membre_nom'];
                $technicien_prenom = $technicien_info['membre_prenom'];
                $technicien_email = $technicien_info['membre_mail'];

                // Récupérer les informations du client concerné
                $query_client = "SELECT membre_nom, membre_prenom, membre_mail FROM membres WHERE membre_id = :pret_client_id";
                $stmt_client = $db->prepare($query_client);
                $stmt_client->bindParam(':pret_client_id', $pret_info_db['membre_id'], PDO::PARAM_INT);
                $stmt_client->execute();
                $client_info = $stmt_client->fetch(PDO::FETCH_ASSOC);

                $client_nom = $client_info['membre_nom'];
                $client_prenom = $client_info['membre_prenom'];
                $client_email = $client_info['membre_mail'];

                // Envoi de l'e-mail au technicien
                sendPretEmail($technicien_email, $technicien_nom, $technicien_prenom, $client_nom, $client_prenom, $pret_info, true);
                sendPretEmail($client_email, $technicien_nom, $technicien_prenom, $client_nom, $client_prenom, $pret_info, false);


                // Envoi de l'e-mail au client

                // Rediriger vers la liste des prêts si la mise à jour est réussie
                header("Location: liste_prets.php");
                exit;
            } else {
                // Gérer le cas où aucune information de prêt n'est trouvée dans la base de données
                // Peut-être afficher un message d'erreur ou rediriger l'utilisateur
                header("Location: erreur.php");
                exit;
            }
        } else {
            // Rediriger vers une page d'erreur si la mise à jour échoue
            header("Location: erreur.php");
            exit;
        }
    } catch (PDOException $e) {
        // Afficher un message d'erreur en cas d'erreur PDO
        echo "Erreur : " . $e->getMessage();
    }
} else {
    // Rediriger vers une page d'erreur si le formulaire n'a pas été soumis correctement
    header("Location: erreur.php");
    exit;
}

// Fonction pour envoyer un e-mail de modification de prêt
function sendPretEmail($recipient_email, $recipient_nom, $recipient_prenom, $sender_nom, $sender_prenom, $pret_info, $is_technicien) {
    // Récupérer l'email du technicien depuis la session
    $technicien_email = $_SESSION['user_mail'];

    try {
        // Composez le contenu de l'e-mail
        $subject = "Création de prêt - Notification";
        $body = "Bonjour,\n\n";
        if ($is_technicien) {
            $body .= "Un prêt a été modifier pour $sender_nom $sender_prenom\n";
        } else {
            $body .= "Un prêt a été modifier pour vous\n";
            $body .= "Votre prêt a été modifié par $recipient_nom $recipient_prenom.\n\n";
        }
        $body .= "Détails du prêt :\n";
        $body .= "Matériel : {$pret_info['pret_materiel']}\n";
        $body .= "Valeur du matériel : {$pret_info['valeurMat']}\n";
        $body .= "Caution : {$pret_info['pret_caution']}\n";
        $body .= "Mode : {$pret_info['pret_mode']}\n";
        $body .= "Date de début : " . date('d/m/Y', $pret_info['pret_datein']) . "\n";
        $body .= "Date de fin : " . date('d/m/Y', $pret_info['pret_dateout']) . "\n";

        $body .= "Commentaire : {$pret_info['commentaire']}\n\n";
        $body .= "Cordialement,\nVotre société";

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
        if ($is_technicien) {
            $mail->addAddress($technicien_email);    // Adresse e-mail du technicien
        } else {
            $mail->addAddress($recipient_email);    // Adresse e-mail du client
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