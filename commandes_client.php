<?php
/*
CREATE TABLE IF NOT EXISTS `commande` (
  `cmd_id` int(11) NOT NULL AUTO_INCREMENT,
  `cmd_reference` varchar(30) NOT NULL,
  `cmd_designation` varchar(200) NOT NULL,
  `cmd_prixachat` decimal(9,2) NOT NULL,
  `cmd_prixvente` decimal(9,2) NOT NULL,
  `cmd_datein` bigint(20) NOT NULL,
  `cmd_dateout` bigint(20) NOT NULL,
  `membre_id` int(11) NOT NULL,
  `cmd_prixventettc` decimal(9,2) NOT NULL,
  `cmd_livreur` int(11) NOT NULL,
  `cmd_dateSouhait` bigint(20) NOT NULL,
  `cmd_etat` int(11) DEFAULT NULL,
  `cmd_fournisseur` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`cmd_id`),
  KEY `membre_id` (`membre_id`),
  KEY `cmd_etat` (`cmd_etat`),
  KEY `cmd_fournisseur` (`cmd_fournisseur`),
  KEY `cmd_livreur` (`cmd_livreur`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
*/



// Connexion à la base de données
include 'ConnexionBD.php'; // Fichier de configuration de la connexion PDO

$id_client = $_GET['id'];

$query = $pdo->prepare("SELECT c.membre_id, m.membre_nom AS nom_client , 
                      m.membre_prenom, c.cmd_id, c.cmd_reference, c.cmd_designation, 
                      c.cmd_prixachat, c.cmd_prixvente, c.cmd_datein, c.cmd_dateout, c.cmd_prixventettc, 
                      l.membre_nom AS nom_livreur, 
                      c.cmd_dateSouhait, e.commande_etat, c.cmd_fournisseur, f.nomFournisseur, m.membre_nom 
                      FROM commande c JOIN membres m ON m.membre_id = c.membre_id 
                      JOIN membres l ON c.cmd_livreur = l.membre_id 
                      JOIN fournisseur f ON c.cmd_fournisseur = f.idFournisseur 
                      JOIN commande_etats e ON c.cmd_etat = e.id_etat_cmd 
                      WHERE c.membre_id = :id_client
                      ORDER BY c.cmd_datein ASC");
$query->bindParam(':id_client', $id_client, PDO::PARAM_INT);
$query->execute();
$commandes_client = $query->fetchAll();

//verifier la variable clients
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
        <h2>Liste des commandes</h2>
        <a href="ajouter_commande.php" class="btn btn-primary mb-3">Ajouter une commande</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Référence</th>
                    <th>Désignation</th>
                    <th>Prix d'achat</th>
                    <th>Prix de vente</th>
                    <th>Date d'entrée</th>
                    <th>Date de sortie</th>
                    <th>Prix de vente TTC</th>
                    <th>Livreur</th>
                    <th>Date de livraison souhaitée</th>
                    <th>Etat</th>
                    <th>Fournisseur</th>
                    <th>Client</th>
                    <th>Action</th> 
                </tr>
            </thead>
            <tbody>
            <?php foreach ($commandes_client as $commande): ?>
                <tr>
                    <td><?= $commande['cmd_reference'] ?></td>
                    <td><?= $commande['cmd_designation'] ?></td>
                    <td><?= $commande['cmd_prixachat'] ?></td>
                    <td><?= $commande['cmd_prixvente'] ?></td>
                    <td><?= date('d/m/Y H:i:s', $commande['cmd_datein']) ?></td>
                    <td><?= date('d/m/Y H:i:s', $commande['cmd_dateout']) ?></td>
                    <td><?= $commande['cmd_prixventettc'] ?></td>
                    <td><?= $commande['nom_livreur'] ?></td>
                    <td><?= date('d/m/Y H:i:s', $commande['cmd_dateSouhait']) ?></td>
                    <td><?= $commande['commande_etat'] ?></td>
                    <td><?= $commande['nomFournisseur'] ?></td>
                    <td><?= $commande['nom_client'] ?></td>
                    <td>
                        <?php if ($commande['commande_etat'] == 'En attente'): ?>
                            <a href="modifier_commande.php?id=<?= $commande['cmd_id'] ?>" class="btn btn-primary">Modifier</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>

            </tbody>
    </div>