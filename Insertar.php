<?php
require_once("conexionsql.php");

$conn = conectar();
function Insertar($conn)
{

    $Nombre=$_POST['Nombre'];
    $Apellidos=$_POST['Apellidos'];
    $Correo=$_POST['Email'];
    $ClaveCruda = $_POST["Clave"]; 
    $Clave      = password_hash($ClaveCruda, PASSWORD_DEFAULT);
    $Rol=$_POST['Rol'];


    $Params = array(
        array($Nombre, SQLSRV_PARAM_IN),
        array($Apellidos, SQLSRV_PARAM_IN),
        array($Correo, SQLSRV_PARAM_IN),  
        array($Clave, SQLSRV_PARAM_IN),
        array($Rol, SQLSRV_PARAM_IN),

    );
    $stmt = sqlsrv_query($conn, '{CALL CREARCUENTA(?,?,?,?,?)}', $Params);
    if ($stmt === false) {
        echo "Error al ejecutar el procedimiento. \n";
        die(print_r(sqlsrv_errors(), true));
    }
    else{
        //EL popup bonito
        echo '
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Registro Exitoso</title>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            
            <style> body { font-family: Arial, sans-serif; background-color: #f4f4f4; } </style>
        </head>
        <body>
            <script>
                // 2. Configuramos el Popup
                Swal.fire({
                    title: "¡Registro Exitoso!",
                    text: "Tu cuenta ha sido creada correctamente.",
                    icon: "success",
                    confirmButtonText: "Ir al Login",
                    confirmButtonColor: "#3085d6", // Color azul bonito para el botón
                    allowOutsideClick: false       // Obliga al usuario a dar clic en el botón
                }).then((result) => {
                    // 3. Cuando el usuario presiona el botón...
                    if (result.isConfirmed) {
                        window.location.href = "login.html";
                    }
                });
            </script>
        </body>
        </html>';
    }
}

if (isset($_POST["Ingresar"])) 
{
    $conn = conectar();
    if($conn)
    {
        Insertar($conn);
        sqlsrv_close( $conn );
    }
}


    
?>
