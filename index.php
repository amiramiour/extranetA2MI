<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3307;dbname=0e5lu_a2mi_extranet', 'root', '');
    // Configure PDO to throw exceptions on errors
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set character set to UTF-8
    $pdo->exec("SET CHARACTER SET utf8");
    echo "Connected successfully to the database!";

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>
