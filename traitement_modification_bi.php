<?php
// Inclure la connexion à la base de données
include('connexionBD.php');

// Etablir la connexion à la base de données
$db = connexionbdd();

// Vérifier si les données du formulaire ont été soumises
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $bi_id = $_POST["bi_id"];
    $facturer = isset($_POST["facturer"]) ? 1 : 0;
    $garantie = isset($_POST["garantie"]) ? 1 : 0;
    $contrat_pack = isset($_POST["contrat_pack"]) ? 1 : 0;
    $service_personne = isset($_POST["service_personne"]) ? 1 : 0;
    $regle = isset($_POST["regle"]) ? 1 : 0;
    $facturation = $_POST["facturation"];
    $commentaire = $_POST["commentaire"];

    // Préparer la requête SQL pour mettre à jour les données du bon d'intervention
    $sql = "UPDATE bi SET bi_facture = ?, bi_garantie = ?, bi_contrat = ?, bi_service = ?, bi_regle = ?, bi_facturation = ?, bi_commentaire = ? WHERE bi_id = ?";

    // Préparer la déclaration SQL
    $stmt = $db->prepare($sql);

    // Exécuter la requête avec les valeurs des paramètres
    $stmt->execute([$facturer, $garantie, $contrat_pack, $service_personne, $regle, $facturation, $commentaire, $bi_id]);

    // Rediriger vers la page de détails du bon d'intervention
    header("Location: bi_details.php?bi_id=$bi_id");
    exit();
} else {
    // Si les données du formulaire n'ont pas été soumises, rediriger vers une page d'erreur
    header("Location: erreur.php");
    exit();
}
?>
