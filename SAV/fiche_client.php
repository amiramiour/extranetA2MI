<?php
include('../connexionBD.php');
/*test fiche client*/
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
