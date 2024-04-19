<?php
require_once 'connexionBD.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $connexion = connexionbdd();
        $membre_id=17;
        $sav_technicien=1;
        // Récupération des données du formulaire
        $probleme = $_POST['probleme'];
        $mot_de_passe = $_POST['mot_de_passe'];
        $type_materiel = $_POST['type_materiel'];
        $accessoires = $_POST['accessoires'];
        $etat = $_POST['etat'];
        $sous_garantie = $_POST['sous_garantie'];
        $envoi_facture = isset($_POST['envoie_facture']) ? $_POST['envoie_facture'] : '';
        $date_recu = $_POST['date_recu']; // Nouvelle ligne pour récupérer la date de réception
        $date_livraison = $_POST['date_livraison']; // Nouvelle ligne pour récupérer la date de livraison



        // Calcul du prix total pour le matériel
        $prix_materiel_ht = 0;
        if (isset($_POST['materiel'])) {
            foreach ($_POST['materiel'] as $index => $materiel) {
                $prix_materiel_ht += $_POST['nombre'][$index] * $_POST['prix_unité'][$index];
            }
        }

        // Calcul du prix total pour la main d'oeuvre
        $prix_main_oeuvre_ht = 0;
        if (isset($_POST['type_service'])) {
            foreach ($_POST['type_service'] as $index => $service) {
                $prix_main_oeuvre_ht += $_POST['nombre_heures'][$index] * $_POST['prix_unité_main_oeuvre'][$index];
            }
        }

        // Calcul des totaux
        $prix_total_ht = $prix_materiel_ht + $prix_main_oeuvre_ht;
        $tva = $prix_total_ht * 0.2;
        $prix_total_ttc = $prix_total_ht + $tva;

        // Enregistrement dans la base de données
        $sql = "INSERT INTO sav (membre_id, sav_accessoire, sav_avancement, sav_datein, sav_dateout, sav_envoi, sav_etat, sav_etats, sav_forfait, sav_garantie, sav_maindoeuvreht, sav_maindoeuvrettc, sav_mdpclient, sav_probleme, sav_regle, sav_tarifmaterielht, sav_tarifmaterielttc, sav_typemateriel,sav_technicien) 
                VALUES (:membre_id, :accessoires, :avancement, :date_recu, :date_livraison, :envoi_facture, :etat, 1, :forfait, :garantie, :maindoeuvreht, :maindoeuvrettc, :mdpclient, :probleme, 'non', :tarifmaterielht, :tarifmaterielttc, :typemateriel,:sav_technicien)";

        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':membre_id', $membre_id);
        $stmt->bindParam(':sav_technicien', $sav_technicien);

        $stmt->bindParam(':accessoires', $accessoires);
        $stmt->bindParam(':avancement', $etat);
        $stmt->bindParam(':envoi_facture', $envoi_facture);
        $stmt->bindValue(':etat', $etat);
        $stmt->bindParam(':date_recu', $date_recu); // Date d'entrée du SAV
        $stmt->bindParam(':date_livraison', $date_livraison); // Binding de la date de livraison
        $stmt->bindValue(':forfait', '0');
        $stmt->bindParam(':garantie', $sous_garantie);
        $stmt->bindParam(':maindoeuvreht', $prix_main_oeuvre_ht);
        $stmt->bindParam(':maindoeuvrettc', $prix_total_ttc);
        $stmt->bindParam(':mdpclient', $mot_de_passe);
        $stmt->bindParam(':probleme', $probleme);
        $stmt->bindParam(':tarifmaterielht', $prix_materiel_ht);
        $stmt->bindParam(':tarifmaterielttc', $prix_total_ttc);
        $stmt->bindParam(':typemateriel', $type_materiel);

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
