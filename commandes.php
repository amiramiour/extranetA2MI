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

//récupérer les clients qui ont des commandes avec l'etat de la commande sachant que membre_id pointe sur la table membres
$query = $pdo->query("SELECT cmd_reference FROM commande");
$query->execute();
$clients = $query->fetchAll();

//verifier la variable clients
var_dump($clients);
?>