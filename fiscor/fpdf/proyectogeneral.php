<?php
require('../fpdf/fpdf.php');
require_once("../modelo/conexionPDO.php");

class Reportes extends Conexion {
    private $conexion;

    public function __construct() {
        parent::__construct();
        $this->conexion = parent::conectar();
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

    public function reporteg() {
        $pdf = new FPDF('P', 'mm', array(214, 275));
        $pdf->AliasNbPages();
        $pdf->SetMargins(5, 5, 5, true);
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(true, 20);

        // Título
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->SetTextColor(0, 89, 171); // Color azul
        $pdf->Cell(0, 10, utf8_decode('Lista de proyectos'), 0, 1, 'C');

        $proyectos = $this->obtenerDatosProyectos();
        $totalPresupuesto = $this->obtenerTotalPresupuesto();
        $totalGastado = $this->obtenerTotalGastado();

        $pdf->SetFillColor(0, 89, 171);
        $pdf->SetDrawColor(0, 89, 171);

        $pdf->SetFont('Helvetica', 'B', 14);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetXY(10, 50);

        $pdf->Cell(10, 10, utf8_decode('N°'), 1, 0, 'C', 1);
        $pdf->Cell(30, 10, utf8_decode('Estatus'), 1, 0, 'C', 1);
        $pdf->Cell(60, 10, utf8_decode('Proyecto'), 1, 0, 'C', 1);
        $pdf->Cell(45, 10, utf8_decode('Presupuesto'), 1, 0, 'C', 1);
        $pdf->Cell(45, 10, utf8_decode('Gastado'), 1, 1, 'C', 1);

        $pdf->SetFillColor(166, 212, 255);
        $pdf->SetDrawColor(0, 89, 171);

        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(0, 0, 0);

        $fill = false; // Manual fill state management

        foreach ($proyectos as $i => $proyecto) {
            $pdf->SetFillColor($fill ? 166 : 255, $fill ? 212 : 255, $fill ? 255 : 255);
            $fill = !$fill; // Toggle fill state

            $pdf->setX(10);
            $pdf->Cell(10, 10, utf8_decode($i + 1), 1, 0, 'C', 1);

            $estado = '';
            $estadoColor = [0, 0, 0]; // Default color (black)

            if ($proyecto['Estado'] == 1) {
                $estado = 'En Proceso';
                $estadoColor = [0, 89, 171]; // Blue
                $pdf->SetFont('Arial', 'B', 10); // Set bold font
            } elseif ($proyecto['Estado'] == 2) {
                $estado = 'Finalizado';
                $estadoColor = [26, 188, 156]; // Green
                $pdf->SetFont('Arial', 'B', 10); // Set bold font
            } else {
                $estado = 'Desconocido';
                $estadoColor = [0, 0, 0]; // Black
                $pdf->SetFont('Arial', 'B', 10); // Set bold font
            }

            $pdf->SetTextColor($estadoColor[0], $estadoColor[1], $estadoColor[2]);
            $pdf->Cell(30, 10, utf8_decode($estado), 1, 0, 'C', 1);

            $pdf->SetTextColor(0, 0, 0); // Reset to black
            $pdf->SetFont('Arial', '', 10); // Set normal font
            $pdf->Cell(60, 10, utf8_decode($proyecto['Nombre']), 1, 0, 'C', 1);
            $pdf->Cell(45, 10, utf8_decode(number_format($proyecto['Presupuesto'], 2, '.', ',')), 1, 0, 'C', 1);
            $pdf->Cell(45, 10, utf8_decode(number_format($proyecto['Gastado'], 2, '.', ',')), 1, 0, 'C', 1);

            $pdf->Ln(10);
        }

        $pdf->Output('I', 'Proyectos.pdf');
    }
}

$reporte = new Reportes();
$reporte->reporteg();
?>
