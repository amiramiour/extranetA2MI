<?php
session_start();
header('Content-type: text/html; charset=utf-8');
include ('connexionBD.php');
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
<nav class="navbar navbar-expand-lg navbar-dark bg-color">
    <div class="container-fluid">
        <!-- Marque et bascule pour mobile -->
        <a href="index.php" class="navbar-brand">
            <img src="images/home.png" width="30" height="30" class="d-inline-block align-top" alt="">
        </a>
        <a class="navbar-brand" href="index.php">A2MI - EXTRANET</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Liens de navigation -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#">Créer un compte client</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="liste_clients.php">Liste clients</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Liste fournisseurs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Commandes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Devis</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="liste_interventions.php">Bons d'intervention</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">SAV</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Prêts</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Historique</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Liste d'administrateurs</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-lg-3">
            <!-- Si vous n'avez pas besoin d'inclure colg.php, vous pouvez le supprimer -->
            <!-- <?php //include ('includes/colg.php'); ?> -->
        </div>
        <div class="col-lg-9">
            <h1 class="mt-5">Bienvenue sur l'extranet d'a2mi !</h1>
            <p>Pour pouvoir accéder à votre fiche client, il faut <a href="connexion.php">se connecter</a> !</p>
            <p>Si vous êtes déjà connecté, vous pouvez accéder à votre <a href="membres/user.php">fiche client</a> pour accéder à vos traitements en cours.</p>
            <p>Le Webmaster</p>
        </div>
    </div>
</div>

<!-- Inclure le fichier JavaScript de Bootstrap à la fin du corps -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<style>
    .bg-color {
        background-color: #C8356C !important;
    }
</style>
