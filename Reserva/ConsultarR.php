<?php include('../verificar_sesion.php'); ?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Consulta de Reservas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
<?php 
require_once('../conexion.php'); 
include("../navigation.html");

if (isset($_POST['eliminar'])) {
    $id = $_POST['id'];
    
    $sql = "UPDATE reserva SET estado = 1 WHERE id = :id";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $sql = "UPDATE detalle_reserva SET estado = 1 WHERE reserva_id = :reserva_id";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':reserva_id', $id);
    $stmt->execute();

    echo "<script>alert('Reserva marcada como inactiva con éxito');</script>";
}

// Cambiar estado de pago
if (isset($_POST['cambiar_estado'])) {
    $id = $_POST['id'];
    $nuevoEstado = $_POST['nuevo_estado'];

    $sql = "UPDATE reserva SET estado_pago = :nuevoEstado WHERE id = :id";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':nuevoEstado', $nuevoEstado);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    echo "<script>alert('Estado de pago actualizado con éxito');</script>";
}

$fechaCreacionInicio = isset($_POST['fechaCreacionInicio']) ? $_POST['fechaCreacionInicio'] : '';
$fechaCreacionFin = isset($_POST['fechaCreacionFin']) ? $_POST['fechaCreacionFin'] : '';
$dni_cliente = isset($_POST['dni_cliente']) ? $_POST['dni_cliente'] : '';
$dni_trabajador = isset($_POST['dni_trabajador']) ? $_POST['dni_trabajador'] : '';

$SQL = "
    SELECT r.id, r.cliente_id, r.subtotal, r.iva, r.total, r.fecha_reserva, r.hora, r.trabajador_id, r.fecha_creacion,
           r.estado_pago,  -- Asegúrate de que este campo exista en tu base de datos
           GROUP_CONCAT(CONCAT_WS(':', s.id, s.nombre, s.valor, dr.cantidad) SEPARATOR '; ') AS servicios
    FROM reserva r
    LEFT JOIN detalle_reserva dr ON r.id = dr.reserva_id
    LEFT JOIN servicio s ON dr.servicio_id = s.id
    LEFT JOIN cliente c ON r.cliente_id = c.id
    LEFT JOIN trabajador t ON r.trabajador_id = t.id
    WHERE r.estado = 0  -- Solo reservas activas
";

// Filtrar por DNI del cliente
if (!empty($dni_cliente)) {
    $SQL .= " AND c.dni = :dni_cliente";
}

// Filtrar por DNI del trabajador
if (!empty($dni_trabajador)) {
    $SQL .= " AND t.dni = :dni_trabajador";
}

// Si se presiona el botón "Hoy"
if (isset($_POST['hoy'])) {
    $fechaHoy = date('Y-m-d');
    $SQL .= " AND r.fecha_creacion = :fechaHoy"; // Asegúrate de que esto sea correcto
} elseif (!empty($fechaCreacionInicio) && !empty($fechaCreacionFin)) {
    $SQL .= " AND r.fecha_creacion BETWEEN :fechaCreacionInicio AND :fechaCreacionFin";
}

$SQL .= " GROUP BY r.id";

$stmt = $conexion->prepare($SQL);

// Vincular parámetros según la condición
if (!empty($dni_cliente)) {
    $stmt->bindParam(':dni_cliente', $dni_cliente);
}
if (!empty($dni_trabajador)) {
    $stmt->bindParam(':dni_trabajador', $dni_trabajador);
}
if (isset($_POST['hoy'])) {
    $stmt->bindParam(':fechaHoy', $fechaHoy); // Vinculando la fecha de hoy
} elseif (!empty($fechaCreacionInicio) && !empty($fechaCreacionFin)) {
    $stmt->bindParam(':fechaCreacionInicio', $fechaCreacionInicio);
    $stmt->bindParam(':fechaCreacionFin', $fechaCreacionFin);
}

$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Contar reservas del día de hoy
$countReservasHoy = 0;
if (isset($_POST['hoy'])) {
    $countSQL = "SELECT COUNT(*) as total FROM reserva WHERE estado = 0 AND fecha_creacion = :fechaHoy";
    $countStmt = $conexion->prepare($countSQL);
    $countStmt->bindParam(':fechaHoy', $fechaHoy);
    $countStmt->execute();
    $countReservasHoy = $countStmt->fetchColumn();
}
?>

