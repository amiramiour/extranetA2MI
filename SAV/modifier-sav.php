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
require_once '../config.php';

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
        $db = connexionbdd();

        // Récupérer les informations du SAV avant la désactivation
        $query_sav_info = "SELECT s.sav_etats, s.sav_technicien, e.etat_intitule FROM sav s INNER JOIN sav_etats e ON s.sav_etats = e.id_etat_sav WHERE s.sav_id = :sav_id";
        $stmt_sav_info = $db->prepare($query_sav_info);
        $stmt_sav_info->bindParam(':sav_id', $sav_id, PDO::PARAM_INT);
        $stmt_sav_info->execute();
        $sav_info = $stmt_sav_info->fetch(PDO::FETCH_ASSOC);

        // Mettre à jour le champ active à 0 dans la table SAV
        $query_delete_sav = "UPDATE sav SET active = 0 WHERE sav_id = :sav_id";
        $stmt_delete_sav = $db->prepare($query_delete_sav);
        $stmt_delete_sav->bindParam(':sav_id', $sav_id, PDO::PARAM_INT);
        $stmt_delete_sav->execute();

        // Récupérer la date de création de la dernière entrée dans la table sauvgarde_etat_info
        $query_last_creation_date = "SELECT date_creation FROM sauvgarde_etat_info WHERE sav_id = :sav_id ORDER BY id_sauvgarde_etat DESC LIMIT 1";
        $stmt_last_creation_date = $db->prepare($query_last_creation_date);
        $stmt_last_creation_date->bindParam(':sav_id', $sav_id, PDO::PARAM_INT);
        $stmt_last_creation_date->execute();
        $last_creation_date = $stmt_last_creation_date->fetchColumn();

        // Si aucune entrée n'a été trouvée, utilisez la date actuelle
        if (!$last_creation_date) {
            $last_creation_date = date('Y-m-d H:i:s');
        }

        // Insérer une entrée dans la table sauvgarde_etat_info pour enregistrer la désactivation
        $query_insert_sav_history = "INSERT INTO sauvgarde_etat_info (sav_id, sauvgarde_etat, sauvgarde_avancement, date_creation, date_update, created_by, updated_by) 
                            VALUES (:sav_id, :sauvgarde_etat, 'Supprimé', :date_creation, NOW(), :created_by, :updated_by)";
        $stmt_insert_sav_history = $db->prepare($query_insert_sav_history);
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
        $nouvelle_date_fin = !empty($_POST['nouvelle_date_fin']) ? $_POST['nouvelle_date_fin'] : null;
        $nouveau_tarif_materiel_ht = isset($_POST['nouveau_tarif_materiel_ht']) ? floatval($_POST['nouveau_tarif_materiel_ht']) : 0;
        $nouveau_tarif_materiel_ttc = $nouveau_tarif_materiel_ht * (1 + 0.2);
        $nouvelle_main_oeuvre_ht = isset($_POST['nouvelle_main_oeuvre_ht']) ? floatval($_POST['nouvelle_main_oeuvre_ht']) : 0;
        $nouvelle_main_oeuvre_ttc = $nouvelle_main_oeuvre_ht * (1 + 0.2);


        try {
            // Connexion à la base de données en utilisant la fonction connexionbdd()
            $db = connexionbdd();


            // Récupérer les anciens prix depuis la base de données
            $query_old_prices = "SELECT sav_tarifmaterielht, sav_tarifmaterielttc, sav_maindoeuvreht, sav_maindoeuvrettc FROM sav WHERE sav_id = :sav_id";
            $stmt_old_prices = $db->prepare($query_old_prices);
            $stmt_old_prices->bindParam(':sav_id', $sav_id, PDO::PARAM_INT);
            $stmt_old_prices->execute();
            $old_prices = $stmt_old_prices->fetch(PDO::FETCH_ASSOC);

            // Calculer les totaux
            $total_materiel_ht = $old_prices['sav_tarifmaterielht'] + $nouveau_tarif_materiel_ht;
            $total_materiel_ttc = $old_prices['sav_tarifmaterielttc'] + $nouveau_tarif_materiel_ttc;
            $total_main_oeuvre_ht = $old_prices['sav_maindoeuvreht'] + $nouvelle_main_oeuvre_ht;
            $total_main_oeuvre_ttc = $old_prices['sav_maindoeuvrettc'] + $nouvelle_main_oeuvre_ttc;

            // Mettre à jour les prix dans la base de données
            $query_update_prices = "UPDATE sav SET 
        sav_tarifmaterielht = :total_materiel_ht,
        sav_tarifmaterielttc = :total_materiel_ttc,
        sav_maindoeuvreht = :total_main_oeuvre_ht,
        sav_maindoeuvrettc = :total_main_oeuvre_ttc
        WHERE sav_id = :sav_id";
            $stmt_update_prices = $db->prepare($query_update_prices);
            $stmt_update_prices->bindParam(':total_materiel_ht', $total_materiel_ht, PDO::PARAM_INT);
            $stmt_update_prices->bindParam(':total_materiel_ttc', $total_materiel_ttc, PDO::PARAM_INT);
            $stmt_update_prices->bindParam(':total_main_oeuvre_ht', $total_main_oeuvre_ht, PDO::PARAM_INT);
            $stmt_update_prices->bindParam(':total_main_oeuvre_ttc', $total_main_oeuvre_ttc, PDO::PARAM_INT);
            $stmt_update_prices->bindParam(':sav_id', $sav_id, PDO::PARAM_INT);
            $stmt_update_prices->execute();

            // Récupération des informations du client
            $query_client = "SELECT m.membre_nom, m.membre_prenom, m.membre_mail 
                             FROM sav s
                             INNER JOIN membres m ON s.membre_id = m.membre_id
                             WHERE s.sav_id = :sav_id";
            $stmt_client = $db->prepare($query_client);
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
            $stmt_technicien = $db->prepare($query_technicien);
            $stmt_technicien->bindParam(':sav_technicien_id', $sav_technicien_id, PDO::PARAM_INT);
            $stmt_technicien->execute();
            $technicien_info = $stmt_technicien->fetch(PDO::FETCH_ASSOC);

            $technicien_nom = $technicien_info['membre_nom'];
            $technicien_prenom = $technicien_info['membre_prenom'];
            $technicien_email = $technicien_info['membre_mail'];

            // Récupération du libellé du nouvel état
            $query_etat_label = "SELECT etat_intitule FROM sav_etats WHERE id_etat_sav = :nouvel_etat_id";
            $stmt_etat_label = $db->prepare($query_etat_label);
            $stmt_etat_label->bindParam(':nouvel_etat_id', $nouvel_etat_id, PDO::PARAM_INT);
            $stmt_etat_label->execute();
            $etat_label = $stmt_etat_label->fetchColumn();

            $query_update_date_fin = "UPDATE sav SET sav_dateout = :nouvelle_date_fin WHERE sav_id = :sav_id";
            $stmt_update_date_fin = $db->prepare($query_update_date_fin);
            $stmt_update_date_fin->bindParam(':nouvelle_date_fin', $nouvelle_date_fin, PDO::PARAM_STR);
            $stmt_update_date_fin->bindParam(':sav_id', $sav_id, PDO::PARAM_INT);
            $stmt_update_date_fin->execute();

            // Récupération de la date de création
            $query_created_by = "SELECT sav_technicien, sav_datein FROM sav WHERE sav_id = :sav_id";
            $stmt_created_by = $db->prepare($query_created_by);
            $stmt_created_by->bindParam(':sav_id', $sav_id, PDO::PARAM_INT);
            $stmt_created_by->execute();
            $sav_info = $stmt_created_by->fetch(PDO::FETCH_ASSOC);
            $created_by = $sav_info['sav_technicien']; // ID de la personne qui a créé le SAV
            $date_creation =  $sav_info['sav_datein'];

            // Vérifier le format de la date
            if (is_numeric($date_creation)) {
                // Si la date est un timestamp Unix, convertissez-la en format 'YYYY-MM-DD HH:MM:SS'
                $date_creation_formatted = date('Y-m-d H:i:s', $date_creation);
            } else {
                // Sinon, la date est déjà au bon format
                $date_creation_formatted = $date_creation;
            }

            // Insérer un nouvel enregistrement dans la table sauvegarde_etat_info
            $query_insert_new_state = "INSERT INTO sauvgarde_etat_info (sav_id, sauvgarde_etat, sauvgarde_avancement, date_creation, date_update, created_by, updated_by) 
    VALUES (:sav_id, :nouvel_etat_id, :nouvel_avancement, :date_creation, NOW(), :created_by, :updated_by)";
            $stmt_insert_new_state = $db->prepare($query_insert_new_state);
            $stmt_insert_new_state->bindParam(':sav_id', $sav_id, PDO::PARAM_INT);
            $stmt_insert_new_state->bindParam(':nouvel_etat_id', $nouvel_etat_id, PDO::PARAM_INT);
            $stmt_insert_new_state->bindParam(':nouvel_avancement', $nouvel_avancement, PDO::PARAM_STR);
            $stmt_insert_new_state->bindParam(':date_creation', $date_creation_formatted, PDO::PARAM_STR); // Utiliser la date formatée ici
            $stmt_insert_new_state->bindParam(':created_by', $created_by, PDO::PARAM_INT); // Utiliser l'ID de la personne qui a créé le SAV
            $stmt_insert_new_state->bindParam(':updated_by', $sav_technicien_id, PDO::PARAM_INT);
            $stmt_insert_new_state->execute();

            // Récupérer l'ID de la dernière insertion
            $last_inserted_id = $db->lastInsertId();

            // Mettre à jour l'avancement dans la table sav pour qu'il pointe vers la dernière valeur ajoutée dans sauvgarde_etat_info
            $query_update_sav = "UPDATE sav SET sav_avancement = :last_inserted_id WHERE sav_id = :sav_id";
            $stmt_update_sav = $db->prepare($query_update_sav);
            $stmt_update_sav->bindParam(':last_inserted_id', $last_inserted_id, PDO::PARAM_INT);
            $stmt_update_sav->bindParam(':sav_id', $sav_id, PDO::PARAM_INT);
            $stmt_update_sav->execute();

            // Mettre à jour l'état dans la table sav
            $query_update_sav_state = "UPDATE sav SET sav_etats = :nouvel_etat_id WHERE sav_id = :sav_id";
            $stmt_update_sav_state = $db->prepare($query_update_sav_state);
            $stmt_update_sav_state->bindParam(':nouvel_etat_id', $nouvel_etat_id, PDO::PARAM_INT);
            $stmt_update_sav_state->bindParam(':sav_id', $sav_id, PDO::PARAM_INT);
            $stmt_update_sav_state->execute();

            // Envoyer un e-mail de modification au client
            sendSAVModificationEmail($client_info['membre_mail'], $client_info['membre_nom'], $client_info['membre_prenom'], $technicien_nom, $technicien_prenom, $etat_label, $nouvel_avancement, true);

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
    $db = connexionbdd();

    // Préparer la requête SQL pour récupérer les informations détaillées du SAV
    $query = "SELECT * FROM sav WHERE sav_id = :sav_id";

    // Préparation de la requête SQL
    $stmt = $db->prepare($query);

    // Liaison des valeurs des paramètres de requête
    $stmt->bindParam(':sav_id', $sav_id, PDO::PARAM_INT);

    // Exécution de la requête
    $stmt->execute();

    // Récupération des résultats
    $sav = $stmt->fetch(PDO::FETCH_ASSOC);
    // Requête pour récupérer les avancements du SAV
    $query_avancements = "SELECT sauvgarde_avancement FROM sauvgarde_etat_info WHERE sav_id = :sav_id";
    $stmt_avancements = $db->prepare($query_avancements);
    $stmt_avancements->bindParam(':sav_id', $sav_id, PDO::PARAM_INT);
    $stmt_avancements->execute();
    $avancements = $stmt_avancements->fetchAll(PDO::FETCH_ASSOC);

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
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;  // Adresse email de l'expéditeur
        $mail->Password = SMTP_PASSWORD;           // Mot de passe de l'expéditeur
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;
        // Destinataire
        $mail->setFrom(SENDER_EMAIL, SENDER_NAME);
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Liens vers les icônes LSF (Line Awesome) -->
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <!-- Inclure le fichier CSS personnalisé -->
    <link href="styles.css" rel="stylesheet">
    <!-- Ajouter un style personnalisé pour les boutons -->
    <style>
        .custom-btn {
            background-color: #C8356C;
            border-color: #C8356C;
        }

        .custom-btn:hover {
            background-color: #a72a55;
            border-color: #a72a55;
        }
    </style>
</head>

<body>
<?php include('../navbar.php'); ?>

<div class="container mt-4">
    <h2>Modifier SAV n°  <?= $sav['sav_id'] ?> </h2>
    <div class="mb-3">

        <h3>Informations détaillées du SAV</h3>
        <p><strong>Accessoire:</strong> <?= $sav['sav_accessoire'] ?></p>
        <p><strong>Problème:</strong> <?= $sav['sav_probleme'] ?></p>
        <p><strong>Matériel (HT):</strong> <?= $sav['sav_tarifmaterielht'] ?></p>
        <p><strong>Matériel (TTC):</strong> <?= $sav['sav_tarifmaterielttc'] ?></p>
        <p><strong>Main d'œuvre (HT):</strong> <?= $sav['sav_maindoeuvreht'] ?></p>
        <p><strong>Main d'œuvre (TTC):</strong> <?= $sav['sav_maindoeuvrettc'] ?></p>
        <p><strong>Date de fin:</strong> <?php
            if (is_numeric($sav['sav_dateout'])) {
                $dateout = date('Y-m-d ', $sav['sav_dateout']);
            } else {
                $dateout = $sav['sav_dateout'];
            }
            echo $dateout; ?></p>

        <!-- Afficher d'autres informations du SAV selon vos besoins -->
        <div class="mb-3">
            <h3>Avancements du SAV</h3>
            <ol>
                <?php
                try {
                    // Affichage des avancements
                    foreach ($avancements as $avancement) {
                        echo "<li>" . htmlspecialchars($avancement['sauvgarde_avancement']) . "</li>";
                    }
                } catch (PDOException $e) {
                    // Gestion des erreurs
                    echo "Erreur : " . $e->getMessage();
                }
                ?>
            </ol>
        </div>
    </div>
    <form action="" method="post">
        <div class="mb-3">
            <label for="nouvelle_date_fin" class="form-label">Date de fin :</label>
            <!-- Champ pour la nouvelle date de fin -->
            <input type="date" class="form-control" name="nouvelle_date_fin" id="nouvelle_date_fin" value="<?php if (is_numeric($sav['sav_dateout'])) {
                // Si la date est un timestamp Unix, convertissez-la en format 'YYYY-MM-DD'
                $date_fin_formatted = date('Y-m-d', $sav['sav_dateout']);
            } else {
                // Sinon, la date est déjà au bon format
                $date_fin_formatted = $sav['sav_dateout'];
            } echo $date_fin_formatted; ?>">
        </div>

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
            <label for="nouvelle_main_oeuvre_ht" class="form-label">Main d'œuvre (HT) :</label>
            <input type="number" step="0.01" class="form-control" name="nouvelle_main_oeuvre_ht" id="nouvelle_main_oeuvre_ht">
        </div>
        <div class="mb-3">
            <label for="nouveau_materiel_ht" class="form-label">Matériel (HT) :</label>
            <input type="number" step="0.01" class="form-control" name="nouveau_tarif_materiel_ht" id="nouveau_materiel_ht">
        </div>
        <div class="mb-3">
            <label for="nouvel_avancement" class="form-label">Nouvel avancement :</label>
            <!-- Ajoutez l'attribut required pour obliger l'utilisateur à saisir un nouvel avancement -->
            <textarea class="form-control" name="nouvel_avancement" id="nouvel_avancement" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary custom-btn">Enregistrer les modifications</button>
    </form>
    <form action="" method="post" onsubmit="return confirm('Voulez-vous vraiment supprimer ce SAV ?');">
        <input type="hidden" name="delete" value="delete">
        <?php if ($_SESSION['user_type'] === 'admin') : ?>
            <button type="submit" class="btn btn-danger mt-3">Supprimer ce SAV</button>
        <?php endif; ?>
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
