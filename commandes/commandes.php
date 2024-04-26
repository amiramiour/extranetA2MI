<?php
include '../connexionBD.php'; // Fichier de configuration de la connexion PDO
$pdo = connexionbdd();

$query = $pdo->query("SELECT DISTINCT m.membre_id, m.membre_nom, m.membre_prenom
                    FROM membres m
                    JOIN commande c ON m.membre_id = c.membre_id;
                    ");
$query->execute();
$clients = $query->fetchAll();

//verifier la variable clients
//var_dump($clients);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Clients avec commandes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Liste des clients ayant des commandes</h2>
        <a href="ajouter_commande.php" class="btn btn-primary mb-3">Ajouter une commande</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nom du client</th>
                    <th>Prénom du client</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><a href="commandes_client.php?id=<?= $client['membre_id'] ?>"><?= $client['membre_nom'] ?> </a></td>
                        <td><a href="commandes_client.php?id=<?= $client['membre_id'] ?>"><?= $client['membre_prenom'] ?> </a></td>
                        <td><a href="ajouter_commande.php?id=<?= $client['membre_id']?>" class="btn btn-primary">Ajouter</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
    </div>
</body>
</html>
