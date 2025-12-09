<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiénes Somos - Alma DermoEstetica</title> 
    <link rel="stylesheet" href="estiloHome.css"> 
</head>
<body>
    <header>
            <div class="logo">
                <img src="img/logo1.jpg" alt="logo de la compañia">
                <h2 class="nombre-empresa">Alma DermoEstetica</h2>
            </div>
            <nav class="nav-principal">
                    <a href="Alma.php">Inicio </a> 
                    <a href="generarcita.php">Agendar Cita</a>
                    <a href="contacto.php">Contacto </a> 
                    <a href="consultar.php">Consultas </a>
                    <a href="quienes-somos.html">Quiénes Somos</a>

                    <?php if (isset($_SESSION['UsuarioID'])) { 
        
                        
                        $rolTexto = "Usuario";
                        switch($_SESSION['Rol']) {
                            case 1: $rolTexto = "Administrador"; break;
                            case 2: $rolTexto = "Esteticista"; break;
                            case 3: $rolTexto = "Dermatólogo"; break;
                            case 4: $rolTexto = "Paciente"; break;
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

    <main>
        <section class="contenido-pagina">
            <div class="login-card-2">

            
            <h3>Conoce al Equipo de Alma DermoEstetica</h3>

            <p>
                En Alma DermoEstetica, somos un equipo apasionado y dedicado de profesionales
                comprometidos con tu bienestar y belleza integral. Creemos firmemente en la
                importancia de cuidar tanto la salud de tu piel como tu equilibrio interior,
                ofreciendo tratamientos personalizados que realzan tu belleza natural y
                promueven una sensación de renovación y confianza.
            </p>
            </div>

            <!-- <h3>Nuestra Filosofía</h3> -->
            <!-- <p>
                Nuestra filosofía se centra en la atención personalizada y en el uso de
                productos de la más alta calidad, combinados con las últimas tecnologías
                en dermoestética. Cada cliente es único, y por ello, dedicamos el tiempo
                necesario para entender tus necesidades y objetivos específicos, diseñando
                un plan de tratamiento a tu medida.
            </p> -->
            <div class="login-card-2">

            
            <h3>El Equipo</h3>
            <div class="miembro-equipo">
                <h3> Tecnologo Medico Maria Paz Valenzuela - Fundadora y Especialista en Dermoestetica Principal</h3>
                <p>
                    Con más de 5 años de experiencia en el campo de la dermoestética, la Especialista Valenzuela
                    es una experta reconocida por su enfoque meticuloso y su habilidad para lograr
                    resultados armoniosos. Su pasión es ayudar a sus pacientes a sentirse
                    y verse mejor.
                </p>
            </div>
            </div>

            <!-- <div class="miembro-equipo">
                <h4>Laura Gómez - Cosmetóloga y Terapeuta Estética</h4>
                <p>
                    Laura se especializa en tratamientos faciales avanzados y terapias corporales.
                    Su conocimiento profundo de los tipos de piel y productos cosmecéuticos asegura
                    que cada tratamiento sea efectivo y placentero.
                </p>
            </div> -->

            <p>
                Te invitamos a conocernos y a descubrir cómo podemos ayudarte a alcanzar
                tus objetivos de belleza y bienestar. ¡En Alma DermoEstetica, tu salud y
                satisfacción son nuestra prioridad!
            </p>

        </section>
    </main>




</body>
    <footer class="footer-alma">
            <div class="contenedor-footer">
                
                <div class="columna-footer">
                    <h3>Centro ALMA</h3>
                    <p>Especialistas en resaltar tu belleza natural con tratamientos dermoestéticos seguros y profesionales.</p>
                </div>

                <div class="columna-footer">
                    <h3>Enlaces</h3>
                    <ul class="lista-footer">
                        <li><a href="Alma.html">Inicio</a></li>
                        <li><a href="#">Tratamientos</a></li>
                        <li><a href="login.html">Intranet Staff</a></li>
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
        <script>
        if (document.getElementById('currentYear')) {
            document.getElementById('currentYear').textContent = new Date().getFullYear();
        }
    </script>
</html>
