<?php
session_start();

// Supprimer les variables de session
session_unset();

// Détruire la session
session_destroy();

// Redirection vers la page d'accueil (index.php)
header('Location: index.php');
exit();
?>
