<?php
// Inclure la connexion à la base de données
include('../ConnexionBD.php');
include "../gestion_session.php";
// Etablir la connexion à la base de données
$db = connexionbdd();

// Vérifier si les données du formulaire ont été soumises
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $bi_id = $_POST["bi_id"];

    // Convertir les valeurs des cases à cocher en "oui" ou "non"
    $facturer = isset($_POST["facturer"]) ? "oui" : "non";
    $garantie = isset($_POST["garantie"]) ? "oui" : "non";
    $contrat_pack = isset($_POST["contrat_pack"]) ? "oui" : "non";
    $service_personne = isset($_POST["service_personne"]) ? "oui" : "non";
    $regle = isset($_POST["regle"]) ? "oui" : "non";

    $facturation = $_POST["facturation"];
    $commentaire = $_POST["commentaire"];

    // Préparer la requête SQL pour mettre à jour les données du bon d'intervention
    $sql = "UPDATE bi SET bi_facture = ?, bi_garantie = ?, bi_contrat = ?, bi_service = ?, bi_regle = ?, bi_facturation = ?, bi_commentaire = ? WHERE bi_id = ?";

    // Préparer la déclaration SQL
    $stmt = $db->prepare($sql);

    // Exécuter la requête avec les valeurs des paramètres
    $stmt->execute([$facturer, $garantie, $contrat_pack, $service_personne, $regle, $facturation, $commentaire, $bi_id]);

    // Récupérer l'identifiant du membre associé à ce bon d'intervention
    $query = $db->prepare("SELECT membre_id FROM bi WHERE bi_id = ?");
    $query->execute([$bi_id]);
    $membre_id = $query->fetchColumn();

    // Rediriger vers la page des détails des bons d'intervention associés à ce membre
    header("Location: /bi/bi_details.php?membre_id=$membre_id");
    exit();
} else {
    // Si les données du formulaire n'ont pas été soumises, rediriger vers une page d'erreur
    header("Location: erreur.php");
    exit();
}
?>
