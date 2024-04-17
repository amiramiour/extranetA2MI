<?php
session_start();

// Vérifier si l'utilisateur est déjà connecté
if (isset($_SESSION['membre_id']) && isset($_SESSION['membre_mail'])) {
    echo "Connexion réussie en tant que " . $_SESSION['membre_mail'];
    echo "<br><a href='deconnexion.php'>Se déconnecter</a>";
    exit();
}

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['validate']) && $_POST['validate'] === 'ok') {
    // Inclure le fichier de connexion à la base de données
    include('connexionBD.php');

    try {
        $db = connexionbdd();

        $query = $db->prepare("SELECT COUNT(membre_id) AS nbr, membre_id, membre_mail, membre_mdp, membre_type FROM membres WHERE membre_mail = ?");
        $query->execute(array($_POST['mail']));
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result['nbr']) {
            if (password_verify($_POST['mdp'], $result['membre_mdp'])) {
                $_SESSION['membre_id'] = $result['membre_id'];
                $_SESSION['membre_mail'] = $result['membre_mail'];
                $_SESSION['membre_mdp'] = $result['membre_mdp'];
                $_SESSION['membre_type'] = $result['membre_type'];

                // Rediriger l'utilisateur vers la page d'accueil après la connexion réussie
                header('Location: connexion.php');
                exit();
            } else {
                $error_message = "Mot de passe incorrect.";
            }
        } else {
            $error_message = "Adresse email inconnue.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <!-- Link Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">Formulaire de connexion</h2>
                    <form method="post" action="connexion.php">
                        <div class="form-group">
                            <label for="mail">E-MAIL :</label>
                            <input type="email" class="form-control" id="mail" name="mail" required>
                        </div>
                        <div class="form-group">
                            <label for="mdp">Passe :</label>
                            <input type="password" class="form-control" id="mdp" name="mdp" required>
                        </div>
                        <input type="hidden" name="validate" value="ok">
                        <button type="submit" class="btn btn-primary">Connexion</button>
                    </form>
                    <?php
                    // Afficher un message d'erreur s'il y a lieu
                    if (isset($error_message)) {
                        echo "<p class='mt-3 text-danger'>$error_message</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Link Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

