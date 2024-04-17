<?php
session_start();

// Supprimer les variables de session
session_unset();

// Détruire la session
session_destroy();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Déconnexion</title>
    <style>
        /* Style pour le bouton de reconnexion */
        #reconnexion-btn {
            position: fixed;
            top: 10px;
            right: 10px;
            padding: 8px 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<h2>Vous êtes déconnecté.</h2>
<!-- Bouton de reconnexion -->
<a id="reconnexion-btn" href="connexion.php">Se connecter à nouveau</a>

</body>
</html>
