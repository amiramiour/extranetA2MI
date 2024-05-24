<?php
// Définition des variables de connexion à la base de données
$bd_nom_serveur  = 'localhost';
$bd_nom_bd       = 'a2mi_db';
$bd_login        = 'root';
$bd_mot_de_passe = '';
$bd_port         = '3307'; // Ajout du numéro de port

// Connexion à la base de données
try {
    $pdo = new PDO('mysql:host='.$bd_nom_serveur.';port='.$bd_port.';dbname='.$bd_nom_bd, $bd_login, $bd_mot_de_passe, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    return $pdo;
} catch(PDOException $e) {
    echo 'La connexion à la base de données a échoué.<br>';
    echo 'Erreur : '.$e->getMessage().'<br>';
    echo 'N° : '.$e->getCode();
    die();
}
?>
