<?php
session_start();
require_once("conexionsql.php");

if (!isset($_SESSION['UsuarioID'])){
    header("Location: login.html");
    exit();
}

$idUsuario = $_SESSION['UsuarioID'];
$idRol = $_SESSION['Rol'];
$conn = conectar();
$stmt = null;

if($conn) {
    $params = array(
        array($idUsuario, SQLSRV_PARAM_IN),
        array($idRol, SQLSRV_PARAM_IN)
    );
    $sql = "{CALL CONSULTARCITASACTIVAS(?,?)}";
    $stmt = sqlsrv_query($conn, $sql, $params);

    if($stmt == false){
        die(print_r(sqlsrv_errors(),true));
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Citas | Alma</title>
    <link href="estiloHome.css" rel="stylesheet"/>
</head>
<body class="fondo-dashboard">
    
    <header>
        <div class="logo"><h2 class="nombre-empresa" style="margin-left:20px;">Alma | Citas</h2></div>
        <nav class="nav-principal">
            <a href="Alma.php">Volver</a>
        </nav>
    </header>

    <div class="contenedor-dashboard">
        
        <h1><?php echo ($idRol == 4) ? "Mis Citas Programadas" : "Agenda de Consultas Activas"; ?></h1>

        <?php if ($stmt){ ?>
            <div style="overflow-x:auto;">
                <table class="tabla-citas">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Horario</th>
                            <th>Tratamiento</th>
                            

                            
                            <?php if($idRol == 4) { ?>
                                <th>Especialista</th>
                            <?php } else { ?>
                                <th>Paciente</th>
                            <?php } ?>
                            
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $hayDatos = false;
                        while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                            $hayDatos = true;
                            $fechaObj = $row['FechaCita'];
                            $horaFinObj= $row['HoraCita'];
                            
                            


                            $fechaTexto = $fechaObj->format('d/m/Y');
                            $horarioTexto = $fechaObj->format('H:i') . " - " . $horaFinObj->format('H:i');
                            
                            


                            $claseEstado = ($row['Estado'] == 'Confirmada') ? 'badge-confirmada' : 'badge-pendiente';
                            $txtEstado = $row['Estado'];
                            if($txtEstado == 'Pendiente') $txtEstado = 'Esperando Confirmación'; 

                            echo "<tr>";
                            echo "<td>" . $fechaTexto . "</td>";
                            echo "<td><strong>" . $horarioTexto . "</strong></td>";
                            echo "<td>" . $row['NombreServicio'] . "</td>";
                            
                    
                            if($idRol == 4) {
                                echo "<td>" . $row['NombreDoctor'] . "</td>";
                            } else {
                                echo "<td>" . $row['NombrePaciente'] . "</td>";
                            }

                            echo "<td><span class='badge $claseEstado'>" . $txtEstado . "</span></td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <?php if (!$hayDatos) { ?>
                <div style="text-align: center; padding: 40px; color: #777;">
                    <h3>No tienes citas agendadas.</h3>
                    <?php if($idRol == 4) { ?>
                        <p>¿Deseas agendar una hora con nosotros?</p>
                        <a href="solicitar_cita.php" class="btn-ver-mas" style="background: #5e1c26; color: white;">Solicitar Hora</a>
                    <?php } else { ?>
                        <p>La agenda está libre por ahora.</p>
                    <?php } ?>
                </div>
            <?php } ?>

        <?php } ?>
        
    </div>
</body>
</html>