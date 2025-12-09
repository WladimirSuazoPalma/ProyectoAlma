<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estiloHome.css">
    <title>Alma DermoEstetica</title></head>
    <body>
        <header>
            <div class="logo">
                <img src="img/logo1.jpg" alt="logo de la compañia">
                <h2 class="nombre-empresa">Alma DermoEstetica</h2>
            </div>
            <nav class="nav-principal">
                    <a href="Alma.php">Inicio </a> 

                    

                    <a href="consultar.php">Consultas </a>
                    <a href="contacto.php">Contacto </a> 
                    <a href="quienes-somos.php">Quiénes Somos</a>


                    <?php if (isset($_SESSION['UsuarioID'])) { 
        

                        $rolTexto = "Usuario";
                        switch($_SESSION['Rol']) {
                            case 1: $rolTexto = "Administrador"; break;
                            case 2: $rolTexto = "Esteticista"; break;
                            case 3: $rolTexto = "Dermatólogo"; break;
                            case 4: $rolTexto = "Paciente"; break;
                        }
                        if ($_SESSION['Rol'] == '1'){
                            ?>
                            <div>
                                <a href="registrar.html">Registrar</a>
                                <a href="generarcita.php">Agendar Cita</a>
                                <a href="solicitudes.php">Aceptar Citas</a>
                            </div>
                            <?php
                        }
                        if ($_SESSION['Rol'] == '2' || $_SESSION['Rol'] == '3'){
                            ?>
                            <div>
                                <a href="generarcita.php">Agendar Cita</a>
                            </div>
                            <?php
                        }
                        if ($_SESSION['Rol'] == '4'){
                            ?>
                            <div>
                                <a href="solicitar_cita.php">Agendar Cita</a>
                            </div>
                            <?php
                        }
                    ?>
                        <div class="perfil-usuario">
                            <div class="detalles">
                                <span class="nombre"><?php echo $_SESSION['Nombre'] . " " . $_SESSION['Apellido']; ?></span>
                                <span class="rol"><?php echo $rolTexto; ?></span>
                            </div>
                            
                            <a href="logout.php" class="btn-salir">Cerrar Sesión</a>
                        </div>

                    <?php } else { ?>
                            <a href="login.html">Login</a>
                            <a href="registrarUsuario.html">Registrar</a>
                        <?php } ?>
                    
            </nav> 
        </header>
    <section class="servicios-destacados">
        <div class="contenedor-servicios">
            
            <div class="titulo-seccion">
                <h2>Nuestros Tratamientos</h2>
                <p>Descubre lo que podemos hacer por tu piel</p>
            </div>

            <div class="grid-tarjetas">
                
                <div class="tarjeta">
                    <div class="imagen-tarjeta" style="background-image: url('img/Limpieza3.png');"></div>
                    <div class="contenido-tarjeta">
                        <h3>Limpieza  Facial</h3>
                        <p>Renueva tu piel eliminando impurezas y células muertas para un brillo natural.</p>
                        <a href="Limpieza.html" class="btn-ver-mas">Ver Más</a>
                    </div>
                </div>

                <div class="tarjeta">
                    <div class="imagen-tarjeta" style="background-image: url('img/acido_hialuronico.jpg');"></div>
                    <div class="contenido-tarjeta">
                        <h3>Rinomodelacion</h3>
                        <p>Resalta tu belezza con pequeñas dosis de Acido Hialuronico.</p>
                        <a href="acidohialuronico.html" class="btn-ver-mas">Ver Más</a>
                    </div>
                </div>

                <div class="tarjeta">
                    <div class="imagen-tarjeta" style="background-image: url('img/peeling1.png');"></div>
                    <div class="contenido-tarjeta">
                        <h3>Peeling Químico</h3>
                        <p>Tratamiento avanzado para manchas y arrugas finas. Rejuvenece tu rostro.</p>
                        <a href="peeling.html" class="btn-ver-mas">Ver Más</a>
                    </div>
                </div>
            </div>
            <div class="slider-frame">
                <ul>
                    <li><img src="img/Limpieza2.png" alt=""></li>
                    <li><img src="img/Limpieza3.png" alt=""></li>
                    <li><img src="img/Peeling2.png" alt=""></li>
                    <li><img src="img/Botox2.png" alt=""></li>
                </ul>
            </div>
            <br>
        </div>
        <footer class="footer-alma">
            <div class="contenedor-footer">
                
                <div class="columna-footer">
                    <h3>Centro ALMA</h3>
                    <p>Especialistas en resaltar tu belleza natural con tratamientos dermoestéticos seguros y profesionales.</p>
                </div>

                <div class="columna-footer">
                    <h3>Enlaces</h3>
                    <ul class="lista-footer">
                        <li><a href="index.php">Inicio</a></li>
                        <li><a href="#">Tratamientos</a></li>
                        <li><a href="login.php">Intranet Staff</a></li>
                    </ul>
                </div>
                <!-- Los iconos de ✉️  📞 y 📍 son mis iconos agregados a mano, no es IA porfavor leer esto -->
                <div class="columna-footer">
                    <h3>Contáctanos</h3>
                    <p>📍 Av. Alemania 0123, Temuco</p>
                    <p>📞 +56 9 1234 5678</p>
                    <p>✉️ contacto@alma.cl</p>
                </div>

            </div>

            <div class="barra-copyright">
                <p>&copy; 2025 Centro de Salud Integral ALMA. Todos los derechos reservados.</p>
            </div>
        </footer>
    </section>
    </body>
</html>
