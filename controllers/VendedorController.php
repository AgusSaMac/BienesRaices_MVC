<?php

namespace Controllers;

use MVC\Router;
use Model\Vendedor;

class VendedorController {

    public static function crear(Router $router) {

        $vendedor = new Vendedor;

        // Muestra los errores
        $errores = Vendedor::getErrores();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Crear una nueva instancia
            $vendedor = new Vendedor($_POST['vendedor']);
            
            // Validar que no haya campos vacios
            $errores = $vendedor->validar();
        
            // No hay errores
            if(empty($errores)) {
                $vendedor->guardar();
            }
        }        

        $router->render('vendedores/crear', [
            'vendedor'=> $vendedor,
            'errores'=> $errores
        ]);
    }

    public static function actualizar(Router $router) {

        // Validar que sea un ID valido
        $id = validarORedireccionar('/admin', 'vendedor', 'Vendedor');

        // Muestra los errores
        $errores = Vendedor::getErrores();

        $vendedor = Vendedor::find($id);

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
            // Sincronizar los valores
            // Asignar los valores
            $args = $_POST['vendedor'];
            $vendedor->sincronizar($args);
        
            // Validacion
            $errores = $vendedor->validar();
            
            if(empty($errores)) {
                $vendedor->guardar();
            }
        
        }        

        $router->render('vendedores/crear', [
            'vendedor'=> $vendedor,
            'errores'=> $errores
        ]);
    }

    public static function eliminar(Router $router) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar id
            $id = $_POST['id']; // Asigna la variable de POST id a una variable ID
            $id = filter_var($id, FILTER_VALIDATE_INT); // Revisa que la variable

            if($id) {
                $tipo = $_POST['tipo'];
                if (validarTipoContenido($tipo)) {
                    $vendedor = Vendedor::find($id);
                    $vendedor->eliminar();
                }
            }
        }
    }

}