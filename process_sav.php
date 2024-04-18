<?php
// Vérifiez d'abord si des données ont été soumises
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inclusion du fichier connexionBD.php
    require_once 'connexionBD.php';

    // Connexion à la base de données en utilisant la fonction connexionbdd() définie dans connexionBD.php
    try {
        $db = connexionbdd();
    } catch (PDOException $e) {
        echo "Erreur de connexion à la base de données : " . $e->getMessage();
        exit();
    }

    // Récupération des données soumises du formulaire
    $probleme = $_POST['probleme'];
    $mdp = $_POST['mdp'];
    $regle = isset($_POST['regle']) ? $_POST['regle'] : 'non'; // La valeur par défaut est 'non' si la case n'est pas cochée
    $envoi_courrier = isset($_POST['courrier']) ? 'oui' : 'non';
    $envoi_mail = isset($_POST['mail']) ? 'oui' : 'non';
    $typemateriel = $_POST['typemateriel'];
    $accessoires = $_POST['access'];
    $maindoeuvre = $_POST['listForfait'] . ' - ' . $_POST['maindoeuvre'][0] . ' x ' . $_POST['maindoeuvre'][1];
    $materiels = $_POST['dynamic'][1]; // Un tableau contenant les données des matériaux
    $avancement = $_POST['avancement'];
    $etat = $_POST['etat'];
    $garantie = $_POST['garantie'];

    // Préparation de la requête d'insertion
    $sql = "INSERT INTO sav (probleme, mdp, regle, envoi_courrier, envoi_mail, typemateriel, accessoires, maindoeuvre, avancement, etat, garantie) 
            VALUES (:probleme, :mdp, :regle, :envoi_courrier, :envoi_mail, :typemateriel, :accessoires, :maindoeuvre, :avancement, :etat, :garantie)";
    $stmt = $db->prepare($sql);

    // Liaison des valeurs des paramètres avec les données soumises
    $stmt->bindParam(':probleme', $probleme);
    $stmt->bindParam(':mdp', $mdp);
    $stmt->bindParam(':regle', $regle);
    $stmt->bindParam(':envoi_courrier', $envoi_courrier);
    $stmt->bindParam(':envoi_mail', $envoi_mail);
    $stmt->bindParam(':typemateriel', $typemateriel);
    $stmt->bindParam(':accessoires', $accessoires);
    $stmt->bindParam(':maindoeuvre', $maindoeuvre);
    $stmt->bindParam(':avancement', $avancement);
    $stmt->bindParam(':etat', $etat);
    $stmt->bindParam(':garantie', $garantie);

    try {
        // Exécution de la requête préparée
        $stmt->execute();

        // Redirection vers la page de confirmation en cas de succès
        header("Location: confirmation.php");
        exit();
    } catch (PDOException $e) {
        // En cas d'erreur, affichez un message d'erreur
        echo "Erreur : " . $e->getMessage();
    }

    // Fermeture de la connexion à la base de données
    $db = null;
} else {
    // Si aucune donnée n'a été soumise, redirigez l'utilisateur vers une autre page ou affichez un message d'erreur
    header('Location: index.php');
    exit();
}
?>
