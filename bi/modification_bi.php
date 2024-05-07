<?php include('../navbar.php'); ?>
<?php
include "../gestion_session.php";
// Inclure le fichier de connexion à la base de données
include '../ConnexionBD.php';

// Etablir la connexion à la base de données
$db = connexionbdd();

// Vérifier si l'identifiant du bon d'intervention est passé dans l'URL
if(isset($_GET['bi_id'])) {
    // Récupérer l'identifiant du bon d'intervention depuis l'URL
    $bi_id = $_GET['bi_id'];

    // Préparer la requête SQL pour récupérer les détails du bon d'intervention
    $query = $db->prepare("SELECT * FROM bi WHERE bi_id = ?");
    $query->execute([$bi_id]);
    $bi = $query->fetch();

    // Vérifier si les détails du bon d'intervention sont disponibles
    if(isset($bi) && !empty($bi)) {
        // Récupérer d'autres détails du bon d'intervention
        $date = date('d/m/Y', strtotime($bi['bi_datein'])); // Formater la date
        // Ajouter d'autres détails du bon d'intervention ici
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire Bon d'Intervention Client</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>

<div class="container">
    <!-- Affichage des détails du bon d'intervention -->
    <div class="card mt-5">
        <div class="card-body">
            <h5 class="card-title">Détails du bon d'intervention</h5>
            <p class="card-text">ID du bon d'intervention : <?php echo $bi_id; ?></p>
            <p class="card-text">Date du bon d'intervention : <?php echo date('d/m/Y', strtotime($date)); ?></p>

        </div>
    </div>

    <!-- Formulaire de modification -->
    <div class="card mt-5">
        <div class="card-body">
            <h5 class="card-title">Modifier le bon d'intervention</h5>
            <form action="traitement_modification_bi.php" method="post">
                <input type="hidden" name="bi_id" value="<?php echo $bi_id; ?>">

                <!-- Champs de modification -->
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="facturer" id="facturer">
                    <label class="form-check-label" for="facturer">Facturer</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="garantie" id="garantie">
                    <label class="form-check-label" for="garantie">Garantie</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="contrat_pack" id="contrat_pack">
                    <label class="form-check-label" for="contrat_pack">Contrat/Pack</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="service_personne" id="service_personne">
                    <label class="form-check-label" for="service_personne">Service à la personne</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="regle" id="regle">
                    <label class="form-check-label" for="regle">Réglé</label>
                </div>

                <div class="form-group">
                    <label for="facturation">Facturation *</label>
                    <select class="form-control" id="facturation" name="facturation">
                        <option value="immediate">Immédiate</option>
                        <option value="differee">Différée</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="commentaire">Laisser un commentaire</label>
                    <textarea class="form-control" id="commentaire" name="commentaire" rows="3"></textarea>
                </div>

                <!-- Bouton "Valider" -->
                <button type="submit" class="btn btn-primary">Valider</button>
            </form>
        </div>
    </div>
</div>