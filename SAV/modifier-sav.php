<?php
session_start(); // Démarrer la session si ce n'est pas déjà fait

// Vérifier si l'utilisateur est connecté et est un technicien
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_mail']) || $_SESSION['user_type'] === 'client') {
    // Si l'utilisateur n'est pas connecté ou est un client, redirigez-le ou affichez un message d'erreur
    header("Location: ../connexion.php");
    exit;
}

// Inclure le fichier de connexion à la base de données
require_once '../ConnexionBD.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// Vérifier si l'ID du SAV est passé en paramètre dans l'URL
if (!isset($_GET['sav_id']) || empty($_GET['sav_id'])) {
    header('Location: sav.php'); // Rediriger si l'ID n'est pas présent
    exit();
}

// Récupérer l'ID du SAV depuis l'URL
$sav_id = $_GET['sav_id'];

// Vérifier si le formulaire de suppression a été soumis
if (isset($_POST['delete']) && $_POST['delete'] === 'delete') {
    try {
        // Connexion à la base de données en utilisant la fonction connexionbdd()
        $connexion = connexionbdd();

        // Récupérer les informations du SAV avant la désactivation
        $query_sav_info = "SELECT s.sav_etats, e.etat_intitule, s.sav_technicien FROM sav s INNER JOIN sav_etats e ON s.sav_etats = e.id_etat_sav WHERE s.sav_id = :sav_id";
        $stmt_sav_info = $connexion->prepare($query_sav_info);
        $stmt_sav_info->bindParam(':sav_id', $sav_id, PDO::PARAM_INT);
        $stmt_sav_info->execute();
        $sav_info = $stmt_sav_info->fetch(PDO::FETCH_ASSOC);

        // Mettre à jour le champ active à 0 dans la table SAV
        $query_delete_sav = "UPDATE sav SET active = 0, date_update = NOW(), updated_by = :updated_by WHERE sav_id = :sav_id";
        $stmt_delete_sav = $connexion->prepare($query_delete_sav);
        $stmt_delete_sav->bindParam(':sav_id', $sav_id, PDO::PARAM_INT);
        $stmt_delete_sav->bindParam(':updated_by', $_SESSION['user_id'], PDO::PARAM_INT); // Utiliser l'ID de la personne connectée
        $stmt_delete_sav->execute();

        // Récupérer la date de création de la dernière entrée dans la table sauvgarde_etat_info
        $query_last_creation_date = "SELECT date_creation FROM sauvgarde_etat_info WHERE sav_id = :sav_id ORDER BY id_sauvgarde_etat DESC LIMIT 1";
        $stmt_last_creation_date = $connexion->prepare($query_last_creation_date);
        $stmt_last_creation_date->bindParam(':sav_id', $sav_id, PDO::PARAM_INT);
        $stmt_last_creation_date->execute();
        $last_creation_date = $stmt_last_creation_date->fetchColumn();

        // Insérer une entrée dans la table sauvgarde_etat_info pour enregistrer la désactivation
        $query_insert_sav_history = "INSERT INTO sauvgarde_etat_info (sav_id, sauvgarde_etat, sauvgarde_avancement, date_creation, date_update, created_by, updated_by) 
                                    VALUES (:sav_id, :sauvgarde_etat, 'Supprimé', :date_creation, NOW(), :created_by, :updated_by)";
        $stmt_insert_sav_history = $connexion->prepare($query_insert_sav_history);
        $stmt_insert_sav_history->bindParam(':sav_id', $sav_id, PDO::PARAM_INT);
        $stmt_insert_sav_history->bindParam(':sauvgarde_etat', $sav_info['sav_etats'], PDO::PARAM_INT);
        $stmt_insert_sav_history->bindParam(':date_creation', $last_creation_date, PDO::PARAM_STR);
        $stmt_insert_sav_history->bindParam(':created_by', $sav_info['sav_technicien'], PDO::PARAM_INT); // Utiliser l'ID de la personne qui a créé le SAV
        $stmt_insert_sav_history->bindParam(':updated_by', $_SESSION['user_id'], PDO::PARAM_INT); // Utiliser l'ID de la personne connectée
        $stmt_insert_sav_history->execute();

        // Rediriger vers la page d'accueil après "suppression"
        header('Location: sav.php');
        exit();
    } catch (PDOException $e) {
        // Gérer les erreurs
        $error = "Erreur : " . $e->getMessage();
    }
}

