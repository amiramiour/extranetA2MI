<?php
session_start();

// Vérification si l'utilisateur est connecté et si son type est différent de "client"
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_mail']) || $_SESSION['user_type'] === 'client') {
    // Si l'utilisateur n'est pas connecté ou est un client, redirigez-le ou affichez un message d'erreur
    header("Location: ../connexion.php");
    exit;
}

// Inclusion du fichier de connexion à la base de données
require_once '../ConnexionBD.php';
$connexion = connexionbdd();

// Requête pour récupérer la liste des fournisseurs
$query = "SELECT idFournisseur, nomFournisseur FROM fournisseur WHERE active = 1 ORDER BY nomFournisseur";
$stmt = $connexion->prepare($query);
$stmt->execute();
$fournisseurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Nombre de fournisseurs
$nbFournisseurs = count($fournisseurs);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des fournisseurs</title>
    <!-- Intégration de Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Liens vers les icônes LSF (Line Awesome) -->
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
</head>
<body>
<?php include('../navbar.php'); ?>

<div class="container mt-5">
    <h1>Liste des fournisseurs</h1>
    <!-- Phrase indiquant le nombre de fournisseurs -->
    <h3>Il y a <?php echo $nbFournisseurs; ?> fournisseur(s)</h3>
    <!-- Boutons d'ajout et de suppression -->
    <div class="mb-3">
        <a href="ajouter_fournisseur.php" class="btn" style="background-color: #C8356C;"><i class="las la-upload"></i> Ajouter un fournisseur</a>
        <a href="supprimer_fournisseur.php" class="btn" style="background-color: #C8356C;"><i class="las la-trash"></i> Supprimer un fournisseur</a>
    </div>
    <!-- Tableau pour afficher les fournisseurs -->
    <table class="table">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Nom du fournisseur</th>
            <!-- Ajoutez ici plus de colonnes si nécessaire -->
        </tr>
        </thead>
        <tbody>
        <?php foreach ($fournisseurs as $fournisseur) : ?>
            <tr>
                <td><?= $fournisseur['idFournisseur']; ?></td>
                <td><?= $fournisseur['nomFournisseur']; ?></td>
                <!-- Ajoutez ici plus de colonnes si nécessaire -->
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
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
