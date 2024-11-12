<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $requiredFields = ['txtCodigo', 'txtTD', 'txtDoc', 'txtNombres', 'txtApellidos', 'txtTG', 'txtTelefono', 'txtDir', 'txtCrr'];
    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            die("Error: Todos los campos son obligatorios.");
        }
    }

    $cod = trim($_POST["txtCodigo"]);
    $td = trim($_POST["txtTD"]);
    $DNI = trim($_POST["txtDoc"]);
    $nom = trim($_POST["txtNombres"]);
    $ape = trim($_POST["txtApellidos"]);
    $tg = trim($_POST["txtTG"]);
    $tel = trim($_POST["txtTelefono"]);
    $dir = trim($_POST["txtDir"]);
    $crr = trim($_POST["txtCrr"]);

    try {
        require_once("../conexion.php");
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
        $sql = 'UPDATE Cliente SET TD=:td, DNI=:dni, Nombre=:n, Apellido=:a, Genero=:tg, Correo=:c, Telefono=:t, Direccion=:d WHERE DNI=:cod';
        $stmt = $conexion->prepare($sql);

    
        $stmt->bindParam(":td", $td);
        $stmt->bindParam(":dni", $DNI);
        $stmt->bindParam(":n", $nom);
        $stmt->bindParam(":a", $ape);
        $stmt->bindParam(":tg", $tg);
        $stmt->bindParam(":c", $crr);
        $stmt->bindParam(":t", $tel);
        $stmt->bindParam(":d", $dir);
        $stmt->bindParam(":cod", $cod);

 
        $stmt->execute();

   
        echo "<script>alert('Registro actualizado correctamente'); window.location.href='mainclientes.php';</script>";
    } catch (PDOException $e) {
    
        echo "<script>alert('Error al actualizar el registro: " . htmlspecialchars($e->getMessage()) . "'); window.location.href='mainclientes.php';</script>";
    }
}
?>
