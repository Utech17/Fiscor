<?php
class Conexion {
    private $conexion;
    
    function __construct(){
        $servidor="localhost";
        $usuarioBD="root";
        $claveBD="";
        $bd="fiscor";
        
        try {
            $this->conexion=new PDO("mysql:host=$servidor;dbname=$bd",$usuarioBD,$claveBD);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $fallo){
            die ("La conexion ha fallado.".$fallo->getMessage());
        }
		// Si no existe una session iniciada, se inicia
		if(!isset($_SESSION)) session_start();
    }
    public function conectar(){
        return $this->conexion; 
    }
    
    function cerrar_conexion() {
       $this->conexion = null;
       unset($this->conexion);
    }

}
?> 
