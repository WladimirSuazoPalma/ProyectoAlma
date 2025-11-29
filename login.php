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
        <form method="GET" action="">
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
        <?php
require_once("Insertar.php");
if(isset($_POST['Ingresar']))
{
    function Insertar(){};
}
?>
    </body>
</html>