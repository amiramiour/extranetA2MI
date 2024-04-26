<?php
/*connexionBD.php*/
function connexionbdd()
{
    try {
        $db = new PDO('mysql:host=127.0.0.1;port=3307;dbname=stage', 'root', '');

        // Configure PDO to throw exceptions on errors
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Set character set to UTF-8
        $db->exec("SET CHARACTER SET utf8");
        return $db; // Retourne la connexion PDO
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        // Arrête l'exécution du script en cas d'erreur
        exit();
    }
}