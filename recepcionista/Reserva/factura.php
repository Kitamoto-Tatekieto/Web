<?php
require '../../vendor/autoload.php';
require_once("conexion.php");
use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_GET['id'])) {
    die('ID de reserva no especificado.');
}

$reserva_id = $_GET['id'];


$sql = "
    SELECT r.id, r.cliente_id, r.subtotal, r.iva, r.total, r.fecha_reserva, r.hora, r.trabajador_id, r.fecha_creacion,
           GROUP_CONCAT(CONCAT_WS(':', s.id, s.nombre, s.valor, dr.cantidad) SEPARATOR '; ') AS servicios
    FROM reserva r
    LEFT JOIN detalle_reserva dr ON r.id = dr.reserva_id
    LEFT JOIN servicio s ON dr.servicio_id = s.id
    WHERE r.id = :reserva_id
    GROUP BY r.id
";

$stmt = $conexion->prepare($sql);
$stmt->bindParam(':reserva_id', $reserva_id);
$stmt->execute();
$reserva = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reserva) {
    die('Reserva no encontrada.');
}


$options = new Options();
$options->set('defaultFont', 'Arial');
$dompdf = new Dompdf($options);


$html = '
<!DOCTYPE html>
<html>
<head>
    <title>Factura de Reserva</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .factura {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
        }
        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="factura">
        <h1>Factura de Reserva</h1>
        <p><strong>ID Reserva:</strong> ' . htmlspecialchars($reserva['id']) . '</p>
        <p><strong>Cliente ID:</strong> ' . htmlspecialchars($reserva['cliente_id']) . '</p>
        <p><strong>Subtotal:</strong> $' . htmlspecialchars(number_format($reserva['subtotal'], 2)) . '</p>
        <p><strong>IVA:</strong> $' . htmlspecialchars(number_format($reserva['iva'], 2)) . '</p>
        <p><strong>Total:</strong> $' . htmlspecialchars(number_format($reserva['total'], 2)) . '</p>
        <p><strong>Fecha Reserva:</strong> ' . htmlspecialchars($reserva['fecha_reserva']) . '</p>
        <p><strong>Hora:</strong> ' . htmlspecialchars($reserva['hora']) . '</p>
        <p><strong>Trabajador ID:</strong> ' . htmlspecialchars($reserva['trabajador_id']) . '</p>
        <table>
            <tr>
                <th>ID Servicio</th>
                <th>Nombre</th>
                <th>Valor</th>
                <th>Cantidad</th>
            </tr>';

$servicios = explode('; ', $reserva['servicios']);
foreach ($servicios as $servicio) {
    list($servicioId, $nombre, $valor, $cantidad) = explode(':', $servicio);
    $html .= '
            <tr>
                <td>' . htmlspecialchars($servicioId) . '</td>
                <td>' . htmlspecialchars($nombre) . '</td>
                <td>$' . htmlspecialchars(number_format($valor, 2)) . '</td>
                <td>' . htmlspecialchars($cantidad) . '</td>
            </tr>';
}

$html .= '
        </table>
    </div >
</body>
</html>
';


$dompdf->loadHtml($html);
$dompdf->render();
$dompdf->stream('factura_' . $reserva['id'] . '.pdf', array('Attachment' => 0)); 
?>