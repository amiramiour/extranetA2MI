<?php
session_start();

// Vérifier si l'utilisateur est connecté et est un technicien
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_mail']) || ($_SESSION['user_type'] !== 'admin' && $_SESSION['user_type'] !== 'sousadmin')) {
    // Si l'utilisateur n'est pas connecté en tant qu'admin ou sous-admin, redirigez-le ou affichez un message d'erreur
    header("Location: ../connexion.php");
    exit;
}

// Vérifier si le formulaire a été soumis et si l'identifiant du prêt est défini
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pret_id'])) {
    // Récupérer les valeurs des champs du formulaire
    $pret_id = $_POST['pret_id'];
    $caution = $_POST['caution'];
    // Mode de paiement est en lecture seule, donc pas besoin de récupérer sa valeur
    //$date_rendu = $_POST['date_rendu'];
    $commentaire = $_POST['commentaire'];



// Convertir la date au format UNIX TIMESTAMP
    //$date_rendu_timestamp = strtotime($date_rendu);

    $date_rendu = strtotime(str_replace('/', '-', $_POST["date_rendu"]));




    // Inclure la connexion à la base de données
    include('../ConnexionBD.php');

    try {
        // Etablir la connexion à la base de données
        $db = connexionbdd();

        // Requête SQL pour mettre à jour les détails du prêt
        $query = "UPDATE pret SET pret_caution = :caution, pret_dateout = :date_rendu, commentaire = :commentaire WHERE pret_id = :pret_id";

        // Préparer la requête SQL
        $stmt = $db->prepare($query);

        // Liaison des valeurs des paramètres de requête
        $stmt->bindParam(':caution', $caution, PDO::PARAM_STR);
        $stmt->bindParam(':date_rendu', $date_rendu, PDO::PARAM_STR);
        $stmt->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
        $stmt->bindParam(':pret_id', $pret_id, PDO::PARAM_INT);

        // Exécution de la requête
        if ($stmt->execute()) {
            // Rediriger vers la liste des prêts si la mise à jour est réussie
            header("Location: liste_prets.php");
            exit;
        } else {
            // Rediriger vers une page d'erreur si la mise à jour échoue
            header("Location: erreur.php");
            exit;
        }
    } catch (PDOException $e) {
        // Afficher un message d'erreur en cas d'erreur PDO
        echo "Erreur : " . $e->getMessage();
    }
} else {
    // Rediriger vers une page d'erreur si le formulaire n'a pas été soumis correctement
    header("Location: erreur.php");
    exit;
}
?>