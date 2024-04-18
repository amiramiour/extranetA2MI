<!-- Creation SAV Contenu-->
<?php
if ($_SESSION['membre_type'] == 'client') {
header('Location: index.php');
exit();
}
$id = 1;?>
<div id="contenu">
    <h1>Formulaire de création d'un SAV client</h1>
    <form action="process_sav.php?id=<?php echo $id; ?>" method="post" name="SAV">
        <fieldset><legend>SAV client <small>* champs obligatoires</small></legend>
            <label for="probleme" class="float">Problème* </label>
            <textarea type="text" name="probleme" id="probleme" rows="4" cols="50" required autofocus></textarea> <br />
            <label for="mdp" class="float mdpclient">Mot de passe client </label>
            <input type="text" name="mdp" id="mdp" size="30" /> <br /><br>

            <label for="regle" class="float">Facture réglée </label> <input type="checkbox" name="regle" value="oui"><br /><br>
            <label for="envoi" class="float">Envoi facture* <span class="mdpclient">(obligatoire)</span> </label><br/><br/>
            <!-- <input type="radio" name="envoie" value="courier" required>Courrier<tr/>
            <input type="radio" name="envoie" value="mail" required>Mail<tr/>
            <input type="radio" name="envoie" value="courrierEtmail" required>Courrier et mail<tr/> -->
            <label for="courrier" class="tabulation float">Courrier </label> <input type="checkbox" name="courrier" value="oui"> <br/>
            <label for="mail" class="tabulation float">Mail </label> <input type="checkbox" name="mail" value="oui">
            <div class="notes">( Vous pouvez cocher les deux cases ci-dessus pour facture via Courrier ET Mail )</div><br/><br/>

            <label for="typemateriel" class="float">Type de matériel* </label><br/><br/>
            <input type="radio" name="typemateriel" value="pc" required>PC
            <input type="radio" name="typemateriel" value="portable" required>Laptop
            <input type="radio" name="typemateriel" value="tablette" required>Tablette
            <input type="radio" name="typemateriel" value="telephone" required>Téléphone
            <input type="radio" name="typemateriel" value="imprimante" required>Imprimante
            <input type="radio" name="typemateriel" value="peripherique" required>Périphérique<br/><br/>
            <label for="access" class="float mdpclient">Accessoires</label>
            <input type="text" name="access" id="access" size="30" /> <br />

            <!-- MAIN D'OEUVRE-->
            <label for="listForfait" class="float">Tarif main d'oeuvre HT </label>
            <input type="text" name="listForfait" list="listForfait">
            <datalist id="listForfait">
                <option value="Format reinstallation"></option>
                <option value="Format reinstallation sans sauvegarde"></option>
                <option value="Main d'oeuvre atelier 1H"></option>
                <option value="Eradication virus"></option>
                <option value="Recuperation de donnees"></option>
                <option value="Diagnostic"></option>
                <option value="Nettoyage"></option>
            </datalist>
            <input type="text" name="maindoeuvre[]"  size="5" placeholder="Nombre" required/>
            <input type="text" name="maindoeuvre[]" size="9" placeholder="Prix unité" required/><br/>

            <!-- MATERIELS -->
            <button type="button" class="myButton tabletteButton lsf-icon add" id="add">Ajouter</button>
            <button type="button" class="myButton tabletteButton lsf-icon remove" id="remove">Enlever</button><br><br>

            <div id="materiels">
                <div class="materiel">
                    <label class="float">Tarif matériel HT </label>
                    <input type="text" name="dynamic[1][]" id="materiel_1" placeholder="Matériel" size="30" required/>
                    <input type="text" name="dynamic[1][]"  size="5" placeholder="Nombre" required/>
                    <input type="text" name="dynamic[1][]" size="9" placeholder="Prix unité" required/><br/>
                </div>
            </div>

            <!-- <label for="date" class="float">Date d'entrée :</label>
            <input type="date" name="date" id="date" size="30" /> <br /> -->

            <label for="avancement" class="float">Avancement </label>
            <textarea type="text" name="avancement" id="avancement" rows="4" cols="50"></textarea> <br/>
            <label for="etat" class="float">État* </label>
            <select name="etat" required>
                <option value="reception">Réceptionné</option>
                <option value="encours">En cours</option>
                <option value="attente">En attente</option>
                <option value="termine">Terminé</option>
            </select><br/>
            <label for="garantie" class="float">Sous Garantie* </label>
            <select name="garantie" required>
                <option value="oui">Oui</option>
                <option value="non">Non</option>
            </select><br/><br/>
            <input type="submit" value="Créer le SAV">
        </fieldset>
    </form>
</div>
