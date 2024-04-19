<?php
    /*Ceci est l'ancien code de la page ajouter_commande.php  avec des version ultérieur de php
    <div id="contenu">
    <h1>Formulaire de création d'une commande</h1>

    <form action="trait-commande.php?id=<?php echo $id; ?>" method="post" name="commande">
        <fieldset><legend>Produit <small>* champs obligatoires</small></legend>

        <div id="materiels">
        <div id="materiel">
        <label><b>1er produit de la commande</b></label><br>
        <label for="reference">Référence* </label>
            <input type="text" name="dynamic['0'][]" id="reference" required autofocus/>&nbsp;&nbsp;|&nbsp;&nbsp;
        <label for="designation">Désignation* </label>
            <input type="text" name="dynamic['0'][]" id="designation" required>&nbsp;&nbsp;|&nbsp;&nbsp;
        <label for="fournisseur">Fournisseur* </label>
        <select name="dynamic['0'][]" required>
            <option value="sans Fournisseur">------</option>
            <?php
            while ($unfournisseur = $fournisseur->fetch(PDO::FETCH_ASSOC)) 
            {
                ?>
            <option value="<?php echo $unfournisseur['nomFournisseur'] ?>"><?php echo $unfournisseur['nomFournisseur'] ?></option>
            <?php
            }
            ?>
        </select>
            <br>
            <br>
        <label >Pa HT* </label>
            <input type="text" SIZE="2" name="dynamic['0'][]" id="paHT" onblur="calcul()"  required autofocus> € &nbsp;&nbsp;|&nbsp;&nbsp;
        <label>Marge* </label>
            <input type="text" SIZE="2" name="dynamic['0'][]" id="marge" onblur="calcul()"  required autofocus> %                                                       &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;
		<label>Pv HT* </label>
            <input type="text"  name="dynamic['0'][]" id="pvHT" SIZE="2" required autofocus readonly> € &nbsp;&nbsp;|&nbsp;&nbsp;
        <label>Pv TTC* </label>
            <input type="text" name="dynamic['0'][]" id="pvTTC" SIZE="2" required autofocus readonly> € &nbsp;&nbsp;|&nbsp;&nbsp;
        <label>Montant marge  </label>
            <input type="text" name="dynamic['0'][]" id="margeM" SIZE="2" readonly> € 
            
            <div id="lesChoix">
                <div id="choixArrondi"></div>
            </div>
            
            <br />
        <label for="etat" class="float">Statut produit* </label>
            <select name="dynamic['0'][]" required>
            <option value="commande">Commandé</option>
            <option value="recu">Reçu</option>
        </select>
            </div>
		</div>
    </fieldset>
           
            <button type="button" class="myButton tabletteButton lsf-icon add" id="add">Ajouter un produit à la commande</button>
            <button type="button" class="myButton tabletteButton lsf-icon remove" id="remove">Enlever un produit à la commande</button><br>
            <br>
            
            <label for="reference" class="float" >Nom de la commande* </label>
            <input type="text" name="nomC" id="reference" required autofocus/><br>
            
            <label class="float">Date de livraison prévue</label><input type="date" name="dateP" required autofocus><br>
            
            <label class="float">Date de livraison souhaitée</label><input type="date" name="dateS" required autofocus><br>
            
            <label for="etat" class="float" >Statut commande* </label>
            
            <select name="statutC" required>
                <option value="commande">Commandé</option>
                <option value="attente">En attente</option>
                <option value="recu">Reçu</option>
                <option value="alivrer">A livrer</option>
                <option value="livrer">Livrer</option>
            </select><br>
            
            <label for="reference" class="float">Total HT </label>
            <input type="text" name="totalHT" id="pHTT" readonly/><br>
            
            <label for="reference" class="float">Total TTC </label>
            <input type="text" name="totalTTC" id="pvTTCT" readonly/><br>
            
            <label for="reference" class="float">Total marge </label>
            <input type="text" name="totalMarge" id="margeT" readonly/><br>
            
        

    <div class="center"><input class="createButton" type="submit" value="Créer" /></div>


        
<script src="<?php echo ROOTPATH; ?>/includes/js/jquery-3.4.1.min.js"></script>
<script>
$(document).ready(function(){
    
    // ajoute une intervention
    i = $('.materiel').length;
    
    
    $('#add').click(function() {
        i++;
        
        $html = '<div class="materiel'+i+'"><hr>';
        $html += '<br><b>'+(i+1)+'e produit de la commande</b><br>';
        $html += '<label for="reference">Référence* </label> ';
        $html += '<input type="text" name="dynamic['+(i+1)+'][]" id="reference'+(i+1)+'" required                                                       autofocus/>&nbsp;&nbsp;|&nbsp;&nbsp;';
        $html += '<label for="designation">Désignation* </label>';
        $html += '<input type="text" name="dynamic['+(i+1)+'][]" id="designation" required>&nbsp;&nbsp;|&nbsp;&nbsp;';

        $html += '<label for="fournisseur">Fournisseur* </label>';
        $.ajax({
                //ON VA CHERCHER LE FICHIER QUI GENERE NOTRE TABLEAU DE DONNEES
                type: 'GET',
                url: 'listeFournisseur.php',
                timeout: 3000,
                //c'est async qui fait que l'on peut ajouter la liste au $html
                async: false,
                success: function(donnees){
                    //on décompresse les données de la page qu'on a mis au dessus
            var messages_js = JSON.parse(donnees);
                    
            var text='<select name="dynamic['+(i+1)+'][]" required><option value="sans Fournisseur">------</option>';
                    for(let j=0;j<messages_js.length;j++){
                    //on fait un for pour récupèré toutes les variables, pl us le nom de l'tribut qu'on souhaite
                    text +='<option value='+messages_js[j]['nomFournisseur']+'>'+messages_js[j]['nomFournisseur']+'</option>';
                        
                    }
                    text +="</select><br><br>";
                    
                    //on ajoute notre texte au produit, sans async=false cela ne marche pas.
                    $html += text;

             },
             error: function(){
                  alert('Erreur !');
             }
            
        });
        
        $html += '<label >Pa HT* </label>';
        $html += '<input type="text" SIZE="2" name="dynamic['+(i+1)+'][]" id="paHTs'+i+'" onblur="calculenplus()"  required autofocus> €                                     &nbsp;&nbsp;|&nbsp;&nbsp;';
        $html += '<label>Marge* </label>';
        $html += '<input type="text" SIZE="2" name="dynamic['+(i+1)+'][]" id="marges'+i+'" onblur="calculenplus()"  required autofocus> %                                   &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;';
        $html += '<label>Pv HT* </label>';
        $html += '<input type="text"  name="dynamic['+(i+1)+'][]" id="pvHTs'+i+'" SIZE="2" required autofocus readonly> € &nbsp;&nbsp;|&nbsp;&nbsp;';
        $html += '<label>Pv TTC* </label>';
        $html += '<input type="text" name="dynamic['+(i+1)+'][]" id="pvTTCs'+i+'" SIZE="2" required autofocus readonly> € &nbsp;&nbsp;|&nbsp;&nbsp;';
        $html += '<label>Montant marge </label>';
        $html += '<input type="text" name="dynamic['+(i+1)+'][]" id="margeM'+i+'" SIZE="2" readonly> € <br />';
        $html += '<label for="etat" class="float">Statut produit* </label><select name="dynamic['+(i+1)+'][]" required><option                                              value="commande">Commandé</option><option value="recu">Reçu</option></fieldset><br>';
        
        $html += '</div>';
        $($html).fadeIn('slow').appendTo('#materiels');
        
    });

    // enleve une intervention sauf si il n'en reste qu'une
    $('#remove').click(function() {
       
            $('.materiel'+i).last().remove();
        if(i>=1){
            i--;
        }
        
        
        //CALCUL DES TOTAUX
        var multiplier = 100;
        var totalMarge = document.getElementById("margeM").value;
                var totalMarge = Math.round(totalMarge * multiplier) / multiplier;  
        
                var totalHT = document.getElementById("pvHT").value;
                var totalHT = Math.round(totalHT * multiplier) / multiplier;
        
                var totalTTC = document.getElementById("pvTTC").value;
                var totalTTC = Math.round(totalTTC * multiplier) / multiplier;
                for (j = 1; j<=i; j++) {
                    
                //CALCULE TOTAL DE LA MARGE
                  var margeTotal="margeM"+j;
                  var sommeHT=document.getElementById(margeTotal).value;
                //simplification au centième près
                 var sommeHT = Math.round(sommeHT * multiplier) / multiplier;  
                 totalMarge+=sommeHT;
                //simplification au centième près
                var totalMarge = Math.round(totalMarge * multiplier) / multiplier;
                    
                //CALCULE TOTAL DE LA HT
                  var horstaxeTotal="pvHTs"+j;
                  var horstaxeSomme=document.getElementById(horstaxeTotal).value;
                //simplification au centième près
                 var horstaxeHT = Math.round(horstaxeSomme * multiplier) / multiplier;  
                 totalHT+=horstaxeHT;
                //simplification au centième près
                var totalHT = Math.round(totalHT * multiplier) / multiplier; 
                
                //CALCULE TOTAL DE LA TTC
                  var ttcTotal="pvTTCs"+j;
                  var ttcSomme=document.getElementById(ttcTotal).value;
                //simplification au centième près
                 var toutetaxeTTC = Math.round(ttcSomme * multiplier) / multiplier;  
                 totalTTC+=toutetaxeTTC;
                //simplification au centième près
                var totalTTC = Math.round(totalTTC * multiplier) / multiplier; 
                }
            
            document.getElementById("margeT").value=totalMarge;
            document.getElementById("pHTT").value=totalHT;
            document.getElementById("pvTTCT").value=totalTTC;
    });
});
    
    var enlever = 0;
       function calcul(){

                var paHT = Number(document.getElementById("paHT").value);
                var marge = Number(document.getElementById("marge").value);
                var multiplier = 100;
 
                var pvHT = Number(paHT+(paHT*(marge/100)));
                pvTTC = Math.round(pvTTC * multiplier) / multiplier;
                document.getElementById("pvHT").value = pvHT;
           
                var pvTTC = Number(pvHT+(pvHT*(20/100)));
                pvTTC = Math.round(pvTTC * multiplier) / multiplier;
                document.getElementById("pvTTC").value = pvTTC;
            
                var margeM = Number(pvHT-paHT);
                margeM = Math.round(margeM * multiplier) / multiplier;
                document.getElementById("margeM").value = margeM;  
           
                var totalMarge = margeM;
                var totalHT = pvHT;
                var totalTTC = pvTTC;
           
            //simplification au centième près
                
                var totalMarge = Math.round(totalMarge * multiplier) / multiplier;
           
            //simplification au centième près
                var totalHT = Math.round(totalHT * multiplier) / multiplier;
           
            //simplification au centième près
                var totalTTC = Math.round(totalTTC * multiplier) / multiplier;
           
           
                document.getElementById("margeT").value=totalMarge;
                document.getElementById("pHTT").value=totalHT;
                document.getElementById("pvTTCT").value=totalTTC;
           
           if(pvHT>10) {
            var arrondiBas = 10*Math.floor(pvHT/10);
                       }
           else {
               var arrondiBas = Math.floor(pvHT);
               }
            $html = '<div id="choixArrondi">Choisir l\'arrondi du PvHT ';
            $html += '<select name="arrondi" onchange="choixArrondi()" id="choixArrondiSelect" required><option value="15">'+pvHT+'</option>';
            $html += '<option value="'+arrondiBas+'">'+arrondiBas+'</option>';
            $html += '<option value="'+(arrondiBas+5)+'">'+(arrondiBas+5)+'</option>';
            $html += '<option value="'+(arrondiBas+10)+'">'+(arrondiBas+10)+'</option>';
            $html += '<option value="'+(arrondiBas+15)+'">'+(arrondiBas+15)+'</option>';
            $html += '<option value="'+(arrondiBas+20)+'">'+(arrondiBas+20)+'</option>';
            $html += '</select> €</div>';
            
            $($html).fadeIn('slow').appendTo('#lesChoix');
            enlever = 1;
           if (enlever == 1) {
            $('#choixArrondi').last().remove();
           }
           
            }
    
    
    function calculenplus(){
                var paHTs = "paHTs"+i;
                var marges = "marges"+i;
                var pvHTs = "pvHTs"+i;
                var pvTTCs = "pvTTCs"+i;
                var margeM = "margeM"+i;
                var multiplier = 100;
        
                var totalMarge = document.getElementById("margeM").value;
                var totalMarge = Math.round(totalMarge * multiplier) / multiplier;  
        
                var totalHT = document.getElementById("pvHT").value;
                var totalHT = Math.round(totalHT * multiplier) / multiplier;
        
                var totalTTC = document.getElementById("pvTTC").value;
                var totalTTC = Math.round(totalTTC * multiplier) / multiplier;
       
                var paHT = Number(document.getElementById(paHTs).value);
        
                var marge = Number(document.getElementById(marges).value);
 
                var pvHT = Number(paHT+(paHT*(marge/100)));
                pvHT = Math.round(pvHT * multiplier) / multiplier;
                document.getElementById(pvHTs).value = pvHT;
        
                var pvTTC = Number(pvHT+(pvHT*(20/100)));
                pvTTC = Math.round(pvTTC * multiplier) / multiplier;
                document.getElementById(pvTTCs).value = pvTTC;
        
                var margeMt = Number(pvHT-paHT);
                margeMt = Math.round(margeMt * multiplier) / multiplier;
                document.getElementById(margeM).value = margeMt;
        
        if(pvHT>10) {
            var arrondiBas = 10*Math.floor(pvHT/10);
                       }
           else {
               var arrondiBas = Math.floor(pvHT);
               }
            $html = '<div id="choixArrondi'+(i+1)+'">Choisir l\'arrondi du PvHT ';
            $html += '<select name="arrondi" onchange="choixArrondiPlus()" id="choixArrondiSelect'+(i+1)+'" required>';               $html += '<option value="'+pvHT+'">'+pvHT+'</option>';
            $html += '<option value="'+(arrondiBas+5)+'">'+(arrondiBas+5)+'</option>';
            $html += '<option value="'+(arrondiBas+10)+'">'+(arrondiBas+10)+'</option>';
            $html += '<option value="'+(arrondiBas+15)+'">'+(arrondiBas+15)+'</option>';
            $html += '<option value="'+(arrondiBas+20)+'">'+(arrondiBas+20)+'</option>';
            $html += '</select> €</div>';
            $('#choixArrondi'+(i+1)).last().remove();
            $($html).fadeIn('slow').appendTo('.materiel'+i);
            
        


        //CALCUL DES TOTAUX
                for (j = 1; j<=i; j++) {
                    
                //CALCULE TOTAL DE LA MARGE
                  var margeTotal="margeM"+j;
                  var sommeHT=document.getElementById(margeTotal).value;
                //simplification au centième près
                 var sommeHT = Math.round(sommeHT * multiplier) / multiplier;  
                 totalMarge+=sommeHT;
                //simplification au centième près
                var totalMarge = Math.round(totalMarge * multiplier) / multiplier;
                    
                //CALCULE TOTAL DE LA HT
                  var horstaxeTotal="pvHTs"+j;
                  var horstaxeSomme=document.getElementById(horstaxeTotal).value;
                //simplification au centième près
                 var horstaxeHT = Math.round(horstaxeSomme * multiplier) / multiplier;  
                 totalHT+=horstaxeHT;
                //simplification au centième près
                var totalHT = Math.round(totalHT * multiplier) / multiplier; 
                
                //CALCULE TOTAL DE LA TTC
                  var ttcTotal="pvTTCs"+j;
                  var ttcSomme=document.getElementById(ttcTotal).value;
                //simplification au centième près
                 var toutetaxeTTC = Math.round(ttcSomme * multiplier) / multiplier;  
                 totalTTC+=toutetaxeTTC;
                //simplification au centième près
                var totalTTC = Math.round(totalTTC * multiplier) / multiplier; 
                }
            
            document.getElementById("margeT").value=totalMarge;
            document.getElementById("pHTT").value=totalHT;
            document.getElementById("pvTTCT").value=totalTTC;

            }
function choixArrondi() {
        var multiplier = 100;
        var choix = document.getElementById("choixArrondiSelect").value;
        choix = Number(choix);
        document.getElementById("pvHT").value = choix;
        
        var pvTTC = Number(choix+(choix*(20/100)));
            pvTTC = Number(pvTTC);
        document.getElementById("pvTTC").value = pvTTC;
    
        var paHT = document.getElementById("paHT").value;    
                var margeM = Number(choix-paHT);
        document.getElementById("margeM").value = margeM;
    
    }
    function choixArrondiPlus() {
        var multiplier = 100;
        //on récupère l'arrondi que l'on a choisi
        var choix = document.getElementById("choixArrondiSelect"+(i+1)).value;
        choix = Number(choix);
        
        document.getElementById("pvHTs"+i).value = choix;
        var pvTTC = Number(choix+(choix*(20/100)));
            pvTTC = Number(pvTTC);
        document.getElementById("pvTTCs"+i).value = pvTTC;
    
        var paHT = document.getElementById("paHTs"+i).value;    
                var margeM = Number(choix-paHT);
        document.getElementById("margeM"+i).value = margeM;
        
        
        
        
    //CALCUL DES TOTAUX
        
                var totalMarge = document.getElementById("margeM").value;
                var totalMarge = Math.round(totalMarge * multiplier) / multiplier;  
        
                var totalHT = document.getElementById("pvHT").value;
                var totalHT = Math.round(totalHT * multiplier) / multiplier;
        
                var totalTTC = document.getElementById("pvTTC").value;
                var totalTTC = Math.round(totalTTC * multiplier) / multiplier;
                for (j = 1; j<=i; j++) {
                    
                //CALCULE TOTAL DE LA MARGE
                  var margeTotal="margeM"+j;
                    
                  var sommeHT = document.getElementById(margeTotal).value;
                //simplification au centième près
                 var sommeHT = Math.round(sommeHT * multiplier) / multiplier;
                 totalMarge += sommeHT;
                //simplification au centième près
                var totalMarge = Math.round(totalMarge * multiplier) / multiplier;
                    
                //CALCULE TOTAL DE LA HT
                  var horstaxeTotal="pvHTs"+j;
                  var horstaxeSomme=document.getElementById(horstaxeTotal).value;
                //simplification au centième près
                 var horstaxeHT = Math.round(horstaxeSomme * multiplier) / multiplier;  
                 totalHT+=horstaxeHT;
                //simplification au centième près
                var totalHT = Math.round(totalHT * multiplier) / multiplier; 
                
                //CALCULE TOTAL DE LA TTC
                  var ttcTotal="pvTTCs"+j;
                  var ttcSomme=document.getElementById(ttcTotal).value;
                //simplification au centième près
                 var toutetaxeTTC = Math.round(ttcSomme * multiplier) / multiplier;  
                 totalTTC+=toutetaxeTTC;
                //simplification au centième près
                var totalTTC = Math.round(totalTTC * multiplier) / multiplier; 
                }
            
            document.getElementById("margeT").value=totalMarge;
            document.getElementById("pHTT").value=totalHT;
            document.getElementById("pvTTCT").value=totalTTC;
    }
</script>
</form>
</div>
    */

