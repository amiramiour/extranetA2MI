<?php
include 'connexion.php';

/******************************************************* CONNEXION *************************************************************/

//on teste si l'utilisateur a envoyé le formulaire et si les valeurs sont cohérentes
if(isset($_POST['password']) AND !empty($_POST['password']) AND isset($_POST['mail']) AND !empty($_POST['mail'])){

    // on récupère le mot de passe correspondant à l'email de connexion entré par l'utilisateur
    $req = $pdo->prepare('SELECT * FROM users WHERE users_mail = :mail');
    $req-> bindValue(':mail', trim($_POST['mail']), PDO::PARAM_STR);
    $req-> execute();
    $user = $req->fetch(PDO::FETCH_OBJ);

    //on compare le mot de passe envoyé avec celui stocké en bdd avec password_verify()
    if(password_verify(trim($_POST['password']), $user->users_password)) {
        //si c'est bon, on crée la session pour cet utilisateur :
        $_SESSION['id']             = $user->users_id;
        $_SESSION['mail']           = $user->users_mail;
        $_SESSION['role']           = $user->users_role;
        $_SESSION['name']           = $user->users_name;
        $_SESSION['firstname']      = $user->users_firstname;
        $_SESSION['phone']          = $user->users_phone;
        $_SESSION['address']        = $user->users_address;
        $_SESSION['address_compl']  = $user->users_address_compl;
        $_SESSION['postcode']       = $user->users_postcode;
        $_SESSION['city']           = $user->users_city;
        $_SESSION['mobile']         = $user->users_mobile;
        $_SESSION['enterprise']     = $user->users_enterprise;
        $_SESSION['inscription']    = $user->users_inscription;
        $_SESSION['tech_id']        = $user->users_tech_id;

        //stockage de l'ip de l'utilisateur qui vient de se connecter
        $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
        //en local, l'ip affichée est ::1

    }
}

if(isset($_GET['deconnexion'])){
    //si l'utilisateur demande à se déconnecter
    session_destroy();
    header('location:login.php');
}

//On créé la clé loged_in si elle n'existe pas.
//La clé loged_in sert à savoir si un utilisateur est connecté ou non.
if( !isset($_SESSION["loged_in"]) ){
    $_SESSION["loged_in"] = false;  //valeur par défaut: l'utilisateur n'est pas connecté
}

//On créé la clé panier si elle n'existe pas.
//La clé panier est un array qui contiendra les produits ajoutés au panier.
if(!isset($_SESSION["panier"])){
    $_SESSION["panier"] = array();
}

//On créé la clé prix_total si elle n'existe pas.
//La clé prix_total contient le prix total TTC du panier.
if(!isset($_SESSION["prix_total"])){
    $_SESSION["prix_total"] = 0;
}

?>