<?php
require_once("conexion.php");

try {
    $codigoServicio = $_POST["codigoServicio"];
    $stmt = $conexion->prepare("SELECT * FROM servicio WHERE id = :codigoServicio");
    $stmt->bindParam(':codigoServicio', $codigoServicio, PDO::PARAM_INT); 
    $stmt->execute();

    $servicio = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($servicio) {
        echo json_encode(array(
            'id' => $servicio['id'],
            'nombre' => $servicio['Nombre'], 
            'valor' => $servicio['Valor'], 
            'detalles' => $servicio['Detalles'] 
        ));
    } else {
        echo json_encode(array('error' => 'Servicio no encontrado'));
    }

} catch (PDOException $e) {
    echo json_encode(array('error' => 'Error de conexión: ' . $e->getMessage()));
}
?>