<?php
include "../gestion_session.php";
// Inclusion du fichier de connexion à la base de données
require_once '../ConnexionBD.php';
$connexion = connexionbdd();

// Traitement du formulaire d'ajout de fournisseur
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ajouter"])) {
    $nomFournisseur = $_POST["nom_fournisseur"];

    // Requête d'insertion du nouveau fournisseur
    $query = "INSERT INTO fournisseur (nomFournisseur) VALUES (:nomFournisseur)";
    $stmt = $connexion->prepare($query);
    $stmt->bindParam(':nomFournisseur', $nomFournisseur);

    // Exécution de la requête
    if ($stmt->execute()) {
        // Redirection vers la liste des fournisseurs si l'insertion est réussie
        header("Location: liste_fournisseurs.php");
        exit;
    } else {
        // Gestion de l'erreur si l'insertion échoue
        $erreur = "Une erreur est survenue lors de l'ajout du fournisseur.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un fournisseur</title>
    <!-- Intégration de Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Liens vers les icônes LSF (Line Awesome) -->
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
</head>
<body>
<?php include('../navbar.php'); ?>

<div class="container mt-5">
    <h1>Ajouter un fournisseur</h1>
    <!-- Formulaire pour ajouter un fournisseur -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <label for="nom_fournisseur">Nom du fournisseur :</label>
            <input type="text" class="form-control" id="nom_fournisseur" name="nom_fournisseur" required>
        </div>
        <button type="submit" class="btn" style="background-color: #C8356C;" name="ajouter"><i class="las la-plus-circle"></i> Ajouter</button>
        <!-- Affichage de l'erreur si l'insertion échoue -->
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
