<?php
require_once 'connexionBD.php';

// Vérification si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $login = $_POST['login'];
    $password = $_POST['password'];

    // Requête SQL pour récupérer les informations de l'utilisateur à partir du login
    $sql = "SELECT * FROM membres WHERE membre_mail = ?";
    
    // Préparation de la requête
    $stmt = $mysqli->prepare($sql);
    
    // Liaison des paramètres
    $stmt->bind_param('s', $login);
    
    // Exécution de la requête
    $stmt->execute();
    
    // Récupération du résultat
    $result = $stmt->get_result();

    // Vérification si l'utilisateur existe et si le mot de passe est correct
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['membre_mdp'])) {
            // Démarre la session et enregistre les données de l'utilisateur
            session_start();
            $_SESSION['user_id'] = $user['membre_id'];
            $_SESSION['user_nom'] = $user['membre_nom'];
            $_SESSION['user_prenom'] = $user['membre_prenom'];
            $_SESSION['user_type'] = $user['membre_type'];

            // Redirection vers la page d'accueil ou autre page appropriée
            header("Location: accueil.php");
            exit;
        } else {
            // Message d'erreur si les informations de connexion sont incorrectes
            $error_message = "Identifiant ou mot de passe incorrect.";
        }
    } else {
        // Message d'erreur si l'utilisateur n'existe pas
        $error_message = "Identifiant ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
    <h2>Connexion</h2>
    <?php if(isset($error_message)): ?>
        <p><?php echo $error_message; ?></p>
    <?php endif; ?>
    <form action="" method="post">
        <label for="login">Login:</label><br>
        <input type="text" id="login" name="login" required><br>
        <label for="password">Mot de passe:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Se connecter">
    </form>
</body>
</html>
