<?php
include('../verificar_sesion.php');
require_once('../conexion.php');
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Proyecto Sena / Editar Datos de Trabajador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
<?php
include("../navigation.html");

// Obtener el ID del trabajador de la sesión
$trabajadorId = $_SESSION['usuario_id']; // Asegúrate de que el ID del trabajador esté almacenado en la sesión

// Si se envía el formulario de actualización
if (isset($_POST['actualizar'])) {
    $td = $_POST['txtTD'];
    $dni = $_POST['txtDoc'];
    $nombre = $_POST['txtNombres'];
    $apellido = $_POST['txtApellidos'];
    $puesto = $_POST['txtPst'];
    $telefono = $_POST['txtTelefono'];
    $direccion = $_POST['txtDir'];
    $correo = $_POST['txtCrr'];
    $contrasena = $_POST['txtContrasena'] ? password_hash($_POST['txtContrasena'], PASSWORD_DEFAULT) : null;

    // Actualizar el trabajador
    $sql = "UPDATE trabajador SET TD = :td, DNI = :dni, Nombre = :nombre, Apellido = :apellido, Puesto = :puesto, Telefono = :telefono, Direccion = :direccion, Correo = :correo" . ($contrasena ? ", Contrasena = :contrasena" : "") . " WHERE Id = :id";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':td', $td);
    $stmt->bindParam(':dni', $dni);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':apellido', $apellido);
    $stmt->bindParam(':puesto', $puesto);
    $stmt->bindParam(':telefono', $telefono);
    $stmt->bindParam(':direccion', $direccion);
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':id', $trabajadorId);
    
    if ($contrasena) {
        $stmt->bindParam(':contrasena', $contrasena);
    }

    $stmt->execute();
    echo "<script>alert('Datos actualizados con éxito');</script>";
}

// Obtener los datos del trabajador
$sql = "SELECT * FROM trabajador WHERE Id = :id";
$stmt = $conexion->prepare($sql);
$stmt->bindParam(':id', $trabajadorId);
$stmt->execute();
$trabajador = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="main">
    <section class="container-edit">
        <h3>Editar Mis Datos</h3>
        <form method="POST">
            <div class="form-group">
                <label for="txtTD">Tipo de Documento:</label>
                <select name="txtTD" class="form-control" required>
                    <option value="CC" <?php echo $trabajador['TD'] == 'CC' ? 'selected' : ''; ?>>Cédula de Ciudadanía</option>
                    <option value="CE" <?php echo $trabajador['TD'] == 'CE' ? 'selected' : ''; ?>>Cédula de Extranjería</option>
                </select>
            </div>
            <div class="form-group">
                <label for="txtDoc">DNI:</label>
                <input type="text" class="form-control" name="txtDoc" required value="<?php echo htmlspecialchars($trabajador['DNI']); ?>">
            </div>
            <div class="form-group">
                <label for="txtNombres">Nombre:</label>
                <input type="text" class="form-control" name="txtNombres" required value="<?php echo htmlspecialchars($trabajador['Nombre']); ?>">
            </div>
            <div class="form-group">
                <label for="txtApellidos">Apellido:</label>
                <input type="text" class="form-control" name="txtApellidos" required value="<?php echo htmlspecialchars($trabajador['Apellido']); ?>">
            </div>
            <div class="form-group">
                <label for="txtPst">Puesto:</label>
                <select name="txtPst" class="form-control" required>
                    <option value="AD" <?php echo $trabajador['Puesto'] == 'AD' ? 'selected' : ''; ?>>Administrador</option>
                    <option value="EM" <?php echo $trabajador['Puesto'] == 'EM' ? 'selected' : ''; ?>>Empleado</option>
                </select>
            </div>
            <div class="form-group">
                <label for="txtTelefono">Teléfono:</label>
                <input type="text" class="form-control" name="txtTelefono" required value="<?php echo htmlspecialchars($trabajador['Telefono']); ?>">
            </div>
            <div class="form-group">
                <label for="txtDir">Dirección:</label>
                <input type="text" class="form-control" name="txtDir" required value="<?php echo htmlspecialchars($trabajador['Direccion']); ?>">
            </div>
            <div class="form-group">
                <label for="txtCrr">Correo:</label>
                <input type="email" class="form-control" name="txtCrr" required value="<?php echo htmlspecialchars($trabajador['Correo']); ?>">
            </div>
            <div class="form-group">
                <label for="txtContrasena">Nueva Contraseña:</label>
                <input type="password" class="form-control" name="txtContrasena">
            </div>
            <div class="modal-footer">
                <input type="submit" class="btn btn-1" name="actualizar" value="Actualizar">
            </div>
        </form>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>