<?php
function conectar()
{
<<<<<<< HEAD
$Server= "WLADIMIR\SQLEXPRESS";
$connectionInfo = array(
    "Database" => "Proyecto_Alma_2", 
    "UID" => "admin",        
    "PWD" => "pass12345"        
=======
$Server= "ALAN";
$connectionInfo = array(
    "Database" => "Proyecto_Alma", 
    "UID" => "admin",        
    "PWD" => "pass1234"        
>>>>>>> dcdb3c4ea265ae947055accff6a5082b54d3af8f
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


