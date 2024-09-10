<?php
require_once("../modelo/conexionPDO.php");

class Reportes extends Conexion {
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->conectar();
    }

    public function obtenerDatosProyectos() {
        $sql = "SELECT p.ID_Proyecto, p.Nombre, p.Estado, 
                       COALESCE(pb.monto_presupuesto, 0) AS Presupuesto, 
                       COALESCE(g.monto_gasto, 0) AS Gastado
                FROM proyecto p
                LEFT JOIN (SELECT ID_Proyecto, SUM(monto_presupuesto) AS monto_presupuesto 
                           FROM presupuesto 
                           GROUP BY ID_Proyecto) pb ON p.ID_Proyecto = pb.ID_Proyecto
                LEFT JOIN (SELECT ID_Proyecto, SUM(Monto_Gasto) AS monto_gasto 
                           FROM gasto 
                           GROUP BY ID_Proyecto) g ON p.ID_Proyecto = g.ID_Proyecto";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTotalPresupuesto() {
        $sql = "SELECT COALESCE(SUM(monto_presupuesto), 0) AS TotalPresupuesto
                FROM (SELECT ID_Proyecto, SUM(monto_presupuesto) AS monto_presupuesto 
                      FROM presupuesto 
                      GROUP BY ID_Proyecto) pb";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function obtenerTotalGastado() {
        $sql = "SELECT COALESCE(SUM(Monto_Gasto), 0) AS TotalGastado
                FROM (SELECT ID_Proyecto, SUM(Monto_Gasto) AS monto_gasto 
                      FROM gasto 
                      GROUP BY ID_Proyecto) g";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
?>