// Vérifier si le formulaire de modification a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nouvel_etat_id']) && isset($_POST['nouvel_avancement'])) {
        $nouvel_etat_id = $_POST['nouvel_etat_id'];
        $nouvel_avancement = $_POST['nouvel_avancement'];

        try {
            // Connexion à la base de données en utilisant la fonction connexionbdd()
            $connexion = connexionbdd();

            // Récupération des informations du client
            $query_client = "SELECT m.membre_nom, m.membre_prenom, m.membre_mail 
                             FROM sav s
                             INNER JOIN membres m ON s.membre_id = m.membre_id
                             WHERE s.sav_id = :sav_id";
            $stmt_client = $connexion->prepare($query_client);
            $stmt_client->bindParam(':sav_id', $sav_id, PDO::PARAM_INT);
            $stmt_client->execute();
            $client_info = $stmt_client->fetch(PDO::FETCH_ASSOC);

            // Récupération de l'ID du technicien en charge depuis la session
            // À remplacer par la gestion de sessions
            $sav_technicien_id = $_SESSION['user_id']; // ID du technicien en charge (à remplacer par la gestion de sessions)

            // Récupération des informations du technicien
            $query_technicien = "SELECT membre_nom, membre_prenom, membre_mail 
                                 FROM membres
                                 WHERE membre_id = :sav_technicien_id";
            $stmt_technicien = $connexion->prepare($query_technicien);
            $stmt_technicien->bindParam(':sav_technicien_id', $sav_technicien_id, PDO::PARAM_INT);
            $stmt_technicien->execute();
            $technicien_info = $stmt_technicien->fetch(PDO::FETCH_ASSOC);

            $technicien_nom = $technicien_info['membre_nom'];
            $technicien_prenom = $technicien_info['membre_prenom'];
            $technicien_email = $technicien_info['membre_mail'];

            // Récupération du libellé du nouvel état
            $query_etat_label = "SELECT etat_intitule FROM sav_etats WHERE id_etat_sav = :nouvel_etat_id";
            $stmt_etat_label = $connexion->prepare($query_etat_label);
            $stmt_etat_label->bindParam(':nouvel_etat_id', $nouvel_etat_id, PDO::PARAM_INT);
            $stmt_etat_label->execute();
            $etat_label = $stmt_etat_label->fetchColumn();
            // Récupérer l'ID de la personne qui a créé le SAV
            $query_created_by = "SELECT sav_technicien FROM sav WHERE sav_id = :sav_id";
            $stmt_created_by = $connexion->prepare($query_created_by);
            $stmt_created_by->bindParam(':sav_id', $sav_id, PDO::PARAM_INT);
            $stmt_created_by->execute();
            $sav_created_by = $stmt_created_by->fetch(PDO::FETCH_ASSOC);
            $created_by = $sav_created_by['sav_technicien']; // ID de la personne qui a créé le SAV

// Insérer un nouvel enregistrement dans la table sauvgarde_etat_info
            $query_insert_new_state = "INSERT INTO sauvgarde_etat_info (sav_id, sauvgarde_etat, sauvgarde_avancement, date_creation, date_update, created_by, updated_by) 
                            VALUES (:sav_id, :nouvel_etat_id, :nouvel_avancement, NOW(), NOW(), :created_by, :updated_by)";
            $stmt_insert_new_state = $connexion->prepare($query_insert_new_state);
            $stmt_insert_new_state->bindParam(':sav_id', $sav_id, PDO::PARAM_INT);
            $stmt_insert_new_state->bindParam(':nouvel_etat_id', $nouvel_etat_id, PDO::PARAM_INT);
            $stmt_insert_new_state->bindParam(':nouvel_avancement', $nouvel_avancement, PDO::PARAM_STR);
            $stmt_insert_new_state->bindParam(':created_by', $created_by, PDO::PARAM_INT); // Utiliser l'ID de la personne qui a créé le SAV
            $stmt_insert_new_state->bindParam(':updated_by', $sav_technicien_id, PDO::PARAM_INT);
            $stmt_insert_new_state->execute();



            // Récupérer l'ID de la dernière insertion
            $last_inserted_id = $connexion->lastInsertId();

            // Mettre à jour l'avancement dans la table sav pour qu'il pointe vers la dernière valeur ajoutée dans sauvgarde_etat_info
            $query_update_sav = "UPDATE sav SET sav_avancement = :last_inserted_id WHERE sav_id = :sav_id";
            $stmt_update_sav = $connexion->prepare($query_update_sav);
            $stmt_update_sav->bindParam(':last_inserted_id', $last_inserted_id, PDO::PARAM_INT);
            $stmt_update_sav->bindParam(':sav_id', $sav_id, PDO::PARAM_INT);
            $stmt_update_sav->execute();

            // Mettre à jour l'état dans la table sav
            $query_update_sav_state = "UPDATE sav SET sav_etats = :nouvel_etat_id WHERE sav_id = :sav_id";
            $stmt_update_sav_state = $connexion->prepare($query_update_sav_state);
            $stmt_update_sav_state->bindParam(':nouvel_etat_id', $nouvel_etat_id, PDO::PARAM_INT);
            $stmt_update_sav_state->bindParam(':sav_id', $sav_id, PDO::PARAM_INT);
            $stmt_update_sav_state->execute();


            if($nouvel_etat_id==5){
                // Envoyer un e-mail de modification au client
                sendSAVModificationEmail($client_info['membre_mail'], $client_info['membre_nom'], $client_info['membre_prenom'], $technicien_nom, $technicien_prenom, $etat_label, $nouvel_avancement, true);
            }
            // Envoyer un e-mail de modification au technicien
            sendSAVModificationEmail($technicien_email, $client_info['membre_nom'], $client_info['membre_prenom'], $technicien_nom, $technicien_prenom, $etat_label, $nouvel_avancement, false);

            // Rediriger vers la page d'accueil après la modification
            header('Location: sav.php');
            exit();
        } catch (PDOException $e) {
            // Gérer les erreurs
            $error = "Erreur : " . $e->getMessage();
        }
    }
}

