<?php
include "../gestion_session.php";
include '../ConnexionBD.php'; // Fichier de configuration de la connexion PDO
include('../navbar.php');

$pdo = connexionbdd();

$type = isset($_GET['type']) ? $_GET['type'] : 'commandes';

// Fonction pour récupérer les clients en fonction du type sélectionné
function getClients($pdo, $type) {
    if ($type === 'commandes') {
        $query = $pdo->prepare("SELECT c.membre_id, m.membre_nom AS nom_client , 
                                m.membre_prenom, c.cmd_devis_id, c.cmd_devis_reference, c.cmd_devis_designation, 
                                c.cmd_devis_datein, c.cmd_devis_dateout, c.cmd_devis_prixventettc,
                                c.cmd_devis_prixHT,l.membre_nom AS nom_technicien, 
                                c.cmd_devis_dateSouhait, e.cmd_devis_etat
                                FROM commande_devis c JOIN membres m ON m.membre_id = c.membre_id 
                                JOIN membres l ON c.cmd_devis_technicien = l.membre_id 
                                JOIN cmd_devis_etats e ON c.cmd_devis_etat = e.id_etat_cmd_devis
                                WHERE c.type_cmd_devis = '1'
                                ORDER BY c.cmd_devis_datein DESC");
    } elseif ($type === 'devis') {
        $query = $pdo->prepare("SELECT c.membre_id, m.membre_nom AS nom_client , 
                                m.membre_prenom, c.cmd_devis_id, c.cmd_devis_reference, c.cmd_devis_designation, 
                                c.cmd_devis_datein, c.cmd_devis_dateout, c.cmd_devis_prixventettc,
                                c.cmd_devis_prixHT, l.membre_nom AS nom_technicien, 
                                c.cmd_devis_dateSouhait, e.cmd_devis_etat
                                FROM commande_devis c JOIN membres m ON m.membre_id = c.membre_id 
                                JOIN membres l ON c.cmd_devis_technicien = l.membre_id 
                                JOIN cmd_devis_etats e ON c.cmd_devis_etat = e.id_etat_cmd_devis
                                WHERE c.type_cmd_devis = '2'
                                ORDER BY c.cmd_devis_datein DESC");
    }

    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

$clients = getClients($pdo, $type);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Commandes / Devis</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2><?= $type === 'commandes' ? 'Commandes' : 'Devis' ?></h2>
        <div class="mb-3">
            
            <select id="type" class="form-control" onchange="changeType(this.value)">
                <option value="commandes" <?= $type === 'commandes' ? 'selected' : '' ?>>Commandes</option>
                <option value="devis" <?= $type === 'devis' ? 'selected' : '' ?>>Devis</option>
            </select>
        </div>
        <a href="ajouter_commandes_devis.php" class="btn btn-primary mb-3">Ajouter</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Reference</th>
                    <th>Client</th>
                    <th>Prix HT</th>
                    <th>Prix TTC</th>
                    <th>Date création</th>
                    <th>Date livraison</th>
                    <th>Date souhaitée</th>
                    <th>Etat</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?= $client['cmd_devis_id'] ?></td>
                        <td><?= $client['cmd_devis_reference'] ?></td>
                        <td><a href="../profile/profile_client?id=<?= $client['membre_id'] ?>"><?= $client['nom_client'] . ' ' . $client['membre_prenom'] ?></a></td>
                        <td><?= $client['cmd_devis_prixHT'] ?></td>
                        <td><?= $client['cmd_devis_prixventettc'] ?></td>
                        <td><?= date('d/m/Y', $client['cmd_devis_datein']) ?></td>
                        <td><?= date('d/m/Y', $client['cmd_devis_dateout']) ?></td>
                        <td><?= date('d/m/Y', $client['cmd_devis_dateSouhait']) ?></td>
                        <td><?= $client['cmd_devis_etat'] ?></td>
                        <td>
                            <a href="ajouter_commandes_devis.php?id=<?= $client['membre_id']?>" class="btn btn-primary">Ajouter</a>
                            <br><br>
                            <a href="modifier_commandes_devis.php?id=<?= $client['cmd_devis_id']?>" class="btn btn-warning">Modifier</a>
                            <br><br>
                            <a href="#" class="btn btn-danger">Supprimer</a>
                            <br><br>
                            <a href="#" class="btn btn-info">PDF</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        function changeType(type) {
            window.location.href = 'commandes_devis.php?type=' + type;
        }
    </script>
</body>
</html>
