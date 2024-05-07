<?php
require_once '../config.php';

session_start();

// Vérifier si l'utilisateur est connecté et est un technicien
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_mail'])  || $_SESSION['user_type'] === 'client') {
    // Si l'utilisateur n'est pas connecté ou est un client, redirigez-le ou affichez un message d'erreur
    header("Location: connexion.php");
    exit;
}

require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include '../ConnexionBD.php';
include '../navbar.php';
$pdo = connexionbdd();

if(isset($_POST['submit'])) {
    $entreprise = $_POST['entreprise'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $adresse = $_POST['adresse'];
    $adresse_comp = $_POST['adresse_comp'];
    $cp = $_POST['cp'];
    $ville = $_POST['ville'];
    $tel = $_POST['tel'];
    $mail = $_POST['mail'];
    $type = $_POST['type'];

    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $message = 'L\'adresse email n\'est pas valide.';
        $type = 'danger';
    } else {
        // Vérifier si un compte avec la même adresse e-mail existe déjà
        $query = $pdo->prepare("SELECT * FROM membres WHERE membre_mail = ?");
        $query->execute([$mail]);
        $existing_account = $query->fetch();

        if(!$existing_account) {
            // Générer automatiquement un mot de passe
            $password = generateRandomString(10);

            // Vérifier si un fichier image a été téléchargé
            if(isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                // Vérifier le type de fichier
                $image_info = getimagesize($_FILES['logo']['tmp_name']);
                if($image_info !== false) {
                    // Si c'est une image, procéder à l'insertion

                    // Définir le dossier de destination et le nom du fichier
                    $logo_tmp_name = $_FILES['logo']['tmp_name'];
                    $logo_name = $_FILES['logo']['name'];
                    $logo_destination = '../images/logoEntreprises/' . $logo_name;

                    // Déplacer le fichier vers le dossier de destination
                    if(move_uploaded_file($logo_tmp_name, $logo_destination)) {
                        // Insérer les informations du client dans la base de données avec le nom du logo
                        $query = $pdo->prepare("INSERT INTO membres (membre_entreprise, membre_nom, membre_prenom, membre_mdp, membre_adresse, membre_adresse_comp, membre_cp, membre_ville, membre_tel, membre_inscription, membre_mail, membre_type, membre_logo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, UNIX_TIMESTAMP(), ?, ?, ?)");
                        $query->execute([$entreprise, $nom, $prenom, password_hash($password, PASSWORD_BCRYPT), $adresse, $adresse_comp, $cp, $ville, $tel, $mail, $type, $logo_name]);

                        // Envoi de l'email au client
                        sendEmailToClient($mail, $nom, $prenom,$password);

                        $message = 'Compte créé avec succès. Un email de vérification a été envoyé au client.';
                        $type = 'success';
                    } else {
                        // Gérer l'erreur si le téléchargement échoue
                        $message = 'Une erreur est survenue lors du téléchargement du fichier.';
                        $type = 'danger';
                    }
                } else {
                    $message = 'Le fichier téléchargé n\'est pas une image.';
                    $type = 'danger';
                }
            } else {
                // Si aucun fichier n'a été téléchargé, exécuter la requête sans le logo
                $query = $pdo->prepare("INSERT INTO membres (membre_entreprise, membre_nom, membre_prenom, membre_mdp, membre_adresse, membre_adresse_comp, membre_cp, membre_ville, membre_tel, membre_inscription, membre_mail, membre_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, UNIX_TIMESTAMP(), ?, ?)");
                $query->execute([$entreprise, $nom, $prenom, password_hash($password, PASSWORD_BCRYPT), $adresse, $adresse_comp, $cp, $ville, $tel, $mail, $type]);

                // Envoi de l'email au client
                sendEmailToClient($mail, $nom, $prenom,$password);

                $message = 'Compte créé avec succès. Un email de vérification a été envoyé au client.';
                $type = 'success';
            }
        } else {
            $message = 'Un compte avec cette adresse e-mail existe déjà.';
            $type = 'danger';
        }
    }
}

