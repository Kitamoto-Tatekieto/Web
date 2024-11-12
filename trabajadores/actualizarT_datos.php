<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requiredFields = ['txtCodigo', 'txtTD', 'txtDoc', 'txtNombres', 'txtApellidos', 'txtPst', 'txtTelefono', 'txtDir', 'txtCrr'];
    
    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            die("Error: Todos los campos son obligatorios.");
        }
    }

    $cod = trim($_POST["txtCodigo"]);
    $td = trim($_POST["txtTD"]);
    $dni = trim($_POST["txtDoc"]);
    $nom = trim($_POST["txtNombres"]);
    $ape = trim($_POST["txtApellidos"]);
    $pst = trim($_POST["txtPst"]);
    $tel = trim($_POST["txtTelefono"]);
    $dir = trim($_POST["txtDir"]);
    $crr = trim($_POST["txtCrr"]);
    $contrasena = isset($_POST["txtContrasena"]) ? trim($_POST["txtContrasena"]) : null;

    try {
        require_once("../conexion.php");
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        
        $sql = 'UPDATE trabajador SET TD=:td, DNI=:dni, Nombre=:n, Apellido=:a, Puesto=:p, Correo=:c, Telefono=:t, Direccion=:d';
        

        if (!empty($contrasena)) {
            $contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);
            $sql .= ', Contrasena=:contrasena';
        }
        
        $sql .= ' WHERE DNI=:cod';
        $stmt = $conexion->prepare($sql);

        $stmt->bindParam(":td", $td);
        $stmt->bindParam(":dni", $dni);
        $stmt->bindParam(":n", $nom);
        $stmt->bindParam(":a", $ape);
        $stmt->bindParam(":p", $pst);
        $stmt->bindParam(":c", $crr);
        $stmt->bindParam(":t", $tel);
        $stmt->bindParam(":d", $dir);
        $stmt->bindParam(":cod", $cod);

       
        if (!empty($contrasena)) {
            $stmt->bindParam(":contrasena", $contrasenaHash);
        }
        
        $stmt->execute();

        echo "<script>alert('Registro actualizado correctamente'); window.location.href='mainworkers.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Error al actualizar el registro: " . htmlspecialchars($e->getMessage()) . "'); window.location.href='actualizarT.php';</script>";
    }
}
?>