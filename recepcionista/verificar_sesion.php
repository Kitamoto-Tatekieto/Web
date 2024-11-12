<?php
session_start();

if (!isset($_SESSION["usuario"]) || empty($_SESSION["usuario"]) || empty($_SESSION["Puesto"])) {
    header("location: /Elenaspa/login.php");
    exit;
}

if ($_SESSION["Puesto"] != "EM") {
    header("location: /Elenaspa/login.php");
    exit;
}
?>