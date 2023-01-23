<?php
// En la progragacion orientada a objetos, usamos este archivo como el encargado de llamar todos los procedimientos y funciones usados en el proyecto.

use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();
require 'funciones.php';
require 'config/database.php';

// Conectar a la base de datos
$db = conectarDB(); // Se crea la conexion a la base de datos

use Model\ActiveRecord;

ActiveRecord::setDB($db); // Se envia la conexion de la base de datos a la clase de propiedad, de esta manera cada vez que se crea un nuevo objeto o instancia de la clase de propiedad, no solo contiene los argumentos de la clase, sino que adicionalmente cada uno tiene la referencia a la base de datos, sin añadir carga adicional al procesador.