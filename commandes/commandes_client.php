<?php
include "../gestion_session.php";
include '../ConnexionBD.php';
include('../navbar.php');


$db = connexionbdd();

$id_client = $_GET['id'];

//requete pour recupérer le nom prénom du client
$query = $db->prepare("SELECT membre_nom, membre_prenom FROM membres WHERE membre_id = :id_client");
$query->bindParam(':id_client', $id_client, PDO::PARAM_INT);
$query->execute();
$client = $query->fetch();
//var_dump($client);

$query = $db->prepare("SELECT c.membre_id, m.membre_nom AS nom_client , 
                      m.membre_prenom, c.cmd_devis_id, c.cmd_devis_reference, c.cmd_devis_designation, 
                      c.cmd_devis_datein, c.cmd_devis_dateout, c.cmd_devis_prixventettc, 
                      l.membre_nom AS nom_technicien, 
                      c.cmd_devis_dateSouhait, e.cmd_devis_etat, c.cmd_devis_etat
                      FROM commande_devis c JOIN membres m ON m.membre_id = c.membre_id 
                      JOIN membres l ON c.cmd_devis_technicien = l.membre_id 
                      JOIN cmd_devis_etats e ON c.cmd_devis_etat = e.id_etat_cmd_devis
                      WHERE c.membre_id = :id_client
                      ORDER BY c.cmd_devis_datein ASC");

$query->bindParam(':id_client', $id_client, PDO::PARAM_INT);
$query->execute();
$commandes_client = $query->fetchAll();
//var_dump($commandes_client);

//requete pour récupréer les états des commandes
$req = $db->prepare("SELECT cmd_devis_etat FROM cmd_devis_etats");
$req->execute();
$commande_etat = $req->fetchAll();
//var_dump($commandes_client);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Commandes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Liste des commandes de <?= $client['membre_nom'] . ' ' . $client['membre_prenom'] ?></h2>
        <a href="commandes_devis.php" class="btn btn-primary mb-3">Retour</a>
        <br>
        <a href="ajouter_commandes_devis.php?id=<?= $id_client ?>" class="btn btn-primary">Ajouter</a>
        <br><br>
        <div class="row">
            <?php foreach ($commandes_client as $commande): ?>
                <?php
                $etat_classe = '';
                switch ($commande['cmd_devis_etat']) {
                    case '1':
                        $etat_classe = 'bg-info';
                        break;
                    case '2':
                        $etat_classe = 'bg-warning';
                        break;
                    case '3':
                        $etat_classe = 'bg-dark';
                        break;
                    case '4':
                        $etat_classe = 'bg-danger';
                        break;
                    case '5':
                        $etat_classe = 'bg-success';
                        break;
                    default:
                        $etat_classe = 'bg-info';
                        break;
                }
                ?>
                <div class="col-md-4 mb-3">
                    <div class="card text-white <?= $etat_classe ?>">
                        <div class="card-header"><?= $commande['cmd_devis_reference'] ?></div>
                        <div class="card-body">
                            <h5 class="card-title"><?= $commande['cmd_devis_designation'] ?></h5>
                            <p class="card-text">Date de création: <?= date('d/m/Y', $commande['cmd_devis_datein']) ?></p>
                            <p class="card-text">Date de livraison: <?= date('d/m/Y', $commande['cmd_devis_dateout']) ?></p>
                            <p class="card-text">Prix de vente TTC: <?= $commande['cmd_devis_prixventettc'] ?></p>
                            <p class="card-text">Date de livraison souhaitée: <?= date('d/m/Y', $commande['cmd_devis_dateSouhait']) ?></p>
                            <p class="card-text">Etat: <?= $commande['cmd_devis_etat'] ?></p>
                            <p class="card-text">Technicien: <?= $commande['nom_technicien'] ?></p>
                            <?php if ($commande['cmd_devis_etat'] == 1 || $commande['cmd_devis_etat'] == 2 || $commande['cmd_devis_etat'] == 3 || $commande['cmd_devis_etat'] == 4): ?>
                                <a href="modifier_commandes_devis.php?id=<?= $commande['cmd_devis_id'] ?>" class="btn btn-primary">Modifier</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>