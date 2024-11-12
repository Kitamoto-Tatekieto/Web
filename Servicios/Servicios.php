<?php
include('../verificar_sesion.php');
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Proyecto Sena / Gestión de Servicios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
<?php
    require_once('../conexion.php');
    include("../navigation.html");

    
    if (isset($_POST['eliminar'])) {
        $id = $_POST['id'];
        
     
        $sql = "UPDATE servicio SET estado = 1 WHERE id = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

       
        $sql = "UPDATE servicio_producto SET estado = 1 WHERE serv_id = :serv_id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':serv_id', $id);
        $stmt->execute();

        echo "<script>alert('Registro marcado como inactivo con éxito');</script>";
    }

    
    if (isset($_POST['agregar'])) {
        $nombre = $_POST['addNombre'];
        $valor = $_POST['addValor'];
        $detalles = $_POST['addDetalles'];
        $productos = $_POST['addProductos'];

        $sql = "INSERT INTO servicio (Nombre, Valor, Detalles, estado) VALUES (:nombre, :valor, :detalles, 0)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':valor', $valor);
        $stmt->bindParam(':detalles', $detalles);
        $stmt->execute();
        $servicioId = $conexion->lastInsertId();

        foreach ($productos as $producto) {
            $sql = "INSERT INTO servicio_producto (serv_id, id_producto, estado) VALUES (:serv_id, :id_producto, 0)";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':serv_id', $servicioId);
            $stmt->bindParam(':id_producto', $producto);
            $stmt->execute();
        }

        echo "<script>alert('Registro agregado con éxito');</script>";
    }
?>

<div class="main">
    <section class="container-add">
        <button type="button" class="btn btn-1" data-bs-toggle="modal" data-bs-target="#addModal">
            Agregar Servicio
        </button>
    </section>
    <section class="container-search"> 
        <h3>Lista de servicios registrados:</h3>
        <div class="container-table">
            <table class="table table-striped table-success align-middle table-responsive">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Valor</th>
                    <th scope="col">Detalles</th>
                    <th scope="col">Productos utilizados</th>
                    <th scope="col">Acciones</th>
                </tr>
                </thead>
                <tbody class="table-group-divider">
                <?php

                $SQL = 'SELECT * FROM servicio WHERE estado = 0';
                $stmt = $conexion->prepare($SQL);
                $stmt->execute();
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($rows as $row) {
                    $servicioId = $row['id'];
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['Nombre']); ?></td>
                        <td><?php echo htmlspecialchars($row['Valor']); ?></td>
                        <td><?php echo htmlspecialchars($row['Detalles']); ?></td>
                        <td>
                            <?php
                            $sql = "SELECT p.id, p.nom_pro, p.desc_pro
                            FROM producto p
                            INNER JOIN servicio_producto sp ON p.id = sp.id_producto
                            WHERE sp.serv_id = :serv_id AND sp.estado = 0";
                            $stmt = $conexion->prepare($sql);
                            $stmt->bindParam(':serv_id', $servicioId);
                            $stmt->execute();
                            $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($productos as $producto) {
                                echo "<p>Producto: " . htmlspecialchars($producto['nom_pro']) . " (" . htmlspecialchars($producto['desc_pro']) . ")</p>";
                            }
                            ?>
                        </td>
                        <td>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="eliminar" class="btn btn-2">Eliminar</button>
                            </form>                            <button type="button" class="btn btn-1" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $servicioId; ?>">
                                Editar
                            </button>

                            <div class="modal fade" id="editModal<?php echo $servicioId; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $servicioId; ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel<?php echo $servicioId; ?>">Editar Servicio</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" action="actualizarServicio.php">
                                                <input type="hidden" name="txtCodigo" value="<?php echo htmlspecialchars($row['id']); ?>">
                                                <div class="form-group">
                                                    <label for="txtNombre">Nombre:</label>
                                                    <input type="text" class="form-control" name="txtNombre" required value="<?php echo htmlspecialchars($row['Nombre']); ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="txtValor">Valor:</label>
                                                    <input type="number" class="form-control" name="txtValor" required value="<?php echo htmlspecialchars($row['Valor']); ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="txtDetalles">Detalles:</ label>
                                                    <input type="text" class="form-control" name="txtDetalles" required value="<?php echo htmlspecialchars($row['Detalles']); ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label>Productos utilizados:</label><br>
                                                    <?php
                                                    $sql = "SELECT id, nom_pro FROM producto";
                                                    $stmt = $conexion->prepare($sql);
                                                    $stmt->execute();
                                                    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                                    foreach ($productos as $producto) {
                                                        $checked = '';
                                                        $sql = "SELECT * FROM servicio_producto WHERE serv_id = :serv_id AND id_producto = :id_producto AND estado = 0";
                                                        $stmt = $conexion->prepare($sql);
                                                        $stmt->bindParam(':serv_id', $servicioId);
                                                        $stmt->bindParam(':id_producto', $producto['id']);
                                                        $stmt->execute();

                                                        if ($stmt->rowCount() > 0) {
                                                            $checked = 'checked';
                                                        }

                                                        echo "<div class='form-check'>
                                                                <input class='form-check-input' type='checkbox' name='txtProductos[]' value='" . $producto['id'] . "' id='editProducto" . $producto['id'] . "' $checked>
                                                                <label class='form-check-label' for='editProducto" . $producto['id'] . "'>
                                                                    " . htmlspecialchars($producto['nom_pro']) . "
                                                                </label>
                                                              </div>";
                                                    }
                                                    ?>
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
                    <h5 class="modal-title" id="addModalLabel">Agregar Servicio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <input type="hidden" name="agregar" value="1">
                        <div class="form-group">
                            <label for="addNombre">Nombre:</label>
                            <input type="text" class="form-control" name="addNombre" required>
                        </div>
                        <div class="form-group">
                            <label for="addValor">Valor:</label>
                            <input type="number" class="form-control" name="addValor" required>
                        </div>
                        <div class="form-group">
                            <label for="addDetalles">Detalles:</ label>
                            <input type="text" class="form-control" name="addDetalles" required>
                        </div>
                        <div class="form-group">
                            <label>Productos utilizados:</label><br>
                            <?php
                            $sql = "SELECT id, nom_pro FROM producto";
                            $stmt = $conexion->prepare($sql);
                            $stmt->execute();
                            $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($productos as $producto) {
                                echo "<div class='form-check'>
                                        <input class='form-check-input' type='checkbox' name='addProductos[]' value='" . $producto['id'] . "' id='producto" . $producto['id'] . "'>
                                        <label class='form-check-label' for='producto" . $producto['id'] . "'>
                                            " . htmlspecialchars($producto['nom_pro']) . "
                                        </label>
                                      </div>";
                            }
                            ?>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
