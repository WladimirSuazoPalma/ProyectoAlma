<?php
// 1. Iniciar sesión para poder guardar variables (como el nombre del usuario)
session_start();

require_once("conexionsql.php");

if (isset($_POST['Usuario']) && isset($_POST['Clave'])) {
    
    $conn = conectar();
    
    $user = $_POST['Usuario']; // Es el correo
    $pass = $_POST['Clave'];   // La contraseña que escribió (ej: 1234)

    // 2. Llamamos al SQL solo con el correo
    $sql = "{CALL OBTENERUSUARIO(?)}";
    $params = array($user);
    
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // 3. Verificamos si SQL encontró el correo
    if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        
        // Recuperamos el HASH de la base de datos
        $hashGuardado = $row['USU_CLAVE']; 
        
        // 4. AQUÍ LA SEGURIDAD: Comparamos la clave escrita con el Hash
        if (password_verify($pass, $hashGuardado)) {
            
            // ¡CONTRASEÑA CORRECTA!
            // Guardamos datos en la sesión para usarlos en las otras páginas
            $_SESSION['UsuarioID'] = $row['USU_ID'];
            $_SESSION['Nombre'] = $row['USU_NOMBRE'];
            $_SESSION['Rol'] = $row['USU_ROL_ID'];

            // Redirigimos al panel principal
            header("Location: Alma.html"); // O la página que sea tu panel de control
            exit();

        } else {
            // El correo existe, pero la clave está mal
            echo "<script>
                    alert('Contraseña incorrecta');
                    window.location.href = 'login.html';
                </script>";
        }

    } else {
        // SQL no devolvió nada (El correo no existe)
        echo "<script>
                alert('El usuario no existe');
                window.location.href = 'login.html';
            </script>";
    }
    
    sqlsrv_close($conn);
}
?>