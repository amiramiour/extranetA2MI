<?php
// Récupération des interventions depuis la base de données
global $db;
include 'connexionBD.php';

// Définir la requête SQL par défaut
$query = $db->prepare("SELECT membres.membre_nom, membres.membre_prenom, bi.bi_datein FROM bi INNER JOIN membres ON bi.membre_id = membres.membre_id");

// Vérifier le paramètre de tri
$sort = $_GET['sort'] ?? '';

// Préparer la requête SQL en fonction du paramètre de tri
if ($sort === 'date') {
    $query = $db->prepare("SELECT membres.membre_nom, membres.membre_prenom, bi.bi_datein, DATE_FORMAT(bi.bi_datein, '%d/%m/%Y') AS formatted_date FROM bi INNER JOIN membres ON bi.membre_id = membres.membre_id ORDER BY bi.bi_datein ASC");
} elseif ($sort === 'name') {
    $query = $db->prepare("SELECT membres.membre_nom, membres.membre_prenom, bi.bi_datein FROM bi INNER JOIN membres ON bi.membre_id = membres.membre_id ORDER BY membres.membre_nom ASC");
} else {
    // Si aucun tri spécifique n'est demandé, sélectionnez toutes les colonnes
    $query = $db->prepare("SELECT membres.membre_nom, membres.membre_prenom, bi.bi_datein FROM bi INNER JOIN membres ON bi.membre_id = membres.membre_id");
}

$query->execute();
$interventions = $query->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des interventions</title>
    <!-- Inclure le fichier CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Inclure le fichier CSS personnalisé -->
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>

<!-- Inclure le navbar -->
<?php include('navbar.php'); ?>

<h1 class="text-center mt-3">Liste des interventions</h1>

<div class="container">
    <!-- Boutons de tri -->
    <div class="d-flex justify-content-between mb-3">
        <a href="?sort=date" class="btn btn-primary" style="background-color: #C8356C;">Trier par date croissante</a>
        <a href="#" class="btn btn-primary" style="background-color: #C8356C;">Créer un bon d'intervention</a>
        <a href="?sort=name" class="btn btn-primary" style="background-color: #C8356C;">Trier par nom croissant</a>
    </div>


    <!-- Liste des interventions -->
    <?php foreach($interventions as $intervention): ?>
        <div class="intervention text-center">
            <a href="#" style="color: #C8356C;"><?php echo $intervention['membre_nom'] . ' ' . $intervention['membre_prenom']; ?></a>
            <span style="color: #C8356C;">→</span>
            <a href="#" style="color: #C8356C;">
                <?php
                if (isset($intervention['formatted_date'])) {
                    echo $intervention['formatted_date'];
                } else {
                    echo date('d/m/Y', strtotime($intervention['bi_datein']));
                }
                ?>
            </a>
        </div>
    <?php endforeach; ?>

</div>

<!-- Inclure le fichier JavaScript de Bootstrap à la fin du corps -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
