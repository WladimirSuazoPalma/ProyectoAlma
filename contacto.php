<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estiloHome.css">
    <title>Contacto</title>
</head>
<body>
    <header>
        <div class="logo">
                <img src="img/logo1.jpg" alt="logo de la compañia">
                <h2 class="nombre-empresa">Alma DermoEstetica</h2>
            </div>
            <nav class="nav-principal">
                    <a href="Alma.php">Inicio </a> 
                    
                     
                    <a href="consultar.php">Citas </a>
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
                            <a href="registrar.html">Registrar</a>
                        <?php } ?>


            </nav>
    </header>
    
    
        <div class="contenedor-imagen2">
            <div class="texto-superpuesto">
                <h2>Contactenos</h2>
            </div>

            <div class="seccion-contacto-dividida">
    
                <div class="columna-contacto izquierda">
                    <h3>Nuestra Ubicación</h3>
                    <p>Estamos ubicados en el corazón de Temuco, en un espacio diseñado para tu relajación.</p>
                    
                    <div class="dato-contacto">
                        <strong>📍 Dirección:</strong><br>
                        Av. Alemania 0123, Oficina 404<br>
                        Temuco, Araucanía.
                    </div>
                    <div class="map-container-pequeno">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3112.537976729088!2d-72.6042732243286!3d-38.74086687175814!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9614d3c3e80c9c2d%3A0x6006730046162311!2sAv.%20Alemania%200123%2C%20Temuco%2C%20Araucan%C3%ADa!5e0!3m2!1ses!2scl!4v1701980000000!5m2!1ses!2scl" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>

                    <div class="dato-contacto">
                        <strong>🕒 Horarios de Atención:</strong><br>
                        Lunes a Viernes: 09:00 - 19:00 hrs.<br>
                        Sábados: 10:00 - 14:00 hrs.
                    </div>

                    <div class="dato-contacto">
                        <strong>📞 Teléfono / WhatsApp:</strong><br>
                        +56 9 1234 5678
                    </div>
                </div>

                    <div class="columna-contacto derecha">
                        <h3>Escríbenos</h3>
                        <p>¿Tienes dudas sobre algún tratamiento? ¿Quieres agendar una evaluación? Hablanos y te ayudaremos a la brevedad.</p>
                        
                        <div class="caja-falsa-formulario">
                            <p>Correo: AlmaDermoestetica@gmail.com</p>
                            <p>Pagina: ProyectoAlma.com</p>
                        </div>
                        <div class="logo-mail">
                            <img src="gif/icons8-mail-96.gif" alt="Gif de mail">
                        </div>
                    </div>
                    
            </div>
            
        

        
        
    <section style="max-width: 800px; margin: 0 auto; padding: 20px;">
    </section>
    
</body>
</html>