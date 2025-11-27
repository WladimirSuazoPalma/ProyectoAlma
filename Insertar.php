<?php
require_once("conexionsql.php");
 
$conn = conectar();
function Insertar($conn) 
{

$Nombres=$_POST['Nombres'];
$Correo=$_POST['Email'];
$Usuario = $_POST["Usuario"];
$Clave = $_POST["Clave"];
$Rol=$_POST['Rol'];

$Params = array(
    array($Nombres, SQLSRV_PARAM_IN),
    array($Correo, SQLSRV_PARAM_IN),
    array($Usuario, SQLSRV_PARAM_IN),
    array($Clave, SQLSRV_PARAM_IN),
    array($Rol, SQLSRV_PARAM_IN),

);
$stmt = sqlsrv_query($conn, '{CALL CREARCUENTA(?,?,?,?,?)}', $Params);
if ($stmt === false) {
    echo "Error al ejecutar el procedimiento. \n";
    die(print_r(sqlsrv_errors(), true));
}
else{
    echo "Datos guardados correctamente \n";
}
}

if (isset($_POST["Ingresar"])) // esto verifica si es que se han enviado los datos con el boton ingresar
{
    $conn = conectar();
    Insertar($conn);
    sqlsrv_close( $conn );
    
}


    
?>