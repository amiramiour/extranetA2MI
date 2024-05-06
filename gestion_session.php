<?php
session_start();
// Vérifier si l'utilisateur est connecté et est un technicien
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_mail'])  || $_SESSION['user_type'] === 'client') {
    // Si l'utilisateur n'est pas connecté ou est un client, redirigez-le ou affichez un message d'erreur
    header("Location: /extranetA2MI/connexion/connexion.php");
    exit;
}
?>