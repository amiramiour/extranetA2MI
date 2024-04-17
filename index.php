<?php
session_start();
header('Content-type: text/html; charset=utf-8');

/********Inclusion du fichier de connexion à la base de données**********/
include ('connexionBD.php')
/********Fin inclusion du fichier de connexion à la base de données**********/

/********Actualisation de la session...**********/
// Supprimez cette ligne car la connexion PDO est déjà gérée dans connexionbdd.php
// actualiser_session($db);
/********Fin actualisation de session...**********/
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <!-- Inclure le fichier CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
            <p>Pour pouvoir accéder à votre fiche client, il faut <a href="membres/connexion.php">se connecter</a> !</p>
            <p>Si vous êtes déjà connecté, vous pouvez accéder à votre <a href="membres/user.php">fiche client</a> pour accéder à vos traitements en cours.</p>
            <p>Le Webmaster</p>
        </div>
    </div>
</div>

<!-- Inclure le fichier JavaScript de Bootstrap à la fin du corps -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Vous n'avez pas besoin de fermer la connexion $db dans ce fichier, car vous n'avez pas de traitement SQL ici.
?>
