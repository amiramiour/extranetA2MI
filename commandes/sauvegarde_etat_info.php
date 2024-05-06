<?php
include '../gestion_session.php';
require_once '../config.php';
include '../ConnexionBD.php';
include '../navbar.php';
$pdo = connexionbdd();

$stmt = $pdo->query("SELECT * FROM sauvgarde_etat_info_commande");
$sauvegardes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de Sauvegarde Commande</title>
</head>
<body>
<h1>Enregistrements dans la table sauvegarde_etat_info_commande</h1>
<table border="1">
    <thead>
    <tr>
        <th>ID Sauvegarde</th>
        <th>Commande ID</th>
        <th>Sauvegarde État</th>
        <th>Créé Par</th>
        <th>Date de Création</th>
        <th>Modifié Par</th>
        <th>Date de Modification</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($sauvegardes as $sauvegarde): ?>
        <tr>
            <td><?= $sauvegarde['id_sauvgarde_etat'] ?></td>
            <td><?= $sauvegarde['commande_id'] ?></td>
            <td><?= $sauvegarde['sauvgarde_etat'] ?></td>
            <td><?= $sauvegarde['created_by'] ?></td>
            <td><?= $sauvegarde['date_creation'] ?></td>
            <td><?= $sauvegarde['updated_by'] ?></td>
            <td><?= $sauvegarde['date_update'] ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>