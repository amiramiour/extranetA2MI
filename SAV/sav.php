<?php
include('../connexionBD.php');

// Vérification de la valeur du paramètre de tri
$tri = isset($_GET['tri']) ? $_GET['tri'] : 'membre_nom';
$colonneTri = ($tri === 'membre_entreprise') ? 'client_entreprise' : 'client_nom';

$etat = isset($_GET['etat']) ? $_GET['etat'] : '';

try {
    // Connexion à la base de données
    $db = connexionbdd();

    // Requête SQL de base pour récupérer les données de la table SAV avec les jointures et le tri
    $query = "
SELECT 
    sav.sav_id, 
    membres.membre_id,
    membres.membre_nom AS client_nom,
    membres.membre_prenom AS client_prenom,
    membres.membre_entreprise AS client_entreprise,
    sav.sav_accessoire, 
    sav.sav_avancement, 
    sav.sav_datein,
    sav.sav_dateout,
    sav.sav_envoi, 
    sav.sav_etat, 
    sav.sav_forfait, 
    sav.sav_garantie, 
    sav.sav_maindoeuvreht, 
    sav.sav_maindoeuvrettc, 
    sav.sav_mdpclient, 
    sav.sav_probleme, 
    sav.sav_regle, 
    sav.sav_tarifmaterielht, 
    sav.sav_tarifmaterielttc, 
    sav.sav_technicien,
    sav.sav_typemateriel,
    sav_etats.etat_intitule
FROM 
    sav
LEFT JOIN 
    sav_etats ON sav.sav_etats = sav_etats.id_etat_sav
LEFT JOIN
    membres ON sav.membre_id = membres.membre_id";

    // Clause WHERE conditionnelle pour filtrer par état si un état est sélectionné
    if (!empty($etat)) {
        $query .= " WHERE sav_etats.etat_intitule = :etat";
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

<title>Résultats de la table SAV</title>
<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th,
    td {
        border: 1px solid black;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }
</style>

<body>
<h2>Résultats de la table SAV</h2>
<div>
<form action="" method="GET">
    <select name="tri">
        <option value="membre_nom" <?php echo ($tri == 'membre_nom') ? 'selected' : ''; ?>>Trier par nom de client</option>
        <option value="membre_entreprise" <?php echo ($tri == 'membre_entreprise') ? 'selected' : ''; ?>>Trier par entreprise</option>
    </select>
    <button type="submit">Trier</button>
    <select name="etat" id="etat_select">
        <option value="">Tous les états</option>
        <option value="Réceptionné" <?php echo ($etat == 'Réceptionné') ? 'selected' : ''; ?>>Réceptionné</option>
        <option value="en cours" <?php echo ($etat == 'En cours') ? 'selected' : ''; ?>>En cours</option>
        <option value="En attente" <?php echo ($etat == 'En attente') ? 'selected' : ''; ?>>En attente</option>
        <option value="Terminé" <?php echo ($etat == 'Terminé') ? 'selected' : ''; ?>>Terminé</option>
        <option value="Rendu au client" <?php echo ($etat == 'Rendu au client') ? 'selected' : ''; ?>>Rendu au client</option>
    </select>
    <button type="submit">Filtrer par état</button>
</form>

</div>
<table>
    <tr>
        <th>Sav ID</th>
        <th>Nom du membre</th>
        <th>entreprise</th>
        <th>Accessoire</th>
        <th>Avancement</th>
        <th>Date de réception</th>
        <th>Date de livraison</th>
        <th>Envoi</th>
        <th>Etat</th>
        <th>Forfait</th>
        <th>Garantie</th>
        <th>Main d'œuvre HT</th>
        <th>Main d'œuvre TTC</th>
        <th>MDP client</th>
        <th>Problème</th>
        <th>Règle</th>
        <th>Tarif matériel HT</th>
        <th>Tarif matériel TTC</th>
        <th>Technicien</th>
        <th>Type matériel</th>
        <th>Etat intitule</th>
    </tr>
    <?php foreach ($results as $row) : ?>
        <tr>
            <td><?= $row['sav_id'] ?></td>
            <td><a href="fiche_client.php?id=<?= $row['membre_id'] ?>"><?= $row['client_nom'] . ' ' . $row['client_prenom'] ?></a></td>
            <td><?= $row['client_entreprise'] ?></td>
            <td><?= $row['sav_accessoire'] ?></td>
            <td><?= $row['sav_avancement'] ?></td>
            <td><?= date('d/m/Y H:i:s', strtotime($row['sav_datein'])) ?></td>
            <td><?= date('d/m/Y H:i:s', strtotime($row['sav_dateout'])) ?></td>
            <td><?= $row['sav_envoi'] ?></td>
            <td><?= $row['sav_etat'] ?></td>
            <td><?= $row['sav_forfait'] ?></td>
            <td><?= $row['sav_garantie'] ?></td>
            <td><?= $row['sav_maindoeuvreht'] ?></td>
            <td><?= $row['sav_maindoeuvrettc'] ?></td>
            <td><?= $row['sav_mdpclient'] ?></td>
            <td><?= $row['sav_probleme'] ?></td>
            <td><?= $row['sav_regle'] ?></td>
            <td><?= $row['sav_tarifmaterielht'] ?></td>
            <td><?= $row['sav_tarifmaterielttc'] ?></td>
            <td><?= $row['sav_technicien'] ?></td>
            <td><?= $row['sav_typemateriel'] ?></td>
            <td><?= $row['etat_intitule'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
