<?php
session_start();
if(isset($_POST['Ingresar']))
{
include("conexionsql.php");
$Usuario=$_POST["Usuario"];
$Clave=$_POST["Clave"];

$conn=conectar();

    if($conn !== false)
    {
        $params = array(
            array($Usuario, SQLSRV_PARAM_IN),
            array($Clave, SQLSRV_PARAM_OUT)
        );
        $stmt = sqlsrv_query($conn, '{call AUTENTIFICACION(?,?)}', $params);
    }
            if ($stmt !== false && sqlsrv_has_rows($stmt)) {
            $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
            
            $_SESSION['usuario_id'] = $row['UsuarioID'];
            $_SESSION['usuario_nombre'] = $row['Nombre'];
            
            sqlsrv_free_stmt($stmt);
            sqlsrv_close($conn);
            
            header("Location: inicio.php");
            exit();
        } else {
            $error = "Usuario o contraseña incorrectos";
        }
        
        if(isset($stmt)) sqlsrv_free_stmt($stmt);
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
                </table>
            </center>
        </form>

    </body>
</html>