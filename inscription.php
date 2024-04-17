<?php
require_once 'connexionBD.php';

// Vérification si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $adresse = $_POST['adresse'];
    $cp = $_POST['cp'];
    $ville = $_POST['ville'];
    $tel = $_POST['tel'];

    // Hashage du mot de passe
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Type de membre (admin)
    $membre_type = 'admin';

    // Timestamp de l'inscription
    $membre_inscription = time();

    // Requête SQL pour insérer le nouveau membre dans la base de données
    $sql = "INSERT INTO membres (membre_nom, membre_prenom, membre_mail, membre_mdp, membre_adresse, membre_cp, membre_ville, membre_tel, membre_inscription, membre_type) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    // Préparation de la requête
    $stmt = $mysqli->prepare($sql);
    
    // Liaison des paramètres et exécution de la requête
    if ($stmt) {
        $stmt->bind_param('ssssssssis', $nom, $prenom, $email, $hashed_password, $adresse, $cp, $ville, $tel, $membre_inscription, $membre_type);
        $stmt->execute();

        // Vérification si l'insertion a réussi
        if ($stmt->affected_rows > 0) {
            // Redirection vers une page de succès ou une autre page appropriée
            header("Location: login.php");
            exit;
        } else {
            // Message d'erreur en cas d'échec de l'inscription
            $error_message = "Une erreur s'est produite lors de l'inscription. Veuillez réessayer.";
        }
    } else {
        // Message d'erreur en cas de préparation de requête échouée
        $error_message = "Une erreur s'est produite lors de la préparation de la requête. Veuillez réessayer.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
    <h2>Inscription Admin</h2>
    <?php if(isset($error_message)): ?>
        <p><?php echo $error_message; ?></p>
    <?php endif; ?>
    <form action="" method="post">
        <label for="nom">Nom:</label><br>
        <input type="text" id="nom" name="nom" required><br>
        <label for="prenom">Prénom:</label><br>
        <input type="text" id="prenom" name="prenom" required><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br>
        <label for="password">Mot de passe:</label><br>
        <input type="password" id="password" name="password" required><br>
        <label for="adresse">Adresse:</label><br>
        <input type="text" id="adresse" name="adresse" required><br>
        <label for="cp">Code Postal:</label><br>
        <input type="text" id="cp" name="cp" required><br>
        <label for="ville">Ville:</label><br>
        <input type="text" id="ville" name="ville" required><br>
        <label for="tel">Téléphone:</label><br>
        <input type="tel" id="tel" name="tel" required><br><br>
        <input type="submit" value="Inscription">
    </form>
</body>
</html>
