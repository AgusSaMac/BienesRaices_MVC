<?php

namespace Controllers;

use MVC\Router;
use Model\Blog;
use Model\Vendedor;
use Intervention\Image\ImageManagerStatic as Image;

class BlogController {

    public static function crear(Router $router) {
        
        $entrada = new Blog;
        $vendedores = Vendedor::all();

        $errores = Blog::getErrores();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Crear una nueva instancia
            $entrada = new Blog($_POST['entrada']);

             /** Subida de archivos */
            // Generar nombre único
            $nombreImagen = md5( uniqid( rand(), true)) . ".jpg";
    
            //Setear la imagen
            // Realiza un resize a la imagen con intervention
            if ($_FILES['entrada']['tmp_name']['imagen']) {
                $image = Image::make($_FILES['entrada']['tmp_name']['imagen'])->fit(800,600);
                $entrada->setImagen($nombreImagen);
            }
            
            // Validar que no haya campos vacios
            $errores = $entrada->validar();
        
            // No hay errores
            if(empty($errores)) {
                 //Crea carpeta
                 if(!is_dir(CARPETA_IMAGENES)) {
                    mkdir(CARPETA_IMAGENES);
                }
    
                // Guarda la imagen en el servidor
                $image->save(CARPETA_IMAGENES . $nombreImagen);

                $entrada->guardar();
            }
        }        

        $router->render('blog/crear', [
            'entrada' => $entrada,
            'errores' => $errores,
            'vendedores' => $vendedores
        ]);
    }

    public static function actualizar(Router $router) {
        $id = validarORedireccionar('/admin', 'entrada', 'Blog');
        $entrada = Blog::find($id);
        $vendedores = Vendedor::all();

        $errores = Blog::getErrores();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $args = $_POST['entrada'];

            $entrada->sincronizar($args);

            // validacion
            $errores = $entrada->validar();
    
            // subida de archivos
            // Generar nombre único
            $nombreImagen = md5( uniqid( rand(), true)) . ".jpg";
        
            if ($_FILES['entrada']['tmp_name']['imagen']) {
                $image = Image::make($_FILES['entrada']['tmp_name']['imagen'])->fit(800,600);
                $entrada->setImagen($nombreImagen);
            }
            if(empty($errores)) {
                // Almacenar imagen
                if($_FILES['entrada']['tmp_name']['imagen']) {
                    $image->save(CARPETA_IMAGENES . $nombreImagen);
                }
                $entrada->guardar();
            }
        }

        $router->render('blog/actualizar',[
            'entrada' => $entrada,
            'vendedores' => $vendedores,
            'errores' => $errores
        ]);
    }

    public static function eliminar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if($id) {
                $tipo = $_POST['tipo'];
                if (validarTipoContenido($tipo)) {
                    $entrada = Blog::find($id);
                    $entrada->eliminar();
                }
            }
        }
    }
}