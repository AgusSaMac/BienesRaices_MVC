<?php 
    // Para evitar problemas de inicio de session, que por ejemplo se inicie dos veces, se tiene que poner una condicional al session_start
    if (!isset($_SESSION)) {
        session_start();
    }
    // Se obtiene la variable del login 
    $auth = $_SESSION['login'] ?? false;

    if (!isset($inicio)) {
        $inicio = false;
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienes Raices</title>
    <link rel="stylesheet" href="../build/css/app.css">
</head>
<body>
    <header class="header <?php echo $inicio ? 'inicio' : '' ?>">
        <!-- contenedores para centrado de contenido, se tiene un contenedor exclusivo para el header, para darle su formato exclusivo. -->
        <div class="contenedor contenido-header">
            <!--Barra superior que contiene el logotipo y barra de navegacióm-->
            <div class="barra">
                <!-- Ya que es comun usar el logo para retornar a la pagina principal, este se tiene dentro de un link -->
                <a href="/">
                    <img src="/build/img/logo.svg" alt="Logotipo de Bienes Raices">
                </a>

                <div class="mobile-menu">
                    <img src="/build/img/barras.svg" alt="icono menu responsive">
                </div>

                <div class="derecha">
                    <img class="darkMode-boton" src="/build/img/dark-mode.svg" alt="Dark mode">
                    <nav class="navegacion">
                        <a href="/nosotros">Nosotros</a>
                        <a href="/propiedades">Anuncios</a>
                        <a href="/blog">Blog</a>
                        <a href="/contacto">Contacto</a>
                        <?php if($auth): ?>
                            <a href="/logout">Cerrar Sesión</a>
                        <?php endif; ?>
                    </nav>
                </div>
            </div> <!--cierre .barra -->
            
            <?php echo $inicio ? '<h1>Venta de casas y departamentos exclusivos de lujo</h1>' : '' ?>
        </div>
    </header>

<?php echo $contenido; ?>

    <footer class="footer seccion">
        <div class="contenedor contenedor-footer">
            <nav class="navegacion">
                <a href="nosotros.php">Nosotros</a>
                <a href="anuncios.php">Anuncios</a>
                <a href="blog.php">Blog</a>
                <a href="contacto.php">Contacto</a>
            </nav>
        </div>

        <p class="copyright">Todos los derechos Reservados <?php echo date('Y'); ?> &copy;</p>
    </footer>

    <script src="build/js/bundle.min.js"></script>
</body>
</html>