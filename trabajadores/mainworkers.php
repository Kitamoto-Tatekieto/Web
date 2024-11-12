<?php
include('../verificar_sesion.php');
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Proyecto Sena / Gestión de Trabajadores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
<?php
    require_once('../conexion.php');
    include("../navigation.html");


    if (isset($_POST['eliminar'])) {
        $id = $_POST['id'];
        $sql = "UPDATE trabajador SET estado = 1 WHERE Id = :id"; 
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        echo "<script>alert('Registro marcado como eliminado con éxito');</script>";
    }

    if (isset($_POST['agregar'])) {
        $td = $_POST['addTD'];
        $dni = $_POST['addDoc'];
        $nombre = $_POST['addNombres'];
        $apellido = $_POST['addApellidos'];
        $puesto = $_POST['addPuesto'];
        $telefono = $_POST['addTelefono'];
        $direccion = $_POST['addDir'];
        $correo = $_POST['addCorreo'];
        $contrasena = password_hash($_POST['addContrasena'], PASSWORD_DEFAULT); 

       
        $sql = "INSERT INTO trabajador (TD, DNI, Nombre, Apellido, Puesto, Telefono, Direccion, Correo, Contrasena, estado) 
                VALUES (:td, :dni, :nombre, :apellido, :puesto, :telefono, :direccion, :correo, :contrasena, 0)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':td', $td);
        $stmt->bindParam(':dni', $dni);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':puesto', $puesto);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':contrasena', $contrasena);
        $stmt->execute();

        echo "<script>alert('Registro agregado con éxito');</script>";
    }
?>
<div class="mothe">
    <div class="main">
        <section class="container-add">
            <button type="button" class="btn btn-1" data-bs-toggle="modal" data-bs-target="#addModal">
                Agregar Trabajador
            </button>
        </section>
        <section class="container-search"> 
            <h3>Lista de trabajadores registrados:</h3>
            <div class="container-table">
                <table class="table table-striped table-success align-middle table-responsive">
                    <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">TD</th>
                        <th scope="col">DNI</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Apellido</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">Dirección</th>
                        <th scope="col">Puesto</th>
                        <th scope="col">Correo</th>
                        <th scope="col">Acciones</th>
                    </tr>
                    </thead>
                    <tbody class="table-group -divider">
                    <?php

                    $SQL = 'SELECT * FROM trabajador WHERE estado = 0';
                    $stmt = $conexion->prepare($SQL);
                    $stmt->execute();
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($rows as $row) {
                        $trabajadorId = $row['Id'];
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['Id']); ?></td>
                            <td><?php echo htmlspecialchars($row['TD']); ?></td>
                            <td><?php echo htmlspecialchars($row['DNI']); ?></td>
                            <td><?php echo htmlspecialchars($row['Nombre']); ?></td>
                            <td><?php echo htmlspecialchars($row['Apellido']); ?></td>
                            <td><?php echo htmlspecialchars($row['Telefono']); ?></td>
                            <td><?php echo htmlspecialchars($row['Direccion']); ?></td>
                            <td><?php echo htmlspecialchars($row['Puesto']); ?></td>
                            <td><?php echo htmlspecialchars($row['Correo']); ?></td>
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $row['Id']; ?>">
                                    <button type="submit" name="eliminar" class="btn btn-2">Eliminar</button>
                                </form>
                                <button type="button" class="btn btn-1" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $trabajadorId; ?>">
                                    Editar
                                </button>
                                <div class="modal fade" id="editModal<?php echo $trabajadorId; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $trabajadorId; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel<?php echo $trabajadorId; ?>">Editar Trabajador</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="actualizarT_datos.php">
                                                    <input type="hidden" name="txtCodigo" value="<?php echo htmlspecialchars($row['DNI']); ?>">
                                                    <div class="form-group">
                                                        <label for="txtTD">Tipo de Documento:</label>
                                                        <select name="txtTD" class="form-control" required>
                                                            <option value="CC" <?php echo $row['TD'] == 'CC' ? 'selected' : ''; ?>>Cédula de Ciudadanía</option>
                                                            <option value="CE" <?php echo $row['TD'] == 'CE' ? 'selected' : ''; ?>>Cédula de Extranjería</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="txtDoc">DNI</label>
                                                        <input type="text" class="form-control" name="txtDoc" required value="<?php echo htmlspecialchars($row['DNI']); ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="txtNombres">Nombre</label>
                                                        <input type="text" class="form-control" name="txtNombres" required value="<?php echo htmlspecialchars($row['Nombre']); ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="txtApellidos">Apellido</label>
                                                        <input type="text" class="form-control" name="txtApellidos" required value="<?php echo htmlspecialchars($row['Apellido']); ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="txtPst">Puesto</label>
                                                        <select name="txtPst" class="form-control" required>
                                                            <option value="AD" <?php echo $row['Puesto'] == 'AD' ? 'selected' : ''; ?>>Administrador</option>
                                                            <option value="EM" <?php echo $row['Puesto'] == 'EM' ? 'selected' : ''; ?>>Empleado</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="txtTelefono">Teléfono</label>
                                                        <input type="text" class="form-control" name="txtTelefono" required value="<?php echo htmlspecialchars($row['Telefono']); ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="txtDir">Dirección</label>
                                                        <input type="text" class="form-control" name="txtDir" required value="<?php echo htmlspecialchars($row['Direccion']); ?>">
                                                    </div>
                                                    <div class="form -group">
                                                        <label for="txtCrr">Correo</label>
                                                        <input type="email" class="form-control" name="txtCrr" required value="<?php echo htmlspecialchars($row['Correo']); ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="txtContrasena">Nueva Contraseña:</label>
                                                        <input type="password" class="form-control" name="txtContrasena">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <input type="submit" class="btn btn-1" value="Actualizar">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </section>

        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Agregar Trabajador</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST">
                            <input type="hidden" name="agregar" value="1">
                            <div class="form-group">
                                <label for="addTD">Tipo de Documento:</label>
                                <select name="addTD" class="form-control" required>
                                    <option value="CC">Cédula de Ciudadanía</option>
                                    <option value="CE">Cédula de Extranjería</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="addDoc">DNI:</label>
                                <input type="text" class="form-control" name="addDoc" required>
                            </div>
                            <div class="form-group">
                                <label for="addNombres">Nombre:</label>
                                <input type="text" class="form-control" name="addNombres" required>
                            </div>
                            <div class="form-group">
                                <label for="addApellidos">Apellido:</label>
                                <input type="text" class="form-control" name="addApellidos" required>
                            </div>
                            <div class="form-group">
                                <label for="addPuesto">Puesto:</label>
                                <select name="addPuesto" class="form-control" required>
                                    <option value="AD">Administrador</option>
                                    <option value="EM">Empleado</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="addTelefono">Teléfono:</label>
                                <input type="text" class="form-control" name="addTelefono" required>
                            </div>
                            <div class="form-group">
                                <label for="addDir">Dirección:</label>
                                <input type="text" class="form-control" name="addDir" required>
                            </div>
                            <div class="form-group">
                                <label for="addCorreo">Correo:</label>
                                <input type="email" class="form-control" name="addCorreo" required>
                            </div>
                            <div class="form-group">
                                <label for="addContrasena">Contraseña:</label>
                                <input type="password" class="form-control" name="addContrasena" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <input type="submit" class="btn btn-1" value="Agregar">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>