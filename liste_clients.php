<?php
include 'connexionBD.php';
// Récupération des noms et prénoms des membres depuis la base de données
$db = connexionbdd();
$query = $db->prepare("SELECT membre_nom, membre_prenom FROM membres WHERE membre_type='client'");
$query->execute();
$membres = $query->fetchAll();

// Vérifier le paramètre de tri
$sort = $_GET['sort'] ?? '';

// Préparer la requête SQL en fonction du paramètre de tri
if ($sort === 'nom') {
    $query = $db->prepare("SELECT membre_nom, membre_prenom FROM membres WHERE membre_type='client' ORDER BY membre_nom ASC");
} elseif ($sort === 'entreprise') {
    $query = $db->prepare("SELECT membre_nom, membre_prenom FROM membres WHERE membre_type='client' ORDER BY membre_entreprise ASC");
} else {
    $query = $db->prepare("SELECT membre_nom, membre_prenom FROM membres WHERE membre_type='client'");
}

$query->execute();
$membres = $query->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des membres</title>
    <!-- Inclure le fichier CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Inclure le fichier CSS personnalisé -->
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<!-- Inclure le navbar -->
<?php include('navbar.php'); ?>

<h1 class="text-center mt-3">Liste des clients</h1>

<div class="container mt-3">
    <!-- Boutons de tri -->
    <div class="d-flex justify-content-between centered-buttons"> <!-- Ajout de la classe centered-buttons -->
        <a href="?sort=nom" class="btn btn-primary" style="background-color: #C8356C;">Trier par nom</a>
        <a href="?sort=entreprise" class="btn btn-primary" style="background-color: #C8356C;">Trier par entreprise</a>
    </div>

    <!-- Nombre de clients -->
    <div class="text-center mt-3">
        <p style="color: #C8356C; font-size: larger; font-weight: bold; text-decoration: underline;">Il y a : <?php echo count($membres)?> client(s) </p>
    </div>

    <!-- Liste des clients -->
    <div class="client-list mt-3">
        <?php foreach($membres as $membre): ?>
            <div class="client-card">
                <a href="#" class="client-link">
                    <img src="images/icon.png" alt="Photo du client" style="width: 50px; height: 50px;">
                    <h3><?php echo $membre['membre_nom']; ?></h3>
                    <p><?php echo $membre['membre_prenom']; ?></p>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>



<!-- Inclure le fichier JavaScript de Bootstrap à la fin du corps -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
