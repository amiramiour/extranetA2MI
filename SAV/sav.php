<?php
include('../ConnexionBD.php');

session_start();

// Vérifier si l'utilisateur est déjà connecté
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_mail']) || $_SESSION['user_type'] === 'client') {
    // Rediriger vers une page d'erreur ou de connexion si l'utilisateur n'est pas connecté ou s'il est un client
    header('Location: ../connexion.php');
    exit();
}

try {
    // Connexion à la base de données
    $db = connexionbdd();

    // Vérification de la valeur du paramètre de tri
    $tri = isset($_GET['tri']) ? $_GET['tri'] : 'membre_nom';
    $colonneTri = ($tri === 'membre_entreprise') ? 'client_entreprise' : 'client_nom';

    $etat = isset($_GET['etat']) ? $_GET['etat'] : '';

    // Requête SQL de base pour récupérer les données de la table SAV avec les jointures et le tri
    $query = "
        SELECT 
            sav.sav_id, 
            membres.membre_id,
            membres.membre_nom AS client_nom,
            membres.membre_prenom AS client_prenom,
            membres.membre_entreprise AS client_entreprise,
            sav.sav_accessoire, 
            sei.sauvgarde_avancement,
            sav.sav_datein,
            sav.sav_dateout,
            sav.sav_envoi, 
            sav.sav_forfait, 
            sav.sav_garantie, 
            sav.sav_maindoeuvreht, 
            sav.sav_maindoeuvrettc, 
            sav.sav_mdpclient, 
            sav.sav_probleme, 
            sav.sav_regle, 
            sav.sav_tarifmaterielht, 
            sav.sav_tarifmaterielttc, 
            sav.sav_typemateriel,
            sav_etats.etat_intitule,
            membres_technicien.membre_nom AS technicien_nom
        FROM 
            sav
        LEFT JOIN 
            sav_etats ON sav.sav_etats = sav_etats.id_etat_sav
        LEFT JOIN
            membres ON sav.membre_id = membres.membre_id
        LEFT JOIN
            sauvgarde_etat_info sei ON sav.sav_avancement = sei.id_sauvgarde_etat
        LEFT JOIN
            membres AS membres_technicien ON sav.sav_technicien = membres_technicien.membre_id
        WHERE
            sav.active = 1";

    // Clause WHERE conditionnelle pour filtrer par état si un état est sélectionné
    if (!empty($etat)) {
        $query .= " AND sav_etats.etat_intitule = :etat";
    }

    // Ajout de la clause ORDER BY pour trier les résultats
    $query .= " ORDER BY CASE WHEN membres.membre_entreprise = '' THEN 1 ELSE 0 END, $colonneTri";

    // Préparation de la requête SQL
    $stmt = $db->prepare($query);

    // Liaison des valeurs des paramètres de requête
    if (!empty($etat)) {
        $stmt->bindParam(':etat', $etat, PDO::PARAM_STR);
    }

    // Exécution de la requête
    $stmt->execute();

    // Récupération des résultats
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de la table SAV</title>
    <!-- Inclure le fichier CSS de Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Liens vers les icônes LSF (Line Awesome) -->
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <!-- Inclure le fichier CSS personnalisé -->
    <link href="styles.css" rel="stylesheet">
</head>
<body>
<?php include('../navbar.php'); ?>
<div class="container">
    <h2>Résultats de la table SAV</h2>
    <div class="row mb-3">
        <div class="col-sm-6">
            <form action="" method="GET">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="tri">Trier par :</label>
                    </div>
                    <select class="custom-select" id="tri" name="tri">
                        <option value="membre_nom" <?= ($tri == 'membre_nom') ? 'selected' : ''; ?>>Nom de client</option>
                        <option value="membre_entreprise" <?= ($tri == 'membre_entreprise') ? 'selected' : ''; ?>>Entreprise</option>
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">Trier</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-sm-6">
            <form action="" method="GET">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="etat">Filtrer par état :</label>
                    </div>
                    <select class="custom-select" id="etat" name="etat">
                        <option value="">Tous les états</option>
                        <option value="Réceptionné" <?= ($etat == 'Réceptionné') ? 'selected' : ''; ?>>Réceptionné</option>
                        <option value="En cours" <?= ($etat == 'En cours') ? 'selected' : ''; ?>>En cours</option>
                        <option value="En attente" <?= ($etat == 'En attente') ? 'selected' : ''; ?>>En attente</option>
                        <option value="Terminé" <?= ($etat == 'Terminé') ? 'selected' : ''; ?>>Terminé</option>
                        <option value="Rendu au client" <?= ($etat == 'Rendu au client') ? 'selected' : ''; ?>>Rendu au client</option>
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">Filtrer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <table class="table">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Nom du membre</th>
            <th scope="col">Entreprise</th>
            <th scope="col">Accessoire</th>
            <th scope="col">Avancement</th>
            <th scope="col">Date de réception</th>
            <th scope="col">Date de livraison</th>
            <th scope="col">Envoi</th>
            <th scope="col">Forfait</th>
            <th scope="col">Garantie</th>
            <th scope="col">Main d'œuvre HT</th>
            <th scope="col">Main d'œuvre TTC</th>
            <th scope="col">MDP client</th>
            <th scope="col">Problème</th>
            <th scope="col">Règle</th>
            <th scope="col">Tarif matériel HT</th>
            <th scope="col">Tarif matériel TTC</th>
            <th scope="col">Technicien</th>
            <th scope="col">Type matériel</th>
            <th scope="col">État</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($results as $row) :
            // Vérifier si la date d'entrée est un timestamp Unix
            if (is_numeric($row['sav_datein'])) {
                // Convertir le timestamp Unix en format de date
                $datein = date('Y-m-d ', $row['sav_datein']);
            } else {
                // Si la date est déjà au bon format, l'utiliser directement
                $datein = $row['sav_datein'];
            }

            // Faites de même pour la date de livraison
            if (is_numeric($row['sav_dateout'])) {
                $dateout = date('Y-m-d ', $row['sav_dateout']);
            } else {
                $dateout = $row['sav_dateout'];
            }
            ?>
            <tr>
                <td><a href="../profile/profile_client.php?id=<?= $row['membre_id'] ?>"><?= $row['client_nom'] . ' ' . $row['client_prenom'] ?></a></td>
                <td><?= $row['client_entreprise'] ?></td>
                <td><?= $row['sav_accessoire'] ?></td>
                <td><?= $row['sauvgarde_avancement'] ?></td>
                <td><?= $datein ?></td> <!-- Afficher la date d'entrée -->
                <td><?= $dateout ?></td> <!-- Afficher la date de livraison -->
                <td><?= $row['sav_envoi'] ?></td>
                <td><?= $row['sav_forfait'] ?></td>
                <td><?= $row['sav_garantie'] ?></td>
                <td><?= $row['sav_maindoeuvreht'] ?></td>
                <td><?= $row['sav_maindoeuvrettc'] ?></td>
                <td><?= $row['sav_mdpclient'] ?></td>
                <td><?= $row['sav_probleme'] ?></td>
                <td><?= $row['sav_regle'] ?></td>
                <td><?= $row['sav_tarifmaterielht'] ?></td>
                <td><?= $row['sav_tarifmaterielttc'] ?></td>
                <td><?= $row['technicien_nom'] ?></td>
                <td><?= $row['sav_typemateriel'] ?></td>
                <td><?= $row['etat_intitule'] ?></td>
                <td><a href="modifier-sav.php?sav_id=<?= $row['sav_id'] ?>" class="btn btn-primary"><i class="la la-pencil"></i> Modifier</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
