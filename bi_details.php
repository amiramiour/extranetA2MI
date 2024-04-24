<?php
// Inclure le fichier de connexion à la base de données
global $db;
include 'connexionBD.php';

$db = connexionbdd();

// Vérifier si l'identifiant du membre est passé dans l'URL
if(isset($_GET['membre_id'])) {
    // Récupérer l'identifiant du membre depuis l'URL
    $membre_id = $_GET['membre_id'];

    // Préparer la requête SQL pour récupérer les bons d'intervention associés à ce membre
    $query = $db->prepare("SELECT * FROM bi WHERE membre_id = ?");
    $query->execute([$membre_id]);
    $bons_intervention = $query->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails des bons d'intervention</title>
    <!-- Inclure le fichier CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Inclure le fichier CSS personnalisé -->
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<!-- Inclure le navbar -->
<?php include('navbar.php'); ?>

<div class="container mt-3">
    <!-- Titre de la page -->
    <h1>Détails des bons d'intervention</h1>

    <!-- Vérifier si des bons d'intervention sont disponibles -->
    <?php if(isset($bons_intervention) && !empty($bons_intervention)): ?>
        <!-- Afficher les détails des bons d'intervention -->
        <div class="row">
            <?php foreach($bons_intervention as $bi): ?>
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">BI n° <?php echo $bi['bi_id']; ?></h5>
                            <p>Date de création : <?php echo date('d/m/Y', strtotime($bi['bi_datein'])); ?></p>
                            <!-- Afficher d'autres détails de l'intervention ici -->
                            <p>Facturation : <?php echo $bi['bi_facturation']; ?></p>
                            <p>Heure d'arrivée : <?php echo $bi['bi_heurearrive']; ?></p>
                            <p>Heure de départ : <?php echo $bi['bi_heuredepart']; ?></p>
                            <!-- Ajoutez d'autres champs selon vos besoins -->
                            <!-- Bouton Modifier -->
                            <button type="button" class="btn btn-danger">Supprimer</button>
                            <a href="modification_bi.php?bi_id=<?php echo $bi['bi_id']; ?>" class="btn btn-primary custom-btn">Modifier</a>
                            <a href="bi_form.php?membre_id=<?php echo $membre_id; ?>" class="btn btn-primary custom-bleu-btn">Ajouter</a>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    <?php else: ?>
        <p>Aucun bon d'intervention trouvé pour ce membre.</p>
    <?php endif; ?>

</div>

<!-- Inclure le fichier JavaScript de Bootstrap à la fin du corps -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
