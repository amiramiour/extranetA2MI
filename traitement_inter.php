<?php
session_start();
include 'connexionBD.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $db = connexionbdd();

        // Récupérer l'ID du bon d'intervention depuis le formulaire
        $bi_id = $_POST['bi_id'];

        // Récupérer l'intervention sélectionnée depuis le formulaire
        $intervention_id = $_POST['selectedIntervention'];

        // Récupérer les autres données du formulaire
        $nb_pieces = $_POST['nb_pieces'];
        $prix_unitaire = $_POST['prixUn'];
        $prix_total = $nb_pieces * $prix_unitaire;

        // Préparer la requête d'insertion dans la table intervention
        $query = $db->prepare("INSERT INTO intervention (bi_id, inter_intervention, inter_nbpiece, inter_prixunit, inter_total)
            VALUES (:bi_id, :intervention_id, :nb_pieces, :prix_unitaire, :prix_total)");

        // Liaison des paramètres
        $query->bindParam(':bi_id', $bi_id);
        $query->bindParam(':intervention_id', $intervention_id);
        $query->bindParam(':nb_pieces', $nb_pieces);
        $query->bindParam(':prix_unitaire', $prix_unitaire);
        $query->bindParam(':prix_total', $prix_total);

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
} else {
    // Redirection vers une page d'erreur si la requête n'est pas de type POST
    header("Location: erreur.php");
    exit;
}
?>
