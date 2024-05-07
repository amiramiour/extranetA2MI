<?php

require_once 'ConnexionBD.php';
include "gestion_session.php";


// Code pour la première requête
try {
    $db = connexionbdd();

    // Modification de la requête pour trier par date de création décroissante
    $stmt1 = $db->query("SELECT * FROM sauvgarde_etat_info_commande ORDER BY date_update DESC");
    $sauvegardes1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error1 = "Erreur : " . $e->getMessage();
}

// Code pour la deuxième requête
try {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_mail']) || $_SESSION['user_type'] === 'client') {
        header("Location: ../connexion.php");
        exit;
    }

    $db = connexionbdd();

    // Modification de la requête pour trier par date de création décroissante
    $query = "
    SELECT 
        sei.*,
        membres_cree.membre_nom AS cree_par,
        membres_modifie.membre_nom AS modifie_par,
        sav_etats.etat_intitule AS etat_nom
    FROM 
        sauvgarde_etat_info sei
    LEFT JOIN 
        membres membres_cree ON sei.created_by = membres_cree.membre_id
    LEFT JOIN 
        membres membres_modifie ON sei.updated_by = membres_modifie.membre_id
    LEFT JOIN
        sav_etats ON sei.sauvgarde_etat = sav_etats.id_etat_sav
    ORDER BY
        sei.date_update DESC
    ";

    $stmt2 = $db->prepare($query);
    $stmt2->execute();
    $results2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error2 = "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Données de sauvgarde_etat_info</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
</head>

<body>
<?php include('navbar.php'); ?>
<div class="container mt-4">
    <h2>Commande</h2>
    <div style="overflow-y: scroll; height: 200px;">
        <table class="table">
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
            <?php foreach ($sauvegardes1 as $sauvegarde): ?>
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
    </div>

    <h2>SAV</h2>
    <div style="overflow-y: scroll; height: 200px;">
        <table class="table">
            <thead>
            <tr>
                <th>ID SAV</th>
                <th>Sauvegarde Etat</th>
                <th>Sauvegarde Avancement</th>
                <th>Date de création</th>
                <th>Date de modification</th>
                <th>Créé par</th>
                <th>Modifié par</th>
            </tr>
            </thead>
            <tbody>
            <?php if (isset($results2) && !empty($results2)) : ?>
                <?php foreach ($results2 as $row) : ?>
                    <tr>
                        <td><?= $row['sav_id'] ?></td>
                        <td><?= $row['etat_nom'] ?></td>
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
    </div>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/js/bootstrap.min.js"></script>
</body>

</html>
