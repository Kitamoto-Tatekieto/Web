
<?php
require_once("conexion.php");

try{

    $idCliente = $_POST["idCliente"];
    $stmt = $conexion->prepare("SELECT * FROM cliente WHERE DNI = :DNI");
    $stmt->bindParam('DNI', $idCliente, PDO:: PARAM_INT);
    $stmt->execute();

    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    if($cliente){
        $response = array(
            'Nombre'=> $cliente['Nombre'],
            'Apellido'=> $cliente['Apellido'],
            'Id'=> $cliente['Id'],
        );
        echo json_encode($response);

    }else {
        echo json_encode(array('error' => 'Cliente no encontrado'));
    }

} catch (PDOException $e) {

    echo json_encode(array('error' => 'Error de conexión'.$e->getMessage()));

}

?>