// voici ma requete qui récupère tout de la base de donnée
    /*
        $query = $pdo->query("SELECT c.membre_id, m.membre_nom AS nom_client , m.membre_prenom, c.cmd_id, c.cmd_reference, c.cmd_designation, 
                      c.cmd_prixachat, c.cmd_prixvente, c.cmd_datein, c.cmd_dateout, c.cmd_prixventettc, l.membre_nom AS nom_livreur, 
                      c.cmd_dateSouhait, e.commande_etat, c.cmd_fournisseur, f.nomFournisseur, m.membre_nom 
                      FROM commande c JOIN membres m ON m.membre_id = c.membre_id 
                      JOIN membres l ON c.cmd_livreur = l.membre_id 
                      JOIN fournisseur f ON c.cmd_fournisseur = f.idFournisseur 
                      JOIN commande_etats e ON c.cmd_etat = e.id_etat_cmd 
                      ORDER BY c.cmd_datein DESC");
    */
//table des produits 
    /*
    CREATE TABLE IF NOT EXISTS `commande_produit` (
  `idProduit` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(30) NOT NULL,
  `designation` text NOT NULL,
  `paHT` decimal(9,2) NOT NULL,
  `marge` decimal(9,2) NOT NULL,
  `pvHT` decimal(9,2) NOT NULL,
  `pvTTC` decimal(9,2) NOT NULL,
  `etat` varchar(20) NOT NULL,
  `id_commande` int(11) NOT NULL,
  `fournisseur` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`idProduit`),
  KEY `id_commande` (`id_commande`),
  KEY `fournisseur` (`fournisseur`)
    ) */

