<?php
//require_once('../connexion/connexion.php');

//$idClient = 778;
$idClient = $_GET['id'];

/*$req = $pdo->prepare("SELECT c.cmd_devis_id, 
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
                                    c.cmd_devis_etat, 
                                    c.cmd_devis_prixHT, 
                                    c.cmd_devis_margeT, 
                                    c.type_cmd_devis, 
                                    c.commentaire
                        FROM cmd_devis c
                        JOIN users m ON m.users_id = c.users_id 
                        WHERE c.active_commande = 1
                        AND c.users_id = :id");
$req->execute(array(":id" => $idClient));
$commandes_client = $req->fetchAll(PDO::FETCH_ASSOC);
//var_dump($commandes_client);*/

$req = $pdo->prepare("SELECT s.*, c.*, u.*
    FROM cmd_etat_info_save s
    JOIN (
        SELECT commande_id, MAX(date_update) AS last_update
        FROM cmd_etat_info_save
        GROUP BY commande_id
    ) AS latest ON s.commande_id = latest.commande_id AND s.date_update = latest.last_update
    JOIN cmd_devis c ON s.commande_id = c.cmd_devis_id
    JOIN users u ON u.users_id = c.users_id
    WHERE c.users_id = :id AND c.active_commande = 1 AND c.type_cmd_devis = 1
    ORDER BY s.date_update DESC");
$req->execute(array(":id" => $idClient));
$commandes_client = $req->fetchAll(PDO::FETCH_ASSOC);
//var_dump($commandes);

$req = $pdo->prepare("SELECT s.*, c.*, u.*
    FROM cmd_etat_info_save s
    JOIN (
        SELECT commande_id, MAX(date_update) AS last_update
        FROM cmd_etat_info_save
        GROUP BY commande_id
    ) AS latest ON s.commande_id = latest.commande_id AND s.date_update = latest.last_update
    JOIN cmd_devis c ON s.commande_id = c.cmd_devis_id
    JOIN users u ON u.users_id = c.users_id
    WHERE c.users_id = :id AND c.active_commande = 1 AND c.type_cmd_devis = 2
    ORDER BY s.date_update DESC");
$req->execute(array(":id" => $idClient));
$devis_client = $req->fetchAll(PDO::FETCH_ASSOC);
//var_dump($devis_client);
