<?php
session_start();
include 'connexionBD.php';

try {
    $db = connexionbdd();

    $query = $db->query("SELECT bi.*, membres.membre_nom, membres.membre_prenom FROM bi INNER JOIN membres ON bi.membre_id = membres.membre_id");
    $resultats = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur lors de la récupération des données : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Bons d'Intervention</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<!-- Inclure le navbar -->
<?php include('navbar.php'); ?>

<div class="container mt-3">
    <h1>Liste des Bons d'Intervention</h1>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Membre</th>
            <th scope="col">Active</th>
            <th scope="col">Technicien</th>
            <th scope="col">Facture</th>
            <th scope="col">Garantie</th>
            <th scope="col">Contrat</th>
            <th scope="col">Service</th>
            <th scope="col">Envoi</th>
            <th scope="col">Facturation</th>
            <th scope="col">Date de Facturation</th>
            <th scope="col">Paiement</th>
            <th scope="col">Date de Création</th>
            <th scope="col">Heure d'Arrivée</th>
            <th scope="col">Heure de Départ</th>
            <th scope="col">Commentaire</th>
            <th scope="col">Réglé</th>

        </tr>
        </thead>
        <tbody>
        <?php foreach ($resultats as $bi): ?>
            <tr>
                <td><?= $bi['bi_id'] ?></td>
                <td><?= $bi['membre_nom'] . ' ' . $bi['membre_prenom'] ?></td>
                <td><?= $bi['bi_active'] ?></td>
                <td><?= $bi['bi_technicien'] ?></td>
                <td><?= $bi['bi_facture'] ?></td>
                <td><?= $bi['bi_garantie'] ?></td>
                <td><?= $bi['bi_contrat'] ?></td>
                <td><?= $bi['bi_service'] ?></td>
                <td><?= $bi['bi_envoi'] ?></td>
                <td><?= $bi['bi_facturation'] ?></td>
                <td><?= $bi['bi_datefacturation'] ?></td>
                <td><?= $bi['bi_paiement'] ?></td>
                <td><?= date('d/m/Y H:i:s', $bi['bi_datein']) ?></td>
                <td><?= $bi['bi_heurearrive'] ?></td>
                <td><?= $bi['bi_heuredepart'] ?></td>
                <td><?= $bi['bi_commentaire'] ?></td>
                <td><?= $bi['bi_regle'] ?></td>

            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
