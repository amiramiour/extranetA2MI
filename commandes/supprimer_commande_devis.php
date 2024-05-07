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
if (!isset($_GET['cmd_devis_id']) || empty($_GET['cmd_devis_id'])) {
    header('Location: commandes_devis.php'); // Rediriger si l'ID n'est pas présent
    exit();
}

// Récupérer l'ID du SAV depuis l'URL
$cmd_devis_id = $_GET['cmd_devis_id'];

// Vérifier si le formulaire de suppression a été soumis
if (isset($_POST['delete']) && $_POST['delete'] === 'delete') {
    try {
        // Connexion à la base de données en utilisant la fonction connexionbdd()
        $connexion = connexionbdd();

      /*  // Récupérer les informations du SAV avant la désactivation
        $query_sav_info = "SELECT s.sav_etats, s.sav_technicien, e.etat_intitule ,s.sav_datein FROM sav s INNER JOIN sav_etats e ON s.sav_etats = e.id_etat_sav WHERE s.sav_id = :sav_id";
        $stmt_sav_info = $connexion->prepare($query_sav_info);
        $stmt_sav_info->bindParam(':sav_id', $sav_id, PDO::PARAM_INT);
        $stmt_sav_info->execute();
        $sav_info = $stmt_sav_info->fetch(PDO::FETCH_ASSOC);
*/

        // Mettre à jour le champ active à 0 dans la table SAV
        $query_delete_sav = "UPDATE commande_devis SET active_commande = 0 WHERE cmd_devis_id = :cmd_devis_id";
        $stmt_delete_sav = $connexion->prepare($query_delete_sav);
        $stmt_delete_sav->bindParam(':cmd_devis_id', $cmd_devis_id, PDO::PARAM_INT);
        $stmt_delete_sav->execute();
/*
        $last_creation_date = $sav_info['sav_datein'];

// Si aucune entrée n'a été trouvée, utilisez la date actuelle

        // Si aucune entrée n'a été trouvée, utilisez la date actuelle
        if (!$last_creation_date) {
            $last_creation_date = date('Y-m-d H:i:s');
        } else {
            // Vérifier si la date est au format Unix timestamp
            if (is_numeric($last_creation_date)) {
                // Si la date est un timestamp Unix, convertissez-la en format 'YYYY-MM-DD HH:MM:SS'
                $last_creation_date = date('Y-m-d H:i:s', $last_creation_date);
            } else {
                // Sinon, la date est déjà au bon format
                $last_creation_date = $last_creation_date;
            }
        }


// Insérer une entrée dans la table sauvgarde_etat_info pour enregistrer la désactivation
        $query_insert_sav_history = "INSERT INTO sauvgarde_etat_info (sav_id, sauvgarde_etat, sauvgarde_avancement, date_creation, date_update, created_by, updated_by) 
                            VALUES (:sav_id, :sauvgarde_etat, 'Supprimé', :date_creation, NOW(), :created_by, :updated_by)";
        $stmt_insert_sav_history = $connexion->prepare($query_insert_sav_history);
        $stmt_insert_sav_history->bindParam(':sav_id', $sav_id, PDO::PARAM_INT);
        $stmt_insert_sav_history->bindParam(':sauvgarde_etat', $sav_info['sav_etats'], PDO::PARAM_INT);
        $stmt_insert_sav_history->bindParam(':date_creation', $last_creation_date, PDO::PARAM_STR);

        $stmt_insert_sav_history->bindParam(':created_by', $sav_info['sav_technicien'], PDO::PARAM_INT); // Utiliser l'ID de la personne qui a créé le SAV
        $stmt_insert_sav_history->bindParam(':updated_by', $_SESSION['user_id'], PDO::PARAM_INT); // Utiliser l'ID de la personne connectée
        $stmt_insert_sav_history->execute();*/

        header('Location: commandes_devis.php');

        // Rediriger vers la page d'accueil après "suppression"
        exit();
    } catch (PDOException $e) {
        // Gérer les erreurs
        $error = "Erreur : " . $e->getMessage();
    }
}
// Récupérer les informations détaillées du SAV

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer un SAV</title>
    <!-- Inclure le fichier CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Inclure le fichier CSS personnalisé -->
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>

<!-- Inclure le navbar -->
<?php include('../navbar.php'); ?>

<div class="container mt-3">
    <h1 class="text-center">Supprimer une commande ou un devis</h1>

    <div class="row">
        <div class="col-md-6 offset-md-3">
            <form method="POST">
                <div class="alert alert-danger" role="alert">
                    Êtes-vous sûr de vouloir supprimer cette commande ?
                </div>
                <button  type="submit" name="delete" value="delete" class="btn btn-danger">Confirmer la suppression</button>
                <a href="sav.php" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>

<!-- Inclure le fichier JavaScript de Bootstrap à la fin du corps -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

