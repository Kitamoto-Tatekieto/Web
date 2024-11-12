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
?>

<div class="main">
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
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>