<?php
require_once '../connexionBD.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $connexion = connexionbdd();

        // Récupération de l'ID du client depuis le formulaire
        $membre_id = $_POST['client_id'];

        // Récupération de l'ID de l'admin connecté depuis la session
        // À traiter après
        /* session_start();
         $id_admin_connecte = $_SESSION['membre_id'];et on le met a la place de sav_technicien
      */
        $sav_technicien = 1;

        // Récupération des données du formulaire
        $probleme = $_POST['probleme'];
        $mot_de_passe = $_POST['mot_de_passe'];
        $type_materiel = $_POST['type_materiel'];
        $accessoires = $_POST['accessoires'];
        $etat = $_POST['etat'];
        $sous_garantie = $_POST['sous_garantie'];
        $date_recu = $_POST['date_recu']; // Nouvelle ligne pour récupérer la date de réception
        $date_livraison = $_POST['date_livraison']; // Nouvelle ligne pour récupérer la date de livraison

        // Récupération des totaux HT depuis le formulaire
        $prix_materiel_ht = $_POST['total_materiel_ht'];
        $prix_main_oeuvre_ht = $_POST['total_service_ht'];

        // Calcul des totaux TTC
        $tva_prix_materiel_ht = $prix_materiel_ht * 0.2;
        $prix_materiel_ttc = $prix_materiel_ht + $tva_prix_materiel_ht;

        $tva_prix_main_oeuvre_ht = $prix_main_oeuvre_ht * 0.2;
        $prix_main_oeuvre_ttc = $prix_main_oeuvre_ht + $tva_prix_main_oeuvre_ht;
        //facture réglée
        // Récupération de la valeur de la case à cocher "Facture réglée"
        $facture_reglee = isset($_POST['facture_reglee']) ? 'oui' : 'non';

        // Gestion de l'envoi de la facture
        $envoi_facture = '';

        if (isset($_POST['envoie_facture_mail']) && isset($_POST['envoie_facture_courrier'])) {
            // Les deux cases sont cochées
            $envoi_facture = 'mail,courrier';
        } elseif (isset($_POST['envoie_facture_mail'])) {
            // Seule la case mail est cochée
            $envoi_facture = 'mail';
        } elseif (isset($_POST['envoie_facture_courrier'])) {
            // Seule la case courrier est cochée
            $envoi_facture = 'courrier';
        }

        // Enregistrement dans la base de données
        $sql = "INSERT INTO sav (membre_id, sav_accessoire, sav_avancement, sav_datein, sav_dateout, sav_envoi, sav_etat, sav_etats, sav_forfait, sav_garantie, sav_maindoeuvreht, sav_maindoeuvrettc, sav_mdpclient, sav_probleme, sav_regle, sav_tarifmaterielht, sav_tarifmaterielttc, sav_typemateriel, sav_technicien) 
        VALUES (:membre_id, :accessoires, :avancement, :date_recu, :date_livraison, :envoi_facture, :etat, 1, :forfait, :garantie, :maindoeuvreht, :maindoeuvrettc, :mdpclient, :probleme, :facture_reglee, :tarifmaterielht, :tarifmaterielttc, :typemateriel, :sav_technicien)";

        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':membre_id', $membre_id); // Utilisation de $client_id pour l'ID du client
        /*a traiter apres $stmt->bindParam(':sav_technicien', $id_admin_connecte); // Utilisation de $id_admin_connecte pour l'ID de l'admin connecté  */
        $stmt->bindParam(':sav_technicien', $sav_technicien); // Utilisation de $id_admin_connecte pour l'ID de l'admin connecté

        $stmt->bindParam(':accessoires', $accessoires);
        $stmt->bindParam(':avancement', $etat);
        $stmt->bindParam(':etat', $etat); // Correction
        $stmt->bindParam(':date_recu', $date_recu); // Date d'entrée du SAV
        $stmt->bindParam(':date_livraison', $date_livraison); // Binding de la date de livraison
        $stmt->bindValue(':forfait', '0');
        $stmt->bindParam(':garantie', $sous_garantie);
        $stmt->bindParam(':maindoeuvreht', $prix_main_oeuvre_ht);
        $stmt->bindParam(':maindoeuvrettc', $prix_main_oeuvre_ttc);
        $stmt->bindParam(':mdpclient', $mot_de_passe);
        $stmt->bindParam(':probleme', $probleme);
        $stmt->bindParam(':tarifmaterielht', $prix_materiel_ht);
        $stmt->bindParam(':tarifmaterielttc', $prix_materiel_ttc);
        $stmt->bindParam(':typemateriel', $type_materiel);
        $stmt->bindParam(':facture_reglee', $facture_reglee);
        $stmt->bindParam(':envoi_facture', $envoi_facture); // Déplacement et correction

        $stmt->execute();

        echo "Enregistrement du SAV effectué avec succès.";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    } finally {
        $connexion = null;
    }
} else {
    header('Location: ../index.php');
    exit();
}
?>
