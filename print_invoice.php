<?php
session_start();
require_once 'vendor/autoload.php'; 

use Dompdf\Dompdf;

include 'Invoice.php';
$invoice = new Invoice();
$invoice->checkLoggedIn();

if (!empty($_GET['invoice_id']) && $_GET['invoice_id']) {
    $invoiceValues = $invoice->getInvoice($_GET['invoice_id']);
    $invoiceItems = $invoice->getInvoiceItems($_GET['invoice_id']);
}

$invoiceDate = date("d/M/Y, H:i:s", strtotime($invoiceValues['order_date']));

$output = '<html><head>';
$output .= '<style>
    body {
        font-family: Arial, sans-serif;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
    }
    th {
        background-color: #f2f2f2;
        text-align: left;
    }
    td {
        text-align: left;
    }
    .header {
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 20px; /* Add margin at the bottom */
    }
    .invoice-info {
        margin-top: 20px;
    }
    .invoice-info table {
        width: 100%;
    }
    .invoice-info td {
        width: 50%;
    }
    .spacer {
        margin-top: 20px;
    }
</style>';
$output .= '</head><body>';
$output .= '<div class="header">Facture</div>';
$output .= '<div class="invoice-info">';
$output .= '<table>
    <tr>
        <td>
            Client,<br />
            Nom Entreprise: ' . $invoiceValues['order_receiver_name'] . '<br /> 
            Address: ' . $invoiceValues['order_receiver_address'] . '<br />
        </td>
        <td>
            Facture N°: ' . $invoiceValues['order_id'] . '<br />
            Facture Date: ' . $invoiceDate . '<br />
        </td>
    </tr>
</table>';
$output .= '</div>';
$output .= '<table>
    <tr>
        <th>N°</th>
        <th>Code Produits</th>
        <th>Libelle</th>
        <th>Quantité</th>
        <th>Prix</th>
        <th>Total</th> 
    </tr>';
$count = 0;
foreach ($invoiceItems as $invoiceItem) {
    $count++;
    $output .= '
    <tr>
        <td>' . $count . '</td>
        <td>' . $invoiceItem["item_code"] . '</td>
        <td>' . $invoiceItem["item_name"] . '</td>
        <td>' . $invoiceItem["order_item_quantity"] . '</td>
        <td>' . $invoiceItem["order_item_price"] . '</td>
        <td>' . $invoiceItem["order_item_final_amount"] . '</td>   
    </tr>';
}
$output .= '</table>';
$output .= '<div class="spacer"></div>'; // Add space between the table and "Total" rows
$output .= '<table>
    <tr>
        <td colspan="5" align="right"><b>Total</b></td>
        <td><b>' . $invoiceValues['order_total_before_tax'] . '</b></td>
    </tr>
    <tr>
        <td colspan="5" align="right"><b>Tax :</b></td>
        <td>' . $invoiceValues['order_tax_per'] . '</td>
    </tr>
    <tr>
        <td colspan="5" align="right">Montant de la Tax :</td>
        <td>' . $invoiceValues['order_total_tax'] . '</td>
    </tr>
    <tr>
        <td colspan="5" align="right">Total TTC:</td>
        <td>' . $invoiceValues['order_total_after_tax'] . '</td>
    </tr>';
$output .= '</table>';
$output .= '</body></html>';

// Create PDF of invoice
$dompdf = new Dompdf();
$dompdf->loadHtml($output);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream('Invoice-' . $invoiceValues['order_id'] . '.pdf', array("Attachment" => false));
?>
