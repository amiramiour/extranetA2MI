<?php
session_start();

require_once('../connexion/traitement_connexion.php');

// Vérifier si le formulaire de suppression a été soumis
if (isset($_POST['delete']) && $_POST['delete'] === 'delete') {
    try {
        // Récupérer l'ID de la commande à partir de la requête GET
        $cmd_devis_id = $_GET['id'];

        // Préparer la requête SQL pour mettre à jour le champ active_commande à 0
        $req = "UPDATE cmd_devis SET active_commande = 0 WHERE cmd_devis_id = :cmd_devis_id";
        $req = $pdo->prepare($req);
        $req->bindParam(':cmd_devis_id', $cmd_devis_id, PDO::PARAM_INT);
        $req->execute();

        //on récupère le type de la commande/devis
        $req = $pdo->prepare("SELECT type_cmd_devis FROM cmd_devis WHERE cmd_devis_id = :cmd_devis_id");
        $req->bindParam(':cmd_devis_id', $cmd_devis_id);
        $req->execute();
        $type_cmd_devis = $req->fetchColumn();;

        if($type_cmd_devis == 1){
            // Rediriger vers la liste des commandes après la suppression
            header('Location: ../list_commandes.php');
            exit();
        }else{
            // Rediriger vers la liste des commandes après la suppression
            header('Location: ../list_devis.php');
            exit();
        }
    } catch (PDOException $e) {
        // Gérer les erreurs de base de données
        $error = "Erreur : " . $e->getMessage();
        echo $error;
    }
}
?>
