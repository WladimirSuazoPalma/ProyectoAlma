<?php
session_start();
require_once("conexionsql.php");


if (!isset($_SESSION['UsuarioID']) || $_SESSION['Rol'] != 1) {
    
    header("Location: Alma.php");
    exit();
}

$conn = conectar();
$mensaje = "";
$tipoMensaje = "";


if (isset($_GET['eliminar_id'])) {
    $idBorrar = $_GET['eliminar_id'];

 
    if ($idBorrar == $_SESSION['UsuarioID']) {
        $mensaje = "Error: No puedes eliminar tu propia cuenta de Administrador.";
        $tipoMensaje = "error";
    } else {
       
        $sqlDelete = "DELETE FROM USU_USUARIO WHERE USU_ID = ?";
        $stmtDelete = sqlsrv_query($conn, $sqlDelete, array($idBorrar));

        if ($stmtDelete) {
            $mensaje = "Usuario eliminado correctamente.";
            $tipoMensaje = "exito";
        } else {
           
            $errors = sqlsrv_errors();
            
            if ($errors[0]['code'] == 547) {
                $mensaje = "No se puede eliminar: Este usuario tiene citas o historial médico asociado. <br> (Por seguridad legal, los datos no se pueden borrar).";
            } else {
                $mensaje = "Error al eliminar: " . $errors[0]['message'];
            }
            $tipoMensaje = "error";
        }
    }
}


$sql = "SELECT USU_ID, USU_NOMBRE, USU_APELLIDO, USU_EMAIL, USU_ROL_ID FROM USU_USUARIO ORDER BY USU_ROL_ID ASC, USU_NOMBRE ASC";
$stmt = sqlsrv_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios | Admin</title>
    <link href="estiloHome.css" rel="stylesheet"/>
    <style>
        .btn-eliminar { 
            background-color: #dc3545; color: white; padding: 5px 10px; 
            border-radius: 5px; text-decoration: none; font-size: 13px; font-weight: bold;
        }
        .btn-eliminar:hover { background-color: #c82333; }
        
        .alerta { padding: 15px; margin-bottom: 20px; border-radius: 5px; text-align: center; }
        .exito { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

       
        .badge-rol { padding: 4px 8px; border-radius: 12px; font-size: 12px; color: white; }
        .rol-1 { background-color: #d4af37; } 
        .rol-2 { background-color: #17a2b8; } 
        .rol-3 { background-color: #28a745; } 
        .rol-4 { background-color: #6c757d; } 
    </style>
</head>
<body class="fondo-dashboard">
    <header>
        <div class="logo"><h2 class="nombre-empresa">Alma | Panel Admin</h2></div>
        <nav class="nav-principal">
            <a href="Alma.php">Volver al Inicio</a>
            <a href="logout.php">Cerrar Sesión</a>
        </nav>
    </header>

    <div class="contenedor-dashboard">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h1>Usuarios Registrados</h1>
            <a href="registrar.html" class="btn-ver-mas" style="background:none;">+ Nuevo Usuario</a>
        </div>
        
        <?php if ($mensaje != "") { ?>
            <div class="alerta <?php echo $tipoMensaje; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php } ?>

        <table class="tabla-citas">
            <thead>
                <tr>
                    <th>Nombre Completo</th>
                    <th>Email / Usuario</th>
                    <th>Rol (Cargo)</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) { 
                
                    $rolNombre = "Desconocido";
                    $claseRol = "rol-4";
                    switch($row['USU_ROL_ID']) {
                        case 1: $rolNombre = "Admin"; $claseRol = "rol-1"; break;
                        case 2: $rolNombre = "Esteticista"; $claseRol = "rol-2"; break;
                        case 3: $rolNombre = "Dermatólogo"; $claseRol = "rol-3"; break;
                        case 4: $rolNombre = "Paciente"; $claseRol = "rol-4"; break;
                    }
                ?>
                <tr>
                    <td><?php echo $row['USU_NOMBRE'] . " " . $row['USU_APELLIDO']; ?></td>
                    <td><?php echo $row['USU_EMAIL']; ?></td>
                    <td><span class="badge-rol <?php echo $claseRol; ?>"><?php echo $rolNombre; ?></span></td>
                    <td>
                        <?php if ($row['USU_ID'] != $_SESSION['UsuarioID']) { ?>
                            <a href="admin_usuarios.php?eliminar_id=<?php echo $row['USU_ID']; ?>" 
                               class="btn-eliminar"
                               onclick="return confirm('¿Estás SEGURO de eliminar a <?php echo $row['USU_NOMBRE']; ?>? Esta acción es irreversible.')">
                               🗑 Eliminar
                            </a>
                        <?php } else { ?>
                            <span style="color:#aaa; font-size:12px;">(Tú)</span>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>