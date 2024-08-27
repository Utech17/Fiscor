<?php
require_once("../modelo/conexionPDO.php");

class Gastos extends Conexion {
    // Attributes
    private $ID_Gasto;
    private $ID_Proyecto;
    private $ID_Item;
    private $ID_Usuario;
    private $Fecha;
    private $Monto_Gasto;
    private $Comprobante;
    private $Observacion;

    private $conexion;

    // Constructor
    public function __construct() {
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->conectar();
    }

    // Getters and Setters
    public function getID_Gasto() {
        return $this->ID_Gasto;
    }

    public function setID_Gasto($ID_Gasto) {
        $this->ID_Gasto = $ID_Gasto;
    }

    public function getID_Proyecto() {
        return $this->ID_Proyecto;
    }

    public function setID_Proyecto($ID_Proyecto) {
        $this->ID_Proyecto = $ID_Proyecto;
    }

    public function getID_Item() {
        return $this->ID_Item;
    }

    public function setID_Item($ID_Item) {
        $this->ID_Item = $ID_Item;
    }

    public function getID_Usuario() {
        return $this->ID_Usuario;
    }

    public function setID_Usuario($ID_Usuario) {
        $this->ID_Usuario = $ID_Usuario;
    }

    public function getFecha() {
        return $this->Fecha;
    }

    public function setFecha($Fecha) {
        $this->Fecha = $Fecha;
    }

    public function getMonto_Gasto() {
        return $this->Monto_Gasto;
    }

    public function setMonto_Gasto($Monto_Gasto) {
        $this->Monto_Gasto = $Monto_Gasto;
    }

    public function getComprobante() {
        return $this->Comprobante;
    }

    public function setComprobante($Comprobante) {
        $this->Comprobante = $Comprobante;
    }

    public function getObservacion() {
        return $this->Observacion;
    }

    public function setObservacion($Observacion) {
        $this->Observacion = $Observacion;
    }

    public function agregarGasto() {
        $sql = "INSERT INTO Gastos (ID_Proyecto, ID_Item, ID_Usuario, Fecha, Monto_Gasto, Comprobante, Observacion) 
                VALUES (:ID_Proyecto, :ID_Item, :ID_Usuario, :Fecha, :Monto_Gasto, :Comprobante, :Observacion)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':ID_Proyecto', $this->ID_Proyecto);
        $stmt->bindParam(':ID_Item', $this->ID_Item);
        $stmt->bindParam(':ID_Usuario', $this->ID_Usuario);
        $stmt->bindParam(':Fecha', $this->Fecha);
        $stmt->bindParam(':Monto_Gasto', $this->Monto_Gasto);
        $stmt->bindParam(':Comprobante', $this->Comprobante);
        $stmt->bindParam(':Observacion', $this->Observacion);
        return $stmt->execute();
    }

    public function buscarTodos() {
        try {
            $sql = "SELECT * 
                    FROM Gastos";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al buscar todos los gastos: " . $e->getMessage(), 0);
            return array();
        }
    }

    public function buscarGastoPorID($ID_Gasto) {
        $sql = "SELECT * 
                FROM Gastos 
                WHERE ID_Gasto = :ID_Gasto";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':ID_Gasto', $ID_Gasto);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarGasto() {
        $sql = "UPDATE Gastos 
                SET ID_Proyecto = :ID_Proyecto, ID_Item = :ID_Item, ID_Usuario = :ID_Usuario, Fecha = :Fecha, Monto_Gasto = :Monto_Gasto, Comprobante = :Comprobante, Observacion = :Observacion 
                WHERE ID_Gasto = :ID_Gasto";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':ID_Proyecto', $this->ID_Proyecto);
        $stmt->bindParam(':ID_Item', $this->ID_Item);
        $stmt->bindParam(':ID_Usuario', $this->ID_Usuario);
        $stmt->bindParam(':Fecha', $this->Fecha);
        $stmt->bindParam(':Monto_Gasto', $this->Monto_Gasto);
        $stmt->bindParam(':Comprobante', $this->Comprobante);
        $stmt->bindParam(':Observacion', $this->Observacion);
        $stmt->bindParam(':ID_Gasto', $this->ID_Gasto);
        return $stmt->execute();
    }

    public function eliminarGasto() {
        $sql = "DELETE 
                FROM Gastos 
                WHERE ID_Gasto = :ID_Gasto";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':ID_Gasto', $this->ID_Gasto);
        return $stmt->execute();
    }

    public function obtenerListaProyectos() {
        $sql = "SELECT id_proyecto, nombre 
                FROM proyecto";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerListaCategorias() {
        $sql = "SELECT id_categoria, nombre 
                FROM categoria";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerListaItems() {
        $sql = "SELECT id_item, nombre 
                FROM item";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>