<?php
include('../verificar_sesion.php');
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Proyecto Sena / Gestión de clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
<?php
    require_once('../conexion.php');
    include("../navigation.html");


    if (isset($_POST['eliminar'])) {
        $id = $_POST['id'];

        $sql = "UPDATE Cliente SET estado = 1 WHERE Id = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            echo "<script>alert('Registro marcado como inactivo con éxito');</script>";
        } else {
            echo "<script>alert('Error al marcar el registro como inactivo');</script>";
        }
    }


    if (isset($_POST['agregar'])) {
        $td = $_POST['addTD'];
        $dni = $_POST['addDoc'];
        $nombre = $_POST['addNombres'];
        $apellido = $_POST['addApellidos'];
        $tg = $_POST["addTG"];
        $telefono = $_POST['addTelefono'];
        $direccion = $_POST['addDir'];
        $correo = $_POST['addCorreo'];

        $sql = "INSERT INTO Cliente (TD, DNI, Nombre, Apellido, Genero, Telefono, Direccion, Correo, estado) VALUES (:td, :dni, :nombre, :apellido, :tg, :telefono, :direccion, :correo, 0)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':td', $td);
        $stmt->bindParam(':dni', $dni);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':tg', $tg);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();

        echo "<script>alert('Registro agregado con éxito');</script>";
    }
?>
<div class="mothe">
    <div class="main">
        <section class="container-add">
            <button type="button" class="btn btn-1" data-bs-toggle="modal" data-bs-target="#addModal">
                Agregar cliente
            </button>
        </section>
        <section class="container-search"> 
            <h3>Lista de clientes registrados:</h3>
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
                        <th scope="col">Correo</th>
                        <th scope="col">Genero</th>
                        <th scope="col">Acciones</th>
                    </tr>
                    </thead>
                    <tbody class="table-group-divider">
                    <?php
                    
                    $SQL = 'SELECT * FROM Cliente WHERE estado = 0';
                    $stmt = $conexion->prepare($SQL);
                    $stmt->execute();
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($rows as $row) {
                        $ClienteId = $row['Id'];
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($ClienteId); ?></td>
                            <td><?php echo htmlspecialchars($row['TD']); ?></td>
                            <td><?php echo htmlspecialchars($row['DNI']); ?></td>
                            <td><?php echo htmlspecialchars($row['Nombre']); ?></td>
                            <td><?php echo htmlspecialchars($row['Apellido']); ?></td>
                            <td><?php echo htmlspecialchars($row['Telefono']); ?></td>
                            <td><?php echo htmlspecialchars($row['Direccion']); ?></td>
                            <td><?php echo htmlspecialchars($row['Correo']); ?></td>
                            <td><?php echo htmlspecialchars($row['Genero']); ?></td>
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $ClienteId; ?>">
                                    <button type="submit" name="eliminar" class="btn btn-2">Eliminar</button>
                                </form>
                                <button type="button" class="btn btn-1" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $ClienteId; ?>">
                                    Editar
                                </button>
                                <div class="modal fade" id="editModal<?php echo $ClienteId; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $ClienteId; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel<?php echo $ClienteId; ?>">Editar cliente</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="actualizarC_datos.php">
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
                                                        <label for="txtTG">Tipo de Genero</label>
                                                        <select name="txtTG" class="form-control" required>
                                                            <option value="MC" <?php echo $row['Genero'] == 'MC' ? 'selected' : ''; ?>>Masculino</option>
                                                            <option value="FM" <?php echo $row['Genero'] == 'FM' ? 'selected' : ''; ?>>Femenino</option>
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
                                                    <div class="form-group">
                                                        <label for="txtCrr">Correo</label>
                                                        <input type="email" class="form-control" name="txtCrr" required value="<?php echo htmlspecialchars($row['Correo']); ?>">
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
                        <h5 class="modal-title" id="addModalLabel">Agregar cliente</h5>
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
                                <label for="addTG">Tipo de Genero</label>
                                <select name="addTG" class="form-control" required>
                                    <option value="MC">Masculino</option>
                                    <option value="FM">Femenino</option>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
