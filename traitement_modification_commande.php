<?php 
include 'ConnexionBD.php';

$idCommande = $_GET['idcommande'];
$idClient = $_GET['idclient'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomC = $_POST['nomC'];
    $designation = $_POST['designationC'];
    $dateP = strtotime($_POST['dateP']);
    $dateS = strtotime($_POST['dateS']);
    $etatC = $_POST['etatC']; 
    $totalHT = $_POST['totalHT'];
    $totalTTC = $_POST['totalTTC'];
    $totalMarge = $_POST['totalMarge'];

    $dateActuelle = time();

    $stmt = $pdo->prepare("UPDATE commande 
                           SET cmd_reference = :nomC, 
                               cmd_designation = :designation, 
                               cmd_datein = :dateActuelle, 
                               cmd_dateout = :dateP, 
                               membre_id = :idClient, 
                               cmd_prixventettc = :totalTTC, 
                               cmd_dateSouhait = :dateS, 
                               cmd_etat = :etatC, 
                               cmd_prixHT = :totalHT, 
                               cmd_margeT = :totalMarge 
                           WHERE cmd_id = :idCommande");

    $stmt->bindValue(':nomC', $nomC);
    $stmt->bindValue(':designation', $designation);
    $stmt->bindValue(':dateActuelle', $dateActuelle);
    $stmt->bindValue(':dateP', $dateP);
    $stmt->bindValue(':idClient', $idClient);
    $stmt->bindValue(':totalTTC', $totalTTC);
    $stmt->bindValue(':dateS', $dateS);
    $stmt->bindValue(':etatC', $etatC);
    $stmt->bindValue(':totalHT', $totalHT);
    $stmt->bindValue(':totalMarge', $totalMarge);
    $stmt->bindValue(':idCommande', $idCommande);

    $stmt->execute();

    //récupérer les produits de la commande à partir du formulaire, si le produit existe déjà : il sera mis à jour, sinon il sera ajouté
    foreach ($_POST['dynamic'] as $produit) {
        $reference = $produit[0];
        //echo($reference); 
        $designation = $produit[1];
        $fournisseur = $produit[2];
        $paHT = $produit[3];

        $marge = $produit[4];
        $pvHT = $produit[5];

        $pvTTC = $produit[6];
        $etatProduit = $produit[8];

         //on récupère l'id du fournisseur
         $stmt = $pdo->prepare("SELECT DISTINCT idFournisseur FROM fournisseur WHERE nomFournisseur = :fournisseur");
         $stmt->bindValue(':fournisseur', $fournisseur);
         $stmt->execute();
         $idFournisseur = $stmt->fetchColumn();


        $stmt = $pdo->prepare("SELECT * FROM commande_produit WHERE reference = :reference AND id_commande = :idCommande");
        $stmt->bindValue(':reference', $reference);
        $stmt->bindValue(':idCommande', $idCommande);
        $stmt->execute();
        $produitExiste = $stmt->fetch();

        if ($produitExiste) {
            echo "produit existe";
            $stmt = $pdo->prepare("UPDATE commande_produit 
                                   SET reference = :reference, 
                                       designation = :designation, 
                                       paHT = :paHT, 
                                       marge = :marge, 
                                       pvHT = :pvHT, 
                                       pvTTC = :pvTTC, 
                                       etat = :etatProduit, 
                                       id_commande = :idCommande,
                                        fournisseur = :fournisseur
                                   WHERE reference = :reference AND id_commande = :idCommande");

            $stmt->bindValue(':reference', $reference);
            $stmt->bindValue(':designation', $designation);
            $stmt->bindValue(':paHT', $paHT);
            $stmt->bindValue(':marge', $marge);
            $stmt->bindValue(':pvHT', $pvHT);
            $stmt->bindValue(':pvTTC', $pvTTC);
            $stmt->bindValue(':etatProduit', $etatProduit);
            $stmt->bindValue(':idCommande', $idCommande);
            $stmt->bindValue(':fournisseur', $idFournisseur);

            $stmt->execute();
        } else {
            $stmt = $pdo->prepare("INSERT INTO commande_produit (reference, designation, paHT, marge, pvHT, pvTTC, etat, id_commande, fournisseur) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$reference, $designation, $paHT, $marge, $pvHT, $pvTTC, $etatProduit, $idCommande, $idFournisseur]);
        }
    }

    header("Location: commandes_client.php?id=$idClient");
}
?>
