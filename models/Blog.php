<?php

namespace Model;

class Blog extends ActiveRecord {
    protected static $tabla = 'blog';
    protected static $columnasDB = ['id', 'titulo', 'extracto', 'imagen', 'articulo', 'creado', 'vendedores_id'];

    public $id;
    public $titulo;
    public $extracto;
    public $imagen;
    public $articulo;
    public $creado;
    public $vendedores_id;


    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->titulo = $args['titulo'] ?? '';
        $this->extracto = $args['extracto'] ?? '';
        $this->imagen = $args['imagen'] ?? '';
        $this->articulo = $args['articulo'] ?? '';
        $this->creado = date('Y/m/d');
        $this->vendedores_id = $args['vendedores_id'] ?? '';
    }

    public function validar() {
        if(!$this->titulo) {
            self::$errores[] = "Debes añadir un titulo";
        }
        if(strlen($this->extracto) > 50 || strlen($this->extracto) === 0) {
            self::$errores[] = "El extracto es obligatorio y no debe ser mayor a 50 caracteres";
        }
        if(!$this->imagen) {
            self::$errores[] = "Una imagen del articulo es obligatoria";
        }
        if(strlen($this->articulo) < 50) {
            self::$errores[] = "El articulo es obligatorio y requiere de al menos 50 caracteres";
        }
        if(!$this->vendedores_id) {
            self::$errores[] = "Debes elegir a la persona que publica el articulo";
        }
        return self::$errores;
    }
}