<?php
//include '../connexion/connexion.php';

// Sélection des fournisseurs d'A2MI
$req = $pdo->prepare('SELECT * FROM fournisseurs');
$req-> execute();
$allsuppliers = $req->fetchAll(PDO::FETCH_OBJ);

$req = $pdo->prepare('SELECT * FROM cmd_devis_etats');
$req->execute();
$etats = $req->fetchAll(PDO::FETCH_OBJ);


// Sélection des informations de tous les utilisateurs présents dans la DB
$req = $pdo->prepare('SELECT * FROM users');
$req-> execute();
$allusers = $req->fetchAll(PDO::FETCH_OBJ);

// Sélection du technicien  en charge du client
$req = $pdo->prepare('SELECT users_id,
                                   users_name, 
                                   users_firstname, 
                                   users_mail, 
                                   users_mobile, 
                                   users_phone, 
                                   users_tech_id, 
                                   users_role 
                            FROM users 
                            WHERE users_tech_id = users_id');
$req-> execute();
$techusers = $req->fetch(PDO::FETCH_OBJ);

$techUsersName           = $techusers->users_name;
$techUsersFirstname      = $techusers->users_firstname;



// Sélection des informations de toutes les catégories de produits (boutique)
/*$req = $pdo->prepare('SELECT * FROM shop_categorie');
$req-> execute();
$allcategories = $req->fetchAll(PDO::FETCH_OBJ);*/

// Sélection des informations de tous les produits présents dans la boutique
/*$req = $pdo->prepare('SELECT * FROM shop_produit AS A
                            INNER JOIN shop_categorie AS B ON B.shop_categorie_id = A.shop_produit_categorie');
$req-> execute();
$allproducts = $req->fetchAll(PDO::FETCH_OBJ);*/



// Sélection des informations de la page Communication
/*$req = $pdo->prepare("SELECT * FROM site_post WHERE site_post_category = 'Com'");
$req-> execute();
$allcommunications = $req->fetchAll(PDO::FETCH_OBJ);*/



// Sélection des informations de la page Formation
/*$req = $pdo->prepare("SELECT * FROM site_img WHERE site_img_category = 'Formation'");
$req-> execute();
$allformations = $req->fetchAll(PDO::FETCH_OBJ);*/



// Sélection des informations de la page Service Informatique
/*$req = $pdo->prepare("SELECT * FROM site_post WHERE site_post_category = 'Info'");
$req-> execute();
$allinfoservices = $req->fetchAll(PDO::FETCH_OBJ);



// Liste des textes et images de la partie gestion administrative
$req = $pdo->prepare("SELECT * FROM site_post WHERE site_post_category = 'GestionText'");
$req-> execute();
$allmanagements = $req->fetchAll(PDO::FETCH_OBJ);

// Liste des prestations de la gestion administrative
$req = $pdo->prepare("SELECT * FROM site_post WHERE site_post_category = 'GestionList'");
$req-> execute();
$managementLists = $req->fetchAll(PDO::FETCH_OBJ);



// Sélection des informations de tous les sites Web
$req = $pdo->prepare("SELECT * FROM site_img WHERE site_img_category = 'Site'");
$req-> execute();
$sitewebs = $req->fetchAll(PDO::FETCH_OBJ);


// Sélection des textes présents pour chaque section du site
$req = $pdo->prepare("SELECT * FROM site_post WHERE site_post_section = 'Intro'");
$req-> execute();
$textes = $req->fetchAll(PDO::FETCH_OBJ);


// Liste des logos des partenaires
$req = $pdo->prepare("SELECT * FROM site_img WHERE site_img_category = 'Logo'");
$req-> execute();
$logos = $req->fetchAll(PDO::FETCH_OBJ);


// Sélection des informations de contact
$req = $pdo->prepare("SELECT * FROM site_contact");
$req-> execute();
$contacts = $req->fetchAll(PDO::FETCH_OBJ);*/


// Sélection de toutes les commandes
/*$req = $pdo->prepare("SELECT A.*, B.*, C.*, COUNT(D.shop_ligne_commande_num_commande) AS CPTE, ANY_VALUE(D.shop_ligne_commande_id), ANY_VALUE(D.shop_ligne_commande_produit_id), SUM(D.shop_ligne_commande_produit_quantite) AS QTE
                            FROM shop_commande AS A
                            INNER JOIN users AS B ON B.users_id = A.shop_commande_user_id
                            INNER JOIN shop_commande_statut AS C ON C.shop_commande_statut_id = A.shop_commande_statut
                            INNER JOIN shop_ligne_commande AS D ON D.shop_ligne_commande_num_commande = A.shop_commande_id
                            GROUP BY D.shop_ligne_commande_num_commande");
$req->execute();
$resumOrders = $req->fetchAll(PDO::FETCH_OBJ);*/

$req = $pdo->prepare("SELECT c.users_id, 
                            m.users_name AS nom_client, 
                            m.users_entreprise,
                            m.users_firstname AS client_prenom, 
                            c.cmd_devis_id, 
                            c.cmd_devis_reference, 
                            c.cmd_devis_designation, 
                            c.cmd_devis_datein, 
                            c.cmd_devis_dateout, 
                            c.cmd_devis_prixventettc,
                            c.cmd_devis_prixHT,
                            l.users_name AS nom_technicien, 
                            c.cmd_devis_dateSouhait, 
                            e.cmd_devis_etat,
                            -- Sous-requête pour compter le nombre de produits par commande
                            (SELECT COUNT(*) FROM cmd_produit WHERE id_commande = c.cmd_devis_id) AS nombre_produits,
                            -- Sous-requête pour calculer la quantité totale de produits demandée
                            (SELECT SUM(quantite_demandee) FROM cmd_produit WHERE id_commande = c.cmd_devis_id) AS quantite_totale_demandee
                            FROM 
                            cmd_devis c 
                            JOIN 
                            users m ON m.users_id = c.users_id 
                            JOIN 
                            users l ON c.cmd_devis_technicien = l.users_id 
                            JOIN 
                            cmd_devis_etats e ON c.cmd_devis_etat = e.id_etat_cmd_devis
                            WHERE 
                            c.type_cmd_devis = '1' 
                            AND c.active_commande = 1
                            ORDER BY 
                            c.cmd_devis_datein DESC;");
$req->execute();
$commandes = $req->fetchAll(PDO::FETCH_ASSOC);

//on récupère les devis
$req = $pdo->prepare("SELECT c.users_id,
              m.users_name AS nom_client,
              m.users_entreprise,
              m.users_firstname AS client_prenom,
              c.cmd_devis_id,
              c.cmd_devis_reference,
              c.cmd_devis_designation,
              c.cmd_devis_datein,
              c.cmd_devis_dateout,
              c.cmd_devis_prixventettc,
              c.cmd_devis_prixHT,
              l.users_name AS nom_technicien,
              c.cmd_devis_dateSouhait,
              e.cmd_devis_etat,
              -- Sous-requête pour compter le nombre de produits par commande
              (SELECT COUNT(*) FROM devis_produit WHERE id_devis = c.cmd_devis_id) AS nombre_produits,
              -- Sous-requête pour calculer la quantité totale de produits demandée
              (SELECT SUM(quantite_demandee) FROM devis_produit WHERE id_devis = c.cmd_devis_id) AS quantite_totale_demandee
              FROM
              cmd_devis c
              JOIN
              users m ON m.users_id = c.users_id
              JOIN
              users l ON c.cmd_devis_technicien = l.users_id
              JOIN
              cmd_devis_etats e ON c.cmd_devis_etat = e.id_etat_cmd_devis
              WHERE
              c.type_cmd_devis = '2'
              AND c.active_commande = 1
              ORDER BY
              c.cmd_devis_datein DESC;");
$req->execute();
$devis = $req->fetchAll(PDO::FETCH_ASSOC);

$req = $pdo->prepare("SELECT e.cmd_devis_etat, 
       c.date_creation, c.date_update, c.commande_id,
       u.users_name AS nom_utilisateur, u.users_firstname AS prenom_utilisateur,
       u.users_entreprise, u.users_id,
       d.cmd_devis_prixventettc, d.cmd_devis_reference
       FROM cmd_etat_info_save c
       JOIN cmd_devis_etats e ON c.sauvgarde_etat = e.id_etat_cmd_devis
       JOIN users t1 ON c.created_by = t1.users_id
       JOIN users t2 ON c.updated_by = t2.users_id
       JOIN cmd_devis d ON c.commande_id = d.cmd_devis_id
       JOIN users u ON d.users_id = u.users_id
       WHERE d.type_cmd_devis = 1
       AND c.date_update >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
       ORDER BY c.date_update DESC");
$req->execute();
$sauvegardes_cmd = $req->fetchAll(PDO::FETCH_ASSOC); 
//var_dump($sauvegardes_cmd)

$req = $pdo->prepare("SELECT e.cmd_devis_etat, 
       c.date_creation, c.date_update, c.commande_id ,
       u.users_name AS nom_utilisateur, u.users_firstname AS prenom_utilisateur,
       u.users_entreprise, u.users_id,
       d.cmd_devis_prixventettc, d.cmd_devis_reference
       FROM cmd_etat_info_save c
       JOIN cmd_devis_etats e ON c.sauvgarde_etat = e.id_etat_cmd_devis
       JOIN users t1 ON c.created_by = t1.users_id
       JOIN users t2 ON c.updated_by = t2.users_id
       JOIN cmd_devis d ON c.commande_id = d.cmd_devis_id
       JOIN users u ON d.users_id = u.users_id
       WHERE d.type_cmd_devis = 2
       AND c.date_update >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
       ORDER BY c.date_update DESC");
$req->execute();
$sauvegardes_devis = $req->fetchAll(PDO::FETCH_ASSOC); 
?>