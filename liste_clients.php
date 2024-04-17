<?php
//récupération des nom et prénoms des membres depuis la base de données
global $db;
include 'connexionBD.php';
$query = $db->prepare("SELECT membre_nom, membre_prenom FROM membres WHERE membre_type='client'");
$query->execute();
$membres = $query->fetchAll();
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des membres</title>
    <!-- Inclure le fichier CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Inclure le fichier CSS personnalisé -->
    <link href="styles.css" rel="stylesheet">
</head>
<body>

<!-- Inclure le navbar -->
<?php include('navbar.php'); ?>

<h1>Liste des membres</h1>
<table border="1">
    <tr>
        <th>Nom</th>
        <th>Prénom</th>
    </tr>
    <?php foreach($membres as $membre): ?>
        <tr>
            <td><?php echo $membre['membre_nom']; ?></td>
            <td><?php echo $membre['membre_prenom']; ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<!-- Inclure le fichier JavaScript de Bootstrap à la fin du corps -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</html>
