<?php
// Inclusion du fichier de connexion à la base de données
include('../ConnexionBD.php');
session_start();

// Vérifier si l'utilisateur est déjà connecté
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_mail']) || $_SESSION['user_type'] === 'client') {
    // Rediriger vers une page d'erreur ou de connexion si l'utilisateur n'est pas connecté ou s'il est un client
    header('Location: ../connexion.php');
    exit();
}

// Connexion à la base de données
$db = connexionbdd();

// Requête SQL pour récupérer les données des prêts avec les noms et prénoms des membres
$query = "
    SELECT p.pret_id, p.pret_materiel, p.pret_caution, p.pret_datein, p.pret_dateout, p.membre_id, m.membre_nom, m.membre_prenom, pe.etat_intitule, p.commentaire
    FROM pret p
    INNER JOIN membres m ON p.membre_id = m.membre_id
    INNER JOIN pret_etat pe ON p.pret_etat = pe.id_etat_pret
    WHERE p.pret_active = 1
    ORDER BY p.pret_dateout DESC";

// Préparation et exécution de la requête
$stmt = $db->query($query);
$prets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des prêts</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <!-- CSS personnalisé -->

</head>

<body>
<?php include('../navbar.php'); ?>

<div class="container mt-4">
    <h1 class="text-center">Liste des prêts</h1>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Emprunteur</th>
                <th scope="col">État</th>
                <th scope="col">Date de debut</th>
                <th scope="col">Date de retour</th>
                <th scope="col">Caution</th>
                <th scope="col">Commentaire</th>
                <th scope="col">Matériel</th>

            </tr>
            </thead>
            <tbody>
            <?php foreach ($prets as $pret) : ?>
                <tr>
                    <td><?= $pret['pret_id'] ?></td>
                    <td><a href="../profile/profile_client.php?id=<?= $pret['membre_id'] ?>"><?= $pret['membre_nom'] . ' ' . $pret['membre_prenom'] ?></a></td>
                    <td><?= $pret['etat_intitule'] ?></td>
                    <td><?= date('Y/m/d', $pret['pret_datein']) ?></td>
                    <td><?= date('Y/m/d', $pret['pret_dateout']) ?></td>
                    <td><?= $pret['pret_caution'] ?></td>
                    <td><?= $pret['commentaire'] ?></td>
                    <td><?= $pret['pret_materiel'] ?></td>

                    <td>
                        <a href="../pret/modifier_pret.php?id=<?= $pret['pret_id'] ?>" class="btn btn-primary">Modifier</a>
                    </td>
                    <td>
                        <a href="#" class="btn btn-danger" onclick="confirmSuppression(<?= $pret['pret_id'] ?>)">Supprimer</a>
                    </td>

                </tr>
            <?php endforeach; ?>

            </tbody>
        </table>
    </div>
</div>

<!-- Inclusion du JavaScript Bootstrap -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/js/bootstrap.min.js"></script>
<script>
    function confirmSuppression(pretId) {
        // Demander confirmation à l'utilisateur
        if (confirm("Êtes-vous sûr de vouloir supprimer ce prêt ?")) {
            // Rediriger vers le script PHP de suppression avec l'ID du prêt
            window.location.href = "../pret/supprimer_pret.php?id=" + pretId;
        }
    }
</script>


</body>

</html>