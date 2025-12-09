<?php
session_start();
require_once("conexionsql.php");



if (!isset($_SESSION['UsuarioID']) || $_SESSION['Rol'] != 4) {
    header("Location: login.html");
    exit();
}

$conn = conectar();
$mensaje = "";
$tipoMensaje = "";
$idCliente = $_SESSION['UsuarioID']; 



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $profesional = $_POST['profesional'];
    $servicio = $_POST['servicio'];
    $fechaRaw = $_POST['fecha']; 
    $fechaSQL = $fechaRaw . ':00'; 


    $sql = "{CALL GENERARCITA(?, ?, ?, ?)}";
    $params = array(
        array($idCliente, SQLSRV_PARAM_IN),
        array($profesional, SQLSRV_PARAM_IN),
        array($servicio, SQLSRV_PARAM_IN),
        array($fechaSQL, SQLSRV_PARAM_IN)
    );

    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        $mensaje = "Error al solicitar: " . print_r(sqlsrv_errors(), true);
        $tipoMensaje = "error";
    } else {
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if ($row['Resultado'] == 'EXITO') {
            $mensaje = "¡Solicitud enviada! Te confirmaremos por correo cuando el especialista la acepte.";
            $tipoMensaje = "exito";
        } else {
            $mensaje = "Error: " . $row['Mensaje'];
            $tipoMensaje = "error";
        }
    }
}

 
$listaProfesionales = [];
$sqlProf = "SELECT USU_ID, USU_NOMBRE, USU_APELLIDO FROM USU_USUARIO WHERE USU_ROL_ID IN (2, 3) ORDER BY USU_NOMBRE";
$stmtProf = sqlsrv_query($conn, $sqlProf);
if($stmtProf){ while($r = sqlsrv_fetch_array($stmtProf, SQLSRV_FETCH_ASSOC)) { $listaProfesionales[] = $r; } sqlsrv_free_stmt($stmtProf); }

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
    <title>Solicitar Hora | Alma</title>
    <link href="estiloHome.css" rel="stylesheet"/>
    <style>
        .contenedor-form { max-width: 500px; margin: 40px auto; background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: 1px solid #d4af37; }
        .form-control { width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 8px; }
        .btn-guardar { width: 100%; padding: 12px; background-color: #5e1c26; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; }
        .alerta { padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; }
        .exito { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body class="fondo-dashboard">
    <header>
        <div class="logo"><h2 class="nombre-empresa" style="margin-left:20px;">Alma | Pacientes</h2></div>
        <nav class="nav-principal"><a href="Alma.php">Volver</a></nav>
    </header>


    <div class="contenedor-form">
        <h2 style="text-align: center; color: #5e1c26;">Solicitar Atención</h2>
        <p style="text-align: center; color: #666; margin-bottom: 20px;">Tu cita quedará pendiente de confirmación.</p>

        <?php if ($mensaje != "") { ?>
            <div class="alerta <?php echo $tipoMensaje; ?>"><?php echo $mensaje; ?></div>
        <?php } ?>

        <form method="POST" action="">
            <label><strong>Especialista:</strong></label>
            <select name="profesional" class="form-control" required>
                <option value="">-- Seleccione --</option>
                <?php foreach($listaProfesionales as $prof) { ?>
                    <option value="<?php echo $prof['USU_ID']; ?>"><?php echo $prof['USU_NOMBRE']." ".$prof['USU_APELLIDO']; ?></option>
                <?php } ?>
            </select>

            <label><strong>Tratamiento:</strong></label>
            <select name="servicio" class="form-control" required>
                <option value="">-- Seleccione --</option>
                <?php foreach($listaServicios as $ser) { ?>
                    <option value="<?php echo $ser['SER_ID']; ?>"><?php echo $ser['SER_NOMBRE']." ($".number_format($ser['SER_COSTO'],0,',','.').")"; ?></option>
                <?php } ?>
            </select>

            <label><strong>Fecha deseada:</strong></label>
            <input type="datetime-local" name="fecha" class="form-control" min="<?php echo date('Y-m-d\TH:i'); ?>" required>

            <button type="submit" class="btn-guardar">Enviar Solicitud</button>
        </form>
    </div>
</body>
</html>