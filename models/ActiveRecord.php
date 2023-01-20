<?php

namespace Model;
class ActiveRecord {
    //Base de datos
    // Para realizar la conexion a la base de datos, es recomendable no declararlos en el construct, en lugar de ello, dejar la conexion a la base de datos en una estructura estatica.
    protected static $db;
    // Se crea este arreglo estatico para poder realizar la iteracion necesaria para sanitizar todos los datos
    protected static $columnasDB = [];
    // Crea una tabla vacia para poder declarar la tabla que se desea consultar.
    protected static $tabla = '';
    // Errores
    protected static $errores = [];
    
    // Definir la conexion a la base de datos
    public static function setDB($database) {
        self::$db = $database;
    }

    public function guardar() {
        if (!is_null($this->id)) {
            // Actualizar
            $this->actualizar();
        }else {
            // Crear
            $this->crear();
        }
    }

    public function crear() {

        // Sanitizar la entrada de datos, se creara una nueva funcion porque de esta forma se puede llamar cuando se requiera (cuando se realize una actualizacion, por ejemplo).
        $atributos = $this->sanitizarAtributos();

        //Cuando se emplea un arreglo se pueden emplear las funciones array_values y array_keys para crear subarreglos del arreglo 

        // join permite concatenar un arreglo en una string, ocupa dos valores, el primero es el separador y el segundo el array que transformara en string.

        // la creacion del query para la insercion a la base de datos se puede realizar de la siguiente manera.

        //Insertar en la base de datos
        $query = " INSERT INTO " . static::$tabla . " ( ";
        $query.= join(', ', array_keys($atributos));
        $query .= " ) VALUES (' ";
        $query.= join("', '", array_values($atributos));
        $query .= " ') ";
        
        $resultado = self::$db->query($query);
        if($resultado) {
            // Redireccionar al usuario
            header('location: /admin?resultado=1');
        }
    }

    public function actualizar() {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        $valores = [];
        foreach ($atributos as $key => $value) {
            $valores[] = "{$key}='{$value}'";
        }
        $query = "UPDATE ". static::$tabla ." SET ";
        $query .= join(', ', $valores);
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1";

        $resultado = self::$db->query($query);
        if($resultado) {
            // Redireccionar al usuario
            header('location: /admin?resultado=2');
        }
    }

    // Eliminar un registro
    public function eliminar() {
        // Eliminar la propiedad
        $query = "DELETE FROM ". static::$tabla ." WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
        $resultado = self::$db->query($query);

        if($resultado) {
            $this->borrarImagen();
            header('location: /admin?resultado=3');
        }

    }


    // Metodo encargado de identificar y unir los atributos de la base de datos
    public function atributos() {
        $atributos = [];
        foreach(static::$columnasDB as $columna) {
            if($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    public function sanitizarAtributos() {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach($atributos  as $key=>$value) { // En este caso, es necesario mantener tanto la llave como el valor, por lo cual se correra el for each como un arreglo asociativo.
            $sanitizado[$key] = self::$db->escape_string($value);

        }

        return $sanitizado;
    }

    // Subida de archivos
    public function setImagen($imagen) {
        // Eliminar imagen previa
        if (!is_null($this->id)) {
            $this->borrarImagen();
        }

        // Asignar al atributo de imagen en el nombre de la imagen
        if ($imagen) {
            $this->imagen = $imagen;
        }
    }

    // Elimina el archivo
    public function borrarImagen() {
        // Comprobar si existe el archivo
        $existeArchivo = file_exists(CARPETA_IMAGENES . $this->imagen);
        if ($existeArchivo) {
            unlink(CARPETA_IMAGENES . $this->imagen);
        }
    }

    // Validacion
    public static function getErrores() {
        return static::$errores;
    }

    public function validar() {
        static::$errores = [];
        return static::$errores;
    }

    // Lista todas los registros.
    // Para poder relizar la lista de  los registros, se debe crear un objeto por cada uno de los resultados obtenidos
    public static function all() {
        // se crea la query.
        $query = "SELECT * FROM " . static::$tabla;

        // Se realiza la consutla a la base de datos
        $resultado = self::consultarSQL($query);

        return $resultado;
    }

    // Obtiene determinado numero de registros
    public static function get($cantidad) {
        // se crea la query.
        $query = "SELECT * FROM " . static::$tabla . " LIMIT " . $cantidad;

        // Se realiza la consutla a la base de datos
        $resultado = self::consultarSQL($query);

        return $resultado;
    }

    //Busca un registro por su ID
    public static function find($id) {
        $query = "SELECT * FROM ". static::$tabla ." WHERE id = ${id}";
        $resultado = self::consultarSQL($query);

        return array_shift($resultado);

    }

    // Este metodo es especifico para realizar la consulta a la base de datos
    public static function consultarSQL($query) {
        // Consultar la base de datos
        $resultado = self::$db->query($query);

        // Iterar los resultados con un foreach o while
        // Creamos un nuevo arreglo donde se almacenen todos los objetos
        $array = [];
        while ($registro = $resultado->fetch_assoc()) {
            $array[] = static::crearObjeto($registro);
        }

        // Este paso es opcional, pero es recomendable hacerlo para ayudar al servidor
        $resultado->free();// liberar la memoria

        //retornar los resultados
        return $array;
    }
    // Esta funcion accesible unicamente dentro de la clase es la encargada de crear el objeto
    protected static function crearObjeto($registro) {
        $objeto = new static; // Crea un nuevo objeto de acuerdo a la clase que esta llamando

        foreach ($registro as $key => $value) {
            if (property_exists($objeto, $key)) { // property_exists sirve para corroborar que una propiedad existe. Toma dos argumentoss, uno es el objeto el segundo argumento es el dato o propiedad contra la cual se comparara
                $objeto->$key = $value;
            }
        }
        return $objeto;
    }

    //Sincronizar el objeto en memoria con los cambios realizados por el usuario
    public function sincronizar($args = []) {
        foreach ($args as $key => $value) {
            if(property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }
}