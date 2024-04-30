<?php
session_start(); // Démarrer la session si ce n'est pas déjà fait

// Vérifier si l'utilisateur est connecté et est un technicien
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_mail']) || ($_SESSION['user_type'] !== 'admin' && $_SESSION['user_type'] !== 'sousadmin')) {
    // Si l'utilisateur n'est pas connecté en tant qu'admin ou sous-admin, redirigez-le ou affichez un message d'erreur
    header("Location: ../connexion.php");
    exit;
}

// Inclure la connexion à la base de données
include('../ConnexionBD.php');

// Vérifier si les données du formulaire ont été soumises
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $pret_materiel = $_POST["pret_materiel"];
    $valeurMat = $_POST["valeurMat"];
    $pret_caution = isset($_POST["pret_caution"]) ? $_POST["pret_caution"] : 0; // Par défaut, la caution est 0 si non fournie
    $pret_mode = $_POST["pret_mode"];
    $pret_datein = $_POST["pret_datein"];
    $pret_dateout = $_POST["pret_dateout"];
    $commentaire = $_POST["commentaire"];

    // Récupérer l'ID du technicien connecté depuis la session
    $technicien_id = $_SESSION['user_id'];

    try {
        // Établir la connexion à la base de données
        $db = connexionbdd();

        // Préparer la requête SQL pour insérer les données du prêt
        $sql = "INSERT INTO pret (pret_materiel, pret_caution, pret_mode, pret_datein, pret_dateout, pret_technicien, commentaire, valeurMat) VALUES (:pret_materiel, :pret_caution, :pret_mode, :pret_datein, :pret_dateout, :pret_technicien, :commentaire, :valeurMat)";

        // Préparer la déclaration SQL
        $stmt = $db->prepare($sql);

        // Lier les valeurs des paramètres
        $stmt->bindParam(':pret_materiel', $pret_materiel);
        $stmt->bindParam(':pret_caution', $pret_caution);
        $stmt->bindParam(':pret_mode', $pret_mode);
        $stmt->bindParam(':pret_datein', $pret_datein);
        $stmt->bindParam(':pret_dateout', $pret_dateout);
        $stmt->bindParam(':pret_technicien', $technicien_id);
        $stmt->bindParam(':commentaire', $commentaire);
        $stmt->bindParam(':valeurMat', $valeurMat);

        // Exécuter la requête
        $stmt->execute();

        // Rediriger vers une page de succès ou une autre page appropriée
        header("Location: page_succes.php");
        exit();
    } catch (PDOException $e) {
        // Gérer les erreurs de base de données
        echo "Erreur: " . $e->getMessage();
    }
} else {
    // Si les données du formulaire n'ont pas été soumises, rediriger vers une page d'erreur
    header("Location: erreur.php");
    exit();
}
?>
