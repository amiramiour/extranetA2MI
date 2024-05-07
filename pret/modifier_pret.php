<?php
include "../gestion_session.php";
require_once '../config.php';

// Vérifier si l'identifiant du prêt à modifier est spécifié dans l'URL
if(isset($_GET['id'])) {
    $pret_id = $_GET['id'];
} else {
    // Rediriger vers une page d'erreur si l'identifiant du prêt n'est pas spécifié
    header("Location: erreur.php");
    exit();
}

// Inclure la connexion à la base de données
include('../ConnexionBD.php');

try {
    // Etablir la connexion à la base de données
    $db = connexionbdd();

    // Requête SQL pour récupérer les détails du prêt à modifier
    $query = "SELECT pret_caution, pret_mode, pret_dateout, commentaire, pret_etat FROM pret WHERE pret_id = :id";

    // Préparer la requête SQL
    $stmt = $db->prepare($query);

    // Liaison des valeurs des paramètres de requête
    $stmt->bindParam(':id', $pret_id, PDO::PARAM_INT);

    // Exécution de la requête
    $stmt->execute();

    // Récupération du résultat
    $pret = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si le prêt existe
    if($pret) {
        // Afficher le formulaire de modification avec les champs pré-remplis
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Modifier Prêt</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            <link rel="stylesheet" type="text/css" href="../css/style.css">
        </head>
        <body>
        <?php include('../navbar.php'); ?>
        <div class="container mt-3">
            <h1>Modifier Prêt</h1>
            <form action="traitement_modification_pret.php" method="post">
                <div class="form-group">
                    <label for="caution">Caution</label>
                    <input type="text" class="form-control" id="caution" name="caution" value="<?php echo $pret['pret_caution']; ?>">
                </div>
                <div class="form-group">
                    <label for="mode_paiement">Mode de paiement</label>
                    <input type="text" name="mode_paiement" value="Chèque" readonly>
                </div>
                <div class="form-group">
                    <label for="date_rendu">Date de rendu</label>
                    <input type="date" class="form-control" id="date_rendu" name="date_rendu" value="<?php echo $pret['pret_dateout']; ?>">
                </div>
                <div class="form-group">
                    <label for="etat">État</label>
                    <select class="form-control" id="etat" name="etat">
                        <option value="1" <?php if ($pret['pret_etat'] == 1) echo "selected"; ?>>En cours</option>
                        <option value="2" <?php if ($pret['pret_etat'] == 2) echo "selected"; ?>>Terminé</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="commentaire">Commentaire</label>
                    <textarea class="form-control" id="commentaire" name="commentaire"><?php echo $pret['commentaire']; ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                <input type="hidden" name="pret_id" value="<?php echo $pret_id; ?>">
            </form>
        </div>
        </body>
        </html>

        <?php
    } else {
        // Afficher un message d'erreur si le prêt n'est pas trouvé
        echo "Aucun prêt trouvé avec cet identifiant.";
    }
} catch (PDOException $e) {
    // Afficher un message d'erreur en cas d'erreur PDO
    echo "Erreur : " . $e->getMessage();
}
?>
