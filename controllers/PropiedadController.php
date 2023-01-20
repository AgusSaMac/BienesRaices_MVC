<?php

namespace Controllers;
use MVC\Router;
use Model\Propiedad;
use Model\Vendedor;
use Model\Blog;
use Intervention\Image\ImageManagerStatic as Image;

class PropiedadController {
    
    public static function index(Router $router) {

        $propiedades = Propiedad::all();
        $vendedores = Vendedor::all();
        $entradas = Blog::all();

        // Muestra mensaje condicional
        $resultado= $_GET['resultado'] ?? null;

        $router->render('propiedades/admin', [
            'propiedades' => $propiedades,
            'resultado' => $resultado,
            'vendedores' => $vendedores,
            'entradas' => $entradas 
        ]);
    }
    
    public static function crear(Router $router) {
        
        $propiedad = new Propiedad;
        $vendedores = Vendedor::all();

        // Arreglo con el mensaje de errores
        $errores = Propiedad::getErrores();

        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            /**Crea una nueva instancia */
            $propiedad = new Propiedad($_POST['propiedad']);
    
            /** Subida de archivos */
            // Generar nombre único
            $nombreImagen = md5( uniqid( rand(), true)) . ".jpg";
    
            //Setear la imagen
            // Realiza un resize a la imagen con intervention
            if ($_FILES['propiedad']['tmp_name']['imagen']) {
                $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800,600);
                $propiedad->setImagen($nombreImagen);
            }
    
            // Validar
            $errores = $propiedad->validar();
            // Verificar que el arreglo este vacio
            if (empty($errores)) {
    
                //Crea carpeta
                if(!is_dir(CARPETA_IMAGENES)) {
                    mkdir(CARPETA_IMAGENES);
                }
    
                // Guarda la imagen en el servidor
                $image->save(CARPETA_IMAGENES . $nombreImagen);
    
                // Guarda en la base de datos
                $propiedad->guardar();
            }
            
        }
        
        $router->render('propiedades/crear', [
            'propiedad' => $propiedad,
            'vendedores' => $vendedores,
            'errores' => $errores
        ]);
    }

    public static function actualizar(Router $router) {
        
        $id = validarORedireccionar('/admin', 'propiedad', 'Propiedad');
        $propiedad = Propiedad::find($id);
        $vendedores = Vendedor::all();

        // Arreglo con el mensaje de errores
        $errores = Propiedad::getErrores();

        // Metodo POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Asignar los atributos
            $args = $_POST['propiedad'];
        
            $propiedad->sincronizar($args);
        
            // Validacion
            $errores = $propiedad->validar();
        
            // Subida de archivos
            // Generar nombre único
            $nombreImagen = md5( uniqid( rand(), true)) . ".jpg";
        
            if ($_FILES['propiedad']['tmp_name']['imagen']) {
                $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800,600);
                $propiedad->setImagen($nombreImagen);
            }
            if(empty($errores)) {
                // Almacenar imagen
                if($_FILES['propiedad']['tmp_name']['imagen']) {
                    $image->save(CARPETA_IMAGENES . $nombreImagen);
                }
                $propiedad->guardar();
            }
        }

        $router->render('/propiedades/actualizar', [
            'propiedad'=> $propiedad,
            'errores' => $errores,
            'vendedores' => $vendedores
        ]);

    }

    public static function eliminar() {
        // revisa que la variable del id exista para poder eliminar la propiedad.
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar Id
            $id = $_POST['id']; // Asigna la variable de POST id a una variable ID
            $id = filter_var($id, FILTER_VALIDATE_INT); // Revisa que la variable

            if($id) {
                $tipo = $_POST['tipo'];
                if (validarTipoContenido($tipo)) {
                    $propiedad = Propiedad::find($id);
                    $propiedad->eliminar();
                }
            }
        }
    }
}