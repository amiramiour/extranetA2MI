<?php
// Inclure le fichier de connexion à la base de données
require_once '../ConnexionBD.php';

try {
    // Connexion à la base de données en utilisant la fonction connexionbdd()
    $db = connexionbdd();

    // Préparer la requête SQL pour récupérer les données de sauvgarde_etat_info avec une jointure sur la table membres
    $query = "
    SELECT 
        sei.*,
        membres_cree.membre_nom AS cree_par,
        membres_modifie.membre_nom AS modifie_par
    FROM 
        sauvgarde_etat_info sei
    LEFT JOIN 
        membres membres_cree ON sei.created_by = membres_cree.membre_id
    LEFT JOIN 
        membres membres_modifie ON sei.updated_by = membres_modifie.membre_id
    ";

    // Préparation de la requête SQL
    $stmt = $db->prepare($query);

    // Exécution de la requête
    $stmt->execute();

    // Récupération des résultats
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Gérer les erreurs
    $error = "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Données de sauvgarde_etat_info</title>
    <!-- Inclure Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/css/bootstrap.min.css">
</head>

<body>
<div class="container mt-4">
    <h2>Données de sauvgarde_etat_info</h2>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">ID SAV</th>
            <th scope="col">Sauvgarde Etat</th>
            <th scope="col">Sauvgarde Avancement</th>
            <th scope="col">Date de création</th>
            <th scope="col">Date de modification</th>
            <th scope="col">Créé par</th>
            <th scope="col">Modifié par</th>
        </tr>
        </thead>
        <tbody>
        <?php if (isset($results) && !empty($results)) : ?>
            <?php foreach ($results as $row) : ?>
                <tr>
                    <td><?= $row['sav_id'] ?></td>
                    <td><?= $row['sauvgarde_etat'] ?></td>
                    <td><?= $row['sauvgarde_avancement'] ?></td>
                    <td><?= $row['date_creation'] ?></td>
                    <td><?= $row['date_update'] ?></td>
                    <td><?= $row['cree_par'] ?></td>
                    <td><?= $row['modifie_par'] ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="7">Aucune donnée trouvée.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
    <?php if (isset($error)) : ?>
        <div class="alert alert-danger mt-3" role="alert">
            <?= $error ?>
        </div>
    <?php endif; ?>
</div>

<!-- Inclure Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/js/bootstrap.min.js"></script>
</body>

</html>
