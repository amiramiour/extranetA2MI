<?php
session_start(); // Démarrer la session si ce n'est pas déjà fait

// Vérifier si l'utilisateur est connecté et est un technicien
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_mail']) || ($_SESSION['user_type'] !== 'admin' && $_SESSION['user_type'] !== 'sousadmin')) {
    // Si l'utilisateur n'est pas connecté en tant qu'admin ou sous-admin, redirigez-le ou affichez un message d'erreur
    header("Location: ../connexion.php");
    exit;
}

// Vérifier si les données du formulaire ont été soumises
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inclure la connexion à la base de données
    include('../ConnexionBD.php');

    // Récupérer les données du formulaire
    $pret_materiel = $_POST["pret_materiel"];
    $valeurMat = $_POST["valeurMat"];
    $pret_caution = isset($_POST["pret_caution"]) ? $_POST["pret_caution"] : 0; // Si non défini, définir à 0
    $pret_mode = $_POST["pret_mode"];

    // Convertir les dates en timestamp Unix
    $pret_datein = $_POST["pret_datein"]; // Convertir la date en timestamp Unix
    $pret_dateout = $_POST["pret_dateout"]; // Convertir la date en timestamp Unix*




    $commentaire = $_POST["commentaire"];

    // Récupérer l'ID du technicien connecté
    $pret_technicien = $_SESSION['user_id'];
    $membre_id = $_POST['client_id'];

    // Valeur par défaut pour l'attribut "rappel"
    $rappel = 1;

    // Valeur par défaut pour l'attribut "pret_etat"
    $pret_etat = 1;

    // Etablir la connexion à la base de données
    $db = connexionbdd();

    // Préparer la requête SQL pour insérer les données du prêt
    $sql = "INSERT INTO pret (pret_materiel, pret_caution, pret_mode, pret_datein, pret_dateout, membre_id, pret_technicien, commentaire, valeurMat, rappel, pret_etat) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Préparer la déclaration SQL
    $stmt = $db->prepare($sql);

    // Exécuter la requête avec les valeurs des paramètres
    $stmt->execute([$pret_materiel, $pret_caution, $pret_mode, $pret_datein, $pret_dateout, $membre_id, $pret_technicien, $commentaire, $valeurMat, $rappel, $pret_etat]);

    //rediriger vers une page de succès
    header("Location: liste_prets.php");
    exit();
} else {
    // Si les données du formulaire n'ont pas été soumises, rediriger vers une page d'erreur
    header("Location: erreur.php");
    exit();
}

?>