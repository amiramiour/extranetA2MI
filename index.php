<?php
session_start();
header('Content-type: text/html; charset=utf-8');

include('ConnexionBD.php');
include('navbar.php');

// Vérifier si l'utilisateur est connecté (variable de session définie)
if (isset($_SESSION['connected']) && $_SESSION['connected']) {
    $message = "Vous êtes connecté.";
    // Une fois que le message est affiché, vous pouvez réinitialiser la variable de session
    unset($_SESSION['connected']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <!-- Inclure le fichier CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Inclure le fichier CSS personnalisé -->
    <link href="styles.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-lg-3">
            <!-- Si vous n'avez pas besoin d'inclure colg.php, vous pouvez le supprimer -->
            <!-- <?php //include ('includes/colg.php'); ?> -->
        </div>
        <div class="col-lg-9">
            <h1 class="mt-5">Bienvenue sur l'extranet d'a2mi !</h1>
            <?php
            // Afficher le message si présent
            if (isset($message)) {
                echo "<p>$message</p>";
            } else {
                // Sinon, afficher le lien de connexion
                echo "<p>Pour pouvoir accéder à votre fiche client, il faut <a href='connexion/connexion.php'>se connecter</a> !</p>";
            }
            ?>
        </div>
    </div>
</div>

<!-- Inclure le fichier JavaScript de Bootstrap à la fin du corps -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
