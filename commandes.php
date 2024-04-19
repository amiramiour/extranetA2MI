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

$query = $pdo->query("SELECT c.membre_id, m.membre_nom AS nom_client , 
                      m.membre_prenom, c.cmd_id, c.cmd_reference, c.cmd_designation, 
                      c.cmd_prixachat, c.cmd_prixvente, c.cmd_datein, c.cmd_dateout, c.cmd_prixventettc, 
                      l.membre_nom AS nom_livreur, 
                      c.cmd_dateSouhait, e.commande_etat, c.cmd_fournisseur, f.nomFournisseur, m.membre_nom 
                      FROM commande c JOIN membres m ON m.membre_id = c.membre_id 
                      JOIN membres l ON c.cmd_livreur = l.membre_id 
                      JOIN fournisseur f ON c.cmd_fournisseur = f.idFournisseur 
                      JOIN commande_etats e ON c.cmd_etat = e.id_etat_cmd 
                      ORDER BY c.cmd_datein ASC");
$query->execute();
$clients = $query->fetchAll();

//verifier la variable clients
//var_dump($clients);
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
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?= $client['cmd_reference'] ?></td>
                        <td><?= $client['cmd_designation'] ?></td>
                        <td><?= $client['cmd_prixachat'] ?></td>
                        <td><?= $client['cmd_prixvente'] ?></td>
                        <td><?= date('d/m/Y H:i:s', $client['cmd_datein']) ?></td>
                        <td><?= date('d/m/Y H:i:s', $client['cmd_dateout']) ?></td>
                        <td><?= $client['cmd_prixventettc'] ?></td>
                        <td><?= $client['nom_livreur'] ?></td>
                        <td><?= date('d/m/Y H:i:s', $client['cmd_dateSouhait']) ?></td>
                        <td><?= $client['commande_etat'] ?></td>
                        <td><?= $client['nomFournisseur'] ?></td>
                        <td><?= $client['nom_client'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
    </div>