// Fonction pour envoyer un email au client avec les informations de connexion
function sendEmailToClient($email, $nom, $prenom, $password) {
    $subject = "=?UTF-8?B?" . base64_encode("Création d'un nouveau compte") . "?=";
    $body = "Bonjour, \n\n";
    $body .= "Votre compte a été créé avec succès. Voici vos informations de connexion :\n\n";
    $body .= "Nom d'utilisateur : " . $email . "\n";
    $body .= "Mot de passe : " . $password . "\n\n";
    $body .= "Vous pouvez vous connecter à votre compte en utilisant le lien suivant : http://localhost/extranetA2MI/connexion/connexion.php\n\n";
    $body .= "Merci de votre confiance.\n";
    $body .= "Cordialement.\n\n";
    $body .= "A2MI";

    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;

        $mail->setFrom(SENDER_EMAIL, SENDER_NAME);
        $mail->addAddress($email, $prenom);   // Destinataire et prénom du client

        $mail->CharSet = 'UTF-8';
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body = $body;

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
    <title>Création de compte</title>
    <!-- Ajout du lien vers Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h2>Création de compte client</h2>
    <form action="create_account.php" method="post" enctype="multipart/form-data">
        <div id="message" class="mt-3"></div>
        <div class="form-group">
            <label for="entreprise">Entreprise </label>
            <input type="text" id="entreprise" name="entreprise" class="form-control">
        </div>
        <div class="form-group">
            <label for="logo">Logo de l'entreprise (image uniquement)</label>
            <input type="file" id="logo" name="logo" class="form-control" accept="image/*">
        </div>

        <div class="form-group">
            <label for="nom">Nom *</label>
            <input type="text" id="nom" name="nom" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="prenom">Prénom *</label>
            <input type="text" id="prenom" name="prenom" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="adresse">Adresse *</label>
            <input type="text" id="adresse" name="adresse" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="adresse_comp">Complément d'adresse </label>
            <input type="text" id="adresse_comp" name="adresse_comp" class="form-control">
        </div>

        <div class="form-group">
            <label for="cp">Code postal *</label>
            <input type="text" id="cp" name="cp" class="form-control" pattern="\d{5}" title="Veuillez entrer un code postal valide à 5 chiffres" required>
        </div>

        <div class="form-group">
            <label for="ville">Ville *</label>
            <input type="text" id="ville" name="ville" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="tel">Téléphone *</label>
            <input type="text" id="tel" name="tel" class="form-control" pattern="[0-9]{10}" required>
        </div>

        <div class="form-group">
            <label for="mail">Email *</label>
            <input type="email" id="mail" name="mail" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="type">Type de compte </label>
            <select id="type" name="type" class="form-control" required>
                <option value="client">Client</option>
                <!-- Si c'est un administrateur qui crée le compte, il peut choisir le type de compte -->
                <?php if($_SESSION['user_type'] === 'admin'): ?>
                    <option value="sousadmin">Sous-administrateur</option>
                <?php endif; ?>
            </select>
        </div>

        <button type="submit" name="submit" class="btn btn-primary">Créer le compte</button>
    </form>
</div>


<!-- Ajout du script Bootstrap (facultatif si vous n'utilisez pas des fonctionnalités JavaScript de Bootstrap) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // Fonction pour afficher un message dans la zone de message
    function showMessage(message, type) {
        var messageDiv = document.getElementById('message');
        messageDiv.innerHTML = '<div class="alert alert-' + type + '">' + message + '</div>';
    }
</script>
<?php
// Appel de la fonction showMessage pour afficher le message PHP
if(isset($message) && isset($type)) {
    echo '<script>showMessage("' . $message . '", "' . $type . '");</script>';
}
?>
</body>
</html>
