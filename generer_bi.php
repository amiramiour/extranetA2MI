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
    if (isset($_GET['bi_id'])) {
        $bi_id = $_GET['bi_id'];

// Récupérer les détails du BI
        $sql_bi = "SELECT * FROM bi WHERE bi_id = ?";
        $stmt_bi = $db->prepare($sql_bi);
        $stmt_bi->bindParam(1, $bi_id);
        $stmt_bi->execute();
        $bi = $stmt_bi->fetch(PDO::FETCH_ASSOC);

        if ($bi) {
// Récupérer les détails du client associé au bon d'intervention
            $sql_client = "SELECT * FROM users WHERE users_id = ?";
            $stmt_client = $db->prepare($sql_client);
            $stmt_client->bindParam(1, $bi['users_id']);
            $stmt_client->execute();
            $client = $stmt_client->fetch(PDO::FETCH_ASSOC);

// Récupérer les détails du technicien associé au bon d'intervention
            $sql_technicien = "SELECT * FROM users WHERE users_id = ?";
            $stmt_technicien = $db->prepare($sql_technicien);
            $stmt_technicien->bindParam(1, $bi['bi_technicien']);
            $stmt_technicien->execute();
            $technicien = $stmt_technicien->fetch(PDO::FETCH_ASSOC);

// Récupérer les détails des tâches d'intervention
            $sql_taches = "SELECT * FROM intervention WHERE bi_id = ?";
            $stmt_taches = $db->prepare($sql_taches);
            $stmt_taches->bindParam(1, $bi['bi_id']);
            $stmt_taches->execute();
            $taches = $stmt_taches->fetchAll(PDO::FETCH_ASSOC);


            if ($client && $technicien) {
                ob_start();
                ?>
                <!DOCTYPE html>
                <html lang="fr">
                <head>
                    <meta charset="utf-8">
                    <title>A2MI | Fiche BI</title>
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
                            /*  min-height: 20px; Increased min-height */

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
                            padding: 30px;
                            margin-bottom: 20px;
                        }

                        .section-header {
                            font-size: 18px;
                            color: #cc0066;
                            margin-bottom: 10px;
                        }

                        .invoice-content {
                            margin-top: 40px;
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
                            margin-top: 40px;
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
                        <img src="./img/a2milogo.png" alt="A2MI
">
                        Bon d'intervention n° <?= htmlspecialchars($bi['bi_id']) ?>
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
                                <strong><?= htmlspecialchars($client['users_name']) . ' ' . htmlspecialchars($client['users_firstname']) ?></strong><br>
                                <?= htmlspecialchars($client['users_address']) ?><br>
                                <?= htmlspecialchars($client['users_city']) . ', ' . htmlspecialchars($client['users_postcode']) ?>
                                <br>
                                Tél: <?= htmlspecialchars($client['users_mobile']) ?>
                            </address>
                        </div>
                        <div class="invoice-date">
                            <div class="section-header">Date</div>

                            <div>
                                <strong>Date d'intrevention :</strong> <?= htmlspecialchars(date('Y-m-d', $bi['bi_date'])) ?>

                            </div>
                        </div>
                    </div>

                    <div class="invoice-tasks">
                        <div class="section-header">Détails de l'intervention</div>
                        <?php foreach ($taches as $tache) : ?>
                            <div class="task-details">
                                <p><strong>Intervention:</strong> <?= htmlspecialchars($tache['inter_intervention']) ?></p>
                                <p><strong>Nombre de pièces:</strong> <?= htmlspecialchars($tache['inter_nbpiece']) ?></p>
                                <?php
                                $prix_total_piece = $tache['inter_nbpiece'] * $tache['inter_prixunit']; // Calcul du prix total de la tâche
                                ?>
                                <p><strong>Prix total:</strong> <?= htmlspecialchars($prix_total_piece) ?>€</p>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="invoice-content">
                        <div class="table-responsive">
                            <table class="table table-invoice">
                                <thead>
                                <tr>
                                    <th>Technicien</th>
                                    <th>Paiement</th>
                                    <th>Reglé</th>
                                    <th>Facturé</th>
                                    <th>Garantie</th>
                                    <th>Contrat</th>
                                    <th>Service</th>

                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><?= htmlspecialchars($technicien['users_name']) . ' ' . htmlspecialchars($technicien['users_firstname']) ?></td>
                                    <td><?= htmlspecialchars($bi['bi_paiement'])  ?></td>
                                    <td><?= htmlspecialchars($bi['bi_regle'])  ?></td>
                                    <td><?= htmlspecialchars($bi['bi_facture'])  ?></td>
                                    <td><?= htmlspecialchars($bi['bi_garantie'])  ?></td>
                                    <td><?= htmlspecialchars($bi['bi_contrat'])  ?></td>
                                    <td><?= htmlspecialchars($bi['bi_service'])  ?></td>


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
                $dompdf->stream("fiche_bi_" . $bi_id . ".pdf", array("Attachment" => 0));
            } else {
                echo "Aucun client ou technicien trouvé associé au bon d'intervention.";
            }
        } else {
            echo "Aucun bon d'intervention trouvé avec l'ID $bi_id";
        }
    } else {
        echo "ID du bon d'intervention non fourni.";
    }
} else {
    echo "Accès refusé. Vous devez être un administrateur connecté pour accéder à cette page.";
}
?>
