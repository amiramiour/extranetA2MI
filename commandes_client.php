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

//requete pour recupérer le nom prénom du client
$query = $pdo->prepare("SELECT membre_nom, membre_prenom FROM membres WHERE membre_id = :id_client");
$query->bindParam(':id_client', $id_client, PDO::PARAM_INT);
$query->execute();
$client = $query->fetch();
//var_dump($client);

$query = $pdo->prepare("SELECT c.membre_id, m.membre_nom AS nom_client , 
                      m.membre_prenom, c.cmd_id, c.cmd_reference, c.cmd_designation, 
                      c.cmd_datein, c.cmd_dateout, c.cmd_prixventettc, 
                      l.membre_nom AS nom_livreur, 
                      c.cmd_dateSouhait, e.commande_etat, c.cmd_etat
                      FROM commande c JOIN membres m ON m.membre_id = c.membre_id 
                      JOIN membres l ON c.cmd_livreur = l.membre_id 
                      JOIN commande_etats e ON c.cmd_etat = e.id_etat_cmd 
                      WHERE c.membre_id = :id_client
                      ORDER BY c.cmd_datein ASC");

$query->bindParam(':id_client', $id_client, PDO::PARAM_INT);
$query->execute();
$commandes_client = $query->fetchAll();
//var_dump($commandes_client);

//requete pour récupréer les états des commandes
$req = $pdo->prepare("SELECT commande_etat FROM commande_etats");
$req->execute();
$commande_etat = $req->fetchAll();



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
        <h2>Liste des commandes de <?= $client['membre_nom'] . ' ' . $client['membre_prenom'] ?></h2>
        <a href="ajouter_commande.php?id=<?= $id_client ?>" class="btn btn-primary">Ajouter</a>
        <br><br>
        <div class="row">
            <?php foreach ($commandes_client as $commande): ?>
                <?php
                // Déterminez la classe de couleur de la carte en fonction de l'état de la commande
                $etat_classe = '';
                switch ($commande['cmd_etat']) {
                    case '1':
                        $etat_classe = 'bg-info';
                        break;
                    case '2':
                        $etat_classe = 'bg-warning';
                        break;
                    case '3':
                        $etat_classe = 'bg-light';
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
                        <div class="card-header"><?= $commande['cmd_reference'] ?></div>
                        <div class="card-body">
                            <h5 class="card-title"><?= $commande['cmd_designation'] ?></h5>
                            <p class="card-text">Date de création: <?= date('d/m/Y H:i:s', $commande['cmd_datein']) ?></p>
                            <p class="card-text">Date de livraison: <?= date('d/m/Y H:i:s', $commande['cmd_dateout']) ?></p>
                            <p class="card-text">Prix de vente TTC: <?= $commande['cmd_prixventettc'] ?></p>
                            <p class="card-text">Date de livraison souhaitée: <?= date('d/m/Y H:i:s', $commande['cmd_dateSouhait']) ?></p>
                            <p class="card-text">Etat: <?= $commande['commande_etat'] ?></p>
                            <p class="card-text">Technicien: <?= $commande['nom_livreur'] ?></p>
                            <a href="modifier_commande.php?id=<?= $commande['cmd_id'] ?>" class="btn btn-primary">Modifier</a>
                            
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>