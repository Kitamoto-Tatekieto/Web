<?php
include('../verificar_sesion.php');
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de Reserva</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: rgb(209, 240, 199);
            margin: 0;
            padding: 0;
        }
        .container {
            margin-top: 20px;
            padding: 30px;
            background-color: whitesmoke;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        h3 {
            color: #013220;
            margin-bottom: 20px;
            font-size: 2rem;
            font-weight: bold;
            text-align: center;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 10px;
            background-color: rgb(174, 200, 166);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        p {
            color: #6c757d;
            font-size: 1.125rem;
            margin-bottom: 20px;
            text-align: center;
        }
        .input-group {
            padding: 10px;
            background-color: rgb(139, 160, 133);
            border: 1px solid #013220;
        }
        .input-group input {
            border-radius: 8px 0 0 8px;
            border: 1px solid #013220;
            font-size: 1rem;
            padding: 10px;
            width: 100%;
        }
        .input-group button {
            border-radius: 0 8px 8px 0;
            border: 1px solid rgb(139, 160, 133);
            background-color: rgb(174, 200, 166);
            color: black;
            font-size: 1rem;
        }
        table {
            margin-top: 20px;
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid rgb(53, 87, 75);
            padding: 12px;
            text-align: center;
            font-size: 1rem;
        }
        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tbody tr:hover {
            background-color: #e9ecef;
        }
        .btn-custom {
            background-color: rgb(254, 196, 174);
            color: rgb(204, 108, 100);
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }
    </style> 
</head>
<body>
<?php
include("../navigation.html");
?>

<div class="container">
    <h3>Registro de Reserva</h3>
    <p>Por favor ingrese todos los datos de su reserva</p>

    <div class="input-group mb-3">
        <input type="text" class="form-control" id="idCliente" placeholder="Ingrese el número de identificación">
        <button class="btn btn-outline-secondary" onclick="consultarCliente();">Buscar</button>
    </div>
    <input type="text" readonly class="form-control-plaintext" id="nombreCliente" value="Cliente">
    
    <div class="input-group mb-3">
        <input type="text" readonly class="form-control-plaintext" id ="nombreTrabajador" value="<?php echo $_SESSION['usuario']; ?>">
    </div>

    <div class="input-group mb-3">
        <input type="date" class="form-control" id="fechaReserva" placeholder="Fecha de Reserva" required>
    </div>

    <div class="input-group mb-3">
        <input type="time" class="form-control" id="horaReserva" placeholder="Hora de Reserva" required>
    </div> 
    <h4>Agregar un servicio:</h4>
    <div class="input-group mb-3">
        <input type="text" class="form-control" id="txtCantidad" placeholder="Cantidad">
    </div>
    <div class="input-group mb-3">
        <input type="text" class="form-control" id="codigoServicio" placeholder="Ingrese el ID del servicio">
        <button class="btn btn-outline-secondary" onclick="buscarServicio();">Buscar</button>
    </div>

    <h4>Servicios seleccionados</h4>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Código</th>
                <th scope="col">Nombre</th>
                <th scope="col">Valor</th>
                <th scope="col">Detalles</th>
                <th scope="col">Subtotal</th>
                <th scope="col">Cantidad</th>
                <th scope="col">Eliminar</th>
            </tr>
        </thead>
        <tbody id="resultadoServicio"></tbody>
    </table>

    <h5 id="subtotal">Sub Total:</h5>
    <h5 id="iva">IVA %:</h5>
    <h5 id="total">Total Reserva:</h5>
    <button id="guardarReservaBtn" onclick="guardarReserva()">Guardar Reserva</button>
</div>

<script>
    var id = 0;
    var idReserva = 0;
    var Iva = 0;
    var Total = 0;
    var SubtotalGeneral = 0;

    function consultarCliente() {
        var idCliente = document.getElementById("idCliente").value;
        $.ajax({
            url: 'consultar_cliente.php',
            method: 'POST',
            data: { idCliente: idCliente },
            dataType: 'json',
            success: function(data) {
                if (data.error) {
                    alert(data.error);
                } else {
                    document.getElementById("nombreCliente").value = data.Nombre + " " + data.Apellido;
                    id = data.Id;
                }
            }
        });
    }

    function buscarServicio() {
        var codigoServicio = document.getElementById("codigoServicio").value;
        var cant = document.getElementById("txtCantidad").value;

        $.ajax({
            url: 'buscar_servicio.php',
            method: 'POST',
            data: { codigoServicio: codigoServicio },
            dataType: 'json',
            success: function(data) {
                if (data.error) {
                    alert(data.error);
                } else if (data.estado == 0) {
                    alert("No hay cantidad suficiente de este servicio o el servicio ya se ha agotado.");
                } else {
                    var resultadoServicio = document.getElementById("resultadoServicio");
                    var servicioExistente = false;

                    var filas = resultadoServicio.getElementsByTagName("tr");
                    for (var i = 0; i < filas.length; i++) {
                        var celdas = filas[i].getElementsByTagName("td");
                        if (celdas[0] && celdas[0].innerText == data.id) {
                            servicioExistente = true;
                            break;
                        }
                    }

                    if (!servicioExistente) {
                        var fila = document.createElement("tr");
                        let subTotal = data.valor * cant;
                        SubtotalGeneral += subTotal;
                        Iva = SubtotalGeneral * 0.16;
                        Total = SubtotalGeneral + Iva;

                        fila.setAttribute('data-servicio-id', data.id);
                        fila.innerHTML = "<td>" + data.id + "</td>" +
                                         "<td>" + data.nombre + "</td>" +
                                         "<td>" + data.valor + "</td>" +
                                         "<td>" + data.detalles + "</td>" +
                                         "<td>" + subTotal.toFixed(2) + "</td>" +
                                         "<td>" + cant + "</td>" +
                                         "<td><button class='btn btn-danger btn-sm' onclick='eliminarServicio(this);'>Eliminar</button></td>";

                        resultadoServicio.appendChild(fila);

                        document.getElementById("subtotal").innerText = "Sub Total: " + SubtotalGeneral.toFixed(2);
                        document.getElementById("iva").innerText = "IVA 19%: " + Iva.toFixed( 2);
                        document.getElementById("total").innerText = "Total Reserva: " + Total.toFixed(2);
                    } else {
                        alert("El servicio ya está en la tabla.");
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX: ", status, error);
            }
        });
    }

    function eliminarServicio(button) {
        var fila = button.parentElement.parentElement;
        var subTotal = parseFloat(fila.children[4].innerText); 

        
        SubtotalGeneral -= subTotal;
        Iva = SubtotalGeneral * 0.16;
        Total = SubtotalGeneral + Iva;


        document.getElementById("subtotal").innerText = "Sub Total: " + SubtotalGeneral.toFixed(2);
        document.getElementById("iva").innerText = "IVA 19%: " + Iva.toFixed(2);
        document.getElementById("total").innerText = "Total Reserva: " + Total.toFixed(2);

     
        fila.remove();
    }

    function guardarReserva() {
        console.log("Función guardarReserva() llamada");

        if (id === 0) {
            console.log("Variable id es igual a 0");
            alert("Debe seleccionar un cliente antes de guardar la reserva.");
            return;
        }

        var servicios = [];
        var filas = document.getElementById("resultadoServicio").getElementsByTagName("tr");
        for (var i = 0; i < filas.length; i++) {
            var celdas = filas[i].getElementsByTagName("td");
            servicios.push({
                servicio_id: celdas[0].innerText,
                cantidad: celdas[5].innerText,
                Valor: parseFloat(celdas[2].innerText) 
            });
        }


        var fechaReserva = document.getElementById("fechaReserva").value;
        var horaReserva = document.getElementById("horaReserva").value;
        var trabajador_id = <?php echo $_SESSION['usuario_id']; ?>; 

        console.log("Arreglo servicios:");
        console.log(servicios);

        $.ajax({
            url: 'guardar_reserva.php',
            method: 'POST',
            data: {
                cliente_id: id,
                servicios: servicios,
                fecha_reserva: fechaReserva,
                hora: horaReserva,
                trabajador_id: trabajador_id
            },
            dataType: 'json',
            success: function(data) {
                console.log("Respuesta del servidor:");
                console.log(data);
                if (data.success) {
                    alert("Reserva guardada correctamente.");
                } else {
                    alert("Error al guardar la reserva: " + data.error);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX: ", status, error);
            }
        });
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>