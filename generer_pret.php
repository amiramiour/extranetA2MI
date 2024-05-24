<?php
require '../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('defaultFont', 'Courier');
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

session_start();
require_once('connexion/traitement_connexion.php');

function connexionbdd()
{
    global $pdo;
    return $pdo;
}

$db = connexionbdd();

if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin' && isset($_SESSION['mail'])) {
    if (isset($_GET['pret_id'])) {
        $pret_id = $_GET['pret_id'];

        // Récupérer les détails du prêt
        $query = "SELECT p.*, u.users_name AS client_name, u.users_firstname AS client_firstname, 
                         u.users_address, u.users_city, u.users_postcode, u.users_mobile,
                         t.users_name AS tech_name, t.users_firstname AS tech_firstname 
                  FROM pret p
                  JOIN users u ON p.users_id = u.users_id
                  JOIN users t ON p.pret_technicien = t.users_id
                  WHERE p.pret_id = ?";
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $pret_id);
        $stmt->execute();
        $pret = $stmt->fetch(PDO::FETCH_ASSOC);

        // Récupérer l'état du prêt
        $query_etat = "SELECT etat_intitule FROM pret_etat WHERE id_etat_pret = ?";
        $stmt_etat = $db->prepare($query_etat);
        $stmt_etat->bindParam(1, $pret['pret_etat']);
        $stmt_etat->execute();
        $etat_pret = $stmt_etat->fetchColumn();

        ob_start();
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="utf-8">
            <title>A2MI | Fiche Prêt</title>
            <link href="assets/css/vendor.min.css" rel="stylesheet"/>
            <link href="assets/css/transparent/app.min.css" rel="stylesheet"/>
            <link href="assets/css/custom.css" rel="stylesheet"/>
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
                    /*  min-height: 800px; Increased min-height */
                }
                .invoice-company {
                    font-size: 24px;
                    font-weight: bold;
                    margin-bottom: 35px;
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
                    padding: 30px; /* Increased padding */
                    margin-bottom: 20px;
                }
                .section-header {
                    font-size: 18px;
                    color: #cc0066;
                    margin-bottom: 10px;
                }
                .invoice-content {
                    margin-top: 40px; /* Increased margin-top */
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
                    margin-top: 40px; /* Increased margin-top */
                }
                .invoice-footer p {
                    margin: 0;
                }
                .invoice-footer .fw-bold {
                    font-weight: bold;
                }
            </style>
        </head>
        <body>
        <div class="invoice">
            <div class="invoice-company">
                <img src="./img/a2milogo.png" alt="A2MI">
                Prêt n° <?= htmlspecialchars($pret['pret_id']) ?>
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
                        <strong><?= htmlspecialchars($pret['client_name']) . ' ' . htmlspecialchars($pret['client_firstname']) ?></strong><br>
                        <?= htmlspecialchars($pret['users_address']) ?><br>
                        <?= htmlspecialchars($pret['users_city']) . ', ' . htmlspecialchars($pret['users_postcode']) ?><br>
                        Tél: <?= htmlspecialchars($pret['users_mobile']) ?><br>
                    </address>
                </div>
                <div class="invoice-date">
                    <div class="section-header">Dates & Etat</div>
                    <div>
                        <strong>Date de début :</strong> <?= htmlspecialchars($pret['pret_datein']) ?><br>
                        <strong>Date de retour :</strong> <?= htmlspecialchars(date('Y-m-d',$pret['pret_dateout'] )) ?><br>
                    </div>
                    <div>
                        <strong>État :</strong> <?= htmlspecialchars($etat_pret) ?><br>
                    </div>
                </div>
            </div>

            <div class="invoice-content">
                <div class="table-responsive">
                    <table class="table table-invoice">
                        <thead>
                        <tr>
                            <th>Technicien</th>
                            <th>Matériel</th>
                            <th>Caution</th>
                            <th>Mode</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><?= htmlspecialchars($pret['tech_name']) . ' ' . htmlspecialchars($pret['tech_firstname']) ?></td>
                            <td><?= htmlspecialchars($pret['pret_materiel']) ?></td>
                            <td><?= htmlspecialchars($pret['pret_caution']) ?></td>
                            <td><?= htmlspecialchars($pret['pret_mode']) ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="invoice-footer">
                <p class="fw-bold">Merci pour votre confiance.</p>
            </div>
        </div>
        </body>
        </html>
        <?php
        $html = ob_get_clean();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("fiche_pret_" . $pret_id . ".pdf", array("Attachment" => 0));
    } else {
        echo "ID du prêt non fourni.";
    }
} else {
    echo "Accès refusé. Vous devez être un administrateur connecté pour accéder à cette page.";
}
?>
