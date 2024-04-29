<?php
session_start();

// Vérifier si l'utilisateur est connecté et est un technicien
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_mail'])  || $_SESSION['user_type'] === 'client') {
    // Si l'utilisateur n'est pas connecté ou est un client, redirigez-le ou affichez un message d'erreur
    header("Location: ../connexion.php");
    exit;
}

include '../ConnexionBD.php';
$pdo = connexionbdd();

//requete pour récupérer les fournisseurs
$req = $pdo->query("SELECT * FROM fournisseur");
$fournisseurs = $req->fetchAll(PDO::FETCH_ASSOC);

$idCommande = $_GET['id'];

$req = $pdo->query("SELECT * FROM commande_etats");
$commande_etats = $req->fetchAll(PDO::FETCH_ASSOC);


//requete pour récupérer les informations de la commande
$query = $pdo->prepare("SELECT c.cmd_id, c.cmd_reference, c.cmd_designation, 
                        c.cmd_dateout, c.cmd_prixventettc, c.membre_id,
                        c.cmd_dateSouhait, e.commande_etat, c.cmd_etat, 
                        c.cmd_prixHT, c.cmd_margeT
                        FROM commande c
                        JOIN commande_etats e ON c.cmd_etat = e.id_etat_cmd
                        WHERE c.cmd_id = :id");
$query->execute(array(":id" => $idCommande));
$commande = $query->fetch(PDO::FETCH_ASSOC);
//var_dump($commande);


//requete pour récupérer les informations des produits de la commande
$query2 = $pdo->prepare("SELECT cp.reference, cp.designation, cp.fournisseur, 
                        cp.paHT, cp.marge, cp.pvHT, cp.pvTTC, cp.etat, f.nomFournisseur
                        FROM commande_produit cp
                        JOIN fournisseur f ON cp.fournisseur = f.idFournisseur
                        WHERE cp.id_commande = :id");
$query2->execute(array(":id"=> $idCommande));
$produits = $query2->fetchAll(PDO::FETCH_ASSOC);
//var_dump($produits);
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Commandes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Modifier une commande</h2>
        <form action="traitement_modification_commande.php?idcommande=<?php echo $idCommande ?>&idclient=<?php echo $commande['membre_id']?>" method="post">
        <fieldset><legend>Produit <small></small></legend>

        <div id="materiels">
            <?php 
            $i=0;
            foreach ($produits as $unproduit) { $i++; ?>
                <div class="materiel">
                    <label><b>Produit n°<?php echo $i ?></b></label><br>

                    <label for="reference">Référence </label> 
                    <input type="text" name="dynamic['<?php echo($i) ?>'][]" id="reference" value="<?php echo $unproduit['reference'] ?>" readonly>&nbsp;&nbsp;|&nbsp;&nbsp;        
                    
                    <label for="designation">Désignation </label>
                    <input type="text" name="dynamic['<?php echo($i) ?>'][]" id="designation" value="<?php echo $unproduit['designation'] ?>"readonly>&nbsp;&nbsp;|&nbsp;&nbsp;
                    
                    <label for="fournisseur">Fournisseur </label>
                    <input type="text" name="dynamic['<?php echo($i) ?>'][]" id="fournisseur" value="<?php echo $unproduit['nomFournisseur'] ?>"readonly>
                    
                    <br><br>
                    
                    <label >Pa HT </label>
                    <input type="text" name="dynamic['<?php echo($i) ?>'][]" SIZE="2" id="paHT<?php echo($i) ?>" value="<?php echo $unproduit['paHT'] ?>" readonly> € &nbsp;&nbsp;|&nbsp;&nbsp;
                        
                    <label>Marge </label>
                    <input type="text" SIZE="2" name="dynamic['<?php echo($i) ?>'][]" id="marge<?php echo($i) ?>" value="<?php echo $unproduit['marge'] ?>" readonly> % &nbsp;&nbsp;|&nbsp;&nbsp;
                    
                    <label>Pv HT </label>
                    <input type="text" SIZE="2"  name="dynamic['<?php echo($i) ?>'][]" id="pvHT<?php echo($i) ?>" value="<?php echo $unproduit['pvHT'] ?>" readonly> € &nbsp;&nbsp;|&nbsp;&nbsp;
                        
                    <label>Pv TTC </label>
                    <input type="text" SIZE="2" name="dynamic['<?php echo($i) ?>'][]" id="pvTTC<?php echo($i) ?>" value="<?php echo $unproduit['pvTTC'] ?>" readonly> € &nbsp;&nbsp;|&nbsp;&nbsp;
                    
                    <label>Montant marge  </label>
                    <input type="text"  name="dynamic['<?php echo($i) ?>'][]" SIZE="2" id="margeM<?php echo $i ?>" value="<?php echo $unproduit['pvHT']-$unproduit['paHT'] ?>"  readonly> € <br />

                    <label>Statut produit</label> 
                    <select name="dynamic['<?php echo($i) ?>'][]" required>
                        <option value="commande">Commandé</option>
                        <option value="recu">Reçu</option>
                        <option value="livre">
                    </select>
                </div>
                
             <?php } ?>
        </div>
        <button type="button" onclick="supprimerProduit()">Supprimer</button>
        <br><br>
        <button type="button" onclick="ajouterProduit()">Ajouter un nouveau produit</button>
        </fieldset>
            <br>
            <label for="reference" class="float" >Nom de la commande </label>
                <input type="text" name="nomC" id="reference" value="<?php echo $commande['cmd_reference']?>" readonly/><br>
            <label for="designation" class="float">Désignation</label>
                <input type="text" name="designationC" id="designation" value="<?php echo $commande['cmd_designation']?>" readonly><br>

            <label class="float">Date de livraison prévue</label>
                <input type="date" name="dateP" value="<?php echo date('Y-m-d', $commande['cmd_dateout']) ?>"><br>
            
            <label class="float">Date de livraison souhaitée</label>
            <input type="date" name="dateS" value="<?php echo date('Y-m-d', $commande['cmd_dateSouhait']) ?>" required autofocus readonly><br>

            <label for="etat" class="float" >Statut commande </label>
            <select name="etatC" required>
                <?php foreach ($commande_etats as $etat) { ?>
                    <option value="<?php echo $etat['id_etat_cmd']; ?>"><?php echo $etat['commande_etat']; ?></option>
                <?php } ?>
            </select>

            <br><br>
            <label for="reference" class="float">Total HT </label>
            <input type="text" name="totalHT" id="pvHTT" value="<?php echo $commande['cmd_prixHT'] ?>" readonly> €<br>
            
            <label for="reference" class="float">Total TTC </label>
            <input type="text" name="totalTTC" id="pvTTCT" value="<?php echo $commande['cmd_prixventettc'] ?>" readonly> €<br>
            
            <label for="reference" class="float">Total marge </label>
            <input type="text" name="totalMarge" id="margeT" value="<?php echo $commande['cmd_margeT']?>" readonly>  €  <br>
    
            <div class="center"><input class="createButton" type="submit" value="Modifier" /></div>
            <br>
            <div class="center"><input class="createButton" type="button" value="Annuler" onclick="window.history.back()"/>       
        </div>
    </form>
<script>
    function ajouterProduit(){
            var i = document.querySelectorAll('.materiel').length;
            i++;
            console.log("i = "+i);

            var html = '<div class="materiel"><hr>';
            html += '<br><b> Produit n°' + i + '</b><br>';
           
            html += '<label for="reference">Référence* </label> ';
            html += '<input type="text" name="dynamic[' + i + '][]" id="reference' + i + '" required autofocus/>&nbsp;&nbsp;|&nbsp;&nbsp;';
            html += '<label for="designation">Désignation* </label>';
            html += '<input type="text" name="dynamic[' + i + '][]" id="designation' + i + '" required>&nbsp;&nbsp;|&nbsp;&nbsp;';

            html += '<label for="fournisseur">Fournisseur* </label>';
            html += '<select name="dynamic[' + i + '][]" required>';
            <?php
            foreach ($fournisseurs as $fournisseur) { ?>
                html += '<option value="<?php echo $fournisseur['nomFournisseur']; ?>"><?php echo $fournisseur['nomFournisseur']; ?></option>';
            <?php } ?>
            html += '</select>';
            html += '<br><br>';

            html += '<label >Pa HT* </label>';
            html += '<input type="text" SIZE="2" name="dynamic[' + i + '][]" id="paHT' + i + '" onblur="calculerenplus(' + i + ')"  required autofocus> € &nbsp;&nbsp;|&nbsp;&nbsp;';
            html += '<label>Marge* </label>';
            html += '<input type="text" SIZE="2" name="dynamic[' + i + '][]" id="marge' + i + '" onblur="calculerenplus(' + i + ')"  required autofocus> % &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;';
            html += '<label>Pv HT* </label>';
            html += '<input type="text"  name="dynamic[' + i + '][]" id="pvHT' + i + '" SIZE="2" required autofocus readonly> € &nbsp;&nbsp;|&nbsp;&nbsp;';
            html += '<label>Pv TTC* </label>';
            html += '<input type="text" name="dynamic[' + i + '][]" id="pvTTC' + i + '" SIZE="2" required autofocus readonly> € &nbsp;&nbsp;|&nbsp;&nbsp;';
            html += '<label>Montant marge </label>';
            html += '<input type="text" name="dynamic[' + i + '][]" id="margeM' + i + '" SIZE="2" readonly> € <br />';
            html += '<div id="choixArrondi' + i + '"></div>';
            html += '<label for="etat" class="float">Statut produit* </label><select name="dynamic[' + i + '][]" required><option value="commande">Commandé</option><option value="recu">Reçu</option>';
            html += '</select><br>';
            html += '</div>';
        
            document.getElementById('materiels').insertAdjacentHTML('beforeend', html);

            
        }
        function calculerenplus(i) {
            var paHTs = "paHT" + i;
            var marges = "marge" + i;
            var pvHTs = "pvHT" + i;
            var pvTTCs = "pvTTC" + i;
            var margeM = "margeM" + i;
            var multiplier = 100;

            var paHT = Number(document.getElementById(paHTs).value);
            var marge = Number(document.getElementById(marges).value);

            // Validation des prix avec deux chiffres après la virgule ou le point
            if (!/^(\d+(,\d{1,2})?|\d+(\.\d{1,2})?)$/.test(paHT) || !/^(\d+(,\d{1,2})?|\d+(\.\d{1,2})?)$/.test(marge)) {
                alert("Les prix doivent être des nombres avec au plus deux chiffres après le point, sans caractères spéciaux.");
                return;
            }

            // Empêcher les nombres négatifs
            if (parseFloat(paHT) < 0 || parseFloat(marge) < 0) {
                alert("Les prix ne peuvent pas être négatifs.");
                return;
            }

            // Empêcher les marges supérieures à 100%
            if (parseFloat(marge) > 100) {
                alert("La marge ne peut pas être supérieure à 100%.");
                return;
            }

            var pvHT = Number(paHT + (paHT * (marge / 100)));
            pvHT = Math.round(pvHT * multiplier) / multiplier;
            document.getElementById(pvHTs).value = pvHT;

            var pvTTC = Number(pvHT + (pvHT * (20 / 100)));
            pvTTC = Math.round(pvTTC * multiplier) / multiplier;
            document.getElementById(pvTTCs).value = pvTTC;

            var margeMt = Number(pvHT - paHT);
            margeMt = Math.round(margeMt * multiplier) / multiplier;
            document.getElementById(margeM).value = margeMt;

            if (pvHT > 10) {
                var arrondiBas = 10 * Math.floor(pvHT / 10);
            } else {
                var arrondiBas = Math.floor(pvHT);
            }
            var html = '<div id="choixArrondi' + i + '">Choisir l\'arrondi du PvHT ';
            html += '<select name="arrondi" onchange="choixArrondiPlus(' + i + ')" id="choixArrondiSelect' + i + '" required>';
            html += '<option value="' + pvHT + '">' + pvHT + '</option>';
            html += '<option value="' + (arrondiBas + 5) + '">' + (arrondiBas + 5) + '</option>';
            html += '<option value="' + (arrondiBas + 10) + '">' + (arrondiBas + 10) + '</option>';
            html += '<option value="' + (arrondiBas + 15) + '">' + (arrondiBas + 15) + '</option>';
            html += '<option value="' + (arrondiBas + 20) + '">' + (arrondiBas + 20) + '</option>';
            html += '</select> €</div>';
            document.getElementById('choixArrondi' + i).innerHTML = html;
            recalculerTotaux(i);

        }

        function choixArrondiPlus(i){
            var multiplier = 100;
            var choix = document.getElementById("choixArrondiSelect" + i).value;
            choix = Number(choix);
            document.getElementById("pvHT" + i).value = choix;
            
            var pvTTC = Number(choix + (choix * (20 / 100)));
            pvTTC = Number(pvTTC);
            document.getElementById("pvTTC" + i).value = pvTTC;

            var paHT = document.getElementById("paHT" + i).value;

            var margeM = Number(choix - paHT);
            document.getElementById("margeM" + i).value = margeM;

            recalculerTotaux(i);
        }

        // Fonction pour recalculer les totaux après suppression d'un produit
        function recalculerTotaux(i) {
            
            var multiplier = 100;
            var totalMarge = 0;
            var totalHT = 0;
            var totalTTC = 0;
    
            for (j = 1; j <= i; j++) {
                //CALCULE TOTAL DE LA MARGE
                console.log("je suis dans la boucle");
                var margeTotal = "margeM" + j;
                var sommeHT = document.getElementById(margeTotal);
                if (sommeHT && !isNaN(parseFloat(sommeHT.value))) {
                    totalMarge += parseFloat(sommeHT.value);
                }

                //CALCULE TOTAL DE LA HT
                var horstaxeTotal = "pvHT" + j;
                var horstaxeSomme = document.getElementById(horstaxeTotal);
                if (horstaxeSomme && !isNaN(parseFloat(horstaxeSomme.value))) {
                    totalHT += parseFloat(horstaxeSomme.value);
                }

                //CALCULE TOTAL DE LA TTC
                var ttcTotal = "pvTTC" + j;
                var ttcSomme = document.getElementById(ttcTotal);
                if (ttcSomme && !isNaN(parseFloat(ttcSomme.value))) {
                    totalTTC += parseFloat(ttcSomme.value);
                }
                //simplification au centième près
                totalMarge = Math.round(totalMarge * multiplier) / multiplier;
                totalHT = Math.round(totalHT * multiplier) / multiplier;
                totalTTC = Math.round(totalTTC * multiplier) / multiplier;
            }

            console.log("totalMarge = "+totalMarge);
            console.log("totalHT = "+totalHT);
            console.log("totalTTC = "+totalTTC);

            document.getElementById("margeT").value = totalMarge;
            document.getElementById("pvHTT").value = totalHT;
            document.getElementById("pvTTCT").value = totalTTC;
            console.log("La fonction recalculerTotaux a fini d'être exécutée."); 
        }

        //fonction qui supprime le dernier produit
        function supprimerProduit(){
            var materiels = document.querySelectorAll('.materiel');
            if (materiels.length > 1) {
                materiels[materiels.length - 1].remove();
                recalculerTotaux(materiels.length - 1);
            }else{
                alert("Vous ne pouvez pas supprimer le dernier produit.");
            }
        }
</script>
</body>
</html>