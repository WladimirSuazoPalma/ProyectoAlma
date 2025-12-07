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


<html>
    <meta charset="UTF-8">
    <head>
        <title>
            Insertar
        </title>
        <<link href="estilo.css" rel="stylesheet"/>
        <script>
            function togglePassword() {
                const passwordInput = document.getElementById("Clave");
                const type = passwordInput.type === "password" ? "text" : "password";
                passwordInput.type = type;
            }
        </script>
    </head>
    <body>
        <form method="POST" action="">
            <center>
                <table>

                        <td>
                            Ingresar Nombre de Usuario:
                        </td>
                        <td>
                            <input type="text" name="Usuario" id="Usuario" placeholder="Ingrese el usuario" required/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Ingresar Clave:
                        </td>
                        <td>
                            <input type="password" name="Clave" id="Clave" placeholder="Ingrese la clave" required/>
                            <span class="toggle-password" onclick="togglePassword()"></span>
                        </td>
                    </tr>
                    <tr >
                        <td>
                            <input type="submit" value="Ingresar" class="boton" name="Ingresar" id="Ingresar"/>
                        </td>
                        <td>
                            <input type="reset" value="Borrar" class="boton"/>
                        </td>
                    </tr>

                                        <tr>
                        <td>
                            <li><a href="Alma.html" class="boton-volver">Volver </a> </li>
                        </td>
                    </tr>


                </table>
            </center>
        </form>

    </body>
</html>
