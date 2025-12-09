<?php
session_start();

require_once("conexionsql.php");

if (isset($_POST['Usuario']) && isset($_POST['Clave'])) {
    
    $conn = conectar();
    
    $user = $_POST['Usuario']; 
    $pass = $_POST['Clave']; 


    $sql = "{CALL OBTENERUSUARIO(?)}";
    $params = array($user);
    
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }


    if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        

        $hashGuardado = $row['USU_CLAVE']; 
        

        if (password_verify($pass, $hashGuardado)) {
            

            $_SESSION['UsuarioID'] = $row['USU_ID'];
            $_SESSION['Nombre'] = $row['USU_NOMBRE'];
            $_SESSION['Rol'] = $row['USU_ROL_ID'];


            header("Location: Alma.html"); 
            exit();

        } else {

            echo "<script>
                    alert('Contraseña incorrecta');
                    window.location.href = 'login.html';
                </script>";
        }

    } else {

        echo "<script>
                alert('El usuario no existe');
                window.location.href = 'login.html';
            </script>";
    }
    
    sqlsrv_close($conn);
}

?>



