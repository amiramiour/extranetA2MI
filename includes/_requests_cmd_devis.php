<?php
/*require_once('../connexion/connexion.php');
$idCommande = 119;*/
$idCommande = $_GET['id'];

// Sélection des fournisseurs d'A2MI
$req = $pdo->prepare('SELECT * FROM fournisseurs');
$req-> execute();
$allsuppliers = $req->fetchAll(PDO::FETCH_OBJ);

$req = $pdo->prepare('SELECT * FROM cmd_devis_etats');
$req->execute();
$etats = $req->fetchAll(PDO::FETCH_OBJ);

//on récupère la commande/devis
$req = $pdo->prepare("SELECT c.cmd_devis_id, 
                                    c.users_id, 
                                    m.users_name AS nom_client, 
                                    m.users_entreprise,
                                    m.users_firstname AS client_prenom,
                                    m.users_address,
                                    m.users_address_compl,
                                    m.users_postcode,
                                    m.users_city,
                                    m.users_mobile,
                                    m.users_phone,
                                    c.cmd_devis_reference, 
                                    c.cmd_devis_designation,
                                    c.cmd_devis_datein,
                                    c.cmd_devis_dateout, 
                                    c.cmd_devis_prixventettc,
                                    c.users_id,
                                    c.cmd_devis_dateSouhait, 
                                    e.cmd_devis_etat,
                                    e.id_etat_cmd_devis,
                                    c.cmd_devis_prixHT, 
                                    c.cmd_devis_margeT, 
                                    c.type_cmd_devis, 
                                    c.commentaire
                            FROM cmd_devis c
                            JOIN cmd_devis_etats e ON c.cmd_devis_etat = e.id_etat_cmd_devis
                            JOIN users m ON m.users_id = c.users_id 
                            WHERE c.cmd_devis_id = :id");
$req->execute(array(":id" => $idCommande));
$commande = $req->fetch(PDO::FETCH_ASSOC);
//var_dump($commande['type_cmd_devis']);

if ($commande['type_cmd_devis'] == 1) {
       //On récupère les informations des produits de la commande
       $req = $pdo->prepare("SELECT cp.reference, cp.designation, cp.fournisseur, 
                               cp.paHT, cp.marge, cp.pvHT, cp.pvTTC, cp.etat, f.fournisseurs_name, 
                               cp.quantite_demandee, cp.quantite_recue
                               FROM cmd_produit cp
                               JOIN fournisseurs f ON cp.fournisseur = f.fournisseurs_id
                               WHERE cp.id_commande = :id");
       $req->execute(array(":id"=> $idCommande));
       $produits = $req->fetchAll(PDO::FETCH_ASSOC);
} else {
    //On récupère les informations des produits du devis 
    $req = $pdo->prepare("SELECT dp.reference, dp.designation, dp.fournisseur, 
                            dp.paHT, dp.marge, dp.pvHT, dp.pvTTC, dp.etat, f.fournisseurs_name, dp.quantite_demandee
                            FROM devis_produit dp
                            JOIN fournisseurs f ON dp.fournisseur = f.fournisseurs_id
                            WHERE dp.id_devis = :id");
    $req->execute(array(":id"=> $idCommande));
    $produits = $req->fetchAll(PDO::FETCH_ASSOC);

    //On récupère les photo du devis 
    $req = $pdo->prepare("SELECT photo FROM devis_photo WHERE id_devis = :id");
    $req->execute(array(":id"=> $idCommande));
    $photos = $req->fetchAll(PDO::FETCH_ASSOC);
}

$req = $pdo->prepare("SELECT e.cmd_devis_etat, 
                    t1.users_name as nom_technicien1 , t1.users_firstname as prenom_technicien1,
                    c.date_creation, 
                    t2.users_name as nom_technicien2 , t2.users_firstname as prenom_technicien2, 
                    c.date_update 
                    FROM cmd_etat_info_save c
                    JOIN cmd_devis_etats e ON c.sauvgarde_etat = e.id_etat_cmd_devis
                    JOIN users t1 ON c.created_by = t1.users_id
                    JOIN users t2 ON c.updated_by = t2.users_id
                    WHERE c.commande_id = :id
                    ORDER BY c.date_update DESC");
$req->execute(array(":id" => $idCommande));
$sauvegardes = $req->fetchAll(PDO::FETCH_ASSOC);
//var_dump($sauvegardes);


