<?php
session_start();
header('Content-type: text/html; charset=utf-8');

include('ConnexionBD.php');

<<<<<<< HEAD
// Vérifier si l'utilisateur est connecté (variable de session définie)
if (isset($_SESSION['connected']) && $_SESSION['connected']) {
    $message = "Vous êtes connecté.";
    // Une fois que le message est affiché, vous pouvez réinitialiser la variable de session
    unset($_SESSION['connected']);
=======
// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    // Si l'utilisateur est connecté, vérifier son type
    if ($_SESSION['user_type'] === 'client') {
        // Si l'utilisateur est un client, le rediriger vers la page de profil du client
        header("Location: /profile/profile_client.php");
        exit;
    } else {
        // Si l'utilisateur est un administrateur ou un sous-administrateur, inclure la navbar
        include('navbar.php');
    }
} else {
    // Si l'utilisateur n'est pas connecté, ne pas inclure la navbar
    // Rediriger vers la page de connexion
    header("Location: connexion/connexion.php");
    exit;
>>>>>>> 9e4ea5c1e90af3d9c318d0eeae636e0326af9050
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil</title>
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
<<<<<<< HEAD
            <h1 class="mt-5">Bienvenue sur l'extranet d'a2mi !</h1>
            <?php
            // Afficher le message si présent
            if (isset($message)) {
                echo "<p>$message</p>";
            } else {
                // Sinon, afficher le lien de connexion
                echo "<p>Pour pouvoir accéder à votre fiche client, il faut <a href='connexion/connexion.php'>se connecter</a> !</p>";
=======
            <h1 class="mt-5">Bienvenue sur l'extranet d'A2MI !</h1>
            <?php
            // Afficher le lien de déconnexion si l'utilisateur est connecté
            if (isset($_SESSION['user_id'])) {
                echo "<p><a href='connexion/deconnexion.php'>Déconnexion</a></p>";
>>>>>>> 9e4ea5c1e90af3d9c318d0eeae636e0326af9050
            }
            ?>
        </div>
    </div>
</div>

<!-- Inclure le fichier JavaScript de Bootstrap à la fin du corps -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
