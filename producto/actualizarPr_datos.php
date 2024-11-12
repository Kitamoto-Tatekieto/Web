<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requiredFields = ['txtCodigo', 'txtNomPro', 'txtDescPro', 'txtTipoPro', 'txtValorPro', 'txtCantPro'];
    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            die("Error: Todos los campos son obligatorios.");
        }
    }

    $cod = trim($_POST["txtCodigo"]);
    $nom = trim($_POST["txtNomPro"]);
    $desc = trim($_POST["txtDescPro"]);
    $tipo = trim($_POST["txtTipoPro"]);
    $valor = trim($_POST["txtValorPro"]);
    $cant = trim($_POST["txtCantPro"]);

    try {
        require_once("../conexion.php");
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'UPDATE producto SET nom_pro=:nom, desc_pro=:desc, tipo_pro=:tipo, valor_pro=:valor, cant_pro=:cant WHERE id=:cod';
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(":nom", $nom);
        $stmt->bindParam(":desc", $desc);
        $stmt->bindParam(":tipo", $tipo);
        $stmt->bindParam(":valor", $valor);
        $stmt->bindParam(":cant", $cant);
        $stmt->bindParam(":cod", $cod);
        $stmt->execute();
        echo "<script>alert('Registro actualizado correctamente'); window.location.href='producto.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Error al actualizar el registro: " . htmlspecialchars($e->getMessage()) . "'); window.location.href='producto.php';</script>";
    }
}
?>
