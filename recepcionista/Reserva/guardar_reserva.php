<?php
require_once("conexion.php");

header('Content-Type: application/json'); 

date_default_timezone_set('America/Bogota');

try {
    
    if (!isset($_POST['cliente_id']) || !isset($_POST['servicios']) || !isset($_POST['fecha_reserva']) || !isset($_POST['hora']) || !isset($_POST['trabajador_id'])) {
        echo json_encode(array('success' => false, 'error' => 'Datos incompletos.'));
        exit;
    }

    $cliente_id = $_POST['cliente_id'];
    $servicios = $_POST['servicios'];
    $fecha_reserva = $_POST['fecha_reserva']; 
    $hora = $_POST['hora']; 
    $trabajador_id = $_POST['trabajador_id']; 


    $subtotal = 0;
    $iva = 0;
    $total = 0;

    
    foreach ($servicios as $servicio) {
        if (isset($servicio['cantidad']) && isset($servicio['Valor'])) {
            $subtotal += $servicio['cantidad'] * $servicio['Valor'];
        } else {
            echo json_encode(array('success' => false, 'error' => 'Error: Falta ' . (isset($servicio['cantidad']) ? 'Valor' : 'cantidad') . ' en el servicio.'));
            exit;
        }
    }

    $iva = $subtotal * 0.16; 
    $total = $subtotal + $iva;

 
    $fecha_creacion = date('Y-m-d H:i:s'); 

    $conexion->beginTransaction();

    
    $stmt = $conexion->prepare("INSERT INTO reserva (cliente_id, subtotal, iva, total, fecha_reserva, hora, trabajador_id, fecha_creacion) VALUES (:cliente_id, :subtotal, :iva, :total, :fecha_reserva, :hora, :trabajador_id, :fecha_creacion)");
    $stmt->bindParam(':cliente_id', $cliente_id);
    $stmt->bindParam(':subtotal', $subtotal);
    $stmt->bindParam(':iva', $iva);
    $stmt->bindParam(':total', $total);
    $stmt->bindParam(':fecha_reserva', $fecha_reserva); 
    $stmt->bindParam(':hora', $hora); 
    $stmt->bindParam(':trabajador_id', $trabajador_id); 
    $stmt->bindParam(':fecha_creacion', $fecha_creacion); 
    $stmt->execute();
    

    $reserva_id = $conexion->lastInsertId();

    
    foreach ($servicios as $servicio) {
        $stmt = $conexion->prepare("INSERT INTO detalle_reserva (reserva_id, servicio_id, cantidad) VALUES (:reserva_id, :servicio_id, :cantidad)");
        $stmt->bindParam(':reserva_id', $reserva_id);
        $stmt->bindParam(':servicio_id', $servicio['servicio_id']);
        $stmt->bindParam(':cantidad', $servicio['cantidad']);
        $stmt->execute();
    }


    $conexion->commit();

    echo json_encode(array('success' => true, 'reserva_id' => $reserva_id));
} catch (PDOException $e) {
    
    $conexion->rollBack();
    echo json_encode(array('success' => false, 'error' => 'Error al guardar la reserva : ' . $e->getMessage()));
}
?>