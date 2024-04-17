<?php
require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';

// Utilisation de la classe PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Connexion à la base de données
include 'ConnexionBD.php'; // Fichier de configuration de la connexion PDO

if(isset($_POST['submit'])) {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $adresse = $_POST['adresse'];
    $adresse_comp = $_POST['adresse_comp'];
    $cp = $_POST['cp'];
    $ville = $_POST['ville'];
    $tel = $_POST['tel'];
    $mail = $_POST['mail'];

    // Vérifier si un compte avec la même adresse e-mail existe déjà
    $query = $pdo->prepare("SELECT * FROM membres WHERE membre_mail = ?");
    $query->execute([$mail]);
    $existing_account = $query->fetch();

    if(!$existing_account) {
        // Générer automatiquement un nom d'utilisateur et un mot de passe
        $username = generateRandomString(8);
        $password = generateRandomString(10);

        // Insérer les informations du client dans la base de données
        $query = $pdo->prepare("INSERT INTO membres (membre_nom, membre_prenom, membre_mdp, membre_adresse, membre_adresse_comp, membre_cp, membre_ville, membre_tel, membre_inscription, membre_mail, membre_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, UNIX_TIMESTAMP(), ?, 'client')");
        $query->execute([$nom, $prenom, password_hash($password, PASSWORD_DEFAULT), $adresse, $adresse_comp, $cp, $ville, $tel, $mail]);

        // Envoi de l'email au client
        sendEmailToClient($mail, $prenom, $username, $password);

        echo 'Compte créé avec succès. Un email de vérification a été envoyé au client.';
    } else {
        echo 'Un compte avec cette adresse e-mail existe déjà.';
    }
}

// Fonction pour envoyer un email au client avec les informations de connexion
function sendEmailToClient($email, $prenom, $username, $password) {
    $mail = new PHPMailer(true);

    try {
        // Paramètres du serveur SMTP
        $mail->isSMTP();
        $mail->Host = 'localhost';
        $mail->SMTPAuth = true;
        $mail->Username = 'rania.masdoua@gmail.com';  // Adresse email de l'expéditeur
        $mail->Password = 'Rania2001*';           // Mot de passe de l'expéditeur
        $mail->SMTPSecure = 'tls';
        $mail->Port = 25;
        $mail->setFrom('','');
        $mail->addAddress($email, $prenom);   // Destinataire et prénom du client
        $mail->isHTML(true);
        $mail->Subject = 'Création de compte réussie';
        $mail->Body = 'Bonjour ' . $prenom . ',<br><br>Votre compte a été créé avec succès. Voici vos informations de connexion :<br><br>Nom d\'utilisateur : ' . $username . '<br>Mot de passe : ' . $password . '<br><br>Merci de votre confiance.<br>L\'équipe de notre site.';
        $mail->send();
        
        $_SESSION['message'] = 'Email envoyé avec succès.';
    } catch (Exception $e) {
        $_SESSION['message'] = 'Erreur lors de l\'envoi de l\'email : ' . $mail->ErrorInfo;
    }
}

// Fonction pour générer une chaîne aléatoire de longueur donnée
function generateRandomString($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de compte client</title>
</head>
<body>
    <h2>Création de compte client</h2>
    <form action="create_account.php" method="post">
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required><br>

        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" required><br>

        <label for="adresse">Adresse :</label>
        <input type="text" id="adresse" name="adresse" required><br>

        <label for="adresse_comp">Complément d'adresse :</label>
        <input type="text" id="adresse_comp" name="adresse_comp"><br>

        <label for="cp">Code postal :</label>
        <input type="text" id="cp" name="cp" required><br>

        <label for="ville">Ville :</label>
        <input type="text" id="ville" name="ville" required><br>

        <label for="tel">Téléphone :</label>
        <input type="text" id="tel" name="tel" required><br>

        <label for="mail">Email :</label>
        <input type="email" id="mail" name="mail" required><br>

        <input type="submit" name="submit" value="Créer le compte">
    </form>
</body>
</html>