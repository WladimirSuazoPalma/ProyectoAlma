<?php
session_start();

// Verificar que el usuario esté creado y sea administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['es_admin'] != 1) {
    header("Location: login.php");
    exit();
}

$nombreUsuario = $_SESSION['usuario_nombre'];
$emailUsuario = isset($_SESSION['usuario_email']) ? $_SESSION['usuario_email'] : 'admin@alma.com';
$inicialUsuario = strtoupper(substr($nombreUsuario, 0, 1));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrador - Alma DermoEstética</title>
    <link rel="stylesheet" href="estilo.css">
    
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>✨ ALMA</h2>
                <p>Panel Administrador</p>
            </div>
            <center>
            <ul class="menu">
                <li><a href="registrartrabajadores.html"><span class="menu-icon">👥</span> Registrar</a></li>
            </ul>
            </center>
            <div class="logout">
                <a href="logout.php">🚪 Cerrar Sesión</a>
            </div>
        </aside>
        
        <main class="main-content">
            <div class="top-bar">
                <div class="user-info">
                    <div class="user-details">
                        <div class="user-name">Administrador</div>
                        <div class="user-email">admin@alma.com</div>
                    </div>
                </div>
            </div>
            
            
            <div class="quick-actions">
                <div class="actions-grid">
                    <a href="nueva-cita.php" class="action-btn">
                        <div class="action-icon">➕</div>
                        <div>Nueva Cita</div>
                    </a>
                    
                    <a href="nuevo-cliente.php" class="action-btn">
                        <div class="action-icon">👤</div>
                        <div>Nuevo Cliente</div>
                    </a>
                    
                    
                    

                </div>
            </div>
            
        </main>
    </div>
</body>
</html>