<?php
/**
 */
// Prevent if the installation successful
if (! file_exists('./../install.php')) {
    header('HTTP/1.0 404 Not Found');
    exit();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Location: ./process/index.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Instalación</title>
</head>
<body style="margin:0">
Por favor espere mientras le redireccionamos a la instalación
</body>
</html>