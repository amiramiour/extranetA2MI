<?php
require '../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

function initializeDompdf() {
    $options = new Options();
    $options->set('defaultFont', 'Courier');
    $options->set('isRemoteEnabled', true);
    return new Dompdf($options);
}

function fetchCommandeData() {
    // Inclure les fichiers nécessaires et récupérer les données
    require_once('includes/_functions.php');
    require_once('connexion/traitement_connexion.php');
    require_once('includes/_requests_cmd_devis.php');
    // Suppose que $commande et $produits sont définis ici
    return [$commande, $produits];
}

function generatePdfContent($commande, $produits) {
    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>A2MI | Fiche</title>
        <link href="assets/css/vendor.min.css" rel="stylesheet" />
        <link href="assets/css/transparent/app.min.css" rel="stylesheet" />
        <link href="assets/css/custom.css" rel="stylesheet" />
        
        <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #333333;
        }
        .invoice {
            padding: 20px;
            background: white;
            border-radius: 5px;
            margin: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .invoice-company {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 60px;
            color: #cc0066;
            text-align: center;
            position: relative;
            border-radius: 5px;
            padding: 20px;
        }
        .invoice-company img {
            position: absolute;
            top: -10px;
            left: 10px;
            width: 50px;
            height: 50px;
        }
        
        .invoice-number {
            font-size: 18px;
            position: absolute;
            top: -10px;
            right: 10px;
            background-color: #cc0066;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .invoice-header > div {
            flex-basis: 30%;
        }
        .invoice-from,
        .invoice-to {
            border: 2px solid #cc0066;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .section-header {
            font-size: 18px;
            color: #cc0066;
            margin-bottom: 10px;
        }
        .invoice-content {
            margin-top: 20px;
        }
        .table-invoice {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            margin-left: auto;
            margin-right: auto;
        }
        .table-invoice th {
            background-color: #cc0066;
            color: white;
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .table-invoice td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .invoice-footer {
            text-align: center;
            margin-top: 20px;
        }
        .invoice-footer p {
            margin: 0;
        }
        .invoice-footer .fw-bold {
            font-weight: bold;
        }
        .price-grid .price-label {
            text-align: left;
            font-weight: bold;
        }
        .price-grid .price-value {
            text-align: right;
            font-weight: bold;
        }
    </style>
    </head>
    <body>
    <div class="invoice">
        <div class="invoice-company">
            <img src="logo.png" alt="Logo A2MI">
            
            <div class="invoice-number">Devis n° <?= $commande['cmd_devis_id']; ?></div>
        </div>

        <div class="invoice-header">
            <div class="invoice-from">
                <div class="section-header">De</div>
                <address>
                    <strong>A2MI-Informatique</strong><br>
                    10-14 rue Jean Perrin<br>
                    17000 La Rochelle<br>
                    Tél : 09 51 52 42 86
                </address>
            </div>
            <div class="invoice-to">
                <div class="section-header">À</div>
                <address>
                    <strong><?= $commande['nom_client'] . ' ' . $commande['client_prenom'] ?></strong><br>
                    <?= $commande['users_address'] ?><br>
                    <?php if (!empty($commande['users_address_compl'])): ?>
                        <?= $commande['users_address_compl'] ?><br>
                    <?php endif; ?>
                    <?= $commande['users_postcode'] . ' ' . $commande['users_city'] ?><br>
                    Tél: <?= $commande['users_mobile'] ?><br>
                </address>
            </div>
            <div class="invoice-dates">
                <div class="section-header"></div>
                <div class="invoice-date">
                    <strong>Créé le :</strong>
                    <span><?= date('Y/m/d', $commande['cmd_devis_datein']) ?></span>
                </div>
                <div class="invoice-date">
                    <strong>Date de livraison :</strong>
                    <span><?= date('Y/m/d', $commande['cmd_devis_dateout']) ?></span>
                </div>
                <div class="invoice-date">
                    <strong>Date de livraison souhaitée :</strong>
                    <span><?= date('Y/m/d', $commande['cmd_devis_dateSouhait']) ?></span>
                </div>
                <div class="invoice-date">
                    <strong>État :</strong>
                    <span><?= $commande['cmd_devis_etat'] ?></span>
                </div>
            </div>
        </div>
            
        <div class="invoice-content">
            <div class="table-responsive">
                <table class="table table-invoice">
                    <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Fournisseur</th>
                        <th>pvHT</th>
                        <th>pvTTC</th>
                        <th>Statut</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($produits as $produit): ?>
                        <tr>
                            <td><?= $produit['reference'] ?></span> </td>
                            <td><?= $produit['fournisseurs_name'] ?></td>
                            <td><?= $produit['pvHT'] ?></td>
                            <td><?= $produit['pvTTC'] ?></td>
                            <td><?= $produit['etat'] == "commande" ? "Commandé" : "Reçu" ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="m-3">
                <strong>Total de la commande</strong>
                <div class="invoice-price">
                    <div class="price-grid">
                        <div class="price-label">Prix de vente HT</div>
                        <div class="price-value"><?= $commande['cmd_devis_prixHT'] ?> €</div>
                    </div>

                    <div class="price-grid">
                        <div class="price-label">TVA</div>
                        <div class="price-value">20 %</div>
                    </div>

                    <div class="price-grid">
                        <div class="price-label">Prix TTC</div>
                        <div class="price-value"><?= $commande['cmd_devis_prixventettc'] ?> €</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="invoice-footer">
            <p class="text-center mb-5px fw-bold">
                MERCI DE NOUS FAIRE CONFIANCE
            </p>
            <p class="text-center">
                <span class="me-10px"><i class="fa fa-fw fa-lg fa-globe"></i> https://www.a2mi-info.com</span>
                <span class="me-10px"><i class="fa fa-fw fa-lg fa-phone-volume"></i> 09 51 52 42 86</span>
                <span class="me-10px"><i class="fa fa-fw fa-lg fa-envelope"></i> contact@a2mi-info.fr</span>
            </p>
        </div>
    </div>
    </body>
    </html>
    <?php
    return ob_get_clean();
}

session_start();

if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin' && isset($_SESSION['mail'])) {
    list($commande, $produits) = fetchCommandeData();
    
    $dompdf = initializeDompdf();
    $html = generatePdfContent($commande, $produits);

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("commande_".$commande['cmd_devis_id'].".pdf", ["Attachment" => false]);
} else {
    echo 'Accès refusé.';
}
?>
