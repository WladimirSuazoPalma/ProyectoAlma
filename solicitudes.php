<?php
session_start();
require_once("conexionsql.php");

// SOLO STAFF (Roles 1, 2, 3)
if (!isset($_SESSION['UsuarioID']) || !in_array($_SESSION['Rol'], [1, 2, 3])) {
    header("Location: Alma.php"); exit();
}

$conn = conectar();
$mensaje = "";

// --- LÓGICA PARA ACEPTAR CITA ---
if (isset($_GET['aceptar_id'])) {
    $idCita = $_GET['aceptar_id'];
    
    // Actualizamos el estado a 'Confirmada'
    $sqlUpd = "UPDATE CIT_CITA SET CIT_ESTADO = 'Confirmada' WHERE CIT_ID = ?";
    $stmtUpd = sqlsrv_query($conn, $sqlUpd, array($idCita));
    
    if ($stmtUpd) {
        $mensaje = "¡Cita #$idCita confirmada!";
        echo "<script>alert('Cita confirmada correctamente.'); window.location.href='solicitudes.php';</script>";
    } else {
        $mensaje = "Error al confirmar.";
    }
}

// --- CONSULTAR CITAS PENDIENTES (CORREGIDO) ---
// Antes buscábamos CLI.CLI_NOMBRE, ahora buscamos PACIENTE.USU_NOMBRE
$sql = "SELECT 
            C.CIT_ID, 
            C.CIT_FECHA_HORA_INICIO, 
            PACIENTE.USU_NOMBRE,    -- Corregido
            PACIENTE.USU_APELLIDO,  -- Corregido
            SER.SER_NOMBRE, 
            PRO.USU_NOMBRE as NomDoc
        FROM CIT_CITA C
        -- Aquí unimos con USUARIOS (PACIENTE) en lugar de CLIENTES
        INNER JOIN USU_USUARIO PACIENTE ON C.CIT_CLI_ID = PACIENTE.USU_ID
        INNER JOIN SER_SERVICIO SER ON C.CIT_SER_ID = SER.SER_ID
        INNER JOIN USU_USUARIO PRO ON C.CIT_USU_ID_PROFESIONAL = PRO.USU_ID
        WHERE C.CIT_ESTADO = 'Pendiente'
        ORDER BY C.CIT_FECHA_HORA_INICIO ASC";

$stmt = sqlsrv_query($conn, $sql);

// SI FALLA LA CONSULTA, MOSTRAMOS POR QUÉ (Para que no salga error fatal)
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitudes Pendientes</title>
    <link href="estiloHome.css" rel="stylesheet"/>
    <style>
        .btn-aceptar { background-color: #28a745; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none; font-size: 14px; }
        .btn-aceptar:hover { background-color: #218838; }
    </style>
</head>
<body class="fondo-dashboard">
    <header>
        <div class="logo"><h2 class="nombre-empresa" style="margin-left: 20px;">Alma | Gestión</h2></div>
        <nav class="nav-principal"><a href="Alma.php">Volver</a></nav>
    </header>

    <div class="contenedor-dashboard">
        <h1>Bandeja de Solicitudes Pendientes</h1>
        
        <?php if($mensaje) echo "<p style='color:green; font-weight:bold;'>$mensaje</p>"; ?>

        <table class="tabla-citas">
            <thead>
                <tr>
                    <th>Fecha / Hora</th>
                    <th>Paciente</th>
                    <th>Tratamiento</th>
                    <th>Doctor Solicitado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) { 
                    $fecha = $row['CIT_FECHA_HORA_INICIO']->format('d/m/Y H:i');
                ?>
                <tr>
                    <td><?php echo $fecha; ?></td>
                    <td><?php echo $row['USU_NOMBRE']." ".$row['USU_APELLIDO']; ?></td>
                    <td><?php echo $row['SER_NOMBRE']; ?></td>
                    <td><?php echo $row['NomDoc']; ?></td>
                    <td>
                        <a href="solicitudes.php?aceptar_id=<?php echo $row['CIT_ID']; ?>" 
                           class="btn-aceptar" 
                           onclick="return confirm('¿Confirmar esta cita?')">
                           ✅ Aceptar
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        
        <?php if (sqlsrv_has_rows($stmt) === false) { ?>
            <p style="text-align:center; margin-top:20px; color:#666;">No hay solicitudes pendientes por ahora.</p>
        <?php } ?>

    </div>
</body>
</html>