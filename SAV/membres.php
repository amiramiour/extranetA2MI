<?php
// Inclure le fichier de connexion à la base de données
include '../ConnexionBD.php';
include "../gestion_session.php";

$pdo = connexionbdd();

// Récupérer les informations des membres depuis la base de données
$query = $pdo->query("SELECT membre_id as client_id, membre_entreprise, membre_nom, membre_prenom, membre_adresse, membre_adresse_comp, membre_cp, membre_ville, membre_tel, membre_mail, membre_type FROM membres");
$membres = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informations des membres</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<h2>Informations des membres</h2>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Entreprise</th>
        <th>Nom et Prénom</th>
        <th>Adresse</th>
        <th>Complément d'adresse</th>
        <th>Code Postal</th>
        <th>Ville</th>
        <th>Téléphone</th>
        <th>Mail</th>
        <th>Type</th>
        <th>Actions</th> <!-- Nouvelle colonne pour les boutons -->
    </tr>
    </thead>
    <tbody>
    <?php foreach ($membres as $membre): ?>
        <tr>
            <td><?= $membre['client_id'] ?></td>
            <td><?= $membre['membre_entreprise'] ?></td>
            <td><a href="../profile/profile_client.php?id=<?= $membre['client_id'] ?>"><?= $membre['membre_nom'] . ' ' . $membre['membre_prenom'] ?></a></td>
            <td><?= $membre['membre_adresse'] ?></td>
            <td><?= $membre['membre_adresse_comp'] ?></td>
            <td><?= $membre['membre_cp'] ?></td>
            <td><?= $membre['membre_ville'] ?></td>
            <td><?= $membre['membre_tel'] ?></td>
            <td><?= $membre['membre_mail'] ?></td>
            <td><?= $membre['membre_type'] ?></td>
            <td><?php $client_id = $membre['client_id']; echo "<a href='sav-formulaire.php?client_id=$client_id'>Créer SAV</a>"; ?></td>
        </tr>
    <?php endforeach; ?>

    </tbody>
</table>
</body>
</html>