//connexion à la base de donnée
include 'ConnexionBD.php'; // Fichier de configuration de la connexion PDO

//requete pour récupérer les fournisseurs
$fournisseur = $pdo->query("SELECT * FROM fournisseur");
$fournisseur->execute();

?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Commandes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Ajouter une commande</h2>
        <form action="trait-commande.php" method="post" name="commande">
            <fieldset><legend>Produit <small>* champs obligatoires</small></legend>
                <div id="materiels">
                    <div id="materiel">
                        <label><b>1er produit de la commande</b></label><br>
                        <label for="reference">Référence* </label> 
                        <input type="text" name="dynamic['0'][]" id="reference" required autofocus/>&nbsp;&nbsp;|&nbsp;&nbsp;
                        <label for="designation">Désignation* </label>
                        <input type="text" name="dynamic['0'][]" id="designation" required>&nbsp;&nbsp;|&nbsp;&nbsp;
                        <label for="fournisseur">Fournisseur* </label>
                        <select name="dynamic['0'][]" required>
                            <option value="sans Fournisseur">------</option>
                            <?php
                            while ($unfournisseur = $fournisseur->fetch(PDO::FETCH_ASSOC)) 
                            {
                                
                                ?>
                            <option value="<?php echo $unfournisseur['nomFournisseur'] ?>"><?php echo $unfournisseur['nomFournisseur'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                        <br>
                        <br>
                        <label >Pa HT* </label>
                        <input type="text" SIZE="2" name="dynamic['0'][]" id="paHT" onblur="calcul()"  required autofocus> € &nbsp;&nbsp;|&nbsp;&nbsp;
                        <label>Marge* </label>
                        <input type="text" SIZE="2" name="dynamic['0'][]" id="marge" onblur="calcul()"  required autofocus> % &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;
                        <label>Pv HT* </label>
                        <input type="text"  name="dynamic['0'][]" id="pvHT" SIZE="2" required autofocus readonly> € &nbsp;&nbsp;|&nbsp;&nbsp;
                        <label>Pv TTC* </label>
                        <input type="text" name="dynamic['0'][]" id="pvTTC" SIZE="2" required autofocus readonly> € &nbsp;&nbsp;|&nbsp;&nbsp;
                        <label>Montant marge  </label>
                        <input type="text" name="dynamic['0'][]" id="margeM" SIZE="2" readonly> € 
                        
                        <div id="lesChoix">
                            <div id="choixArrondi"></div>
                        </div>

                        <label for="etat" class="float">Statut produit* </label>
                        <select name="dynamic['0'][]" required>
                            <option value="commande">Commandé</option>
                            <option value="recu">Reçu</option>
                        </select>
                        <div id="choixArrondi"></div>
                    </div>
                </div>
            </fieldset>
            <button type="button" onclick="ajouterProduit()">Ajouter un produit à la commande</button>
            <button type="button" onclick="enleverProduit()">Enlever un produit à la commande</button>
            
            
            <label for="reference" class="float" >Nom de la commande* </label>
            <input type="text" name="nomC" id="reference" required autofocus/><br>

            <label class="float">Date de livraison prévue</label><input type="date" name="dateP" required autofocus><br>
            <label class="float">Date de livraison souhaitée</label><input type="date" name="dateS" required autofocus><br>

            <label for="etat" class="float" >Statut commande* </label>
            <select name="etatC" required>
                <option value="commande">Commandé</option>
                <option value="attente">En attente</option>
                <option value="recu">Reçu</option>
                <option value="alivrer">A livrer</option>
                <option value="livrer">Livrer</option>
            </select><br>

            <label for="reference" class="float">Total HT </label>
            <input type="text" name="totalHT" id="pHTT" readonly/><br>
            
            <label for="reference" class="float">Total TTC </label>
            <input type="text" name="totalTTC" id="pvTTCT" readonly/><br>
            
            <label for="reference" class="float">Total marge </label>
            <input type="text" name="totalMarge" id="margeT" readonly/><br>
        </form>
    </div>
</body>
<script>
    function calcul(){
            
            var paHT = Number(document.getElementById("paHT").value);
            var marge = Number(document.getElementById("marge").value);
            var multiplier = 100;
    
            var pvHT = Number(paHT+(paHT*(marge/100)));
            pvTTC = Math.round(pvTTC * multiplier) / multiplier;
            document.getElementById("pvHT").value = pvHT;
    
            var pvTTC = Number(pvHT+(pvHT*(20/100)));
            pvTTC = Math.round(pvTTC * multiplier) / multiplier;
            document.getElementById("pvTTC").value = pvTTC;
    
            var margeM = Number(pvHT-paHT);
            margeM = Math.round(margeM * multiplier) / multiplier;
            document.getElementById("margeM").value = margeM;  
    
            var totalMarge = margeM;
            var totalHT = pvHT;
            var totalTTC = pvTTC;
    
            //simplification au centième près
    
            var totalMarge = Math.round(totalMarge * multiplier) / multiplier;
    
            //simplification au centième près
            var totalHT = Math.round(totalHT * multiplier) / multiplier;
    
            //simplification au centième près
            var totalTTC = Math.round(totalTTC * multiplier) / multiplier;

            document.getElementById('margeT').value=totalMarge;
            document.getElementById("pHTT").value=totalHT;
            document.getElementById("pvTTCT").value=totalTTC;

            if(pvHT>10) {
                var arrondiBas = 10*Math.floor(pvHT/10);
            }
            else {
                var arrondiBas = Math.floor(pvHT);
            }
            var html = '<div id="choixArrondi">Choisir l\'arrondi du PvHT ';
            html += '<select name="arrondi" onchange="choixArrondi()" id="choixArrondiSelect" required><option value="15">' + pvHT + '</option>';
            html += '<option value="' + arrondiBas + '">' + arrondiBas + '</option>';
            html += '<option value="' + (arrondiBas + 5) + '">' + (arrondiBas + 5) + '</option>';
            html += '<option value="' + (arrondiBas + 10) + '">' + (arrondiBas + 10) + '</option>';
            html += '<option value="' + (arrondiBas + 15) + '">' + (arrondiBas + 15) + '</option>';
            html += '<option value="' + (arrondiBas + 20) + '">' + (arrondiBas + 20) + '</option>';
            html += '</select> €</div>';
            document.getElementById('lesChoix').innerHTML = html;
        }

        function choixArrondi() {
            var multiplier = 100;
            var choix = document.getElementById("choixArrondiSelect").value;
            choix = Number(choix);
            document.getElementById("pvHT").value = choix;

            var pvTTC = Number(choix+(choix*(20/100)));
            pvTTC = Number(pvTTC);
            document.getElementById("pvTTC").value = pvTTC;

            var paHT = document.getElementById("paHT").value;    
            var margeM = Number(choix-paHT);
            document.getElementById("margeM").value = margeM;
        }

        function ajouterProduit(){
            var i = document.querySelectorAll('.materiel').length + 1;
            i++;
            var html = '<div class="materiel"><hr>';
            html += '<br><b>' + i + 'e produit de la commande</b><br>';
            html += '<label for="reference">Référence* </label> ';
            html += '<input type="text" name="dynamic[' + i + '][]" id="reference' + i + '" required autofocus/>&nbsp;&nbsp;|&nbsp;&nbsp;';
            html += '<label for="designation">Désignation* </label>';
            html += '<input type="text" name="dynamic[' + i + '][]" id="designation" required>&nbsp;&nbsp;|&nbsp;&nbsp;';

            html += '<label for="fournisseur">Fournisseur* </label>';
            html += '<select name="dynamic[' + i + '][]" required>';
            html += '<option value="sans Fournisseur">------</option>';
            <?php
            while ($unfournisseur = $fournisseur->fetch(PDO::FETCH_ASSOC)) 
            {
                ?>
                html += '<option value="<?php echo $unfournisseur['nomFournisseur'] ?>"><?php echo $unfournisseur['nomFournisseur'] ?></option>';
                <?php
            }
            ?>
            html += '</select>';
            html += '<br><br>';
            html += '<label >Pa HT* </label>';
            html += '<input type="text" SIZE="2" name="dynamic[' + i + '][]" id="paHTs' + i + '" onblur="calculenplus()"  required autofocus> € &nbsp;&nbsp;|&nbsp;&nbsp;';
            html += '<label>Marge* </label>';
            html += '<input type="text" SIZE="2" name="dynamic[' + i + '][]" id="marges' + i + '" onblur="calculenplus()"  required autofocus> % &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;';
            html += '<label>Pv HT* </label>';
            html += '<input type="text"  name="dynamic[' + i + '][]" id="pvHTs' + i + '" SIZE="2" required autofocus readonly> € &nbsp;&nbsp;|&nbsp;&nbsp;';
            html += '<label>Pv TTC* </label>';
            html += '<input type="text" name="dynamic[' + i + '][]" id="pvTTCs' + i + '" SIZE="2" required autofocus readonly> € &nbsp;&nbsp;|&nbsp;&nbsp;';
            html += '<label>Montant marge </label>';
            html += '<input type="text" name="dynamic[' + i + '][]" id="margeM' + i + '" SIZE="2" readonly> € <br />';
            html += '<div id="lesChoix"> <div id="choixArrondi"></div> </div> </div>';
            html += '<label for="etat" class="float">Statut produit* </label><select name="dynamic[' + i + '][]" required><option value="commande">Commandé</option><option value="recu">Reçu</option></fieldset><br>';
            html += '</div>';

            document.getElementById('materiels').innerHTML += html;

        }

        function calculerenplus(){
            var paHTs = "paHTs" + i;
            var marges = "marges" + i;
            var pvHTs = "pvHTs" + i;
            var pvTTCs = "pvTTCs" + i;
            var margeM = "margeM" + i;
            var multiplier = 100;

            var totalMarge = document.getElementById("margeM").value;
            var totalMarge = Math.round(totalMarge * multiplier) / multiplier;

            var totalHT = document.getElementById("pvHT").value;
            var totalHT = Math.round(totalHT * multiplier) / multiplier;

            var totalTTC = document.getElementById("pvTTC").value;
            var totalTTC = Math.round(totalTTC * multiplier) / multiplier;

            var paHT = Number(document.getElementById(paHTs).value);

            var marge = Number(document.getElementById(marges).value);

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
            }
            else {
                var arrondiBas = Math.floor(pvHT);
            }
            var html = '<div id="choixArrondi' + i + '">Choisir l\'arrondi du PvHT ';
            html += '<select name="arrondi" onchange="choixArrondiPlus()" id="choixArrondiSelect' + i + '" required>';
            html += '<option value="' + pvHT + '">' + pvHT + '</option>';
            html += '<option value="' + (arrondiBas + 5) + '">' + (arrondiBas + 5) + '</option>';
            html += '<option value="' + (arrondiBas + 10) + '">' + (arrondiBas + 10) + '</option>';
            html += '<option value="' + (arrondiBas + 15) + '">' + (arrondiBas + 15) + '</option>';
            html += '<option value="' + (arrondiBas + 20) + '">' + (arrondiBas + 20) + '</option>';
            html += '</select> €</div>';
            document.getElementById('choixArrondi' + i).innerHTML = html;
        }

    

    </script>
</script>
</html>
