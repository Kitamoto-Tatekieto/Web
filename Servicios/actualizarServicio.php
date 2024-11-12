<?php
require_once('../conexion.php');

if (isset($_POST['txtCodigo'])) {
    $id = $_POST['txtCodigo'];
    $nombre = $_POST['txtNombre'];
    $valor = $_POST['txtValor'];
    $detalles = $_POST['txtDetalles'];
    $productos = $_POST['txtProductos'];

    $sql = "UPDATE servicio SET Nombre = :nombre, Valor = :valor, Detalles = :detalles WHERE id = :id"; 
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':valor', $valor);
    $stmt->bindParam(':detalles', $detalles);
    $stmt->execute();


    $sql = "DELETE FROM servicio_producto WHERE serv_id = :id"; 
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();


    foreach ($productos as $producto) {
        $sql = "INSERT INTO servicio_producto (serv_id, id_producto) VALUES (:serv_id, :id_producto)"; 
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':serv_id', $id); 
        $stmt->bindParam(':id_producto', $producto);
        $stmt->execute();
    }

    echo "<script>alert('Registro actualizado con éxito');</script>";
    header('Location: Servicios.php');
}
?>