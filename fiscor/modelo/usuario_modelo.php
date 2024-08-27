<?php
require_once("conexionPDO.php");

class Usuario extends Conexion {
    private $ID_Usuario;
    private $Usuario;

    private $Contrasena;
    private $Nombre;
    private $Apellido;
    private $ID_Rol;
    private $objbd;

    public function __construct(){
        parent::__construct();
        $this->objbd = parent::conectar();
    }

    // Métodos getter y setter para ID_Rol
    public function get_ID_Rol(){
        return $this->ID_Rol;
    }

    public function set_ID_Rol($ID_Rol){
        $this->ID_Rol = $ID_Rol;
    }

    // Métodos existentes para otros campos
    public function get_ID_Usuario(){
        return $this->ID_Usuario;
    }

    public function set_ID_Usuario($ID_Usuario){
        $this->ID_Usuario = $ID_Usuario;
    }

    public function get_Usuario() {
        return $this->Usuario;
    }

    public function set_Usuario($Usuario){
        $this->Usuario = $Usuario;
    }


    public function get_Contrasena(){
        return $this->Contrasena;
    }

    public function set_Contrasena($Contrasena){
        $this->Contrasena = $Contrasena;
    }

    public function get_Nombre(){
        return $this->Nombre;
    }

    public function set_Nombre($Nombre){
        $this->Nombre = $Nombre;
    }

    public function get_Apellido(){
        return $this->Apellido;
    }

    public function set_Apellido($Apellido){
        $this->Apellido = $Apellido;
    }

    public function autenticarUsuario($usuario, $clave) {
        $registro = "SELECT * FROM usuario WHERE Usuario = :usuario";
        $preparado = $this->objbd->prepare($registro);
        $preparado->bindParam(':usuario', $usuario);
        $preparado->execute();
        $datos = $preparado->fetch(PDO::FETCH_ASSOC);
        

        if ($datos && $clave === $datos['Contrasena']) { // Comparar contrasenas directamente
            $this->ID_Usuario = $datos['ID_Usuario'];
            $this->Usuario = $datos['Usuario'];
            $this->Nombre = $datos['Nombre'];
            $this->Apellido = $datos['Apellido'];
            $this->ID_Rol = $datos['ID_Rol'];
            return true;
        } else {
            return false;
        }
    }    

    // Método para obtener la lista de todos los usuarios (solo si el usuario es admin)
    public function obtenerUsuarios() {
        $lista = array();
        $consulta = $this->objbd->query("SELECT * FROM usuario WHERE ID_Usuario > 1");
        while ($filas = $consulta->fetch(PDO::FETCH_ASSOC)) {
            $lista[] = $filas;
        }
        return $lista;
    }

    public function obtenerUsuarioPorId($id) {
        $consulta = $this->objbd->prepare("SELECT * FROM usuario WHERE ID_Usuario = :ID_usuario");
        $consulta->bindParam(':ID_usuario', $id, PDO::PARAM_INT);
        $consulta->execute();
        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario) {
            $this->ID_Usuario = $usuario['ID_Usuario'];
            $this->Usuario = $usuario['Usuario'];
            $this->Nombre = $usuario['Nombre'];
            $this->Apellido = $usuario['Apellido'];
            $this->ID_Rol = $usuario['ID_Rol'];
        } else {
            throw new Exception("Usuario no encontrado");
        }
    }

    // Método para actualizar el rol de un usuario
    public function actualizarRolUsuario($idUsuario, $nuevoRol) {
        $sql = "UPDATE usuario SET ID_Rol = :nuevoRol WHERE ID_Usuario = :idUsuario";
        $stmt = $this->objbd->prepare($sql);
        $stmt->bindParam(':nuevoRol', $nuevoRol, PDO::PARAM_INT);
        $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Método para agregar un nuevo usuario

    public function agregarUsuario($usuario, $nombre, $apellido, $contrasena) {
        try {
            $sql = "INSERT INTO usuario ( Usuario, Contrasena, Nombre, Apellido )
                    VALUES (:usuario, :contrasena, :nombre, :apellido)";
        
            $stmt = $this->objbd->prepare($sql);
    
            $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);

            $stmt->bindParam(':contrasena', $contrasena, PDO::PARAM_STR);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':apellido', $apellido, PDO::PARAM_STR);
        
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function actualizarUsuario() {
        $sql = "UPDATE usuario SET Usuario = :usuario, Nombre = :nombre, Apellido = :apellido, ID_Rol = :rol WHERE ID_Usuario = :idUsuario";
        

        if (!empty($this->Contrasena)) {
            $sql = "UPDATE usuario SET Usuario = :usuario, Nombre = :nombre, Apellido = :apellido, Contrasena = :contrasena, ID_Rol = :rol WHERE ID_Usuario = :idUsuario";
        }
    
        $stmt = $this->objbd->prepare($sql);
        
        // Vincular parámetros
        $stmt->bindParam(':usuario', $this->Usuario);
        $stmt->bindParam(':nombre', $this->Nombre);
        $stmt->bindParam(':apellido', $this->Apellido);
        $stmt->bindParam(':rol', $this->ID_Rol, PDO::PARAM_INT);
        $stmt->bindParam(':idUsuario', $this->ID_Usuario, PDO::PARAM_INT);
    

        if (!empty($this->Contrasena)) {
            $stmt->bindParam(':contrasena', $this->Contrasena);
        }
        return $stmt->execute();
    }

    public function getLastInsertedID() {
        try {
            return $this->objbd->lastInsertId();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function eliminarUsuario($idUsuario) {
        try {
            $sql = "DELETE FROM usuario WHERE ID_Usuario = :idUsuario";
            $stmt = $this->objbd->prepare($sql);
            $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
?>