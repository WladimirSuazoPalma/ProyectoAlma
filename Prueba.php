<?php
require_once("conexionsql.php");

// Nota: Eliminé la línea "$conn = conectar();" que tenías aquí arriba suelta 
// porque ya la estás llamando correctamente abajo dentro del IF.

function Insertar($conn)
{
    // Recogida de datos
    $Nombre    = $_POST['Nombre'];
    $Apellidos = $_POST['Apellidos'];
    $Correo    = $_POST['Email'];
    $Clave     = $_POST["Clave"];
    $Rol       = $_POST['Rol'];

    $Params = array(
        array($Nombre, SQLSRV_PARAM_IN),
        array($Apellidos, SQLSRV_PARAM_IN),
        array($Correo, SQLSRV_PARAM_IN),  
        array($Clave, SQLSRV_PARAM_IN),
        array($Rol, SQLSRV_PARAM_IN),
    );

    // Ejecución del Procedimiento
    $sql = "{CALL CREARCUENTA(?,?,?,?,?)}";
    $stmt = sqlsrv_query($conn, $sql, $Params);

    // Verificación
    if ($stmt === false) {
        echo "Error al ejecutar el procedimiento. \n";
        die(print_r(sqlsrv_errors(), true));
    }
    else {
        // --- AQUÍ EMPIEZA LA MAGIA DE SWEETALERT ---
        
        // Imprimimos una estructura HTML básica para que el navegador sepa interpretar la librería
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
                        window.location.href = "login.php";
                    }
                });
            </script>
        </body>
        </html>';
        // --- FIN DEL BLOQUE SWEETALERT ---
    }
}

// Bloque principal de ejecución
if (isset($_POST["Ingresar"])) 
{
    $conn = conectar();
    if($conn)
    {
        Insertar($conn);
        sqlsrv_close($conn);
    }
}
?>
session_start();
require_once("conexionsql.php");

if (!isset($_SESSION['UsuarioID'])) {
    header("Location: login.php");
    exit();
}

$idUsuario = $_SESSION['UsuarioID'];
$idRol     = $_SESSION['Rol'];

$conn = conectar();
$stmt = null;

if ($conn) {
    $params = array(
        array($idUsuario, SQLSRV_PARAM_IN),
        array($idRol, SQLSRV_PARAM_IN)
    );
    $sql = "{CALL CONSULTARCITASACTIVAS(?, ?)}";
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda Activa | Alma Dermoestética</title>
    <link href="estilos.css" rel="stylesheet"/>
</head>

<body class="fondo-dashboard">

    <div class="contenedor-dashboard">
        <h1>Agenda de Consultas Activas</h1>
        
        <?php 
        // CAMBIO AL ESTILO CLÁSICO (LLAVES)
        if ($stmt) { 
        ?>
            <div style="overflow-x:auto;"> 
                <table class="tabla-citas">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Horario</th>
                            <th>Paciente</th>
                            <th>Servicio</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $hayDatos = false;
                        // Bucle while con llaves
                        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                            $hayDatos = true;
                            
                            $fechaObj = $row['FechaCita']; 
                            $horaFinObj = $row['HoraCita'];

                            $fechaTexto = $fechaObj->format('d/m/Y'); 
                            $horarioTexto = $fechaObj->format('H:i') . " - " . $horaFinObj->format('H:i');

                            $claseEstado = ($row['Estado'] == 'Confirmada') ? 'badge-confirmada' : 'badge-pendiente';
                            
                            echo "<tr>";
                            echo "<td>" . $fechaTexto . "</td>";
                            echo "<td><strong>" . $horarioTexto . "</strong></td>";
                            echo "<td>" . $row['NombreCliente'] . "</td>";
                            echo "<td>" . $row['NombreServicio'] . "</td>";
                            echo "<td><span class='badge $claseEstado'>" . $row['Estado'] . "</span></td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <?php 
            // If clásico con llaves
            if (!$hayDatos) { 
            ?>
                <div style="text-align: center; padding: 40px; color: #777;">
                    <h3>No hay citas pendientes ni confirmadas en este momento.</h3>
                    <p>¡Buen trabajo! O quizás es hora de agendar nuevos pacientes.</p>
                </div>
            <?php 
            } // Cierre del if (!$hayDatos)
            ?>

        <?php 
        } // Cierre del if ($stmt) principal
        ?>

        <div style="text-align: center;">
            <a href="Alma.html" class="btn-volver">← Volver al Menú Principal</a>
        </div>
    </div>

</body>
</html>
