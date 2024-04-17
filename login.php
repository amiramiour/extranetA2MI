<?php
require_once 'connexionBD.php';

// Vérification si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $tel = $_POST['tel'];
    $password = $_POST['password'];

    // Requête SQL pour récupérer le mot de passe haché de l'utilisateur à partir du numéro de téléphone
    $sql = "SELECT membre_id, membre_mdp FROM membres WHERE membre_tel = ?";
    
    // Préparation de la requête
    $stmt = $mysqli->prepare($sql);
    
    // Liaison des paramètres
    $stmt->bind_param('s', $tel);
    
    // Exécution de la requête
    $stmt->execute();
    
    // Récupération du résultat
    $result = $stmt->get_result();

    // Vérification si l'utilisateur existe
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['membre_mdp'];
        
        // Vérification du mot de passe haché
        if (password_verify($password, $hashed_password)) {
            // Démarre la session et enregistre les données de l'utilisateur
            session_start();
            $_SESSION['user_id'] = $row['membre_id'];

            // Redirection vers la page d'accueil ou autre page appropriée
            header("Location: accueil.php");
            exit;
        } else {
            // Message d'erreur si le mot de passe est incorrect
            $error_message = "Numéro de téléphone ou mot de passe incorrect.";
        }
    } else {
        // Message d'erreur si l'utilisateur n'existe pas
        $error_message = "Numéro de téléphone ou mot de passe incorrect.";
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
        <label for="tel">Numéro de téléphone:</label><br>
        <input type="tel" id="tel" name="tel" required><br>
        <label for="password">Mot de passe:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Se connecter">
    </form>
</body>
</html>
