<?php
//connexionBD.php
// Configuration de la base de données
/* $host = '127.0.0.1';
$port = '3307';
$dbname = 'stage';
$username = 'root';
$password = ''; */

//connexion.php
/*
    $membre_id = $_SESSION['membre_id']  // Identifiant de session de l'utilisateur.
    $_SESSION['membre_mail'] : Adresse email de session de l'utilisateur.
    $_SESSION['membre_mdp'] : Mot de passe de session de l'utilisateur.
    $_SESSION['membre_type'] : Type de membre (client, admin, etc.) de session de l'utilisateur.
    $error_message : Message d'erreur affiché en cas d'échec de connexion.
    $db : Objet de connexion à la base de données.
    $query : Requête préparée pour récupérer les informations de l'utilisateur.
    $result : Résultat de la requête pour obtenir les données de l'utilisateur.
*/

//mot_de_passe_oublie.php
/*
    $email : Adresse e-mail saisie dans le formulaire.
    $message : Message affiché après le traitement du formulaire.
    $type : Type de message (success ou danger) pour l'affichage.
    $db : Objet de connexion à la base de données.
    $query : Requête préparée pour récupérer les informations de l'utilisateur.
    $user : Résultat de la requête pour obtenir les données de l'utilisateur.
    // Fonction pour envoyer un e-mail avec le nouveau mot de passe
    sendNewPasswordEmail($email, $password) : Fonction pour envoyer un e-mail avec le nouveau mot de passe.
    $mail : Objet PHPMailer pour envoyer l'e-mail.
*/

//sav.php
/* $tri_defaut : Variable pour déterminer le mode de tri par défaut "membre_nom"
   $colonneTri_defaut : Variable pour déterminer la colonne de tri en fonction du tri choisi
    $tri :  C'est la variable qui détermine la manière dont les résultats sont triés
    $colonneTri :Cette variable est dérivée de $tri. Elle est utilisée dans la requête SQL
    $etat: C'est la variable qui stocke l'état sélectionné pour filtrer les résultats, comme 'Réceptionné', 'En cours', 'En attente', 'Terminé', ou 'Rendu au client'.
   $client_id : L'identifiant du client sélectionné (dans l'URL) pour lequel le profil est affiché. */

//sav-formulaire.php
/*
    $client_id : Identifiant du client passé dans l'URL.
    $_SESSION['membre_id'] : Identifiant du membre connecté.
    $_SESSION['membre_type'] : Type du membre connecté (client, administrateur, etc.).
    $html : Variable temporaire pour générer du code HTML dynamiquement.
    $i : Compteur pour les services ajoutés dynamiquement.
    $j : Compteur pour les matériels ajoutés dynamiquement.
    $total_ht : Total hors taxes (services + matériel).
    $total_ht_materiel : Total hors taxes du matériel.
    $total_ht_service : Total hors taxes des services.
    $tva : Taxe sur la valeur ajoutée.
    $prix_total_ttc : Prix total toutes taxes comprises.
*/

//traitement-sav.php
/*
   $membre_id : L'identifiant du client concerné par le service après-vente.
   $sav_technicien : L'identifiant de l'administrateur connecté qui gère le service après-vente.
   $probleme : Description du problème signalé par le client.
   $mot_de_passe : Mot de passe fourni par le client pour accéder au matériel.
   $type_materiel : Type de matériel concerné par le service après-vente.
   $accessoires : Liste des accessoires inclus dans le service après-vente.
   $etat : État du service après-vente (Réceptionné, En cours, En attente, Terminé, Rendu au client).
   $sous_garantie : Indicateur indiquant si le matériel est encore sous garantie.
   $date_recu : Date de réception du matériel pour le service après-vente.
   $date_livraison : Date de livraison du matériel après le service après-vente.
   $prix_materiel_ht : Prix total du matériel hors taxes.
   $prix_main_oeuvre_ht : Prix total de la main d'œuvre hors taxes.
   $facture_reglee : Indicateur indiquant si la facture est réglée.
   $envoi_facture : Méthode d'envoi de la facture (mail, courrier, ou les deux).
*/

//modifier-sav.php
/*
    $nouvel_etat : Nouvel état du SAV
    $nouvel_avancement : Nouvel avancement du SAV
    $sav_id : ID du SAV récupéré depuis l'URL
    $error : Message d'erreur en cas de problème avec la base de données
*/

//profile_client.php
/*
    $client_id : L'identifiant du client dont le profil est affiché, extrait de l'URL.
    $db : Connexion à la base de données.
    $query : Requête SQL pour récupérer le nom et le prénom du client en fonction de son ID.
    $stmt : Requête préparée pour récupérer les informations du client.
    $client : Résultat de la requête pour obtenir les données du client.
*/

?>
