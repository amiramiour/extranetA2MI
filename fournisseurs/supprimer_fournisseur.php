<?php
include "../gestion_session.php";

// Inclusion du fichier de connexion à la base de données
require_once '../ConnexionBD.php';
$connexion = connexionbdd();

// Traitement du formulaire de suppression de fournisseur
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $fournisseur_id = $_POST["fournisseur_id"];

    // Requête pour désactiver le fournisseur
    $query = "UPDATE fournisseur SET active = 0 WHERE idFournisseur = :fournisseur_id";
    $stmt = $connexion->prepare($query);
    $stmt->bindParam(':fournisseur_id', $fournisseur_id);

    // Exécution de la requête
    if ($stmt->execute()) {
        // Redirection vers la liste des fournisseurs
        header("Location: liste_fournisseurs.php");
        exit;
    } else {
        // Gestion de l'erreur si la désactivation échoue
        $erreur = "Une erreur est survenue lors de la désactivation du fournisseur.";
    }
}

// Requête pour récupérer les fournisseurs actifs
$query = "SELECT idFournisseur, nomFournisseur FROM fournisseur WHERE active = 1 ORDER BY nomFournisseur";
$stmt = $connexion->prepare($query);
$stmt->execute();
$fournisseurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer un fournisseur</title>
    <!-- Intégration de Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Liens vers les icônes LSF (Line Awesome) -->
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
</head>
<body>
<?php include('../navbar.php'); ?>

<div class="container mt-5">
    <h1>Supprimer un fournisseur</h1>
    <!-- Formulaire pour choisir le fournisseur à désactiver -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <label for="fournisseur_id">Choisir un fournisseur :</label>
            <select class="form-control" id="fournisseur_id" name="fournisseur_id" required>
                <?php foreach ($fournisseurs as $fournisseur) : ?>
                    <option value="<?= $fournisseur['idFournisseur']; ?>"><?= $fournisseur['nomFournisseur']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn" style="background-color: #C8356C;" name="submit"><i class="las la-trash"></i> Désactiver</button>
        <!-- Affichage de l'erreur si la désactivation échoue -->
        <?php if (isset($erreur)) : ?>
            <div class="alert alert-danger mt-3" role="alert">
                <?php echo $erreur; ?>
            </div>
        <?php endif; ?>
    </form>
</div>

<!-- Intégration du script Bootstrap (jQuery requis) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

<?php
// Fermeture de la connexion à la base de données
$connexion = null;
?>
