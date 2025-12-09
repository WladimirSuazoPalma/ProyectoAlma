<?php
session_start();
require_once("conexionsql.php");

if (!isset($_SESSION['UsuarioID'])) {
    header("Location: login.php");
    exit();
}

$rolesPermitidos = [1, 2, 3];
if (!in_array($_SESSION['Rol'], $rolesPermitidos)) {
    
    echo "<script>alert('Acceso denegado. No tienes permisos para agendar.'); window.location.href='Alma.php';</script>";
    exit();
}

$conn = conectar();
$mensaje = "";
$tipoMensaje = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cliente = $_POST['cliente'];
    $profesional = $_POST['profesional'];
    $servicio = $_POST['servicio'];
    $fecha = $_POST['fecha']; 

    
    $fechaSQL = str_replace(' ', ' ', $fecha) . ':00'; 

    $sql = "{CALL GENERARCITA(?, ?, ?, ?)}";
    $params = array(
        array($cliente, SQLSRV_PARAM_IN),
        array($profesional, SQLSRV_PARAM_IN),
        array($servicio, SQLSRV_PARAM_IN),
        array($fechaSQL, SQLSRV_PARAM_IN)
    );

    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        $mensaje = "Error de conexión: " . print_r(sqlsrv_errors(), true);
        $tipoMensaje = "error";
    } else {
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if ($row['Resultado'] == 'EXITO') {
            $mensaje = $row['Mensaje'];
            $tipoMensaje = "exito";
        } else {
            $mensaje = "No se pudo agendar: " . $row['Mensaje'];
            $tipoMensaje = "error";
        }
    }
}

$sqlCli = "SELECT CLI_ID, CLI_NOMBRE, CLI_APELLIDO FROM CLI_CLIENTE ORDER BY CLI_NOMBRE";
$stmtCli = sqlsrv_query($conn, $sqlCli);

$sqlProf = "SELECT USU_ID, USU_NOMBRE, USU_APELLIDO FROM USU_USUARIO WHERE USU_ROL_ID IN (2, 3) ORDER BY USU_NOMBRE";
$stmtProf = sqlsrv_query($conn, $sqlProf);


$sqlSer = "SELECT SER_ID, SER_NOMBRE, SER_PRECIO FROM SER_SERVICIO ORDER BY SER_NOMBRE";
$stmtSer = sqlsrv_query($conn, $sqlSer);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Cita</title>
    <link href="estiloHome.css" rel="stylesheet"/>
    <style>
        .form-group { margin-bottom: 20px; text-align: left; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; color: #5e1c26; }
        .form-control {
            width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 16px;
        }
        .mensaje-alerta {
            padding: 15px; margin-bottom: 20px; border-radius: 5px; text-align: center; font-weight: bold;
        }
        .exito { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body class="fondo-dashboard">

    <div class="contenedor-dashboard">
        <h1>Agendar Nueva Cita</h1>
        
        <?php if ($mensaje != "") { ?>
            <div class="mensaje-alerta <?php echo $tipoMensaje; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php } ?>

        <div class="login-card" style="max-width: 600px; margin: 0 auto; border: none; box-shadow: none;">
            <form method="POST" action="generarcitas.php">
                
                <div class="form-group">
                    <label>Paciente:</label>
                    <select name="cliente" class="form-control" required>
                        <option value="">-- Seleccione Paciente --</option>
                        <?php while($row = sqlsrv_fetch_array($stmtCli, SQLSRV_FETCH_ASSOC)) { ?>
                            <option value="<?php echo $row['CLI_ID']; ?>">
                                <?php echo $row['CLI_NOMBRE'] . " " . $row['CLI_APELLIDO']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Profesional:</label>
                    <select name="profesional" class="form-control" required>
                        <option value="">-- Seleccione Especialista --</option>
                        <?php while($row = sqlsrv_fetch_array($stmtProf, SQLSRV_FETCH_ASSOC)) { ?>
                            <option value="<?php echo $row['USU_ID']; ?>">
                                <?php echo $row['USU_NOMBRE'] . " " . $row['USU_APELLIDO']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Servicio:</label>
                    <select name="servicio" class="form-control" required>
                        <option value="">-- Seleccione Tratamiento --</option>
                        <?php while($row = sqlsrv_fetch_array($stmtSer, SQLSRV_FETCH_ASSOC)) { ?>
                            <option value="<?php echo $row['SER_ID']; ?>">
                                <?php echo $row['SER_NOMBRE'] . " ($" . number_format($row['SER_PRECIO'], 0, ',', '.') . ")"; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Fecha y Hora de Inicio:</label>
                    <input type="datetime-local" name="fecha" class="form-control" required>
                </div>

                <div class="btn-container">
                    <input type="submit" value="Confirmar Cita" class="btn btn-ingresar">
                </div>

            </form>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="Alma.php" class="btn-volver">← Volver al Menú Principal</a>
        </div>
    </div>

</body>
</html>
