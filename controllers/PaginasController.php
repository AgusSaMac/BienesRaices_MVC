<?php

namespace Controllers;

use Model\Blog;
use MVC\Router;
use Model\Vendedor;
use Model\Propiedad;
use PHPMailer\PHPMailer\PHPMailer;

class PaginasController {

    public static function index(Router $router) {
        
        $propiedades = Propiedad::get(3);
        $entradas = Blog::get(2);
        $vendedores = Vendedor::all();

        $inicio = true;

        $router->render('paginas/index', [
            'propiedades'=> $propiedades,
            'inicio' => $inicio,
            'entradas' => $entradas,
            'vendedores' => $vendedores
        ]);
    }
    
    public static function nosotros(Router $router) {
        $router->render('paginas/nosotros');
    }

    public static function propiedades(Router $router) {
        
        $propiedades = Propiedad::all();
        
        $router->render('paginas/propiedades',[
            'propiedades' => $propiedades
        ]);
    }
    public static function propiedad(Router $router) {
        $id = validarORedireccionar('/propiedades', 'propiedad', 'Propiedad');

        // buscar propiedad por id
        $propiedad = Propiedad::find($id);

        $router->render('paginas/propiedad',[
            'propiedad'=> $propiedad
        ]);
    }
    public static function blog(Router $router) {

        $entradas = Blog::all();
        $vendedores = Vendedor::all();

        $router->render('paginas/blog', [
            'vendedores' => $vendedores,
            'entradas' => $entradas
        ]);
    }
    public static function entrada(Router $router) {
        $id = validarORedireccionar('/blog', 'entrada', 'Blog');

        $vendedores = Vendedor::all();
        // Buscar entrada de blog por id
        $entrada = Blog::find($id);
        

        $router->render('paginas/entrada',[
            'entrada' => $entrada,
            'vendedores' => $vendedores
        ]);
    }
    public static function contacto(Router $router) {

        $mensaje = null;
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $respuestas = $_POST['contacto'];
            
            // Crear nueva instacia de phpmailer
            $mail = new PHPMailer();
            
            // Configurar SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Port = 2525;
            $mail->Username = '6baea700e19913';
            $mail->Password = 'b18e26d2a49b48';
            $mail->SMTPSecure ='tls'; // no olvidar, se tiene que enviar el email por un puerto seguro.

            // Configurar el contenido del mail.

            $mail->setFrom('admin@bienesraices.com');
            $mail->addAddress('admin@bienesraices.com', 'BienesRaices.com');
            $mail->Subject = 'Tienes un nuevo mensaje';

            // Habilitar HTML
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';

            // Definir contenido
            $contenido = '<html>';
            $contenido .= '<p>Tienes un nuevo mensaje</p>';
            $contenido .= '<p>Nombre: ' . $respuestas['nombre'] . '</p>';
            $contenido .= '<p>Eligio ser contactado por: ' . $respuestas['contacto'] . '</p>';

            if($respuestas['contacto'] === 'telefono') {
                $contenido .= '<p>Telefono: ' . $respuestas['telefono'] . '</p>';
                $contenido .= '<p>Fecha Contacto: ' . $respuestas['fecha'] . '</p>';
                $contenido .= '<p>Hora: ' . $respuestas['hora'] . '</p>';
            } else {
                $contenido .= '<p>Email: ' . $respuestas['email'] . '</p>';
            }
            
            $contenido .= '<p>Mensaje: ' . $respuestas['mensaje'] . '</p>';
            $contenido .= '<p>Vende o Compra: ' . $respuestas['tipo'] . '</p>';
            $contenido .= '<p>Precio o Presupuesto: $' . $respuestas['precio'] . '</p>';
            $contenido .= '</html>';

            $mail->Body = $contenido;
            $mail->AltBody = 'Esto es texto alternativo sin HTML';

            // Enviar el email
            if($mail->send()) {
                $mensaje = "Mensaje enviado correctamente";
            } else {
                $mensaje = "El mensaje no se pudo enviar";
            }

        }
        
        $router->render('paginas/contacto',[
            'mensaje' => $mensaje
        ]);
    }
}