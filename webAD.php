
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/styles.css">
    <style>
        .card {
            box-shadow: 2px 2px 10px gray;
            background-color: var(--color4-light); /* Cambiar fondo de la tarjeta a verde */
        }
        .card-title {
            color: black; /* Cambiar color del título a negro */
        }
        .card-text {
            color: var(--color1);
        }
        .btn-primary {
            background-color: var(--color2);
            border-color: var(--color2);
        }
        .btn-primary:hover {
            background-color: var(--color2-dark);
            border-color: var(--color2-dark);
        }
        body {
            background-image: url('styles/Fondo.png');
            background-size: cover; /* Ajustar la imagen para cubrir todo el fondo */
            background-repeat: no-repeat; /* Evitar que la imagen se repita */
            
 
        }
        
    </style>
</head>
<body style="background-image: url('styles/Fondo.png'); background-size: cover; background-position: center;">
<?php
include('verificar_sesion.php');
include('conexion.php'); // Asegúrate de que este archivo establece la conexión a la base de datos

// Consultas para obtener los totales
try {
    $total_trabajadores = $conexion->query("SELECT COUNT(*) AS total FROM trabajador")->fetch(PDO::FETCH_ASSOC)['total'];
    $total_clientes = $conexion->query("SELECT COUNT(*) AS total FROM cliente")->fetch(PDO::FETCH_ASSOC)['total'];
    $total_productos = $conexion->query("SELECT COUNT(*) AS total FROM producto")->fetch(PDO::FETCH_ASSOC)['total'];
    $total_servicios = $conexion->query("SELECT COUNT(*) AS total FROM servicio")->fetch(PDO::FETCH_ASSOC)['total'];
    $total_reservas = $conexion->query("SELECT COUNT(*) AS total FROM reserva")->fetch(PDO::FETCH_ASSOC)['total'];
} catch (PDOException $e) {
    echo "Error en la consulta: " . $e->getMessage();
    exit;
}
?>
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="webAD.php">Elenaspa</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="trabajadores/mainworkers.php">Gestionar trabajadores</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Cliente/mainclientes.php">Gestionar Cliente</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="producto/producto.php">Producto</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Servicios/Servicios.php">Gestionar Servicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Reserva/nueva_venta.php">Gestionar Reserva</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Reserva/ConsultarR.php">Reservas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="salir.php">Salir</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card" style="width: 18rem;">
                <img src ="styles/masajista.png" class="card-img-top" alt="Trabajadores">
                <div class="card-body">
                    <h5 class="card-title">Total Trabajadores</h5>
                    <p class="card-text">Número total de trabajadores: <?php echo $total_trabajadores; ?></p>
                    <a href="trabajadores/mainworkers.php" class="btn btn-primary">Gestionar Trabajadores</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card" style="width: 18rem;">
                <img src="styles/facial.png" class="card-img-top" alt="Clientes">
                <div class="card-body">
                    <h5 class="card-title">Total Clientes</h5>
                    <p class="card-text">Número total de clientes: <?php echo $total_clientes; ?></p>
                    <a href="Cliente/mainclientes.php" class="btn btn-primary">Gestionar Clientes</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card" style="width: 18rem;">
                <img src="styles/locion.png" class="card-img-top" alt="Productos">
                <div class="card-body">
                    <h5 class="card-title">Total Productos</h5>
                    <p class="card-text">Número total de productos: <?php echo $total_productos; ?></p>
                    <a href="producto/producto.php" class="btn btn-primary">Gestionar Productos</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card" style="width: 18rem;">
                <img src="styles/spa.png" class="card-img-top" alt="Servicios">
                <div class="card-body">
                    <h5 class="card-title">Total Servicios</h5>
                    <p class="card-text">Número total de servicios: <?php echo $total_servicios; ?></p>
                    <a href="Servicios/Servicios.php" class="btn btn-primary">Gestionar Servicios</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card" style="width: 18rem;">
                <img src="styles/reserva.png" class="card-img-top" alt="Reservas">
                <div class="card-body">
                    <h5 class="card-title">Total Reservas</h5>
                    <p class="card-text">Número total de reservas: <?php echo $total_reservas; ?></p>
                    <a href="Reserva/nueva_venta.php" class="btn btn-primary">Gestionar Reservas</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4ClPpBbD+IiUt2q6vzFsQFQ+0jxzwQ+T7f3VqzGzH/lUfsqslbuzASf4lyJKiLa0IFT+T8tXL3XHJNc" crossorigin="anonymous"></script>
</body>
</html>