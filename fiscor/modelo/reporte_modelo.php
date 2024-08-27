<?php
require_once("../modelo/conexionPDO.php");

class Reportes extends Conexion {
    // Attributes


    private $conexion;

    // Constructor
    public function __construct() {
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->conectar();
    }


}
?>