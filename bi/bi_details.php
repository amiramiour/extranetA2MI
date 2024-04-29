<?php
// Inclure le fichier de connexion à la base de données
session_start(); // Démarrer la session si ce n'est pas déjà fait

// Vérifier si l'utilisateur est connecté et est un technicien
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_mail']) || $_SESSION['user_type'] === 'client') {
    // Si l'utilisateur n'est pas connecté ou est un client, redirigez-le ou affichez un message d'erreur
    header("Location: ../connexion.php");
    exit;
}
include '../ConnexionBD.php';

$db = connexionbdd();
$query = $db->prepare("SELECT membres.membre_id, membres.membre_nom, membres.membre_prenom, bi.bi_datein FROM bi INNER JOIN membres ON bi.membre_id = membres.membre_id");

// Vérifier si l'identifiant du membre est passé dans l'URL
if(isset($_GET['membre_id'])) {
    // Récupérer l'identifiant du membre depuis l'URL
    $membre_id = $_GET['membre_id'];

    // Préparer la requête SQL pour récupérer les bons d'intervention actifs associés à ce membre
    $query = $db->prepare("SELECT * FROM bi WHERE membre_id = ? AND bi_active = 1");
    $query->execute([$membre_id]);
    $bons_intervention = $query->fetchAll();
}

// Traitement de la suppression d'un bon d'intervention
if(isset($_POST['action']) && $_POST['action'] === 'supprimer' && isset($_POST['bi_id'])) {
    // Récupérer l'ID du bon d'intervention à supprimer
    $bi_id = $_POST['bi_id'];

    // Mettre à jour l'attribut bi_active dans la base de données
    $update_query = $db->prepare("UPDATE bi SET bi_active = 0 WHERE bi_id = ?");
    $update_query->execute([$bi_id]);

    // Répondre avec un message indiquant que la suppression a été effectuée
    echo "Le bon d'intervention a été supprimé avec succès.";
    exit; // Arrêter le script après la réponse AJAX
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails des bons d'intervention</title>
    <!-- Inclure le fichier CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Inclure le fichier CSS personnalisé -->
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>

<!-- Inclure le navbar -->
<?php include('../navbar.php'); ?>

<div class="container mt-3">
    <!-- Titre de la page -->
    <h1>Détails des bons d'intervention</h1>

    <!-- Afficher le bouton Ajouter -->
    <a href="bi_form.php?membre_id=<?php echo $membre_id; ?>" class="btn btn-primary custom-bleu-btn">Ajouter</a>

    <!-- Vérifier si des bons d'intervention sont disponibles -->
    <?php if(isset($bons_intervention) && !empty($bons_intervention)): ?>
        <!-- Afficher les détails des bons d'intervention -->
        <div class="row" id="bons-intervention">
            <?php foreach($bons_intervention as $bi): ?>
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">BI n° <?php echo $bi['bi_id']; ?></h5>
                            <p>Date de création : <?php echo date('d/m/Y', $bi['bi_datein']); ?></p>

                            <!-- Afficher d'autres détails de l'intervention ici -->
                            <p>Facturation : <?php echo $bi['bi_facturation']; ?></p>
                            <p>Heure d'arrivée : <?php echo $bi['bi_heurearrive']; ?></p>
                            <p>Heure de départ : <?php echo $bi['bi_heuredepart']; ?></p>
                            <!-- Ajoutez d'autres champs selon vos besoins -->
                            <!-- Bouton Supprimer avec l'attribut data-bi-id -->
                            <button type="button" class="btn btn-danger btn-supprimer" data-bi-id="<?php echo $bi['bi_id']; ?>">Supprimer</button>
                            <a href="modification_bi.php?bi_id=<?php echo $bi['bi_id']; ?>" class="btn btn-primary custom-btn">Modifier</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php else: ?>
        <p>Aucun bon d'intervention trouvé pour ce membre.</p>
    <?php endif; ?>
</div>

<!-- Inclure le fichier JavaScript de Bootstrap à la fin du corps -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Inclure jQuery pour les requêtes AJAX -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    // Ecouteur d'événement sur les boutons "Supprimer"
    $(".btn-supprimer").click(function() {
        // Récupérer l'ID du bon d'intervention à supprimer
        var bi_id = $(this).data("bi-id");
        // Demander confirmation à l'utilisateur
        if (confirm("Êtes-vous sûr de vouloir supprimer ce bon d'intervention ?")) {
            // Supprimer l'élément de la page HTML
            $(this).closest(".col-md-4").remove();
            // Envoyer une requête AJAX pour supprimer le bon d'intervention de la base de données
            $.ajax({
                url: "",
                type: "POST",
                data: { action: 'supprimer', bi_id: bi_id },
                success: function(response) {
                    alert(response); // Afficher le message de confirmation
                }
            });
        }
    });
</script>

</body>
</html>