<?php

namespace Model;

// En general hay que tener en cuenta que tener funciones o metodos pequeños ayuda a mantener la limpieza del codigo, el cual a su vez dara un mejor entendimiento del mismo a cualquier programador que lo lea.

class Propiedad extends ActiveRecord {
    protected static $tabla = 'propiedades';
    protected static $columnasDB = ['id', 'titulo', 'precio', 'imagen', 'descripcion', 'habitaciones', 'wc', 'estacionamiento','creado', 'vendedores_id'];
    
    public $id;
    public $titulo;
    public $precio;
    public $imagen;
    public $descripcion;
    public $habitaciones;
    public $wc;
    public $estacionamiento;
    public $creado;
    public $vendedores_id;
    
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->titulo = $args['titulo'] ?? '';
        $this->precio = $args['precio'] ?? '';
        $this->imagen = $args['imagen'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->habitaciones = $args['habitaciones'] ?? '';
        $this->wc = $args['wc'] ?? '';
        $this->estacionamiento = $args['estacionamiento'] ?? '';
        $this->creado = date('Y/m/d');
        $this->vendedores_id = $args['vendedores_id'] ?? '';
    }

    public function validar() {
        // En este caso, dado que los mensajes son diferentes para cada uno de los errores, no se usa un foreach para iterar a traves de ellos, aunque se podria poner un mensaje generico y cambiar unicamente el tipo de error.
        if(!$this->titulo) {
            self::$errores[] = "Debes añadir un titulo";
        }
        if(!$this->precio) {
            self::$errores[] = "El precio es Obligatorio";
        }
        if(strlen($this->descripcion) < 50) {
            self::$errores[] = "La descripcion es Obligatoria y debe ser de al menos 50 caracteres";
        }
        if(!$this->habitaciones) {
            self::$errores[] = "Las habitaciones son Obligatorias";
        }

        if(!$this->wc) {
            self::$errores[] = "El número de baños son Obligatorios";
        }

        if(!$this->estacionamiento) {
            self::$errores[] = "El número de estacionamientos es obligatorio";
        }

        if(!$this->vendedores_id) {
            self::$errores[] = "Elije un vendedor";
        }

        if(!$this->imagen) {
            self::$errores[] = 'La imagen de la propiedad es obligatoria';
            
            // La validacion de tamaño  que se realizaba con anterioridad, se elimina pues se creo el resize.
        }
        return self::$errores;
    }
}