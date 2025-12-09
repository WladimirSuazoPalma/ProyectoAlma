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
    $fechaRaw = $_POST['fecha']; 

    
    $fechaSQL = str_replace('T', ' ', $fechaRaw) . ':00'; 
    $fechaSQL= $fechaRaw . ':00';

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

$listaClientes = [];
$sqlCli = "SELECT USU_ID as CLI_ID, USU_NOMBRE as CLI_NOMBRE, USU_APELLIDO as CLI_APELLIDO 
           FROM USU_USUARIO 
           WHERE USU_ROL_ID = 4 
           ORDER BY USU_NOMBRE";
$stmtCli =sqlsrv_query($conn, $sqlCli);
if($stmtCli){
    while($row =sqlsrv_fetch_array($stmtCli,SQLSRV_FETCH_ASSOC)){
            $listaClientes[]=$row;
    }
    sqlsrv_free_stmt($stmtCli);
}

$listaProfesionales = [];
$sqlProf = "SELECT USU_ID, USU_NOMBRE, USU_APELLIDO FROM USU_USUARIO WHERE USU_ROL_ID IN (2,3) ORDER BY USU_NOMBRE";
$stmtProf =sqlsrv_query($conn, $sqlProf);
if($stmtProf){
    while($row =sqlsrv_fetch_array($stmtProf,SQLSRV_FETCH_ASSOC)){
            $listaProfesionales[]=$row;
    }
    sqlsrv_free_stmt($stmtProf);
}




$listaServicios=[];
$sqlSer = "SELECT SER_ID, SER_NOMBRE, SER_COSTO FROM SER_SERVICIO ORDER BY SER_NOMBRE";
$stmtSer =sqlsrv_query($conn, $sqlSer);
if($stmtSer==false){
        echo "Error cargando servicios"; print_r(sqlsrv_errors());
    } else {
        while($row =sqlsrv_fetch_array($stmtSer,SQLSRV_FETCH_ASSOC)){
                $listaServicios[]=$row;
        }
        sqlsrv_free_stmt($stmtSer);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Cita</title>
    <link href="estiloHome.css" rel="stylesheet"/>
    <style>
        .contenedor-form{
            max-width: 500px;
            margin: 40px auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid #d4af37;
        }
        .form-group { margin-bottom: 20px; text-align: left; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 8px; color: #5e1c26; }
    
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            background-color: #f9f9f9;
        }
        
        
        .acciones-form {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        .btn-full {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }
        .btn-guardar { background-color: #5e1c26; color: white; }
        .btn-guardar:hover { background-color: #4a151e; }
        
        .btn-cancelar { background-color: #ddd; color: #333; }
        .btn-cancelar:hover { background-color: #ccc; }

        
        .alerta { padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; }
        .exito { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body class="fondo-dashboard">

    <header>
        <div class="logo">
            <h2 class="nombre-empresa" style="margin-left: 20px;">Alma | Gestión de Citas</h2>
        </div>
        <nav class="nav-principal">
            <a href="Alma.php">Volver al Inicio</a>
        </nav>
    </header>

    <div class="contenedor-form">
        <h2 style="text-align: center; color: #5e1c26; margin-bottom: 30px;">Agendar Nueva Cita</h2>
        
        <?php if ($mensaje != "") { ?>
            <div class="alerta <?php echo $tipoMensaje; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php } ?>

        <form method="POST" action="generarcita.php">
            
            <div class="form-group">
                <label>Paciente:</label>
                <select name="cliente" class="form-control" required>
                    <option value="">-- Seleccione --</option>
                    <?php foreach($listaClientes as $cli) { ?>
                        <option value="<?php echo $cli['CLI_ID']; ?>">
                            <?php echo $cli['CLI_NOMBRE'] . " " . $cli['CLI_APELLIDO']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label>Profesional a cargo:</label>
                <select name="profesional" class="form-control" required>
                    <option value="">-- Seleccione --</option>
                    <?php foreach($listaProfesionales as $prof) { ?>
                        <option value="<?php echo $prof['USU_ID']; ?>">
                            <?php echo $prof['USU_NOMBRE'] . " " . $prof['USU_APELLIDO']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label>Tratamiento / Servicio:</label>
                <select name="servicio" class="form-control" required>
                    <option value="">-- Seleccione --</option>
                    <?php if (empty($listaServicios)) { ?>
                        <option value="" disabled>No hay servicios cargados</option>
                    <?php } else { 
                        foreach($listaServicios as $ser) { ?>
                        <option value="<?php echo $ser['SER_ID']; ?>">
                            <?php echo $ser['SER_NOMBRE'] . " ($" . number_format($ser['SER_COSTO'], 0, ',', '.') . ")"; ?>
                        </option>
                    <?php } 
                    } ?>
                </select>
            </div>

            <div class="form-group">
                <label>Fecha y Hora:</label>
                <input type="datetime-local" name="fecha" class="form-control" 
                    min="<?php echo date('Y-m-d\TH:i'); ?>" required>
            </div>

            <div class="acciones-form">
                <a href="Alma.php" class="btn-full btn-cancelar">Cancelar / Volver</a>
                
                <button type="submit" class="btn-full btn-guardar">Confirmar Cita</button>
            </div>

        </form>
    </div>

</body>
</html>
