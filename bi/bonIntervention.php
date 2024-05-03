<?php
session_start();

// Vérifier si l'utilisateur est connecté en tant qu'administrateur ou sous-administrateur
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_mail']) || ($_SESSION['user_type'] !== 'admin' && $_SESSION['user_type'] !== 'sousadmin')) {
    // Redirection vers une page d'erreur si l'utilisateur n'est pas connecté en tant qu'admin ou sous-admin
    header('Location: ../index.php');
    exit();
}
include '../ConnexionBD.php';

// Si une action de suppression est effectuée
if (isset($_POST['action']) && $_POST['action'] === 'supprimer' && isset($_POST['bi_id'])) {
    // Récupérer l'ID du bon d'intervention à supprimer
    $bi_id = $_POST['bi_id'];

    try {
        $db = connexionbdd();

        // Mettre à jour l'attribut bi_active dans la base de données
        $update_query = $db->prepare("UPDATE bi SET bi_active = 0 WHERE bi_id = ?");
        $update_query->execute([$bi_id]);

        // Redirection vers bonIntervention.php après la suppression
        header('Location: bonIntervention.php');
        exit;
    } catch (PDOException $e) {
        echo "Erreur lors de la suppression du bon d'intervention : " . $e->getMessage();
    }
}

try {
    $db = connexionbdd();

    $query = $db->prepare("SELECT bi.*, membres.membre_nom, membres.membre_prenom FROM bi INNER JOIN membres ON bi.membre_id = membres.membre_id WHERE bi.bi_active = ?");
    $query->execute([1]);
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
<?php include('../navbar.php'); ?>

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
            <th scope="col">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($resultats as $bi): ?>
            <tr>
                <td><?= $bi['bi_id'] ?></td>
                <td><a href="../profile/profile_client.php?id=<?= $bi['membre_id'] ?>"><?= $bi['membre_nom'] . ' ' . $bi['membre_prenom'] ?></a></td>
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
                <td>
                    <a href="../bi/modification_bi.php?bi_id=<?= $bi['bi_id'] ?>" class="btn btn-primary">Modifier</a>
                </td>
                <td>
                    <form action="" method="post" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce bon d\'intervention ?');">
                        <input type="hidden" name="action" value="supprimer">
                        <input type="hidden" name="bi_id" value="<?= $bi['bi_id'] ?>">
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                </td>
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