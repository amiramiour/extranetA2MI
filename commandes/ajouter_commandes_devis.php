<?php
include '../gestion_session.php';
require_once '../config.php';

include '../ConnexionBD.php';
$pdo = connexionbdd();

include '../navbar.php';

$db = connexionbdd();

//requete pour récupérer les fournisseurs
$req = $pdo->query("SELECT * FROM fournisseur");
$fournisseurs = $req->fetchAll(PDO::FETCH_ASSOC);
$jsonFournisseurs = json_encode($fournisseurs);
file_put_contents('fournisseurs.json', $jsonFournisseurs);
$req2 = $pdo->query("SELECT * FROM cmd_devis_etats");
$cmd_devis_etats = $req2->fetchAll(PDO::FETCH_ASSOC);
//vérifier si $id est récupéré
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    // on fait une requete pour récupérer les clients
    $query = $pdo->query("SELECT DISTINCT *
                    FROM membres m 
                    where m.membre_type = 'client'
                    GROUP BY membre_nom
                    ORDER BY membre_nom");
    /*$query->execute();
    $clients = $query->fetchAll();*/
}

?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Commandes / Devis</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Ajouter une Commande / Devis</h2>
        <!-- <form action="traitement_commande.php?id=<?php echo $id; ?>" method="post" name="commande">-->
        <form action="<?php echo isset($id) ? 'traitement_ajout.php?id=' . $id : 'traitement_ajout.php'; ?>" method="post" name="commande" enctype="multipart/form-data">
            <!-- Liste déroulante pour selectionner si le formulaire est pour créer une commande ou un devis -->
            <select name="type" required onchange="afficherChampsSupplementaires()">
                <option value="1">Commande</option>
                <option value="2">Devis</option>
            </select>
            <br><br>
            <?php if (!isset($id)) { ?>
                <input type="search" name="client_id" placeholder="Rechercher" size="10" list="recherche">
                <datalist id="recherche">
                    <?php while ($donnees = $query->fetch(PDO::FETCH_ASSOC)) { ?>
                        <!-- recherche par nom,n prenom, entreprise, tel, value = "<?php echo $client['membre_id']; ?>" pour récupérer l'id du client-->
                        <option value="<?php echo $donnees['membre_id']; ?>">
                            <?php echo mb_strtoupper(htmlspecialchars($donnees['membre_entreprise'], ENT_QUOTES)); ?>
                            <?php echo mb_strtoupper(htmlspecialchars($donnees['membre_nom'], ENT_QUOTES)); ?>
                            <?php echo mb_strtoupper(htmlspecialchars($donnees['membre_prenom'], ENT_QUOTES)); ?>
                            <?php echo mb_strtoupper(htmlspecialchars($donnees['membre_ville'], ENT_QUOTES)); ?>
                            <?php echo mb_strtoupper(htmlspecialchars($donnees['membre_tel'], ENT_QUOTES)); ?>
                        </option>
                    <?php } ?>
                </datalist>
            <?php } ?>
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
                            <?php
                            $jsonData = file_get_contents('fournisseurs.json');
                            $fournisseurs = json_decode($jsonData, true);
                            foreach ($fournisseurs as $fournisseur) {?>
                                <option value="<?php echo $fournisseur['idFournisseur']; ?>"><?php echo $fournisseur['nomFournisseur']; ?></option>
                                <?php
                            }?>
                        </select>
                        <br><br>
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
            <button type="button" onclick="supprimerProduit()">Supprimer</button>
            <br><br>
            <button type="button" onclick="ajouterProduit()">Ajouter un produit à la commande</button>
            <br><br>
            <!-- <button type="button" onclick="enleverProduit()">Enlever un produit à la commande</button> -->
            <div id="champsDevis" style="display: none;">
                <div id="photos">
                    <label for="picture">Prendre une photo :</label>
                    <input type="file" id="picture" accept="image/* , .pdf" capture="environment" name="photos[]" multiple>
                </div>
                <br><br>
                <button type="button" onclick="ajouterPhoto()">Ajouter une autre photo</button>
                <br><br>
                <label for="commentaire">Commentaire *</label><br>
                <textarea name="commentaire" id="commentaire" rows="4" cols="50"></textarea><br><br>
            </div>
            
            <br>
            
            <label for="reference" class="float" >Reference* </label>
            <input type="text" name="nomC" id="reference" required autofocus/><br>
            <label for="designation" class="float">Désignation* </label>
            <input type="text" name="designation" id="designation" required autofocus/><br>
            <label class="float">Date de livraison prévue</label><input type="date" name="dateP" required autofocus><br>
            <label class="float">Date de livraison souhaitée</label><input type="date" name="dateS" required autofocus><br>

            <label for="etat" class="float" >Statut * </label>         
            <select name="etatC" id="etatC" required></select><br>

            <label for="reference" class="float">Total HT </label>
            <input type="text" name="totalHT" id="pHTT" readonly/><br>
            <label for="reference" class="float">Total TTC </label>
            <input type="text" name="totalTTC" id="pvTTCT" readonly/><br>
            
            <label for="reference" class="float">Total marge </label>
            <input type="text" name="totalMarge" id="margeT" readonly/><br>
            <div class="center"><input class="createButton" type="submit" value="Créer" /></div>
        </form>
        <div class="center"><input class="createButton" type="button" value="Annuler" onclick="window.history.back()"/>       
    </div>
    <script>
        var TVA = <?php echo TVA; ?>;
        function calcul(){
            var i = document.querySelectorAll('.materiel').length;
            var paHT = Number(document.getElementById("paHT").value);
            var marge = Number(document.getElementById("marge").value);
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
            var multiplier = 100;
    
            var pvHT = Number(paHT+(paHT*(marge/100)));
            pvTTC = Math.round(pvTTC * multiplier) / multiplier;
            document.getElementById("pvHT").value = pvHT;
    
            var pvTTC = Number(pvHT+(pvHT * TVA));
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
            recalculerTotaux(i);
        }
        function choixArrondi() {
            var i = document.querySelectorAll('.materiel').length;
            var multiplier = 100;
            var choix = document.getElementById("choixArrondiSelect").value;
            choix = Number(choix);
            document.getElementById("pvHT").value = choix;
            var pvTTC = Number(choix+(choix * TVA)); 
            pvTTC = Number(pvTTC);
            document.getElementById("pvTTC").value = pvTTC;
            var paHT = document.getElementById("paHT").value;    
            var margeM = Number(choix-paHT);
            document.getElementById("margeM").value = margeM;
            var totalMarge = document.getElementById("margeM").value;
            var totalMarge = Math.round(totalMarge * multiplier) / multiplier;
            document.getElementById("margeT").value=totalMarge;
            var totalHT = document.getElementById("pvHT").value;
            var totalHT = Math.round(totalHT * multiplier) / multiplier;
            document.getElementById("pHTT").value=totalHT;
            var totalTTC = document.getElementById("pvTTC").value;
            var totalTTC = Math.round(totalTTC * multiplier) / multiplier;
            document.getElementById("pvTTCT").value=totalTTC;
            recalculerTotaux(i);
            
        }
        function ajouterProduit(){
            var i = document.querySelectorAll('.materiel').length;
            i++;
            var html = '<div class="materiel"><hr>';
            html += '<br><b>' + (i+1) + 'e produit de la commande</b><br>';
           
            html += '<label for="reference">Référence* </label> ';
            html += '<input type="text" name="dynamic[' + i + '][]" id="reference' + i + '" required autofocus/>&nbsp;&nbsp;|&nbsp;&nbsp;';
            html += '<label for="designation">Désignation* </label>';
            html += '<input type="text" name="dynamic[' + i + '][]" id="designation' + i + '" required>&nbsp;&nbsp;|&nbsp;&nbsp;';
            html += '<label for="fournisseur">Fournisseur* </label>';
            html += '<select name="dynamic[' + i + '][]" required>';
            <?php
            $jsonData = file_get_contents('fournisseurs.json');
            $fournisseurs = json_decode($jsonData, true);
            foreach ($fournisseurs as $fournisseur) { ?>
                html += '<option value="<?php echo $fournisseur['idFournisseur']; ?>"><?php echo $fournisseur['nomFournisseur']; ?></option>';
            <?php } ?>
            html += '</select>';
            html += '<br><br>';
            html += '<label >Pa HT* </label>';
            html += '<input type="text" SIZE="2" name="dynamic[' + i + '][]" id="paHTs' + i + '" onblur="calculerenplus(' + i + ')"  required autofocus> € &nbsp;&nbsp;|&nbsp;&nbsp;';
            html += '<label>Marge* </label>';
            html += '<input type="text" SIZE="2" name="dynamic[' + i + '][]" id="marges' + i + '" onblur="calculerenplus(' + i + ')"  required autofocus> % &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;';
            html += '<label>Pv HT* </label>';
            html += '<input type="text"  name="dynamic[' + i + '][]" id="pvHTs' + i + '" SIZE="2" required autofocus readonly> € &nbsp;&nbsp;|&nbsp;&nbsp;';
            html += '<label>Pv TTC* </label>';
            html += '<input type="text" name="dynamic[' + i + '][]" id="pvTTCs' + i + '" SIZE="2" required autofocus readonly> € &nbsp;&nbsp;|&nbsp;&nbsp;';
            html += '<label>Montant marge </label>';
            html += '<input type="text" name="dynamic[' + i + '][]" id="margeM' + i + '" SIZE="2" readonly> € <br />';
            html += '<div id="choixArrondi' + i + '"></div>';
            html += '<label for="etat" class="float">Statut produit* </label><select name="dynamic[' + i + '][]" required><option value="commande">Commandé</option><option value="recu">Reçu</option>';
            html += '</select><br>';
            html += '</div>';
        
            document.getElementById('materiels').insertAdjacentHTML('beforeend', html);
            
        }
         
        function calculerenplus(i) {
            var paHTs = "paHTs" + i;
            var marges = "marges" + i;
            var pvHTs = "pvHTs" + i;
            var pvTTCs = "pvTTCs" + i;
            var margeM = "margeM" + i;
            var multiplier = 100;
            /**var totalMarge = document.getElementById("margeT").value;
            var totalMarge = Math.round(totalMarge * multiplier) / multiplier;  
        
            var totalHT = document.getElementById("pHTT").value;
            var totalHT = Math.round(totalHT * multiplier) / multiplier;
        
            var totalTTC = document.getElementById("pvTTCT").value;
            var totalTTC = Math.round(totalTTC * multiplier) / multiplier;**/
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
            var pvTTC = Number(pvHT + (pvHT * TVA));
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
            /*totalMarge += margeMt;
            totalHT += pvHT;
            totalTTC += pvTTC;
            document.getElementById('margeT').value = Math.round(totalMarge * multiplier) / multiplier;
            document.getElementById('pHTT').value = Math.round(totalHT * multiplier) / multiplier;
            document.getElementById('pvTTCT').value = Math.round(totalTTC * multiplier) / multiplier;*/
        }
        function choixArrondiPlus(i){
            var multiplier = 100;
            var choix = document.getElementById("choixArrondiSelect" + i).value;
            choix = Number(choix);
            document.getElementById("pvHTs" + i).value = choix;
            
            var pvTTC = Number(choix + (choix * TVA));
            pvTTC = Number(pvTTC);
            document.getElementById("pvTTCs" + i).value = pvTTC;
            var paHT = document.getElementById("paHTs" + i).value;
            var margeM = Number(choix - paHT);
            document.getElementById("margeM" + i).value = margeM;
            /*var totalMarge = document.getElementById("margeM").value;
            var totalMarge = Math.round(totalMarge * multiplier) / multiplier;  
        
            var totalHT = document.getElementById("pvHT").value;
            var totalHT = Math.round(totalHT * multiplier) / multiplier;
        
            var totalTTC = document.getElementById("pvTTC").value;
            var totalTTC = Math.round(totalTTC * multiplier) / multiplier;
            totalMarge += margeM;
            totalHT += paHT;
            totalTTC += pvTTC;
            document.getElementById("margeT").value = totalMarge;
            document.getElementById("pHTT").value = totalHT;
            document.getElementById("pvTTCT").value = totalTTC;*/
            recalculerTotaux(i);
        }
        // Fonction pour recalculer les totaux après suppression d'un produit
        function recalculerTotaux(i) {
            
            var multiplier = 100;
            if (document.getElementById("margeM") && !isNaN(parseFloat(document.getElementById("margeM").value))) {
                var totalMarge = parseFloat(document.getElementById("margeM").value);
            }else 
            {
                var totalMarge = 0;
            }
            var totalMarge = Math.round(totalMarge * multiplier) / multiplier;
        
            var totalHT = document.getElementById("pvHT").value;
            var totalHT = Math.round(totalHT * multiplier) / multiplier;
        
            var totalTTC = document.getElementById("pvTTC").value;
            var totalTTC = Math.round(totalTTC * multiplier) / multiplier;
            console.log("La fonction recalculerTotaux est en train d'être exécutée.");
    
            for (j = 1; j <= i; j++) {
                //CALCULE TOTAL DE LA MARGE
                var margeTotal = "margeM" + j;
                var sommeHT = document.getElementById(margeTotal);
                if (sommeHT && !isNaN(parseFloat(sommeHT.value))) {
                    totalMarge += parseFloat(sommeHT.value);
                }
                //CALCULE TOTAL DE LA HT
                var horstaxeTotal = "pvHTs" + j;
                var horstaxeSomme = document.getElementById(horstaxeTotal);
                console.log(parseFloat(horstaxeSomme.value));
                if (horstaxeSomme && !isNaN(parseFloat(horstaxeSomme.value))) {
                    totalHT += parseFloat(horstaxeSomme.value);
                }
                //CALCULE TOTAL DE LA TTC
                var ttcTotal = "pvTTCs" + j;
                var ttcSomme = document.getElementById(ttcTotal);
                if (ttcSomme && !isNaN(parseFloat(ttcSomme.value))) {
                    totalTTC += parseFloat(ttcSomme.value);
                }
                //simplification au centième près
                totalMarge = Math.round(totalMarge * multiplier) / multiplier;
                totalHT = Math.round(totalHT * multiplier) / multiplier;
                totalTTC = Math.round(totalTTC * multiplier) / multiplier;
            }
            document.getElementById("margeT").value = totalMarge;
            document.getElementById("pHTT").value = totalHT;
            document.getElementById("pvTTCT").value = totalTTC;
            console.log("La fonction recalculerTotaux a fini d'être exécutée."); 
        }
        function supprimerProduit(){
            var materiels = document.querySelectorAll('.materiel');
            if (materiels.length > 0) {
                materiels[materiels.length - 1].remove();
                recalculerTotaux(materiels.length - 1);
            }else{
                alert("Vous ne pouvez pas supprimer le dernier produit.");
            }
        }
        function afficherChampsSupplementaires() {
            var type = document.querySelector('select[name="type"]').value;
            var champsDevis = document.getElementById('champsDevis');
            var commentaire = document.getElementById('commentaire');
            // Si l'utilisateur choisit un devis, affichez les champs supplémentaires, sinon masquez-les
            if (type === '2') {
                champsDevis.style.display = 'block';
                commentaire.required = true;
            } else {
                champsDevis.style.display = 'none';
                commentaire.required = false;            
            }
        }
        document.querySelector('select[name="type"]').addEventListener('change', afficherChampsSupplementaires);
        afficherChampsSupplementaires();

        function ajouterPhoto() {
            var divPhotos = document.getElementById('photos');
            var nouveauChamp = document.createElement('div');
            nouveauChamp.innerHTML = '<label for="picture">Prendre une photo :</label>' +
                                    '<input type="file" accept="image/* , .pdf" id="picture" capture="environment" name="photos[]" multiple>';
            divPhotos.appendChild(nouveauChamp);
        }

        function filtrerEtats() {
            var type = document.querySelector('select[name="type"]').value;
            var selectEtat = document.getElementById('etatC');

            // Supprimer toutes les options existantes
            selectEtat.innerHTML = '';

            if (type === '2') {  //Devis
                var optionEnAttente = document.createElement('option');
                optionEnAttente.value = '2'; 
                optionEnAttente.textContent = 'En attente';
                selectEtat.appendChild(optionEnAttente);

                var optionTermine = document.createElement('option');
                optionTermine.value = '6'; 
                optionTermine.textContent = 'Terminé';
                selectEtat.appendChild(optionTermine);
            } else { //commande
                <?php foreach ($cmd_devis_etats as $etat) { ?>
                    var option<?php echo $etat['id_etat_cmd_devis']; ?> = document.createElement('option');
                    option<?php echo $etat['id_etat_cmd_devis']; ?>.value = '<?php echo $etat['id_etat_cmd_devis']; ?>';
                    option<?php echo $etat['id_etat_cmd_devis']; ?>.textContent = '<?php echo $etat['cmd_devis_etat']; ?>';
                    selectEtat.appendChild(option<?php echo $etat['id_etat_cmd_devis']; ?>);
                <?php } ?>
            }
        }
        document.querySelector('select[name="type"]').addEventListener('change', filtrerEtats);

        filtrerEtats();
    </script>
</body>
</html>