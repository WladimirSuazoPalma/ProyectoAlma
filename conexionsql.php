<?php
function conectar()
{
$Server= "WLADIMIR\SQLEXPRESS";
$connectionInfo= array("Database"=>"Proyecto_Alma",);
$conn = sqlsrv_connect( $Server, $connectionInfo );
if( $conn === false)
    {
        echo "No se Pudo Establecer la Conexion. <br />";
        die(print_r( sqlsrv_errors(), true));
    }
        return $conn;
    
}
function close($Variable)
{
    sqlsrv_close( $Variable );
}
?>


