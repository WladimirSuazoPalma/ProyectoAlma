<?php
session_start();
require_once("conexionsql.php");
if (!isset($_SESSION['UsuarioID'])){
    header("Location: login.php");
    exit();
}

$idUsuario = $_SESSION['UsuarioID'];
$idRol = $_SESSION['Rol'];
$conn = conectar();
$stmt = null;

if($conn)
{
    $params = array(
        array($idUsuario, SQLSRV_PARAM_IN),
        array($idRol, SQLSRV_PARAM_IN)
    );
    $sql = "{CALL CONSULTARCITASACTIVAS(?,?)}";
    $stmt = sqlsrv_query($conn,$sql,$params);

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
    <title>Agenda Activa | Alma Dermoestética</title>
    <link href="estiloHome.css" rel="stylesheet"/>
</head>
<body class="contenedor-dashboard">
    <div class="fondo-dashboard">
        <h1>Agenda de Consultas Activas</h1>
        <?php if ($stmt){
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
                        while($row = sqlsrv_fetch_array($stmt,  SQLSRV_FETCH_ASSOC)){
                            $hayDatos = true;
                            $fechaObj = $row['FechaCita'];
                            $horaFinObj= $row['HoraCita'];
                            $fechaTexto= $fechaObj->format('d/m/Y');
                            $horarioTexto =$fechaObj->format('H:i')."-". $horaFinObj->format('H:i');
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
                </Table>
            </div>
            <?php 
            if (!$hayDatos) { 
            ?>
                <div style="text-align: center; padding: 40px; color: #777;">
                    <h3>No hay citas pendientes ni confirmadas en este momento.</h3>
                    <p>¡Buen trabajo! O quizás es hora de agendar nuevos pacientes.</p>
                </div>
            <?php 
            } 
            ?>
            <?php
            }
            ?>
            <div style="text-align: center;">
            <a href="Alma.html" class="btn-volver">← Volver al Menú Principal</a>
        </div>
    </div>
</body>
</html>