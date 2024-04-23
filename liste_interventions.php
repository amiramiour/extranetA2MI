<?php
// Récupération des interventions depuis la base de données
include 'connexionBD.php';

$db = connexionbdd();

// Définir la requête SQL par défaut
$query = $db->prepare("SELECT membres.membre_id, membres.membre_nom, membres.membre_prenom, bi.bi_datein FROM bi INNER JOIN membres ON bi.membre_id = membres.membre_id");

// Vérifier le paramètre de tri
$sort = $_GET['sort'] ?? '';

// Préparer la requête SQL en fonction du paramètre de tri
if ($sort === 'date') {
    $query = $db->prepare("SELECT membres.membre_id, membres.membre_nom, membres.membre_prenom, bi.bi_datein, DATE_FORMAT(bi.bi_datein, '%d/%m/%Y') AS formatted_date FROM bi INNER JOIN membres ON bi.membre_id = membres.membre_id ORDER BY bi.bi_datein ASC");
} elseif ($sort === 'name') {
    $query = $db->prepare("SELECT membres.membre_id, membres.membre_nom, membres.membre_prenom, bi.bi_datein FROM bi INNER JOIN membres ON bi.membre_id = membres.membre_id ORDER BY membres.membre_nom ASC");
} else {
    // Si aucun tri spécifique n'est demandé, sélectionnez toutes les colonnes
    $query = $db->prepare("SELECT membres.membre_id, membres.membre_nom, membres.membre_prenom, bi.bi_datein FROM bi INNER JOIN membres ON bi.membre_id = membres.membre_id");
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
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<!-- Inclure le navbar -->
<?php include('navbar.php'); ?>

<div class="container mt-3">
    <!-- Boutons de tri -->
    <div class="d-flex justify-content-between centered-buttons">
        <a href="?sort=date" class="btn btn-primary" style="background-color: #C8356C;">Trier par date croissante</a>
        <a href="bi_form.php" class="btn btn-primary" style="background-color: #C8356C;">Créer un bon d'intervention</a>
        <a href="?sort=name" class="btn btn-primary" style="background-color: #C8356C;">Trier par nom croissant</a>
    </div>

    <!-- Nombre d'interventions -->
    <div class="text-center mt-3">
        <p style="color: #C8356C; font-size: larger; font-weight: bold; text-decoration: underline;">Il y a : <?php echo count($interventions); ?> intervention(s) </p>
    </div>

    <!-- Liste des interventions -->
    <div class="d-flex flex-wrap justify-content-center mt-3">
        <?php foreach($interventions as $intervention): ?>
            <div class="client-card">
                <a href="bi_details.php?membre_id=<?php echo $intervention['membre_id']; ?>" class="client-link">
                    <img src="images/icon.png" style="width: 50px; height: 50px; alt="Icon" class="icon-size" >
                    <h3><?php echo $intervention['membre_nom']; ?></h3>
                    <p><?php echo $intervention['membre_prenom']; ?></p>
                    <p><?php echo date('d/m/Y', $intervention['bi_datein']); ?></p>

                </a>
            </div>
        <?php endforeach; ?>
    </div>

</div>


<!-- Inclure le fichier JavaScript de Bootstrap à la fin du corps -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
