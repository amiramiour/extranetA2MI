<?php
// Inclure le fichier de connexion à la base de données
include('../ConnexionBD.php');
session_start();
// Vérifier si l'utilisateur est connecté en tant qu'administrateur ou sous-administrateur
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_mail']) || ($_SESSION['user_type'] !== 'admin' && $_SESSION['user_type'] !== 'sousadmin')) {
    // Redirection vers une page d'erreur si l'utilisateur n'est pas connecté en tant qu'admin ou sous-admin
    header('Location: ../connexion.php');
    exit();
}
// Vérifier si l'ID du prêt est présent dans l'URL
if(isset($_GET['id'])) {
    // Récupérer l'ID du prêt depuis l'URL
    $pret_id = $_GET['id'];

    try {
        // Connexion à la base de données
        $db = connexionbdd();

        // Mettre à jour le champ active_pret à 0 pour le prêt sélectionné
        $update_query = $db->prepare("UPDATE pret SET pret_active = 0 WHERE pret_id = ?");
        $update_query->execute([$pret_id]);

        // Redirection vers la liste des prêts
        header("Location: liste_prets.php");
        exit(); // Arrêter le script après la redirection
    } catch (PDOException $e) {
        // En cas d'erreur, afficher le message d'erreur
        echo "Erreur : " . $e->getMessage();
    }
} else {
    // Si l'ID du prêt n'est pas spécifié dans l'URL, afficher un message d'erreur
    echo "ID du prêt non spécifié.";
}
?>
