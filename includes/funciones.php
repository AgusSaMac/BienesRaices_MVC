<?php

use Model\Vendedor;
use Model\Propiedad;
use Model\Blog;

define('TEMPLATES_URL', __DIR__ . '/templates');
define('FUNCIONES_URL', __DIR__ .  'funciones.php');
define('CARPETA_IMAGENES', $_SERVER['DOCUMENT_ROOT'] . '/imagenes/');

function incluirTemplate( string $nombre, bool $inicio = false) {
    include TEMPLATES_URL . "/${nombre}.php";
}

function estaAutenticado() {
    session_start();

    if (!$_SESSION['login']) {
        header('Location: /');
    }
}

function debugear($variable) {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}
// Escapar / sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

// Validad tipo de Contenido
function validarTipoContenido($tipo) {
    $tipos = ['vendedor', 'propiedad', 'entrada'];
    return in_array($tipo, $tipos);
}

// Muestra los mensajes
function mostrarNotificacion($codigo) {
    $mensaje = '';
    switch ($codigo) {
        case 1:
            $mensaje = 'Registro Creado Correctamente';
            break;
        case 2:
            $mensaje = 'Registro Actualizado Correctamente';
            break;
        case 3:
            $mensaje = 'Registro Eliminado correctamente';
            break;   
        default:
            $mensaje = false;
            break;
    }
    return $mensaje;
}

function validarORedireccionar(string $url, string $var, string $class) {
    // Verificar el id
    $id =  $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);
    if(is_numeric($id)){
        switch ($class) {
            case 'Propiedad':
                $$var = Propiedad::find($id);
                break;
            case 'Vendedor':
                $$var = Vendedor::find($id);
                break;
            case 'Blog':
                $$var = Blog::find($id);
                break;
            default:
                $$var = null;
                break;
        }
    }
    // Se realiza la comprobación de la variable y el Id para su redireccionamiento
    if ($$var === null || !$id) {
        header("Location: ${url}");
    }
    return $id;
}