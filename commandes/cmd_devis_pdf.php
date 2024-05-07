<?php
include '../gestion_session.php';
require_once '../config.php';
include '../ConnexionBD.php';
include '../navbar.php';

require ('../vendor/setasign/fpdf/fpdf.php');

$pdo = connexionbdd();
if (isset($_GET['id'])) {
    $sql_cmd = "SELECT c.membre_id, m.membre_nom AS nom_client , 
                m.membre_prenom, c.cmd_devis_id, c.cmd_devis_reference, c.cmd_devis_designation, 
                c.cmd_devis_datein, c.cmd_devis_dateout, c.cmd_devis_prixventettc, 
                l.membre_nom AS nom_technicien,
                c.cmd_devis_dateSouhait, e.cmd_devis_etat, c.cmd_devis_etat
                FROM commande_devis c JOIN membres m ON m.membre_id = c.membre_id 
                JOIN membres l ON c.cmd_devis_technicien = l.membre_id 
                JOIN cmd_devis_etats e ON c.cmd_devis_etat = e.id_etat_cmd_devis
                WHERE c.membre_id =" . $_GET['id'];
    $liste_cmd = $pdo->query($sql_cmd);
    $i = 0;
    $data_cmd = array();

    while ($tab = $liste_cmd->fetch(PDO::FETCH_ASSOC)) {
        $data_cmd[$i] = $tab;
        $i++;
    }
    var_dump($data_cmd);
}
function tronquer($texte, $max_caracteres) {

    // Test si la longueur du texte dépasse la limite
    if (strlen($texte) > $max_caracteres) {

        // Séléction du maximum de caractères
        $texte = substr($texte, 0, $max_caracteres);

        // Récupération de la position du dernier espace (afin déviter de tronquer un mot)
        $position_espace = strrpos($texte, " ");
        $texte = substr($texte, 0, $position_espace);

        // Ajout des "..."$texte = $texte."...";}?
        $texte = $texte . "...";
    }

    //on retourne le texte
    return $texte;
}
class PDF extends FPDF
{
    // Variable pour le titre
    private $titre;

    // Constructeur
    function __construct($titre)
    {
        parent::__construct();
        $this->titre = $titre;
        $this->SetFont('Arial', '', 12); // Définition de la police par défaut
    }

    // En-tête
    function Header()
    {
        // Logo
        $this->Image('../images/a2mi_3.jpg', 10, 6, 30);
        $this->Cell(80);

        // Arial gras 15
        $this->SetFont('Arial', 'B', 15);

        // Calcul de la largeur du titre et positionnement
        $w = $this->GetStringWidth($this->titre) + 6;

        // Couleurs du cadre, du fond et du texte
        $this->SetDrawColor(200, 53, 108);
        $this->SetFillColor(219, 219, 220);
        $this->SetTextColor(200, 53, 108);

        // Epaisseur du cadre (mm)
        $this->SetLineWidth(0.5);

        // Titre
        $this->Cell($w, 9, $this->titre, 1, 1, 'C', false);

        // Saut de ligne
        $this->Ln(10);
    }

    // Titre du chapitre
    function TitreChapitre($libelle)
    {
        // Arial 12
        $this->SetFont('Arial', '', 12);

        // Couleur de fond
        $this->SetFillColor(219, 219, 220);

        // Titre
        $this->Cell(0, 6, "Liste des $libelle", 0, 1, 'L', true);

        // Saut de ligne
        $this->Ln(4);
    }

    // Tableau pour les commandes
    function FancyTable_cmd($header, $data)
    {
        // Couleurs, épaisseur du trait et police grasse
        $this->SetFillColor(200, 53, 108);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(.3);
        $this->SetFont('', 'B');

        // En-tête
        $w = array(45, 45, 90, 30, 30, 30);
        for ($i = 0; $i < count($header); $i++) $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
        $this->Ln();

        // Restauration des couleurs et de la police
        $this->SetFillColor(237, 237, 237);
        $this->SetTextColor(0);
        $this->SetFont('');

        // Données
        $fill = false;
        foreach ($data as $row) {
            $this->Cell($w[0], 6, mb_convert_encoding(stripslashes($row['cmd_devis_reference']), 'ISO-8859-1'), 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 6, $this->tronquer(mb_convert_encoding(stripslashes($row['cmd_devis_designation']), 'ISO-8859-1'), 50), 'LR', 0, 'L', $fill);
            $this->Cell($w[3], 6, $row['cmd_devis_prixventettc'] . chr(128), 'LR', 0, 'L', $fill);
            $this->Cell($w[4], 6, $this->mepd($row['cmd_devis_datein']), 'LR', 0, 'L', $fill);
            $this->Cell($w[5], 6, $this->mepd($row['cmd_devis_dateout']), 'LR', 0, 'R', $fill);
            $this->Ln();
            $fill = !$fill;
        }

        // Trait de terminaison
        $this->Cell(array_sum($w), 0, '', 'T');
    }

    function mepd($date) {
        $date = date_create($date);
        return date_format($date, 'd/m/Y');
    }

    // Fonction pour tronquer le texte
    function tronquer($texte, $longueur)
    {
        if (strlen($texte) > $longueur) {
            $texte = substr($texte, 0, $longueur) . '...';
        }
        return $texte;
    }

    function AjouterChapitre($titre) {
        $this->Ln(10);
        $this->TitreChapitre($titre);
        $this->Ln();
    }

    // Pied de page
    function Footer() {

        // Positionnement à 1,5 cm du bas
        $this->SetY(-15);

        // Police Arial 8
        $this->SetFont('Arial', '', 8);

        // Numéro de page
        $this->Cell(0, 5, mb_convert_encoding('EURL A2MI au capital de 6000','ISO-8859-1') . chr(128) . mb_convert_encoding(' - 10/14 rue Jean Perrin 17000 LA ROCHELLE - Tél : 09 51 52 42 86 - Fax : 09 56 52 42 86 ','ISO-8859-1'), 0, 1, 'C');
        $this->Cell(0, 5, mb_convert_encoding('RCS  LA ROCHELLE B 751 763 962 - SIRET 751 763 962 00014 - NACE 6209Z - TVA intra FR91751763962','ISO-8859-1'), 0, 1, 'C');
        $this->Cell(0, 5, mb_convert_encoding('sav@a2mi-info.fr - www.a2mi-info.com','ISO-8859-1'), 0, 1, 'C');
    }
}
$pdf = new PDF('L');
$titre = mb_convert_encoding('Récapitulatif du client ' . $data_cmd['membre_prenom'] . ' ' . $data_cmd['nom_client'],'ISO-8859-1');
$pdf->AddPage();
$pdf->SetTitle($titre);

$header_cmd = array(mb_convert_encoding('Référence','ISO-8859-1'), mb_convert_encoding('Désignation','ISO-8859-1'), 'Prix HT', mb_convert_encoding('Date d\'entrée','ISO-8859-1'), 'Livraison');
$pdf->AjouterChapitre('commandes');
$pdf->FancyTable_cmd($header_cmd, $data_cmd);
$terminaison = 'commande';

$pdf->Output('recapitulatif_commandes_devis_client_n' . $_GET['id'] . '_' . mb_strtoupper($sql['membre_nom'], "UTF-8") . '_' . $terminaison . '.pdf', 'I');

