<?php
include('../ConnexionBD.php');
include('../navbar.php');

// Vérification de l'ID du client dans l'URL
if(isset($_GET['id'])) {

    $client_id = $_GET['id'];

    try {
        // Connexion à la base de données
        $db = connexionbdd();

        // Requête SQL pour récupérer le nom et le prénom du client en fonction de son ID
        $query = "SELECT membre_nom, membre_prenom FROM membres WHERE membre_id = :id";

        // Préparation de la requête SQL
        $stmt = $db->prepare($query);

        // Liaison des valeurs des paramètres de requête
        $stmt->bindParam(':id', $client_id, PDO::PARAM_INT);

        // Exécution de la requête
        $stmt->execute();

        // Récupération du résultat
        $client = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérification si le client existe
        if($client) {
            // Affichage du nom et du prénom du client
            echo "Nom du client : " . $client['membre_nom'] . "<br>";
            echo "Prénom du client : " . $client['membre_prenom'] . "<br>";
            // Bouton pour créer un SAV pour ce client
            echo "<a href='../SAV/sav-formulaire.php?client_id=$client_id'>Créer SAV</a>";
            echo "<br>";
            echo "<a href='../bi/bi_form.php?membre_id=$client_id'>Créer BI</a>";
            echo "<br>";
            echo "<a href='../commandes/ajouter_commande.php?id=$client_id'>Créer Commande</a>";

            echo "<br>"; // Ligne vide

        } else {
            echo "Aucun client trouvé avec cet ID.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo "ID du client non spécifié.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire Bon d'Intervention Client</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<a href="../connexion/deconnexion.php">Se déconnecter</a>