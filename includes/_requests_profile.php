<?php
// Sélection des informations de tous les utilisateurs présents dans la DB
$req = $pdo->prepare('SELECT * FROM users WHERE users_id = :id');
$req->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
$req-> execute();
$users = $req->fetch(PDO::FETCH_OBJ);


$id             = $users->users_id;
$mail           = $users->users_mail;
$role           = $users->users_role;
$name           = $users->users_name;
$firstname      = $users->users_firstname;
$phone          = wordwrap($users->users_phone ?? '', 2,' ', 1);
$address        = $users->users_address;
$addressCompl   = $users->users_address_compl;
$postcode       = $users->users_postcode;
$city           = $users->users_city;
$mobile         = wordwrap($users->users_mobile ?? '', 2,' ', 1);
$password       = $users->users_password;
$inscription    = mepd($users->users_inscription);
$enterprise     = $users->users_enterprise;
$role           = ucfirst($users->users_role ?? '');
$techId         = $users->users_tech_id;


// Sélection des informations des techniciens en charge de chaque client
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
$tech = $req->fetch(PDO::FETCH_OBJ);

$techMail           = $tech->users_mail;
$techRole           = $tech->users_role;
$techName           = $tech->users_name;
$techFirstname      = $tech->users_firstname;
$techMobile         = wordwrap($tech->users_mobile ?? '', 2,' ', 1);
$techPhone          = wordwrap($tech->users_phone ?? '', 2,' ', 1);


?>