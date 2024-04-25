<?php
include 'ConnexionBD.php';

//var_dump($_POST['client_id']);

if (isset($_GET['id'])) {
    $id_client = $_GET['id'];
}
else  {
    $id_client = $_POST['client_id'];
}

    

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nomC = $_POST['nomC'];
    $dateP = strtotime($_POST['dateP']);
    $dateS = strtotime($_POST['dateS']);
    $etatC = $_POST['etatC'];
    $totalHT = $_POST['totalHT'];
    $totalTTC = $_POST['totalTTC'];
    $totalMarge = $_POST['totalMarge'];
    $designation = $_POST['designation'];

    
    $dateActuelle = time();


    $stmt = $pdo->prepare("SELECT id_etat_cmd FROM commande_etats WHERE commande_etat = ?");
    $stmt->execute([$etatC]);
    $etat = $stmt->fetch(PDO::FETCH_ASSOC);
    $etat_cmd = $etat['id_etat_cmd'];

    
    $stmt = $pdo->prepare("INSERT INTO commande (cmd_reference, cmd_designation, cmd_datein, cmd_dateout, membre_id, cmd_prixventettc, cmd_dateSouhait, cmd_etat, cmd_prixHT, cmd_margeT) VALUES (?, ?, ?, ?, ?, ?, ?,?, ?, ?)");
    $stmt->execute([$nomC, $designation, $dateActuelle, $dateP, $id_client, $totalTTC, $dateS, $etat_cmd, $totalHT, $totalMarge]);
    
    // Récupérer l'ID de la commande nouvellement insérée
    $id_commande = $pdo->lastInsertId();

    foreach ($_POST['dynamic'] as $produit) {
        $reference = $produit[0];
        $designation = $produit[1];
        $fournisseur = $produit[2];
        $paHT = $produit[3];
        $marge = $produit[4];
        $pvHT = $produit[5];
        $pvTTC = $produit[6];
        $etatProduit = $produit[8];
        

        $stmt = $pdo->prepare("INSERT INTO commande_produit (reference, designation, paHT, marge, pvHT, pvTTC, etat, id_commande, fournisseur) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$reference, $designation, $paHT, $marge, $pvHT, $pvTTC, $etatProduit, $id_commande, $fournisseur]);
    }

    
    header("Location: commandes.php");
    
    //echo "Commande ajoutée avec succès";
}
?>
