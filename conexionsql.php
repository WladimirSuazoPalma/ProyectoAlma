<?php
function conectar()
{

$Server= "ALAN";
$connectionInfo = array(
    "Database" => "Proyecto_Alma", 
    "UID" => "admin",        
    "PWD" => "pass1234"  
);
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