// Récupérer les informations détaillées du SAV
try {
    // Connexion à la base de données en utilisant la fonction connexionbdd()
    $connexion = connexionbdd();

    // Préparer la requête SQL pour récupérer les informations détaillées du SAV
    $query = "SELECT * FROM sav WHERE sav_id = :sav_id";

    // Préparation de la requête SQL
    $stmt = $connexion->prepare($query);

    // Liaison des valeurs des paramètres de requête
    $stmt->bindParam(':sav_id', $sav_id, PDO::PARAM_INT);

    // Exécution de la requête
    $stmt->execute();

    // Récupération des résultats
    $sav = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si le SAV existe
    if (!$sav) {
        header('Location: index.php'); // Rediriger si le SAV n'est pas trouvé
        exit();
    }
} catch (PDOException $e) {
    // Gérer les erreurs
    $error = "Erreur : " . $e->getMessage();
}

// Fonction pour envoyer un e-mail de notification de modification de SAV
function sendSAVModificationEmail($to_email, $client_nom, $client_prenom, $technicien_nom, $technicien_prenom, $nouvel_etat, $nouvel_avancement, $is_client) {
    // Construire le sujet du courriel
    $subject = "=?UTF-8?B?" . base64_encode("Modification SAV") . "?="; // Encodage du sujet

    // Construire le corps du courriel
    if ($is_client) {
        $body = "Cher $client_prenom $client_nom,\n\n";
        $body .= "Votre SAV a été modifié avec succès.\n\n";
        $body .= "Nouvel état : $nouvel_etat\n";
        $body .= "Nouveau progrès : $nouvel_avancement\n\n";
        $body .= "Cordialement,\n$technicien_prenom $technicien_nom";
    } else {
        $body = "Cher $technicien_prenom $technicien_nom,\n\n";
        $body .= "Vous avez modifié le SAV de $client_prenom $client_nom avec succès.\n\n";
        $body .= "Nouvel état : $nouvel_etat\n";
        $body .= "Nouveau progrès : $nouvel_avancement\n\n";
    }

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

        // Destinataire
        $mail->setFrom('masdouarania02@gmail.com', 'A2MI informatique');
        $mail->addAddress($to_email);

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
        // Échec de l'envoi
        return false;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier SAV</title>
    <!-- Inclure Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/css/bootstrap.min.css">
</head>

<body>
<div class="container mt-4">
    <h2>Modifier SAV</h2>
    <div class="mb-3">
        <h3>Informations détaillées du SAV</h3>
        <p><strong>ID SAV:</strong> <?= $sav['sav_id'] ?></p>
        <p><strong>Accessoire:</strong> <?= $sav['sav_accessoire'] ?></p>
        <!-- Afficher d'autres informations du SAV selon vos besoins -->
    </div>
    <form action="" method="post">
        <div class="mb-3">
            <label for="nouvel_etat_id" class="form-label">Nouvel état :</label>
            <select name="nouvel_etat_id" id="nouvel_etat_id" class="form-select" required>
                <option value="3" <?= ($sav['sav_etats'] == 3) ? 'selected' : ''; ?>>Réceptionné</option>
                <option value="2" <?= ($sav['sav_etats'] == 2) ? 'selected' : ''; ?>>En cours</option>
                <option value="1" <?= ($sav['sav_etats'] == 1) ? 'selected' : ''; ?>>En attente</option>
                <option value="5" <?= ($sav['sav_etats'] == 5) ? 'selected' : ''; ?>>Terminé</option>
                <option value="4" <?= ($sav['sav_etats'] == 4) ? 'selected' : ''; ?>>Rendu au client</option>
                <!-- Ajoutez d'autres états si nécessaire -->
            </select>
        </div>
        <div class="mb-3">
            <label for="nouvel_avancement" class="form-label">Nouvel avancement :</label>
            <!-- Ajoutez l'attribut required pour obliger l'utilisateur à saisir un nouvel avancement -->
            <textarea class="form-control" name="nouvel_avancement" id="nouvel_avancement" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
    </form>
    <form action="" method="post" onsubmit="return confirm('Voulez-vous vraiment supprimer ce SAV ?');">
        <input type="hidden" name="delete" value="delete">
        <button type="submit" class="btn btn-danger mt-3">Supprimer ce SAV</button>
    </form>
    <?php if (isset($error)) : ?>
        <div class="alert alert-danger mt-3" role="alert">
            <?= $error ?>
        </div>
    <?php endif; ?>
</div>

<!-- Inclure Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/js/bootstrap.min.js"></script>
</body>

</html>