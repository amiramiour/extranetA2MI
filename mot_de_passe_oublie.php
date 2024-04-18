<?php
// Inclure le fichier de connexion à la base de données
include('connexionBD.php');
require 'C:\wamp64\www\A2MI2024\extranetA2MI\vendor\autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// Initialiser les variables
$email = "";
$message = "";
$type = "";


// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['validate']) && $_POST['validate'] === 'ok') {
    // Vérifier si l'e-mail a été saisi
    if (isset($_POST['mail']) && !empty($_POST['mail'])) {
        // Récupérer l'adresse e-mail saisie
        $email = $_POST['mail'];

        try {
            // Vérifier si l'adresse e-mail existe dans la base de données
            $db = connexionbdd();
            $query = $db->prepare("SELECT * FROM membres WHERE membre_mail = ?");
            $query->execute([$email]);
            $user = $query->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Générer un nouveau mot de passe aléatoire
                $nouveau_mdp = generateRandomString(10);

                // Mettre à jour le mot de passe dans la base de données
                $hashed_password = password_hash($nouveau_mdp, PASSWORD_DEFAULT);
                $update_query = $db->prepare("UPDATE membres SET membre_mdp = ? WHERE membre_mail = ?");
                $update_query->execute([$hashed_password, $email]);

                // Envoyer un e-mail avec le nouveau mot de passe
                sendNewPasswordEmail($email, $nouveau_mdp);

                $message = "Un nouveau mot de passe a été envoyé à votre adresse e-mail.";
                $type = "success";
            } else {
                $message = "Aucun compte associé à cette adresse e-mail.";
                $type = "danger";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        $message = "Veuillez entrer votre adresse e-mail.";
        $type = "danger";
    }
}

// Fonction pour envoyer un e-mail avec le nouveau mot de passe
function sendNewPasswordEmail($email, $password) {

    // Utilisation de la classe PHPMailer


    try {
        $mail = new PHPMailer(true);

        // Paramètres du serveur SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'masdouarania02@gmail.com';  // Adresse email de l'expéditeur
        $mail->Password = 'wmeffiafffoqvkvl';           // Mot de passe de l'expéditeur
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Destinataire
        $mail->setFrom('masdouarania02@gmail.com', 'Masdoua');
        $mail->addAddress($email);   // Adresse e-mail du destinataire

        // Contenu de l'e-mail
        $mail->isHTML(true);
        $mail->Subject = 'Réinitialisation de votre mot de passe';
        $mail->Body = 'Bonjour,<br><br>Votre mot de passe a été réinitialisé avec succès. Voici votre nouveau mot de passe : <strong>' . $password . '</strong><br><br>Cordialement,<br>Votre Nom';

        // Envoi de l'e-mail
        $mail->send();

        return true; // L'e-mail a été envoyé avec succès
    } catch (Exception $e) {
        return false; // Une erreur s'est produite lors de l'envoi de l'e-mail
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
    <title>Mot de passe oublié</title>
    <!-- Link Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">Mot de passe oublié</h2>
                    <?php if (!empty($message)): ?>
                        <div class="alert alert-<?php echo $type; ?>" role="alert">
                            <?php echo $message; ?>
                        </div>
                    <?php endif; ?>
                    <form method="post" action="mot_de_passe_oublie.php">
                        <div class="form-group">
                            <label for="mail">E-MAIL :</label>
                            <input type="email" class="form-control" id="mail" name="mail" value="<?php echo $email; ?>" required>
                        </div>
                        <input type="hidden" name="validate" value="ok">
                        <button type="submit" class="btn btn-primary">Valider</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Link Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
