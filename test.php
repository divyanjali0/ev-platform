<?php
ob_start();
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json');

use Dompdf\Dompdf;
use Dompdf\Options;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/db_connect.php';

/* ===============================
   ERROR LOGGING
=============================== */
$logFile = __DIR__ . '/../../assets/includes/booking-errors.log';

function logBooking($message, $reference = 'N/A') {
    global $logFile;
    $date = date('Y-m-d H:i:s');
    $entry = "[{$date}] [REF: {$reference}] {$message}" . PHP_EOL;
    file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
}

try {

    /* ===============================
       READ & VALIDATE INPUT
    =============================== */
    $data = json_decode(file_get_contents("php://input"), true);
    if (!$data) {
        logBooking('Invalid JSON payload');
        throw new Exception("Invalid JSON");
    }

    $required = [
        'slot','vehicleType','hometown',
        'startDate','endDate','name',
        'email','whatsapp','days',
        'pricePerDay','totalPrice'
    ];

    foreach ($required as $field) {
        if (empty($data[$field])) {
            logBooking("Missing required field: {$field}");
            throw new Exception("Missing field: {$field}");
        }
    }

    /* ===============================
       BASE URL
    =============================== */
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $projectRoot = dirname(dirname(dirname($_SERVER['SCRIPT_NAME'])));
    $baseUrl = $protocol . '://' . $_SERVER['HTTP_HOST'] . $projectRoot;

    /* ===============================
       GENERATE REFERENCE
    =============================== */
    $stmt = $conn->prepare("SELECT COUNT(*) FROM reserved_slots WHERE slot_number = :slot");
    $stmt->execute([':slot' => $data['slot']]);
    $count = (int)$stmt->fetchColumn();
    $reference = $data['slot'] . '-AP-' . str_pad($count + 1, 2, '0', STR_PAD_LEFT);

    /* ===============================
       INSERT BOOKING
    =============================== */
    $extras = isset($data['extras']) ? implode(', ', $data['extras']) : '';

    $stmt = $conn->prepare("
        INSERT INTO reserved_slots
        (slot_number, reference_number, vehicle_type, hometown, flight_number,
         start_date, end_date, name, email, whatsapp_number,
         days, price_per_day, total_price, extra_services,
         created_at, updated_at)
        VALUES
        (:slot,:ref,:vehicle,:home,:flight,
         :start,:end,:name,:email,:whatsapp,
         :days,:ppd,:total,:extras,NOW(),NOW())
    ");

    $stmt->execute([
        ':slot'     => $data['slot'],
        ':ref'      => $reference,
        ':vehicle'  => $data['vehicleType'],
        ':home'     => $data['hometown'],
        ':flight'   => $data['flightNumber'] ?? '',
        ':start'    => $data['startDate'],
        ':end'      => $data['endDate'],
        ':name'     => $data['name'],
        ':email'    => $data['email'],
        ':whatsapp' => $data['whatsapp'],
        ':days'     => $data['days'],
        ':ppd'      => $data['pricePerDay'],
        ':total'    => $data['totalPrice'],
        ':extras'   => $extras
    ]);

    logBooking('Booking inserted into database', $reference);

    /* ===============================
       DOMPDF OPTIONS
    =============================== */
    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $options->set('chroot', realpath(__DIR__ . '/../../'));
    $dompdf = new Dompdf($options);

    /* ===============================
       LOGO
    =============================== */
    $logoPath = realpath(__DIR__ . '/../../assets/images/logo.png');
    if (!$logoPath) {
        logBooking('Logo image not found', $reference);
        throw new Exception("Logo image not found");
    }

    /* ===============================
       CALCULATIONS
    =============================== */
    $slotSubtotal = $data['days'] * $data['pricePerDay'];
    $extrasTotal = 0;

    /* ===============================
       HTML
    =============================== */
    $html = "
    <style>
    body { font-family: Cambria; font-size: 12px; margin: 0; padding: 10px; border: 2px solid #105a85ff; }
    .header { text-align: center; margin-bottom: 5px; }
    .header img { max-width: 120px; margin-bottom: 5px; }
    .company-details { text-align: center; font-size: 12px; margin-bottom: 5px; }
    hr { border: 1px solid #000; margin: 5px 0 15px 0; }
    .invoice-title { text-align: center; font-size: 24px; font-weight: bold; margin-bottom: 5px; }
    .reference { text-align: center; font-size: 14px; margin-bottom: 20px; }
    .info-table { width: 100%; margin-bottom: 20px; }
    .info-table td { padding: 5px; vertical-align: top; }
    .slot-table, .extras-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
    .slot-table th, .slot-table td, .extras-table th, .extras-table td { border: 1px solid #ddd; padding: 8px; }
    .slot-table th, .extras-table th { background-color: #f2f2f2; }
    .total-row { font-size: 16px; font-weight: bold; text-align: right; margin-top: 5px; }
    .footer { margin-top: 50px; text-align: center; font-size: 12px; }
    </style>

    <div class='header'>
        <img src='file://{$logoPath}'>
    </div>

    <div class='company-details'>
        <strong>Airport Parking</strong><br>
        No. 371/5, Negombo Road, Seeduwa, Sri Lanka<br>
        info@airportparking.lk | +94 76 141 4557
    </div>

    <hr>

    <div class='invoice-title'>INVOICE</div>
    <div class='reference'>Reference: {$reference}</div>

    <table class='info-table'>
        <tr>
            <td>
                <strong>Customer Details:</strong><br>
                {$data['name']}<br>
                {$data['email']}<br>
                {$data['whatsapp']}
            </td>
            <td>
                <strong>Booking Details:</strong><br>
                Flight: {$data['flightNumber']}<br>
                {$data['startDate']} - {$data['endDate']}
            </td>
        </tr>
    </table>

    <table class='slot-table'>
        <tr>
            <th>Slot</th>
            <th>Days</th>
            <th>Price / Day</th>
            <th>Subtotal</th>
        </tr>
        <tr>
            <td>{$data['slot']}</td>
            <td>{$data['days']}</td>
            <td>{$data['pricePerDay']}</td>
            <td>{$slotSubtotal}</td>
        </tr>
    </table>
    ";

    if (!empty($data['extras'])) {
        $html .= "<table class='extras-table'><tr><th>Extra</th><th>Price</th></tr>";
        foreach ($data['extras'] as $extra) {
            $html .= "<tr><td>{$extra}</td><td>1000</td></tr>";
            $extrasTotal += 1000;
        }
        $html .= "</table>";
    }

    $grandTotal = $slotSubtotal + $extrasTotal;

    $html .= "
    <div class='total-row'>Total: LKR {$grandTotal}</div>
    <div class='footer'>Thank you for choosing Airport Parking Services</div>
    ";

    /* ===============================
       PDF RENDER
    =============================== */
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A5', 'portrait');
    $dompdf->render();

    /* ===============================
       SAVE PDF
    =============================== */
    $invoiceDir = __DIR__ . '/../../assets/invoices/';
    if (!is_dir($invoiceDir)) {
        mkdir($invoiceDir, 0777, true);
    }

    $pdfFile = "Invoice_{$reference}.pdf";
    $pdfPath = $invoiceDir . $pdfFile;
    file_put_contents($pdfPath, $dompdf->output());

    $stmt = $conn->prepare("UPDATE reserved_slots SET pdf_path = :p WHERE reference_number = :r");
    $stmt->execute([
        ':p' => realpath($pdfPath),
        ':r' => $reference
    ]);

    logBooking('PDF generated successfully', $reference);

    /* ===============================
       RESPONSE
    =============================== */
    ob_clean();
    echo json_encode([
        'success'   => true,
        'reference' => $reference,
        'pdf_url'   => $baseUrl . '/assets/invoices/' . $pdfFile
    ]);

} catch (Throwable $e) {

    logBooking(
        $e->getMessage() . ' | File: ' . $e->getFile() . ' | Line: ' . $e->getLine(),
        $reference ?? 'N/A'
    );

    ob_clean();
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'error'   => 'Server error'
    ]);
}
