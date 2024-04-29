<?php
session_start(); // Démarrer la session si ce n'est pas déjà fait

// Vérifier si l'utilisateur est connecté et est un technicien
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_mail']) || $_SESSION['user_type'] === 'client') {
    // Si l'utilisateur n'est pas connecté ou est un client, redirigez-le ou affichez un message d'erreur
    header("Location: ../connexion.php");
    exit;
}
include '../ConnexionBD.php';

$db = connexionbdd();

// Définir la requête SQL par défaut
$query = $db->prepare("SELECT DISTINCT membres.membre_id, membres.membre_nom, membres.membre_prenom FROM membres INNER JOIN bi ON membres.membre_id = bi.membre_id");

// Vérifier le paramètre de tri
$sort = $_GET['sort'] ?? '';

// Préparer la requête SQL en fonction du paramètre de tri
if ($sort === 'date') {
    $query = $db->prepare("SELECT membres.membre_id, membres.membre_nom, membres.membre_prenom, bi.bi_datein, DATE_FORMAT(bi.bi_datein, '%d/%m/%Y') AS formatted_date FROM bi INNER JOIN membres ON bi.membre_id = membres.membre_id ORDER BY bi.bi_datein ASC");
} elseif ($sort === 'name') {
    $query = $db->prepare("SELECT membres.membre_id, membres.membre_nom, membres.membre_prenom, bi.bi_datein FROM bi INNER JOIN membres ON bi.membre_id = membres.membre_id ORDER BY membres.membre_nom ASC");
}

$query->execute();
$membres_avec_bi = $query->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des membres avec bons d'interventions</title>
    <!-- Inclure le fichier CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Inclure le fichier CSS personnalisé -->
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>

<!-- Inclure le navbar -->
<?php include '../navbar.php'; ?>

<div class="container mt-3">
    <!-- Boutons de tri -->
    <div class="d-flex justify-content-between centered-buttons">
        <a href="?sort=date" class="btn btn-primary" style="background-color: #C8356C;">Trier par date croissante</a>
        <!--  <a href="bi_form.php" class="btn btn-primary" style="background-color: #C8356C;">Créer un bon d'intervention</a> -->
          <a href="?sort=name" class="btn btn-primary" style="background-color: #C8356C;">Trier par nom croissant</a>
      </div>

      <!-- Nombre de membres -->
    <div class="text-center mt-3">
        <p style="color: #C8356C; font-size: larger; font-weight: bold; text-decoration: underline;">Il y a : <?php echo count($membres_avec_bi); ?> membre(s) avec des bons d'interventions</p>
    </div>

    <!-- Liste des membres avec bons d'interventions -->
    <div class="d-flex flex-wrap justify-content-center mt-3">
        <?php foreach($membres_avec_bi as $membre): ?>
            <div class="client-card">
                <a href="bi_details.php?membre_id=<?php echo $membre['membre_id']; ?>" class="client-link">
                    <img src="../images/icon.png" style="width: 50px; height: 50px; alt=" Icon" class="icon-size" >
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
