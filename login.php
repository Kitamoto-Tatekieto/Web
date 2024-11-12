<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title> Login </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="login, formulario de acceso html">
    <link rel="stylesheet" href="styles/log.css">
</head>
<body>
    <div class="contenedor">
        <h2>ElenaSpa</h2>
        <div id="contenedorcentrado">
            <div id="login">
                <form action="login.php" method="POST" id="loginform">
                    <label class="label" for="txt1">Usuario</label>
                    <input class="input" id="usuario" type="text" name="t1" placeholder="Usuario" required>
                    
                    <label class="label" for="txt1">Contraseña</label>
                    <input class="input" id="password" type="password" placeholder="Contraseña" name="t2" required>
                    
                    <button type="submit">Ingresar</button>
                </form>
            </div>
        </div>
    </div>
    
    <?php
    if ($_POST) { 
        session_start(); 
        require('Conexion.php'); 
        $u = $_POST['t1']; 
        $p = $_POST['t2']; 
        
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
        
        $query = $conexion->prepare("SELECT * FROM trabajador WHERE Nombre = :u"); 
        $query->bindParam(":u", $u); 
        $query->execute(); 
        
        $usuario = $query->fetch(PDO::FETCH_ASSOC); 
        
        if ($usuario) { 
            if (password_verify($p, $usuario["Contrasena"])) { 
                $_SESSION["usuario"] = $usuario["Nombre"]; 
                $_SESSION["usuario_id"] = $usuario["Id"];  
                $_SESSION["Puesto"] = $usuario["Puesto"];
                
                if ($usuario["Puesto"] == "EM") { 
                    header("location: webEM.php"); 
                } elseif ($usuario["Puesto"] == "AD") { 
                    header("location: webAD.php"); 
                } 
            } else { 
                echo "Usuario o contraseña inválidos"; 
            } 
        } else { 
            echo "Usuario o contraseña inválidos"; 
        } 
    } 
    ?>
</body>
</html>