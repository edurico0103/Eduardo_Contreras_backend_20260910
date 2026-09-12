<?php
class conexion
{
    //ESTABLECIENDO VARIABLES GLOBALES / VARIABLES DE CLASE
    public $host = "";
    public $db = "";
    public $user = "";
    public $pass = "";
    public $port = "";
    public $charset = "";
    //OPCIONES POR DEFECTO PARA MANEJO DE CONEXION
    public $options = [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, //PORDEFECTO MANEJE ERROR EXCEPTION DE LA CLASE PDO
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC, //FORMA DE COMO DEVOLVERA LOS DATOS (ARREGLO ASOCIATIVO)
        \PDO::ATTR_EMULATE_PREPARES => false //EL COMPORTAMIENTO DE QUERIES NO SE MANEJARA PREPARES
    ];

    public function conectar()
    {
        try {
            //LINEA DE CONEXION A BASE DE DATOS (DSN)
            $pdo = new PDO("mysql:host={$this->host};dbname={$this->db};charset={$this->charset};port={$this->port};", "{$this->user}", "{$this->pass}");
            return $pdo; //EL OBJETO DE CONEXION
        } catch (PDOException $exp) {
            //SE IMPRIME UN MENSAJE AL OBTENER UN ERROR DE CONEXION
            echo ("Hubo un error en la conexion" . $exp->getMessage());
        }
    }
}
?>