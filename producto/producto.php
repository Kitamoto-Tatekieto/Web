<?php
include('../verificar_sesion.php');
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Proyecto Sena / Gestión de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
<?php
    require_once('../conexion.php');
    include("../navigation.html");

    if (isset($_POST['eliminar'])) {
        $id = $_POST['id'];
        $sql = "UPDATE producto SET estado = 1 WHERE id = :id";  // Cambiar estado a inactivo
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            echo "<script>alert('Registro marcado como inactivo con éxito');</script>";
        } else {
            echo "<script>alert('Error al marcar el registro como inactivo');</script>";
        }
    }

    if (isset($_POST['agregar'])) {
        $nom_pro = $_POST['addNomPro'];
        $desc_pro = $_POST['addDescPro'];
        $tipo_pro = $_POST['addTipoPro'];
        $valor_pro = $_POST['addValorPro'];
        $cant_pro = $_POST['addCantPro'];

        $sql = "INSERT INTO producto (nom_pro, desc_pro, tipo_pro, valor_pro, cant_pro, estado) VALUES (:nom_pro, :desc_pro, :tipo_pro, :valor_pro, :cant_pro, 0)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':nom_pro', $nom_pro);
        $stmt->bindParam(':desc_pro', $desc_pro);
        $stmt->bindParam(':tipo_pro', $tipo_pro);
        $stmt->bindParam(':valor_pro', $valor_pro);
        $stmt->bindParam(':cant_pro', $cant_pro);
        $stmt->execute();
        echo "<script>alert('Registro agregado con éxito');</script>";
    }
?>

<div class="mothe">
    <div class="main">
        <section class="container-add">
            <button type="button" class="btn btn-1" data-bs-toggle="modal" data-bs-target="#addModal">
                Agregar Producto
            </button>
        </section>
        <section class="container-search"> 
            <h3>Lista de productos registrados:</h3>
            <div class="container-table">
                <table class="table table-striped table-success align-middle table-responsive">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Tipo</th>
                            <th>Valor</th>
                            <th>Cantidad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                    <?php
                   
                    $SQL = 'SELECT * FROM producto WHERE estado = 0'; 
                    $stmt = $conexion->prepare($SQL);
                    $stmt->execute();
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($rows as $row) {
                        $productoId = $row['id'];
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['nom_pro']); ?></td>
                            <td><?php echo htmlspecialchars($row['desc_pro']); ?></td>
                            <td><?php echo htmlspecialchars($row['tipo_pro']); ?></td>
                            <td><?php echo htmlspecialchars($row['valor_pro']); ?></td>
                            <td><?php echo htmlspecialchars($row['cant_pro']); ?></td>
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="eliminar" class="btn btn-2">Eliminar</button>
                                </form>
                                <button type="button" class="btn btn-1" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $productoId; ?>">
                                    Editar
                                </button>

                                <div class="modal fade" id="editModal<?php echo $productoId; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $productoId; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Editar Producto</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="actualizarPr_datos.php">
                                                    <input type="hidden" name="txtCodigo" value="<?php echo htmlspecialchars($row['id']); ?>">
                                                    <div class="form-group">
                                                        <label>Nombre del Producto:</label>
                                                        <input type="text" class="form-control" name="txtNomPro" required value="<?php echo htmlspecialchars($row['nom_pro']); ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Descripción:</label>
                                                        <input type="text" class="form-control" name="txtDescPro" required value="<?php echo htmlspecialchars($row['desc_pro']); ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Tipo:</label>
                                                        <input type="text" class="form-control" name="txtTipoPro" required value="<?php echo htmlspecialchars($row['tipo_pro']); ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Valor:</label>
                                                        <input type="number" class="form-control" name="txtValorPro" required value="<?php echo htmlspecialchars($row['valor_pro']); ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Cantidad:</label>
                                                        <input type="number" class="form-control" name="txtCantPro" required value="<?php echo htmlspecialchars($row['cant_pro']); ?>">
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
                        <h5 class="modal-title">Agregar Producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST">
                            <input type="hidden" name="agregar" value="1">
                            <div class="form-group">
                                <label>Nombre del Producto:</label>
                                <input type="text" class="form-control" name="addNomPro" required>
                            </div>
                            <div class="form-group">
                                <label>Descripción:</label>
                                <input type="text" class="form-control" name="addDescPro" required>
                            </div>
                            <div class="form-group">
                                <label>Tipo:</label>
                                <input type="text" class="form-control" name="addTipoPro" required>
                            </div>
                            <div class="form-group">
                                <label>Valor:</label>
                                <input type="number" class="form-control" name="addValorPro" required>
                            </div>
                            <div class="form-group">
                                <label>Cantidad:</label>
                                <input type="number" class="form-control" name="addCantPro" required>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
