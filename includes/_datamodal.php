<?php

require("../../../admin/connexion/traitement_connexion.php");
// Sélection des informations de tous les produits présents dans la boutique
$rowid = $_POST['rowid'];
$req = $pdo->prepare("SELECT * FROM shop_produit AS A
                            INNER JOIN shop_categorie AS B ON B.shop_categorie_id = A.shop_produit_categorie
                            WHERE A.shop_produit_id = '$rowid'");
$req-> execute();
$allproducts = $req->fetch(PDO::FETCH_OBJ);

?>
<div class="row">
    <div class="col-md-2 my-1">
        <div class="form-floating">
            <input disabled type="text" class="form-control fs-15px" id="id"
                   value="<?= $allproducts->shop_produit_id ?>">
            <label for="id" class="d-flex align-items-center fs-13px">ID</label>
        </div>
    </div>
    <div class="col-md-7 my-1">
        <div class="form-floating">
            <input type="email" class="form-control fs-15px" id="nomProduit" value="<?= $allproducts->shop_produit_nom ?>">
            <label for="nomProduit" class="d-flex align-items-center fs-13px">Nom du produit</label>
        </div>
    </div>
    <div class="col-md-3 my-1">
        <div class="form-floating">
            <input type="email" class="form-control fs-15px" id="floatingInputValue" value="<?= $allproducts->shop_produit_quantite ?>">
            <label for="floatingInputValue" class="d-flex align-items-center fs-13px">Quantité</label>
        </div>
    </div>

    <div class="col-md-12 my-1">
        <div class="form-floating">
            <textarea class="form-control fs-15px" rows="6" style="min-height:200px;" id="floatingInputValue"><?= $allproducts->shop_produit_texte ?></textarea>
            <label for="floatingInputValue" class="d-flex fs-13px">Texte</label>
        </div>
    </div>

    <div class="col-md-12 my-1">
        <div class="form-floating">
            <textarea class="form-control fs-15px" rows="6" style="min-height:200px;" id="floatingInputValue"><?= $allproducts->shop_produit_description ?></textarea>
            <label for="floatingInputValue" class="d-flex fs-13px">Détails techniques</label>
        </div>
    </div>

    <div class="col-md-4 my-1">
        <div class="form-floating">
            <input type="email" class="form-control fs-15px" id="floatingInputValue" value="<?= $allproducts->shop_produit_prix ?>">
            <label for="floatingInputValue" class="d-flex align-items-center fs-13px">Prix HT</label>
        </div>
    </div>

    <div class="col-md-4 my-1">
        <div class="form-floating">
            <input type="email" class="form-control fs-15px" id="floatingInputValue" value="<?= $allproducts->shop_produit_tva ?>">
            <label for="floatingInputValue" class="d-flex align-items-center fs-13px">TVA</label>
        </div>
    </div>
    <div class="col-md-4 my-1">
        <div class="form-floating">
            <input disabled type="email" class="form-control fs-15px" id="floatingInputValue"
                   value="<?= round($allproducts->shop_produit_prix*(1+($allproducts->shop_produit_tva/100)), 2) ?>">
            <label for="floatingInputValue" class="d-flex align-items-center fs-13px">Prix TTC</label>
        </div>
    </div>

    <div class="col-md-4 my-1">
        <div class="form-floating">
            <input type="email" class="form-control fs-15px" id="floatingInputValue" value="<?= $allproducts->shop_produit_poids ?>">
            <label for="floatingInputValue" class="d-flex align-items-center fs-13px">Poids</label>
        </div>
    </div>
    <div class="col-md-4 my-1">
        <div class="form-floating">
            <input type="email" class="form-control fs-15px" id="floatingInputValue" value="<?= $allproducts->shop_categorie_nom ?>">
            <label for="floatingInputValue" class="d-flex align-items-center fs-13px">Catégorie</label>
        </div>
    </div>
    <div class="col-md-4 my-1">
        <div class="form-floating">
            <input type="email" class="form-control fs-15px" id="floatingInputValue" value="<?= $allproducts->shop_produit_etat ?>">
            <label for="floatingInputValue" class="d-flex align-items-center fs-13px">Etat</label>
        </div>
    </div>
</div>