<?php
// Inclure le fichier de connexion à la base de données
require_once '../connexionBD.php';

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
        $query_sav_info = "SELECT sav_etats FROM sav WHERE sav_id = :sav_id";
        $stmt_sav_info = $db->prepare($query_sav_info);
        $stmt_sav_info->bindValue(':sav_id', $sav_id, PDO::PARAM_INT);
        $stmt_sav_info->execute();
        $sav_info = $stmt_sav_info->fetch(PDO::FETCH_ASSOC);

        // Mettre à jour le champ active à 0 dans la table SAV
        $query_delete_sav = "UPDATE sav SET active = 0 WHERE sav_id = :sav_id";
        $stmt_delete_sav = $db->prepare($query_delete_sav);
        $stmt_delete_sav->bindValue(':sav_id', $sav_id, PDO::PARAM_INT);
        $stmt_delete_sav->execute();

        // Récupérer la date de création de la dernière entrée dans la table sauvgarde_etat_info
        $query_last_creation_date = "SELECT date_creation FROM sauvgarde_etat_info WHERE sav_id = :sav_id ORDER BY id_sauvgarde_etat DESC LIMIT 1";
        $stmt_last_creation_date = $db->prepare($query_last_creation_date);
        $stmt_last_creation_date->bindValue(':sav_id', $sav_id, PDO::PARAM_INT);
        $stmt_last_creation_date->execute();
        $last_creation_date = $stmt_last_creation_date->fetchColumn();

        // Insérer une entrée dans la table sauvgarde_etat_info pour enregistrer la désactivation
        $query_insert_sav_history = "INSERT INTO sauvgarde_etat_info (sav_id, sauvgarde_etat, sauvgarde_avancement, date_creation, date_update, created_by, updated_by) 
                                    VALUES (:sav_id, :sauvgarde_etat, 'Supprimé', :date_creation, NOW(), 1, 1)";
        $stmt_insert_sav_history = $db->prepare($query_insert_sav_history);
        $stmt_insert_sav_history->bindValue(':sav_id', $sav_id, PDO::PARAM_INT);
        $stmt_insert_sav_history->bindValue(':sauvgarde_etat', $sav_info['sav_etats'], PDO::PARAM_INT);
        $stmt_insert_sav_history->bindValue(':date_creation', $last_creation_date, PDO::PARAM_STR);
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
            $db = connexionbdd();

            // Récupérer les informations de la dernière entrée dans la table sauvgarde_etat_info
            $query_last_info = "SELECT sauvgarde_etat, date_creation, created_by FROM sauvgarde_etat_info WHERE sav_id = :sav_id ORDER BY id_sauvgarde_etat DESC LIMIT 1";
            $stmt_last_info = $db->prepare($query_last_info);
            $stmt_last_info->bindValue(':sav_id', $sav_id, PDO::PARAM_INT);
            $stmt_last_info->execute();
            $last_info_row = $stmt_last_info->fetch(PDO::FETCH_ASSOC);
            $last_etat_id = $last_info_row['sauvgarde_etat'];
            $date_creation = $last_info_row['date_creation'];
            $created_by = $last_info_row['created_by'];

            // Insérer un nouvel enregistrement dans la table sauvgarde_etat_info
            $query_insert_new_state = "INSERT INTO sauvgarde_etat_info (sav_id, sauvgarde_etat, sauvgarde_avancement, date_creation, date_update, created_by, updated_by) 
                                    VALUES (:sav_id, :nouvel_etat_id, :nouvel_avancement, :date_creation, NOW(), :created_by, 1)";
            $stmt_insert_new_state = $db->prepare($query_insert_new_state);
            $stmt_insert_new_state->bindValue(':sav_id', $sav_id, PDO::PARAM_INT);
            $stmt_insert_new_state->bindValue(':nouvel_etat_id', $nouvel_etat_id, PDO::PARAM_INT);
            $stmt_insert_new_state->bindValue(':nouvel_avancement', $nouvel_avancement, PDO::PARAM_STR);
            $stmt_insert_new_state->bindValue(':date_creation', $date_creation, PDO::PARAM_STR);
            $stmt_insert_new_state->bindValue(':created_by', $created_by, PDO::PARAM_INT);
            $stmt_insert_new_state->execute();

            // Récupérer l'ID de la dernière insertion
            $last_inserted_id = $db->lastInsertId();

            // Mettre à jour l'avancement dans la table sav pour qu'il pointe vers la dernière valeur ajoutée dans sauvgarde_etat_info
            $query_update_sav = "UPDATE sav SET sav_avancement = :last_inserted_id WHERE sav_id = :sav_id";
            $stmt_update_sav = $db->prepare($query_update_sav);
            $stmt_update_sav->bindValue(':last_inserted_id', $last_inserted_id, PDO::PARAM_INT);
            $stmt_update_sav->bindValue(':sav_id', $sav_id, PDO::PARAM_INT);
            $stmt_update_sav->execute();

            // Mettre à jour l'état dans la table sav
            $query_update_sav_state = "UPDATE sav SET sav_etats = :nouvel_etat_id WHERE sav_id = :sav_id";
            $stmt_update_sav_state = $db->prepare($query_update_sav_state);
            $stmt_update_sav_state->bindValue(':nouvel_etat_id', $nouvel_etat_id, PDO::PARAM_INT);
            $stmt_update_sav_state->bindValue(':sav_id', $sav_id, PDO::PARAM_INT);
            $stmt_update_sav_state->execute();

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
    $stmt->bindValue(':sav_id', $sav_id, PDO::PARAM_INT);

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
            <textarea class="form-control" name="nouvel_avancement" id="nouvel_avancement" rows="3"><?= $sav['sav_avancement'] ?></textarea>
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