<div class="container">
    <h3 >Lista de Reservas Registradas</h3>
    <form method="post" class="mb-3">
        <div class="row">
            <div class="col">
                <input type="date" name="fechaCreacionInicio" class="form-control" value="<?php echo htmlspecialchars($fechaCreacionInicio); ?>" placeholder="Fecha Creación Inicio">
            </div>
            <div class="col">
                <input type="date" name="fechaCreacionFin" class="form-control" value="<?php echo htmlspecialchars($fechaCreacionFin); ?>" placeholder="Fecha Creación Fin">
            </div>
            <div class="col-auto d-flex align-items-end">
                <button type="submit" class="btn btn-1 me-1">Buscar</button>
                <button type="submit" name="hoy" class="btn btn-2">Hoy <?php if (isset($_POST['hoy'])): ?> (<?php echo $countReservasHoy; ?>) <?php endif; ?></button>
            </div>
        </div>
    </form>

    <form method="post" class="mb-3">
        <div class="row">
            <div class="col">
                <input type="text" name="dni_cliente" class="form-control" value="<?php echo htmlspecialchars($dni_cliente); ?>" placeholder="DNI Cliente">
            </div>
            <div class="col">
                <input type="text" name="dni_trabajador" class="form-control" value="<?php echo htmlspecialchars($dni_trabajador); ?>" placeholder="DNI Trabajador">
            </div>
            <div class="col-auto d-flex align-items-end">
                <button type="submit" class="btn btn-1">Buscar</button>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped table-success align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Subtotal</th>
                    <th>IVA</th>
                    <th>Total</th>
                    <th>Reserva</th>
                    <th>Hora</th>
                    <th>Trabajador</th>
                    <th>Creación</th>
                    <th>Servicios</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['cliente_id']); ?></td>
                    <td><?php echo htmlspecialchars(number_format($row ['subtotal'], 2)); ?></td>
                    <td><?php echo htmlspecialchars(number_format($row['iva'], 2)); ?></td>
                    <td><?php echo htmlspecialchars(number_format($row['total'], 2)); ?></td>
                    <td><?php echo htmlspecialchars($row['fecha_reserva']); ?></td>
                    <td><?php echo htmlspecialchars($row['hora']); ?></td>
                    <td><?php echo htmlspecialchars($row['trabajador_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['fecha_creacion']); ?></td>
                    <td>
                        <button type="button" class="btn btn-2" data-bs-toggle="modal" data-bs-target="#serviciosModal<?php echo $row['id']; ?>">
                            Ver Servicios
                        </button>

                        <div class="modal fade" id="serviciosModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="serviciosModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"> Servicios de la Reserva ID: <?php echo $row['id']; ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <?php 
                                        if (!empty($row['servicios'])) {
                                            $servicios = explode('; ', $row['servicios']);
                                            echo "<ul>";
                                            foreach ($servicios as $servicio) {
                                                list($servicioId, $nombre, $valor , $cantidad) = explode(':', $servicio);
                                                echo "<li>ID: $servicioId, Nombre: $nombre, Valor: $valor, Cantidad: $cantidad</li>";
                                            }
                                            echo "</ul>";
                                        } else {
                                            echo "No hay servicios asociados.";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <?php 
                        $estadoPago = $row['estado_pago'] == 'Paga' ? 'Paga' : 'No paga';
                        ?>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="nuevo_estado" value="<?php echo $estadoPago == 'Paga' ? 'No paga' : 'Paga'; ?>">
                            <button type="submit" name="cambiar_estado" class="btn btn-<?php echo $estadoPago == 'Paga' ? '2' : '1'; ?>"><?php echo $estadoPago; ?></button>
                        </form>
                    </td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="eliminar" class="btn btn-2">Eliminar</button>
                        </form>
                        <a href="factura.php?id=<?php echo $row['id']; ?>" class="btn btn-1" target="_blank">Imprimir </a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>