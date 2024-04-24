<?php
session_start();
include 'connexionBD.php';

// Vérifier si l'ID du membre est passé dans l'URL
if(isset($_GET['membre_id'])) {
    // Récupérer l'ID du membre depuis l'URL
    $membre_id = $_GET['membre_id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            $db = connexionbdd();

            // Récupérer les données du formulaire
            $bi_id = $_POST['bi_id'];
            $nb_pieces = $_POST['dynamic'][1][0]; // Récupérer le nombre de pièces
            $prix_unitaire = $_POST['dynamic'][1][1]; // Récupérer le prix unitaire
            $prix_total = $nb_pieces * $prix_unitaire; // Calculer le prix total

            // Autres données du formulaire
            // Vous pouvez les récupérer de la même manière que vous avez récupéré pour la table bi

            // Préparer la requête d'insertion dans la table intervention
            $query = $db->prepare("INSERT INTO intervention (bi_id, membre_id, nb_pieces, prix_unitaire, prix_total, autre_colonne_1, autre_colonne_2, ...)
            VALUES (:bi_id, :membre_id, :nb_pieces, :prix_unitaire, :prix_total, :autre_colonne_1, :autre_colonne_2, ...)");

            // Liaison des paramètres
            $query->bindParam(':bi_id', $bi_id);
            $query->bindParam(':membre_id', $membre_id);
            $query->bindParam(':nb_pieces', $nb_pieces);
            $query->bindParam(':prix_unitaire', $prix_unitaire);
            $query->bindParam(':prix_total', $prix_total);
            // Assurez-vous de lier les autres colonnes du même manière

            // Exécution de la requête
            $query->execute();

            // Message de succès
            $_SESSION['success_message'] = "Les données ont été ajoutées avec succès dans la base de données.";

            // Redirection vers bonIntervention.php après traitement
            header("Location: bonIntervention.php");
            exit;
        } catch (PDOException $e) {
            // Message d'erreur en cas d'échec
            $_SESSION['error_message'] = "Erreur lors de l'insertion des données : " . $e->getMessage();
        }
    }
} else {
    // Redirection vers une page d'erreur si l'ID du membre n'est pas présent dans l'URL
    header("Location: erreur.php");
    exit;
}
