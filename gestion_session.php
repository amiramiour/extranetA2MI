<?php
session_start();
// Vérifier si l'utilisateur est connecté et est un technicien
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_mail']) || ($_SESSION['user_type'] !== 'admin' && $_SESSION['user_type'] !== 'sousadmin' &&$_SESSION['user_type'] === 'client')) {
    // Si l'utilisateur n'est pas connecté en tant qu'admin ou sous-admin, redirigez-le ou affichez un message d'erreur
    header("Location: /connexion/connexion.php");
    exit;
}
?>