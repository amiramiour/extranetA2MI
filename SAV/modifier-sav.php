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

        // Préparer la requête SQL pour supprimer le SAV
        $query = "DELETE FROM sav WHERE sav_id = :sav_id";

        // Préparation de la requête SQL
        $stmt = $db->prepare($query);

        // Liaison des valeurs des paramètres de requête
        $stmt->bindValue(':sav_id', $sav_id, PDO::PARAM_INT);

        // Exécution de la requête
        $stmt->execute();

        // Rediriger vers la page d'accueil après la suppression
        header('Location: sav.php');
        exit();
    } catch (PDOException $e) {
        // Gérer les erreurs
        $error = "Erreur : " . $e->getMessage();
    }
}

// Vérifier si le formulaire de modification a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nouvel_etat = $_POST['nouvel_etat'];
    $nouvel_avancement = $_POST['nouvel_avancement'];

    try {
        // Connexion à la base de données en utilisant la fonction connexionbdd()
        $db = connexionbdd();

        // Préparer la requête SQL pour mettre à jour le SAV
        $query = "UPDATE sav SET sav_avancement = :nouvel_avancement, sav_etat = :nouvel_etat WHERE sav_id = :sav_id";

        // Préparation de la requête SQL
        $stmt = $db->prepare($query);

        // Liaison des valeurs des paramètres de requête
        $stmt->bindValue(':nouvel_avancement', $nouvel_avancement, PDO::PARAM_STR);
        $stmt->bindValue(':nouvel_etat', $nouvel_etat, PDO::PARAM_STR);
        $stmt->bindValue(':sav_id', $sav_id, PDO::PARAM_INT);

        // Exécution de la requête
        $stmt->execute();

        // Rediriger vers la page d'accueil après la modification
        header('Location: sav.php');
        exit();
    } catch (PDOException $e) {
        // Gérer les erreurs
        $error = "Erreur : " . $e->getMessage();
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
            <label for="nouvel_etat" class="form-label">Nouvel état :</label>
            <select name="nouvel_etat" id="nouvel_etat" class="form-select" required>
                <option value="Réceptionné" <?= ($sav['sav_etat'] === 'Réceptionné') ? 'selected' : ''; ?>>Réceptionné</option>
                <option value="En cours" <?= ($sav['sav_etat'] === 'En cours') ? 'selected' : ''; ?>>En cours</option>
                <option value="En attente" <?= ($sav['sav_etat'] === 'En attente') ? 'selected' : ''; ?>>En attente</option>
                <option value="Terminé" <?= ($sav['sav_etat'] === 'Terminé') ? 'selected' : ''; ?>>Terminé</option>
                <option value="Rendu au client" <?= ($sav['sav_etat'] === 'Rendu au client') ? 'selected' : ''; ?>>Rendu au client</option>
                <!-- Ajoutez d'autres états si nécessaire -->
            </select>
        </div>
        <div class="mb-3">
            <label for="nouvel_avancement" class="form-label">Nouvel avancement :</label>
            <textarea class="form-control" name="nouvel_avancement" id="nouvel_avancement" rows="3" required><?= $sav['sav_avancement'] ?></textarea>
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
